<?php

namespace App\adms\Models;

use PDO;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Ledger de movimentações de stock (módulo 7) — registado ao lado de cada
 * mutação directa de produto.estoque (compras, orçamentos, execuções,
 * ajustes/estornos), sem alterar o comportamento dessas mutações.
 */
class AdmsMovimentoEstoque extends Conn {

    private $conn;

    public function __construct() {
        $this->conn = $this->connect();
    }

    public function registar(int $idProduto, string $tipo, int $quantidade, string $origem, $idReferencia = null, $idUsuario = null): void {
        if ($quantidade <= 0) {
            return;
        }
        $stmt = $this->conn->prepare("
            INSERT INTO movimento_estoque (id_produto, tipo, quantidade, origem, id_referencia, idusuario, created)
            VALUES (:id_produto, :tipo, :quantidade, :origem, :id_referencia, :idusuario, NOW())
        ");
        $stmt->bindParam(':id_produto', $idProduto, PDO::PARAM_INT);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':quantidade', $quantidade, PDO::PARAM_INT);
        $stmt->bindParam(':origem', $origem);
        $stmt->bindParam(':id_referencia', $idReferencia, PDO::PARAM_INT);
        $stmt->bindParam(':idusuario', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function dadosProduto(int $idProduto) {
        $stmt = $this->conn->prepare("SELECT * FROM dadosProduto WHERE idproduto = :id LIMIT 1");
        $stmt->bindParam(':id', $idProduto, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function dadosMovimentosProduto(int $idProduto): array {
        $stmt = $this->conn->prepare("
            SELECT m.*, u.nome AS usuario_nome, u.sobrenome AS usuario_sobrenome
            FROM movimento_estoque m
            LEFT JOIN usuario u ON u.idusuario = m.idusuario
            WHERE m.id_produto = :id
            ORDER BY m.created DESC
        ");
        $stmt->bindParam(':id', $idProduto, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
