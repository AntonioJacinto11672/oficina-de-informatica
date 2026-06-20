<?php

namespace App\adms\Models;
use PDO;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Description of AdmsPerfil
 *
 * @author Double
 */
class AdmsPerfil extends Conn {

    private $dados;
    private $conn;

    public function __construct() {
        $this->conn = $this->connect();
    }

    protected function limparInput($input) {
        $newConn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME, DBPORT);
        $var = mysqli_real_escape_string($newConn, $input);
        $var = htmlspecialchars($var);
        return $var;
    }

    public function editarPerfil($dados) {
        $this->dados = $dados;
        //var_dump($this->dados);
        $this->dados['emailnovo'] = $this->limparInput($this->dados['emailnovo']);
        $this->dados['email'] = $this->limparInput($this->dados['email']);
        $this->dados['nome'] = $this->limparInput($this->dados['nome']);
        $this->dados['sobrenome'] = $this->limparInput($this->dados['sobrenome']);
        $this->dados['nbi'] = $this->limparInput($this->dados['nbi']);
        $this->dados['nif'] = $this->limparInput($this->dados['nif']);
        $this->dados['telefone'] = $this->limparInput($this->dados['telefone']);
        $this->dados['idusuario'] = $this->limparInput($this->dados['idusuario']);
        $this->dados['nivel'] = $this->limparInput($this->dados['nivel']);

        if (isset($this->dados['morada'])) {
            $this->dados['morada'] = $this->limparInput($this->dados['morada']);
        }


        if ($this->valEditTecnicos()) {
            if ($this->dados['nivel'] == "adimin") {
                //var_dump($this->dados);
                $query_login = "UPDATE usuario SET nbi=:nbi, nif=:nif, nome=:nome, sobrenome=:sobrenome, email=:email, telefone=:telefone,  modified=NOW() WHERE idusuario=:idusuario";
                $result_login = $this->conn->prepare($query_login);
                $result_login->bindParam(":nome", $this->dados['nome']);
                $result_login->bindParam(":sobrenome", $this->dados['sobrenome']);
                $result_login->bindParam(":email", $this->dados['emailnovo']);
                $result_login->bindParam(":telefone", $this->dados['telefone']);
                //$result_login->bindParam(":senha", $this->dados['senha']);
                $result_login->bindParam(":idusuario", $this->dados['idusuario']);
                $result_login->bindParam(":nbi", $this->dados['nbi']);
                $result_login->bindParam(":nif", $this->dados['nif']);
                $result_login->execute();

                //$result_login->bindParam(":foto", $this->dados['novo_nome']);
                if ($result_login->rowCount()) {

                    //$_SESSION['logado'] = true;
                    $_SESSION['usuario'] = $this->dados['nivel'];
                    $_SESSION['idlogado'] = $this->dados['idusuario'];
                    $_SESSION['nome'] = $this->dados['nome'];
                    $_SESSION['sobrenome'] = $this->dados['sobrenome'];
                    $_SESSION['email'] = $this->dados['email'];
                    $_SESSION['telefone'] = $this->dados['telefone'];
                    $_SESSION['nbi'] = $this->dados['nbi'];
                    $_SESSION['nif'] = $this->dados['nif'];
                    //$_SESSION['foto'] = $this->dados['foto'];
                    $_SESSION['msg'] = '<div class="alert alert-success text-center"> Perfil Editado Com Sucesso!</div>';
                    //$this->enviarEmail();
                    return true;
                } else {
                    $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Perfil Editado Sem Sucesso</div>';
                    return false;
                }
            } elseif ($_SESSION['usuario'] == "tecnico") {
                if ($this->idTecnico()) {
                    //var_dump($this->dados);
                    $query_login = "UPDATE tecnicos SET nbi=:nbi, nif=:nif, nome=:nome, sobrenome=:sobrenome, email=:email, telefone=:telefone,  morada=:morada, modified=NOW() WHERE idtecnico=:idtecnico";
                    $result_login = $this->conn->prepare($query_login);
                    $result_login->bindParam(":nome", $this->dados['nome']);
                    $result_login->bindParam(":sobrenome", $this->dados['sobrenome']);
                    $result_login->bindParam(":email", $this->dados['emailnovo']);
                    $result_login->bindParam(":telefone", $this->dados['telefone']);
                    $result_login->bindParam(":morada", $this->dados['morada']);
                    $result_login->bindParam(":idtecnico", $this->dados['idtecnico']);
                    $result_login->bindParam(":nbi", $this->dados['nbi']);
                    $result_login->bindParam(":nif", $this->dados['nif']);
                    $result_login->execute();
                    if ($result_login->rowCount()) {
                        $query_login = "UPDATE usuario SET nbi=:nbi, nif=:nif, nome=:nome, sobrenome=:sobrenome, email=:email, telefone=:telefone,  modified=NOW() WHERE idusuario=:idusuario";
                        $result_login = $this->conn->prepare($query_login);
                        $result_login->bindParam(":nome", $this->dados['nome']);
                        $result_login->bindParam(":sobrenome", $this->dados['sobrenome']);
                        $result_login->bindParam(":email", $this->dados['emailnovo']);
                        $result_login->bindParam(":telefone", $this->dados['telefone']);
                        //$result_login->bindParam(":senha", $this->dados['senha']);
                        $result_login->bindParam(":idusuario", $this->dados['idusuario']);
                        $result_login->bindParam(":nbi", $this->dados['nbi']);
                        $result_login->bindParam(":nif", $this->dados['nif']);
                        $result_login->execute();
                        if ($result_login->rowCount()) {

                            //$_SESSION['logado'] = true;
                            $_SESSION['usuario'] = $this->dados['nivel'];
                            $_SESSION['idlogado'] = $this->dados['idusuario'];
                            $_SESSION['nome'] = $this->dados['nome'];
                            $_SESSION['sobrenome'] = $this->dados['sobrenome'];
                            $_SESSION['email'] = $this->dados['email'];
                            $_SESSION['telefone'] = $this->dados['telefone'];
                            $_SESSION['nbi'] = $this->dados['nbi'];
                            $_SESSION['nif'] = $this->dados['nif'];
                            //$_SESSION['foto'] = $this->dados['foto'];
                            $_SESSION['msg'] = '<div class="alert alert-success text-center"> Perfil Editado Com Sucesso!</div>';
                            //$this->enviarEmail();
                            return true;
                        } else {
                            $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Perfil Editado Sem Sucesso</div>';
                            return false;
                        }
                    } else {
                        $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Perfil Editado Sem Sucesso! Mecânica table</div>';
                        return false;
                    }
                }
            } elseif ($_SESSION['usuario'] == "recep") {
                if ($this->idRecepcionista()) {
                    //var_dump($this->dados);
                    $query_login = "UPDATE recepcionista SET nbi=:nbi, nif=:nif, nome=:nome, sobrenome=:sobrenome, email=:email, telefone=:telefone,  morada=:morada, modified=NOW() WHERE idrecepcionista=:idrecepcionista";
                    $result_login = $this->conn->prepare($query_login);
                    $result_login->bindParam(":nome", $this->dados['nome']);
                    $result_login->bindParam(":sobrenome", $this->dados['sobrenome']);
                    $result_login->bindParam(":email", $this->dados['emailnovo']);
                    $result_login->bindParam(":telefone", $this->dados['telefone']);
                    $result_login->bindParam(":morada", $this->dados['morada']);
                    $result_login->bindParam(":idrecepcionista", $this->dados['idrecepcionista']);
                    $result_login->bindParam(":nbi", $this->dados['nbi']);
                    $result_login->bindParam(":nif", $this->dados['nif']);
                    $result_login->execute();
                    if ($result_login->rowCount()) {
                        $query_login = "UPDATE usuario SET nbi=:nbi, nif=:nif, nome=:nome, sobrenome=:sobrenome, email=:email, telefone=:telefone,  modified=NOW() WHERE idusuario=:idusuario";
                        $result_login = $this->conn->prepare($query_login);
                        $result_login->bindParam(":nome", $this->dados['nome']);
                        $result_login->bindParam(":sobrenome", $this->dados['sobrenome']);
                        $result_login->bindParam(":email", $this->dados['emailnovo']);
                        $result_login->bindParam(":telefone", $this->dados['telefone']);
                        //$result_login->bindParam(":senha", $this->dados['senha']);
                        $result_login->bindParam(":idusuario", $this->dados['idusuario']);
                        $result_login->bindParam(":nbi", $this->dados['nbi']);
                        $result_login->bindParam(":nif", $this->dados['nif']);
                        $result_login->execute();
                        if ($result_login->rowCount()) {

                            //$_SESSION['logado'] = true;
                            $_SESSION['usuario'] = $this->dados['nivel'];
                            $_SESSION['idlogado'] = $this->dados['idusuario'];
                            $_SESSION['nome'] = $this->dados['nome'];
                            $_SESSION['sobrenome'] = $this->dados['sobrenome'];
                            $_SESSION['email'] = $this->dados['email'];
                            $_SESSION['telefone'] = $this->dados['telefone'];
                            $_SESSION['nbi'] = $this->dados['nbi'];
                            $_SESSION['nif'] = $this->dados['nif'];
                            //$_SESSION['foto'] = $this->dados['foto'];
                            $_SESSION['msg'] = '<div class="alert alert-success text-center"> Perfil Editado Com Sucesso!</div>';
                            //$this->enviarEmail();
                            return true;
                        } else {
                            $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Perfil Editado Sem Sucesso</div>';
                            return false;
                        }
                    } else {
                        $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Perfil Editado Sem Sucesso! Mecânica table</div>';
                        return false;
                    }
                }
            } else {
                $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Usuario Desconhecido, Não Podes Fazer Alterações No Sistema,<br> Terminar A Sessão E iniciar Uma Nova, Caso Essa Mensagem Precista Contactar O Adiministrador do Sistema</div>';
                return false;
            }
        }
    }

    private function valEditTecnicos() {
        $queryValBI = "SELECT nbi FROM usuario WHERE nbi LIKE '{$this->dados['nbi']}'  AND idusuario!=:idusuario";
        $result_ValBI = $this->conn->prepare($queryValBI);
        $result_ValBI->bindParam(":idusuario", $this->dados['idusuario']);
        $result_ValBI->execute();

        $queryValEmail = "SELECT email FROM usuario WHERE email LIKE '{$this->dados['emailnovo']}' AND idusuario!='{$this->dados['idusuario']}'";
        $result_ValEmail = $this->conn->prepare($queryValEmail);
        $result_ValEmail->execute();

        $queryValNif = "SELECT nif FROM usuario WHERE nif LIKE '{$this->dados['nif']}' AND idusuario!='{$this->dados['idusuario']}'";
        $result_ValNif = $this->conn->prepare($queryValNif);
        $result_ValNif->execute();
        //var_dump($this->dados);


        if ($result_ValBI->rowCount()) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Número do Bilhete Existente Insira Um Número de BI Que não Existe No Sistema!</div>';
            return false;
        } else {
            if ($result_ValEmail->rowCount()) {
                $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Email Existente Insira Um E-mail Que não Existe No Sistema!</div>';
                return false;
            } else {
                if ($result_ValNif->rowCount()) {
                    $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Número de Indetificação Fiscal Existente Insira Um (NIF) Que não Existe No Sistema!</div>';
                    return false;
                } else {
                    //$_SESSION['smg'] = '<div class="alert alert-success text-center">Apagado Com Sucesso!</div>';
                    return true;
                }
            }
        }
    }

    private function idTecnico() {
        $result = $this->conn->prepare("SELECT * FROM tecnicos WHERE  nbi = '{$this->dados['nbiantigo']}' AND nif = '{$this->dados['nifantigo']}' AND email = '{$this->dados['email']}' LIMIT 1");
        $result->execute();
        if ($result->rowCount()) {
            $resultado = $result->fetch();
            $this->dados['idtecnico'] = $resultado['idtecnico'];
            return true;
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Mecânico Editado Sem Sucesso, Caso Essa Mensagem Precista Contactar O Adiministrador do Sistema!</div>';
            return false;
        }
    }

    private function idRecepcionista() {
        $result = $this->conn->prepare("SELECT * FROM recepcionista WHERE  nbi = '{$this->dados['nbiantigo']}' AND nif = '{$this->dados['nifantigo']}' AND email = '{$this->dados['email']}' LIMIT 1");
        $result->execute();
        if ($result->rowCount()) {
            $resultado = $result->fetch();
            $this->dados['idrecepcionista'] = $resultado['idrecepcionista'];
            return true;
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Recepcionista Editado Sem Sucesso, Caso Não Essa Mensagem Precista Contactar O Adiministrador do Sistema!</div>';
            return false;
        }
    }

}

