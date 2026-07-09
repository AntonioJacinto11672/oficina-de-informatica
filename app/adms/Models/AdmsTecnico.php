<?php

namespace App\adms\Models;

USE PDO;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Mpdf\Mpdf;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Description of AdmsTecnico
 *
 * @author Double
 */
class AdmsTecnico extends Conn {

    private $dados;
    private $dadosAlter;
    private $conn;

    public function __construct() {
        $this->conn = $this->connect();
    }

    // A lógica de escrita em `ocorrencias` vive agora em AdmsOcorrencia (módulo 9).
    // Estes métodos ficam como wrappers finos para não alterar o comportamento de
    // abrirOrcamento()/editOrcamento() nesta fase — a religação completa ao módulo
    // de Ocorrências acontece na Fase 3 (orçamento passa a ser filho da ocorrência).
    private function criarOcorrencia($dados, $status = 'Aberta') {
        return (new \App\adms\Models\AdmsOcorrencia())->criarOcorrenciaDeOrcamento($dados, $status);
    }

    private function atualizarOcorrencia($dados, $status = 'Aberta') {
        return (new \App\adms\Models\AdmsOcorrencia())->atualizarOcorrenciaDeOrcamento($dados, $status);
    }

    //Limpar Os campos contra O sqlInjection e Caracter especial

