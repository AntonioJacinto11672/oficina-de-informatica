-- ============================================================
-- Sistema de Gestão de Manutenção Preventiva e Corretiva de
-- Equipamentos Informáticos — Universidade Lusíada de Angola
-- Versão: 5.0.0 (adaptação institucional — ver CHANGELOG.md)
-- Charset: utf8mb4
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------------
-- Utilizadores do sistema
-- nivel: 'gerente' (Gerente de TI, acesso total) | 'tecnico' (Técnico de Informática)
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
  `nivel`      ENUM('gerente','tecnico') NOT NULL DEFAULT 'tecnico',
  `st_conta`   VARCHAR(20)  NOT NULL DEFAULT 'Ativada',
  `foto`       VARCHAR(255) DEFAULT NULL,
  `created`    DATETIME     DEFAULT CURRENT_TIMESTAMP,
  `modified`   DATETIME     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idusuario`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Controlo de migrações — ver migrate.php e database/migrations/.
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `schema_migrations` (
  `id`         INT(11)      NOT NULL AUTO_INCREMENT,
  `migration`  VARCHAR(191) NOT NULL,
  `applied_at` DATETIME     DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `migration` (`migration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Log de acessos ao sistema
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `control_usuario` (
  `id`         INT(11)      NOT NULL AUTO_INCREMENT,
  `id_usuario` INT(11)      NOT NULL,
  `data_hora`  DATETIME     DEFAULT CURRENT_TIMESTAMP,
  `accao`      VARCHAR(50)  DEFAULT 'Acesso',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Técnicos de Informática (ficha, ligada à conta de login em `usuario`)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tecnicos` (
  `idtecnico`  INT(11)      NOT NULL AUTO_INCREMENT,
  `idusuario`  INT(11)      DEFAULT NULL,
  `nbi`        VARCHAR(20)  DEFAULT NULL,
  `nif`        VARCHAR(20)  DEFAULT NULL,
  `nome`       VARCHAR(100) NOT NULL,
  `sobrenome`  VARCHAR(100) DEFAULT NULL,
  `email`      VARCHAR(150) DEFAULT NULL,
  `telefone`   VARCHAR(30)  DEFAULT NULL,
  `morada`     VARCHAR(200) DEFAULT NULL,
  `created`    DATETIME     DEFAULT CURRENT_TIMESTAMP,
  `foto`       VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`idtecnico`),
  KEY `fk_tecnicos_usuario` (`idusuario`),
  CONSTRAINT `fk_tecnicos_usuario` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Departamentos da Universidade (a quem os equipamentos pertencem)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `departamentos` (
  `iddepartamento` INT(11)      NOT NULL AUTO_INCREMENT,
  `nome`           VARCHAR(150) NOT NULL,
  `responsavel`    VARCHAR(150) DEFAULT NULL,
  `telefone`       VARCHAR(30)  DEFAULT NULL,
  `email`          VARCHAR(150) DEFAULT NULL,
  `observacoes`    TEXT         DEFAULT NULL,
  `created`        DATETIME     DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`iddepartamento`),
  UNIQUE KEY `uq_departamento_nome` (`nome`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Categorias/Tipos de Equipamento
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `categoria_equipamento` (
  `idcategoria_equipamento` INT(11)      NOT NULL AUTO_INCREMENT,
  `nome`                    VARCHAR(100) NOT NULL,
  `created`                 DATETIME     DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idcategoria_equipamento`),
  UNIQUE KEY `uq_categoria_equipamento_nome` (`nome`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Fornecedores — fornecem equipamentos, peças e consumíveis à
-- Universidade. Sem qualquer funcionalidade comercial (não há venda).
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `fornecedor` (
  `idfornecedor` INT(11)      NOT NULL AUTO_INCREMENT,
  `nif`          VARCHAR(20)  DEFAULT NULL,
  `nome`         VARCHAR(150) NOT NULL,
  `email`        VARCHAR(150) DEFAULT NULL,
  `telefone`     VARCHAR(30)  DEFAULT NULL,
  `morada`       VARCHAR(200) DEFAULT NULL,
  `tipo_pessoa`  VARCHAR(20)  DEFAULT 'Coletiva',
  `created`      DATETIME     DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idfornecedor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Equipamentos Informáticos da Universidade
-- estado: Disponível | Em Manutenção | Avariado | Abatido
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `equipamento` (
  `idequipamento`           INT(11)      NOT NULL AUTO_INCREMENT,
  `codigo`                  VARCHAR(50)  DEFAULT NULL,
  `patrimonio`              VARCHAR(50)  DEFAULT NULL,
  `nome`                    VARCHAR(150) NOT NULL,
  `iddepartamento`          INT(11)      DEFAULT NULL,
  `idresponsavel`           INT(11)      DEFAULT NULL,
  `localizacao`             VARCHAR(150) DEFAULT NULL,
  `numero_serie`            VARCHAR(100) NOT NULL,
  `idcategoria_equipamento` INT(11)      DEFAULT NULL,
  `marca`                   VARCHAR(80)  DEFAULT NULL,
  `modelo`                  VARCHAR(80)  DEFAULT NULL,
  `estado`                  ENUM('Disponível','Em Manutenção','Avariado','Abatido') NOT NULL DEFAULT 'Disponível',
  `idfornecedor`            INT(11)      DEFAULT NULL,
  `data_aquisicao`          DATE         DEFAULT NULL,
  `garantia_ate`            DATE         DEFAULT NULL,
  `diagnostico_tecnico`     TEXT         DEFAULT NULL,
  `foto`                    VARCHAR(255) DEFAULT NULL,
  `observacoes`             TEXT         DEFAULT NULL,
  `dataregisto`             DATE         DEFAULT NULL,
  `created`                 DATETIME     DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idequipamento`),
  UNIQUE KEY `numero_serie` (`numero_serie`),
  KEY `fk_equipamento_departamento` (`iddepartamento`),
  KEY `fk_equipamento_responsavel` (`idresponsavel`),
  KEY `fk_equipamento_categoria` (`idcategoria_equipamento`),
  KEY `fk_equipamento_fornecedor` (`idfornecedor`),
  CONSTRAINT `fk_equipamento_departamento` FOREIGN KEY (`iddepartamento`)          REFERENCES `departamentos` (`iddepartamento`)          ON DELETE SET NULL,
  CONSTRAINT `fk_equipamento_responsavel`  FOREIGN KEY (`idresponsavel`)           REFERENCES `usuario` (`idusuario`)                     ON DELETE SET NULL,
  CONSTRAINT `fk_equipamento_categoria`    FOREIGN KEY (`idcategoria_equipamento`) REFERENCES `categoria_equipamento` (`idcategoria_equipamento`) ON DELETE SET NULL,
  CONSTRAINT `fk_equipamento_fornecedor`   FOREIGN KEY (`idfornecedor`)            REFERENCES `fornecedor` (`idfornecedor`)               ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Abatimento de Equipamentos — workflow de fim de vida útil com aprovação
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `equipamentos_abatidos` (
  `idabatimento`          INT(11)      NOT NULL AUTO_INCREMENT,
  `id_equipamento`        INT(11)      NOT NULL,
  `motivo`                TEXT         NOT NULL,
  `idusuario_solicitante` INT(11)      DEFAULT NULL,
  `idusuario_aprovador`   INT(11)      DEFAULT NULL,
  `estado`                VARCHAR(20)  NOT NULL DEFAULT 'Solicitado',
  `data_solicitacao`      DATETIME     DEFAULT CURRENT_TIMESTAMP,
  `data_decisao`          DATETIME     DEFAULT NULL,
  `observacoes`           TEXT         DEFAULT NULL,
  PRIMARY KEY (`idabatimento`),
  KEY `fk_abat_solicitante` (`idusuario_solicitante`),
  KEY `fk_abat_aprovador` (`idusuario_aprovador`),
  CONSTRAINT `fk_abat_equipamento`  FOREIGN KEY (`id_equipamento`)        REFERENCES `equipamento`(`idequipamento`) ON DELETE CASCADE,
  CONSTRAINT `fk_abat_solicitante`  FOREIGN KEY (`idusuario_solicitante`) REFERENCES `usuario`(`idusuario`)         ON DELETE SET NULL,
  CONSTRAINT `fk_abat_aprovador`    FOREIGN KEY (`idusuario_aprovador`)   REFERENCES `usuario`(`idusuario`)         ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Categorias de Peças e Consumíveis
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `categoria` (
  `idcategoria` INT(11)      NOT NULL AUTO_INCREMENT,
  `nome`        VARCHAR(100) NOT NULL,
  `created`     DATETIME     DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idcategoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Peças e Consumíveis (stock interno, sem preço de venda)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `produto` (
  `idproduto`        INT(11)        NOT NULL AUTO_INCREMENT,
  `idfornecedor`     INT(11)        DEFAULT NULL,
  `idcategoria`      INT(11)        DEFAULT NULL,
  `referencia`       VARCHAR(100)   DEFAULT NULL,
  `nome`             VARCHAR(150)   NOT NULL,
  `custo_aquisicao`  DECIMAL(12,2)  DEFAULT 0.00,
  `estoque`          INT(11)        DEFAULT 0,
  `estoque_minimo`   INT(11)        DEFAULT 5,
  `descricao`        TEXT           DEFAULT NULL,
  `created`          DATETIME       DEFAULT CURRENT_TIMESTAMP,
  `modified`         DATETIME       DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `foto`             VARCHAR(255)   DEFAULT NULL,
  PRIMARY KEY (`idproduto`),
  KEY `fk_produto_fornecedor` (`idfornecedor`),
  KEY `fk_produto_categoria` (`idcategoria`),
  CONSTRAINT `fk_produto_fornecedor` FOREIGN KEY (`idfornecedor`) REFERENCES `fornecedor` (`idfornecedor`) ON DELETE SET NULL,
  CONSTRAINT `fk_produto_categoria`  FOREIGN KEY (`idcategoria`)  REFERENCES `categoria` (`idcategoria`)   ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Tipos de Manutenção (catálogo técnico, sem preço)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tipo_manutencao` (
  `idtipo_manutencao` INT(11)      NOT NULL AUTO_INCREMENT,
  `nome`               VARCHAR(100) NOT NULL,
  `categoria`          ENUM('Preventiva','Corretiva') NOT NULL DEFAULT 'Corretiva',
  `descricao`          TEXT         DEFAULT NULL,
  `created`            DATETIME     DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idtipo_manutencao`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Ocorrências — ponto de entrada do fluxo de manutenção:
-- Ocorrência → Diagnóstico → Execução → Conclusão → Histórico
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `ocorrencias` (
  `idocorrencia`          INT(11)      NOT NULL AUTO_INCREMENT,
  `id_equipamento`        INT(11)      DEFAULT NULL,
  `id_tipo_manutencao`    INT(11)      DEFAULT NULL,
  `idtecnico_responsavel` INT(11)      DEFAULT NULL,
  `categoria_manutencao`  ENUM('Preventiva','Corretiva') NOT NULL DEFAULT 'Corretiva',
  `prioridade`            ENUM('Baixa','Média','Alta','Urgente') NOT NULL DEFAULT 'Média',
  `descricao`             TEXT         DEFAULT NULL,
  `estado`                ENUM('Aberta','Em diagnóstico','Aguardando execução','Em execução','Concluída','Cancelada') NOT NULL DEFAULT 'Aberta',
  `data_abertura`         DATE         DEFAULT NULL,
  `data_prevista`         DATE         DEFAULT NULL,
  `data_encerramento`     DATE         DEFAULT NULL,
  `observacoes`           TEXT         DEFAULT NULL,
  `created`               DATETIME     DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idocorrencia`),
  KEY `idx_ocorrencias_data_prevista` (`data_prevista`),
  KEY `idx_ocorrencias_estado` (`estado`),
  KEY `fk_ocorrencias_equipamento` (`id_equipamento`),
  KEY `fk_ocorrencias_tipo_manutencao` (`id_tipo_manutencao`),
  KEY `fk_ocorrencias_tecnico` (`idtecnico_responsavel`),
  CONSTRAINT `fk_ocorrencias_equipamento`     FOREIGN KEY (`id_equipamento`)        REFERENCES `equipamento` (`idequipamento`)             ON DELETE SET NULL,
  CONSTRAINT `fk_ocorrencias_tipo_manutencao` FOREIGN KEY (`id_tipo_manutencao`)    REFERENCES `tipo_manutencao` (`idtipo_manutencao`)     ON DELETE SET NULL,
  CONSTRAINT `fk_ocorrencias_tecnico`         FOREIGN KEY (`idtecnico_responsavel`) REFERENCES `usuario` (`idusuario`)                     ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Equipamentos associados a uma ocorrência (1 ocorrência : N equipamentos)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `ocorrencia_equipamento` (
  `idocorrencia_equipamento` INT(11)  NOT NULL AUTO_INCREMENT,
  `id_ocorrencia`            INT(11)  NOT NULL,
  `id_equipamento`           INT(11)  NOT NULL,
  `created`                  DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idocorrencia_equipamento`),
  UNIQUE KEY `uq_ocorrencia_equipamento` (`id_ocorrencia`, `id_equipamento`),
  KEY `fk_oceq_equipamento` (`id_equipamento`),
  CONSTRAINT `fk_oceq_ocorrencia`  FOREIGN KEY (`id_ocorrencia`)  REFERENCES `ocorrencias`(`idocorrencia`)   ON DELETE CASCADE,
  CONSTRAINT `fk_oceq_equipamento` FOREIGN KEY (`id_equipamento`) REFERENCES `equipamento`(`idequipamento`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Auditoria de mudanças de estado de uma ocorrência
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `ocorrencia_historico` (
  `idocorrencia_historico` INT(11)     NOT NULL AUTO_INCREMENT,
  `id_ocorrencia`          INT(11)     NOT NULL,
  `estado_anterior`        VARCHAR(30) DEFAULT NULL,
  `estado_novo`            VARCHAR(30) NOT NULL,
  `idusuario`              INT(11)     DEFAULT NULL,
  `observacao`             TEXT        DEFAULT NULL,
  `created`                DATETIME    DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idocorrencia_historico`),
  KEY `fk_ochist_usuario` (`idusuario`),
  CONSTRAINT `fk_ochist_ocorrencia` FOREIGN KEY (`id_ocorrencia`) REFERENCES `ocorrencias`(`idocorrencia`) ON DELETE CASCADE,
  CONSTRAINT `fk_ochist_usuario`    FOREIGN KEY (`idusuario`)     REFERENCES `usuario`(`idusuario`)       ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Diagnóstico Técnico — histórico de diagnósticos ligado a uma ocorrência
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `diagnostico` (
  `iddiagnostico`         INT(11)      NOT NULL AUTO_INCREMENT,
  `id_ocorrencia`         INT(11)      NOT NULL,
  `id_equipamento`        INT(11)      NOT NULL,
  `idusuario_tecnico`     INT(11)      DEFAULT NULL,
  `problema_descrito`     TEXT         DEFAULT NULL,
  `solucao_proposta`      TEXT         DEFAULT NULL,
  `pecas_solicitadas`     TEXT         DEFAULT NULL,
  `encaminhado_execucao`  TINYINT(1)   NOT NULL DEFAULT 0,
  `created`               DATETIME     DEFAULT CURRENT_TIMESTAMP,
  `modified`              DATETIME     DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`iddiagnostico`),
  KEY `fk_diag_equipamento` (`id_equipamento`),
  KEY `fk_diag_usuario` (`idusuario_tecnico`),
  CONSTRAINT `fk_diag_ocorrencia`  FOREIGN KEY (`id_ocorrencia`)  REFERENCES `ocorrencias`(`idocorrencia`)   ON DELETE CASCADE,
  CONSTRAINT `fk_diag_equipamento` FOREIGN KEY (`id_equipamento`) REFERENCES `equipamento`(`idequipamento`) ON DELETE CASCADE,
  CONSTRAINT `fk_diag_usuario`     FOREIGN KEY (`idusuario_tecnico`) REFERENCES `usuario`(`idusuario`)      ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Execução da Manutenção — sempre ligada a uma Ocorrência
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `execucao_manutencao` (
  `idexecucao`        INT(11)      NOT NULL AUTO_INCREMENT,
  `id_ocorrencia`     INT(11)      NOT NULL,
  `idusuario_tecnico` INT(11)      DEFAULT NULL,
  `data_inicio`       DATETIME     DEFAULT NULL,
  `data_fim`          DATETIME     DEFAULT NULL,
  `estado`            ENUM('Em execução','Concluída','Cancelada') NOT NULL DEFAULT 'Em execução',
  `observacoes`       TEXT         DEFAULT NULL,
  `created`           DATETIME     DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idexecucao`),
  KEY `fk_exec_usuario` (`idusuario_tecnico`),
  CONSTRAINT `fk_exec_ocorrencia` FOREIGN KEY (`id_ocorrencia`) REFERENCES `ocorrencias`(`idocorrencia`) ON DELETE CASCADE,
  CONSTRAINT `fk_exec_usuario`    FOREIGN KEY (`idusuario_tecnico`) REFERENCES `usuario`(`idusuario`)     ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Peças consumidas numa execução de manutenção
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `execucao_peca` (
  `idexecucao_peca` INT(11)  NOT NULL AUTO_INCREMENT,
  `id_execucao`     INT(11)  NOT NULL,
  `id_produto`      INT(11)  NOT NULL,
  `quantidade`      INT(11)  NOT NULL DEFAULT 1,
  `created`         DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idexecucao_peca`),
  KEY `fk_execpeca_produto` (`id_produto`),
  CONSTRAINT `fk_execpeca_execucao` FOREIGN KEY (`id_execucao`) REFERENCES `execucao_manutencao`(`idexecucao`) ON DELETE CASCADE,
  CONSTRAINT `fk_execpeca_produto`  FOREIGN KEY (`id_produto`)  REFERENCES `produto`(`idproduto`)              ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Compras a fornecedores (entrada de equipamentos/peças/consumíveis)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `compras` (
  `idcompras`        INT(11)        NOT NULL AUTO_INCREMENT,
  `idfornecedor`     INT(11)        DEFAULT NULL,
  `idproduto`        INT(11)        DEFAULT NULL,
  `quantidade`       INT(11)        NOT NULL DEFAULT 1,
  `custo_unitario`   DECIMAL(12,2)  DEFAULT 0.00,
  `idusuario`        INT(11)        DEFAULT NULL,
  `observacoes`      TEXT           DEFAULT NULL,
  `data`             DATETIME       DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idcompras`),
  KEY `fk_compras_fornecedor` (`idfornecedor`),
  KEY `fk_compras_produto` (`idproduto`),
  KEY `fk_compras_usuario` (`idusuario`),
  CONSTRAINT `fk_compras_fornecedor` FOREIGN KEY (`idfornecedor`) REFERENCES `fornecedor` (`idfornecedor`) ON DELETE SET NULL,
  CONSTRAINT `fk_compras_produto`    FOREIGN KEY (`idproduto`)    REFERENCES `produto` (`idproduto`)       ON DELETE SET NULL,
  CONSTRAINT `fk_compras_usuario`    FOREIGN KEY (`idusuario`)    REFERENCES `usuario` (`idusuario`)       ON DELETE SET NULL
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

-- ------------------------------------------------------------
-- Planeamento de Manutenção Preventiva
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `plano_manutencao_preventiva` (
  `idplano`            INT(11)      NOT NULL AUTO_INCREMENT,
  `id_equipamento`     INT(11)      NOT NULL,
  `id_tipo_manutencao` INT(11)      DEFAULT NULL,
  `idusuario_tecnico`  INT(11)      DEFAULT NULL,
  `periodicidade_dias` INT(11)      NOT NULL,
  `data_inicio`        DATE         NOT NULL,
  `proxima_execucao`   DATE         NOT NULL,
  `ativo`              TINYINT(1)   NOT NULL DEFAULT 1,
  `observacoes`        TEXT         DEFAULT NULL,
  `created`            DATETIME     DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idplano`),
  KEY `fk_plano_tipo_manutencao` (`id_tipo_manutencao`),
  KEY `fk_plano_usuario` (`idusuario_tecnico`),
  KEY `idx_plano_proxima_execucao` (`proxima_execucao`),
  CONSTRAINT `fk_plano_equipamento`     FOREIGN KEY (`id_equipamento`)     REFERENCES `equipamento`(`idequipamento`)         ON DELETE CASCADE,
  CONSTRAINT `fk_plano_tipo_manutencao` FOREIGN KEY (`id_tipo_manutencao`) REFERENCES `tipo_manutencao`(`idtipo_manutencao`) ON DELETE SET NULL,
  CONSTRAINT `fk_plano_usuario`         FOREIGN KEY (`idusuario_tecnico`)  REFERENCES `usuario`(`idusuario`)                 ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `plano_manutencao_lembrete` (
  `idlembrete` INT(11)  NOT NULL AUTO_INCREMENT,
  `idplano`    INT(11)  NOT NULL,
  `data_envio` DATETIME DEFAULT NULL,
  `enviado`    TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`idlembrete`),
  CONSTRAINT `fk_lembrete_plano` FOREIGN KEY (`idplano`) REFERENCES `plano_manutencao_preventiva`(`idplano`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Histórico de movimentações de stock (Entradas / Saídas)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `movimento_estoque` (
  `idmovimento`   INT(11)      NOT NULL AUTO_INCREMENT,
  `id_produto`    INT(11)      NOT NULL,
  `tipo`          ENUM('Entrada','Saida') NOT NULL,
  `quantidade`    INT(11)      NOT NULL,
  `origem`        VARCHAR(40)  DEFAULT NULL,
  `id_referencia` INT(11)      DEFAULT NULL,
  `idusuario`     INT(11)      DEFAULT NULL,
  `created`       DATETIME     DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idmovimento`),
  KEY `fk_movest_usuario` (`idusuario`),
  KEY `idx_movest_produto` (`id_produto`),
  CONSTRAINT `fk_movest_produto` FOREIGN KEY (`id_produto`) REFERENCES `produto`(`idproduto`) ON DELETE CASCADE,
  CONSTRAINT `fk_movest_usuario` FOREIGN KEY (`idusuario`)  REFERENCES `usuario`(`idusuario`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- VIEWS
-- ============================================================

-- View: peças/consumíveis + categoria + fornecedor
CREATE OR REPLACE VIEW `dadosProduto` AS
SELECT
  p.idproduto,
  p.referencia,
  p.nome,
  p.custo_aquisicao,
  p.estoque,
  p.estoque_minimo,
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

-- View: equipamentos + departamento + categoria + responsável + fornecedor
CREATE OR REPLACE VIEW `dadosEquipamento` AS
SELECT
  e.idequipamento,
  e.codigo,
  e.patrimonio,
  e.nome,
  e.iddepartamento,
  d.nome AS departamento,
  e.idresponsavel,
  ur.nome AS responsavel_nome,
  ur.sobrenome AS responsavel_sobrenome,
  e.localizacao,
  e.numero_serie,
  e.idcategoria_equipamento,
  ce.nome AS categoria_equipamento,
  e.marca,
  e.modelo,
  e.estado,
  e.idfornecedor,
  f.nome AS fornecedor,
  e.data_aquisicao,
  e.garantia_ate,
  e.diagnostico_tecnico,
  e.foto,
  e.observacoes,
  e.dataregisto,
  e.created
FROM equipamento e
LEFT JOIN departamentos d ON d.iddepartamento = e.iddepartamento
LEFT JOIN usuario ur ON ur.idusuario = e.idresponsavel
LEFT JOIN categoria_equipamento ce ON ce.idcategoria_equipamento = e.idcategoria_equipamento
LEFT JOIN fornecedor f ON f.idfornecedor = e.idfornecedor;

-- View: compras + peça + fornecedor
CREATE OR REPLACE VIEW `dadosCompras` AS
SELECT
  c.idcompras,
  c.quantidade,
  c.custo_unitario,
  c.data,
  c.observacoes,
  p.idproduto,
  p.nome AS produto,
  p.referencia,
  f.idfornecedor,
  f.nome AS fornecedor,
  u.nome AS usuario_nome,
  u.sobrenome AS usuario_sobrenome
FROM compras c
LEFT JOIN produto p     ON p.idproduto    = c.idproduto
LEFT JOIN fornecedor f  ON f.idfornecedor = c.idfornecedor
LEFT JOIN usuario u     ON u.idusuario    = c.idusuario;

-- ============================================================
-- DADOS INICIAIS
-- ============================================================

-- Utilizador Gerente padrão (senha em hash bcrypt — ver RELATORIO_TFC_ADAPTACAO.md
-- para a senha em texto simples a usar no primeiro acesso; deve ser alterada de imediato)
INSERT IGNORE INTO `usuario`
  (nbi, nif, nome, sobrenome, email, telefone, senha, nivel, st_conta, created)
VALUES
  ('ULA-GTI-0001', 'ULA-GTI-0001', 'Gerente', 'de TI', 'gerente.ti@ula.co.ao', '+244 930 000 000',
   '$2y$12$tf9YKB2HjEpywf4XgjqCguim4DybQXlF.TnTuEWTN.BdfAhdhWbFy', 'gerente', 'Ativada', NOW());

-- Departamentos de exemplo
INSERT IGNORE INTO `departamentos` (nome, responsavel, email) VALUES
  ('Departamento de Informática', 'Gerente de TI', 'gerente.ti@ula.co.ao'),
  ('Secretaria Geral', NULL, NULL),
  ('Biblioteca', NULL, NULL),
  ('Reitoria', NULL, NULL);

-- Categorias/Tipos de equipamento
INSERT IGNORE INTO `categoria_equipamento` (nome) VALUES
  ('Computador de Secretária'), ('Computador Portátil'), ('Impressora'),
  ('Servidor'), ('Equipamento de Rede'), ('Monitor'), ('Projetor'), ('Tablet'), ('Outro');

-- Tipos de manutenção base
INSERT IGNORE INTO `tipo_manutencao` (nome, categoria, descricao) VALUES
  ('Manutenção Preventiva Geral', 'Preventiva', 'Verificação periódica de hardware e software.'),
  ('Limpeza de Hardware', 'Preventiva', 'Limpeza interna e externa do equipamento.'),
  ('Atualização de Software', 'Preventiva', 'Atualização de sistema operativo e aplicações.'),
  ('Reparação de Hardware', 'Corretiva', 'Substituição ou reparação de componentes avariados.'),
  ('Formatação e Reinstalação', 'Corretiva', 'Formatação e reinstalação de sistema operativo.'),
  ('Diagnóstico de Rede', 'Corretiva', 'Resolução de falhas de ligação de rede.');

-- Categoria de peças/consumíveis de exemplo
INSERT IGNORE INTO `categoria` (idcategoria, nome) VALUES
  (1, 'Componentes de Hardware'),
  (2, 'Consumíveis de Impressão'),
  (3, 'Cabos e Conectores');

SET FOREIGN_KEY_CHECKS = 1;
