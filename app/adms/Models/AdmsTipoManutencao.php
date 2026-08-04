<?php

namespace App\adms\Models;

use PDO;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Tipos de Manutenção — catálogo técnico (Preventiva/Corretiva), sem preço.
 */
class AdmsTipoManutencao extends Conn {

    private $conn;

    public function __construct() {
        $this->conn = $this->connect();
    }

    protected function limparInput($input) {
        return htmlspecialchars(strip_tags(trim((string)$input)));
    }

    public function dadosTipos(): array {
        $stmt = $this->conn->prepare("SELECT * FROM tipo_manutencao ORDER BY categoria, nome");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cdsTipo(array $dados): bool {
        $nome = $this->limparInput($dados['nome'] ?? '');
        $categoria = in_array($dados['categoria'] ?? '', ['Preventiva', 'Corretiva'], true) ? $dados['categoria'] : 'Corretiva';
        $descricao = $this->limparInput($dados['descricao'] ?? '');

        if ($nome === '') {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">Preencha os campos obrigatórios.</div>';
            return false;
        }

        $stmt = $this->conn->prepare("INSERT INTO tipo_manutencao (nome, categoria, descricao, created) VALUES (:nome, :categoria, :descricao, NOW())");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Tipo de manutenção registado com sucesso.</div>';
            return true;
        }
        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível guardar os dados.</div>';
        return false;
    }

    public function editTipo(array $dados): bool {
        $id = (int)$this->limparInput($dados['idtipo_manutencao']);
        $nome = $this->limparInput($dados['nome'] ?? '');
        $categoria = in_array($dados['categoria'] ?? '', ['Preventiva', 'Corretiva'], true) ? $dados['categoria'] : 'Corretiva';
        $descricao = $this->limparInput($dados['descricao'] ?? '');

        $stmt = $this->conn->prepare("UPDATE tipo_manutencao SET nome=:nome, categoria=:categoria, descricao=:descricao WHERE idtipo_manutencao=:id");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':categoria', $categoria);
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

    public function deleteTipo(array $dados): bool {
        $id = (int)$this->limparInput($dados['idtipo_manutencao']);
        $stmt = $this->conn->prepare("DELETE FROM tipo_manutencao WHERE idtipo_manutencao=:id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Tipo de manutenção eliminado com sucesso.</div>';
            return true;
        }
        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível eliminar o tipo de manutenção.</div>';
        return false;
    }
}