    protected function limparInput($input) {
        $newConn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME, DBPORT);
        if (!$newConn) {
            if (DEBUG_MODE) {
                die('Erro de conexão ao banco de dados: ' . mysqli_connect_error());
            }
            die('Erro: não foi possível conectar ao banco de dados. Verifique as credenciais em .env.');
        }
        $var = mysqli_real_escape_string($newConn, $input);
        $var = htmlspecialchars($var);
        mysqli_close($newConn);
        return $var;
    }

    //Enviar Email Com phpmailer

    private function enviarEmail() {
        $mail = new PHPMailer(true);

        try {
            //Server settings
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
            //Informações vindo do phpmailler  fazer um caastramento previo
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host = 'smtp.mailtrap.io';                    // Set the SMTP server to send through  Servidor de teste
            $mail->SMTPAuth = true;                                   // Enable SMTP authentication
            $mail->Username = 'c85e426e1ec5a1';                     // SMTP username
            $mail->Password = '7cf202962d5c0e';                               // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
            //Recipients
            $mail->setFrom('antjacinto11672@gmail.com', 'António');                       //Que está Enviar
            $mail->addAddress($this->dados['email'], $this->dados['nome']);     // Add a recipient //Destinatario
            // Content
            $mail->isHTML(true);                                  // Set email format to HTML // formatar com Html
            $mail->Subject = '  Confirmar e-mail'; // Asunto do Email
            //A Conteudo HTML do Email
            $conteudoHTML = "Prezado {$this->dados['nome']}<br><br>";
            $conteudoHTML .= "A Foste Cadastrado Com Supervisor Use o Seu email Para Acessar o Sistema e A Sua palavra passe é {$this->dados['senhaguardada']}<br>";
            //$conteudoHTML .= "<a href='".URLADM."/index?chave={$this->dados['chave_ativar']}'>".URL."ativar/index?chave={$this->dados['chave_ativar']}</a><br>";
            $mail->Body = $conteudoHTML;

            //O Conteudo Só texto do Email
            $conteudoTexto = "Prezado {$this->dados['nome']}\n\n";
            $conteudoTexto .= "A Foste Cadastrado Usuário do Sistema de Gestão de Mecanica, Use o Seu email Para Acessar o Sistema e A Sua palavra passe é {$this->dados['senhaguardada']}<br>.\n";
            //$conteudoTexto .= URL."ativar/index?chave={$this->dados['chave_ativar']}\n";
            $mail->AltBody = $conteudoTexto;

            $mail->send();
            $_SESSION['msg'] = "<div class='alert alert-success'>Usuário cadastrado com sucesso, E E-mail enviado!</div>";
        } catch (Exception $e) {
            //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            $_SESSION['msg'] = "<div class='alert alert-warning'>Usuário Cadastrado Com Sucesso! <spam  class='txt-red'>Erro: E-mail não enviado. </spam> !</div>";
            // echo " Mailer Error: {$mail->ErrorInfo}";
        }
    }

    public function enviar($dados) {
        $this->dados = $dados;
        $value = $this->dadosOrcamentosCompletoAlter($this->dados);
        $this->dados = $this->dadosOrcamentosCompletoId($dados);

        //var_dump($value);
        $this->enviarRelatorioPorEmail();
    }

    private function enviarRelatorioPorEmail() {
        $mail = new PHPMailer(true);

        try {
            //Server settings
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
            //Informações vindo do phpmailler  fazer um caastramento previo
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host = 'smtp.mailtrap.io';                    // Set the SMTP server to send through  Servidor de teste
            $mail->SMTPAuth = true;                                   // Enable SMTP authentication
            $mail->Username = 'c85e426e1ec5a1';                     // SMTP username
            $mail->Password = '7cf202962d5c0e';                               // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
            //Recipients
            $mail->setFrom('antjacinto11672@gmail.com', 'António');                       //Que está Enviar
            $mail->addAddress($this->dados['email'], $this->dados['nome_cliente']);     // Add a recipient //Destinatario
            // Content
            $mail->isHTML(true);                                  // Set email format to HTML // formatar com Html
            $mail->Subject = 'Relaório do Oçamento da Sua Viatura'; // Asunto do Email
            //A Conteudo HTML do Email
            $conteudoHTML = ' 
                            <div class="wrapper"> 
                                <div class="content-wrapper">
                                    <div class="content">
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="invoice p-3 mb-3 p-5">
                                                        <!-- title row -->
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <h4 class="text-center">
                                                                    <img src="app/adms/assets/imagens/login/logo_novo.png" width="100px" height="60px"/>
                                                                    <span class="text-muted">' . NOME_OFICINA . '</span>
                                                                </h4>
                                                                <div class="row justify-content-center">
                                                                    <small class="badge badge-primary text-wrap text-center " style="width: 24rem;font-family: 16px">' . ENDERECO_OFICINA . '<br>" Tel: ' . TELEFONE . '  " Email: " ' . EMAIL_OFICINA . '</small>
                                                                </div>
                                                                <hr>
                                                            </div>
                     ';
            $conteudoHTML .= ' 
                                                             <div class="col-12">
                                                                <h4>
                                                                    <span class=" text-muted">Orçamento Nº  ' . $this->dados['idorcamentos'] . ' </span>
                                                                    <small class="float-right">Data:  ' . date("d/M/Y") . '; </small><br>
                                                                </h4>
                                                                <hr>
                                                            </div>
                                                        </div>

                                             ';
            $conteudoHTML .= ' 
                                                <div class="row invoice-info">                   
                                                    <div class="col-sm-4 invoice-col">
                                                            <address>
                                                                <strong>Cliente.</strong><br>

                                                                Nome:  ' . $this->dados['nome_cliente'] . '  ' . $this->dados['sobrenome'] . '<br>
                                                                Nº BI: ' . $this->dados['nbi'] . '<br>
                                                                NIF:   ' . $this->dados['nif'] . '<br>

                                                            </address>
                                                        </div>                        
                                             ';
            $conteudoHTML .= ' 
                                                               <div class="col-sm-4 invoice-col">
                                                                    <address>
                                                                        <strong></strong><br>
                                                                        ' . $this->dados['morada'] . '
                                                                        Telefone: (+244)' . $this->dados['telefone'] . '<br>
                                                                        Email:   ' . $this->dados['email'] . '<br>
                                                                    </address>
                                                                </div>                        
                                                     ';
            $conteudoHTML .= '  
                                                                <div class="col-sm-4 invoice-col">

                                                                </div>
                                                            </div>
                                                    ';
            $conteudoHTML .= ' 
                                                        <div class="row invoice-info">                   
                                                            <div class="col-sm-4 invoice-col">
                                                                    <address>
                                                                        <strong>Equipamento.</strong><br>
                                                                        Nº de Série:  ' . $this->dados['matricula'] . '<br>
                                                                        Marca: ' . $this->dados['marca'] . '<br>
                                                                        Modelo:   ' . $this->dados['modelo'] . '<br>
                                                                        Cor:   ' . $this->dados['cor'] . '<br> 
                                                                    </address>
                                                                </div>                        
                                                     ';
            $conteudoHTML .= ' 
                                                                <div class="col-sm-4 invoice-col">
                                                                     <address>
                                                                         <strong></strong><br>
                                                                     </address>
                                                                 </div>                        
                                                      ';
            $conteudoHTML .= '  
                                                                 <div class="col-sm-4 invoice-col">
                                                                     <address>
                                                                         <strong></strong><br>
                                                                     </address>
                                                                 </div>
                                                             </div>
                                                         </div>
                                                     ';
            $conteudoHTML .= '
                                            <hr>
                                                        <div class="row invoice-info">
                                                            <div class="col-sm-4 invoice-col">
                                                                <address>
                                                                    <strong>Diagnóstico do Técnico.</strong><br>
                                                                    <b>Tipo Serviço:</b> ' . $this->dados['nome_servico'] . '  ; <br>

                                                                </address>
                                                            </div>

                                                            <div class="col-sm-4 invoice-col">
                                                                <address>
                                                                    <strong></strong><br>
                                                                    <b> Garantia: </b>' . $this->dados['garantia'] . ' dias
                                                                </address>
                                                            </div>

                                                            <div class="col-sm-4 invoice-col">
                                                                <address>
                                                                    <strong></strong><br>
                                                                    <b> Valor De Serviço: </b>' . number_format($this->dados['valor_t_servico'], 2, ",", ".") . ' Kz
                                                                </address>
                                                            </div>

                                                        </div>
                                            ';

            $conteudoHTML .= '
                                                        <div class="row">
                                                            <div class="col-12 table-responsive">
                                                                <table class="table table-striped">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Peça / Produto</th>
                                                                            <th>Valor</th>
                                                                            <th>Quantidade</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                ';
            $totalproduto = 0;
            if (isset($this->dadosAlter)) {
                for ($index = 0; $index < count($this->dadosAlter); $index++) {
                    $valorForm = $this->dadosAlter[$index];
                    $conteudoHTML .= '
                                                                                    <tr>
                                                                                    <td>' . $valorForm['nome_produto'] . '/<td>
                                                                                    <td>';
                    $totalproduto = $totalproduto + ($valorForm['valor_venda'] * $valorForm['quantidade']);
                    $valorForm['valor_vendam'] = number_format($valorForm['valor_venda'], 2, ",", ".");
                    $conteudoHTML .= '' . $valorForm['valor_vendam'] . '
                                                                                        KZ
                                                                                    </td>
                                                                                    <td>' . $valorForm['quantidade'] . '</td>
                                                                                </tr>';
                }
            }
            $conteudoHTML .= '
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    ';
            $conteudoHTML .= ' <div class="row">
                                                        <div class="col-6">

                                                        </div>
                                                        <div class="col-6">
                                                            <p class="lead">Valor do Orçamento </p>

                                                            <div class="table-responsive">
                                                                <table class="table">
                                                                ';
            $conteudoHTML .= '               <tr>
                                                                        <th style="width:50%">Valor Peça / Produto:</th>
                                                                        <td>' . number_format($totalproduto, 2, ",", ".") . ' Kz</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Valor Mão de Obra</th>
                                                                        <td>' . number_format($this->dados['valor_maodeobra'], 2, ",", ".") . ' Kz</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Desconto';

            $this->dados['valortotali'] = $this->dados['valor_t_servico'] + $this->dados['valor_maodeobra'] + $totalproduto;
            $desconto = ($this->dados['valortotali'] * VALOR_DESCONTO);
            $this->dados['valortotalf'] = $this->dados['valortotali'] - $desconto;
            $conteudoHTML .= '       

                                                                            (' . (VALOR_DESCONTO * 100) . '%)</th>
                                                                        <td>' . number_format($desconto, 2, ",", ".") . ' Kz</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Mecânico:</th>
                                                                        <td>António Jacinto</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Subtotal:</th>
                                                                        <td>' . number_format($this->dados['valortotali'], 2, ",", ".") . ' Kz</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Total:</th>
                                                                        <td>' . number_format($this->dados['valortotalf'], 2, ",", ".") . ' Kz</td>
                                                                    </tr>
                                                                    <tfoot>
                                                                        <tr>
                                                                            <td colspan="2"><b>Obs:</b> Data Prevista Para Entrega ' . date("d/m/Y", strtotime($this->dados['data_entrega'])) . ' </td>
                                                                        </tr>
                                                                    </tfoot>';
            $conteudoHTML .= '

                                                                </table>

                                                            </div>
                                                        </div>
                                                        <!-- /.col -->
                                                    </div>
                                                    <!-- /.row -->

                                                    <!-- this row will not appear when printing -->
                                                    <div class="row no-print">
                                                        <div class="col-12">
                                                            <a href="invoice-print.html" rel="noopener" target="_blank" class="btn btn-default border"><i class="fas fa-print"></i> Print</a>
                                                            <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
                                                                <i class="fas fa-download"></i> Gerar PDF
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- /.invoice -->
                                            </div><!-- /.col -->
                                        </div><!-- /.row -->
                                    </div><!-- /.container-fluid -->
                                </div>
                                <!-- /.content -->
                            </div>
                        </div>
                            ';

            //$conteudoHTML .= "<a href='".URLADM."/index?chave={$this->dados['chave_ativar']}'>".URL."ativar/index?chave={$this->dados['chave_ativar']}</a><br>";
            $mail->Body = $conteudoHTML;

            $conteudoTexto = ' 
                            <div class="wrapper"> 
                                <div class="content-wrapper">
                                    <div class="content">
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="invoice p-3 mb-3 p-5">
                                                        <!-- title row -->
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <h4 class="text-center">
                                                                    <img src="app/adms/assets/imagens/login/logo_novo.png" width="100px" height="60px"/>
                                                                    <span class="text-muted">' . NOME_OFICINA . '</span>
                                                                </h4>
                                                                <div class="row justify-content-center">
                                                                    <small class="badge badge-primary text-wrap text-center " style="width: 24rem;font-family: 16px">' . ENDERECO_OFICINA . '<br>" Tel: ' . TELEFONE . '  " Email: " ' . EMAIL_OFICINA . '</small>
                                                                </div>
                                                                <hr>
                                                            </div>
                     ';
            $conteudoTexto .= ' 
                                                             <div class="col-12">
                                                                <h4>
                                                                    <span class=" text-muted">Orçamento Nº  ' . $this->dados['idorcamentos'] . ' </span>
                                                                    <small class="float-right">Data:  ' . date("d/M/Y") . '; </small><br>
                                                                </h4>
                                                                <hr>
                                                            </div>
                                                        </div>

                                             ';
            $conteudoTexto .= ' 
                                                <div class="row invoice-info">                   
                                                    <div class="col-sm-4 invoice-col">
                                                            <address>
                                                                <strong>Cliente.</strong><br>

                                                                Nome:  ' . $this->dados['nome_cliente'] . '  ' . $this->dados['sobrenome'] . '<br>
                                                                Nº BI: ' . $this->dados['nbi'] . '<br>
                                                                NIF:   ' . $this->dados['nif'] . '<br>

                                                            </address>
                                                        </div>                        
                                             ';
            $conteudoTexto .= ' 
                                                               <div class="col-sm-4 invoice-col">
                                                                    <address>
                                                                        <strong></strong><br>
                                                                        ' . $this->dados['morada'] . '
                                                                        Telefone: (+244)' . $this->dados['telefone'] . '<br>
                                                                        Email:   ' . $this->dados['email'] . '<br>
                                                                    </address>
                                                                </div>                        
                                                     ';
            $conteudoTexto .= '  
                                                                <div class="col-sm-4 invoice-col">

                                                                </div>
                                                            </div>
                                                    ';
            $conteudoTexto .= ' 
                                                        <div class="row invoice-info">                   
                                                            <div class="col-sm-4 invoice-col">
                                                                    <address>
                                                                        <strong>Equipamento.</strong><br>
                                                                        Nº de Série:  ' . $this->dados['matricula'] . '<br>
                                                                        Marca: ' . $this->dados['marca'] . '<br>
                                                                        Modelo:   ' . $this->dados['modelo'] . '<br>
                                                                        Cor:   ' . $this->dados['cor'] . '<br> 
                                                                    </address>
                                                                </div>                        
                                                     ';
            $conteudoTexto .= ' 
                                                                <div class="col-sm-4 invoice-col">
                                                                     <address>
                                                                         <strong></strong><br>
                                                                     </address>
                                                                 </div>                        
                                                      ';
            $conteudoTexto .= '  
                                                                 <div class="col-sm-4 invoice-col">
                                                                     <address>
                                                                         <strong></strong><br>
                                                                     </address>
                                                                 </div>
                                                             </div>
                                                         </div>
                                                     ';
            $conteudoTexto .= '
                                            <hr>
                                                        <div class="row invoice-info">
                                                            <div class="col-sm-4 invoice-col">
                                                                <address>
                                                                    <strong>Diagnóstico do Técnico.</strong><br>
                                                                    <b>Tipo Serviço:</b> ' . $this->dados['nome_servico'] . '  ; <br>

                                                                </address>
                                                            </div>

                                                            <div class="col-sm-4 invoice-col">
                                                                <address>
                                                                    <strong></strong><br>
                                                                    <b> Garantia: </b>' . $this->dados['garantia'] . ' dias
                                                                </address>
                                                            </div>

                                                            <div class="col-sm-4 invoice-col">
                                                                <address>
                                                                    <strong></strong><br>
                                                                    <b> Valor De Serviço: </b>' . number_format($this->dados['valor_t_servico'], 2, ",", ".") . ' Kz
                                                                </address>
                                                            </div>

                                                        </div>
                                            ';

            $conteudoTexto .= '
                                                        <div class="row">
                                                            <div class="col-12 table-responsive">
                                                                <table class="table table-striped">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Peça / Produto</th>
                                                                            <th>Valor</th>
                                                                            <th>Quantidade</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                ';
            $totalproduto = 0;
            if (isset($this->dadosAlter)) {
                for ($index = 0; $index < count($this->dadosAlter); $index++) {
                    $valorForm = $this->dadosAlter[$index];
                    $conteudoTexto .= '
                                                                                    <tr>
                                                                                    <td>' . $valorForm['nome_produto'] . '/<td>
                                                                                    <td>';
                    $totalproduto = $totalproduto + ($valorForm['valor_venda'] * $valorForm['quantidade']);
                    $valorForm['valor_vendam'] = number_format($valorForm['valor_venda'], 2, ",", ".");
                    $conteudoTexto .= '' . $valorForm['valor_vendam'] . '
                                                                                        KZ
                                                                                    </td>
                                                                                    <td>' . $valorForm['quantidade'] . '</td>
                                                                                </tr>';
                }
            }
            $conteudoTexto .= '
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    ';
            $conteudoTexto .= ' <div class="row">
                                                        <div class="col-6">

                                                        </div>
                                                        <div class="col-6">
                                                            <p class="lead">Valor do Orçamento </p>

                                                            <div class="table-responsive">
                                                                <table class="table">
                                                                ';
            $conteudoTexto .= '               <tr>
                                                                        <th style="width:50%">Valor Peça / Produto:</th>
                                                                        <td>' . number_format($totalproduto, 2, ",", ".") . ' Kz</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Valor Mão de Obra</th>
                                                                        <td>' . number_format($this->dados['valor_maodeobra'], 2, ",", ".") . ' Kz</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Desconto';

            $this->dados['valortotali'] = $this->dados['valor_t_servico'] + $this->dados['valor_maodeobra'] + $totalproduto;
            $desconto = ($this->dados['valortotali'] * VALOR_DESCONTO);
            $this->dados['valortotalf'] = $this->dados['valortotali'] - $desconto;
            $conteudoTexto .= '       

                                                                            (' . (VALOR_DESCONTO * 100) . '%)</th>
                                                                        <td>' . number_format($desconto, 2, ",", ".") . ' Kz</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Mecânico:</th>
                                                                        <td>António Jacinto</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Subtotal:</th>
                                                                        <td>' . number_format($this->dados['valortotali'], 2, ",", ".") . ' Kz</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Total:</th>
                                                                        <td>' . number_format($this->dados['valortotalf'], 2, ",", ".") . ' Kz</td>
                                                                    </tr>
                                                                    <tfoot>
                                                                        <tr>
                                                                            <td colspan="2"><b>Obs:</b> Data Prevista Para Entrega ' . date("d/m/Y", strtotime($this->dados['data_entrega'])) . ' </td>
                                                                        </tr>
                                                                    </tfoot>';
            $conteudoTexto .= '

                                                                </table>

                                                            </div>
                                                        </div>
                                                        <!-- /.col -->
                                                    </div>
                                                    <!-- /.row -->

                                                    <!-- this row will not appear when printing -->
                                                    <div class="row no-print">
                                                        <div class="col-12">
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- /.invoice -->
                                            </div><!-- /.col -->
                                        </div><!-- /.row -->
                                    </div><!-- /.container-fluid -->
                                </div>
                                <!-- /.content -->
                            </div>
                        </div>
                            ';

            //O Conteudo Só texto do Email
            $conteudoTexto = "Prezado {$this->dados['nome']}\n\n";
            $conteudoTexto .= "A Foste Cadastrado Com Supervisor Use o Seu email Para Acessar o Sistema e A Sua palavra passe é {$this->dados['nome']}<br>.\n";

            //$conteudoTexto .= URL."ativar/index?chave={$this->dados['chave_ativar']}\n";
            $mail->AltBody = $conteudoTexto;

            $mail->send();
            $_SESSION['msg'] = "<div class='alert alert-success'>E-mail enviado!</div>";
        } catch (Exception $e) {
            //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            $_SESSION['msg'] = "<div class='alert alert-danger'><spam  class='txt-red'>Erro: E-mail não enviado. {$mail->ErrorInfo} </spam> !</div>";
            // echo " Mailer Error: {$mail->ErrorInfo}";
        }
    }

    // Iprimir Relatório PDF

    public function gerar($dados) {
        $this->dados = $dados;
        $this->dados = $dados;
        $value = $this->dadosOrcamentosCompletoAlter($this->dados);
        $this->dados = $this->dadosOrcamentosCompletoId($dados);
        //var_dump($value);
        //var_dump($this->dados);
        $this->gerarPdfOrcamento();
    }

    public function gerarPdfOrcamento() {
        $pdf = new Mpdf();



        $conteudoHTML = ' 
                            <div class="wrapper"> 
                                <div class="content-wrapper">
                                    <div class="content">
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="invoice p-3 mb-3 p-5">
                                                        <!-- title row -->
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <h4 class="text-center">
                                                                    <img src="app/adms/assets/imagens/login/logo_novo.png" width="100px" height="60px"/>
                                                                    <span class="text-muted">' . NOME_OFICINA . '</span>
                                                                </h4>
                                                                <div class="row justify-content-center">
                                                                    <small class="badge badge-primary text-wrap text-center " style="width: 24rem;font-family: 16px">' . ENDERECO_OFICINA . '<br>" Tel: ' . TELEFONE . '  " Email: " ' . EMAIL_OFICINA . '</small>
                                                                </div>
                                                                <hr>
                                                            </div>
                     ';
        $conteudoHTML .= ' 
                                                             <div class="col-12">
                                                                <h4>
                                                                    <span class=" text-muted">Orçamento Nº  ' . $this->dados['idorcamentos'] . ' </span>
                                                                    <small class="float-right">Data:  ' . date("d/M/Y") . '; </small><br>
                                                                </h4>
                                                                <hr>
                                                            </div>
                                                        </div>

                                             ';
        $conteudoHTML .= ' 
                                                <div class="row invoice-info">                   
                                                    <div class="col-sm-4 invoice-col">
                                                            <address>
                                                                <strong>Cliente.</strong><br>

                                                                Nome:  ' . $this->dados['nome_cliente'] . '  ' . $this->dados['sobrenome'] . '<br>
                                                                Nº BI: ' . $this->dados['nbi'] . '<br>
                                                                NIF:   ' . $this->dados['nif'] . '<br>

                                                            </address>
                                                        </div>                        
                                             ';
        $conteudoHTML .= ' 
                                                               <div class="col-sm-4 invoice-col">
                                                                    <address>
                                                                        <strong></strong><br>
                                                                        ' . $this->dados['morada'] . '
                                                                        Telefone: (+244)' . $this->dados['telefone'] . '<br>
                                                                        Email:   ' . $this->dados['email'] . '<br>
                                                                    </address>
                                                                </div>                        
                                                     ';
        $conteudoHTML .= '  
                                                                <div class="col-sm-4 invoice-col">

                                                                </div>
                                                            </div>
                                                    ';
        $conteudoHTML .= ' 
                                                        <div class="row invoice-info">                   
                                                            <div class="col-sm-4 invoice-col">
                                                                    <address>
                                                                        <strong>Equipamento.</strong><br>
                                                                        Nº de Série:  ' . $this->dados['matricula'] . '<br>
                                                                        Marca: ' . $this->dados['marca'] . '<br>
                                                                        Modelo:   ' . $this->dados['modelo'] . '<br>
                                                                        Cor:   ' . $this->dados['cor'] . '<br> 
                                                                    </address>
                                                                </div>                        
                                                     ';
        $conteudoHTML .= ' 
                                                                <div class="col-sm-4 invoice-col">
                                                                     <address>
                                                                         <strong></strong><br>
                                                                     </address>
                                                                 </div>                        
                                                      ';
        $conteudoHTML .= '  
                                                                 <div class="col-sm-4 invoice-col">
                                                                     <address>
                                                                         <strong></strong><br>
                                                                     </address>
                                                                 </div>
                                                             </div>
                                                         </div>
                                                     ';
        $conteudoHTML .= '
                                            <hr>
                                                        <div class="row invoice-info">
                                                            <div class="col-sm-4 invoice-col">
                                                                <address>
                                                                    <strong>Diagnóstico do Técnico.</strong><br>
                                                                    <b>Tipo Serviço:</b> ' . $this->dados['nome_servico'] . '  ; <br>

                                                                </address>
                                                            </div>

                                                            <div class="col-sm-4 invoice-col">
                                                                <address>
                                                                    <strong></strong><br>
                                                                    <b> Garantia: </b>' . $this->dados['garantia'] . ' dias
                                                                </address>
                                                            </div>

                                                            <div class="col-sm-4 invoice-col">
                                                                <address>
                                                                    <strong></strong><br>
                                                                    <b> Valor De Serviço: </b>' . number_format($this->dados['valor_t_servico'], 2, ",", ".") . ' Kz
                                                                </address>
                                                            </div>

                                                        </div>
                                            ';

        $conteudoHTML .= '
                                                        <div class="row">
                                                            <div class="col-12 table-responsive">
                                                                <table class="table table-striped">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Peça / Produto</th>
                                                                            <th>Valor</th>
                                                                            <th>Quantidade</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                ';
        $totalproduto = 0;
        if (isset($this->dadosAlter)) {
            for ($index = 0; $index < count($this->dadosAlter); $index++) {
                $valorForm = $this->dadosAlter[$index];
                $conteudoHTML .= '
                                                                                    <tr>
                                                                                    <td>' . $valorForm['nome_produto'] . '/<td>
                                                                                    <td>';
                $totalproduto = $totalproduto + ($valorForm['valor_venda'] * $valorForm['quantidade']);
                $valorForm['valor_vendam'] = number_format($valorForm['valor_venda'], 2, ",", ".");
                $conteudoHTML .= '' . $valorForm['valor_vendam'] . '
                                                                                        KZ
                                                                                    </td>
                                                                                    <td>' . $valorForm['quantidade'] . '</td>
                                                                                </tr>';
            }
        }
        $conteudoHTML .= '
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    ';
        $conteudoHTML .= ' <div class="row">
                                                        <div class="col-6">

                                                        </div>
                                                        <div class="col-6">
                                                            <p class="lead">Valor do Orçamento </p>

                                                            <div class="table-responsive">
                                                                <table class="table">
                                                                ';
        $conteudoHTML .= '               <tr>
                                                                        <th style="width:50%">Valor Peça / Produto:</th>
                                                                        <td>' . number_format($totalproduto, 2, ",", ".") . ' Kz</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Valor Mão de Obra</th>
                                                                        <td>' . number_format($this->dados['valor_maodeobra'], 2, ",", ".") . ' Kz</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Desconto';

        $this->dados['valortotali'] = $this->dados['valor_t_servico'] + $this->dados['valor_maodeobra'] + $totalproduto;
        $desconto = ($this->dados['valortotali'] * VALOR_DESCONTO);
        $this->dados['valortotalf'] = $this->dados['valortotali'] - $desconto;
        $conteudoHTML .= '       

                                                                            (' . (VALOR_DESCONTO * 100) . '%)</th>
                                                                        <td>' . number_format($desconto, 2, ",", ".") . ' Kz</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Mecânico:</th>
                                                                        <td>António Jacinto</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Subtotal:</th>
                                                                        <td>' . number_format($this->dados['valortotali'], 2, ",", ".") . ' Kz</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Total:</th>
                                                                        <td>' . number_format($this->dados['valortotalf'], 2, ",", ".") . ' Kz</td>
                                                                    </tr>
                                                                    <tfoot>
                                                                        <tr>
                                                                            <td colspan="2"><b>Obs:</b> Data Prevista Para Entrega ' . date("d/m/Y", strtotime($this->dados['data_entrega'])) . ' </td>
                                                                        </tr>
                                                                    </tfoot>';
        $conteudoHTML .= '

                                                                </table>

                                                            </div>
                                                        </div>
                                                        <!-- /.col -->
                                                    </div>
                                                    <!-- /.row -->

                                                    <!-- this row will not appear when printing -->
                                                    
                                                </div>
                                                <!-- /.invoice -->
                                            </div><!-- /.col -->
                                        </div><!-- /.row -->
                                    </div><!-- /.container-fluid -->
                                </div>
                                <!-- /.content -->
                            </div>
                        </div>
                            ';


        //$desconto = URLADM . "adms/Views/relatorio/pdf/pdf_teste.php";
        echo $conteudoHTML;
        $pdf->SetDisplayMode("fullpage");
        $pdf->WriteHTML($conteudoHTML);
        $pdf->Output();
    }

    public function teste() {
        echo "<h1>Testenda Outra vez</h1>";
        $html = "<h1>Testenda Mais Outra vez</h1>";

        $pdf = new Mpdf();


        $pdf->SetDisplayMode("fullpage");
        $pdf->WriteHTML($html);
        $pdf->Output();
    }

    //Mecanico


    public function cdsTecnico($dados) {
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
        if ($this->valTecnicos()) {
            if ($this->upload()) {

                $query_cdsMecanico = "INSERT INTO tecnicos(nbi, nif, nome, sobrenome, email, telefone, morada, created, foto) VALUES (:nbi, :nif, :nome, :sobrenome, :email, :telefone, :morada, NOW(), :foto)";
                $result_cdsMecanico = $this->conn->prepare($query_cdsMecanico);
                $result_cdsMecanico->bindParam(":nbi", $this->dados['nbi']);
                $result_cdsMecanico->bindParam(":nif", $this->dados['nif']);
                $result_cdsMecanico->bindParam(":nome", $this->dados['nome']);
                $result_cdsMecanico->bindParam(":sobrenome", $this->dados['sobrenome']);
                $result_cdsMecanico->bindParam(":email", $this->dados['email']);
                $result_cdsMecanico->bindParam(":telefone", $this->dados['telefone']);
                $result_cdsMecanico->bindParam(":morada", $this->dados['morada']);
                $result_cdsMecanico->bindParam(":foto", $this->dados['novo_nome']);
                $result_cdsMecanico->execute();
                if ($result_cdsMecanico->rowCount()) {
                    //$this->dados['nrecenseador'] = $this->conn->lastInsertId();
                    $this->dados['senha'] = rand(1000, 999999);
                    $this->dados['senhaguardada'] = $this->dados['senha'];
                    $this->dados['senha'] = md5($this->dados['senha']);
                    $this->dados['nivel'] = "tecnico";
                    $this->dados['st_conta'] = "Ativada";
                    //var_dump($this->dados);

                    $query_login = "INSERT INTO usuario (nbi, nif, nome, sobrenome, email, telefone, senha, nivel, st_conta, foto, created)
                    VALUES (:nbi, :nif, :nome, :sobrenome, :email, :telefone, :senha, :nivel, :st_conta, :foto, NOW())";
                    $result_login = $this->conn->prepare($query_login);
                    $result_login->bindParam(":nome", $this->dados['nome']);
                    $result_login->bindParam(":sobrenome", $this->dados['sobrenome']);
                    $result_login->bindParam(":email", $this->dados['email']);
                    $result_login->bindParam(":telefone", $this->dados['telefone']);
                    $result_login->bindParam(":senha", $this->dados['senha']);
                    $result_login->bindParam(":nivel", $this->dados['nivel']);
                    $result_login->bindParam(":st_conta", $this->dados['st_conta']);
                    $result_login->bindParam(":foto", $this->dados['novo_nome']);
                    $result_login->bindParam(":nbi", $this->dados['nbi']);
                    $result_login->bindParam(":nif", $this->dados['nif']);

                    $result_login->execute();
                    if ($result_login->rowCount()) {
                        //$_SESSION['msg'] = '<div class="alert alert-success text-center"> Mecânico Cadastrado Com Sucesso!</div>';
                        $this->enviarEmail();
                        return true;
                    } else {
                        $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Mecânico Cadastrado Sem Sucesso Dados do Login!</div>';
                        return false;
                    }
                } else {
                    $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Mecânico Cadastrado Sem Sucesso!</div>';
                    return false;
                }
            }
        }
    }

    private function valTecnicos() {
        $queryValBI = "SELECT nbi FROM usuario WHERE nbi LIKE '{$this->dados['nbi']}'";
        $result_ValBI = $this->conn->prepare($queryValBI);
        $result_ValBI->execute();

        $queryValEmail = "SELECT email FROM usuario WHERE email LIKE '{$this->dados['email']}'";
        $result_ValEmail = $this->conn->prepare($queryValEmail);
        $result_ValEmail->execute();

        $queryValNif = "SELECT nif FROM usuario WHERE nif LIKE '{$this->dados['nif']}'";
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
                    if ($this->dados['nbi'] == $this->dados['nif']) {
                        return true;
                    } else {
                        $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Número de Indetificação Fiscal (NIF) Tem de Ser Indetico ao Número do Bilhete!</div>';
                        return false;
                    }
                }
            }
        }
    }

    private function valEditTecnicos() {
        $this->idUsuario();
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
                    if ($this->dados['nbi'] == $this->dados['nif']) {
                        return true;
                    } else {
                        $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Número de Indetificação Fiscal (NIF) Tem de Ser Indetico ao Número do Bilhete!</div>';
                        return false;
                    }
                }
            }
        }
    }

    private function upload() {
        // Fazer o Upload da Foto 
        $formatos = array("png", "jpg", "jpeg");
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
                $_SESSION['smg'] = '<div class="alert alert-danger text-center"> Upload Sem Sucesso!</div>';
                return false;
            }
        } else {
            $_SESSION['smg'] = '
              <div class="alert alert-danger" role="alert">
              Ficheiro Não Permitido! Escolha Uma Imagem Com Esses Formatos (png,jpg,jpeg)
              </div>
              ';
            return false;
        }
    }

    public function dadosTecnico() {
        $dadosMecânico = "SELECT m.*, u.st_conta FROM tecnicos m LEFT JOIN usuario u ON u.nbi = m.nbi AND u.nif = m.nif AND u.nivel = 'mecanico'";
        $result_resenceador = $this->conn->prepare($dadosMecânico);
        $result_resenceador->execute();
        $this->dados = $result_resenceador->fetchAll();
        return $this->dados;
    }
    public function dadosClienteNif() {
        $dadosMecânico = "SELECT nif FROM clientes LIMIT 1";
        $result_resenceador = $this->conn->prepare($dadosMecânico);
        $result_resenceador->execute();
        $this->dados = $result_resenceador->fetchAll();
        return $this->dados;
    }

    public function deleteTecnico($dados) {
        $this->dados = $dados;
        $this->dados['idtecnico'] = $this->limparInput($this->dados['idtecnico']);
        //echo "<br> ".$this->dados['idtecnico'];
        $this->idUsuario();
        //var_dump($this->dados['idusuario']);
        //echo "<br> id Usuario " . $this->dados['idusuario'];
        //var_dump($this->dados['idusuario']);
        $query_mecanico = "DELETE FROM tecnicos  WHERE idtecnico=:idtecnico";
        $resul_deletMecanico = $this->conn->prepare($query_mecanico);
        $resul_deletMecanico->bindParam(":idtecnico", $this->dados['idtecnico']);
        $resul_deletMecanico->execute();
        if ($resul_deletMecanico->rowCount()) {
            $query_usuario = "DELETE FROM usuario WHERE idusuario=:idusuario";
            $resul_deletusuario = $this->conn->prepare($query_usuario);
            $resul_deletusuario->bindParam(":idusuario", $this->dados['idusuario']);
            $resul_deletusuario->execute();
            if ($resul_deletusuario->rowCount()) {

                $_SESSION['msg'] = '
          <div class="alert alert-success text-center" role="alert">
          Mecânico Deletado Com Sucesso!
          </div>
          ';
                return true;
            } else {
                $_SESSION['msg'] = '
          <div class="alert alert-danger text-center" role="alert">
          Mecânico Deletado Com Sucesso Usuario Não foi Deletado<br> Contactar O Desevolvidor do Sistema!
          </div>
          ';
                return false;
            }
        } else {
            $_SESSION['msg'] = '
          <div class="alert alert-danger text-center" role="alert">
          Mecânico Deletado Sem Sucesso !
          </div>
          ';
            return false;
        }
    }

    public function idUsuario() {
        $query = "SELECT idusuario FROM usuario WHERE email=:email AND nif=:nif AND nbi=:nbi LIMIT 1";
        $resultverf = $this->conn->prepare($query);
        $resultverf->bindParam(":email", $this->dados['email']);
        $resultverf->bindParam(":nif", $this->dados['nif']);
        $resultverf->bindParam(":nbi", $this->dados['nbi']);
        $resultverf->execute();
        if ($resultverf->rowCount()) {
            $idusuario = $resultverf->fetch();
            foreach ($idusuario as $value) {
                $this->dados['idusuario'] = $value;
            }
        } else {
            //var_dump($this->dados);
            $this->dados['idusuario'] = 0;
        }
        //var_dump($this->dados);
    }

    public function editTecnico($dados) {
        $this->dados = $dados;
        $this->idUsuario();
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
        if ($this->valEditTecnicos()) {


            $query_cdsMecanico = "UPDATE tecnicos SET nbi=:nbi, nif=:nif, nome=:nome, sobrenome=:sobrenome, email=:email, telefone=:telefone, morada=:morada, modified=NOW() WHERE idtecnico=:idtecnico";
            $result_cdsMecanico = $this->conn->prepare($query_cdsMecanico);
            $result_cdsMecanico->bindParam(":nbi", $this->dados['nbi']);
            $result_cdsMecanico->bindParam(":nif", $this->dados['nif']);
            $result_cdsMecanico->bindParam(":nome", $this->dados['nome']);
            $result_cdsMecanico->bindParam(":sobrenome", $this->dados['sobrenome']);
            $result_cdsMecanico->bindParam(":email", $this->dados['emailnovo']);
            $result_cdsMecanico->bindParam(":telefone", $this->dados['telefone']);
            $result_cdsMecanico->bindParam(":morada", $this->dados['morada']);
            $result_cdsMecanico->bindParam(":idtecnico", $this->dados['idtecnico']);
            $result_cdsMecanico->execute();
            if ($result_cdsMecanico->rowCount()) {
                //$this->dados['nrecenseador'] = $this->conn->lastInsertId();
                $this->dados['senha'] = rand(1000, 999999);
                $this->dados['senhaguardada'] = $this->dados['senha'];
                $this->dados['senha'] = md5($this->dados['senha']);
                $this->dados['nivel'] = "Mecânico";
                $this->dados['st_conta'] = "Ativada";
                //var_dump($this->dados);

                $query_login = "UPDATE usuario SET nome=:nome, sobrenome=:sobrenome, email=:email, telefone=:telefone, senha=:senha,  modified=NOW() WHERE idusuario=:idusuario";
                $result_login = $this->conn->prepare($query_login);
                $result_login->bindParam(":nome", $this->dados['nome']);
                $result_login->bindParam(":sobrenome", $this->dados['sobrenome']);
                $result_login->bindParam(":email", $this->dados['emailnovo']);
                $result_login->bindParam(":telefone", $this->dados['telefone']);
                $result_login->bindParam(":senha", $this->dados['senha']);
                $result_login->bindParam(":idusuario", $this->dados['idusuario']);
                //$result_login->bindParam(":foto", $this->dados['novo_nome']);
                $result_login->execute();
                if ($result_login->rowCount()) {
                    $_SESSION['msg'] = '<div class="alert alert-success text-center"> Mecânico Editado Com Sucesso!</div>';
                    //$this->enviarEmail();
                    return true;
                } else {
                    $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Mecânico Editado Sem Sucesso Dados do Login!</div>';
                    return false;
                }
            } else {
                $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Mecânico Editado Sem Sucesso!</div>';
                return false;
            }
        }
    }

    //Recepcionista    

    public function dadosRecepcionista() {
        $query_dadosRecep = "SELECT r.*, u.st_conta FROM recepcionista r LEFT JOIN usuario u ON u.nbi = r.nbi AND u.nif = r.nif AND u.nivel = 'recep'";
        $result_dadosRecep = $this->conn->prepare($query_dadosRecep);
        $result_dadosRecep->execute();
        $this->dados = $result_dadosRecep->fetchAll();
        return $this->dados;
    }

    public function cdsRecepcionista($dados) {
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
        if ($this->valTecnicos()) {
            if ($this->upload()) {

                $query_cdsRecepcionista = "INSERT INTO recepcionista(nbi, nif, nome, sobrenome, email, telefone, morada, created, foto) VALUES (:nbi, :nif, :nome, :sobrenome, :email, :telefone, :morada, NOW(), :foto)";
                $result_cdsRecepcionista = $this->conn->prepare($query_cdsRecepcionista);
                $result_cdsRecepcionista->bindParam(":nbi", $this->dados['nbi']);
                $result_cdsRecepcionista->bindParam(":nif", $this->dados['nif']);
                $result_cdsRecepcionista->bindParam(":nome", $this->dados['nome']);
                $result_cdsRecepcionista->bindParam(":sobrenome", $this->dados['sobrenome']);
                $result_cdsRecepcionista->bindParam(":email", $this->dados['email']);
                $result_cdsRecepcionista->bindParam(":telefone", $this->dados['telefone']);
                $result_cdsRecepcionista->bindParam(":morada", $this->dados['morada']);
                $result_cdsRecepcionista->bindParam(":foto", $this->dados['novo_nome']);
                $result_cdsRecepcionista->execute();
                if ($result_cdsRecepcionista->rowCount()) {
                    //$this->dados['nrecenseador'] = $this->conn->lastInsertId();
                    $this->dados['senha'] = rand(1000, 999999);
                    $this->dados['senhaguardada'] = $this->dados['senha'];
                    $this->dados['senha'] = md5($this->dados['senha']);
                    $this->dados['nivel'] = "recep";
                    $this->dados['st_conta'] = "Ativada";
                    //var_dump($this->dados);

                    $query_login = "INSERT INTO usuario (nbi, nif, nome, sobrenome, email, telefone, senha, nivel, st_conta, foto, created)
                    VALUES (:nbi, :nif, :nome, :sobrenome, :email, :telefone, :senha, :nivel, :st_conta, :foto, NOW())";
                    $result_login = $this->conn->prepare($query_login);
                    $result_login->bindParam(":nome", $this->dados['nome']);
                    $result_login->bindParam(":sobrenome", $this->dados['sobrenome']);
                    $result_login->bindParam(":email", $this->dados['email']);
                    $result_login->bindParam(":telefone", $this->dados['telefone']);
                    $result_login->bindParam(":senha", $this->dados['senha']);
                    $result_login->bindParam(":nivel", $this->dados['nivel']);
                    $result_login->bindParam(":st_conta", $this->dados['st_conta']);
                    $result_login->bindParam(":foto", $this->dados['novo_nome']);
                    $result_login->bindParam(":nbi", $this->dados['nbi']);
                    $result_login->bindParam(":nif", $this->dados['nif']);

                    $result_login->execute();
                    if ($result_login->rowCount()) {
                        //$_SESSION['msg'] = '<div class="alert alert-success text-center"> Recepicionista Cadastrado Com Sucesso!</div>';
                        $this->enviarEmail();
                        return true;
                    } else {
                        $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Recepicionista Cadastrado Sem Sucesso Dados do Login!</div>';
                        return false;
                    }
                } else {
                    $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Recepicionista Cadastrado Sem Sucesso!</div>';
                    return false;
                }
            }
        }
    }

    public function deleteRecepcionista($dados) {
        $this->dados = $dados;
        $this->dados['idrecepcionista'] = $this->limparInput($this->dados['idrecepcionista']);

        //echo "<br> ".$this->dados['idtecnico'];
        $this->idUsuario();
        //echo "<br> id Usuario " . $this->dados['idusuario'];
        //var_dump($this->dados['idusuario']);
        $query_mecanico = "DELETE FROM recepcionista  WHERE idrecepcionista=:idrecepcionista";
        $resul_deletMecanico = $this->conn->prepare($query_mecanico);
        $resul_deletMecanico->bindParam(":idrecepcionista", $this->dados['idrecepcionista']);
        $resul_deletMecanico->execute();
        if ($resul_deletMecanico->rowCount()) {
            $query_usuario = "DELETE FROM usuario WHERE idusuario=:idusuario";
            $resul_deletusuario = $this->conn->prepare($query_usuario);
            $resul_deletusuario->bindParam(":idusuario", $this->dados['idusuario']);
            $resul_deletusuario->execute();
            if ($resul_deletusuario->rowCount()) {

                $_SESSION['msg'] = '
          <div class="alert alert-success text-center" role="alert">
          Recepicionista Deletado Com Sucesso!
          </div>
          ';
                return true;
            } else {
                $_SESSION['msg'] = '
          <div class="alert alert-danger text-center" role="alert">
          Recepicionista Deletado Com Sucesso Usuario Não foi Deletado<br> Coctar O Desevolvidor do Sistema!
          </div>
          ';
                return false;
            }
        } else {
            $_SESSION['msg'] = '
          <div class="alert alert-danger text-center" role="alert">
          Recepicionista Deletado Sem Sucesso !
          </div>
          ';
            return false;
        }
    }

    public function editRecepicionista($dados) {
        $this->dados = $dados;
        $this->idUsuario();
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
        if ($this->valEditTecnicos()) {


            $query_cdsRecepcionista = "UPDATE recepcionista SET nbi=:nbi, nif=:nif, nome=:nome, sobrenome=:sobrenome, email=:email, telefone=:telefone, morada=:morada, modified=NOW() WHERE idrecepcionista=:idrecepcionista";
            $result_cdsRecepcionista = $this->conn->prepare($query_cdsRecepcionista);
            $result_cdsRecepcionista->bindParam(":nbi", $this->dados['nbi']);
            $result_cdsRecepcionista->bindParam(":nif", $this->dados['nif']);
            $result_cdsRecepcionista->bindParam(":nome", $this->dados['nome']);
            $result_cdsRecepcionista->bindParam(":sobrenome", $this->dados['sobrenome']);
            $result_cdsRecepcionista->bindParam(":email", $this->dados['emailnovo']);
            $result_cdsRecepcionista->bindParam(":telefone", $this->dados['telefone']);
            $result_cdsRecepcionista->bindParam(":morada", $this->dados['morada']);
            $result_cdsRecepcionista->bindParam(":idrecepcionista", $this->dados['idrecepcionista']);
            $result_cdsRecepcionista->execute();
            if ($result_cdsRecepcionista->rowCount()) {
                //$this->dados['nrecenseador'] = $this->conn->lastInsertId();
                $this->dados['senha'] = rand(1000, 999999);
                $this->dados['senhaguardada'] = $this->dados['senha'];
                $this->dados['senha'] = md5($this->dados['senha']);
                $this->dados['nivel'] = "recep";
                $this->dados['st_conta'] = "Ativada";
                //var_dump($this->dados);

                $query_login = "UPDATE usuario SET nome=:nome, sobrenome=:sobrenome, email=:email, telefone=:telefone, senha=:senha,  modified=NOW() WHERE idusuario=:idusuario";
                $result_login = $this->conn->prepare($query_login);
                $result_login->bindParam(":nome", $this->dados['nome']);
                $result_login->bindParam(":sobrenome", $this->dados['sobrenome']);
                $result_login->bindParam(":email", $this->dados['emailnovo']);
                $result_login->bindParam(":telefone", $this->dados['telefone']);
                $result_login->bindParam(":senha", $this->dados['senha']);
                $result_login->bindParam(":idusuario", $this->dados['idusuario']);
                //$result_login->bindParam(":foto", $this->dados['novo_nome']);
                $result_login->execute();
                if ($result_login->rowCount()) {
                    $_SESSION['msg'] = '<div class="alert alert-success text-center"> Recepicionista Editado Com Sucesso!</div>';
                    //$this->enviarEmail();
                    return true;
                } else {
                    $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Recepicionista Editado Sem Sucesso Dados do Login!</div>';
                    return false;
                }
            } else {
                $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Recepicionista Editado Sem Sucesso!</div>';
                return false;
            }
        }
    }

    //Fornecedor

    public function dadosFornecedor() {
        $query_dadosFornecedor = "SELECT * FROM fornecedor";
        $result_dadosFornecedor = $this->conn->prepare($query_dadosFornecedor);
        $result_dadosFornecedor->execute();
        $this->dados = $result_dadosFornecedor->fetchAll();
        return $this->dados;
    }

    public function cdsFornecedor($dados) {
        $this->dados = $dados;
        $this->dados['email'] = $this->limparInput($this->dados['email']);
        $this->dados['nome'] = $this->limparInput($this->dados['nome']);
        $this->dados['tipo_pessoa'] = $this->limparInput($this->dados['tipo_pessoa']);
        //$this->dados['sobrenome'] = $this->limparInput($this->dados['sobrenome']);
        $this->dados['nbi'] = $this->limparInput($this->dados['nbi']);
        $this->dados['nif'] = $this->limparInput($this->dados['nif']);
        $this->dados['telefone'] = $this->limparInput($this->dados['telefone']);
        $this->dados['morada'] = $this->limparInput($this->dados['morada']);

        //var_dump($this->dados);
        if ($this->valFornecedor()) {


            $query_cdsFornecedor = "INSERT INTO fornecedor(nbi, nif, nome, email, telefone, morada, tipo_pessoa, created) VALUES (:nbi, :nif, :nome, :email, :telefone, :morada, :tipo_pessoa, NOW())";
            $result_cdsFornecedor = $this->conn->prepare($query_cdsFornecedor);
            $result_cdsFornecedor->bindParam(":nbi", $this->dados['nbi']);
            $result_cdsFornecedor->bindParam(":nif", $this->dados['nif']);
            $result_cdsFornecedor->bindParam(":nome", $this->dados['nome']);
            //$result_cdsFornecedor->bindParam(":sobrenome", $this->dados['sobrenome']);
            $result_cdsFornecedor->bindParam(":email", $this->dados['email']);
            $result_cdsFornecedor->bindParam(":telefone", $this->dados['telefone']);
            $result_cdsFornecedor->bindParam(":morada", $this->dados['morada']);
            $result_cdsFornecedor->bindParam(":tipo_pessoa", $this->dados['tipo_pessoa']);
            $result_cdsFornecedor->execute();
            //var_dump($query_cdsFornecedor);

            if ($result_cdsFornecedor->rowCount()) {
                $_SESSION['msg'] = '<div class="alert alert-success text-center"> Fornecedor Cadastrado Com Sucesso!</div>';
                //$this->enviarEmail();
                return true;
            } else {
                $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Fornecedor Cadastrado Sem Sucesso!</div>';
                return false;
            }
        }
    }

    private function valFornecedor() {
        $queryValBI = "SELECT nbi FROM fornecedor WHERE nbi LIKE '{$this->dados['nbi']}'";
        $result_ValBI = $this->conn->prepare($queryValBI);
        $result_ValBI->execute();

        $queryValEmail = "SELECT email FROM fornecedor WHERE email LIKE '{$this->dados['email']}'";
        $result_ValEmail = $this->conn->prepare($queryValEmail);
        $result_ValEmail->execute();

        $queryValNif = "SELECT nif FROM fornecedor WHERE nif LIKE '{$this->dados['nif']}'";
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

    private function valEditFornecedor() {
        $queryValBI = "SELECT nbi FROM fornecedor WHERE nbi LIKE '{$this->dados['nbi']}' AND idfornecedor!='{$this->dados['idfornecedor']}'";
        $result_ValBI = $this->conn->prepare($queryValBI);
        //$result_ValBI->bindParam(":idfornecedor", $this->dados['idfornecedor']);
        $result_ValBI->execute();

        $queryValEmail = "SELECT email FROM fornecedor WHERE email LIKE '{$this->dados['emailnovo']}' AND idfornecedor!='{$this->dados['idfornecedor']}'";
        $result_ValEmail = $this->conn->prepare($queryValEmail);
        //$result_ValEmail->bindParam(":idfornecedor", $this->dados['idfornecedor']);
        $result_ValEmail->execute();

        $queryValNif = "SELECT nif FROM fornecedor WHERE nif LIKE '{$this->dados['nif']}' AND idfornecedor!='{$this->dados['idfornecedor']}'";
        $result_ValNif = $this->conn->prepare($queryValNif);
        //$result_ValNif->bindParam(":idfornecedor", $this->dados['idfornecedor']);
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

    public function deletFornecedor($dados) {
        $this->dados = $dados;
        $this->dados['idfornecedor'] = $this->limparInput($this->dados['idfornecedor']);
        $querydeleF = "DELETE FROM fornecedor WHERE idfornecedor=:idfornecedor";
        $resultDeletF = $this->conn->prepare($querydeleF);
        $resultDeletF->bindParam(":idfornecedor", $this->dados['idfornecedor']);
        $resultDeletF->execute();
        if ($resultDeletF->rowCount()) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center"> Fornecedor Deletado Com Sucesso!</div>';
            //$this->enviarEmail();
            return true;
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Fornecedor Deletado Sem Sucesso!</div>';
            return false;
        }
    }

    public function editFornecedor($dados) {
        $this->dados = @$dados;
        $this->idUsuario();
        $this->dados['emailnovo'] = $this->limparInput(@$this->dados['emailnovo']);
        $this->dados['nome'] = $this->limparInput(@$this->dados['nome']);
        $this->dados['sobrenome'] = $this->limparInput(@$this->dados['sobrenome']);
        $this->dados['nbi'] = $this->limparInput(@$this->dados['nbi']);
        $this->dados['nif'] = $this->limparInput(@$this->dados['nif']);
        $this->dados['telefone'] = $this->limparInput(@$this->dados['telefone']);
        $this->dados['morada'] = $this->limparInput(@$this->dados['morada']);


        //var_dump($this->dados);
        //var_dump($this->dados);
        //var_dump($this->dados['foto']['name']);
        if ($this->valEditFornecedor()) {
            $query_cdsFornecedor = "UPDATE fornecedor SET nbi=:nbi, nif=:nif, nome=:nome,  email=:email, telefone=:telefone, morada=:morada, tipo_pessoa=:tipo_pessoa, modified=NOW() WHERE idfornecedor=:idfornecedor";
            $result_cdsFornecedor = $this->conn->prepare($query_cdsFornecedor);
            $result_cdsFornecedor->bindParam(":nbi", $this->dados['nbi']);
            $result_cdsFornecedor->bindParam(":nif", $this->dados['nif']);
            $result_cdsFornecedor->bindParam(":nome", $this->dados['nome']);
            $result_cdsFornecedor->bindParam(":tipo_pessoa", $this->dados['tipo_pessoa']);
            $result_cdsFornecedor->bindParam(":email", $this->dados['emailnovo']);
            $result_cdsFornecedor->bindParam(":telefone", $this->dados['telefone']);
            $result_cdsFornecedor->bindParam(":morada", $this->dados['morada']);
            $result_cdsFornecedor->bindParam(":idfornecedor", $this->dados['idfornecedor']);
            $result_cdsFornecedor->execute();
            if ($result_cdsFornecedor->rowCount()) {
                $_SESSION['msg'] = '<div class="alert alert-success text-center"> Fornecedor Editado Com Sucesso!</div>';
                //$this->enviarEmail();
                return true;
            } else {
                $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Fornecedor Editado Sem Sucesso!</div>';
                return false;
            }
        }
    }

    //Categoria

    public function dadosCategoria() {
        $query_dadosCategoria = "SELECT * FROM categoria";
        $result_dadosCategoria = $this->conn->prepare($query_dadosCategoria);
        $result_dadosCategoria->execute();
        $this->dados = $result_dadosCategoria->fetchAll();
        return $this->dados;
    }

    public function dadosProdutos() {
        $query_dadosProduto = "SELECT * FROM dadosProduto";
        $result_dadosProduto = $this->conn->prepare($query_dadosProduto);
        $result_dadosProduto->execute();
        $this->dados = $result_dadosProduto->fetchAll();
        return $this->dados;
    }

    public function dadosProdutosEditFoto($id) {
        $this->dados = $id;
        $this->dados['idproduto'] = $this->limparInput($this->dados['idproduto']);
        $query_dadosProduto = "SELECT * FROM dadosProduto WHERE idproduto=:idproduto LIMIT 1";
        $result_dadosProduto = $this->conn->prepare($query_dadosProduto);
        $result_dadosProduto->bindParam(":idproduto", $this->dados['idproduto']);
        $result_dadosProduto->execute();
        $this->dados = $result_dadosProduto->fetch();
        return $this->dados;
    }

    public function editCategoria($dados) {
        $this->dados = $dados;
        $this->dados['nome'] = $this->limparInput(@$this->dados['nome']);
        $this->dados['idcategoria'] = $this->limparInput(@$this->dados['idcategoria']);


        //var_dump($this->dados);
        //var_dump($this->dados);
        //var_dump($this->dados['foto']['name']);

        $query_cdsCategoria = "UPDATE categoria SET  nome=:nome, modified=NOW() WHERE idcategoria=:idcategoria";
        $result_cdsCategoria = $this->conn->prepare($query_cdsCategoria);
        $result_cdsCategoria->bindParam(":nome", $this->dados['nome']);
        $result_cdsCategoria->bindParam(":idcategoria", $this->dados['idcategoria']);
        $result_cdsCategoria->execute();
        if ($result_cdsCategoria->rowCount()) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center"> Categoria Editado Com Sucesso!</div>';
            //$this->enviarEmail();
            return true;
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Categoria Editado Sem Sucesso!</div>';
            return false;
        }
    }

    public function deletCategoria($dados) {
        $this->dados = $dados;
        $this->dados['idcategoria'] = $this->limparInput($this->dados['idcategoria']);
        $querydeleC = "DELETE FROM categoria WHERE idcategoria=:idcategoria";
        $resultDeletC = $this->conn->prepare($querydeleC);
        $resultDeletC->bindParam(":idcategoria", $this->dados['idcategoria']);
        $resultDeletC->execute();
        if ($resultDeletC->rowCount()) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center"> Categoria Deletado Com Sucesso!</div>';
            //$this->enviarEmail();
            return true;
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Categoria Deletado Sem Sucesso!</div>';
            return false;
        }
    }

    public function cdsCategoria($dados) {
        $this->dados = $dados;
        $this->dados['nome'] = $this->limparInput($this->dados['nome']);


        $query_cdsCategoria = "INSERT INTO categoria(nome, created) VALUES (:nome, NOW())";
        $result_cdsCategoria = $this->conn->prepare($query_cdsCategoria);
        $result_cdsCategoria->bindParam(":nome", $this->dados['nome']);
        $result_cdsCategoria->execute();
        if ($result_cdsCategoria->rowCount()) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center"> Categoria Cadastrado Com Sucesso!</div>';
            //$this->enviarEmail();
            return true;
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Categoria Cadastrado Sem Sucesso!</div>';
            return false;
        }
    }

    public function dadosCategoriaId() {
        $query_dados = "SELECT idcategoria, nome FROM categoria";
        $result_dados = $this->conn->prepare($query_dados);
        $result_dados->execute();
        $this->dados = $result_dados->fetchAll();
        return $this->dados;
    }

    public function dadosFornecedorId() {
        $query_dados = "SELECT idfornecedor, nome FROM fornecedor";
        $result_dados = $this->conn->prepare($query_dados);
        $result_dados->execute();
        $this->dados = $result_dados->fetchAll();
        return $this->dados;
    }

    //Produtos

    public function cdsProduto($dados) {
        $this->dados = $dados;
        $this->dados['referencia'] = $this->limparInput($this->dados['referencia']);
        $this->dados['nome'] = $this->limparInput($this->dados['nome']);
        $this->dados['valor_compra'] = $this->limparInput($this->dados['valor_compra']);
        $this->dados['valor_venda'] = $this->limparInput($this->dados['valor_venda']);
        $this->dados['estoque'] = $this->limparInput($this->dados['estoque']);
        $this->dados['descricao'] = $this->limparInput($this->dados['descricao']);
        $this->dados['fornecedor'] = $this->limparInput($this->dados['fornecedor']);
        $this->dados['categoria'] = $this->limparInput($this->dados['categoria']);
        //Quantidade A ser Acrescentada na compra
        $this->dados['quantidade_estoque'] = $this->dados['estoque'];


        //var_dump($this->dados);
        //var_dump($this->dados['foto']['name']);
        if ($this->valProduto()) {
            if ($this->upload()) {
                //var_dump($this->dados);
                $query_cdsProduto = "INSERT INTO produto(idfornecedor, idcategoria, referencia, nome, valor_compra, valor_venda, estoque, descricao, created, foto)
                                                VALUES (:idfornecedor, :idcategoria, :referencia, :nome, :valor_compra, :valor_venda, :estoque, :descricao, NOW(), :foto)";
                $result_cdsProduto = $this->conn->prepare($query_cdsProduto);
                $result_cdsProduto->bindParam(":idfornecedor", $this->dados['fornecedor']);
                $result_cdsProduto->bindParam(":idcategoria", $this->dados['categoria']);
                $result_cdsProduto->bindParam(":referencia", $this->dados['referencia']);
                $result_cdsProduto->bindParam(":nome", $this->dados['nome']);
                $result_cdsProduto->bindParam(":valor_compra", $this->dados['valor_compra']);
                $result_cdsProduto->bindParam(":valor_venda", $this->dados['valor_venda']);
                $result_cdsProduto->bindParam(":estoque", $this->dados['estoque']);
                $result_cdsProduto->bindParam(":descricao", $this->dados['descricao']);
                $result_cdsProduto->bindParam(":foto", $this->dados['novo_nome']);
                $result_cdsProduto->execute();
                if ($result_cdsProduto->rowCount()) {
                    $this->dados['valor'] = $this->dados['quantidade_estoque'] * $this->dados['valor_compra'];
                    $this->dados['idproduto'] = $this->conn->lastInsertId();
                    $this->cdsContaApagar();
                    $this->dados['idcontas_apagar'] = $this->conn->lastInsertId();
                    $this->cdsCompras();

                    $_SESSION['msg'] = '<div class="alert alert-success text-center"> Produto Cadastrado Com Sucesso!</div>';
                    //$this->enviarEmail();
                    return true;
                } else {
                    $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Produto Cadastrado Sem Sucesso!</div>';
                    return false;
                }
            }
        }
    }

    public function editProduto($dados) {
        $this->dados = $dados;
        $this->dados['referencia'] = $this->limparInput($this->dados['referencia']);
        $this->dados['nome'] = $this->limparInput($this->dados['nome']);
        $this->dados['valor_compra'] = $this->limparInput($this->dados['valor_compra']);
        $this->dados['valor_venda'] = $this->limparInput($this->dados['valor_venda']);
        //$this->dados['estoque'] = $this->limparInput($this->dados['estoque']);
        $this->dados['descricao'] = $this->limparInput($this->dados['descricao']);
        $this->dados['fornecedor'] = $this->limparInput($this->dados['fornecedor']);
        $this->dados['categoria'] = $this->limparInput($this->dados['categoria']);
        $this->dados['idproduto'] = $this->limparInput($this->dados['idproduto']);




        //var_dump($this->dados);
        //var_dump($this->dados['foto']['name']);
        if ($this->valEditProduto()) {
            //$this->formatarValor($this->dados['estoque']);
            $this->formatarValor($this->dados['valor_compra']);
            $this->formatarValor($this->dados['valor_venda']);

            //$this->dados['estoque'] = $this->dados['estoque'] + $this->dados['estoque_antigo'];
            //var_dump($this->dados);
            //var_dump($this->dados);
            $query_cdsProduto = "UPDATE produto SET idfornecedor=:idfornecedor, idcategoria=:idcategoria, referencia=:referencia, nome=:nome, valor_compra=:valor_compra, valor_venda=:valor_venda, descricao=:descricao, modified=NOW()
            WHERE idproduto=:idproduto";
            $result_cdsProduto = $this->conn->prepare($query_cdsProduto);
            $result_cdsProduto->bindParam(":idproduto", $this->dados['idproduto']);
            $result_cdsProduto->bindParam(":idfornecedor", $this->dados['fornecedor']);
            $result_cdsProduto->bindParam(":idcategoria", $this->dados['categoria']);
            $result_cdsProduto->bindParam(":referencia", $this->dados['referencia']);
            $result_cdsProduto->bindParam(":nome", $this->dados['nome']);
            $result_cdsProduto->bindParam(":valor_compra", $this->dados['valor_compra']);
            $result_cdsProduto->bindParam(":valor_venda", $this->dados['valor_venda']);
            //$result_cdsProduto->bindParam(":estoque", $this->dados['estoque']);
            $result_cdsProduto->bindParam(":descricao", $this->dados['descricao']);
            $result_cdsProduto->execute();
            if ($result_cdsProduto->rowCount()) {
                $_SESSION['msg'] = '<div class="alert alert-success text-center"> Produto Editado Com Sucesso!</div>';
                //$this->enviarEmail();
                return true;
            } else {
                $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Produto Editado Sem Sucesso!</div>';
                return false;
            }
        }
    }

    public function editFotoProduto($dados) {
        $this->dados = $dados;
        $this->dados['idproduto'] = $this->limparInput($this->dados['idproduto']);




        //var_dump($this->dados);
        //var_dump($this->dados['foto']['name']);

        if ($this->upload()) {
            //var_dump($this->dados);
            $query_cdsProduto = "UPDATE produto SET  modified=NOW(), foto=:foto
                WHERE idproduto=:idproduto";
            $result_cdsProduto = $this->conn->prepare($query_cdsProduto);
            $result_cdsProduto->bindParam(":idproduto", $this->dados['idproduto']);
            $result_cdsProduto->bindParam(":foto", $this->dados['novo_nome']);
            $result_cdsProduto->execute();
            if ($result_cdsProduto->rowCount()) {
                $_SESSION['msg'] = '<div class="alert alert-success text-center"> Foto do Produto Editado Com Sucesso!</div>';
                //$this->enviarEmail();
                return true;
            } else {
                $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Foto do Produto Editado Sem Sucesso!</div>';
                return false;
            }
        }
    }

    public function valProduto() {
        $query = "SELECT * FROM dadosProduto WHERE nome=:nome AND referencia=:referencia LIMIT 1";
        $result = $this->conn->prepare($query);
        $result->bindParam(":nome", $this->dados['nome']);
        $result->bindParam(":referencia", $this->dados['referencia']);
        $result->execute();
        if ($result->rowCount()) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Produto Já Cadastrado Com Mesmo nome e Referencia!</div>';
            return false;
        } else {
            return true;
        }
    }

    public function valEditProduto() {
        $query = "SELECT * FROM dadosProduto WHERE nome=:nome AND referencia=:referencia AND idproduto!=:idproduto LIMIT 1";
        $result = $this->conn->prepare($query);
        $result->bindParam(":nome", $this->dados['nome']);
        $result->bindParam(":referencia", $this->dados['referencia']);
        $result->bindParam(":idproduto", $this->dados['idproduto']);
        $result->execute();
        if ($result->rowCount()) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Produto Já Editado Com Mesmo nome e Referencia!</div>';
            return false;
        } else {
            return true;
        }
    }

    public function deletProduto($dados) {
        $this->dados = $dados;
        $this->dados['idproduto'] = $this->limparInput($this->dados['idproduto']);
        $querydeleP = "DELETE FROM produto WHERE idproduto=:idproduto";
        $resultDeletP = $this->conn->prepare($querydeleP);
        $resultDeletP->bindParam(":idproduto", $this->dados['idproduto']);
        $resultDeletP->execute();
        if ($resultDeletP->rowCount()) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center"> Produto Deletado Com Sucesso!</div>';
            //$this->enviarEmail();
            return true;
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Produto Deletado Sem Sucesso!</div>';
            return false;
        }
    }

    private function formatarValor($valor) {
        $valor = $valor;
        $valor = number_format($valor, 2, ',', '.');
        return $valor;
    }

    public function addEstoque($dados) {
        $this->dados = $dados;
        $this->dados['idproduto'] = $this->limparInput($this->dados['idproduto']);
        $this->dados['estoque'] = $this->limparInput($this->dados['estoque']);
        $this->dados['estoque_antigo'] = $this->limparInput($this->dados['estoque_antigo']);
        $this->dados['valor_compra'] = $this->limparInput($this->dados['valor_compra']);
        $this->dados['valor_venda'] = $this->limparInput($this->dados['valor_venda']);
        $this->dados['fornecedor'] = $this->limparInput($this->dados['fornecedor']);
        //Quantidade A ser Acrescentada na compra
        $this->dados['quantidade_estoque'] = $this->dados['estoque'];
        $this->dados['estoque'] = $this->dados['estoque'] + $this->dados['estoque_antigo'];
        $result_1 = $this->conn->prepare("SELECT  foto FROM produto WHERE idproduto='{$this->dados['idproduto']}'");
        $result_1->execute();
        $reslutado_foto = $result_1->fetch(\PDO::FETCH_ASSOC);
        if (isset($reslutado_foto['foto']) && !empty($reslutado_foto['foto'])) {
            $this->dados['novo_nome'] = $reslutado_foto['foto'];
        }

        //var_dump($this->dados);
        //var_dump($this->dados['foto']['name']);
        //var_dump($this->dados);
        $query_cdsProduto = "UPDATE produto SET estoque=:estoque, valor_compra=:valor_compra, valor_venda=:valor_venda, idfornecedor=:fornecedor
                WHERE idproduto=:idproduto";
        $result_cdsProduto = $this->conn->prepare($query_cdsProduto);
        $result_cdsProduto->bindParam(":idproduto", $this->dados['idproduto']);
        $result_cdsProduto->bindParam(":estoque", $this->dados['estoque']);
        $result_cdsProduto->bindParam(":valor_compra", $this->dados['valor_compra']);
        $result_cdsProduto->bindParam(":valor_venda", $this->dados['valor_venda']);
        $result_cdsProduto->bindParam(":fornecedor", $this->dados['fornecedor']);
        $result_cdsProduto->execute();
        if ($result_cdsProduto->rowCount()) {
            $this->dados['valor'] = $this->dados['quantidade_estoque'] * $this->dados['valor_compra'];
            $this->cdsContaApagar();
            $this->dados['idcontas_apagar'] = $this->conn->lastInsertId();
            $this->cdsCompras();
            (new \App\adms\Models\AdmsMovimentoEstoque())->registar((int)$this->dados['idproduto'], 'Entrada', (int)$this->dados['quantidade_estoque'], 'Compra', $this->dados['idcontas_apagar'], $_SESSION['idlogado'] ?? null);
            $_SESSION['msg'] = '<div class="alert alert-success text-center"> Adicionado Valor no Estoque Com Sucesso!</div>';
            //$this->enviarEmail();
            return true;
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Adicionado Valor no Estoque Sem Sucesso!</div>';
            return false;
        }
    }

    public function dadosProdutosEstoqueBaixo() {
        $estoque = NIVEL_STOQUE;
        $query_dadosProduto = "SELECT * FROM dadosProduto WHERE estoque<=:nivel_estoque";
        $result_dadosProduto = $this->conn->prepare($query_dadosProduto);
        $result_dadosProduto->bindParam(":nivel_estoque", $estoque);
        $result_dadosProduto->execute();
        $this->dados = $result_dadosProduto->fetchAll();
        return $this->dados;
    }

    //Dados Tipo Serviço

    public function dadosTipoServico() {
        $query_dadosServico = "SELECT * FROM tipo_servico";
        $result_dadosServico = $this->conn->prepare($query_dadosServico);
        $result_dadosServico->execute();
        $this->dados = $result_dadosServico->fetchAll();
        return $this->dados;
    }

    public function cdsTipo_servico($dados) {
        $this->dados = $dados;
        $this->dados['nome'] = $this->limparInput($this->dados['nome']);
        $this->dados['valor'] = $this->limparInput(@$this->dados['valor']);



        $query_cdsTipo_servico = "INSERT INTO tipo_servico(nome, valor, created) VALUES (:nome, :valor, NOW())";
        $result_cdsTipo_servico = $this->conn->prepare($query_cdsTipo_servico);
        $result_cdsTipo_servico->bindParam(":nome", $this->dados['nome']);
        $result_cdsTipo_servico->bindParam(":valor", $this->dados['valor']);
        $result_cdsTipo_servico->execute();
        if ($result_cdsTipo_servico->rowCount()) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center"> Tipo Serviço Cadastrado Com Sucesso!</div>';
            //$this->enviarEmail();
            return true;
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Tipo Serviço Cadastrado Sem Sucesso!</div>';
            return false;
        }
    }

    public function editTipoServico($dados) {
        $this->dados = $dados;
        $this->dados['nome'] = $this->limparInput(@$this->dados['nome']);
        $this->dados['valor'] = $this->limparInput(@$this->dados['valor']);
        $this->dados['idtipo_servico'] = $this->limparInput(@$this->dados['idtipo_servico']);


        //var_dump($this->dados);
        //var_dump($this->dados);
        //var_dump($this->dados['foto']['name']);

        $query_cdsTipo_servico = "UPDATE tipo_servico SET  nome=:nome, valor=:valor, modified=NOW() WHERE idtipo_servico=:idtipo_servico";
        $result_cdsTipo_servico = $this->conn->prepare($query_cdsTipo_servico);
        $result_cdsTipo_servico->bindParam(":nome", $this->dados['nome']);
        $result_cdsTipo_servico->bindParam(":valor", $this->dados['valor']);
        $result_cdsTipo_servico->bindParam(":idtipo_servico", $this->dados['idtipo_servico']);
        $result_cdsTipo_servico->execute();
        if ($result_cdsTipo_servico->rowCount()) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center"> Tipo Serviço Editado Com Sucesso!</div>';
            //$this->enviarEmail();
            return true;
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Tipo Serviço Editado Sem Sucesso!</div>';
            return false;
        }
    }

    public function deletTipoServico($dados) {
        $this->dados = $dados;
        $this->dados['idtipo_servico'] = $this->limparInput($this->dados['idtipo_servico']);
        $querydeleC = "DELETE FROM tipo_servico WHERE idtipo_servico=:idtipo_servico";
        $resultDeletC = $this->conn->prepare($querydeleC);
        $resultDeletC->bindParam(":idtipo_servico", $this->dados['idtipo_servico']);
        $resultDeletC->execute();
        if ($resultDeletC->rowCount()) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center"> Tipo Serviço Deletado Com Sucesso!</div>';
            //$this->enviarEmail();
            return true;
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Tipo Serviço Deletado Sem Sucesso!</div>';
            return false;
        }
    }

    // Compras
    public function deletCompras($dados) {
        $this->dados = $dados;
        $this->dados['idcompras'] = $this->limparInput($this->dados['idcompras']);
        $querydeleC = "DELETE FROM compras WHERE idcompras=:idcompras";
        $resultDeletC = $this->conn->prepare($querydeleC);
        $resultDeletC->bindParam(":idcompras", $this->dados['idcompras']);
        $resultDeletC->execute();
        if ($resultDeletC->rowCount()) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center"> Compras Deletado Com Sucesso!</div>';
            //$this->enviarEmail();
            return true;
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Compras Deletado Sem Sucesso!</div>';
            return false;
        }
    }

    public function dadosCompras() {
        $query_dadosCompras = "SELECT * FROM compras";
        $result_dadosComras = $this->conn->prepare($query_dadosCompras);
        $result_dadosComras->execute();
        $this->dados = $result_dadosComras->fetchAll();
        return $this->dados;
    }

    public function cdsCompras() {
        $funcionario = $_SESSION['nif'];
        $query = "INSERT INTO compras (funcionario, idcontas_apagar, idproduto, produto, valor, quantidade_estoque, estoque, data) VALUES (:funcionario, :idcontas_apagar, :idproduto,  :produto, :valor, :quantidade_estoque, :estoque, NOW())";
        $result = $this->conn->prepare($query);
        $result->bindParam(":funcionario", $funcionario);
        $result->bindParam(":produto", $this->dados['nome']);
        $result->bindParam(":valor", $this->dados['valor_compra']);
        $result->bindParam(":idcontas_apagar", $this->dados['idcontas_apagar']);
        $result->bindParam(":idproduto", $this->dados['idproduto']);
        $result->bindParam(":quantidade_estoque", $this->dados['quantidade_estoque']);
        $result->bindParam(":estoque", $this->dados['estoque']);
        $result->execute();
    }

    // Vendas
    public function deletVendas($dados) {
        $this->dados = $dados;
        $this->dados['idvendas'] = $this->limparInput($this->dados['idvendas']);
        $querydeleC = "DELETE FROM vendas WHERE idvendas=:idvendas";
        $resultDeletC = $this->conn->prepare($querydeleC);
        $resultDeletC->bindParam(":idvendas", $this->dados['idvendas']);
        $resultDeletC->execute();
        if ($resultDeletC->rowCount()) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center"> Vendas Deletado Com Sucesso!</div>';
            //$this->enviarEmail();
            return true;
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Vendas Deletado Sem Sucesso!</div>';
            return false;
        }
    }

    public function dadosVendas() {
        $query_dadosVendas = "SELECT * FROM vendas";
        $result_dadosComras = $this->conn->prepare($query_dadosVendas);
        $result_dadosComras->execute();
        $this->dados = $result_dadosComras->fetchAll();
        return $this->dados;
    }

    public function cdsVendas() {
        $funcionario = $_SESSION['nome'] . " " . $_SESSION['sobrenome'];
        $query = "INSERT INTO vendas (funcionario, produto, valor, data) VALUES (:funcionario, :produto, :valor, NOW())";
        $result = $this->conn->prepare($query);
        $result->bindParam(":funcionario", $funcionario);
        $result->bindParam(":produto", $this->dados['nome']);
        $result->bindParam(":valor", $this->dados['valor_compra']);
        $result->execute();
    }

    private function cdsContaApagar() {
        $this->dados['descricao'] = "Compra de Produto";
        $this->dados['funcionario'] = $_SESSION['nif'];
        //$this->dados['data_venci'] = $this->limparInput($this->dados['data_venci']);
        $this->dados['pago'] = "nao";


        //  var_dump($this->dados);
        $query = "INSERT INTO contas_apagar (descricao, valor, funcionario, data_venci, foto, pago, nome, created) VALUES (:descricao, :valor, :funcionario, NOW(), :foto, :pago, :nome, NOW())";
        $result = $this->conn->prepare($query);
        $result->bindParam(":descricao", $this->dados['descricao']);
        $result->bindParam(":valor", $this->dados['valor']);
        $result->bindParam(":funcionario", $this->dados['funcionario']);
        $result->bindParam(":pago", $this->dados['pago']);
        $result->bindParam(":foto", $this->dados['novo_nome']);
        $result->bindParam(":nome", $this->dados['nome']);
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

    // Orçamentos 
    public function abrirOrcamento($dados) {
        @$this->dados = @$dados;
        @$this->dados['veiculo'] = $this->limparInput(@$this->dados['veiculo']);
        @$this->dados['id_tipo_servico'] = $this->limparInput(@$this->dados['id_tipo_servico']);
        @$this->dados['valor'] = $this->limparInput(@$this->dados['valor']);
        //$this->dados['data'] = date('"Y-m-d');
        @$this->dados['data_entrega'] = $this->limparInput(@$this->dados['data_entrega']);
        @$this->dados['garantia'] = $this->limparInput(@$this->dados['garantia']);
        @$this->dados['descricao'] = $this->limparInput(@$this->dados['descricao']);
        @$this->dados['obs'] = $this->limparInput(@$this->dados['obs']);
        @$this->dados['status'] = "Aberto";
        @$this->dados['tecnico'] = @$_SESSION['nif'];
        $idOcorrenciaOrigem = !empty($dados['id_ocorrencia']) ? (int)$dados['id_ocorrencia'] : null;




        if ($this->valEditOrcClienteEquipamento()) {
            //var_dump($this->dados);
            //$query = "INSERT INTO orcamentos SET veiculo=:veiculo, id_tipo_servico=:id_tipo_servico, valor=:valor, data=curDate(), data_entrega=:data_entrega, garantia=:garantia, tecnico=:tecnico, descricacao=:descricacao, obs=:obs, status=:status, created=NOW()";
            $query = "INSERT INTO orcamentos (veiculo, id_tipo_servico, valor, data, data_entrega, garantia, tecnico, descricao, obs, status, tipo, created) VALUES
                     (:veiculo, :id_tipo_servico, :valor, curDate(), :data_entrega, :garantia, :tecnico, :descricao, :obs, :status, :tipo, NOW())";
            $result = $this->conn->prepare($query);
            $result->bindParam(":veiculo", $this->dados['veiculo']);
            $result->bindParam(":id_tipo_servico", $this->dados['id_tipo_servico']);
            $result->bindParam(":valor", $this->dados['valor']);
            //$result->bindParam(":data", $this->dados['data']);
            $result->bindParam(":data_entrega", $this->dados['data_entrega']);
            $result->bindParam(":garantia", $this->dados['garantia']);
            $result->bindParam(":tecnico", $this->dados['tecnico']);
            $result->bindParam(":descricao", $this->dados['descricao']);
            $result->bindParam(":obs", $this->dados['obs']);
            $result->bindParam(":status", $this->dados['status']);
            $result->bindParam(":tipo", $this->dados['tipo']);
            $result->execute();

            if ($result->rowCount()) {
                $idOrcamento = $this->conn->lastInsertId();
                $this->dados['idorcamentos'] = $idOrcamento;
                if ($idOcorrenciaOrigem) {
                    // Veio de uma Ocorrência já existente: liga em vez de criar uma sombra duplicada.
                    $ligar = $this->conn->prepare("UPDATE orcamentos SET id_ocorrencia=:id_ocorrencia WHERE idorcamentos=:idorcamentos");
                    $ligar->bindParam(":id_ocorrencia", $idOcorrenciaOrigem, PDO::PARAM_INT);
                    $ligar->bindParam(":idorcamentos", $idOrcamento);
                    $ligar->execute();

                    $ligarVolta = $this->conn->prepare("UPDATE ocorrencias SET id_orcamento=:idorcamentos WHERE idocorrencia=:id_ocorrencia");
                    $ligarVolta->bindParam(":idorcamentos", $idOrcamento);
                    $ligarVolta->bindParam(":id_ocorrencia", $idOcorrenciaOrigem, PDO::PARAM_INT);
                    $ligarVolta->execute();

                    (new \App\adms\Models\AdmsOcorrencia())->alterarEstado([
                        'idocorrencia' => $idOcorrenciaOrigem,
                        'estado' => 'Aguardando aprovação',
                        'observacao' => 'Orçamento criado a partir da ocorrência.',
                    ]);
                } else {
                    // Caminho legado (ainda acessível pela recepção sem passar por uma Ocorrência):
                    // mantém a criação automática da ocorrência-sombra.
                    $this->criarOcorrencia($this->dados, 'Aberta');
                }
                $_SESSION['msg'] = '<div class="alert alert-success text-center"> Orçamento Aberto Com Sucesso!</div>';
                return false;
            } else {
                $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Orçamento Aberto Sem Sucesso</div>';
                return false;
            }
        }
    }

    public function dadosOrcamentos() {
        $query_dados = "SELECT * FROM orcamentos";
        $result_dados = $this->conn->prepare($query_dados);
        $result_dados->execute();
        $this->dados = $result_dados->fetchAll();
        return $this->dados;
    }

    private function valOrcClienteEquipamento() {
        $resultC = $this->conn->prepare("SELECT * FROM dadosClienteVeiculo WHERE nif = '{$this->dados['clinete']}'");
        $resultC->execute();
        $resultM = $this->conn->prepare("SELECT * FROM dadosClienteVeiculo WHERE matricula = '{$this->dados['veiculo']}' LIMIT 1");
        $resultM->execute();

        $result = $this->conn->prepare("SELECT * FROM dadosClienteVeiculo WHERE nif = '{$this->dados['clinete']}' AND matricula = '{$this->dados['veiculo']}'");
        $result->execute();
        if ($resultC->rowCount()) {
            if ($resultM->rowCount()) {
                $resultBd = $resultM->fetch();
                if ($result->rowCount()) {
                    @$this->dados['idveiculo'] = $resultBd['idveiculo'];
                    $result = $this->conn->prepare("SELECT * FROM dadosorcamento WHERE nif = '{$this->dados['clinete']}' AND matricula = '{$this->dados['veiculo']}'");
                    $result->execute();
                    if ($result->rowCount()) {
                        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Esse Orçamento Já Está Aberto</div>';
                        return false;
                    } else {
                        return true;
                    }
                } else {
                    $_SESSION['msg'] = '<div class="alert alert-danger text-center">O NIF do Cliente não coincide com o proprietário do Nº de Série do Equipamento inserido.</div>';
                    return false;
                }
            } else {
                $_SESSION['msg'] = '<div class="alert alert-danger text-center">O Nº de Série do Equipamento inserido não está cadastrado no sistema.</div>';
                return false;
            }
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">O NIF do Cliente inserido não existe. Por favor, cadastre o cliente na recepção.</div>';
            return false;
        }
    }

    private function valEditOrcClienteEquipamento() {
        $resultC = $this->conn->prepare("SELECT * FROM dadosClienteEquipamento WHERE nif = '{$this->dados['clinete']}'");
        $resultC->execute();
        $resultM = $this->conn->prepare("SELECT * FROM dadosClienteEquipamento WHERE numero_serie = '{$this->dados['veiculo']}' LIMIT 1");
        $resultM->execute();

        $result = $this->conn->prepare("SELECT * FROM dadosClienteEquipamento WHERE nif = '{$this->dados['clinete']}' AND numero_serie = '{$this->dados['veiculo']}'");
        $result->execute();
        if ($resultC->rowCount()) {
            if ($resultM->rowCount()) {
                $resultBd = $resultM->fetch();
                if ($result->rowCount()) {
                    @$this->dados['idveiculo'] = $resultBd['idequipamento'];
                    $result = $this->conn->prepare("SELECT * FROM dadosorcamento WHERE nif = '{$this->dados['clinete']}' AND numero_serie = '{$this->dados['veiculo']}' AND idtipo_servico = '{$this->dados['id_tipo_servico']}' AND status = 'Aberto'");
                    $result->execute();
                    if ($result->rowCount()) {
                        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Já existe um Orçamento aberto para este equipamento.</div>';
                        return false;
                    } else {
                        return true;
                    }
                } else {
                    $_SESSION['msg'] = '<div class="alert alert-danger text-center">O NIF do Cliente não coincide com o proprietário do Nº de Série do Equipamento inserido.</div>';
                    return false;
                }
            } else {
                $_SESSION['msg'] = '<div class="alert alert-danger text-center">O Nº de Série do Equipamento inserido não está cadastrado no sistema.</div>';
                return false;
            }
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">O NIF do Cliente inserido não existe. Por favor, cadastre o cliente na recepção.</div>';
            return false;
        }
    }

    public function deletOrcamento($dados) {
        $this->dados = $dados;
        $result = $this->conn->prepare("SELECT produtos FROM orc_prod WHERE orcamentos=:idorcamentos");
        $result->bindParam(":idorcamentos", $this->dados['idorcamentos']);
        $result->execute();
        $produtos = $result->fetchAll(PDO::FETCH_ASSOC);
        //$produtos = $produtos['produtos'];
        //var_dump($produtos);
        //echo "<br>".count($produtos);
        for ($index = 0; $index < count($produtos); $index++) {
            $valorForm = $produtos[$index];
            //var_dump($produtos[$index]);
            foreach ($valorForm as $value) {
                //echo "<br>" . $value;
                $this->dados['idproduto'] = $value;
                $result = $this->conn->prepare("SELECT estoque FROM produto WHERE idproduto=:idproduto LIMIT 1");
                $result->bindParam(":idproduto", $this->dados['idproduto']);
                $result->execute();
                $estoque = $result->fetch();
                //echo "<br> Estoque <br>" . $estoque['estoque'];
                $this->dados['estoque'] = $estoque['estoque'] + 1;
                $result = $this->conn->prepare("UPDATE produto SET estoque=:estoque WHERE idproduto=:idproduto LIMIT 1");
                $result->bindParam(":idproduto", $this->dados['idproduto']);
                $result->bindParam(":estoque", $this->dados['estoque']);
                $result->execute();
                if ($result->rowCount()) {
                    (new \App\adms\Models\AdmsMovimentoEstoque())->registar((int)$this->dados['idproduto'], 'Entrada', 1, 'OrcamentoEliminado', $this->dados['idorcamentos'], $_SESSION['idlogado'] ?? null);
                }
            }
        }
        if ($result->rowCount() || empty($produtos)) {
            $result = $this->conn->prepare("DELETE FROM ocorrencias WHERE id_orcamento=:idorcamentos");
            $result->bindParam(":idorcamentos", $this->dados['idorcamentos']);
            $result->execute();

            $result = $this->conn->prepare("DELETE FROM orcamentos WHERE idorcamentos=:idorcamentos");
            $result->bindParam(":idorcamentos", $this->dados['idorcamentos']);
            $result->execute();
            if ($result->rowCount()) {
                $_SESSION['msg'] = '<div class="alert alert-success text-center"> Orçamento Deletado Com Sucesso</div>';
                return false;
            } else {
                $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Orçamento Deletado Sem Sucesso</div>';
                return false;
            }
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Orçamento Deletado Sem Sucesso Não Está Alterar O Valor do Estoque</div>';
            return false;
        }
    }

    public function addProdutoOrcamento($dados) {
        $this->dados = $dados;
        $this->dados['idproduto'] = $this->limparInput($this->dados['idproduto']);
        $this->dados['estoque'] = $this->limparInput($this->dados['estoque']);
        $this->dados['estoque_antigo'] = $this->limparInput($this->dados['estoque_antigo']);
        $this->dados['idorcamentos'] = $this->limparInput($this->dados['idorcamentos']);

        if ($this->valProdutoEstoqueAdd()) {
            $this->dados['estoque'] = $this->dados['estoque'] - 1;
            $result = $this->conn->prepare("SELECT * FROM orc_prod WHERE orcamentos=:orcamentos AND produtos=:produtos");
            $result->bindParam(":produtos", $this->dados['idproduto']);
            $result->bindParam(":orcamentos", $this->dados['idorcamentos']);
            $result->execute();
            if (($result->rowCount() == 0) || ($result->rowCount() == null) || ($result->rowCount() == "")) {
                $query = "INSERT INTO orc_prod SET  orcamentos=:orcamentos, produtos=:produtos, data=NOW()";
                $result = $this->conn->prepare($query);
                $result->bindParam(":produtos", $this->dados['idproduto']);
                $result->bindParam(":orcamentos", $this->dados['idorcamentos']);
                $result->execute();
                if ($result->rowCount()) {
                    $query = "UPDATE produto SET estoque=:estoque WHERE idproduto=:idproduto";
                    $result = $this->conn->prepare($query);
                    $result->bindParam(":idproduto", $this->dados['idproduto']);
                    $result->bindParam(":estoque", $this->dados['estoque']);
                    $result->execute();

                    if ($result->rowCount()) {
                        (new \App\adms\Models\AdmsMovimentoEstoque())->registar((int)$this->dados['idproduto'], 'Saida', 1, 'Orcamento', $this->dados['idorcamentos'], $_SESSION['idlogado'] ?? null);
                        $this->cdsContasReceber();
                    } else {
                        $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Produto Redução do Valor no Estoque Sem Sucesso!</div>';
                        return false;
                    }
                } else {
                    $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Produto Adicionado Sem Sucesso!</div>';
                    return false;
                }
            } else {
                $result = $this->conn->prepare("SELECT * FROM orc_prod WHERE orcamentos=:orcamentos AND produtos=:produtos LIMIT 1");
                $result->bindParam(":produtos", $this->dados['idproduto']);
                $result->bindParam(":orcamentos", $this->dados['idorcamentos']);
                $result->execute();
                $resultado = $result->fetch();
                $this->dados['idorc_prod'] = $resultado['idorc_prod'];
                $this->dados['quantidade'] = $resultado['quantidade'] + 1;
                //var_dump($this->dados);
                $query = "UPDATE orc_prod SET  quantidade=:quantidade, data=NOW() WHERE idorc_prod=:idorc_prod";
                $result = $this->conn->prepare($query);
                $result->bindParam(":quantidade", $this->dados['quantidade']);
                $result->bindParam(":idorc_prod", $this->dados['idorc_prod']);
                $result->execute();
                if ($result->rowCount()) {
                    $query = "UPDATE produto SET estoque=:estoque WHERE idproduto=:idproduto";
                    $result = $this->conn->prepare($query);
                    $result->bindParam(":idproduto", $this->dados['idproduto']);
                    $result->bindParam(":estoque", $this->dados['estoque']);
                    $result->execute();

                    if ($result->rowCount()) {
                        (new \App\adms\Models\AdmsMovimentoEstoque())->registar((int)$this->dados['idproduto'], 'Saida', 1, 'Orcamento', $this->dados['idorcamentos'], $_SESSION['idlogado'] ?? null);
                        $this->cdsContasReceber();
                    } else {
                        $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Produto Redução do Valor no Estoque Sem Sucesso!</div>';
                        return false;
                    }
                } else {
                    $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Produto Adicionado Sem Sucesso!</div>';
                    return false;
                }
            }
        }
    }

    //Conta A Receber Não Está Sendo Utilizada Essa Tabela
    private function cdsContasReceber() {
        if (isset($this->dados['cliente']) AND ! empty($this->dados['cliente'])) {
            $result = $this->conn->prepare("SELECT * FROM clientes WHERE nif = '{$this->dados['cliente']}'");
            $result->execute();
            if ($result->rowCount()) {
                $this->dados['tipo'] = "Venda de Produto";
                $this->dados['tecnico'] = $_SESSION['nif'];
                $this->dados['adiantameto'] = 00;


                $result = $this->conn->prepare("INSERT INTO conntas_areceber (idorcamentos, descricao, adiantameto, tecnico, cliente, data, valortotal) VALUES (:idorcamentos, :descricao, :adiantameto, :tecnico, :cliente, NOW(), :valortotal)");
                $result->bindParam(":idorcamentos", $this->dados['idorcamentos']);
                $result->bindParam(":descricao", $this->dados['tipo']);
                $result->bindParam(":tecnico", $this->dados['tecnico']);
                $result->bindParam(":cliente", $this->dados['cliente']);
                $result->bindParam(":adiantameto", $this->dados['adiantameto']);
                $result->bindParam(":valortotal", $this->dados['valor_venda']);
                $result->execute();
                //var_dump($this->dados);
                if ($result->rowCount()) {
                    $_SESSION['msg'] = '<div class="alert alert-success text-center"> Adicionado Produto Com Sucesso!</div>';
                    //$this->enviarEmail();
                    return true;
                } else {
                    $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Produto Redução do Valor no Estoque Com Sucesso Mas Não Criou Uma Conta Arecber!</div>';
                    return false;
                }
            } else {
                $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Produto Redução do Valor no Estoque Com Sucesso Mas Não Criou Uma Conta Arecber O Cliente Não Está Cadastrado!</div>';
                return false;
            }
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Produto Redução do Valor no Estoque Com Sucesso Mas Não Criou Uma Conta Arecber Porque o Nif Está Vasio O Não Existe(URL)!</div>';
            return false;
        }
    }

    private function valProdutoEstoqueAdd() {
        $result = $this->conn->prepare("SELECT * FROM produto WHERE idproduto='{$this->dados['idproduto']}' AND estoque >=1");
        $result->execute();
        if ($result->rowCount()) {
            return true;
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Esse Produto Está Sem Estoque!</div>';
            return false;
        }
    }

    public function dadosProdutoOrcamento($dados) {
        $this->dados = $dados;
        $query_dados = "SELECT * FROM orc_prod WHERE orcamentos='{$this->dados['idorcamentos']}'";
        $result_dados = $this->conn->prepare($query_dados);
        $result_dados->execute();
        $this->dados = $result_dados->fetchAll();
        return $this->dados;
    }

    public function deleteAddProduto($dados) {
        $this->dados = $dados;
        $result = $this->conn->prepare("SELECT produtos FROM orc_prod WHERE idorc_prod=:idorc_prod LIMIT 1");
        $result->bindParam(":idorc_prod", $this->dados['idorc_prod']);
        $result->execute();
        $produtos = $result->fetch();
        $this->dados['idproduto'] = $produtos['produtos'];

        //echo $this->dados['idproduto'];
        $result = $this->conn->prepare("SELECT estoque FROM produto WHERE idproduto=:idproduto LIMIT 1");
        $result->bindParam(":idproduto", $this->dados['idproduto']);
        $result->execute();
        $estoque = $result->fetch();
        //echo "<br> Estoque " . $estoque['estoque'];
        $this->dados['estoque'] = $estoque['estoque'] + 1;
        $result = $this->conn->prepare("UPDATE produto SET estoque=:estoque WHERE idproduto=:idproduto");
        $result->bindParam(":idproduto", $this->dados['idproduto']);
        $result->bindParam(":estoque", $this->dados['estoque']);
        $result->execute();

        if ($result->rowCount()) {
            (new \App\adms\Models\AdmsMovimentoEstoque())->registar((int)$this->dados['idproduto'], 'Entrada', 1, 'OrcamentoRemoverPeca', $this->dados['idorc_prod'], $_SESSION['idlogado'] ?? null);
            $result = $this->conn->prepare("DELETE FROM orc_prod WHERE idorc_prod=:idorc_prod");
            $result->bindParam(":idorc_prod", $this->dados['idorc_prod']);
            $result->execute();
            if ($result->rowCount()) {
                $_SESSION['msg'] = '<div class="alert alert-success text-center"> Produto Deletado Com Sucesso</div>';
                return true;
            } else {
                $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Produto Deletado Sem Sucesso</div>';
                return false;
            }
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Produto Deletado Sem Sucesso Não Está Alterar O Valor do Estoque</div>';
            return false;
        }
    }

    public function reduzirAddProduto($dados) {
        $this->dados = $dados;
        //var_dump($this->dados);
        if ($this->dados['quantidade'] > 1) {


            $result = $this->conn->prepare("SELECT produtos FROM orc_prod WHERE idorc_prod=:idorc_prod LIMIT 1");
            $result->bindParam(":idorc_prod", $this->dados['idorc_prod']);
            $result->execute();
            $produtos = $result->fetch();
            $this->dados['idproduto'] = $produtos['produtos'];

            //echo $this->dados['idproduto'];
            $result = $this->conn->prepare("SELECT estoque FROM produto WHERE idproduto=:idproduto LIMIT 1");
            $result->bindParam(":idproduto", $this->dados['idproduto']);
            $result->execute();
            $estoque = $result->fetch();
            //echo "<br> Estoque " . $estoque['estoque'];
            $this->dados['estoque'] = $estoque['estoque'] + 1;
            $result = $this->conn->prepare("UPDATE produto SET estoque=:estoque WHERE idproduto=:idproduto");
            $result->bindParam(":idproduto", $this->dados['idproduto']);
            $result->bindParam(":estoque", $this->dados['estoque']);
            $result->execute();

            if ($result->rowCount()) {
                (new \App\adms\Models\AdmsMovimentoEstoque())->registar((int)$this->dados['idproduto'], 'Entrada', 1, 'OrcamentoReduzirPeca', $this->dados['idorc_prod'], $_SESSION['idlogado'] ?? null);
                $this->dados['quantidade'] = $this->dados['quantidade'] - 1;
                $result = $this->conn->prepare("UPDATE orc_prod SET quantidade=:quantidade WHERE idorc_prod=:idorc_prod");
                $result->bindParam(":idorc_prod", $this->dados['idorc_prod']);
                $result->bindParam(":quantidade", $this->dados['quantidade']);
                $result->execute();
                if ($result->rowCount()) {
                    $_SESSION['msg'] = '<div class="alert alert-success text-center"> Produto Deletado Com Sucesso</div>';
                    return true;
                } else {
                    $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Produto Deletado Sem Sucesso</div>';
                    return false;
                }
            } else {
                $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Produto Deletado Sem Sucesso Não Está Alterar O Valor do Estoque</div>';
                return false;
            }
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center"> A Quantidade ' . $this->dados['quantidade'] . ' De Produtos É Inferior Ou Igual á 1, Tens que Apagar Não Reduzir</div>';
            return false;
        }
    }

    public function dadosOrcamentosCompleto() {
        $query_dados = "SELECT * FROM dadosorcamento order by idorcamentos desc";
        $result_dados = $this->conn->prepare($query_dados);
        $result_dados->execute();
        $this->dados = $result_dados->fetchAll();
        return $this->dados;
    }

    public function dadosOrcamentosCompletoId($dados) {
        $this->dados = $dados;
        $this->dados['idorcamentos'] = $this->limparInput($this->dados['idorcamentos']);
        $query_dados = "SELECT * FROM dadosorcamento WHERE idorcamentos = '{$this->dados['idorcamentos']}' LIMIT 1";
        $result_dados = $this->conn->prepare($query_dados);
        $result_dados->execute();
        $this->dados = $result_dados->fetch(PDO::FETCH_ASSOC);
        return $this->dados;
    }

    public function dadosOrcamentosCompletoAlter($dados) {
        $this->dados = $dados;
        $query_dados = "SELECT * FROM dadosOrcamentosCompletoComProdutos WHERE idorcamentos='{$this->dados['idorcamentos']}'";
        $result_dados = $this->conn->prepare($query_dados);
        $result_dados->execute();
        $this->dados = $result_dados->fetchAll();
        return $this->dados;
    }

    public function editOrcamento($dados) {
        @$this->dados = @$dados;
        @$this->dados['veiculo'] = $this->limparInput(@$this->dados['veiculo']);
        @$this->dados['id_tipo_servico'] = $this->limparInput(@$this->dados['id_tipo_servico']);
        @$this->dados['valor'] = $this->limparInput(@$this->dados['valor']);
        //$this->dados['data'] = date('"Y-m-d');
        @$this->dados['data_entrega'] = $this->limparInput(@$this->dados['data_entrega']);
        @$this->dados['garantia'] = $this->limparInput(@$this->dados['garantia']);
        @$this->dados['descricao'] = $this->limparInput(@$this->dados['descricao']);
        @$this->dados['obs'] = $this->limparInput(@$this->dados['obs']);
        @$this->dados['status'] = "Aberto";
        @$this->dados['tecnico'] = @$_SESSION['nif'];




        if ($this->valEditOrcClienteEquipamento()) {
            //var_dump($this->dados);
            $query = "UPDATE orcamentos SET veiculo=:veiculo, id_tipo_servico=:id_tipo_servico, valor=:valor, data=curDate(), data_entrega=:data_entrega, garantia=:garantia, tecnico=:tecnico, descricao=:descricao, obs=:obs, status=:status, modified=NOW()
                    WHERE idorcamentos=:idorcamentos";
            //$query = "INSERT INTO orcamentos (veiculo, id_tipo_servico, valor,  data, data_entrega, garantia, mecanico, descricao, obs, status, created) VALUES
            //         (:veiculo, :id_tipo_servico, :valor, curDate(), :data_entrega, :garantia, :mecanico, :descricao, :obs, :status, NOW())";
            $result = $this->conn->prepare($query);
            $result->bindParam(":veiculo", $this->dados['veiculo']);
            $result->bindParam(":idorcamentos", $this->dados['idorcamentos']);
            $result->bindParam(":id_tipo_servico", $this->dados['id_tipo_servico']);
            $result->bindParam(":valor", $this->dados['valor']);
            //$result->bindParam(":data", $this->dados['data']);
            $result->bindParam(":data_entrega", $this->dados['data_entrega']);
            $result->bindParam(":garantia", $this->dados['garantia']);
            $result->bindParam(":tecnico", $this->dados['tecnico']);
            $result->bindParam(":descricao", $this->dados['descricao']);
            $result->bindParam(":obs", $this->dados['obs']);
            $result->bindParam(":status", $this->dados['status']);
            $result->execute();
            if ($result->rowCount()) {
                $this->atualizarOcorrencia($this->dados, 'Aberta');
                $_SESSION['msg'] = '<div class="alert alert-success text-center"> Orçamento Editado Com Sucesso</div>';
                return false;
            } else {
                $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Orçamento Editado Sem Sucesso</div>';
                return false;
            }
        }
    }

    public function aprovarOrcamento($dados) {
        $this->dados = $dados;
        //$this->dados['status'] = "Aprovado";
        $this->dados['idorcamentos'] = $this->limparInput($this->dados['idorcamentos']);
        $this->dados['funcionario'] = $_SESSION['nif'];

        $result = $this->conn->prepare("UPDATE orcamentos SET status=:status,  data=curDate() WHERE idorcamentos=:idorcamentos");
        $result->bindParam(":idorcamentos", $this->dados['idorcamentos']);
        $result->bindParam(":status", $this->dados['status']);
        $result->execute();
        //var_dump($_SESSION);
        //var_dump($this->dados);
        if ($result->rowCount()) {
            $this->atualizarOcorrencia($this->dados, $this->dados['status']);
            if ($this->dados['status'] == "Concluído") {
                $this->dados['idorcamentos'] = $this->limparInput($this->dados['idorcamentos']);
                $this->dados = $dados;
                $this->dados['descricao'] = "Comissão";
                $this->dados['valor_maodeobra'] = $this->limparInput($this->dados['valor_maodeobra']);
                // Achar a ComssÕa inicial com  o desconto
                $this->dados['valor_comissaoi'] = $this->dados['valor_t_servico'] + $this->dados['valor_maodeobra'];
                $desconto = ($this->dados['valor_comissaoi'] * VALOR_DESCONTO);
                $idTipoServicoComissao = !empty($this->dados['id_tipo_servico']) ? (int)$this->dados['id_tipo_servico'] : null;
                $percentualComissao = (new \App\adms\Models\AdmsComissaoConfig())->obterPercentualEfetivo($_SESSION['idlogado'] ?? null, $idTipoServicoComissao);
                $this->dados['valor_comissao'] = ($this->dados['valor_comissaoi'] - $desconto) * $percentualComissao;
                //$this->dados['data_venci'] = $this->limparInput($this->dados['data_venci']);
                $this->dados['pago'] = "nao";

                $result = $this->conn->prepare("INSERT INTO contas_apagar (descricao, valor, funcionario, data_venci, pago, created) VALUES (:descricao, :valor, :funcionario, curDate(), :pago, NOW())");
                $result->bindParam(":descricao", $this->dados['descricao']);
                $result->bindParam(":valor", $this->dados['valor_maodeobra']);
                $result->bindParam(":funcionario", $_SESSION['nif']);
                $result->bindParam(":pago", $this->dados['pago']);
                $result->execute();

                $percentualAplicadoPct = round($percentualComissao * 100, 2);
                $result1 = $this->conn->prepare("INSERT INTO comissao (valor, percentual_aplicado, servico, tipo, data, niftecnico) VALUES (:valor, :percentual_aplicado, :servico, :tipo, curDate(), :funcionario)");
                $result1->bindParam(":servico", $this->dados['nome_servico']);
                $result1->bindParam(":valor", $this->dados['valor_comissao']);
                $result1->bindParam(":percentual_aplicado", $percentualAplicadoPct);
                $result1->bindParam(":tipo", $this->dados['tipo']);
                $result1->bindParam(":funcionario", $_SESSION['nif']);
                $result1->execute();


                //var_dump($this->dados);

                if ($result1->rowCount() && $result->rowCount()) {
                    $_SESSION['msg'] = '<div class="alert alert-success text-center"> Serviço Terminado Com Sucesso! Verifica a sua Comissão!</div>';
                    //$this->enviarEmail();
                    return true;
                } else {
                    $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Contas À Pagar Cadastrado Sem Sucesso!</div>';
                    return false;
                }
            }
            if ($this->dados['status'] == "Aprovado") {
                // Carregar dados completos do orçamento (numero_serie, nif do cliente, modelo, tipo_servico)
                $orcResult = $this->conn->prepare("SELECT numero_serie, matricula, nif, marca, modelo, tipo_servico FROM dadosorcamento WHERE idorcamentos = :idorcamentos LIMIT 1");
                $orcResult->bindParam(":idorcamentos", $this->dados['idorcamentos']);
                $orcResult->execute();
                $orcData = $orcResult->fetch(PDO::FETCH_ASSOC);
                if ($orcData) {
                    $this->dados['matricula']    = $orcData['numero_serie'] ?? $orcData['matricula'];
                    $this->dados['numero_serie'] = $orcData['numero_serie'] ?? $orcData['matricula'];
                    $this->dados['cliente']      = $orcData['nif'];
                    $this->dados['marca']        = $orcData['marca'];
                    $this->dados['modelo']       = $orcData['modelo'];
                    $this->dados['servico']      = $orcData['tipo_servico'];
                }

                $result = $this->conn->prepare("SELECT SUM(valor_venda) as valor_total_do_produto FROM dadosOrcamentosCompletoComProdutos WHERE idorcamentos = '{$this->dados['idorcamentos']}';");
                $result->execute();
                $result = $result->fetch();

                //$this->dados['valor_total_do_produto'] = $result['valor_total_do_produto'];
                if (isset($result['valor_total_do_produto']) && !empty($result['valor_total_do_produto'])) {
                    $this->dados['valor_total_do_produto'] = $result['valor_total_do_produto'];
                } else {
                    $this->dados['valor_total_do_produto'] = 0;
                }

                $this->dados['valortotali'] = $this->dados['valor_t_servico'] + $this->dados['valor_maodeobra'] + $this->dados['valor_total_do_produto'];
                $desconto = ($this->dados['valortotali'] * VALOR_DESCONTO);
                $this->dados['valortotalf'] = $this->dados['valortotali'] - $desconto;
                $this->dados['adiantameto'] = 00;
                //$this->dados['valortotal'] = $this->dados[''];
                $result2 = $this->conn->prepare("INSERT INTO conntas_areceber (idorcamentos, descricao, adiantameto, tecnico, cliente, data, valortotal) VALUES (:idorcamentos, :descricao, :adiantameto, :tecnico, :cliente, NOW(), :valortotal)");
                $result2->bindParam(":idorcamentos", $this->dados['idorcamentos']);
                $result2->bindParam(":descricao", $this->dados['tipo']);
                $result2->bindParam(":tecnico", $this->dados['funcionario']);
                $result2->bindParam(":cliente", $this->dados['cliente']);
                $result2->bindParam(":adiantameto", $this->dados['adiantameto']);
                $result2->bindParam(":valortotal", $this->dados['valortotalf']);
                $result2->execute();
                //var_dump($this->dados);

                if ($result2->rowCount()) {
                    $this->entradaEquipamento();
                    $_SESSION['msg'] = '<div class="alert alert-success text-center"> Orçamento Aprovado e Contas À REceber Cadastrado Com Sucesso!</div>';
                    //$this->enviarEmail();
                    return true;
                } else {
                    $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Orçamento Aprovado e Contas À Receber Cadastrado Sem Sucesso!</div>';
                    return false;
                }
            }
            //$_SESSION['msg'] = '<div class="alert alert-success text-center"> Orçamento Aprovado Com Sucesso</div>';
            //return false;
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Orçamento Aprovado Sem Sucesso!</div>';
            return false;
        }
    }

    private function valAprovaOrcamento() {
        $result_val = $this->conn->prepare("SELECT * FROM WHERE status = 'Aprovado'");
        $result_val->execute();
        if ($result_val->rowCount()) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center"> O Orçamento Já Foi Aprovado Aprovado Sem Sucesso!</div>';
            return false;
        }
    }

    public function dadosTipoServicoAlter() {
        $query_dadosServico = "SELECT * FROM tipo_servico WHERE valor >= 1";
        $result_dadosServico = $this->conn->prepare($query_dadosServico);
        $result_dadosServico->execute();
        $this->dados = $result_dadosServico->fetchAll();
        return $this->dados;
    }

    public function dadosServico() {
        $query_dados = "SELECT * FROM dadosorcamento WHERE tipo ='Serviço'";
        $result_dados = $this->conn->prepare($query_dados);
        $result_dados->execute();
        $this->dados = $result_dados->fetchAll();
        return $this->dados;
    }

    public function dadosOrcamentosTipo() {
        $query_dados = "SELECT * FROM dadosorcamento WHERE tipo ='Orçamento'";
        $result_dados = $this->conn->prepare($query_dados);
        $result_dados->execute();
        $this->dados = $result_dados->fetchAll();
        return $this->dados;
    }

    //Contas A receber E Comissão e Movimentação
    public function dadosComissao() {
        $query_dados = "SELECT * FROM comissao WHERE niftecnico ='{$_SESSION['nif']}'";
        $result_dados = $this->conn->prepare($query_dados);
        $result_dados->execute();
        $this->dados = $result_dados->fetchAll();
        return $this->dados;
    }

    private function entradaEquipamento() {
        $numeroSerie = $this->dados['matricula'] ?? $this->dados['numero_serie'] ?? '';
        $result = $this->conn->prepare("SELECT * FROM entrada_equipamento WHERE numero_serie=:numero_serie LIMIT 1");
        $result->bindParam(":numero_serie", $numeroSerie);
        $result->execute();
        if ($result->rowCount() < 1) {
            $marca      = $this->dados['marca']   ?? '';
            $modelo     = $this->dados['modelo']  ?? '';
            $niftecnico = $_SESSION['nif'];
            $result = $this->conn->prepare("INSERT INTO entrada_equipamento SET numero_serie=:numero_serie, marca=:marca, modelo=:modelo, cliente=:cliente, niftecnico=:niftecnico, servico=:servico, estado='Recebido', data_entrada=curDate()");
            $result->bindParam(":numero_serie", $numeroSerie);
            $result->bindParam(":marca",        $marca);
            $result->bindParam(":cliente",      $this->dados['cliente']);
            $result->bindParam(":niftecnico",   $niftecnico);
            $result->bindParam(":servico",      $this->dados['servico']);
            $result->bindParam(":modelo",       $modelo);
            $result->execute();
            if ($result->rowCount()) {
                $_SESSION['msg'] = '<div class="alert alert-success text-center"> Equipamento registado com Sucesso!</div>';
                return true;
            } else {
                $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Registo do Equipamento sem Sucesso!</div>';
                return false;
            }
        }
    }

    public function dadosEntradaEquipamento() {
        $query_dados = "SELECT e.*, c.nome AS cliente_nome, c.sobrenome AS cliente_sobrenome, u.nome AS tecnico_nome, u.sobrenome AS tecnico_sobrenome
                        FROM entrada_equipamento e
                        LEFT JOIN clientes c ON c.nif = e.cliente
                        LEFT JOIN usuario u ON u.nif = e.niftecnico
                        ORDER BY e.id DESC";
        $result_dados = $this->conn->prepare($query_dados);
        $result_dados->execute();
        $this->dados = $result_dados->fetchAll(PDO::FETCH_ASSOC);
        return $this->dados;
    }

    public function deletEntradaEquipamento($dados) {
        $this->dados = $dados;
        $id = $this->dados['identrada_veiculo'] ?? $this->dados['id'] ?? 0;
        $query_dados = "DELETE FROM entrada_equipamento WHERE id=:id";
        $result_dados = $this->conn->prepare($query_dados);
        $result_dados->bindParam(":id", $id);
        $result_dados->execute();
        if ($result_dados->rowCount()) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center"> Registo eliminado com Sucesso!</div>';
            return true;
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center"> Registo eliminado sem Sucesso!</div>';
            return false;
        }
    }

    public function ativarConta($dados) {
        $this->dados = $dados;
        $this->dados['nbi'] = $this->limparInput($this->dados['nbi']);
        $this->dados['nif'] = $this->limparInput($this->dados['nif']);
        $this->dados['email'] = $this->limparInput($this->dados['email']);
        $this->dados['st_conta'] = $this->limparInput($this->dados['st_conta']);
        //echo "<br> Conta <br>" . $this->dados['st_conta'];
        
        if ($this->dados['st_conta'] == "Ativada") {
            $this->dados['st_conta'] = "Desativada";
        } else {
            $this->dados['st_conta'] = "Ativada";
        }
        
        if (!empty($this->idUsuarioAlter())) {
            foreach ($this->idUsuarioAlter() as $value) {
                $this->dados['idusuario'] = $value;
            }
        } else {
            $this->dados['idusuario'] = 0;
        }


        if ($this->dados['usuario'] == "Recep") {
            $this->dados['senha'] = rand(10000, 999999999);
            $query_supervisor = "UPDATE usuario SET st_conta=:st_conta, senha=:senha, modified=NOW() WHERE idusuario=:idusuario";
            $resul = $this->conn->prepare($query_supervisor);
            $resul->bindParam(":idusuario", $this->dados['idusuario']);
            $resul->bindParam(":st_conta", $this->dados['st_conta']);
            $resul->bindParam(":senha", $this->dados['senha']);
            $resul->execute();

            if ($resul->rowCount()) {
                $_SESSION['msg'] = '
                <div class="alert alert-success text-center" role="alert">
                    Operação Feita Com Sucesso
                </div>
                ';
                return true;
            } else {
                $_SESSION['msg'] = '
                <div class="alert alert-danger text-center" role="alert">
                    Estado da Conta Alterado Sem Sucesso!
                </div>
                ';
                return false;
            }
        } elseif ($this->dados['usuario'] == "Mecanico") {
            $this->dados['senha'] = md5(rand(10000, 999999999));

            $query_supervisor = "UPDATE usuario SET st_conta=:st_conta, senha=:senha, modified=NOW() WHERE idusuario=:idusuario";
            $resul = $this->conn->prepare($query_supervisor);
            $resul->bindParam(":idusuario", $this->dados['idusuario']);
            $resul->bindParam(":st_conta", $this->dados['st_conta']);
            $resul->bindParam(":senha", $this->dados['senha']);
            $resul->execute();

            if ($resul->rowCount()) {
                $_SESSION['msg'] = '
                <div class="alert alert-success text-center" role="alert">
                    Operação Feita Com Sucesso
                </div>
                ';
                return true;
            } else {
                $_SESSION['msg'] = '
                <div class="alert alert-danger text-center" role="alert">
                    Estado da Conta Alterado Sem Sucesso!
                </div>
                ';
                return false;
            }
        }
    }

    public function idUsuarioAlter() {
        $query = "SELECT idusuario FROM usuario WHERE nbi like '{$this->dados['nbi']}' AND nif like '{$this->dados['nif']}' AND email like '{$this->dados['email']}' LIMIT 1";
        $result = $this->conn->prepare($query);
        $result->execute();
        $this->dados['idusuario'] = $result->fetch();
        return $this->dados['idusuario'];
    }

}


