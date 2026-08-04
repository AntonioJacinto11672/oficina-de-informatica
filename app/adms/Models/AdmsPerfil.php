<?php

namespace App\adms\Models;

use PDO;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Edição do perfil do utilizador autenticado (Gerente ou Técnico).
 */
class AdmsPerfil extends Conn {

    private $dados;
    private $conn;

    public function __construct() {
        $this->conn = $this->connect();
    }

    protected function limparInput($input) {
        return htmlspecialchars(strip_tags(trim((string)$input)));
    }

    public function editarPerfil($dados) {
        $this->dados = $dados;
        $this->dados['emailnovo'] = $this->limparInput($this->dados['emailnovo'] ?? '');
        $this->dados['nome'] = $this->limparInput($this->dados['nome'] ?? '');
        $this->dados['sobrenome'] = $this->limparInput($this->dados['sobrenome'] ?? '');
        $this->dados['nbi'] = $this->limparInput($this->dados['nbi'] ?? '');
        $this->dados['nif'] = $this->limparInput($this->dados['nif'] ?? '');
        $this->dados['telefone'] = $this->limparInput($this->dados['telefone'] ?? '');
        $this->dados['idusuario'] = (int)$this->limparInput($this->dados['idusuario'] ?? 0);
        $this->dados['nivel'] = $this->limparInput($this->dados['nivel'] ?? '');

        if (isset($this->dados['morada'])) {
            $this->dados['morada'] = $this->limparInput($this->dados['morada']);
        }

        if (!$this->valEditUtilizador()) {
            return false;
        }

        if ($this->dados['nivel'] === 'gerente') {
            return $this->atualizarGerente();
        }
        if ($this->dados['nivel'] === 'tecnico') {
            return $this->atualizarTecnico();
        }

        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Utilizador desconhecido. Termine a sessão e inicie novamente.</div>';
        return false;
    }

    private function atualizarGerente(): bool {
        $stmt = $this->conn->prepare("UPDATE usuario SET nbi=:nbi, nif=:nif, nome=:nome, sobrenome=:sobrenome, email=:email, telefone=:telefone, modified=NOW() WHERE idusuario=:idusuario");
        $stmt->bindParam(":nome", $this->dados['nome']);
        $stmt->bindParam(":sobrenome", $this->dados['sobrenome']);
        $stmt->bindParam(":email", $this->dados['emailnovo']);
        $stmt->bindParam(":telefone", $this->dados['telefone']);
        $stmt->bindParam(":idusuario", $this->dados['idusuario'], PDO::PARAM_INT);
        $stmt->bindParam(":nbi", $this->dados['nbi']);
        $stmt->bindParam(":nif", $this->dados['nif']);
        $stmt->execute();

        if ($stmt->rowCount()) {
            $this->atualizarSessao();
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Perfil atualizado com sucesso.</div>';
            return true;
        }
        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível guardar os dados.</div>';
        return false;
    }

    private function atualizarTecnico(): bool {
        $idTecnico = $this->idTecnico();
        if (!$idTecnico) {
            return false;
        }

        $stmt = $this->conn->prepare("UPDATE tecnicos SET nbi=:nbi, nif=:nif, nome=:nome, sobrenome=:sobrenome, email=:email, telefone=:telefone, morada=:morada WHERE idtecnico=:idtecnico");
        $stmt->bindParam(":nome", $this->dados['nome']);
        $stmt->bindParam(":sobrenome", $this->dados['sobrenome']);
        $stmt->bindParam(":email", $this->dados['emailnovo']);
        $stmt->bindParam(":telefone", $this->dados['telefone']);
        $stmt->bindParam(":morada", $this->dados['morada'] ?? '');
        $stmt->bindParam(":idtecnico", $idTecnico, PDO::PARAM_INT);
        $stmt->bindParam(":nbi", $this->dados['nbi']);
        $stmt->bindParam(":nif", $this->dados['nif']);
        $stmt->execute();

        if (!$stmt->rowCount()) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível guardar os dados.</div>';
            return false;
        }

        $stmtUser = $this->conn->prepare("UPDATE usuario SET nbi=:nbi, nif=:nif, nome=:nome, sobrenome=:sobrenome, email=:email, telefone=:telefone, modified=NOW() WHERE idusuario=:idusuario");
        $stmtUser->bindParam(":nome", $this->dados['nome']);
        $stmtUser->bindParam(":sobrenome", $this->dados['sobrenome']);
        $stmtUser->bindParam(":email", $this->dados['emailnovo']);
        $stmtUser->bindParam(":telefone", $this->dados['telefone']);
        $stmtUser->bindParam(":idusuario", $this->dados['idusuario'], PDO::PARAM_INT);
        $stmtUser->bindParam(":nbi", $this->dados['nbi']);
        $stmtUser->bindParam(":nif", $this->dados['nif']);
        $stmtUser->execute();

        $this->atualizarSessao();
        $_SESSION['msg'] = '<div class="alert alert-success text-center">Perfil atualizado com sucesso.</div>';
        return true;
    }

    private function atualizarSessao(): void {
        $_SESSION['nome'] = $this->dados['nome'];
        $_SESSION['sobrenome'] = $this->dados['sobrenome'];
        $_SESSION['email'] = $this->dados['emailnovo'];
        $_SESSION['telefone'] = $this->dados['telefone'];
        $_SESSION['nbi'] = $this->dados['nbi'];
        $_SESSION['nif'] = $this->dados['nif'];
    }

    private function valEditUtilizador(): bool {
        $stmt = $this->conn->prepare("SELECT nbi FROM usuario WHERE nbi = :nbi AND idusuario <> :idusuario");
        $stmt->bindParam(':nbi', $this->dados['nbi']);
        $stmt->bindParam(':idusuario', $this->dados['idusuario'], PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount()) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">Já existe um utilizador registado com este número de BI.</div>';
            return false;
        }

        $stmt = $this->conn->prepare("SELECT email FROM usuario WHERE email = :email AND idusuario <> :idusuario");
        $stmt->bindParam(':email', $this->dados['emailnovo']);
        $stmt->bindParam(':idusuario', $this->dados['idusuario'], PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount()) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">Já existe um utilizador registado com este e-mail.</div>';
            return false;
        }

        $stmt = $this->conn->prepare("SELECT nif FROM usuario WHERE nif = :nif AND idusuario <> :idusuario");
        $stmt->bindParam(':nif', $this->dados['nif']);
        $stmt->bindParam(':idusuario', $this->dados['idusuario'], PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount()) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">Já existe um utilizador registado com este NIF.</div>';
            return false;
        }

        return true;
    }

    private function idTecnico() {
        $stmt = $this->conn->prepare("SELECT idtecnico FROM tecnicos WHERE nbi = :nbi AND nif = :nif AND email = :email LIMIT 1");
        $stmt->bindParam(':nbi', $this->dados['nbiantigo'] ?? '');
        $stmt->bindParam(':nif', $this->dados['nifantigo'] ?? '');
        $stmt->bindParam(':email', $this->dados['email'] ?? '');
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return (int)$row['idtecnico'];
        }
        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível encontrar a sua ficha de técnico. Contacte o Gerente de TI.</div>';
        return null;
    }
}
