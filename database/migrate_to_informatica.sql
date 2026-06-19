-- =============================================================================
-- Migração: Oficina de Mecânica → Sistema de Gestão de Assistência Técnica
-- Versão: 3.0.0
-- Execute ONCE numa base de dados `manutencao` existente.
-- Seguro para re-executar (usa IF NOT EXISTS / IF EXISTS).
-- =============================================================================

USE `manutencao`;

-- =============================================================================
-- PARTE 1: Renomear tabela mecanicos → tecnicos
-- =============================================================================
SET @existe_mecanicos = (SELECT COUNT(*) FROM information_schema.tables
    WHERE table_schema = 'manutencao' AND table_name = 'mecanicos');

SET @existe_tecnicos = (SELECT COUNT(*) FROM information_schema.tables
    WHERE table_schema = 'manutencao' AND table_name = 'tecnicos');

-- Criar tecnicos se ainda não existir
CREATE TABLE IF NOT EXISTS `tecnicos` (
  `idtecnico`  INT(11)      NOT NULL AUTO_INCREMENT,
  `nbi`        VARCHAR(20)  DEFAULT NULL,
  `nif`        VARCHAR(20)  DEFAULT NULL,
  `nome`       VARCHAR(100) NOT NULL,
  `sobrenome`  VARCHAR(100) DEFAULT NULL,
  `email`      VARCHAR(150) DEFAULT NULL,
  `telefone`   VARCHAR(30)  DEFAULT NULL,
  `morada`     VARCHAR(200) DEFAULT NULL,
  `created`    DATETIME     DEFAULT CURRENT_TIMESTAMP,
  `foto`       VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`idtecnico`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Migrar dados de mecanicos → tecnicos (se mecanicos existir)
INSERT IGNORE INTO `tecnicos` (`idtecnico`, `nbi`, `nif`, `nome`, `sobrenome`, `email`, `telefone`, `morada`, `created`, `foto`)
SELECT `idmecanicos`, `nbi`, `nif`, `nome`, `sobrenome`, `email`, `telefone`, `morada`, `created`, `foto`
FROM `mecanicos`
WHERE (SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='manutencao' AND table_name='mecanicos') > 0;

-- =============================================================================
-- PARTE 2: Renomear coluna orcamentos.mecanico → orcamentos.tecnico
-- =============================================================================

-- Adicionar nova coluna tecnico (se não existir)
SET @col_exists = (SELECT COUNT(*) FROM information_schema.columns
    WHERE table_schema='manutencao' AND table_name='orcamentos' AND column_name='tecnico');

-- Copiar dados mecanico → tecnico
ALTER TABLE `orcamentos`
    ADD COLUMN IF NOT EXISTS `tecnico` VARCHAR(100) DEFAULT NULL AFTER `garantia`;

UPDATE `orcamentos` SET `tecnico` = `mecanico` WHERE `tecnico` IS NULL AND `mecanico` IS NOT NULL;

-- Nota: a coluna `mecanico` é mantida como legado para compatibilidade.
-- Para removê-la após confirmar estabilidade: ALTER TABLE orcamentos DROP COLUMN mecanico;

-- =============================================================================
-- PARTE 3: Renomear coluna conntas_areceber.mecanico → .tecnico
-- =============================================================================

ALTER TABLE `conntas_areceber`
    ADD COLUMN IF NOT EXISTS `tecnico` VARCHAR(100) DEFAULT NULL AFTER `adiantameto`;

UPDATE `conntas_areceber` SET `tecnico` = `mecanico` WHERE `tecnico` IS NULL AND `mecanico` IS NOT NULL;

-- =============================================================================
-- PARTE 4: Renomear nifmecanico → niftecnico nas tabelas de entrada e comissão
-- =============================================================================

-- comissao
ALTER TABLE `comissao`
    ADD COLUMN IF NOT EXISTS `niftecnico` VARCHAR(30) DEFAULT NULL;

UPDATE `comissao` SET `niftecnico` = `nifmecanico` WHERE `niftecnico` IS NULL AND `nifmecanico` IS NOT NULL;

-- entrada_equipamento (criada na PARTE 7 — já inclui niftecnico, nada a fazer aqui)

-- entrada_veiculo (legado)
ALTER TABLE `entrada_veiculo`
    ADD COLUMN IF NOT EXISTS `niftecnico` VARCHAR(30) DEFAULT NULL;

UPDATE `entrada_veiculo` SET `niftecnico` = `nifmecanico` WHERE `niftecnico` IS NULL AND `nifmecanico` IS NOT NULL;

-- =============================================================================
-- PARTE 5: Atualizar nivel='mecanico' → 'tecnico' na tabela usuario
-- =============================================================================

UPDATE `usuario` SET `nivel` = 'tecnico' WHERE `nivel` = 'mecanico';

-- Atualizar o valor padrão da coluna nivel
ALTER TABLE `usuario` MODIFY COLUMN `nivel` VARCHAR(20) NOT NULL DEFAULT 'tecnico';

-- =============================================================================
-- PARTE 6: Criar tabela equipamento (se não existir) e migrar de veiculo
-- =============================================================================

CREATE TABLE IF NOT EXISTS `equipamento` (
  `idequipamento`       INT(11)      NOT NULL AUTO_INCREMENT,
  `idcliente`           INT(11)      DEFAULT NULL,
  `numero_serie`        VARCHAR(100) NOT NULL,
  `imei`                VARCHAR(50)  DEFAULT NULL,
  `tipo_equipamento`    VARCHAR(50)  DEFAULT 'Computador',
  `marca`               VARCHAR(80)  DEFAULT NULL,
  `modelo`              VARCHAR(80)  DEFAULT NULL,
  `estado`              VARCHAR(50)  DEFAULT 'Recebido',
  `defeito_reportado`   TEXT         DEFAULT NULL,
  `diagnostico_tecnico` TEXT         DEFAULT NULL,
  `garantia_reparacao`  VARCHAR(100) DEFAULT NULL,
  `foto_antes`          VARCHAR(255) DEFAULT NULL,
  `foto_depois`         VARCHAR(255) DEFAULT NULL,
  `dataregisto`         DATE         DEFAULT NULL,
  `created`             DATETIME     DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idequipamento`),
  UNIQUE KEY `numero_serie` (`numero_serie`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Migrar dados de veiculo → equipamento
INSERT IGNORE INTO `equipamento` (`idequipamento`, `idcliente`, `numero_serie`, `marca`, `modelo`, `dataregisto`, `created`)
SELECT `idveiculo`, `idcliente`,
       COALESCE(NULLIF(`matricula`, ''), CONCAT('MIGRADO-', `idveiculo`)),
       `marca`, `modelo`, `dataregisto`, `created`
FROM `veiculo`
WHERE (SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='manutencao' AND table_name='veiculo') > 0;

-- =============================================================================
-- PARTE 7: Criar tabela entrada_equipamento (se não existir) e migrar
-- =============================================================================

CREATE TABLE IF NOT EXISTS `entrada_equipamento` (
  `id`               INT(11)      NOT NULL AUTO_INCREMENT,
  `numero_serie`     VARCHAR(100) DEFAULT NULL,
  `tipo_equipamento` VARCHAR(50)  DEFAULT NULL,
  `marca`            VARCHAR(80)  DEFAULT NULL,
  `modelo`           VARCHAR(80)  DEFAULT NULL,
  `estado`           VARCHAR(50)  DEFAULT 'Recebido',
  `cliente`          VARCHAR(100) DEFAULT NULL,
  `niftecnico`       VARCHAR(30)  DEFAULT NULL,
  `servico`          VARCHAR(150) DEFAULT NULL,
  `data_entrada`     DATE         DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `entrada_equipamento` (`id`, `numero_serie`, `marca`, `cliente`, `niftecnico`, `servico`, `data_entrada`)
SELECT `id`,
       COALESCE(NULLIF(`matricula`, ''), CONCAT('MIGRADO-', `id`)),
       `modelo`, `cliente`, `nifmecanico`, `servico`, `data_entrada`
FROM `entrada_veiculo`
WHERE (SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='manutencao' AND table_name='entrada_veiculo') > 0;

-- =============================================================================
-- PARTE 8: Recriar todas as VIEWS
-- =============================================================================

CREATE OR REPLACE VIEW `dadosClienteEquipamento` AS
SELECT
  e.idequipamento, e.numero_serie, e.imei, e.tipo_equipamento,
  e.marca, e.modelo, e.estado, e.defeito_reportado, e.diagnostico_tecnico,
  e.garantia_reparacao, e.foto_antes, e.foto_depois, e.dataregisto,
  c.idclientes, c.nbi, c.nif, c.nome, c.sobrenome, c.email, c.telefone, c.morada
FROM equipamento e LEFT JOIN clientes c ON c.idclientes = e.idcliente;

CREATE OR REPLACE VIEW `dadosClienteVeiculo` AS
SELECT
  idequipamento AS idveiculo, numero_serie AS matricula, numero_serie,
  imei, tipo_equipamento, marca, modelo, estado, defeito_reportado,
  diagnostico_tecnico, garantia_reparacao, foto_antes, foto_depois,
  dataregisto, idclientes, nbi, nif, nome, sobrenome, email, telefone, morada
FROM dadosClienteEquipamento;

CREATE OR REPLACE VIEW `dadosorcamento` AS
SELECT
  o.idorcamentos,
  o.veiculo        AS numero_serie,
  o.veiculo        AS matricula,
  o.id_tipo_servico AS idtipo_servico,
  o.valor, o.data AS data_orcamento, o.data_entrega, o.garantia,
  o.tecnico, o.tecnico AS mecanico,
  o.descricao, o.obs, o.status, o.tipo, o.created,
  ts.nome AS tipo_servico,
  c.nif, c.nbi, c.nome AS nome_cliente, c.sobrenome, c.email, c.telefone, c.morada,
  e.marca, e.modelo, e.tipo_equipamento, e.estado, e.imei,
  e.defeito_reportado, e.diagnostico_tecnico,
  NULL AS cor, NULL AS nmotor, NULL AS nquadro, NULL AS pesobruto,
  NULL AS medidapeneu, NULL AS cilindrada, NULL AS ncilindros,
  NULL AS tipocaixa, NULL AS combustivel, NULL AS distanciaeixo, NULL AS lotacao
FROM orcamentos o
LEFT JOIN equipamento e ON e.numero_serie = o.veiculo
LEFT JOIN clientes c ON c.idclientes = e.idcliente
LEFT JOIN tipo_servico ts ON ts.idtipo_servico = o.id_tipo_servico;

CREATE OR REPLACE VIEW `dadosOrcamentosCompletoComProdutos` AS
SELECT
  o.idorcamentos, o.veiculo AS numero_serie, o.veiculo AS matricula,
  o.valor AS valor_servico, o.data AS data_orcamento, o.status, o.tipo,
  o.tecnico, o.tecnico AS mecanico, o.descricao,
  op.idorc_prod, op.quantidade,
  p.idproduto, p.nome AS produto, p.nome AS nome_produto,
  p.valor_venda, p.referencia,
  c.nif, c.nome AS nome_cliente, c.sobrenome,
  ts.nome AS tipo_servico
FROM orcamentos o
INNER JOIN orc_prod op ON op.orcamentos = o.idorcamentos
INNER JOIN produto p ON p.idproduto = op.produtos
LEFT JOIN equipamento e ON e.numero_serie = o.veiculo
LEFT JOIN clientes c ON c.idclientes = e.idcliente
LEFT JOIN tipo_servico ts ON ts.idtipo_servico = o.id_tipo_servico;

-- =============================================================================
-- Confirmação
-- =============================================================================
SELECT 'Migração para Sistema de Gestão de Assistência Técnica Informática: CONCLUÍDA' AS status;
