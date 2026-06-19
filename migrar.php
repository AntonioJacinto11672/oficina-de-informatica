<?php
// Script de migração — apagar este ficheiro após usar
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=manutencao', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$ok = [];
$err = [];

function run(PDO $pdo, string $label, string $sql, array &$ok, array &$err): void {
    try { $pdo->exec($sql); $ok[] = $label; }
    catch (PDOException $e) { $err[] = "$label: " . $e->getMessage(); }
}

// 1. Coluna modified em produto
run($pdo, 'ALTER produto ADD modified', "
    ALTER TABLE produto
    ADD COLUMN IF NOT EXISTS modified DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP AFTER created
", $ok, $err);

// 2. VIEW dadosProduto — adiciona campos do fornecedor
run($pdo, 'VIEW dadosProduto', "
CREATE OR REPLACE VIEW dadosProduto AS
SELECT
  p.idproduto, p.referencia, p.nome, p.valor_compra, p.valor_venda,
  p.estoque, p.descricao, p.foto, p.created,
  c.idcategoria, c.nome AS categoria,
  f.idfornecedor, f.nome AS fornecedor,
  f.nif, f.tipo_pessoa, f.telefone, f.email, f.morada
FROM produto p
LEFT JOIN categoria c ON c.idcategoria = p.idcategoria
LEFT JOIN fornecedor f ON f.idfornecedor = p.idfornecedor
", $ok, $err);

// 3. VIEW dadosorcamento — usa tecnico e equipamento
run($pdo, 'VIEW dadosorcamento', "
CREATE OR REPLACE VIEW dadosorcamento AS
SELECT
  o.idorcamentos, o.veiculo AS numero_serie, o.veiculo AS matricula,
  o.id_tipo_servico AS idtipo_servico,
  o.valor, o.data AS data_orcamento, o.data_entrega, o.garantia,
  o.tecnico, o.tecnico AS mecanico,
  o.descricao, o.obs, o.status, o.tipo, o.created,
  ts.nome AS tipo_servico,
  c.nif, c.nbi, c.nome AS nome_cliente, c.sobrenome, c.email, c.telefone, c.morada,
  e.marca, e.modelo, e.tipo_equipamento, e.estado, e.imei,
  e.defeito_reportado, e.diagnostico_tecnico,
  NULL AS cor, NULL AS nmotor, NULL AS nquadro, NULL AS pesobruto,
  NULL AS medidapeneu, NULL AS cilindrada, NULL AS ncilindros,
  NULL AS tipocaixa, NULL AS combustivel, NULL AS distanciaeixo, NULL AS lotacao
FROM orcamentos o
LEFT JOIN equipamento e ON e.numero_serie = o.veiculo
LEFT JOIN clientes c ON c.idclientes = e.idcliente
LEFT JOIN tipo_servico ts ON ts.idtipo_servico = o.id_tipo_servico
", $ok, $err);

// 4. VIEW dadosOrcamentosCompletoComProdutos — usa tecnico e equipamento
run($pdo, 'VIEW dadosOrcamentosCompletoComProdutos', "
CREATE OR REPLACE VIEW dadosOrcamentosCompletoComProdutos AS
SELECT
  o.idorcamentos, o.veiculo AS numero_serie, o.veiculo AS matricula,
  o.valor AS valor_servico, o.data AS data_orcamento, o.status, o.tipo,
  o.tecnico, o.tecnico AS mecanico, o.descricao,
  op.idorc_prod, op.quantidade,
  p.idproduto, p.nome AS produto, p.nome AS nome_produto, p.valor_venda, p.referencia,
  c.nif, c.nome AS nome_cliente, c.sobrenome,
  ts.nome AS tipo_servico
FROM orcamentos o
INNER JOIN orc_prod op ON op.orcamentos = o.idorcamentos
INNER JOIN produto p ON p.idproduto = op.produtos
LEFT JOIN equipamento e ON e.numero_serie = o.veiculo
LEFT JOIN clientes c ON c.idclientes = e.idcliente
LEFT JOIN tipo_servico ts ON ts.idtipo_servico = o.id_tipo_servico
", $ok, $err);

// 5. Coluna pago em conntas_areceber
run($pdo, 'ALTER conntas_areceber ADD pago', "
    ALTER TABLE conntas_areceber
    ADD COLUMN IF NOT EXISTS pago VARCHAR(3) NOT NULL DEFAULT 'nao' AFTER valortotal
", $ok, $err);

echo '<pre>';
echo "=== OK ===\n" . implode("\n", $ok) . "\n\n";
echo "=== ERROS ===\n" . implode("\n", $err) . "\n";
echo '</pre>';
echo '<p style="color:red"><strong>APAGAR este ficheiro (migrar.php) após usar!</strong></p>';
