<?php

namespace App\adms\Models;

use PDO;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Departamentos da Universidade Lusíada de Angola — a quem os equipamentos
 * informáticos pertencem.
 */
class AdmsDepartamento extends Conn {

    private $conn;

    public function __construct() {
        $this->conn = $this->connect();
    }

    protected function limparInput($input) {
        return htmlspecialchars(strip_tags(trim((string)$input)));
    }

    public function dadosDepartamentos(): array {
        $stmt = $this->conn->prepare("
            SELECT d.*, (SELECT COUNT(*) FROM equipamento e WHERE e.iddepartamento = d.iddepartamento) AS total_equipamentos
            FROM departamentos d ORDER BY d.nome
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cdsDepartamento(array $dados): bool {
        $nome = $this->limparInput($dados['nome'] ?? '');
        $responsavel = $this->limparInput($dados['responsavel'] ?? '');
        $telefone = $this->limparInput($dados['telefone'] ?? '');
        $email = $this->limparInput($dados['email'] ?? '');

        if ($nome === '') {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">Preencha os campos obrigatórios.</div>';
            return false;
        }

        $stmt = $this->conn->prepare("INSERT INTO departamentos (nome, responsavel, telefone, email, created) VALUES (:nome, :responsavel, :telefone, :email, NOW())");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':responsavel', $responsavel);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Departamento registado com sucesso.</div>';
            return true;
        }
        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível guardar os dados.</div>';
        return false;
    }

    public function editDepartamento(array $dados): bool {
        $id = (int)$this->limparInput($dados['iddepartamento']);
        $nome = $this->limparInput($dados['nome'] ?? '');
        $responsavel = $this->limparInput($dados['responsavel'] ?? '');
        $telefone = $this->limparInput($dados['telefone'] ?? '');
        $email = $this->limparInput($dados['email'] ?? '');

        $stmt = $this->conn->prepare("UPDATE departamentos SET nome=:nome, responsavel=:responsavel, telefone=:telefone, email=:email WHERE iddepartamento=:id");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':responsavel', $responsavel);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Dados atualizados com sucesso.</div>';
            return true;
        }
        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível guardar os dados.</div>';
        return false;
    }

    public function deleteDepartamento(array $dados): bool {
        $id = (int)$this->limparInput($dados['iddepartamento']);
        $stmt = $this->conn->prepare("DELETE FROM departamentos WHERE iddepartamento=:id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Departamento eliminado com sucesso.</div>';
            return true;
        }
        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível eliminar o departamento.</div>';
        return false;
    }
}
