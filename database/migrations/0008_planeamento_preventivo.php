<?php

/**
 * Planeamento de Manutenção Preventiva (módulo 13) — recorrência/periodicidade
 * por equipamento, hoje totalmente ausente. Um plano vencido gera uma
 * ocorrência 'Preventiva' automaticamente (manual via botão nesta fase, ou
 * via cron_planeamento.php agendado a nível de SO).
 */

return [
    'up' => function (PDO $pdo): array {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `plano_manutencao_preventiva` (
              `idplano`            INT(11)      NOT NULL AUTO_INCREMENT,
              `id_equipamento`     INT(11)      NOT NULL,
              `id_tipo_servico`    INT(11)      DEFAULT NULL,
              `idusuario_tecnico`  INT(11)      DEFAULT NULL,
              `periodicidade_dias` INT(11)      NOT NULL,
              `data_inicio`        DATE         NOT NULL,
              `proxima_execucao`   DATE         NOT NULL,
              `ativo`              TINYINT(1)   NOT NULL DEFAULT 1,
              `observacoes`        TEXT         DEFAULT NULL,
              `created`            DATETIME     DEFAULT CURRENT_TIMESTAMP,
              PRIMARY KEY (`idplano`),
              KEY `fk_plano_tipo_servico` (`id_tipo_servico`),
              KEY `fk_plano_usuario` (`idusuario_tecnico`),
              KEY `idx_plano_proxima_execucao` (`proxima_execucao`),
              CONSTRAINT `fk_plano_equipamento`  FOREIGN KEY (`id_equipamento`)    REFERENCES `equipamento`(`idequipamento`)     ON DELETE CASCADE,
              CONSTRAINT `fk_plano_tipo_servico` FOREIGN KEY (`id_tipo_servico`)   REFERENCES `tipo_servico`(`idtipo_servico`)   ON DELETE SET NULL,
              CONSTRAINT `fk_plano_usuario`       FOREIGN KEY (`idusuario_tecnico`) REFERENCES `usuario`(`idusuario`)            ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `plano_manutencao_lembrete` (
              `idlembrete` INT(11)  NOT NULL AUTO_INCREMENT,
              `idplano`    INT(11)  NOT NULL,
              `data_envio` DATETIME DEFAULT NULL,
              `enviado`    TINYINT(1) NOT NULL DEFAULT 0,
              PRIMARY KEY (`idlembrete`),
              CONSTRAINT `fk_lembrete_plano` FOREIGN KEY (`idplano`) REFERENCES `plano_manutencao_preventiva`(`idplano`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        return ['Tabelas plano_manutencao_preventiva e plano_manutencao_lembrete criadas.'];
    },
];
