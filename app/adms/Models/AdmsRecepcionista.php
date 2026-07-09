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
 * Description of AdmsRecepcionista
 *
 * @author Double
 */
class AdmsRecepcionista extends Conn {

    private $dados;
    private $conn;

    public function __construct() {
        $this->conn = $this->connect();
        $this->ensureDefaultClient();
    }

    private function ensureDefaultClient()
    {
        try {
            $defaultNif = '0.025.816/00-4';
            $defaultNbi = '0.025.816/00-4';
            $defaultNome = 'Universidade Lusiadas de Angola';
            $defaultSobrenome = '';
            $defaultMorada = 'Luanda, Mutamba Largo do Lumeji, nº 11/12';
            $defaultEmail = 'geral@ula.co.ao';
            $defaultTelefone = '+244 930-038-044';
            $query = "SELECT idclientes FROM clientes WHERE nif = :nif LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nif', $defaultNif);
            $stmt->execute();
            if (!$stmt->rowCount()) {
                $insert = "INSERT INTO clientes (nbi, nif, nome, sobrenome, email, telefone, morada, created)
                           VALUES (:nbi, :nif, :nome, :sobrenome, :email, :telefone, :morada, NOW())";
                $ins = $this->conn->prepare($insert);
                $ins->bindParam(':nbi', $defaultNbi);
                $ins->bindParam(':nif', $defaultNif);
                $ins->bindParam(':nome', $defaultNome);
                $ins->bindParam(':sobrenome', $defaultSobrenome);
                $ins->bindParam(':email', $defaultEmail);
                $ins->bindParam(':telefone', $defaultTelefone);
                $ins->bindParam(':morada', $defaultMorada);
                $ins->execute();
            }
        } catch (\Exception $e) {
            // não interromper execução por causa de falha na criação do cliente padrão
        }
    }

