<?php

/**
 * Diagnóstico Técnico (módulo 10): histórico de diagnósticos ligado a uma
 * ocorrência concreta, em vez do único campo `equipamento.diagnostico_tecnico`
 * que era reescrito a cada intervenção. Esse campo mantém-se como resumo do
 * último diagnóstico (compatibilidade com ecrãs/relatórios existentes).
 */

return [
    'up' => function (PDO $pdo): array {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `diagnostico` (
              `iddiagnostico`          INT(11)      NOT NULL AUTO_INCREMENT,
              `id_ocorrencia`          INT(11)      NOT NULL,
              `id_equipamento`         INT(11)      NOT NULL,
              `idusuario_tecnico`      INT(11)      DEFAULT NULL,
              `problema_descrito`      TEXT         DEFAULT NULL,
              `solucao_proposta`       TEXT         DEFAULT NULL,
              `pecas_solicitadas`      TEXT         DEFAULT NULL,
              `encaminhado_orcamento`  TINYINT(1)   NOT NULL DEFAULT 0,
              `created`                DATETIME     DEFAULT CURRENT_TIMESTAMP,
              `modified`               DATETIME     DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
              PRIMARY KEY (`iddiagnostico`),
              KEY `fk_diag_equipamento` (`id_equipamento`),
              KEY `fk_diag_usuario` (`idusuario_tecnico`),
              CONSTRAINT `fk_diag_ocorrencia`  FOREIGN KEY (`id_ocorrencia`)  REFERENCES `ocorrencias`(`idocorrencia`)   ON DELETE CASCADE,
              CONSTRAINT `fk_diag_equipamento` FOREIGN KEY (`id_equipamento`) REFERENCES `equipamento`(`idequipamento`) ON DELETE CASCADE,
              CONSTRAINT `fk_diag_usuario`     FOREIGN KEY (`idusuario_tecnico`) REFERENCES `usuario`(`idusuario`)      ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        $total = (int)$pdo->query('SELECT COUNT(*) FROM diagnostico')->fetchColumn();
        return ["Tabela diagnostico confirmada ({$total} registos existentes)."];
    },
];
