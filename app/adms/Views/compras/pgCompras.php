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
            <h6 class="m-0 font-weight-bold text-primary">Compras</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Valor Compra</th>
                            <th>Quantidade</th>
                            <th>Total á Pagar</th>
                            <th>Funcionário</th>
                            <th>Data</th>
                            <th>Acção</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Produto</th>
                            <th>Valor Compra</th>
                            <th>Quantidade</th>
                            <th>Total á Pagar</th>
                            <th>Funcionário</th>
                            <th>Data</th>
                            <th>Acção</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        </tr>
                        <?php
                        if (isset($this->dados)) {
                            for ($index = 0; $index < count($this->dados); $index++) {
                                $valorForm = $this->dados[$index];
                                ?>
                                <tr>
                                    <td><?php echo $valorForm['produto']; ?></td>
                                    <td><?php
                                        $valorForm['valor_novo'] = number_format($valorForm['valor'], 2,",",".");
                                        echo $valorForm['valor_novo'];
                                        ?>KZ</td>
                                    <td><?php echo $valorForm['quantidade_estoque']; ?></td>

                                    <td>
                                        <?php
                                        $total = $valorForm['valor'] * $valorForm['quantidade_estoque']; 
                                        $total = number_format($total, 2, ",", ".");
                                        echo $total. " Kz";
                                        ?>
                                    </td>
                                    <td><?php
                                $newConn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME, DBPORT);
                                $query = "SELECT * FROM usuario WHERE nif='{$valorForm['funcionario']}' LIMIT 1";
                                $result = mysqli_query($newConn, $query);
                                $mecanico = mysqli_fetch_assoc($result);
                                //var_dump($mecanico);
                                @$mecanico = @$mecanico['nome'] . " " . @$mecanico['sobrenome'];
                                        ?>
                                        <?php echo @$mecanico; ?></td>
                                    <td><?php echo date("d/m/Y", strtotime($valorForm['data'])); ?></td>
                                    <td>
                                        <a href="<?php echo $valorForm['idcompras']; ?>" data-toggle="modal" data-target="#delete<?php echo $valorForm['idcompras']; ?>"><i class="icofont icofont-trash text-danger"></i></a>
                                    </td>
                                </tr>
                                <!--  Modal Deletar-->
                            <div class="modal fade" id="delete<?php echo $valorForm['idcompras']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                 aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Tens A Certeza Que Queres Apagar?</h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">Clica "Sim" Para Pagar Esse compras <?php echo $valorForm['produto']; ?>.</div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                            <form action="" method="post" class="needs-validation" enctype="multipart/form-data" novalidate>
                                                <input type="hidden" name="idcompras" value="<?php echo $valorForm['idcompras']; ?>">
                                                <button class="btn btn-primary" name="btndeletCompras">Sim</button>
                                            </form>
                                        </div>
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
