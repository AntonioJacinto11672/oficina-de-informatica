<?php
if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}
if (isset($this->dados['form'])) {
    $valorForm = $this->dados['form'];
    //var_dump($valorForm);
}
if (isset($_SESSION['idlogado'])) {
    
}
?>
<!-- Begin Page Content -->

<div class="container-fluid">
    <?php
    if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
    ?>
    <!-- Page Heading -->
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Contas á Receber</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Descrição</th>
                            <th>Valor</th>
                            <th>Adiantamento</th>
                            <th>Técnico</th>
                            <th>Cliente</th>
                            <th>Data</th>
                            <th>Acção</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Descrição</th>
                            <th>Valor</th>
                            <th>Adiantamento</th>
                            <th>Técnico</th>
                            <th>Cliente</th>
                            <th>Data</th>
                            <th>Acção</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
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
                                        ?> 
                                        <?php echo $valorForm['descricao']; ?></td>
                                    <td><?php
                                        $valorForm['valorm'] = number_format($valorForm['valortotal'], 2, ',', '.');
                                        echo $valorForm['valorm'];
                                        ?> KZ
                                    </td>
                                    <td><?php
                                        $valorForm['valorm'] = number_format($valorForm['adiantameto'], 2, ',', '.');
                                        echo $valorForm['valorm'];
                                        ?> KZ
                                    </td>
                                    <td><?php
                                        $newConn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME, DBPORT);
                                        $query = "SELECT * FROM usuario WHERE nif='{$valorForm['tecnico']}' LIMIT 1";
                                        $result = mysqli_query($newConn, $query);
                                        $mecanico = mysqli_fetch_assoc($result);
                                        //var_dump($mecanico);
                                        @$mecanico = @$mecanico['nome'] . " " . @$mecanico['sobrenome'];
                                        ?>
                                        <?php echo @$mecanico; ?></td>
                                    <td><?php
                                        $newConn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME, DBPORT);
                                        $query = "SELECT * FROM clientes WHERE nif='{$valorForm['cliente']}' LIMIT 1";
                                        $result = mysqli_query($newConn, $query);
                                        $mecanico = mysqli_fetch_assoc($result);
                                        //var_dump($mecanico);
                                        @$mecanico = @$mecanico['nome'] . " " . @$mecanico['sobrenome'];
                                        ?>
                                        <?php echo @$mecanico; ?></td>
                                    <td><?php echo $valorForm['data']; ?></td>
                                    <td>   
                                        <a href="<?php echo URLADM . "relatorioTecnico?relatorio=orcamento&idorcamentos=" . $valorForm['idorcamentos']; ?>"  title="Imprimir Relatório"><i class="icofont icofont-file-text text-success px-1"></i></a>
                                        <?php if ($valorForm['pago'] === "nao") { ?>
                                            <a href="<?php echo $valorForm['idconntas_areceber']; ?>" data-toggle="modal" data-target="#edit<?php echo $valorForm['idconntas_areceber']; ?>" title="Editar"><i class="icofont icofont-edit text-primary px-1"></i></a>
                                            <a href="<?php echo $valorForm['idconntas_areceber']; ?>" data-toggle="modal" data-target="#pagar<?php echo $valorForm['idconntas_areceber']; ?>" title="Terminar Orçamento Pagar"><i class="icofont icofont-ui-check text-success px-2"></i></a>
                                        <?php } ?>
                                        <?php if ($_SESSION['usuario'] == "adimin") { ?>
                                            <a href="<?php echo $valorForm['idconntas_areceber']; ?>" data-toggle="modal" data-target="#delete<?php echo $valorForm['idconntas_areceber']; ?>" title="Pagar Já Não Vai se Pago"><i class="icofont icofont-trash text-danger px-1"></i></a>
                                        <?php } ?>

                                    </td>
                                </tr>       

                                <!-- /.modal-dialog -->

                                <!-- /.modal -->
                                <!--  Modal Deletar-->
                            <div class="modal fade" id="delete<?php echo $valorForm['idconntas_areceber']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                 aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Tens A Certeza Que Queres Apagar?</h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">Clica "Sim" Para Apagar Esse Conta?.</div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                            <form action="" method="post" class="needs-validation" enctype="multipart/form-data" novalidate>
                                                <input type="hidden" name="idconntas_areceber" value="<?php echo $valorForm['idconntas_areceber']; ?>">
                                                <input type="hidden" value="<?php echo $valorForm['adiantameto'] ?>" name="adiantameto_antigo" />
                                                <button class="btn btn-primary" name="btnDeleteConta">Sim</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Abrir Orçamento Modal-->
                            <div class="modal fade" id="edit<?php echo $valorForm['idconntas_areceber']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                 aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <form action="" method="post" class="needs-validation" enctype="multipart/form-data" novalidate>
                                            <div class="modal-header bg-dark text-white">
                                                <h5 class="modal-title" id="exampleModalLabel">Abrir Orcamento</h5>
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">

                                                </div>

                                                <div class="row">
                                                    <div class="col-md-8 mb-3">
                                                        <label for="adiantameto"> Pagar Um Adiantamento Do Serviço Ou Total Cobrado</label>
                                                        <input type="text" class="form-control" id="tel1" placeholder="Valor da Mão de Obra"  name="adiantameto" value="<?php
                                                        /* if (isset($valorForm['adiantameto'])) {
                                                          echo $valorForm['adiantameto'];
                                                          } */
                                                        ?>" required>
                                                        <div class="invalid-feedback">
                                                            Campo Obrigatório.
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class=" col-md-12">
                                                    <div class="card shadow bg-danger text-white">
                                                        <p><span class=""> Valor Adiantado:  </span><span><?php echo $valorForm['adiantameto']; ?> Kz</span></p>
                                                        <p><span>Valor Em Falta: </span><span><?php echo ($valorForm['valortotal'] - $valorForm['adiantameto']); ?> Kz</span></p>
                                                        <p><span> Total: </span><span class="text-success"><?php echo $valorForm['valortotal']; ?> Kz</span></p>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="modal-footer bg-danger">
                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                                <input type="hidden" value="<?php echo $valorForm['idconntas_areceber'] ?>" name="idconntas_areceber" />
                                                <input type="hidden" value="<?php echo $valorForm['adiantameto'] ?>" name="adiantameto_antigo" />
                                                <button class="btn btn-primary text-white" name="btnAdiantamento">Salvar</button>
                                            </div>
                                        </form> 
                                    </div>
                                </div>
                            </div>
                            <!-- Pagar Orçamento Modal-->
                            <div class="modal fade" id="pagar<?php echo $valorForm['idconntas_areceber']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                 aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content text-white">
                                        <form action="" method="post" class="needs-validation" enctype="multipart/form-data" novalidate>
                                            <div class="modal-header bg-secondary">
                                                <h5 class="modal-title" id="exampleModalLabel">Abrir Orcamento</h5>
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="text-gray-600"> Tens certeza que  O cliente já pagou esse serviço?</p>
                                            </div>
                                            <div class="modal-footer bg-success text-primary">
                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                                <input type="hidden" value="<?php echo $valorForm['idconntas_areceber'] ?>" name="idconntas_areceber"/>
                                                <input type="hidden" value="<?php echo $valorForm['adiantameto'] ?>" name="adiantameto_antigo" />
                                                <input type="hidden" value="<?php echo $valorForm['descricao'] ?>" name="descricao" />
                                                <button class="btn btn-info text-white " name="btnPagar">Sim  Aprovar</button>
                                            </div>
                                        </form> 
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->


