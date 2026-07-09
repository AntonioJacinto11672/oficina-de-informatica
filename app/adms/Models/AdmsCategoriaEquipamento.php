<?php

namespace App\adms\Models;

use PDO;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Categorias de Equipamento (módulo 4).
 */
class AdmsCategoriaEquipamento extends Conn {

    private $conn;

    public function __construct() {
        $this->conn = $this->connect();
    }

    protected function limparInput($input) {
        return htmlspecialchars(strip_tags(trim((string)$input)));
    }

    public function dadosCategoriaEquipamento(): array {
        $stmt = $this->conn->prepare("
            SELECT c.*, (SELECT COUNT(*) FROM equipamento e WHERE e.idcategoria_equipamento = c.idcategoria_equipamento) AS total_equipamentos
            FROM categoria_equipamento c
            ORDER BY c.nome
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cdsCategoriaEquipamento(array $dados): bool {
        $nome = $this->limparInput($dados['nome']);
        $stmt = $this->conn->prepare("INSERT INTO categoria_equipamento (nome, created) VALUES (:nome, NOW())");
        $stmt->bindParam(':nome', $nome);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center"> Categoria Cadastrada Com Sucesso!</div>';
            return true;
        }
        $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Categoria Cadastrada Sem Sucesso!</div>';
        return false;
    }

    public function editCategoriaEquipamento(array $dados): bool {
        $id = (int)$this->limparInput($dados['idcategoria_equipamento']);
        $nome = $this->limparInput($dados['nome']);
        $stmt = $this->conn->prepare("UPDATE categoria_equipamento SET nome=:nome WHERE idcategoria_equipamento=:id");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center"> Categoria Editada Com Sucesso!</div>';
            return true;
        }
        $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Categoria Editada Sem Sucesso!</div>';
        return false;
    }

    public function deleteCategoriaEquipamento(array $dados): bool {
        $id = (int)$this->limparInput($dados['idcategoria_equipamento']);
        $stmt = $this->conn->prepare("DELETE FROM categoria_equipamento WHERE idcategoria_equipamento=:id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center"> Categoria Eliminada Com Sucesso!</div>';
            return true;
        }
        $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Categoria Eliminada Sem Sucesso!</div>';
        return false;
    }
}
