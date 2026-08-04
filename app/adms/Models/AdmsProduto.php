<?php

namespace App\adms\Models;

use PDO;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Peças e Consumíveis — stock interno de manutenção, sem preço de venda.
 */
class AdmsProduto extends Conn {

    private $conn;

    public function __construct() {
        $this->conn = $this->connect();
    }

    protected function limparInput($input) {
        return htmlspecialchars(strip_tags(trim((string)$input)));
    }

    private function upload($ficheiro): ?string {
        if (empty($ficheiro['name']) || empty($ficheiro['tmp_name']) || !is_uploaded_file($ficheiro['tmp_name'])) {
            return null;
        }
        $formatos = ["png", "jpg", "jpeg"];
        $mimesPermitidos = ["image/png", "image/jpeg"];
        $extensao = strtolower(pathinfo($ficheiro['name'], PATHINFO_EXTENSION));
        $mimeReal = function_exists('finfo_open') ? (new \finfo(FILEINFO_MIME_TYPE))->file($ficheiro['tmp_name']) : null;

        if (!in_array($extensao, $formatos, true) || ($mimeReal !== null && !in_array($mimeReal, $mimesPermitidos, true))) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">Ficheiro não permitido. Escolha uma imagem (png, jpg, jpeg).</div>';
            return null;
        }
        if ($ficheiro['size'] > 5 * 1024 * 1024) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">A imagem não pode exceder 5 MB.</div>';
            return null;
        }
        $pasta = "app/adms/assets/foto/";
        $novoNome = uniqid() . "." . $extensao;
        if (move_uploaded_file($ficheiro['tmp_name'], $pasta . $novoNome)) {
            return $novoNome;
        }
        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível carregar o ficheiro.</div>';
        return null;
    }

    public function dadosProdutos(): array {
        $stmt = $this->conn->prepare("SELECT * FROM dadosProduto ORDER BY nome");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function dadosProdutosEstoqueBaixo(): array {
        $stmt = $this->conn->prepare("SELECT * FROM dadosProduto WHERE estoque < estoque_minimo ORDER BY estoque ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function dadosProduto($id) {
        $stmt = $this->conn->prepare("SELECT * FROM produto WHERE idproduto = :id LIMIT 1");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function cdsProduto(array $dados): bool {
        $nome = $this->limparInput($dados['nome'] ?? '');
        $referencia = $this->limparInput($dados['referencia'] ?? '');
        $idCategoria = !empty($dados['categoria']) ? (int)$dados['categoria'] : null;
        $idFornecedor = !empty($dados['fornecedor']) ? (int)$dados['fornecedor'] : null;
        $estoque = (int)($dados['estoque'] ?? 0);
        $estoqueMinimo = isset($dados['estoque_minimo']) && $dados['estoque_minimo'] !== '' ? (int)$dados['estoque_minimo'] : 5;
        $custoAquisicao = isset($dados['custo_aquisicao']) && $dados['custo_aquisicao'] !== '' ? (float)$dados['custo_aquisicao'] : 0.00;
        $descricao = $this->limparInput($dados['descricao'] ?? '');
        $foto = $this->upload($dados['foto'] ?? []);

        if ($nome === '') {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">Preencha os campos obrigatórios.</div>';
            return false;
        }

        $stmt = $this->conn->prepare("INSERT INTO produto (idfornecedor, idcategoria, referencia, nome, custo_aquisicao, estoque, estoque_minimo, descricao, foto, created)
                                       VALUES (:idfornecedor, :idcategoria, :referencia, :nome, :custo_aquisicao, :estoque, :estoque_minimo, :descricao, :foto, NOW())");
        $stmt->bindParam(':idfornecedor', $idFornecedor, PDO::PARAM_INT);
        $stmt->bindParam(':idcategoria', $idCategoria, PDO::PARAM_INT);
        $stmt->bindParam(':referencia', $referencia);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':custo_aquisicao', $custoAquisicao);
        $stmt->bindParam(':estoque', $estoque, PDO::PARAM_INT);
        $stmt->bindParam(':estoque_minimo', $estoqueMinimo, PDO::PARAM_INT);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':foto', $foto);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $idProduto = (int)$this->conn->lastInsertId();
            if ($estoque > 0) {
                (new AdmsMovimentoEstoque())->registar($idProduto, 'Entrada', $estoque, 'CadastroInicial', $idProduto, $_SESSION['idlogado'] ?? null);
            }
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Peça registada com sucesso.</div>';
            return true;
        }
        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível guardar os dados.</div>';
        return false;
    }

    public function editProduto(array $dados): bool {
        $id = (int)$this->limparInput($dados['idproduto']);
        $nome = $this->limparInput($dados['nome'] ?? '');
        $referencia = $this->limparInput($dados['referencia'] ?? '');
        $idCategoria = !empty($dados['categoria']) ? (int)$dados['categoria'] : null;
        $idFornecedor = !empty($dados['fornecedor']) ? (int)$dados['fornecedor'] : null;
        $estoqueMinimo = isset($dados['estoque_minimo']) && $dados['estoque_minimo'] !== '' ? (int)$dados['estoque_minimo'] : 5;
        $custoAquisicao = isset($dados['custo_aquisicao']) && $dados['custo_aquisicao'] !== '' ? (float)$dados['custo_aquisicao'] : 0.00;
        $descricao = $this->limparInput($dados['descricao'] ?? '');

        $stmt = $this->conn->prepare("UPDATE produto SET idfornecedor=:idfornecedor, idcategoria=:idcategoria, referencia=:referencia,
                        nome=:nome, custo_aquisicao=:custo_aquisicao, estoque_minimo=:estoque_minimo, descricao=:descricao
                      WHERE idproduto=:id");
        $stmt->bindParam(':idfornecedor', $idFornecedor, PDO::PARAM_INT);
        $stmt->bindParam(':idcategoria', $idCategoria, PDO::PARAM_INT);
        $stmt->bindParam(':referencia', $referencia);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':custo_aquisicao', $custoAquisicao);
        $stmt->bindParam(':estoque_minimo', $estoqueMinimo, PDO::PARAM_INT);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Dados atualizados com sucesso.</div>';
            return true;
        }
        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível guardar os dados.</div>';
        return false;
    }

    public function editFotoProduto(array $dados): bool {
        $id = (int)$this->limparInput($dados['idproduto']);
        $foto = $this->upload($dados['foto'] ?? []);
        if (!$foto) {
            return false;
        }
        $stmt = $this->conn->prepare("UPDATE produto SET foto=:foto WHERE idproduto=:id");
        $stmt->bindParam(':foto', $foto);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Fotografia atualizada com sucesso.</div>';
            return true;
        }
        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível atualizar a fotografia.</div>';
        return false;
    }

    public function deleteProduto(array $dados): bool {
        $id = (int)$this->limparInput($dados['idproduto']);
        $stmt = $this->conn->prepare("DELETE FROM produto WHERE idproduto=:id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Peça eliminada com sucesso.</div>';
            return true;
        }
        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível eliminar a peça. Verifique se está associada a execuções de manutenção.</div>';
        return false;
    }

    public function addEstoque(array $dados): bool {
        $id = (int)$this->limparInput($dados['idproduto']);
        $quantidade = (int)($dados['estoque'] ?? 0);
        if ($quantidade <= 0) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">Indique uma quantidade válida.</div>';
            return false;
        }

        $stmt = $this->conn->prepare("UPDATE produto SET estoque = estoque + :quantidade WHERE idproduto=:id");
        $stmt->bindParam(':quantidade', $quantidade, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            (new AdmsMovimentoEstoque())->registar($id, 'Entrada', $quantidade, 'AjusteManual', $id, $_SESSION['idlogado'] ?? null);
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Stock atualizado com sucesso.</div>';
            return true;
        }
        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível atualizar o stock.</div>';
        return false;
    }
}
