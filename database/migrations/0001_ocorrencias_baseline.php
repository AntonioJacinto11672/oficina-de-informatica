<?php

/**
 * Garante que a tabela `ocorrencias` existe com a definição de database/schema.sql,
 * antes de removermos o CREATE TABLE IF NOT EXISTS que hoje corre em
 * core/ConfigController.php a cada pedido HTTP (DDL não deve correr por pedido).
 *
 * A partir desta migração, database/schema.sql + database/migrations/ passam a ser
 * a única fonte de verdade da estrutura da base de dados.
 */

return [
    'up' => function (PDO $pdo): array {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `ocorrencias` (
              `idocorrencia`      INT(11)        NOT NULL AUTO_INCREMENT,
              `id_orcamento`      INT(11)        DEFAULT NULL,
              `id_equipamento`    INT(11)        DEFAULT NULL,
              `id_tipo_servico`   INT(11)        DEFAULT NULL,
              `tecnico`           VARCHAR(100)   DEFAULT NULL,
              `tipo_manutencao`   VARCHAR(30)    DEFAULT 'Corretiva',
              `descricao`         TEXT           DEFAULT NULL,
              `estado`            VARCHAR(30)    DEFAULT 'Aberta',
              `data_abertura`     DATE           DEFAULT NULL,
              `data_prevista`     DATE           DEFAULT NULL,
              `data_encerramento` DATE           DEFAULT NULL,
              `observacoes`       TEXT           DEFAULT NULL,
              `status`            VARCHAR(30)    DEFAULT 'Aberta',
              `created`           DATETIME       DEFAULT CURRENT_TIMESTAMP,
              PRIMARY KEY (`idocorrencia`),
              KEY `idx_ocorrencias_data_prevista` (`data_prevista`),
              KEY `idx_ocorrencias_status` (`status`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        $existentes = (int)$pdo->query('SELECT COUNT(*) FROM ocorrencias')->fetchColumn();

        return ["Tabela ocorrencias confirmada ({$existentes} registos existentes)."];
    },
];
