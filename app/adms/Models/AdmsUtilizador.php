<?php

namespace App\adms\Models;

use PDO;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Gestão de contas de utilizador (Gerente/Técnico): listagem, ativação/
 * desativação e alteração de papel. A criação de contas de Técnico é feita
 * a partir do módulo Técnicos (que cria a conta de login em conjunto com a
 * ficha do técnico).
 */
class AdmsUtilizador extends Conn {

    private $conn;

    public function __construct() {
        $this->conn = $this->connect();
    }

    protected function limparInput($input) {
        return htmlspecialchars(strip_tags(trim((string)$input)));
    }

    public function dadosUtilizadores(): array {
        $stmt = $this->conn->prepare("SELECT idusuario, nome, sobrenome, email, telefone, nivel, st_conta, foto, created FROM usuario ORDER BY nivel, nome");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function alterarEstado(array $dados): bool {
        $id = (int)$this->limparInput($dados['idusuario']);
        if ((int)($_SESSION['idlogado'] ?? 0) === $id) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não pode desativar a sua própria conta.</div>';
            return false;
        }

        $atual = $this->conn->prepare("SELECT st_conta FROM usuario WHERE idusuario = :id LIMIT 1");
        $atual->bindParam(':id', $id, PDO::PARAM_INT);
        $atual->execute();
        $row = $atual->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">Utilizador não encontrado.</div>';
            return false;
        }
        $novoEstado = $row['st_conta'] === 'Ativada' ? 'Desativada' : 'Ativada';

        $stmt = $this->conn->prepare("UPDATE usuario SET st_conta = :estado WHERE idusuario = :id");
        $stmt->bindParam(':estado', $novoEstado);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Estado da conta atualizado com sucesso.</div>';
            return true;
        }
        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível atualizar o estado da conta.</div>';
        return false;
    }

    public function editarNivel(array $dados): bool {
        $id = (int)$this->limparInput($dados['idusuario']);
        $nivel = in_array($dados['nivel'] ?? '', ['gerente', 'tecnico'], true) ? $dados['nivel'] : null;
        if (!$nivel) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">Papel inválido.</div>';
            return false;
        }

        $stmt = $this->conn->prepare("UPDATE usuario SET nivel = :nivel WHERE idusuario = :id");
        $stmt->bindParam(':nivel', $nivel);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Papel do utilizador atualizado com sucesso.</div>';
            return true;
        }
        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível atualizar o papel do utilizador.</div>';
        return false;
    }
}
