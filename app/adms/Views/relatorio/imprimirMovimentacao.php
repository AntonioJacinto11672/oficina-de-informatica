<?php
if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}
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
                                        <span class=" text-muted p-5">Relatório de Movimentações</span>
                                        <small class="float-right">Data: <?php echo date("d/M/Y"); ?></small><br>
                                    </h4>
                                    <hr>
                                </div>
                                <div class="col-12">
                                    <p>
                                        <b> <span class="  p-5">Periodo da Apuração</span> </b>
                                        <small class=""><?php echo date("d/M/Y"); ?></small> até <small class=""><?php echo date("d/M/Y"); ?></small>
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
                                                <th>Tipo</th>
                                                <th>Descricão</th>
                                                <th>Valor</th>
                                                <th>Funcionario</th>
                                                <th>Data</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>Tipo</th>
                                                <th>Descricão</th>
                                                <th>Valor</th>
                                                <th>Funcionario</th>
                                                <th>Data</th>
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
                                <div class="col-4 p-2">
                                    <div class="table-responsive">
                                        <table class="table border table-striped">
                                            <tr>
                                                <th>Entradas:</th>
                                                <td>$265.24</td>
                                                <th>Saidas:</th>
                                                <td>$265.24</td>
                                            </tr>
                                            
                                        </table>
                                    </div>
                                </div>
                                <!-- /.col -->
                                <div class="col-2 p-2">
                                    <!--<p class="lead">Valor do Orçamento de <?php //echo date("d/M/d"); ?></p>-->

                                    <div class="table-responsive">
                                        <table class="table border table-striped">
                                            <tr>
                                                <th>Saldo:</th>
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
                                    <a href="" rel="noopener" target="_blank" class="btn btn-default border"><i class="fas fa-print"></i> Print</a>
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
<!-- ./wrapper -->
<!-- Page specific script -->
<script>
    window.addEventListener("load", window.print());
</script>
