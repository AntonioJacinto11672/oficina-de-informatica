<?php

namespace App\adms\Models;

use PDO;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Categorias de Peças e Consumíveis.
 */
class AdmsCategoria extends Conn {

    private $conn;

    public function __construct() {
        $this->conn = $this->connect();
    }

    protected function limparInput($input) {
        return htmlspecialchars(strip_tags(trim((string)$input)));
    }

    public function dadosCategorias(): array {
        $stmt = $this->conn->prepare("SELECT * FROM categoria ORDER BY nome");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cdsCategoria(array $dados): bool {
        $nome = $this->limparInput($dados['nome'] ?? '');
        if ($nome === '') {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">Preencha os campos obrigatórios.</div>';
            return false;
        }
        $stmt = $this->conn->prepare("INSERT INTO categoria (nome, created) VALUES (:nome, NOW())");
        $stmt->bindParam(':nome', $nome);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Categoria registada com sucesso.</div>';
            return true;
        }
        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível guardar os dados.</div>';
        return false;
    }

    public function editCategoria(array $dados): bool {
        $id = (int)$this->limparInput($dados['idcategoria']);
        $nome = $this->limparInput($dados['nome'] ?? '');
        $stmt = $this->conn->prepare("UPDATE categoria SET nome=:nome WHERE idcategoria=:id");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Dados atualizados com sucesso.</div>';
            return true;
        }
        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível guardar os dados.</div>';
        return false;
    }

    public function deleteCategoria(array $dados): bool {
        $id = (int)$this->limparInput($dados['idcategoria']);
        $stmt = $this->conn->prepare("DELETE FROM categoria WHERE idcategoria=:id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Categoria eliminada com sucesso.</div>';
            return true;
        }
        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível eliminar a categoria.</div>';
        return false;
    }
}
