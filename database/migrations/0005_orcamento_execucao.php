<?php

/**
 * Módulos 11-12: `orcamentos` passa a poder ligar-se a uma `ocorrencia` (FK
 * nullable — orçamentos antigos/legados sem ocorrência ficam NULL), e a
 * Execução da Manutenção ganha tabelas próprias (`execucao_manutencao` +
 * `execucao_peca`), em paralelo ao fluxo antigo em `orcamentos` (tipo='Serviço'),
 * que se mantém intacto para não arriscar os controllers de maior tráfego.
 */

return [
    'up' => function (PDO $pdo): array {
        $mensagens = [];

        $pdo->exec("ALTER TABLE orcamentos ADD COLUMN IF NOT EXISTS id_ocorrencia INT(11) DEFAULT NULL AFTER idorcamentos");

        // Backfill via ligação inversa já existente: ocorrencias.id_orcamento -> orcamentos.idorcamentos
        $pdo->exec("
            UPDATE orcamentos o
            INNER JOIN ocorrencias oc ON oc.id_orcamento = o.idorcamentos
            SET o.id_ocorrencia = oc.idocorrencia
            WHERE o.id_ocorrencia IS NULL
        ");

        $totalOrc = (int)$pdo->query('SELECT COUNT(*) FROM orcamentos')->fetchColumn();
        $semOcorrencia = (int)$pdo->query('SELECT COUNT(*) FROM orcamentos WHERE id_ocorrencia IS NULL')->fetchColumn();
        $mensagens[] = "orcamentos: {$totalOrc} total, {$semOcorrencia} sem ocorrência ligada (legado, id_ocorrencia fica NULL).";

        $pdo->exec("
            ALTER TABLE orcamentos
            ADD CONSTRAINT fk_orcamentos_ocorrencia FOREIGN KEY IF NOT EXISTS (id_ocorrencia)
            REFERENCES ocorrencias(idocorrencia) ON DELETE SET NULL
        ");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `execucao_manutencao` (
              `idexecucao`        INT(11)      NOT NULL AUTO_INCREMENT,
              `id_ocorrencia`     INT(11)      NOT NULL,
              `id_orcamento`      INT(11)      DEFAULT NULL,
              `idusuario_tecnico` INT(11)      DEFAULT NULL,
              `data_inicio`       DATETIME     DEFAULT NULL,
              `data_fim`          DATETIME     DEFAULT NULL,
              `estado`            VARCHAR(30)  NOT NULL DEFAULT 'Em execução',
              `observacoes`       TEXT         DEFAULT NULL,
              `created`           DATETIME     DEFAULT CURRENT_TIMESTAMP,
              PRIMARY KEY (`idexecucao`),
              KEY `fk_exec_orcamento` (`id_orcamento`),
              KEY `fk_exec_usuario` (`idusuario_tecnico`),
              CONSTRAINT `fk_exec_ocorrencia` FOREIGN KEY (`id_ocorrencia`) REFERENCES `ocorrencias`(`idocorrencia`) ON DELETE CASCADE,
              CONSTRAINT `fk_exec_orcamento`  FOREIGN KEY (`id_orcamento`)  REFERENCES `orcamentos`(`idorcamentos`)   ON DELETE SET NULL,
              CONSTRAINT `fk_exec_usuario`    FOREIGN KEY (`idusuario_tecnico`) REFERENCES `usuario`(`idusuario`)     ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        $pdo->exec("
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        return $mensagens;
    },
];
