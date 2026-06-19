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
<div class="container-fluid">
    <?php
    if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
    ?>
    <!--  Modal Fazer Pedido Do Fornecedor-->
    <div class="modal fade" id="produto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="" method="post" class="needs-validation" enctype="multipart/form-data" novalidate>
                <div class="modal-content text-light">
                    <div class="modal-header bg-dark">
                        <h5 class="modal-title" id="exampleModalLabel">Ver Produtos</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body text-muted">
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Peça / Produto</th>
                                            <th>Valor Cada</th>
                                            <th>Quantidade</th>
                                            <th>Acção</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $total = 0;
                                        if (isset($this->dadosAlter)) {
                                            for ($index = 0; $index < count($this->dadosAlter); $index++) {
                                                $valorForm = $this->dadosAlter[$index];
                                                ?>
                                                <tr>
                                                    <td><?php echo $valorForm['nome_produto']; ?></td>
                                                    <td><?php
                                                        $total = $total + ($valorForm['valor_venda'] * $valorForm['quantidade']);
                                                        $valorForm['valor_vendam'] = number_format($valorForm['valor_venda'], 2, ",", ".");
                                                        echo $valorForm['valor_vendam'];
                                                        ?>KZ
                                                    </td>
                                                    <td><?php echo $valorForm['quantidade']; ?></td>
                                                    <td>
                                                        <a class="" href="<?php echo URLADM . "addProdutoOrcamento?idorc_prod=" . $valorForm['idorc_prod'] . "&&idorcamentos=" . $valorForm['idorcamentos']; ?>"><i class="icofont icofont-trash text-danger p-1"></i></a>
                                                        <a class="" href="<?php echo URLADM . "addProdutoOrcamento?idorc_prod_reduzir=" . $valorForm['idorc_prod'] . "&&idorcamentos=" . $valorForm['quantidade']. "&&quantidade=" . $valorForm['quantidade']; ?>" title="Reduzir Produto"><i class="icofont icofont-minus-circle text-warning"></i></a>
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

                    </div>
                    <div class="modal-footer bg-danger">
                        <p>
                            <span class="p-3">
                                Total Produtos: <?php
                                $total = number_format($total, 2, ",", ".");
                                echo $total;
                                ?>Kz
                            </span>
                            <span>

                            </span>
                        </p>
                    </div>
                </div>
            </form>

        </div>
    </div>
    <div class="row mt-4 mb-4">
        <a type="button" class="btn-primary btn-sm ml-3  d-md-block text-white" href="" data-toggle="modal" data-target="#produto"> Produtos Do Equipamento</a>
        <!--<a type="button" class="btn-primary btn-sm ml-3 d-block d-lg-none"> + </a>-->
    </div>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Adicionar Produtos</h6>
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
                                    <td>
                                        <a data-toggle="modal" data-target="#mais<?php echo $valorForm['idproduto']; ?> " title="Adicionar Produto"><i class="icofont icofont-ui-check text-success px-2"></i></a>
                                    </td>
                                </tr>

                                <!--  Modal Fazer Pedido Do Fornecedor-->
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
                                                Tens A Certeza QUe queres Adicionar essse Produto Para Esse Veiculo?
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                                <input type="hidden" name="idproduto" value="<?php echo $valorForm['idproduto']; ?>">
                                                <input type="hidden" name="estoque_antigo" value="<?php echo $valorForm['estoque']; ?>">
                                                <input type="hidden" name="estoque" value="<?php echo $valorForm['estoque']; ?>">
                                                <input type="hidden" name="valor_venda" value="<?php echo $valorForm['valor_venda']; ?>">
                                                <input type="hidden" name="nome" value="<?php echo $valorForm['nome']; ?>">
                                                <button class="btn btn-primary" name="btnAddProduto">Sim</button>
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

