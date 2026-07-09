<?php

/**
 * Módulos 15-16 e lacuna do módulo 7:
 *  - comissao_config: percentagem de comissão configurável por técnico e/ou
 *    tipo de serviço, substituindo a constante global .env VALOR_COMISSAO
 *    (que se mantém como fallback de última instância).
 *  - movimento_estoque: ledger de todas as entradas/saídas de stock — hoje só
 *    existe a quantidade actual em produto.estoque, sem histórico.
 *  - dadosCustoOcorrencia: view com custo de peças + serviço por ocorrência.
 */

return [
    'up' => function (PDO $pdo): array {
        $mensagens = [];

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `comissao_config` (
              `idconfig`          INT(11)       NOT NULL AUTO_INCREMENT,
              `idusuario_tecnico` INT(11)       DEFAULT NULL,
              `id_tipo_servico`   INT(11)       DEFAULT NULL,
              `percentual`        DECIMAL(5,2)  NOT NULL DEFAULT 30.00,
              `ativo`             TINYINT(1)    NOT NULL DEFAULT 1,
              `created`           DATETIME      DEFAULT CURRENT_TIMESTAMP,
              PRIMARY KEY (`idconfig`),
              KEY `fk_comconfig_usuario` (`idusuario_tecnico`),
              KEY `fk_comconfig_tiposervico` (`id_tipo_servico`),
              CONSTRAINT `fk_comconfig_usuario`     FOREIGN KEY (`idusuario_tecnico`) REFERENCES `usuario`(`idusuario`)           ON DELETE CASCADE,
              CONSTRAINT `fk_comconfig_tiposervico` FOREIGN KEY (`id_tipo_servico`)   REFERENCES `tipo_servico`(`idtipo_servico`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        // Semeia 1 linha global (idusuario_tecnico e id_tipo_servico ambos NULL)
        // igual ao valor actual do .env, para o comportamento ficar inalterado
        // até um admin personalizar.
        $existeGlobal = (int)$pdo->query("SELECT COUNT(*) FROM comissao_config WHERE idusuario_tecnico IS NULL AND id_tipo_servico IS NULL")->fetchColumn();
        if ($existeGlobal === 0) {
            $pdo->exec("INSERT INTO comissao_config (idusuario_tecnico, id_tipo_servico, percentual, ativo) VALUES (NULL, NULL, 30.00, 1)");
            $mensagens[] = 'comissao_config: semeada 1 linha global (30.00%, igual ao .env actual).';
        } else {
            $mensagens[] = 'comissao_config: já existia uma linha global, backfill ignorado.';
        }

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `movimento_estoque` (
              `idmovimento`   INT(11)      NOT NULL AUTO_INCREMENT,
              `id_produto`    INT(11)      NOT NULL,
              `tipo`          VARCHAR(10)  NOT NULL,
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        $mensagens[] = 'movimento_estoque: ledger começa vazio a partir de agora (sem reconstrução retroactiva do histórico).';

        $pdo->exec("
            CREATE OR REPLACE VIEW `dadosCustoOcorrencia` AS
            SELECT
              oc.idocorrencia,
              COALESCE(pecas.custo_pecas, 0) AS custo_pecas,
              COALESCE(o.valor, 0) AS custo_servico,
              COALESCE(pecas.custo_pecas, 0) + COALESCE(o.valor, 0) AS custo_total
            FROM ocorrencias oc
            LEFT JOIN orcamentos o ON o.id_ocorrencia = oc.idocorrencia
            LEFT JOIN (
                SELECT op.orcamentos AS idorcamentos, SUM(op.quantidade * p.valor_venda) AS custo_pecas
                FROM orc_prod op
                INNER JOIN produto p ON p.idproduto = op.produtos
                GROUP BY op.orcamentos
            ) pecas ON pecas.idorcamentos = o.idorcamentos
        ");
        $mensagens[] = 'View dadosCustoOcorrencia criada (custo de peças + serviço por ocorrência).';

        return $mensagens;
    },
];
