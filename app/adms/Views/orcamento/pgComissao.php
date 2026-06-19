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
            <h6 class="m-0 font-weight-bold text-primary">Comissões</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Valor</th>
                            <th>Serviço</th>
                            <th>Tipo</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Valor</th>
                            <th>Serviço</th>
                            <th>Tipo</th>
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
                                    <td><?php
                                        $valorForm['valorm'] = number_format($valorForm['valor'], 2, ',', '.');
                                        echo $valorForm['valorm'];
                                        ?> KZ
                                    </td>
                                    <td><?php echo $valorForm['servico']; ?></td>
                                    <td><?php echo $valorForm['tipo']; ?></td>
                                    <td><?php echo date("d/m/Y", strtotime($valorForm['data'])); ?></td>
                                </tr>       
                                <!-- Modal do W3C-->
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
