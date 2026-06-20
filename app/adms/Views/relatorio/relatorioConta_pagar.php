<?php
if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: PÃ¡gina nÃ£o encontrada!");
}
//var_dump($this->dadosAlter);
?>
<div class="wrapper"> 
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->


        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <!-- Main content -->
                        <div class="invoice p-3 mb-3">
                            <!-- title row -->
                            <div class="row">
                                <div class="col-12">
                                    <h4 class="text-center">
                                        <img src="app/adms/assets/imagens/login/logo.png" width="100px" height="60px"/>
                                        <span class="text-muted"><?php echo NOME_OFICINA; ?></span>
                                    </h4>
                                    <div class="row justify-content-center">
                                        <small class="badge badge-primary text-wrap text-center " style="width: 24rem;font-family: 16px"><?php echo ENDERECO_OFICINA; ?><br><?php echo "Tel: " . TELEFONE . "  " . "Email:" . EMAIL_OFICINA; ?></small>
                                    </div>
                                    <hr>
                                </div>
                                <div class="col-12">
                                    <h4>
                                        <span class=" text-muted p-5">RelatÃ³rio de Contas Ã Pagar <?php
                                            if (isset($this->dadosAlter['status']) && $this->dadosAlter['status'] != "Todos") {
                                                echo $this->dadosAlter['status'];
                                            }
                                            ?></span>
                                        <small class="float-right">Data: <?php echo date("d/M/Y"); ?></small><br>
                                    </h4>
                                    <hr>
                                </div>
                                <div class="col-12">
                                    <p>
                                        <b> <span class="  p-5">Periodo da ApuraÃ§Ã£o</span> </b>
                                        <small class=""><?php if (isset($this->dadosAlter)) { ?><?php echo date("d/M/Y", strtotime($this->dadosAlter['data_inicio'])); ?></small> atÃ© <small class=""><?php
                                                echo date("d/M/Y", strtotime($this->dadosAlter['data_final']));
                                            }
                                            ?></small>
                                    </p>
                                    <hr>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- info row -->

                            <!-- /.row -->

                            <!-- Table row -->
                            <div class="row">
                                <div class="col-12 table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>DescriÃ§Ã£o</th>
                                                <th>Valor</th>
                                                <th>FuncionÃ¡rio</th>
                                                <th>Data Vencimento</th>
                                                <th>Pago</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $total = 0;
                                            if (isset($this->dados)) {
                                                for ($index = 0; $index < count($this->dados); $index++) {
                                                    $valorForm = $this->dados[$index];
                                                    ?>
                                                    <tr>
                                                        <td><?php
                                                            if ($valorForm['pago'] === "sim") {
                                                                echo '<i class="fa fa-square text-success px-1"></i>';
                                                            } else {
                                                                echo '<i class="fa fa-square text-danger px-1"></i>';
                                                            }
                                                            ?> <?php
                                                            echo $valorForm['descricao'];
                                                            ?></td>
                                                        <td><?php
                                                            $total = $total + $valorForm['valor'];
                                                            $valorForm['valor_novo'] = number_format($valorForm['valor'], 2, ',', '.');
                                                            echo $valorForm['valor_novo'];
                                                            ?> KZ
                                                        </td>
                                                        <td><?php
                                                            $newConn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME, DBPORT);
                                                            $query = "SELECT * FROM usuario WHERE nif='{$valorForm['funcionario']}' LIMIT 1";
                                                            $result = mysqli_query($newConn, $query);
                                                            $mecanico = mysqli_fetch_assoc($result);
                                                            //var_dump($mecanico);
                                                            @$mecanico = @$mecanico['nome'] . " " . @$mecanico['sobrenome'];
                                                            ?>
                                                            <?php echo @$mecanico; ?></td>
                                                        <td><?php echo $valorForm['data_venci']; ?></td>
                                                        <td><?php if($valorForm['pago'] == "sim") {echo "SIM"; } else {echo "NÃƒO";}?></td>
                                                    </tr>
                                                    <!--  Modal Deletar-->
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->

                            <div class="row">
                                <!-- accepted payments column -->
                                <div class="col-8">

                                </div>
                                <!-- /.col -->
                                <div class="col-4">
                                    <!--<p class="lead">Valor do OrÃ§amento de <?php //echo date("d/M/d");       ?></p>-->

                                    <div class="table-responsive">
                                        <table class="table border table-striped">
                                            <tr>
                                                <th>Total de ServiÃ§o:</th>
                                                <td><?php echo number_format($total, 2, ",", "."); ?> Kz</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->

                            <!-- this row will not appear when printing -->
                            <div class="row no-print">
                                <div class="col-12">
                                    <a href="" rel="noopener" target="_blank" class="btn btn-default border" onclick=" window.addEventListener('load', window.print());"><i class="fas fa-print"></i> Print</a>
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
