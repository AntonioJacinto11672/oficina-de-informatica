<?php

namespace App\adms\Models;

use PDO;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Autenticação de utilizadores (Gerente/Técnico).
 */
class AdmsLogin extends Conn {

    private $dados;
    private $conn;
    private $resultadoBd;
    private $resultado = false;

    public function getResultado() {
        return $this->resultado;
    }

    public function login(?array $dados = null) {
        $this->dados = $dados;
        $senhaSubmetida = (string)($this->dados['btnPassword'] ?? '');
        $email = (string)($this->dados['btnUsuario'] ?? '');
        $this->conn = $this->connect();

        $query = "SELECT idusuario, nbi, nif, nome, sobrenome, email, telefone, senha, nivel, st_conta, foto, created
                  FROM usuario WHERE email=:email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $this->resultadoBd = $stmt->fetch();

        if (!$this->resultadoBd) {
            $_SESSION['msg'] = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Erro:</strong> E-mail ou senha incorretos.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>';
            $this->resultado = false;
            return;
        }

        if (!password_verify($senhaSubmetida, $this->resultadoBd['senha'])) {
            $_SESSION['msg'] = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Erro:</strong> E-mail ou senha incorretos.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>';
            $this->resultado = false;
            return;
        }

        if ($this->resultadoBd['st_conta'] !== 'Ativada') {
            $_SESSION['msg'] = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Erro:</strong> A sua conta está desativada. Contacte o Gerente de TI.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>';
            $this->resultado = false;
            return;
        }

        session_regenerate_id(true);

        $_SESSION['logado'] = true;
        $_SESSION['nivel'] = $this->resultadoBd['nivel'];
        $_SESSION['usuario'] = $this->resultadoBd['nivel']; // mantido por compatibilidade com views existentes
        $_SESSION['idlogado'] = $this->resultadoBd['idusuario'];
        $_SESSION['nome'] = $this->resultadoBd['nome'];
        $_SESSION['sobrenome'] = $this->resultadoBd['sobrenome'];
        $_SESSION['email'] = $this->resultadoBd['email'];
        $_SESSION['telefone'] = $this->resultadoBd['telefone'];
        $_SESSION['nbi'] = $this->resultadoBd['nbi'];
        $_SESSION['nif'] = $this->resultadoBd['nif'];
        $_SESSION['foto'] = $this->resultadoBd['foto'];

        $idLogin = $this->resultadoBd['idusuario'];
        $acessou = "Acesso";
        $stmtLog = $this->conn->prepare("INSERT INTO control_usuario(id_usuario, data_hora, accao) VALUES (:id_login, NOW(), :acessou)");
        $stmtLog->bindParam(':id_login', $idLogin, PDO::PARAM_INT);
        $stmtLog->bindParam(':acessou', $acessou, PDO::PARAM_STR);
        $stmtLog->execute();

        $this->resultado = true;
    }

}
