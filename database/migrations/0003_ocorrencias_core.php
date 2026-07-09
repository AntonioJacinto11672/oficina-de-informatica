<?php

/**
 * Promove `ocorrencias` a entidade central e independente (módulo 9):
 *  - prioridade + técnico responsável ligado a usuario.idusuario (FK real)
 *  - ocorrencia_equipamento: suporta vários equipamentos por ocorrência
 *  - ocorrencia_historico: auditoria da máquina de estados
 *
 * A coluna `tecnico` (string/NIF) e `id_equipamento` (equipamento principal)
 * mantêm-se sem alteração, só para leitura de compatibilidade — o widget de
 * dashboard `AdmsHome::dadosOcorrenciasPreventivas()` continua a ler direto
 * dessas colunas até à Fase 7.
 */

return [
    'up' => function (PDO $pdo): array {
        $mensagens = [];

        $pdo->exec("ALTER TABLE ocorrencias ADD COLUMN IF NOT EXISTS prioridade VARCHAR(20) DEFAULT 'Média' AFTER tipo_manutencao");
        $pdo->exec("ALTER TABLE ocorrencias ADD COLUMN IF NOT EXISTS idtecnico_responsavel INT(11) DEFAULT NULL AFTER tecnico");
        $pdo->exec("
            ALTER TABLE ocorrencias
            ADD CONSTRAINT fk_ocorrencias_tecnico FOREIGN KEY IF NOT EXISTS (idtecnico_responsavel)
            REFERENCES usuario(idusuario) ON DELETE SET NULL
        ");

        $pdo->exec("
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        $pdo->exec("
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        // Backfill: equipamento principal existente -> junção
        $pdo->exec("
            INSERT INTO ocorrencia_equipamento (id_ocorrencia, id_equipamento, created)
            SELECT o.idocorrencia, o.id_equipamento, o.created
            FROM ocorrencias o
            WHERE o.id_equipamento IS NOT NULL
              AND NOT EXISTS (
                  SELECT 1 FROM ocorrencia_equipamento oe
                  WHERE oe.id_ocorrencia = o.idocorrencia AND oe.id_equipamento = o.id_equipamento
              )
        ");
        $totalJuncao = (int)$pdo->query('SELECT COUNT(*) FROM ocorrencia_equipamento')->fetchColumn();
        $mensagens[] = "ocorrencia_equipamento: {$totalJuncao} vínculos (backfill do equipamento principal existente).";

        // Backfill: técnico (NIF em ocorrencias.tecnico) -> idtecnico_responsavel
        $pdo->exec("
            UPDATE ocorrencias o
            INNER JOIN usuario u ON u.nif = o.tecnico AND o.tecnico IS NOT NULL AND o.tecnico <> ''
            SET o.idtecnico_responsavel = u.idusuario
            WHERE o.idtecnico_responsavel IS NULL
        ");
        $totalOcorrencias = (int)$pdo->query('SELECT COUNT(*) FROM ocorrencias')->fetchColumn();
        $semTecnico = (int)$pdo->query('SELECT COUNT(*) FROM ocorrencias WHERE idtecnico_responsavel IS NULL')->fetchColumn();
        $mensagens[] = "ocorrencias: {$totalOcorrencias} total, {$semTecnico} sem técnico responsável ligado (idtecnico_responsavel NULL — NIF não encontrado ou em branco).";

        // Backfill: 1 linha de histórico por ocorrência existente, com o estado actual
        $pdo->exec("
            INSERT INTO ocorrencia_historico (id_ocorrencia, estado_anterior, estado_novo, observacao, created)
            SELECT o.idocorrencia, NULL, COALESCE(NULLIF(o.status,''), NULLIF(o.estado,''), 'Aberta'),
                   'Estado inicial migrado automaticamente (Fase 1).', o.created
            FROM ocorrencias o
            WHERE NOT EXISTS (SELECT 1 FROM ocorrencia_historico h WHERE h.id_ocorrencia = o.idocorrencia)
        ");
        $totalHistorico = (int)$pdo->query('SELECT COUNT(*) FROM ocorrencia_historico')->fetchColumn();
        $mensagens[] = "ocorrencia_historico: {$totalHistorico} registos (1 semente por ocorrência existente).";

        return $mensagens;
    },
];
