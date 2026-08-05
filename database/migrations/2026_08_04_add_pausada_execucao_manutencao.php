<?php

return [
    'up' => function (PDO $pdo): array {
        $coluna = $pdo->query("SHOW COLUMNS FROM execucao_manutencao LIKE 'estado'")->fetch(PDO::FETCH_ASSOC);
        if ($coluna && strpos($coluna['Type'], 'Pausada') !== false) {
            return ['Estado "Pausada" já existe em execucao_manutencao — nada a fazer.'];
        }

        $pdo->exec("
            ALTER TABLE execucao_manutencao
            MODIFY COLUMN estado ENUM('Em execução','Pausada','Concluída','Cancelada') NOT NULL DEFAULT 'Em execução'
        ");

        return ['Estado "Pausada" adicionado a execucao_manutencao — permite interromper e retomar uma execução em curso.'];
    },
];
