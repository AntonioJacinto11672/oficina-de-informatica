<?php

/**
 * Liga tecnicos/recepcionista à conta de login (usuario) através de uma FK real,
 * em vez do cruzamento implícito por NIF usado hoje em todo o código (sessão,
 * comissões, orçamentos, ocorrências referem-se sempre a usuario.nif).
 *
 * Cruza por NIF e reporta órfãos/duplicados — não força nada às cegas.
 * A FK só é adicionada se a contagem de órfãos for aceitável (ver mensagens).
 */

return [
    'up' => function (PDO $pdo): array {
        $mensagens = [];

        $pdo->exec("ALTER TABLE tecnicos ADD COLUMN IF NOT EXISTS idusuario INT(11) DEFAULT NULL AFTER idtecnico");
        $pdo->exec("ALTER TABLE recepcionista ADD COLUMN IF NOT EXISTS idusuario INT(11) DEFAULT NULL AFTER idrecepcionista");

        $pdo->exec("
            UPDATE tecnicos t
            INNER JOIN usuario u ON u.nif = t.nif AND t.nif IS NOT NULL AND t.nif <> ''
            SET t.idusuario = u.idusuario
            WHERE t.idusuario IS NULL
        ");
        $pdo->exec("
            UPDATE recepcionista r
            INNER JOIN usuario u ON u.nif = r.nif AND r.nif IS NOT NULL AND r.nif <> ''
            SET r.idusuario = u.idusuario
            WHERE r.idusuario IS NULL
        ");

        $totalTecnicos = (int)$pdo->query('SELECT COUNT(*) FROM tecnicos')->fetchColumn();
        $orfaosTecnicos = (int)$pdo->query('SELECT COUNT(*) FROM tecnicos WHERE idusuario IS NULL')->fetchColumn();
        $totalRecep = (int)$pdo->query('SELECT COUNT(*) FROM recepcionista')->fetchColumn();
        $orfaosRecep = (int)$pdo->query('SELECT COUNT(*) FROM recepcionista WHERE idusuario IS NULL')->fetchColumn();

        $nifDuplicadosUsuario = (int)$pdo->query("
            SELECT COUNT(*) FROM (
                SELECT nif FROM usuario WHERE nif IS NOT NULL AND nif <> '' GROUP BY nif HAVING COUNT(*) > 1
            ) x
        ")->fetchColumn();

        $mensagens[] = "tecnicos: {$totalTecnicos} total, {$orfaosTecnicos} sem correspondência em usuario.nif (idusuario ficou NULL).";
        $mensagens[] = "recepcionista: {$totalRecep} total, {$orfaosRecep} sem correspondência em usuario.nif (idusuario ficou NULL).";
        $mensagens[] = "usuario: {$nifDuplicadosUsuario} valores de NIF duplicados entre contas (risco de cruzamento ambíguo).";

        // FK só é adicionada quando não há ambiguidade (nenhum NIF duplicado em usuario).
        // Órfãos isolados (idusuario NULL) não impedem a FK, porque a coluna é NULLable.
        if ($nifDuplicadosUsuario === 0) {
            $pdo->exec("
                ALTER TABLE tecnicos
                ADD CONSTRAINT fk_tecnicos_usuario FOREIGN KEY IF NOT EXISTS (idusuario)
                REFERENCES usuario(idusuario) ON DELETE SET NULL
            ");
            $pdo->exec("
                ALTER TABLE recepcionista
                ADD CONSTRAINT fk_recepcionista_usuario FOREIGN KEY IF NOT EXISTS (idusuario)
                REFERENCES usuario(idusuario) ON DELETE SET NULL
            ");
            $mensagens[] = 'FKs fk_tecnicos_usuario / fk_recepcionista_usuario adicionadas.';
        } else {
            $mensagens[] = 'FK NÃO adicionada — existem NIFs duplicados em usuario, resolver manualmente antes de reforçar a integridade.';
        }

        return $mensagens;
    },
];
