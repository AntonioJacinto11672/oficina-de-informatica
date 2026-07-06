-- ============================================================
-- Dados Padrão Adicionais (opcional)
-- Sistema de Gestão de Assistência Técnica Informática
-- ------------------------------------------------------------
-- Execute este script DEPOIS de aplicar `database/schema.sql`.
-- Cria uma conta de técnico de demonstração e dois tipos de
-- serviço adicionais usados no fluxo de manutenção.
--
-- É seguro executar mais do que uma vez: usa `INSERT IGNORE`
-- e verificações de existência para não duplicar registos.
-- ============================================================

SET NAMES utf8mb4;
USE manutencao;

-- ------------------------------------------------------------
-- Técnico de demonstração
-- Login:  tecnico@gmail.com
-- Senha:  tecnico123
-- ------------------------------------------------------------
INSERT IGNORE INTO tecnicos (nbi, nif, nome, sobrenome, email, telefone, morada, created)
VALUES ('000000001', '000000001', 'Técnico', 'Padrão', 'tecnico@gmail.com', NULL, NULL, NOW());

INSERT IGNORE INTO usuario (nbi, nif, nome, sobrenome, email, telefone, senha, nivel, st_conta, foto, created)
VALUES ('000000001', '000000001', 'Técnico', 'Padrão', 'tecnico@gmail.com', NULL, MD5('tecnico123'), 'tecnico', 'Ativada', NULL, NOW());

-- ------------------------------------------------------------
-- Tipos de Serviço adicionais
-- ------------------------------------------------------------
INSERT INTO tipo_servico (nome, valor, created)
SELECT 'Manutenção Preventiva', 0.00, NOW()
WHERE NOT EXISTS (SELECT 1 FROM tipo_servico WHERE nome = 'Manutenção Preventiva');

INSERT INTO tipo_servico (nome, valor, created)
SELECT 'Manutenção Corretiva', 0.00, NOW()
WHERE NOT EXISTS (SELECT 1 FROM tipo_servico WHERE nome = 'Manutenção Corretiva');
