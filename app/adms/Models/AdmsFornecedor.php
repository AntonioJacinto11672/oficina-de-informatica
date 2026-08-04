<?php

namespace App\adms\Models;

use PDO;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Fornecedores — fornecem equipamentos, peças e consumíveis à Universidade.
 */
class AdmsFornecedor extends Conn {

    private $conn;

    public function __construct() {
        $this->conn = $this->connect();
    }

    protected function limparInput($input) {
        return htmlspecialchars(strip_tags(trim((string)$input)));
    }

    public function dadosFornecedores(): array {
        $stmt = $this->conn->prepare("SELECT * FROM fornecedor ORDER BY nome");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function dadosFornecedor($id) {
        $stmt = $this->conn->prepare("SELECT * FROM fornecedor WHERE idfornecedor = :id LIMIT 1");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function cdsFornecedor(array $dados): bool {
        $nome = $this->limparInput($dados['nome'] ?? '');
        $nif = $this->limparInput($dados['nif'] ?? '');
        $tipoPessoa = $this->limparInput($dados['tipo_pessoa'] ?? 'Coletiva');
        $email = $this->limparInput($dados['email'] ?? '');
        $telefone = $this->limparInput($dados['telefone'] ?? '');
        $morada = $this->limparInput($dados['morada'] ?? '');

        if ($nome === '') {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">Preencha os campos obrigatórios.</div>';
            return false;
        }

        $stmt = $this->conn->prepare("INSERT INTO fornecedor (nif, nome, email, telefone, morada, tipo_pessoa, created)
                                       VALUES (:nif, :nome, :email, :telefone, :morada, :tipo_pessoa, NOW())");
        $stmt->bindParam(':nif', $nif);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':morada', $morada);
        $stmt->bindParam(':tipo_pessoa', $tipoPessoa);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Fornecedor registado com sucesso.</div>';
            return true;
        }
        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível guardar os dados.</div>';
        return false;
    }

    public function editFornecedor(array $dados): bool {
        $id = (int)$this->limparInput($dados['idfornecedor']);
        $nome = $this->limparInput($dados['nome'] ?? '');
        $nif = $this->limparInput($dados['nif'] ?? '');
        $tipoPessoa = $this->limparInput($dados['tipo_pessoa'] ?? 'Coletiva');
        $email = $this->limparInput($dados['emailnovo'] ?? $dados['email'] ?? '');
        $telefone = $this->limparInput($dados['telefone'] ?? '');
        $morada = $this->limparInput($dados['morada'] ?? '');

        $stmt = $this->conn->prepare("UPDATE fornecedor SET nif=:nif, nome=:nome, email=:email, telefone=:telefone, morada=:morada, tipo_pessoa=:tipo_pessoa WHERE idfornecedor=:id");
        $stmt->bindParam(':nif', $nif);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':morada', $morada);
        $stmt->bindParam(':tipo_pessoa', $tipoPessoa);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Dados atualizados com sucesso.</div>';
            return true;
        }
        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível guardar os dados.</div>';
        return false;
    }

    public function deleteFornecedor(array $dados): bool {
        $id = (int)$this->limparInput($dados['idfornecedor']);
        $stmt = $this->conn->prepare("DELETE FROM fornecedor WHERE idfornecedor=:id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Fornecedor eliminado com sucesso.</div>';
            return true;
        }
        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível eliminar o fornecedor.</div>';
        return false;
    }
}
