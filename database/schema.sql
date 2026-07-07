-- ============================================================
-- Oficina de Equipamentos Informáticos — Schema da Base de Dados
-- Sistema de Gestão de Assistência Técnica Informática
-- Versão: 3.0.0
-- Charset: utf8mb4
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------------
-- Utilizadores do sistema (administradores, técnicos, recep.)
-- nivel: 'adimin' | 'tecnico' | 'recep'
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `usuario` (
  `idusuario`  INT(11)      NOT NULL AUTO_INCREMENT,
  `nbi`        VARCHAR(20)  DEFAULT NULL,
  `nif`        VARCHAR(20)  DEFAULT NULL,
  `nome`       VARCHAR(100) NOT NULL,
  `sobrenome`  VARCHAR(100) DEFAULT NULL,
  `email`      VARCHAR(150) NOT NULL,
  `telefone`   VARCHAR(30)  DEFAULT NULL,
  `senha`      VARCHAR(255) NOT NULL,
  `nivel`      VARCHAR(20)  NOT NULL DEFAULT 'tecnico',
  `st_conta`   VARCHAR(20)  NOT NULL DEFAULT 'Ativada',
  `foto`       VARCHAR(255) DEFAULT NULL,
  `created`    DATETIME     DEFAULT CURRENT_TIMESTAMP,
  `modified`   DATETIME     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idusuario`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Log de acessos
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `control_usuario` (
  `id`         INT(11)      NOT NULL AUTO_INCREMENT,
  `id_usuario` INT(11)      NOT NULL,
  `data_hora`  DATETIME     DEFAULT CURRENT_TIMESTAMP,
  `accao`      VARCHAR(50)  DEFAULT 'Acessou',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Técnicos de Informática (antes: mecanicos)
-- ------------------------------------------------------------
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

-- ------------------------------------------------------------
-- Recepcionistas
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `recepcionista` (
  `idrecepcionista` INT(11)      NOT NULL AUTO_INCREMENT,
  `nbi`             VARCHAR(20)  DEFAULT NULL,
  `nif`             VARCHAR(20)  DEFAULT NULL,
  `nome`            VARCHAR(100) NOT NULL,
  `sobrenome`       VARCHAR(100) DEFAULT NULL,
  `email`           VARCHAR(150) DEFAULT NULL,
  `telefone`        VARCHAR(30)  DEFAULT NULL,
  `morada`          VARCHAR(200) DEFAULT NULL,
  `created`         DATETIME     DEFAULT CURRENT_TIMESTAMP,
  `foto`            VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`idrecepcionista`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Clientes
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `clientes` (
  `idclientes` INT(11)      NOT NULL AUTO_INCREMENT,
  `nbi`        VARCHAR(20)  DEFAULT NULL,
  `nif`        VARCHAR(20)  DEFAULT NULL,
  `nome`       VARCHAR(100) NOT NULL,
  `sobrenome`  VARCHAR(100) DEFAULT NULL,
  `email`      VARCHAR(150) DEFAULT NULL,
  `telefone`   VARCHAR(30)  DEFAULT NULL,
  `morada`     VARCHAR(200) DEFAULT NULL,
  `created`    DATETIME     DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idclientes`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Equipamentos Informáticos
-- tipo_equipamento: Computador, Portátil, Impressora, Servidor, Equipamento de Rede, Telemóvel, Tablet, Monitor, Outro
-- estado: Recebido, Em Diagnóstico, Aguardando Peças, Em Reparação, Concluído, Entregue
-- ------------------------------------------------------------
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
  UNIQUE KEY `numero_serie` (`numero_serie`),
  KEY `fk_equip_cliente` (`idcliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Alias de compatibilidade (veiculo → equipamento)
CREATE TABLE IF NOT EXISTS `veiculo` (
  `idveiculo`      INT(11)      NOT NULL AUTO_INCREMENT,
  `idcliente`      INT(11)      DEFAULT NULL,
  `matricula`      VARCHAR(30)  NOT NULL,
  `dataregisto`    DATE         DEFAULT NULL,
  `marca`          VARCHAR(80)  DEFAULT NULL,
  `modelo`         VARCHAR(80)  DEFAULT NULL,
  `nmotor`         VARCHAR(50)  DEFAULT NULL,
  `nquadro`        VARCHAR(50)  DEFAULT NULL,
  `cor`            VARCHAR(50)  DEFAULT NULL,
  `pesobruto`      VARCHAR(30)  DEFAULT NULL,
  `medidapeneu`    VARCHAR(30)  DEFAULT NULL,
  `cilindrada`     VARCHAR(30)  DEFAULT NULL,
  `ncilindros`     VARCHAR(10)  DEFAULT NULL,
  `tipocaixa`      VARCHAR(30)  DEFAULT NULL,
  `combustivel`    VARCHAR(30)  DEFAULT NULL,
  `distanciaeixo`  VARCHAR(30)  DEFAULT NULL,
  `lotacao`        VARCHAR(10)  DEFAULT NULL,
  `created`        DATETIME     DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idveiculo`),
  UNIQUE KEY `matricula` (`matricula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Fornecedores
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `fornecedor` (
  `idfornecedor` INT(11)      NOT NULL AUTO_INCREMENT,
  `nbi`          VARCHAR(20)  DEFAULT NULL,
  `nif`          VARCHAR(20)  DEFAULT NULL,
  `nome`         VARCHAR(100) NOT NULL,
  `email`        VARCHAR(150) DEFAULT NULL,
  `telefone`     VARCHAR(30)  DEFAULT NULL,
  `morada`       VARCHAR(200) DEFAULT NULL,
  `tipo_pessoa`  VARCHAR(20)  DEFAULT 'Singular',
  `created`      DATETIME     DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idfornecedor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Categorias de produtos / peças
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `categoria` (
  `idcategoria` INT(11)      NOT NULL AUTO_INCREMENT,
  `nome`        VARCHAR(100) NOT NULL,
  `created`     DATETIME     DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idcategoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Produtos / Peças e Componentes
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `produto` (
  `idproduto`    INT(11)        NOT NULL AUTO_INCREMENT,
  `idfornecedor` INT(11)        DEFAULT NULL,
  `idcategoria`  INT(11)        DEFAULT NULL,
  `referencia`   VARCHAR(100)   DEFAULT NULL,
  `nome`         VARCHAR(150)   NOT NULL,
  `valor_compra` DECIMAL(12,2)  DEFAULT 0.00,
  `valor_venda`  DECIMAL(12,2)  DEFAULT 0.00,
  `estoque`      INT(11)        DEFAULT 0,
  `descricao`    TEXT           DEFAULT NULL,
  `created`      DATETIME       DEFAULT CURRENT_TIMESTAMP,
  `modified`     DATETIME       DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `foto`         VARCHAR(255)   DEFAULT NULL,
  PRIMARY KEY (`idproduto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Tipos de serviço técnico
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tipo_servico` (
  `idtipo_servico` INT(11)        NOT NULL AUTO_INCREMENT,
  `nome`           VARCHAR(100)   NOT NULL,
  `valor`          DECIMAL(12,2)  DEFAULT 0.00,
  `created`        DATETIME       DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idtipo_servico`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Orçamentos / Ordens de Serviço
-- O campo `veiculo` guarda o número de série do equipamento (legado)
-- O campo `tecnico` guarda o NIF do técnico responsável (antes: mecanico)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `ocorrencias` (
  `idocorrencia`     INT(11)        NOT NULL AUTO_INCREMENT,
  `id_orcamento`     INT(11)        DEFAULT NULL,
  `id_equipamento`   INT(11)        DEFAULT NULL,
  `id_tipo_servico`  INT(11)        DEFAULT NULL,
  `tecnico`          VARCHAR(100)   DEFAULT NULL,
  `tipo_manutencao`  VARCHAR(30)    DEFAULT 'Corretiva',
  `descricao`        TEXT           DEFAULT NULL,
  `estado`           VARCHAR(30)    DEFAULT 'Aberta',
  `data_abertura`    DATE           DEFAULT NULL,
  `data_prevista`    DATE           DEFAULT NULL,
  `data_encerramento` DATE          DEFAULT NULL,
  `observacoes`      TEXT           DEFAULT NULL,
  `status`           VARCHAR(30)    DEFAULT 'Aberta',
  `created`          DATETIME       DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idocorrencia`),
  KEY `idx_ocorrencias_data_prevista` (`data_prevista`),
  KEY `idx_ocorrencias_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `orcamentos` (
  `idorcamentos`    INT(11)        NOT NULL AUTO_INCREMENT,
  `veiculo`         VARCHAR(100)   DEFAULT NULL,
  `id_tipo_servico` INT(11)        DEFAULT NULL,
  `valor`           DECIMAL(12,2)  DEFAULT 0.00,
  `data`            DATE           DEFAULT NULL,
  `data_entrega`    DATE           DEFAULT NULL,
  `garantia`        VARCHAR(100)   DEFAULT NULL,
  `tecnico`         VARCHAR(100)   DEFAULT NULL,
  `descricao`       TEXT           DEFAULT NULL,
  `obs`             TEXT           DEFAULT NULL,
  `status`          VARCHAR(20)    DEFAULT 'Aberto',
  `tipo`            VARCHAR(20)    DEFAULT 'Orçamento',
  `created`         DATETIME       DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idorcamentos`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Produtos / Peças associados a orçamentos
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `orc_prod` (
  `idorc_prod` INT(11)  NOT NULL AUTO_INCREMENT,
  `orcamentos` INT(11)  DEFAULT NULL,
  `produtos`   INT(11)  DEFAULT NULL,
  `quantidade` INT(11)  DEFAULT 1,
  `data`       DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idorc_prod`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Contas a pagar
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `contas_apagar` (
  `idcontas_apagar` INT(11)        NOT NULL AUTO_INCREMENT,
  `descricao`       TEXT           DEFAULT NULL,
  `valor`           DECIMAL(12,2)  DEFAULT 0.00,
  `funcionario`     VARCHAR(100)   DEFAULT NULL,
  `data_venci`      DATE           DEFAULT NULL,
  `foto`            VARCHAR(255)   DEFAULT NULL,
  `pago`            VARCHAR(5)     DEFAULT 'NAO',
  `nome`            VARCHAR(100)   DEFAULT NULL,
  `created`         DATETIME       DEFAULT CURRENT_TIMESTAMP,
  `modified`        DATETIME       DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idcontas_apagar`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Contas a receber
-- tecnico: NIF do técnico responsável (antes: mecanico)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `conntas_areceber` (
  `idconntas_areceber` INT(11)        NOT NULL AUTO_INCREMENT,
  `idorcamentos`       INT(11)        DEFAULT NULL,
  `descricao`          TEXT           DEFAULT NULL,
  `adiantameto`        DECIMAL(12,2)  DEFAULT 0.00,
  `tecnico`            VARCHAR(100)   DEFAULT NULL,
  `cliente`            VARCHAR(100)   DEFAULT NULL,
  `data`               DATETIME       DEFAULT CURRENT_TIMESTAMP,
  `valortotal`         DECIMAL(12,2)  DEFAULT 0.00,
  `pago`               VARCHAR(3)     NOT NULL DEFAULT 'nao',
  PRIMARY KEY (`idconntas_areceber`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Movimentação financeira (caixa)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `movimentacao` (
  `id`          INT(11)        NOT NULL AUTO_INCREMENT,
  `tipo`        VARCHAR(20)    DEFAULT NULL,
  `descricao`   TEXT           DEFAULT NULL,
  `valor`       DECIMAL(12,2)  DEFAULT 0.00,
  `funcionario` VARCHAR(100)   DEFAULT NULL,
  `data`        DATE           DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Compras a fornecedores
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `compras` (
  `idcompras`          INT(11)        NOT NULL AUTO_INCREMENT,
  `funcionario`        VARCHAR(100)   DEFAULT NULL,
  `idcontas_apagar`    INT(11)        DEFAULT NULL,
  `idproduto`          INT(11)        DEFAULT NULL,
  `produto`            VARCHAR(150)   DEFAULT NULL,
  `valor`              DECIMAL(12,2)  DEFAULT 0.00,
  `quantidade_estoque` INT(11)        DEFAULT 0,
  `estoque`            INT(11)        DEFAULT 0,
  `data`               DATETIME       DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idcompras`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Vendas de produtos / peças
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `vendas` (
  `idvendas`    INT(11)        NOT NULL AUTO_INCREMENT,
  `funcionario` VARCHAR(100)   DEFAULT NULL,
  `produto`     VARCHAR(150)   DEFAULT NULL,
  `valor`       DECIMAL(12,2)  DEFAULT 0.00,
  `data`        DATETIME       DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idvendas`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Comissões dos técnicos (antes: mecanicos)
-- niftecnico: NIF do técnico (antes: nifmecanico)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `comissao` (
  `id`         INT(11)        NOT NULL AUTO_INCREMENT,
  `valor`      DECIMAL(12,2)  DEFAULT 0.00,
  `servico`    VARCHAR(150)   DEFAULT NULL,
  `tipo`       VARCHAR(30)    DEFAULT NULL,
  `data`       DATE           DEFAULT NULL,
  `niftecnico` VARCHAR(30)    DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Entrada de equipamentos na oficina
-- niftecnico: NIF do técnico responsável (antes: nifmecanico)
-- ------------------------------------------------------------
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

-- Alias de compatibilidade (entrada_veiculo → entrada_equipamento)
CREATE TABLE IF NOT EXISTS `entrada_veiculo` (
  `id`           INT(11)      NOT NULL AUTO_INCREMENT,
  `modelo`       VARCHAR(80)  DEFAULT NULL,
  `matricula`    VARCHAR(30)  DEFAULT NULL,
  `cliente`      VARCHAR(100) DEFAULT NULL,
  `niftecnico`   VARCHAR(30)  DEFAULT NULL,
  `servico`      VARCHAR(150) DEFAULT NULL,
  `data_entrada` DATE         DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Tokens de recuperação de senha
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `reset_senha` (
  `id`         INT(11)      NOT NULL AUTO_INCREMENT,
  `email`      VARCHAR(150) NOT NULL,
  `codigo`     VARCHAR(6)   NOT NULL,
  `expira_em`  DATETIME     NOT NULL,
  `usado`      TINYINT(1)   DEFAULT 0,
  `created_at` DATETIME     DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_email_codigo` (`email`, `codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- VIEWS
-- ============================================================

-- View: produto + fornecedor + categoria
CREATE OR REPLACE VIEW `dadosProduto` AS
SELECT
  p.idproduto,
  p.referencia,
  p.nome,
  p.valor_compra,
  p.valor_venda,
  p.estoque,
  p.descricao,
  p.foto,
  p.created,
  c.idcategoria,
  c.nome      AS categoria,
  f.idfornecedor,
  f.nome      AS fornecedor,
  f.nif,
  f.tipo_pessoa,
  f.telefone,
  f.email,
  f.morada
FROM produto p
LEFT JOIN categoria c ON c.idcategoria = p.idcategoria
LEFT JOIN fornecedor f ON f.idfornecedor = p.idfornecedor;

-- View: orçamentos completos com dados do cliente, equipamento e tipo de serviço
-- tecnico: NIF do técnico responsável (antes: mecanico)
CREATE OR REPLACE VIEW `dadosorcamento` AS
SELECT
  o.idorcamentos,
  o.veiculo         AS numero_serie,
  o.veiculo         AS matricula,
  o.id_tipo_servico AS idtipo_servico,
  o.valor,
  o.data            AS data_orcamento,
  o.data_entrega,
  o.garantia,
  o.tecnico,
  o.tecnico         AS mecanico,
  o.descricao,
  o.obs,
  o.status,
  o.tipo,
  o.created,
  ts.nome           AS tipo_servico,
  c.nif,
  c.nbi,
  c.nome            AS nome_cliente,
  c.sobrenome,
  c.email,
  c.telefone,
  c.morada,
  e.marca,
  e.modelo,
  e.tipo_equipamento,
  e.estado,
  e.imei,
  e.defeito_reportado,
  e.diagnostico_tecnico,
  -- Campos legacy (compatibilidade)
  NULL AS cor,
  NULL AS nmotor,
  NULL AS nquadro,
  NULL AS pesobruto,
  NULL AS medidapeneu,
  NULL AS cilindrada,
  NULL AS ncilindros,
  NULL AS tipocaixa,
  NULL AS combustivel,
  NULL AS distanciaeixo,
  NULL AS lotacao
FROM orcamentos o
LEFT JOIN equipamento e ON e.numero_serie = o.veiculo
LEFT JOIN clientes c ON c.idclientes = e.idcliente
LEFT JOIN tipo_servico ts ON ts.idtipo_servico = o.id_tipo_servico;

-- View: clientes + equipamentos
CREATE OR REPLACE VIEW `dadosClienteEquipamento` AS
SELECT
  e.idequipamento,
  e.numero_serie,
  e.imei,
  e.tipo_equipamento,
  e.marca,
  e.modelo,
  e.estado,
  e.defeito_reportado,
  e.diagnostico_tecnico,
  e.garantia_reparacao,
  e.foto_antes,
  e.foto_depois,
  e.dataregisto,
  c.idclientes,
  c.nbi,
  c.nif,
  c.nome,
  c.sobrenome,
  c.email,
  c.telefone,
  c.morada
FROM equipamento e
LEFT JOIN clientes c ON c.idclientes = e.idcliente;

-- View: compatibilidade com código legado (dadosClienteVeiculo)
CREATE OR REPLACE VIEW `dadosClienteVeiculo` AS
SELECT
  idequipamento  AS idveiculo,
  numero_serie   AS matricula,
  imei,
  tipo_equipamento,
  marca,
  modelo,
  estado,
  defeito_reportado,
  diagnostico_tecnico,
  garantia_reparacao,
  foto_antes,
  foto_depois,
  dataregisto,
  idclientes,
  nbi,
  nif,
  nome,
  sobrenome,
  email,
  telefone,
  morada
FROM dadosClienteEquipamento;

-- View: orçamentos com peças associadas
CREATE OR REPLACE VIEW `dadosOrcamentosCompletoComProdutos` AS
SELECT
  o.idorcamentos,
  o.veiculo        AS numero_serie,
  o.veiculo        AS matricula,
  o.valor          AS valor_servico,
  o.data           AS data_orcamento,
  o.status,
  o.tipo,
  o.tecnico,
  o.tecnico        AS mecanico,
  o.descricao,
  op.idorc_prod,
  op.quantidade,
  p.idproduto,
  p.nome           AS produto,
  p.nome           AS nome_produto,
  p.valor_venda,
  p.referencia,
  c.nif,
  c.nome           AS nome_cliente,
  c.sobrenome,
  ts.nome          AS tipo_servico
FROM orcamentos o
INNER JOIN orc_prod op ON op.orcamentos = o.idorcamentos
INNER JOIN produto p ON p.idproduto = op.produtos
LEFT JOIN equipamento e ON e.numero_serie = o.veiculo
LEFT JOIN clientes c ON c.idclientes = e.idcliente
LEFT JOIN tipo_servico ts ON ts.idtipo_servico = o.id_tipo_servico;

-- View: compras de produto associadas a contas a pagar
CREATE OR REPLACE VIEW `compras_contaspagar_dadosproduto` AS
SELECT
  c.idcontas_apagar,
  ca.pago,
  ca.data_venci,
  p.foto,
  p.nome        AS produto,
  cat.nome      AS categoria,
  p.referencia,
  c.quantidade_estoque,
  c.valor       AS valor_compras,
  (c.valor * c.quantidade_estoque) AS total_apagar,
  f.nome        AS fornecedor
FROM compras c
LEFT JOIN contas_apagar ca  ON ca.idcontas_apagar = c.idcontas_apagar
LEFT JOIN produto p         ON p.idproduto        = c.idproduto
LEFT JOIN categoria cat     ON cat.idcategoria    = p.idcategoria
LEFT JOIN fornecedor f      ON f.idfornecedor     = p.idfornecedor;

-- ============================================================
-- DADOS INICIAIS
-- ============================================================

-- Utilizador administrador padrão
-- Senha: 12345 (MD5)
INSERT IGNORE INTO `usuario`
  (nbi, nif, nome, sobrenome, email, telefone, senha, nivel, st_conta, created)
VALUES
  ('ALDADL1222334', 'ALDADL1222334', 'António', 'Jacinto', 'antjacinto11672@gmail.com', '937585960',
   '827ccb0eea8a706c4c34a16891f84e7b', 'adimin', 'Ativada', NOW());

SET FOREIGN_KEY_CHECKS = 1;
