<?php

namespace App\adms\Models;

use PDO;
use Mpdf\Mpdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Description of AdmsLogin
 *
 * @author Double
 */
class AdmsLogin extends Conn {

    // Deixado  Aberto para fazer o Controlo dos Dados e Acção
    private $dados;
    private $conn;
    private $resultadoBd;
    private $resultado = false;

    public function getResultado() {
        return $this->resultado;
    }

    public function login(array $dados = null) {
        $this->dados = $dados;
        $this->dados['btnPassword'] = md5($this->dados['btnPassword']);
        $this->conn = $this->connect();

        //SELECT ADM
        $query_email = "SELECT idusuario,nbi, nif, nome, sobrenome, email,telefone, senha, nivel, st_conta, created 
        FROM usuario WHERE email=:email LIMIT 1";
        $resultado_query_email = $this->conn->prepare($query_email);
        $resultado_query_email->bindParam(':email', $this->dados['btnUsuario'], PDO::PARAM_STR);
        $resultado_query_email->execute();
        $this->resultadoBd = $resultado_query_email->fetch();
        //var_dump($this->resultadoBd);

        if ($this->resultadoBd) {
            $query_senha = "SELECT  idusuario, nbi, nif, nome, sobrenome, email, telefone, senha, nivel, st_conta, foto, created  FROM usuario WHERE email=:email AND senha=:senha";
            $resultado_query_senha = $this->conn->prepare($query_senha);
            $resultado_query_senha->bindParam(':email', $this->dados['btnUsuario'], PDO::PARAM_STR);
            $resultado_query_senha->bindParam(':senha', $this->dados['btnPassword'], PDO::PARAM_STR);
            $resultado_query_senha->execute();
            $this->resultadoBd = $resultado_query_senha->fetch();
            if ($this->resultadoBd) {
                if ($this->resultadoBd['st_conta'] == "Ativada") {


                    var_dump($this->resultadoBd);
                    $_SESSION['logado'] = true;
                    $_SESSION['usuario'] = $this->resultadoBd['nivel'];
                    $_SESSION['idlogado'] = $this->resultadoBd['idusuario'];
                    $_SESSION['nome'] = $this->resultadoBd['nome'];
                    $_SESSION['sobrenome'] = $this->resultadoBd['sobrenome'];
                    $_SESSION['email'] = $this->resultadoBd['email'];
                    $_SESSION['telefone'] = $this->resultadoBd['telefone'];
                    $_SESSION['nbi'] = $this->resultadoBd['nbi'];
                    $_SESSION['nif'] = $this->resultadoBd['nif'];
                    $_SESSION['foto'] = $this->resultadoBd['foto'];


                    $id_login = $this->resultadoBd['idusuario'];
                    //$data_hora = date('Y-m-d H:i:s');
                    $acessou = "Acessou";

                    $control_conta = "INSERT INTO control_usuario(id_usuario, data_hora, accao) VALUES (:id_login, NOW(), :acessou)";
                    $resultado_query_control = $this->conn->prepare($control_conta);
                    $resultado_query_control->bindParam(':id_login', $id_login, PDO::PARAM_INT);
                    $resultado_query_control->bindParam(':acessou', $acessou, PDO::PARAM_STR);
                    $resultado_query_control->execute();

                    if ($resultado_query_control->rowCount()) {
                        $this->resultado = true;
                    } else {
                        $_SESSION['msg'] = '
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Erro:!</strong> Tipo De Conta  ' . $this->resultadoBd["nivel"] . '  Não está fazer control dos dados.
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                ';
                        $this->resultado = false;
                    }
                } else {
                    $_SESSION['msg'] = '
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Erro:!</strong> A Sua Conta Está Desativada Fale Com O Gerente!.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                ';
                }
            } else {
                $_SESSION['msg'] = '
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Erro:!</strong> E-mail E Senha Não Conferem!.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                ';
                $this->resultado = false;
            }
        } else {
            $_SESSION['msg'] = '
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Erro:</strong> E-mail Desconhecido!.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                ';
            $this->resultado = false;
        }
    }

}
