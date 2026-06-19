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
    //echo "<br>id Logado " . $_SESSION['idlogado'];
    //var_dump($_SESSION);
    //var_dump($this->dadosAlter);
    //echo "<br><br>";
    //var_dump($this->dadosPaginacao);
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
            <h6 class="m-0 font-weight-bold text-primary">Orçamentos</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Veiculo</th>
                            <th>Valor</th>
                            <th>Serviço</th>
                            <th>Data</th>
                            <th>Técnico</th>
                            <th>Acção</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Cliente</th>
                            <th>Veiculo</th>
                            <th>Valor</th>
                            <th>Serviço</th>
                            <th>Data</th>
                            <th>Técnico</th>
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
                                    <td>
                                        <?php if ($valorForm['status'] === "Aberto") { ?>
                                            <i class="fa fa-square text-danger px-1"></i>
                                        <?php } elseif ($valorForm['status'] === "Aprovado") { ?>
                                            <i class="fa fa-square text-primary px-1"></i>
                                            <?php
                                        } elseif ($valorForm['status'] === "Concluído") {
                                            echo '<i class="fa fa-square text-success px-1"></i>';
                                        }
                                        ?>
                                        <?php echo $valorForm['nome_cliente'] . " " . $valorForm['sobrenome']; ?></td>
                                    <td><?php echo $valorForm['marca']; ?></td>
                                    <td><?php
                                        $valorForm['valorm'] = number_format($valorForm['valor'], 2, ',', '.');
                                        echo $valorForm['valorm'];
                                        ?> KZ
                                    </td>
                                    <td><?php echo $valorForm['tipo_servico']; ?></td>
                                    <td><?php echo $valorForm['data_orcamento']; ?></td>
                                    <td><?php
                                        $newConn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
                                        $query = "SELECT * FROM usuario WHERE nif='{$valorForm['tecnico']}' LIMIT 1";
                                        $result = mysqli_query($newConn, $query);
                                        $mecanico = mysqli_fetch_assoc($result);
                                        //var_dump($mecanico);
                                        $mecanico = $mecanico['nome'] . " " . $mecanico['sobrenome'];
                                        ?>
                                        <?php echo $mecanico; ?></td>
                                    <td>
                                        <a href="<?php echo URLADM."relatorioTecnico?relatorio=orcamento&idorcamentos=".$valorForm['idorcamentos']; ?>"  title="Imprimir Relatório"><i class="icofont icofont-file-text text-success px-1"></i></a>
                                        <?php if ($valorForm['status'] === "Aberto") { ?>
                                            <a href="<?php echo $valorForm['idorcamentos']; ?>" data-toggle="modal" data-target="#delete<?php echo $valorForm['idorcamentos']; ?>" title="Apagar Registo"><i class="icofont icofont-trash text-danger px-1"></i></a>
                                            <a href="<?php echo URLADM . "addProdutoOrcamento?id=" . $valorForm['idorcamentos']; ?>" data-toggle="enviar" data-target="#aprovar<?php echo $valorForm['idorcamentos']; ?>" title="Enviar Email"><i class="icofont icofont-envelope text-success px-1"></i></a>
                                            <a href="<?php echo $valorForm['idorcamentos']; ?>" data-toggle="modal" data-target="#aprovar<?php echo $valorForm['idorcamentos']; ?>" title="Aprovar Orcamento"><i class="icofont icofont-ui-check text-success px-2"></i></a>
                                        <?php
                                        }
                                        if (!($valorForm['status'] == "Aprovado")) {
                                            ?>
                                        <?php } ?>

                                    </td>
                                </tr>
                                <!-- Modal do W3C-->

                                <!--  Modal Deletar-->
                            <div class="modal fade" id="delete<?php echo $valorForm['idorcamentos']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                 aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Tens A Certeza Que Queres Apagar?</h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">Clica "Sim" Para Apagar Esse Orçamento?.</div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                            <form action="" method="post" class="needs-validation" enctype="multipart/form-data" novalidate>
                                                <input type="hidden" name="idorcamentos" value="<?php echo $valorForm['idorcamentos']; ?>">
                                                <button class="btn btn-primary" name="btnDeletOrcamento">Sim</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Abrir Orçamento Modal-->
                            <div class="modal fade" id="aprovar<?php echo $valorForm['idorcamentos']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                 aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content text-white">
                                        <form action="" method="post" class="needs-validation" enctype="multipart/form-data" novalidate>
                                            <div class="modal-header bg-secondary">
                                                <h5 class="modal-title" id="exampleModalLabel">Aprovar Orcamento</h5>
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="text-gray-600"> Tens Certeza Que Queres Aprovar Esse Orçamento??</p>
                                            </div>
                                            <div class="modal-footer bg-success text-gray-600">
                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                                <input type="hidden" value="<?php echo $valorForm['idorcamentos']; ?>" name="idorcamentos"/>
                                                <input type="hidden" value="<?php echo $valorForm['matricula']; ?>" name="veiculo"/>
                                                <input type="hidden" value="<?php echo $valorForm['idtipo_servico']; ?>" name="id_tipo_servico"/>
                                                <input type="hidden" value="<?php echo $valorForm['valor']; ?>" name="valor_t_servico"/>
                                                <input type="hidden" value="<?php echo $valorForm['valor']; ?>" name="valor_maodeobra"/>
                                                <input type="hidden" value="<?php echo $valorForm['nif']; ?>" name="cliente"/>
                                                <input type="hidden" value="<?php echo $valorForm['tipo_servico']; ?>" name="servico"/>
                                                <input type="hidden" value="<?php echo $valorForm['matricula']; ?>" name="matricula"/>
                                                <input type="hidden" value="<?php echo $valorForm['modelo']; ?>" name="modelo"/>
                                                <input type="hidden" value="<?php echo $valorForm['modelo']; ?>" name="modelo"/>
                                                <input type="hidden" value="<?php echo $valorForm['tipo']; ?>" name="tipo"/>
                                                <button class="btn btn-info text-white " name="btnAprovar">Aprovar</button>
                                            </div>
                                        </form> 
                                    </div>
                                </div>
                            </div>
                            <!-- Abrir Orçamento Modal-->
                            <div class="modal fade" id="enviar<?php echo $valorForm['idorcamentos']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                                                <p class="text-gray-600"> Tens Certeza Que Queres Aprovar Esse Orçamento??</p>
                                            </div>
                                            <div class="modal-footer bg-info">
                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                                <input type="hidden" value="<?php echo $valorForm['idorcamentos'] ?>" name="idorcamentos"/>
                                                <button class="btn btn-success text-white " name="btnEnviar">Envar Relatório</button>
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


