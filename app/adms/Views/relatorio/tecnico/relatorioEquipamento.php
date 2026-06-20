<?php
if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}
//var_dump($this->dados);
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
                        <div class="invoice p-3 mb-3 p-5">
                            <!-- title row -->
                            <div class="row">
                                <div class="col-12">
                                    <h4 class="text-center">
                                        <img src="app/adms/assets/imagens/login/logo_novo.png" width="100px" height="60px"/>
                                        <span class="text-muted"><?php echo NOME_OFICINA; ?></span>
                                    </h4>
                                    <div class="row justify-content-center">
                                        <small class="badge badge-primary text-wrap text-center " style="width: 24rem;font-family: 16px"><?php echo ENDERECO_OFICINA; ?><br><?php echo "Tel: " . TELEFONE . "  " . "Email:" . EMAIL_OFICINA; ?></small>
                                    </div>
                                    <hr>
                                </div>
                                <?php
                                if (isset($this->dados)) {
                                    $value = $this->dados;
                                    ?>


                                <?php }
                                ?>
                                <div class="col-12">
                                    <h4>
                                        <span class=" text-muted">Orçamento Nº <?php echo $value['idorcamentos']; ?></span>
                                        <small class="float-right">Data: <?php echo date("d/M/Y"); ?></small><br>
                                    </h4>
                                    <hr>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- info row -->
                            <div class="row invoice-info">
                                <div class="col-sm-4 invoice-col">
                                    <address>
                                        <strong>Cliente.</strong><br>

                                        Nome: <?php echo $value['nome_cliente'] . " " . $value['sobrenome']; ?><br>
                                        Nº BI: <?php echo $value['nbi']; ?><br>
                                        NIF: <?php echo $value['nif']; ?><br>

                                    </address>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 invoice-col">
                                    <address>
                                        <strong></strong><br>
                                        <?php echo $value['morada']; ?><br>
                                        Telefone: (+244) <?php echo $value['telefone']; ?><br>
                                        Email: <?php echo $value['email']; ?>
                                    </address>
                                </div>

                                <!-- /.col -->

                                <div class="col-sm-4 invoice-col">

                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                            <hr>
                            <div class="row invoice-info">
                                <div class="col-sm-4 invoice-col">
                                    <address>
                                        <strong>Equipamento.</strong><br>
                                        Nº de Série: <?php echo $value['matricula']; ?><br>
                                        Marca: <?php echo $value['marca']; ?><br>
                                        Modelo: <?php echo $value['modelo']; ?><br>
                                        Cor: <?php echo $value['cor']; ?><br>
                                    </address>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 invoice-col">
                                    <address>
                                        <strong></strong><br>
                                    </address>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 invoice-col">
                                    <address>
                                        <strong></strong><br>
                                    </address>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                            <hr>
                            <div class="row invoice-info">
                                <div class="col-sm-4 invoice-col">
                                    <address>
                                        <strong>Laudo do Técnico.</strong><br>
                                        <b>Tipo Serviço:</b> <?php echo $value['tipo_servico']; ?><br>

                                    </address>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 invoice-col">
                                    <address>
                                        <strong></strong><br>
                                        <b> Garantia: </b><?php echo $value['garantia']; ?> dias
                                    </address>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 invoice-col">
                                    <address>
                                        <strong></strong><br>
                                        <b> Valor De Serviço: </b><?php echo number_format($value['valor'] ?? 0, 2, ",", "."); ?> Kz
                                    </address>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                            <!-- Table row -->
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
                                            <?php
                                            $totalproduto = 0;
                                            if (isset($this->dadosAlter)) {
                                                for ($index = 0; $index < count($this->dadosAlter); $index++) {
                                                    $valorForm = $this->dadosAlter[$index];
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $valorForm['produto']; ?></td>
                                                        <td><?php
                                                            $totalproduto = $totalproduto + ($valorForm['valor_venda'] * $valorForm['quantidade']);
                                                            $valorForm['valor_vendam'] = number_format($valorForm['valor_venda'], 2, ",", ".");
                                                            echo $valorForm['valor_vendam'];
                                                            ?>KZ
                                                        </td>
                                                        <td><?php echo $valorForm['quantidade']; ?></td>
                                                    </tr>
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
                                <div class="col-6">

                                </div>
                                <!-- /.col -->
                                <div class="col-6">
                                    <p class="lead">Valor do Orçamento </p>

                                    <div class="table-responsive">
                                        <table class="table">
                                            <tr>
                                                <th style="width:50%">Valor Peça / Produto:</th>
                                                <td><?php echo number_format($totalproduto, 2, ",", "."); ?> Kz</td>
                                            </tr>
                                            <tr>
                                                <th>Valor Mão de Obra</th>
                                                <td><?php echo number_format($value['valor'] ?? 0, 2, ",", ".") ?> Kz</td>
                                            </tr>
                                            <tr>
                                                <th>Desconto
                                                    <?php
                                                    $value['valortotali'] = ($value['valor'] ?? 0) + $totalproduto;
                                                    $desconto = ($value['valortotali'] * VALOR_DESCONTO);
                                                    $value['valortotalf'] = $value['valortotali'] - $desconto;
                                                    ;
                                                    ?>
                                                    (<?php echo (VALOR_DESCONTO * 100); ?>%)</th>
                                                <td><?php echo number_format($desconto, 2, ",", "."); ?> Kz</td>
                                            </tr>
                                            <tr>
                                                <th>Técnico:</th>
                                                <td><?php
                                                    $newConn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME, DBPORT);
                                                    $query = "SELECT * FROM usuario WHERE nif='{$value['tecnico']}' LIMIT 1";
                                                    $result = mysqli_query($newConn, $query);
                                                    $mecanico = mysqli_fetch_assoc($result);
                                                    //var_dump($mecanico);
                                                    $mecanico = $mecanico['nome'] . " " . $mecanico['sobrenome'];
                                                    ?>
                                                    <?php echo $mecanico; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Subtotal:</th>
                                                <td><?php echo number_format($value['valortotali'], 2, ",", "."); ?> Kz</td>
                                            </tr>
                                            <tr>
                                                <th>Total:</th>
                                                <td><?php echo number_format($value['valortotalf'], 2, ",", "."); ?> Kz</td>
                                            </tr>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="2"><b>Obs:</b> Data Prevista Para Entrega <?php echo date("d/m/Y", strtotime($value['data_entrega'])); ?> </td>
                                                </tr>
                                            </tfoot>
                                        </table>

                                    </div>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->

                            <!-- this row will not appear when printing -->
                            <div class="row no-print">
                                <div class="col-12">
                                    <button rel="noopener" target="_blank" class="btn btn-default border" onclick=" window.addEventListener('load', window.print());"><i class="fas fa-print"></i> Print</button>
                                    <a href="<?php echo URLADM;?>relatorio?value=pdf_teste&&idorcamentos=<?php echo $value['idorcamentos']; ?>" class="btn btn-primary float-right" style="margin-right: 5px;" >
                                        <i class="fas fa-download"></i> Gerar PDF
                                    </a>
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


