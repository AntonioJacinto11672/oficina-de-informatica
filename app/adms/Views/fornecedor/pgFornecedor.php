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
<div class="modal fade" id="novofornecedor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" method="post" class="needs-validation" enctype="multipart/form-data" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cadastrar Fornecedor</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
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
                        <!--<div class="col-md-6 mb-3">
                            <label for="sobrenome">Sobrenome</label>
                            <input type="text" class="form-control" id="sobrenome" placeholder="Inseri O Sobrenome" name="sobrenome" value="<?php
                        if (isset($valorForm['sobrenone'])) {
                            echo $valorForm['sobrenone'];
                        }
                        ?>" required>
                            <div class="invalid-feedback">
                                Campo Obrigatório.
                            </div>
                        </div>-->
                        <div class="form-group col-md-6">
                            <label for="tipo_pessoa">Tipo Pessoa</label>
                            <select id="tipo_pessoa" class="form-control" name="tipo_pessoa">
                                <option selected>Física</option>
                                <option>Jurídica</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nbi">Nº Do Bilhete</label>
                            <input type="text" class="form-control" id="tel1" placeholder="Digete O Número do Bilhete"  name="nbi" value="<?php
                            if (isset($valorForm['nbi'])) {
                                echo $valorForm['nbi'];
                            }
                            ?>" required>
                            <div class="invalid-feedback">
                                Campo Obrigatório.
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nif">Nº de Indetificação Fiscal (NIF)</label>
                            <input type="text" class="form-control" id="nif" placeholder="Nº de Indetificação Fiscal (NIF)" name="nif" value="<?php
                            if (isset($valorForm['nif'])) {
                                echo $valorForm['nif'];
                            }
                            ?>" required>
                            <div class="invalid-feedback">
                                Campo Obrigatório.
                            </div>
                        </div>
                    </div> 
                    <div class="mb-3">

                        <label for="email">Email <span class="text-muted"></span></label>
                        <input type="email" class="form-control" id="email" placeholder="you@example.com" name="email" value="<?php
                        if (isset($valorForm['email'])) {
                            echo $valorForm['email'];
                        }
                        ?>" required>
                        <div class="invalid-feedback">
                            Campo Obrigatório.
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="morada">Morada</label>
                            <input type="text" class="form-control" id="tel1" placeholder="Número do Morada"  name="morada" value="<?php
                            if (isset($valorForm['morada'])) {
                                echo $valorForm['morada'];
                            }
                            ?>" required>
                            <div class="invalid-feedback">
                                Campo Obrigatório.
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telefone">Nº de Telefone</label>
                            <input type="text" class="form-control" id="tel1" placeholder="Número do Telefone"  name="telefone" value="<?php
                            if (isset($valorForm['telefone'])) {
                                echo $valorForm['telefone'];
                            }
                            ?>" minlength="9" maxlength="9" min="0" required>
                            <div class="invalid-feedback">
                                Campo Obrigatório.
                            </div>
                        </div>

                    </div>  


                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary text-white" name="btnCdsFornecedor">Salvar</button>
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
        <a type="button" class="btn-primary btn-sm ml-3 d-none d-md-block text-white" href="" data-toggle="modal" data-target="#novofornecedor"> Novo Fornecedor</a>
        <!--<a type="button" class="btn-primary btn-sm ml-3 d-block d-lg-none"> + </a>-->
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Fornecedores</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Tipo Pessoa</th>
                            <th>Nº Bilhete</th>
                            <th>NIF</th>
                            <th>Telefone</th>
                            <th>Email</th>
                            <th>Acção</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Nome</th>
                            <th>Tipo Pessoa</th>
                            <th>NºBilhete</th>
                            <th>NIF</th>
                            <th>Telefone</th>
                            <th>Email</th>
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
                                    <td><?php echo $valorForm['nbi']; ?></td>
                                    <td><?php echo $valorForm['nbi']; ?></td>
                                    <td><?php echo $valorForm['nif']; ?></td>
                                    <td><?php echo $valorForm['telefone']; ?></td>
                                    <td><?php echo $valorForm['email']; ?></td>
                                    <td>
                                        <a href="<?php echo $valorForm['idfornecedor']; ?>" data-toggle="modal" data-target="#edit<?php echo $valorForm['idfornecedor']; ?>"><i class="icofont icofont-edit px-2"></i></a>
                                        <a href="<?php echo $valorForm['idfornecedor']; ?>" data-toggle="modal" data-target="#delete<?php echo $valorForm['idfornecedor']; ?>"><i class="icofont icofont-trash text-danger"></i></a>
                                    </td>
                                </tr>
                                <!--  Modal Deletar-->
                            <div class="modal fade" id="delete<?php echo $valorForm['idfornecedor']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                 aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Tens A Certeza Que Queres Apagar?</h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">Clica "Sim" Para Pagar Esse Fornecedor <?php echo $valorForm['nome']; ?>.</div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                            <form action="" method="post" class="needs-validation" enctype="multipart/form-data" novalidate>
                                                <input type="hidden" name="idfornecedor" value="<?php echo $valorForm['idfornecedor']; ?>">
                                                <input type="hidden" name="email" value="<?php echo $valorForm['email']; ?>">
                                                <button class="btn btn-primary" name="btnDeletFornecedor">Sim</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--  Modal Deletar-->
                            <div class="modal fade" id="edit<?php echo $valorForm['idfornecedor']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                 aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
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
                                                    <div class="col-md-6 mb-3">
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
                                                    <!--<div class="col-md-6 mb-3">
                                                        <label for="sobrenome">Sobrenome</label>
                                                        <input type="text" class="form-control" id="sobrenome" placeholder="Inseri O Sobrenome" name="sobrenome" value="<?php
                                                    if (isset($valorForm['sobrenome'])) {
                                                        // echo $valorForm['sobrenome'];
                                                    }
                                                    ?>" required>
                                                        <div class="invalid-feedback">
                                                            Campo Obrigatório.
                                                        </div>
                                                    </div>-->
                                                    <div class="form-group col-md-6">
                                                        <label for="tipo_pessoa">Tipo Pessoa</label>
                                                        <select id="tipo_pessoa" class="form-control" name="tipo_pessoa">
                                                            <option selected>Física</option>
                                                            <option>Jurídica</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="nbi">Nº Do Bilhete</label>
                                                        <input type="text" class="form-control" id="tel1" placeholder="Digete O Número do Bilhete"  name="nbi" value="<?php
                                                        if (isset($valorForm['nbi'])) {
                                                            echo $valorForm['nbi'];
                                                        }
                                                        ?>" required>
                                                        <div class="invalid-feedback">
                                                            Campo Obrigatório.
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="nif">Nº de Indetificação Fiscal (NIF)</label>
                                                        <input type="text" class="form-control" id="nif" placeholder="Nº de Indetificação Fiscal (NIF)" name="nif" value="<?php
                                                        if (isset($valorForm['nif'])) {
                                                            echo $valorForm['nif'];
                                                        }
                                                        ?>" required>
                                                        <div class="invalid-feedback">
                                                            Campo Obrigatório.
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="emailnovo">Email <span class="text-muted"></span></label>
                                                    <input type="emailnovo" class="form-control" id="email" placeholder="you@example.com" name="emailnovo" value="<?php
                                                    if (isset($valorForm['email'])) {
                                                        echo $valorForm['email'];
                                                    }
                                                    ?>" required>
                                                    <div class="invalid-feedback">
                                                        Campo Obrigatório.
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="morada">Morada</label>
                                                        <input type="text" class="form-control" id="tel1" placeholder="Número do Morada"  name="morada" value="<?php
                                                        if (isset($valorForm['morada'])) {
                                                            echo $valorForm['morada'];
                                                        }
                                                        ?>" required>
                                                        <div class="invalid-feedback">
                                                            Campo Obrigatório.
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="telefone">Nº de Telefone</label>
                                                        <input type="text" class="form-control" id="telefone" placeholder="Número do Telefone"  name="telefone" value="<?php
                                                        if (isset($valorForm['telefone'])) {
                                                            echo $valorForm['telefone'];
                                                        }
                                                        ?>" minlength="9" maxlength="9" min="0" required>
                                                        <div class="invalid-feedback">
                                                            Campo Obrigatório.
                                                        </div>
                                                    </div>



                                                </div>  

                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                                <input type="hidden" name="idfornecedor" value="<?php echo $valorForm['idfornecedor']; ?>">
                                                <input type="hidden" name="email" value="<?php echo $valorForm['email']; ?>">
                                                <button class="btn btn-primary" name="btnEditFornecedor">Salvar</button>
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
