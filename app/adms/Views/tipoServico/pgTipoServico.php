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


<!-- Logout Modal-->
<div class="modal fade" id="novoCategria" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" method="post" class="needs-validation" enctype="multipart/form-data" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cadastrar Tipo Servico</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="nome">Nome</label>
                            <input type="text" class="form-control" id="tel1" placeholder="Digete o Nome"  name="nome" value="<?php
                            if (isset($valorForm['nome'])) {
                                echo $valorForm['nome'];
                            }
                            ?>" required>
                            <div class="invalid-feedback">
                                Insira O Nome.
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="valor">Valor</label>
                            <input type="text" class="form-control" id="tel1" placeholder="Digete o Valor"  name="valor" value="<?php
                            if (isset($valorForm['valor'])) {
                                echo $valorForm['valor'];
                            }
                            ?>" >
                            <div class="invalid-feedback">
                                Insira O Nome.
                            </div>
                        </div>
                    </div>




                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary text-white" name="btnCdsTipo_servico">Salvar</button>
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
        <a type="button" class="btn-primary btn-sm ml-3 d-none d-md-block text-white" href="" data-toggle="modal" data-target="#novoCategria"> Novo Tipo de Serviço</a>
        <!--<a type="button" class="btn-primary btn-sm ml-3 d-block d-lg-none"> + </a>-->
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tipo Serviço</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Valor</th>
                            <th>Acção</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Nome</th>
                            <th>Valor</th>
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
                                    <td><?php echo $valorForm['nome']; ?></td>
                                    <td><?php $valorForm['valor_novo'] = number_format($valorForm['valor'], 2); echo $valorForm['valor_novo']; ?>KZ</td>
                                    <td>
                                        <a href="<?php echo $valorForm['idtipo_servico']; ?>" data-toggle="modal" data-target="#edit<?php echo $valorForm['idtipo_servico']; ?>"><i class="icofont icofont-edit px-2"></i></a>
                                        <a href="<?php echo $valorForm['idtipo_servico']; ?>" data-toggle="modal" data-target="#delete<?php echo $valorForm['idtipo_servico']; ?>"><i class="icofont icofont-trash text-danger"></i></a>
                                    </td>
                                </tr>
                                <!--  Modal Deletar-->
                            <div class="modal fade" id="delete<?php echo $valorForm['idtipo_servico']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                 aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Tens A Certeza Que Queres Apagar?</h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">Clica "Sim" Para Pagar Esse tipo_servico <?php echo $valorForm['nome']; ?>.</div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                            <form action="" method="post" class="needs-validation" enctype="multipart/form-data" novalidate>
                                                <input type="hidden" name="idtipo_servico" value="<?php echo $valorForm['idtipo_servico']; ?>">
                                                <button class="btn btn-primary" name="btnDelettipo_servico">Sim</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--  Modal Deletar-->
                            <div class="modal fade" id="edit<?php echo $valorForm['idtipo_servico']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                 aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form action="" method="post" class="needs-validation" enctype="multipart/form-data" novalidate>
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Tens A Certeza Que Queres Editar?</h5>
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-12 mb-3">
                                                        <label for="nome">Nome</label>
                                                        <input type="text" class="form-control" id="tel1" placeholder="Digete o Nome"  name="nome" value="<?php
                                                        if (isset($valorForm['nome'])) {
                                                            echo $valorForm['nome'];
                                                        }
                                                        ?>" required>
                                                        <div class="invalid-feedback">
                                                            Insira O Nome.
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <label for="valor">Valor</label>
                                                        <input type="text" class="form-control" id="tel1" placeholder="Digete o Valor"  name="valor" value="<?php
                                                        if (isset($valorForm['valor'])) {
                                                            echo $valorForm['valor'];
                                                        }
                                                        ?>" required>
                                                        <div class="invalid-feedback">
                                                            Insira O Nome.
                                                        </div>
                                                    </div>
                                                </div>  

                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                                <input type="hidden" name="idtipo_servico" value="<?php echo $valorForm['idtipo_servico']; ?>">
                                                <button class="btn btn-primary" name="btnEdittipo_servico">Sim</button>
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
