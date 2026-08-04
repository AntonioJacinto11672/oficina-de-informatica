<?php

namespace App\adms\Models;

use PDO;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Compras a Fornecedores — entrada de equipamentos, peças e consumíveis.
 * Sem qualquer ligação a contas a pagar/financeiro (não é uma oficina comercial).
 */
class AdmsCompras extends Conn {

    private $conn;

    public function __construct() {
        $this->conn = $this->connect();
    }

    protected function limparInput($input) {
        return htmlspecialchars(strip_tags(trim((string)$input)));
    }

    public function dadosCompras(): array {
        $stmt = $this->conn->prepare("SELECT * FROM dadosCompras ORDER BY data DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function dadosFornecedores(): array {
        $stmt = $this->conn->prepare("SELECT idfornecedor, nome FROM fornecedor ORDER BY nome");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function dadosProdutos(): array {
        $stmt = $this->conn->prepare("SELECT idproduto, nome FROM produto ORDER BY nome");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cdsCompra(array $dados): bool {
        $idFornecedor = !empty($dados['idfornecedor']) ? (int)$dados['idfornecedor'] : null;
        $idProduto = !empty($dados['idproduto']) ? (int)$dados['idproduto'] : null;
        $quantidade = max(1, (int)($dados['quantidade'] ?? 1));
        $custoUnitario = isset($dados['custo_unitario']) && $dados['custo_unitario'] !== '' ? (float)$dados['custo_unitario'] : 0.00;
        $observacoes = $this->limparInput($dados['observacoes'] ?? '') ?: null;
        $idUsuario = $_SESSION['idlogado'] ?? null;

        if (!$idProduto) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">Preencha os campos obrigatórios.</div>';
            return false;
        }

        $stmt = $this->conn->prepare("
            INSERT INTO compras (idfornecedor, idproduto, quantidade, custo_unitario, idusuario, observacoes, data)
            VALUES (:idfornecedor, :idproduto, :quantidade, :custo_unitario, :idusuario, :observacoes, NOW())
        ");
        $stmt->bindParam(':idfornecedor', $idFornecedor, PDO::PARAM_INT);
        $stmt->bindParam(':idproduto', $idProduto, PDO::PARAM_INT);
        $stmt->bindParam(':quantidade', $quantidade, PDO::PARAM_INT);
        $stmt->bindParam(':custo_unitario', $custoUnitario);
        $stmt->bindParam(':idusuario', $idUsuario, PDO::PARAM_INT);
        $stmt->bindParam(':observacoes', $observacoes);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $idCompra = (int)$this->conn->lastInsertId();
            $upd = $this->conn->prepare("UPDATE produto SET estoque = estoque + :quantidade WHERE idproduto = :id");
            $upd->bindParam(':quantidade', $quantidade, PDO::PARAM_INT);
            $upd->bindParam(':id', $idProduto, PDO::PARAM_INT);
            $upd->execute();
            (new AdmsMovimentoEstoque())->registar($idProduto, 'Entrada', $quantidade, 'Compra', $idCompra, $idUsuario);
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Compra registada com sucesso.</div>';
            return true;
        }
        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível guardar os dados.</div>';
        return false;
    }

    public function deleteCompra(array $dados): bool {
        $id = (int)$this->limparInput($dados['idcompras']);

        $stmt = $this->conn->prepare("SELECT idproduto, quantidade FROM compras WHERE idcompras = :id LIMIT 1");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $compra = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$compra) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">Compra não encontrada.</div>';
            return false;
        }

        $del = $this->conn->prepare("DELETE FROM compras WHERE idcompras = :id");
        $del->bindParam(':id', $id, PDO::PARAM_INT);
        $del->execute();

        if ($del->rowCount() > 0) {
            if (!empty($compra['idproduto'])) {
                $upd = $this->conn->prepare("UPDATE produto SET estoque = GREATEST(0, estoque - :quantidade) WHERE idproduto = :id");
                $upd->bindParam(':quantidade', $compra['quantidade'], PDO::PARAM_INT);
                $upd->bindParam(':id', $compra['idproduto'], PDO::PARAM_INT);
                $upd->execute();
                (new AdmsMovimentoEstoque())->registar((int)$compra['idproduto'], 'Saida', (int)$compra['quantidade'], 'CompraCancelada', $id, $_SESSION['idlogado'] ?? null);
            }
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Compra eliminada com sucesso.</div>';
            return true;
        }
        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível eliminar a compra.</div>';
        return false;
    }
}
