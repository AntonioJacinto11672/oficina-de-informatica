<?php

/**
 * Actualiza a view `dadosClienteEquipamento` para expor os novos campos do
 * equipamento (módulo 4). `equipamento.nome` é exposto como `nome_equipamento`
 * para não colidir com `clientes.nome` (já usado como `nome` por todas as
 * views/consumidores existentes — não pode mudar de significado aqui).
 */

return [
    'up' => function (PDO $pdo): array {
        $pdo->exec("
            CREATE OR REPLACE VIEW `dadosClienteEquipamento` AS
            SELECT
              e.idequipamento,
              e.codigo,
              e.patrimonio,
              e.nome AS nome_equipamento,
              e.departamento,
              e.localizacao,
              e.numero_serie,
              e.imei,
              e.tipo_equipamento,
              e.idcategoria_equipamento,
              ce.nome AS categoria_equipamento,
              e.marca,
              e.modelo,
              e.estado,
              e.defeito_reportado,
              e.diagnostico_tecnico,
              e.garantia_reparacao,
              e.foto_antes,
              e.foto_depois,
              e.dataregisto,
              e.data_aquisicao,
              e.observacoes,
              c.idclientes,
              c.nbi,
              c.nif,
              c.nome,
              c.sobrenome,
              c.email,
              c.telefone,
              c.morada
            FROM equipamento e
            LEFT JOIN clientes c ON c.idclientes = e.idcliente
            LEFT JOIN categoria_equipamento ce ON ce.idcategoria_equipamento = e.idcategoria_equipamento
        ");

        return ['View dadosClienteEquipamento actualizada com os campos do módulo 4.'];
    },
];
