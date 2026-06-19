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
            <h6 class="m-0 font-weight-bold text-primary">Produtos</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Referência</th>
                            <th>Categoria</th>
                            <th>Fornecedor</th>
                            <th>Valor Compra</th>
                            <th>Valor Venda</th>
                            <th>Estoque</th>
                            <th>Imagem</th>
                            <th>Acção</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Nome</th>
                            <th>Referência</th>
                            <th>Categoria</th>
                            <th>Fornecedor</th>
                            <th>Valor Compra</th>
                            <th>Valor Venda</th>
                            <th>Estoque</th>
                            <th>Imagem</th>
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
                                    <td><?php echo $valorForm['nome']; ?></td>
                                    <td><?php echo $valorForm['categoria']; ?></td>
                                    <td><a data-toggle="modal" data-target="#fornecedor<?php echo $valorForm['idproduto']; ?>" href=""> <?php echo $valorForm['fornecedor']; ?></a></td>
                                    <th><?php echo $valorForm['referencia']; ?></th>
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
                                        <a href="<?php echo URLADM . "editarFoto?id=" . $valorForm['idproduto']; ?>" class="btn btn-outline-warning  btn-sm edit">
                                            <div class="icon"><i class="icofont-edit text-uppercase w3-text-black">Editar</i></div>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="<?php echo $valorForm['idproduto']; ?>" data-toggle="modal" data-target="#mais<?php echo $valorForm['idproduto']; ?>" title="adicionar Estoque"><i class="icofont icofont-plus text-success px-2"></i></a>
                                    </td>
                                </tr>
                                <!--  Modal Deletar-->
                            <div class="modal fade" id="delete<?php echo $valorForm['idproduto']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                 aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Tens A Certeza Que Queres Apagar?</h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">Clica "Sim" Para Pagar Esse Produto <?php echo $valorForm['nome']; ?>.</div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                            <form action="" method="post" class="needs-validation" enctype="multipart/form-data" novalidate>
                                                <input type="hidden" name="idproduto" value="<?php echo $valorForm['idproduto']; ?>">
                                                <button class="btn btn-primary" name="btnDeletProduto">Sim</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--  Modal Dados Do Fornecedor-->
                            <div class="modal fade" id="mais<?php echo $valorForm['idproduto']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                 aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form action="" method="post" class="needs-validation" enctype="multipart/form-data" novalidate>
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Dados do Fornecedor</h5>
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="col-md-12 mb-3">
                                                    <label for="fornecedor">Fornecedor</label>
                                                    <select class="custom-select" id="fornecedor" name="fornecedor" required>
                                                        <option selected value="<?php echo $valorForm['idfornecedor']; ?>"><?php echo $valorForm['fornecedor']; ?></option>
                                                        <?php
                                                        if (isset($this->dadosAlter)) {
                                                            foreach ($this->dadosAlter as $valor) {
                                                                ?>
                                                                <option value="<?php echo $valor['idfornecedor']; ?>"><?php echo $valor['nome']; ?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>

                                                    </select>
                                                </div>

                                                <div class="col-md-12 mb-3">
                                                    <label for="valor_compra">Valor Compra</label>
                                                    <input type="text" class="form-control" id="tel1" placeholder="Valor da Compra"  name="valor_compra" value="<?php
                                                    if (isset($valorForm['valor_compra'])) {
                                                        //$valorForm['valor_compra'] = number_format($valorForm['valor_compra'], 2, '.');
                                                        echo $valorForm['valor_compra'];
                                                    }
                                                    ?>" required>
                                                    <div class="invalid-feedback">
                                                        Campo Obrigatório.
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label for="valor_venda">Valor Venda</label>
                                                    <input type="text" class="form-control" id="tel1" placeholder="Valor da Venda"  name="valor_venda" value="<?php
                                                    if (isset($valorForm['valor_venda'])) {
                                                        //$valorForm['valor_venda'] = number_format($valorForm['valor_venda'], 2, '.','.');
                                                        echo $valorForm['valor_venda'];
                                                    }
                                                    ?>" required>
                                                    <div class="invalid-feedback">
                                                        Campo Obrigatório.
                                                    </div>
                                                </div>

                                                <div class="col-md-12 mb-3">
                                                    <label for="estoque">Estoque <span class="text-muted"></span></label>
                                                    <input type="number" class="form-control" id="estoque" placeholder="Adicio Mais no Estoque" name="estoque" value="" required>
                                                    <div class="invalid-feedback">
                                                        Campo Obrigatório.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                                <input type="hidden" name="idproduto" value="<?php echo $valorForm['idproduto']; ?>">
                                                <input type="hidden" name="estoque_antigo" value="<?php echo $valorForm['estoque']; ?>">
                                                <input type="hidden" name="nome" value="<?php echo $valorForm['nome']; ?>">
                                                 <input type="hidden" name="foto" value="<?php echo $valorForm['foto']; ?>">
                                                <button class="btn btn-primary" name="btnaddEstoque">Sim</button>
                                            </div>
                                        </div>
                                    </form>

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
