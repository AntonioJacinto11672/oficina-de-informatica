<?php

/**
 * Módulos 4 e 14:
 *  - categoria_equipamento: substitui o texto livre equipamento.tipo_equipamento
 *    por uma lista estruturada (seedada com a união dos valores já usados +
 *    os exemplos da especificação).
 *  - equipamento ganha os campos em falta face à spec (Código, Património,
 *    Nome, Departamento, Localização, Data de Aquisição, Observações).
 *    `dataregisto` mantém-se — é a data de ENTRADA/registo na oficina, não a
 *    data de aquisição do bem pela universidade (confirmado nos ecrãs
 *    existentes: "Data de Entrada"/"Data do Primeiro Registo").
 *  - equipamentos_abatidos: workflow de abatimento com aprovação.
 */

return [
    'up' => function (PDO $pdo): array {
        $mensagens = [];

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `categoria_equipamento` (
              `idcategoria_equipamento` INT(11)      NOT NULL AUTO_INCREMENT,
              `nome`                    VARCHAR(100) NOT NULL,
              `created`                 DATETIME     DEFAULT CURRENT_TIMESTAMP,
              PRIMARY KEY (`idcategoria_equipamento`),
              UNIQUE KEY `uq_categoria_equipamento_nome` (`nome`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        $categorias = ['Computador', 'Portátil', 'Impressora', 'Servidor', 'Equipamento de Rede',
            'Telemóvel', 'Tablet', 'Monitor', 'Projetor', 'Switch', 'Router', 'UPS', 'Outro'];
        $stmt = $pdo->prepare('INSERT IGNORE INTO categoria_equipamento (nome) VALUES (:nome)');
        foreach ($categorias as $nome) {
            $stmt->execute([':nome' => $nome]);
        }

        $pdo->exec("ALTER TABLE equipamento ADD COLUMN IF NOT EXISTS idcategoria_equipamento INT(11) DEFAULT NULL AFTER tipo_equipamento");
        $pdo->exec("ALTER TABLE equipamento ADD COLUMN IF NOT EXISTS codigo VARCHAR(50) DEFAULT NULL AFTER idequipamento");
        $pdo->exec("ALTER TABLE equipamento ADD COLUMN IF NOT EXISTS patrimonio VARCHAR(50) DEFAULT NULL AFTER codigo");
        $pdo->exec("ALTER TABLE equipamento ADD COLUMN IF NOT EXISTS nome VARCHAR(150) DEFAULT NULL AFTER patrimonio");
        $pdo->exec("ALTER TABLE equipamento ADD COLUMN IF NOT EXISTS departamento VARCHAR(100) DEFAULT NULL AFTER idcliente");
        $pdo->exec("ALTER TABLE equipamento ADD COLUMN IF NOT EXISTS localizacao VARCHAR(150) DEFAULT NULL AFTER departamento");
        $pdo->exec("ALTER TABLE equipamento ADD COLUMN IF NOT EXISTS data_aquisicao DATE DEFAULT NULL AFTER dataregisto");
        $pdo->exec("ALTER TABLE equipamento ADD COLUMN IF NOT EXISTS observacoes TEXT DEFAULT NULL");

        $pdo->exec("
            UPDATE equipamento e
            INNER JOIN categoria_equipamento c ON LOWER(c.nome) = LOWER(e.tipo_equipamento)
            SET e.idcategoria_equipamento = c.idcategoria_equipamento
            WHERE e.idcategoria_equipamento IS NULL
        ");
        $pdo->exec("
            ALTER TABLE equipamento
            ADD CONSTRAINT fk_equipamento_categoria FOREIGN KEY IF NOT EXISTS (idcategoria_equipamento)
            REFERENCES categoria_equipamento(idcategoria_equipamento) ON DELETE SET NULL
        ");

        $totalEquip = (int)$pdo->query('SELECT COUNT(*) FROM equipamento')->fetchColumn();
        $semCategoria = (int)$pdo->query('SELECT COUNT(*) FROM equipamento WHERE idcategoria_equipamento IS NULL')->fetchColumn();
        $mensagens[] = "equipamento: {$totalEquip} total, {$semCategoria} sem categoria correspondida automaticamente (tipo_equipamento não coincide com nenhuma categoria seedada).";

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `equipamentos_abatidos` (
              `idabatimento`          INT(11)      NOT NULL AUTO_INCREMENT,
              `id_equipamento`        INT(11)      NOT NULL,
              `motivo`                TEXT         NOT NULL,
              `idusuario_solicitante` INT(11)      DEFAULT NULL,
              `idusuario_aprovador`   INT(11)      DEFAULT NULL,
              `estado`                VARCHAR(20)  NOT NULL DEFAULT 'Solicitado',
              `data_solicitacao`      DATETIME     DEFAULT CURRENT_TIMESTAMP,
              `data_decisao`          DATETIME     DEFAULT NULL,
              `observacoes`           TEXT         DEFAULT NULL,
              PRIMARY KEY (`idabatimento`),
              KEY `fk_abat_solicitante` (`idusuario_solicitante`),
              KEY `fk_abat_aprovador` (`idusuario_aprovador`),
              CONSTRAINT `fk_abat_equipamento`  FOREIGN KEY (`id_equipamento`)        REFERENCES `equipamento`(`idequipamento`) ON DELETE CASCADE,
              CONSTRAINT `fk_abat_solicitante`  FOREIGN KEY (`idusuario_solicitante`) REFERENCES `usuario`(`idusuario`)         ON DELETE SET NULL,
              CONSTRAINT `fk_abat_aprovador`    FOREIGN KEY (`idusuario_aprovador`)   REFERENCES `usuario`(`idusuario`)         ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        return $mensagens;
    },
];
