<?php

/**
 * Regista a percentagem efectivamente aplicada em cada comissão (módulo 16),
 * para o ecrã de Comissões poder mostrar a % usada por linha sem depender de
 * reconstruir isso a partir da configuração actual (que pode já ter mudado).
 */

return [
    'up' => function (PDO $pdo): array {
        $pdo->exec("ALTER TABLE comissao ADD COLUMN IF NOT EXISTS percentual_aplicado DECIMAL(5,2) DEFAULT NULL AFTER valor");
        return ['Coluna comissao.percentual_aplicado adicionada.'];
    },
];
