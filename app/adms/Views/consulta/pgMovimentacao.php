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
            <h6 class="m-0 font-weight-bold text-primary">MovimentaÃ§Ãµes</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>DescriÃ§Ã£o</th>
                            <th>Valor</th>
                            <th>FuncionÃ¡rio</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Tipo</th>
                            <th>DescriÃ§Ã£o</th>
                            <th>Valor</th>
                            <th>FuncionÃ¡rio</th>
                            <th>Data</th>
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
                                        <?php
                                        if ($valorForm['tipo'] === "Entrada") {
                                            echo '<i class="fa fa-square text-success px-1"></i>';
                                        } else {
                                            echo '<i class="fa fa-square text-danger px-1"></i>';
                                        }
                                        ?><?php echo $valorForm['tipo']; ?></td>
                                    <td><?php echo $valorForm['descricao']; ?></td>
                                    <td><?php
                                        $valorForm['valor_novo'] = number_format($valorForm['valor'], 2, ',', '.');
                                        echo $valorForm['valor_novo'];
                                        ?> KZ
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
                                </tr>
                            <?php
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <div class="mb-4 mt-5">
                <p>
                    <span class="p-4">Entrada Dia: <span class="text-success"><?php $this->dadosAlter['entrada_dia'] = number_format($this->dadosAlter['entrada_dia'], 2, ',', '.'); echo $this->dadosAlter['entrada_dia'];?> Kz</span> </span><span class="">Saida Dia <span class="text-danger"><?php $this->dadosAlter['saida_dia'] = number_format($this->dadosAlter['saida_dia'], 2, ',', '.'); echo $this->dadosAlter['saida_dia'];?> kz</span></span> <span class="float-right">Saldo Dia: <span class="text-danger"><?php $this->dadosAlter['total'] = number_format($this->dadosAlter['total'], 2, ',', '.'); echo $this->dadosAlter['total'];?> Kz</span> </span>
                </p>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
