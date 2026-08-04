<?php

return [
    'up' => function (PDO $pdo): array {
        $coluna = $pdo->query("SHOW COLUMNS FROM execucao_manutencao LIKE 'id_equipamento'")->fetch();
        if ($coluna) {
            return ['Coluna id_equipamento já existe em execucao_manutencao — nada a fazer.'];
        }

        $pdo->exec("ALTER TABLE execucao_manutencao ADD COLUMN id_equipamento INT(11) NULL AFTER id_ocorrencia");
        $pdo->exec("
            ALTER TABLE execucao_manutencao
            ADD CONSTRAINT fk_exec_equipamento FOREIGN KEY (id_equipamento)
            REFERENCES equipamento(idequipamento) ON DELETE SET NULL
        ");

        return ['Coluna id_equipamento adicionada a execucao_manutencao (execução passa a ser por equipamento, não só por ocorrência).'];
    },
];
