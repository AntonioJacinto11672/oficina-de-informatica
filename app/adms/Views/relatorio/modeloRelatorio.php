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
                                        <span class=" text-muted">Nome Relatório</span>
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
                                        Nome: António José Jacinto<br>
                                        Nº BI: 00012323434A234<br>
                                        NIF: 00012323434A234<br>
                                        Email: info@almasaeedstudio.com
                                    </address>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 invoice-col">
                                    <address>
                                        <strong></strong><br>
                                        Morada<br>
                                        Telefone: (+244) 930 933 998<br>
                                        Email: info@almasaeedstudio.com
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
                                        Nº de Série: LD-34-34-VC<br>
                                        Marca: Yhundai<br>
                                        Modelo: Yhunday<br>
                                        Cor: Amarelo
                                    </address>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 invoice-col">
                                    <address>
                                        <strong></strong><br>
                                        Morada<br>
                                        Telefone: (+244) 930 933 998<br>
                                        Email: info@almasaeedstudio.com
                                    </address>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 invoice-col">

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
                                                <th>Serviço</th>
                                                <th>Mão de Obra</th>
                                                <th>Produtos / Peças</th>
                                                <th>TOtal Serviço</th>
                                                <th>Data</th>
                                                <th>Garantia</th>
                                                <th>Data Entrega</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Troca de Óleo</td>
                                                <td>9000 Kz</td>
                                                <td>130,0 Kz</td>
                                                <td>130,0 Kz</td>
                                                <td><?php echo date("d/M/Y"); ?></td>
                                                <td>3 Dias</td>
                                                <td><?php echo date("d/M/Y"); ?></td>

                                            </tr>

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
                                    <p class="lead">Valor do Orçamento de <?php echo date("d/M/d"); ?></p>

                                    <div class="table-responsive">
                                        <table class="table">
                                            <tr>
                                                <th style="width:50%">SubTotal:</th>
                                                <td>$250.30</td>
                                            </tr>
                                            <tr>
                                                <th>Desconto (<?php echo (VALOR_DESCONTO * 100); ?>%)</th>
                                                <td>$10.34</td>
                                            </tr>
                                            <tr>
                                                <th>Total do Produto:</th>
                                                <td>$5.80</td>
                                            </tr>
                                            <tr>
                                                <th>Total:</th>
                                                <td>$265.24</td>
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
                                    <a href="invoice-print.html" rel="noopener" target="_blank" class="btn btn-default border"><i class="fas fa-print"></i> Print</a>
                                    <button type="button" class="btn btn-success float-right"><i class="far fa-credit-card"></i> Submit
                                        Payment
                                    </button>
                                    <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
                                        <i class="fas fa-download"></i> Generate PDF
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

