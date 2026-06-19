<?php
if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: PÃ¡gina nÃ£o encontrada!");
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
            <h6 class="m-0 font-weight-bold text-primary">Vendas</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Valor</th>
                            <th>FuncionÃ¡rio</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Produto</th>
                            <th>Valor</th>
                            <th>FuncionÃ¡rio</th>
                            <th>Data</th>
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
                                        $valorForm['valor_novo'] = number_format($valorForm['valor_venda'], 2);
                                        echo $valorForm['valor_novo'];
                                        ?>KZ</td>
                                     <td><?php
                                        $newConn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
                                        $query = "SELECT * FROM usuario WHERE nif='{$valorForm['funcionario']}' LIMIT 1";
                                        $result = mysqli_query($newConn, $query);
                                        $funcionario = mysqli_fetch_assoc($result);
                                        //var_dump($funcionario);
                                        $funcionario = $funcionario['nome'] . " " . $funcionario['sobrenome'];
                                        ?>
                                        <?php echo $funcionario; ?></td>
                                    <td><?php echo $valorForm['data']; ?></td>
                                </tr>
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
