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
                                        <span class=" text-muted p-5">Relatório de Equipamentos</span>
                                        <small class="float-right">Data: <?php echo date("d/M/Y"); ?></small><br>
                                    </h4>
                                    <hr>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- info row -->

                            <!-- /.row -->

                            <!-- Table row -->
                            <div class="row p-5">
                                <div class="col-12 table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Nome</th>
                                                <th>Nº de Série</th>
                                                <th>Cliente</th>
                                                <th>Funcionario</th>
                                                <th>Data Entrada</th>
                                                <th>Serviço</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            </tr>
                                            <?php
                                            if (isset($this->dados)) {
                                                for ($index = 0; $index < count($this->dados); $index++) {
                                                    $valorForm = $this->dados[$index];
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $valorForm['modelo']; ?></td>
                                                        <td><?php echo $valorForm['matricula']; ?></td>
                                                        <td>
                                                            <?php
                                                            $newConn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
                                                            $query = "SELECT * FROM clientes WHERE nif='{$valorForm['cliente']}' LIMIT 1";
                                                            $result = mysqli_query($newConn, $query);
                                                            $mecanico = mysqli_fetch_assoc($result);
                                                            //var_dump($mecanico);
                                                            @$mecanico = @$mecanico['nome'] . " " . @$mecanico['sobrenome'];
                                                            ?>
                                                            <?php echo @$mecanico; ?></td>
                                                        </td>
                                                        <td><?php
                                                            $newConn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
                                                            $query = "SELECT * FROM usuario WHERE nif='{$valorForm['niftecnico']}' LIMIT 1";
                                                            $result = mysqli_query($newConn, $query);
                                                            $mecanico = mysqli_fetch_assoc($result);
                                                            //var_dump($mecanico);
                                                            @$mecanico = @$mecanico['nome'] . " " . @$mecanico['sobrenome'];
                                                            ?>
                                                            <?php echo @$mecanico; ?></td>
                                                        <td><?php $data = date("d/m/Y", strtotime($valorForm['data_entrada'])); echo $data; ?></td>
                                                        <td><?php echo $valorForm['servico']; ?></td>
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


                            <!-- this row will not appear when printing -->
                            <div class="row no-print">
                                <div class="col-12">
                                    <a href="<?php echo URLADM;?>relatorio?value=rveiculoimprimir" rel="noopener" target="_blank" class="btn btn-default border"><i class="fas fa-print"></i> Print</a>
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


