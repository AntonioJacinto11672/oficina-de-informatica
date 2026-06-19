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
<!-- Abrir Orçamento Modal-->
<div class="modal fade" id="servico" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" method="post" class="needs-validation" enctype="multipart/form-data" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Abrir Orcamento</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="Cliente">Nif do Cliente </label>
                            <input type="text" class="form-control" id="tel1" placeholder="Digete o Nif  do Cliente"  name="clinete" value="<?php
                            if (isset($valorForm['clinete'])) {
                                echo $valorForm['clinete'];
                            }
                            ?>" required>
                            <div class="invalid-feedback">
                                Insira O Nome.
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="veiculo">Nº de Série do Equipamento</label>
                            <input type="text" class="form-control" id="referencia" placeholder="Degite A Matricula do Equipamento" name="veiculo" value="<?php
                            if (isset($valorForm['veiculo'])) {
                                echo $valorForm['veiculo'];
                            }
                            ?>" required>
                            <div class="invalid-feedback">
                                Campo Obrigatório.
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="servico">Serviço</label>
                            <select class="custom-select" id="servico" name="id_tipo_servico" required>
                                <option selected disabled value="">Escolhe...</option>
                                <?php
                                if (isset($this->dadosAlter)) {
                                    foreach ($this->dadosAlter as $valor) {
                                        ?>
                                        <option value="<?php echo $valor['idtipo_servico']; ?>"><?php echo $valor['nome']; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!--<div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="descricao">Descrição</label>
                            <textarea class="form-control" id="descricao" name="descricao" placeholder="Escreva A discrição da Peça" required></textarea>
                            <div class="invalid-feedback">
                                Campo Obrigatório.
                            </div>
                        </div>
                    </div> -->

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="data_entrega">Data da Entrega <span class="text-muted"></span></label>
                            <input type="date" class="form-control" id="data_entrada" name="data_entrega" value="<?php
                            if (isset($valorForm['data_entrada'])) {
                                echo $valorForm['data_entrada'];
                            }
                            ?>" required>
                            <div class="invalid-feedback">
                                Campo Obrigatório.
                            </div>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label for="garantia">Garantia</label>
                            <input type="number" class="form-control" id="tel1" placeholder=""  min="0" name="garantia" value="<?php
                            if (isset($valorForm['garantia'])) {
                                echo $valorForm['garantia'];
                            }
                            ?>" required>
                            <div class="invalid-feedback">
                                Campo Obrigatório.
                            </div>
                        </div>
                        <!--<div class="col-md-4 mb-3">
                            <label for="valor">Valor (Mão de Obra)</label>
                            <input type="text" class="form-control" id="tel1" placeholder="Valor da Mão de Obra"  name="valor" value="<?php
                        //if (isset($valorForm['valor'])) {
                        //echo $valorForm['valor'];
                        //}
                        ?>" required>
                            <div class="invalid-feedback">
                                Campo Obrigatório.
                            </div>
                        </div>-->
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="obs">Observação do Equipamento</label>
                            <textarea class="form-control" id="obs" name="obs" placeholder="Escreva A discrição da Peça"></textarea>
                            <div class="invalid-feedback">
                                Campo Obrigatório.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary text-white" name="btnServico">Salvar</button>
                </div>
            </form> 
        </div>
    </div>
</div>
<div class="container-fluid">
    <?php
    if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
    ?>
    <!-- Page Heading -->
    <div class="row mt-4 mb-4">
        <a type="button" class="btn-primary btn-sm ml-3 d-none d-md-block text-white" href="" data-toggle="modal" data-target="#servico"> Novo Serviço</a>
        <!--<a type="button" class="btn-primary btn-sm ml-3 d-block d-lg-none"> + </a>-->
    </div>

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
                                <?php if (!(($valorForm['status'] == "Aberto") AND ( $valorForm['tipo'] == "Orçamento"))) { ?>
                                    <tr>
                                        <td>
                                            <?php if ($valorForm['status'] === "Aberto") { ?>
                                                <i class="fa fa-square text-danger px-1"></i>
                                            <?php } elseif ($valorForm['status'] === "Aprovado") { ?>
                                                <i class="fa fa-square text-primary px-1"></i>
                                            <?php } elseif ($valorForm['status'] === "Fechado") {
                                                ?>
                                                <i class = "fa fa-square text-success px-1"></i>
                                            <?php }
                                            ?>
                                            <?php echo $valorForm['nome_cliente'] . " " . $valorForm['sobrenome']; ?></td>
                                        <td><?php echo $valorForm['marca']; ?></td>
                                        <td><?php
                                            $valorForm['valorm'] = number_format($valorForm['valor'], 2, ',', '.');
                                            echo $valorForm['valorm'];
                                            ?> KZ
                                        </td>
                                        <td><?php echo $valorForm['tipo_servico']; ?></td>
                                        <td><?php echo date("d/m/Y", strtotime($valorForm['data_orcamento'])); ?></td>
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
                                            <a href="<?php echo $valorForm['idorcamentos']; ?>" data-toggle="modal" data-target="#enviar<?php echo $valorForm['idorcamentos']; ?>" title="Imprimir Relatório"><i class="icofont icofont-file-text text-success px-1"></i></a>
                                        </td>
                                    </tr>
                                <?php } ?>
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
                                                <button class="btn btn-primary" name="btnDeletServico">Sim</button>
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
                                                <h5 class="modal-title" id="exampleModalLabel">Abrir Orcamento</h5>
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="text-gray-600"> Tens Certeza Que Queres Termiar  Esse Serviço??</p>
                                            </div>
                                            <div class="modal-footer bg-success text-primary">
                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                                <input type="hidden" value="<?php echo $valorForm['idorcamentos'] ?>" name="idorcamentos"/>
                                                <input type="hidden" name="valor_maodeobra" value="<?php echo $valorForm['valor']; ?>">
                                                <input type="hidden" name="valor_t_servico" value="<?php echo $valorForm['valor']; ?>">
                                                <input type="hidden" name="nome_servico" value="<?php echo $valorForm['tipo_servico']; ?>">
                                                <input type="hidden" name="tipo" value="<?php echo $valorForm['tipo']; ?>">
                                                <button class="btn btn-info text-white " name="btnAprovar">Sim Termina</button>
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
                                            <div class="modal-footer bg-success">
                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                                <input type="hidden" value="<?php echo $valorForm['idorcamentos'] ?>" name="idorcamentos"/>
                                                <button class="btn btn-info text-white " name="btnEnviar">Aprovar</button>
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