    protected function limparInput($input) {
        $newConn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME, DBPORT);
        $var = mysqli_real_escape_string($newConn, $input);
        $var = htmlspecialchars($var);
        return $var;
    }

    private function upload() {
        // Fazer o Upload da Foto 
        $formatos = array("png", "jpg", "jpeg", "pdf");
        $extensao = pathinfo($this->dados['foto']['name'], PATHINFO_EXTENSION);
        //echo "<br>extesão do Argiuivo" . $extensao;
        if (in_array($extensao, $formatos)) {
            $pasta = "app/adms/assets/foto/";
            $temporario = $this->dados['foto']['tmp_name'];
            $this->dados['novo_nome'] = uniqid() . "." . $extensao;

            //echo "<br> Temporario " . $temporario . "<br> Novo Nome " . $this->dados['novo_nome'];
            if (move_uploaded_file($temporario, $pasta . $this->dados['novo_nome'])) {
                return true;
            } else {
                $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Upload Sem Sucesso!</div>';
                return false;
            }
        } else {
            $_SESSION['msg'] = '
              <div class="alert alert-danger" role="alert">
              Ficheiro Não Permitido! Escolha Uma Imagem Com Esses Formatos (png,jpg,jpeg)
              </div>
              ';
            return false;
        }
    }

    //Contas À Pagar
    public function dadoscontaPagar() {
        $query = "SELECT * FROM contas_apagar ORDER BY pago ASC, data_venci ASC";
        $result = $this->conn->prepare($query);
        $result->execute();
        $this->dados = $result->fetchAll();
        return $this->dados;
    }

    public function dadosFuncionario() {
        $query = "SELECT * FROM usuario";
        $result = $this->conn->prepare($query);
        $result->execute();
        $this->dados = $result->fetchAll();
        return $this->dados;
    }

    public function cdsContaApagar($dados) {
        $this->dados = $dados;
        $this->dados['descricao'] = $this->limparInput($this->dados['descricao']);
        $this->dados['funcionario'] = $_SESSION['nif'];
        $this->dados['valor'] = $this->limparInput($this->dados['valor']);
        $this->dados['data_venci'] = $this->limparInput($this->dados['data_venci']);
        $this->dados['pago'] = "nao";
        if ($this->upload()) {
            //  var_dump($this->dados);
            $query = "INSERT INTO contas_apagar (descricao, valor, funcionario, data_venci, foto, pago, created) VALUES (:descricao, :valor, :funcionario, :data_venci, :foto, :pago, NOW())";
            $result = $this->conn->prepare($query);
            $result->bindParam(":descricao", $this->dados['descricao']);
            $result->bindParam(":valor", $this->dados['valor']);
            $result->bindParam(":funcionario", $_SESSION['nif']);
            $result->bindParam(":data_venci", $this->dados['data_venci']);
            $result->bindParam(":foto", $this->dados['novo_nome']);
            $result->bindParam(":pago", $this->dados['pago']);
            $result->execute();
            if ($result->rowCount()) {
                $_SESSION['msg'] = '<div class="alert alert-success text-center"> Contas À Pagar Cadastrado Com Sucesso!</div>';
                //$this->enviarEmail();
                return true;
            } else {
                $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Contas À Pagar Cadastrado Sem Sucesso!</div>';
                return false;
            }
        }
    }

    public function aprovarContaPagar($dados) {
        $this->dados = $dados;
        $this->dados['idcontas_apagar'] = $this->limparInput($this->dados['idcontas_apagar']);
        $this->dados['pago'] = "sim";
        $query = "UPDATE contas_apagar SET  pago=:pago WHERE idcontas_apagar=:idcontas_apagar";
        $result = $this->conn->prepare($query);
        $result->bindParam(":pago", $this->dados['pago']);
        $result->bindParam(":idcontas_apagar", $this->dados['idcontas_apagar']);
        $result->execute();
        if ($result->rowCount()) {
            $this->dados['tipo'] = "Saída";
            $this->cdsMovimentacao();
            $_SESSION['msg'] = '<div class="alert alert-success text-center"> Conta À Pagar Aprovada Com Sucesso!</div>';
            header("Location: " . URLADM . "contasPagar");
            exit;
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Contas À Pagar Aprovada Sem Sucesso!</div>';
            header("Location: " . URLADM . "contasPagar");
            exit;
        }
    }

    //Cliente
    public function dadosClientes() {
        $query = "SELECT * FROM clientes";
        $result = $this->conn->prepare($query);
        $result->execute();
        $this->dados = $result->fetchAll();
        return $this->dados;
    }

    public function cdsCliente($dados) {
        $this->dados = $dados;
        $this->dados['email'] = $this->limparInput($this->dados['email']);
        $this->dados['nome'] = $this->limparInput($this->dados['nome']);
        $this->dados['sobrenome'] = $this->limparInput($this->dados['sobrenome']);
        $this->dados['nbi'] = $this->limparInput($this->dados['nbi']);
        $this->dados['nif'] = $this->limparInput($this->dados['nif']);
        $this->dados['telefone'] = $this->limparInput($this->dados['telefone']);
        $this->dados['morada'] = $this->limparInput($this->dados['morada']);



        //var_dump($this->dados);
        //var_dump($this->dados['foto']['name']);
        if ($this->valClientes()) {


            $query_cdsCliente = "INSERT INTO clientes (nbi, nif, nome, sobrenome, email, telefone, morada, created) VALUES (:nbi, :nif, :nome, :sobrenome, :email, :telefone, :morada, NOW())";
            $result_cdsCliente = $this->conn->prepare($query_cdsCliente);
            $result_cdsCliente->bindParam(":nbi", $this->dados['nbi']);
            $result_cdsCliente->bindParam(":nif", $this->dados['nif']);
            $result_cdsCliente->bindParam(":nome", $this->dados['nome']);
            $result_cdsCliente->bindParam(":sobrenome", $this->dados['sobrenome']);
            $result_cdsCliente->bindParam(":email", $this->dados['email']);
            $result_cdsCliente->bindParam(":telefone", $this->dados['telefone']);
            $result_cdsCliente->bindParam(":morada", $this->dados['morada']);
            //$result_cdsCliente->bindParam(":foto", $this->dados['novo_nome']);
            $result_cdsCliente->execute();
            if ($result_cdsCliente->rowCount()) {
                $_SESSION['msg'] = '<div class="alert alert-success text-center"> Cliente Cadastrado Com Sucesso!</div>';
                //$this->enviarEmail();
                return true;
            } else {
                $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Cliente Cadastrado Sem Sucesso!</div>';
                return false;
            }
        }
    }

    private function valClientes() {
        $queryValBI = "SELECT nbi FROM clientes WHERE nbi LIKE '{$this->dados['nbi']}'";
        $result_ValBI = $this->conn->prepare($queryValBI);
        $result_ValBI->execute();

        $queryValEmail = "SELECT email FROM clientes WHERE email LIKE '{$this->dados['email']}'";
        $result_ValEmail = $this->conn->prepare($queryValEmail);
        $result_ValEmail->execute();

        $queryValNif = "SELECT nif FROM clientes WHERE nif LIKE '{$this->dados['nif']}'";
        $result_ValNif = $this->conn->prepare($queryValNif);
        $result_ValNif->execute();

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
                    return true;
                }
            }
        }
    }

    private function valEditClientes() {
        $queryValBI = "SELECT nbi FROM clientes WHERE nbi LIKE '{$this->dados['nbi']}'  AND idclientes!=:idclientes";
        $result_ValBI = $this->conn->prepare($queryValBI);
        $result_ValBI->bindParam(":idclientes", $this->dados['idclientes']);
        $result_ValBI->execute();

        $queryValEmail = "SELECT email FROM clientes WHERE email LIKE '{$this->dados['emailnovo']}' AND idclientes!='{$this->dados['idclientes']}'";
        $result_ValEmail = $this->conn->prepare($queryValEmail);
        $result_ValEmail->execute();

        $queryValNif = "SELECT nif FROM clientes WHERE nif LIKE '{$this->dados['nif']}' AND idclientes!='{$this->dados['idclientes']}'";
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

    public function deleteCliente($dados) {
        $this->dados = $dados;
        $this->dados['idclientes'] = $this->limparInput($this->dados['idclientes']);
        $query_cliente = "DELETE FROM clientes  WHERE idclientes=:idclientes";
        $resul_deletcliente = $this->conn->prepare($query_cliente);
        $resul_deletcliente->bindParam(":idclientes", $this->dados['idclientes']);
        $resul_deletcliente->execute();
        if ($resul_deletcliente->rowCount()) {
            $_SESSION['msg'] = '
            <div class="alert alert-success text-center" role="alert">
            Cliente Deletado Com Sucesso!
            </div>
          ';
            return true;
        } else {
            $_SESSION['msg'] = '
            <div class="alert alert-danger text-center" role="alert">
            Cliente Deletado Sem Sucesso !
            </div>
          ';
            return false;
        }
    }

    public function editCliente($dados) {
        $this->dados = $dados;
        $this->dados['emailnovo'] = $this->limparInput($this->dados['emailnovo']);
        $this->dados['nome'] = $this->limparInput($this->dados['nome']);
        $this->dados['sobrenome'] = $this->limparInput($this->dados['sobrenome']);
        $this->dados['nbi'] = $this->limparInput($this->dados['nbi']);
        $this->dados['nif'] = $this->limparInput($this->dados['nif']);
        $this->dados['telefone'] = $this->limparInput($this->dados['telefone']);
        $this->dados['morada'] = $this->limparInput($this->dados['morada']);


        //var_dump($this->dados);
        //var_dump($this->dados);
        //var_dump($this->dados['foto']['name']);
        if ($this->valEditClientes()) {


            $query_cdscliente = "UPDATE clientes SET nbi=:nbi, nif=:nif, nome=:nome, sobrenome=:sobrenome, email=:email, telefone=:telefone, morada=:morada, modified=NOW() WHERE idclientes=:idclientes";
            $result_cdscliente = $this->conn->prepare($query_cdscliente);
            $result_cdscliente->bindParam(":nbi", $this->dados['nbi']);
            $result_cdscliente->bindParam(":nif", $this->dados['nif']);
            $result_cdscliente->bindParam(":nome", $this->dados['nome']);
            $result_cdscliente->bindParam(":sobrenome", $this->dados['sobrenome']);
            $result_cdscliente->bindParam(":email", $this->dados['emailnovo']);
            $result_cdscliente->bindParam(":telefone", $this->dados['telefone']);
            $result_cdscliente->bindParam(":morada", $this->dados['morada']);
            $result_cdscliente->bindParam(":idclientes", $this->dados['idclientes']);
            $result_cdscliente->execute();
            if ($result_cdscliente->rowCount()) {
                $_SESSION['msg'] = '<div class="alert alert-success text-center"> Cliente Editado Com Sucesso!</div>';
                //$this->enviarEmail();
                return true;
            } else {
                $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Cliente Editado Sem Sucesso!</div>';
                return false;
            }
        }
    }

    //Dados Equipamentos Informáticos

    public function dadosEquipamento() {
        $query = "SELECT * FROM dadosClienteEquipamento";
        $result = $this->conn->prepare($query);
        $result->execute();
        $this->dados = $result->fetchAll();
        return $this->dados;
    }

    public function cdsEquipamento($dados) {
        $this->dados = $dados;
        $this->dados['idclientes']        = $this->limparInput($this->dados['idclientes']);
        $this->dados['numero_serie']      = $this->limparInput($this->dados['numero_serie']);
        $this->dados['imei']              = $this->limparInput($this->dados['imei'] ?? '');
        $this->dados['tipo_equipamento']  = $this->limparInput($this->dados['tipo_equipamento']);
        $this->dados['marca']             = $this->limparInput($this->dados['marca']);
        $this->dados['modelo']            = $this->limparInput($this->dados['modelo']);
        $this->dados['estado']            = $this->limparInput($this->dados['estado'] ?? 'Recebido');
        $this->dados['defeito_reportado'] = $this->limparInput($this->dados['defeito_reportado'] ?? '');
        $this->dados['dataregisto']       = $this->limparInput($this->dados['dataregisto']);
        $this->dados['idcategoria_equipamento'] = !empty($this->dados['idcategoria_equipamento']) ? (int)$this->dados['idcategoria_equipamento'] : null;
        $this->dados['codigo']            = $this->limparInput($this->dados['codigo'] ?? '') ?: null;
        $this->dados['patrimonio']        = $this->limparInput($this->dados['patrimonio'] ?? '') ?: null;
        $this->dados['nome']              = $this->limparInput($this->dados['nome'] ?? '') ?: null;
        $this->dados['departamento']      = $this->limparInput($this->dados['departamento'] ?? '') ?: null;
        $this->dados['localizacao']       = $this->limparInput($this->dados['localizacao'] ?? '') ?: null;
        $this->dados['data_aquisicao']    = !empty($this->dados['data_aquisicao']) ? $this->limparInput($this->dados['data_aquisicao']) : null;
        $this->dados['observacoes']       = $this->limparInput($this->dados['observacoes'] ?? '') ?: null;
        $this->sincronizarTipoEquipamento();

        if ($this->valEquipamento()) {
            $query = "INSERT INTO equipamento (idcliente, numero_serie, imei, tipo_equipamento, idcategoria_equipamento, marca, modelo, estado, defeito_reportado, dataregisto, codigo, patrimonio, nome, departamento, localizacao, data_aquisicao, observacoes, created)
                      VALUES (:idcliente, :numero_serie, :imei, :tipo_equipamento, :idcategoria_equipamento, :marca, :modelo, :estado, :defeito_reportado, :dataregisto, :codigo, :patrimonio, :nome, :departamento, :localizacao, :data_aquisicao, :observacoes, NOW())";
            $result = $this->conn->prepare($query);
            $result->bindParam(":idcliente",        $this->dados['idclientes']);
            $result->bindParam(":numero_serie",     $this->dados['numero_serie']);
            $result->bindParam(":imei",             $this->dados['imei']);
            $result->bindParam(":tipo_equipamento", $this->dados['tipo_equipamento']);
            $result->bindParam(":idcategoria_equipamento", $this->dados['idcategoria_equipamento'], PDO::PARAM_INT);
            $result->bindParam(":marca",            $this->dados['marca']);
            $result->bindParam(":modelo",           $this->dados['modelo']);
            $result->bindParam(":estado",           $this->dados['estado']);
            $result->bindParam(":defeito_reportado",$this->dados['defeito_reportado']);
            $result->bindParam(":dataregisto",      $this->dados['dataregisto']);
            $result->bindParam(":codigo",           $this->dados['codigo']);
            $result->bindParam(":patrimonio",       $this->dados['patrimonio']);
            $result->bindParam(":nome",             $this->dados['nome']);
            $result->bindParam(":departamento",     $this->dados['departamento']);
            $result->bindParam(":localizacao",      $this->dados['localizacao']);
            $result->bindParam(":data_aquisicao",   $this->dados['data_aquisicao']);
            $result->bindParam(":observacoes",      $this->dados['observacoes']);
            $result->execute();
            if ($result->rowCount()) {
                $_SESSION['msg'] = '<div class="alert alert-success text-center"> Equipamento Cadastrado Com Sucesso!</div>';
                return true;
            } else {
                $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Equipamento Cadastrado Sem Sucesso!</div>';
                return false;
            }
        }
    }

    // Mantém tipo_equipamento (texto livre, usado nas views legadas dadosorcamento/
    // dadosClienteEquipamento) sincronizado com o nome da categoria escolhida.
    private function sincronizarTipoEquipamento(): void {
        if (empty($this->dados['idcategoria_equipamento'])) {
            return;
        }
        $stmt = $this->conn->prepare("SELECT nome FROM categoria_equipamento WHERE idcategoria_equipamento=:id LIMIT 1");
        $stmt->bindParam(':id', $this->dados['idcategoria_equipamento'], PDO::PARAM_INT);
        $stmt->execute();
        $categoria = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($categoria) {
            $this->dados['tipo_equipamento'] = $categoria['nome'];
        }
    }

    private function valEquipamento() {
        $query = "SELECT numero_serie FROM equipamento WHERE numero_serie LIKE '{$this->dados['numero_serie']}'";
        $result = $this->conn->prepare($query);
        $result->execute();

        if ($result->rowCount()) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Nº de Série já existe! Insira um Número de Série que não esteja registado no sistema.</div>';
            return false;
        } else {
            return true;
        }
    }

    public function deleteEquipamento($dados) {
        $this->dados = $dados;
        $this->dados['idequipamento'] = $this->limparInput($this->dados['idequipamento'] ?? $this->dados['idveiculo'] ?? 0);
        $query_eq = "DELETE FROM equipamento WHERE idequipamento=:idequipamento";
        $resul = $this->conn->prepare($query_eq);
        $resul->bindParam(":idequipamento", $this->dados['idequipamento']);
        $resul->execute();
        if ($resul->rowCount()) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center" role="alert">Equipamento Eliminado Com Sucesso!</div>';
            return true;
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center" role="alert">Equipamento Eliminado Sem Sucesso!</div>';
            return false;
        }
    }

    private function valEditEquipamento() {
        $id = $this->dados['idequipamento'] ?? $this->dados['idveiculo'] ?? 0;
        $query = "SELECT numero_serie FROM equipamento WHERE numero_serie LIKE '{$this->dados['numero_serie']}' AND idequipamento!='{$id}'";
        $result = $this->conn->prepare($query);
        $result->execute();

        if ($result->rowCount()) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Nº de Série já existe! Insira um Número de Série que não esteja registado no sistema.</div>';
            return false;
        } else {
            return true;
        }
    }

    public function editEquipamento($dados) {
        $this->dados = $dados;
        $this->dados['idequipamento']     = $this->limparInput($this->dados['idequipamento'] ?? $this->dados['idveiculo'] ?? 0);
        $this->dados['idclientes']        = $this->limparInput($this->dados['idclientes']);
        $this->dados['numero_serie']      = $this->limparInput($this->dados['numero_serie']);
        $this->dados['imei']              = $this->limparInput($this->dados['imei'] ?? '');
        $this->dados['tipo_equipamento']  = $this->limparInput($this->dados['tipo_equipamento']);
        $this->dados['marca']             = $this->limparInput($this->dados['marca']);
        $this->dados['modelo']            = $this->limparInput($this->dados['modelo']);
        $this->dados['estado']            = $this->limparInput($this->dados['estado'] ?? 'Recebido');
        $this->dados['defeito_reportado'] = $this->limparInput($this->dados['defeito_reportado'] ?? '');
        $this->dados['diagnostico_tecnico'] = $this->limparInput($this->dados['diagnostico_tecnico'] ?? '');
        $this->dados['garantia_reparacao']  = $this->limparInput($this->dados['garantia_reparacao'] ?? '');
        $this->dados['dataregisto']       = $this->limparInput($this->dados['dataregisto']);
        $this->dados['idcategoria_equipamento'] = !empty($this->dados['idcategoria_equipamento']) ? (int)$this->dados['idcategoria_equipamento'] : null;
        $this->dados['codigo']            = $this->limparInput($this->dados['codigo'] ?? '') ?: null;
        $this->dados['patrimonio']        = $this->limparInput($this->dados['patrimonio'] ?? '') ?: null;
        $this->dados['nome']              = $this->limparInput($this->dados['nome'] ?? '') ?: null;
        $this->dados['departamento']      = $this->limparInput($this->dados['departamento'] ?? '') ?: null;
        $this->dados['localizacao']       = $this->limparInput($this->dados['localizacao'] ?? '') ?: null;
        $this->dados['data_aquisicao']    = !empty($this->dados['data_aquisicao']) ? $this->limparInput($this->dados['data_aquisicao']) : null;
        $this->dados['observacoes']       = $this->limparInput($this->dados['observacoes'] ?? '') ?: null;
        $this->sincronizarTipoEquipamento();

        if ($this->valEditEquipamento()) {
            $query = "UPDATE equipamento SET idcliente=:idcliente, numero_serie=:numero_serie, imei=:imei,
                        tipo_equipamento=:tipo_equipamento, idcategoria_equipamento=:idcategoria_equipamento, marca=:marca, modelo=:modelo, estado=:estado,
                        defeito_reportado=:defeito_reportado, diagnostico_tecnico=:diagnostico_tecnico,
                        garantia_reparacao=:garantia_reparacao, dataregisto=:dataregisto,
                        codigo=:codigo, patrimonio=:patrimonio, nome=:nome, departamento=:departamento,
                        localizacao=:localizacao, data_aquisicao=:data_aquisicao, observacoes=:observacoes
                      WHERE idequipamento=:idequipamento";
            $result = $this->conn->prepare($query);
            $result->bindParam(":idcliente",           $this->dados['idclientes']);
            $result->bindParam(":numero_serie",        $this->dados['numero_serie']);
            $result->bindParam(":imei",                $this->dados['imei']);
            $result->bindParam(":tipo_equipamento",    $this->dados['tipo_equipamento']);
            $result->bindParam(":idcategoria_equipamento", $this->dados['idcategoria_equipamento'], PDO::PARAM_INT);
            $result->bindParam(":marca",               $this->dados['marca']);
            $result->bindParam(":modelo",              $this->dados['modelo']);
            $result->bindParam(":estado",              $this->dados['estado']);
            $result->bindParam(":defeito_reportado",   $this->dados['defeito_reportado']);
            $result->bindParam(":diagnostico_tecnico", $this->dados['diagnostico_tecnico']);
            $result->bindParam(":garantia_reparacao",  $this->dados['garantia_reparacao']);
            $result->bindParam(":dataregisto",         $this->dados['dataregisto']);
            $result->bindParam(":codigo",              $this->dados['codigo']);
            $result->bindParam(":patrimonio",          $this->dados['patrimonio']);
            $result->bindParam(":nome",                $this->dados['nome']);
            $result->bindParam(":departamento",        $this->dados['departamento']);
            $result->bindParam(":localizacao",         $this->dados['localizacao']);
            $result->bindParam(":data_aquisicao",      $this->dados['data_aquisicao']);
            $result->bindParam(":observacoes",         $this->dados['observacoes']);
            $result->bindParam(":idequipamento",       $this->dados['idequipamento']);
            $result->execute();
            if ($result->rowCount()) {
                $_SESSION['msg'] = '<div class="alert alert-success text-center"> Equipamento Editado Com Sucesso!</div>';
                return true;
            } else {
                $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Equipamento Editado Sem Sucesso!</div>';
                return false;
            }
        }
    }

    //Contas á Receber
    public function dadosContaReceber() {
        $query_dados = "SELECT * FROM conntas_areceber";
        $result_dados = $this->conn->prepare($query_dados);
        $result_dados->execute();
        $this->dados = $result_dados->fetchAll();
        return $this->dados;
    }

    public function adiantarConta($dados) {
        $this->dados = $dados;
        $this->dados['adiantameto'] = $this->limparInput($this->dados['adiantameto']);
        $this->dados['valor'] = $this->dados['adiantameto'];
        $this->dados['adiantameto'] = $this->dados['adiantameto'] + $this->dados['adiantameto_antigo'];
        //var_dump($this->dados);
        if ($this->valcontaAdiantamento()) {
            $result = $this->conn->prepare("UPDATE conntas_areceber SET adiantameto=:adiantameto WHERE idconntas_areceber=:idconntas_areceber");
            $result->bindParam(":adiantameto", $this->dados['adiantameto']);
            $result->bindParam(":idconntas_areceber", $this->dados['idconntas_areceber']);
            $result->execute();
            if ($result->rowCount()) {
                $this->dados['tipo'] = "Entrada";
                $this->dados['descricao'] = "Adiantamento";
                $this->cdsMovimentacao();
            } else {
                $_SESSION['msg'] = '<div class="alert alert-danger text-center">Conta Adiantada Sem Sucesso!</div>';
                return false;
            }
        }
    }

    public function pagarConta($dados) {
        $this->dados = $dados;
        $this->dados['pago'] = "sim";
        $this->dados['adiantameto'] = $this->dados['adiantameto_antigo'];

        if ($this->valcontaAdiantamentoDelete()) {
            $result = $this->conn->prepare("UPDATE conntas_areceber SET pago=:pago WHERE idconntas_areceber=:idconntas_areceber");
            $result->bindParam(":pago", $this->dados['pago']);
            $result->bindParam(":idconntas_areceber", $this->dados['idconntas_areceber']);
            $result->execute();
            if ($result->rowCount()) {
                $this->dados['tipo'] = "Entrada";
                $this->dados['valor'] = 0;
                $this->cdsMovimentacao();
                $_SESSION['msg'] = '<div class="alert alert-success text-center">Conta Paga Com Sucesso!</div>';
                return true;
            } else {
                $_SESSION['msg'] = '<div class="alert alert-danger text-center">Conta Paga Sem Sucesso!</div>';
                return false;
            }
        }
    }

    public function deleteConta($dados) {
        $this->dados = $dados;
        //var_dump($this->dados);
        $this->dados['adiantameto'] = $this->dados['adiantameto_antigo'];

        if ($this->valcontaAdiantamentoDelete()) {
            $result = $this->conn->prepare("DELETE FROM conntas_areceber WHERE idconntas_areceber=:idconntas_areceber");
            $result->bindParam(":idconntas_areceber", $this->dados['idconntas_areceber']);
            $result->execute();
            if ($result->rowCount()) {
                $_SESSION['msg'] = '<div class="alert alert-success text-center">Conta Deletada Com Sucesso!</div>';
                return true;
            } else {
                $_SESSION['msg'] = '<div class="alert alert-danger text-center">Conta Deletada Sem Sucesso!</div>';
                return false;
            }
        }
    }

    public function valcontaAdiantamento() {
        $result = $this->conn->prepare("SELECT valortotal FROM conntas_areceber  WHERE idconntas_areceber='{$this->dados['idconntas_areceber']}' LIMIT 1");
        $result->execute();
        $resultado = $result->fetch(PDO::FETCH_ASSOC);
        //var_dump($resultado);
        //echo $resultado['valortotal'];
        $this->dados['valortotal'] = $resultado['valortotal'];
        //echo "<br>" . $this->dados['adiantameto'];
        if ($this->dados['adiantameto'] > $this->dados['valortotal']) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center"> O Cliente Já Fez O Pagamento do Orçamentado Ou O Adiantamento a ser inserido de hoje somando com o Adiantamento Está sendo Maior!</div>';
            return false;
        } elseif ($this->dados['adiantameto'] == $this->dados['valortotal']) {
            return true;
        } else {
            return true;
        }
    }

    public function valcontaAdiantamentoDelete() {
        $result = $this->conn->prepare("SELECT valortotal FROM conntas_areceber  WHERE idconntas_areceber='{$this->dados['idconntas_areceber']}' LIMIT 1");
        $result->execute();
        $resultado = $result->fetch(PDO::FETCH_ASSOC);
        //var_dump($resultado);
        //echo $resultado['valortotal'];
        $this->dados['valortotal'] = $resultado['valortotal'];
        //echo "<br>" . $this->dados['adiantameto'];
        if ($this->dados['adiantameto'] == $this->dados['valortotal']) {
            return true;
            //var_dump($this->dados);
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center"> O Cliente Tem que  Fazer O Pagamento do Orçamentado Para Executares Essa Operação!</div>';
            return false;
        }
    }

    public function deleteContaApagar($dados) {
        $this->dados = $dados;
        if ($this->dados['descricao'] == "Compra de Produto") {
            if ($this->dados['pago'] == "sim") {
                $this->deleteFncContaApagar();
            } else {
                if ($_SESSION['usuario'] == "adimin") {
                    $result = $this->conn->prepare("SELECT * FROM compras WHERE  idcontas_apagar='{$this->dados['idcontas_apagar']}' LIMIT 1");
                    $result->execute();
                    $resultado = $result->fetch(PDO::FETCH_ASSOC);
                    @$this->dados['estoque_de_volta'] = $resultado['estoque'] - @$resultado['quantidade_estoque'];
                    if (@$this->dados['estoque_de_volta'] < 0) {
                        @$this->dados['estoque_de_volta'] = 0;
                    }
                    $this->dados['idproduto'] = $resultado['idproduto'];
                    $result = $this->conn->prepare("UPDATE  produto SET estoque=:estoque WHERE  idproduto=:idproduto");
                    $result->bindParam(":estoque", $this->dados['estoque_de_volta']);
                    $result->bindParam(":idproduto", $this->dados['idproduto']);
                    $result->execute();
                    if ($result->rowCount()) {
                        $qtdEstornada = (int)(@$resultado['quantidade_estoque'] ?? 0);
                        if ($qtdEstornada > 0) {
                            (new \App\adms\Models\AdmsMovimentoEstoque())->registar((int)$this->dados['idproduto'], 'Saida', $qtdEstornada, 'CompraCancelada', $this->dados['idcontas_apagar'], $_SESSION['idlogado'] ?? null);
                        }
                        $this->deleteFncContaApagar();
                    } else {
                        //var_dump($this->dados);

                        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Conta à Pagar Deletado Sem Successo!</div>';
                        return false;
                    }
                } else {
                    $_SESSION['msg'] = '<div class="alert alert-danger text-center">Só Adiministrador do Sistema Pode Apagar Compra de Produto Que Ainda Não Foi Paga!</div>';
                    return false;
                }
            }
        } elseif ($this->dados['descricao'] == "Comissão") {
            if ($this->dados['pago'] == "sim") {
                $this->deleteFncContaApagar();
            } else {
                $_SESSION['msg'] = '<div class="alert alert-danger text-center">Tens que Pagar Primeiro A comissão desse Mecânico!</div>';
                return false;
            }
        } else {
            $this->deleteFncContaApagar();
        }
    }

    //Deletando Conta â Pagar
    private function deleteFncContaApagar() {
        $result = $this->conn->prepare("DELETE FROM contas_apagar WHERE idcontas_apagar=:idcontas_apagar");
        $result->bindParam(":idcontas_apagar", $this->dados['idcontas_apagar']);
        $result->execute();
        if ($result->rowCount()) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Conta À Deletada Com Sucesso!</div>';
            return true;
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">Conta Á Deletada Sem Sucesso!</div>';
            return false;
        }
    }

    public function editContaApagar($dados) {
        $this->dados = $dados;
        $this->dados['descricao'] = $this->limparInput($this->dados['descricao']);
        $this->dados['funcionario'] = $_SESSION['nif'];
        $this->dados['valor'] = $this->limparInput($this->dados['valor']);
        $this->dados['data_venci'] = $this->limparInput($this->dados['data_venci']);
        $this->dados['pago'] = "nao";

        //  var_dump($this->dados);
        $query = "UPDATE contas_apagar SET descricao=:descricao, valor=:valor, funcionario=:funcionario, data_venci=:data_venci, modified=NOW() WHERE idcontas_apagar=:idcontas_apagar";
        $result = $this->conn->prepare($query);
        $result->bindParam(":descricao", $this->dados['descricao']);
        $result->bindParam(":valor", $this->dados['valor']);
        $result->bindParam(":funcionario", $_SESSION['nif']);
        $result->bindParam(":data_venci", $this->dados['data_venci']);
        //$result->bindParam(":foto", $this->dados['novo_nome']);
        $result->bindParam(":idcontas_apagar", $this->dados['idcontas_apagar']);
        $result->execute();
        if ($result->rowCount()) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center"> Contas À Pagar Editado Com Sucesso!</div>';
            //$this->enviarEmail();
            return true;
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Contas À Pagar Editado Sem Sucesso!</div>';
            return false;
        }
    }

    // Movimentação

    private function cdsMovimentacao() {
        $result = $this->conn->prepare("INSERT INTO movimentacao (tipo, descricao, valor, funcionario, data) VALUES
            (:tipo, :descricao, :valor, :funcionario, curDate())");
        $result->bindParam(":tipo", $this->dados['tipo']);
        $result->bindParam(":descricao", $this->dados['descricao']);
        $result->bindParam(":valor", $this->dados['valor']);
        $result->bindParam(":funcionario", $_SESSION['nif']);
        $result->execute();
        if ($result->rowCount()) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center"> Operação Feita com  Sucesso!</div>';
            //$this->enviarEmail();
            return true;
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Operação Feita sem Sucesso!</div>';
            return false;
        }
    }

    public function dadosMovimentacao() {
        $query_dados = "SELECT * FROM movimentacao";
        $result_dados = $this->conn->prepare($query_dados);
        $result_dados->execute();
        $this->dados = $result_dados->fetchAll();
        return $this->dados;
    }

    public function dadosOperacaoMovimentacao() {
        //Valor Entrada do Dia
        $result_entrada = $this->conn->prepare("SELECT SUM(valor) as entrada_dia FROM movimentacao WHERE tipo='Entrada' AND data=curDate()");
        $result_entrada->execute();
        $resultado_entrada = $result_entrada->fetch(PDO::FETCH_ASSOC);
        $this->dados['entrada_dia'] = $resultado_entrada['entrada_dia'];
        //Saida do Dia
        $result_saida = $this->conn->prepare("SELECT SUM(valor) as saida_dia FROM movimentacao WHERE tipo='Saída' AND data=curDate()");
        $result_saida->execute();
        $resultado_saida = $result_saida->fetch(PDO::FETCH_ASSOC);
        $this->dados['saida_dia'] = $resultado_saida['saida_dia'];

        //total do Dia
        /*$result_total = $this->conn->prepare("SELECT SUM(valor) as total FROM movimentacao WHERE data=curDate()");
        $result_total->execute();
        $resultado_total = $result_total->fetch(PDO::FETCH_ASSOC);*/
        @$this->dados['total'] = @$this->dados['entrada_dia'] - @$this->dados['saida_dia'];

        return $this->dados;
    }

    private function entradaEquipamento() {
        $result = $this->conn->prepare("INSERT INTO entrada_veiculo SET idveiculo=:idveiculo, data_entrada=curDate()");
        $result->bindParam(":idveiculo", $this->dados['idveiculo']);
        $result->execute();
        if ($result->rowCount()) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center"> Operação Feita com  Sucesso!</div>';
            //$this->enviarEmail();
            return true;
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Operação Feita sem Sucesso!</div>';
            return false;
        }
    }

}

