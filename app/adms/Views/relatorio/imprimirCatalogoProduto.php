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
                                                <th>Referencia</th>
                                                <th>Categoria</th>
                                                <th>Fornecedor</th>
                                                <th>Valor Compra </th>
                                                <th>Valor Venda</th>
                                                <th>Estoque</th>
                                                <th>Imagem</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (isset($this->dados)) {
                                                for ($index = 0; $index < count($this->dados); $index++) {
                                                    $valorForm = $this->dados[$index];
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $valorForm['nome']; ?></td>
                                                        <td><?php echo $valorForm['referencia']; ?></td>
                                                        <td><?php echo $valorForm['categoria']; ?></td>
                                                        <td><a data-toggle="modal" data-target="#fornecedor<?php echo $valorForm['idproduto']; ?>" href=""> <?php echo $valorForm['fornecedor']; ?></a></td>
                                                        <td><?php
                                                            $valorForm['valor_compra_novo'] = number_format($valorForm['valor_compra'], 2, ',', '.');
                                                            echo $valorForm['valor_compra_novo'];
                                                            ?> KZ</td>
                                                        <td><?php
                                                            $valorForm['valor_venda_novo'] = number_format($valorForm['valor_venda'], 2, '.', '.');
                                                            echo $valorForm['valor_venda_novo'];
                                                            ?> KZ</td>

                                                        <td class="<?php
                                                        if ($valorForm['estoque'] < NIVEL_STOQUE) {
                                                            echo "text-danger";
                                                        }
                                                        ?>"><?php echo $valorForm['estoque']; ?></td>
                                                        <td class="td-double"> 
                                                            <img src="<?php echo URLADM . "app/adms/assets/foto/" . $valorForm['foto']; ?>" width="85" height="75" alt="center" class="rounded mx-auto d-block"/>
                                                        </td>
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
<script>
    window.addEventListener("load", window.print());
</script>

