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


<!-- Logout Modal-->
<div class="modal fade" id="novoproduto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" method="post" class="needs-validation" enctype="multipart/form-data" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cadastrar Produto</h5>
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
                        <div class="col-md-6 mb-3">
                            <label for="referencia">Refêrencia</label>
                            <input type="text" class="form-control" id="referencia" placeholder="Inseri O referencia" name="referencia" value="<?php
                            if (isset($valorForm['referencia'])) {
                                echo $valorForm['referencia'];
                            }
                            ?>" required>
                            <div class="invalid-feedback">
                                Campo Obrigatório.
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="categoria">Categoria</label>
                            <select class="custom-select" id="fornecedor" name="categoria" required>
                                <option selected disabled value="">Escolhe...</option>
                                <?php
                                if (isset($this->dadosPaginacao)) {
                                    foreach ($this->dadosPaginacao as $valor) {
                                        ?>
                                        <option value="<?php echo $valor['idcategoria']; ?>"><?php echo $valor['nome']; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="fornecedor">Fornecedor</label>
                            <select class="custom-select" id="fornecedor" name="fornecedor" required>
                                <option selected disabled value="">Escolhe...</option>
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
                    </div> 
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="estoque">Estoque <span class="text-muted"></span></label>
                            <input type="text" class="form-control" id="estoque" placeholder="Número Estoque" name="estoque" value="<?php
                            if (isset($valorForm['estoque'])) {
                                echo $valorForm['estoque'];
                            }
                            ?>" required>
                            <div class="invalid-feedback">
                                Campo Obrigatório.
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="valor_compra">Valor Compra</label>
                            <input type="text" class="form-control" id="tel1" placeholder="Valor da Compra"  name="valor_compra" value="<?php
                            if (isset($valorForm['valor_compra'])) {
                                echo $valorForm['valor_compra'];
                            }
                            ?>" required>
                            <div class="invalid-feedback">
                                Campo Obrigatório.
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="valor_venda">Valor Venda</label>
                            <input type="text" class="form-control" id="tel1" placeholder="Valor da Venda"  name="valor_venda" value="<?php
                            if (isset($valorForm['valor_venda'])) {
                                echo $valorForm['valor_venda'];
                            }
                            ?>" required>
                            <div class="invalid-feedback">
                                Campo Obrigatório.
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-7 mb-3">
                            <label for="descricao">Descrição</label>
                            <textarea class="form-control" id="descricao" name="descricao" placeholder="Escreva A discrição da Peça" required></textarea>
                            <div class="invalid-feedback">
                                Campo Obrigatório.
                            </div>
                        </div>
                        <div class="col-md-5 mb-3">
                            <img src="<?php echo URLADM . "/app/adms/assets/foto/" . $valorForm['foto']; ?>" style="width: 150px;height: 150px;" class="img-fluid rounded mx-auto d-block img-thumbnail prev-img" id="preview-img" alt="">
                            <br>

                            <div class="custom-file">
                                <input type="file" class="custom-file-input pb-4" id="foto" name="foto"  onchange="previewImagem();" required>
                                <label class="custom-file-label" for="foto" data-browse="Procurar foto">Escolha a Foto</label>
                                <div class="invalid-feedback">Escolha Uma foto de Perfil Nova</div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary text-white" name="btnCdsProduto">Salvar</button>
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
        <a type="button" class="btn-primary btn-sm ml-3 d-none d-md-block text-white" href="" data-toggle="modal" data-target="#novoproduto"> Novo Produto</a>
        <!--<a type="button" class="btn-primary btn-sm ml-3 d-block d-lg-none"> + </a>-->
    </div>

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
                                        <a href="<?php echo URLADM . "editarFoto?id=" . $valorForm['idproduto']; ?>" class="btn btn-outline-warning  btn-sm edit">
                                            <div class="icon"><i class="icofont-edit text-uppercase w3-text-black">Editar</i></div>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="<?php echo $valorForm['idproduto']; ?>" data-toggle="modal" data-target="#edit<?php echo $valorForm['idproduto']; ?>" title="Editar Registo"><i class="icofont icofont-edit px-1"></i></a>
                                        <a href="<?php echo $valorForm['idproduto']; ?>" data-toggle="modal" data-target="#delete<?php echo $valorForm['idproduto']; ?>" title="Apagar Registo"><i class="icofont icofont-trash text-danger px-1"></i></a>
                                        <a href="<?php echo $valorForm['idproduto']; ?>" data-toggle="modal" data-target="#info<?php echo $valorForm['idproduto']; ?>" title="Descrição"><i class="icofont icofont-warning-alt text-primary px-1"></i></a>
                                        <a href="<?php echo $valorForm['idproduto']; ?>" data-toggle="modal" data-target="#mais<?php echo $valorForm['idproduto']; ?>" title="Fazer Pedido"><i class="icofont icofont-plus text-success px-2"></i></a>
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
                            <!--  Modal Deletar-->
                            <div class="modal fade" id="edit<?php echo $valorForm['idproduto']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                                                    <div class="col-md-6 mb-3">
                                                        <label for="referencia">Refêrencia</label>
                                                        <input type="text" class="form-control" id="referencia" placeholder="Inseri O referencia" name="referencia" value="<?php
                                                        if (isset($valorForm['referencia'])) {
                                                            echo $valorForm['referencia'];
                                                        }
                                                        ?>" required>
                                                        <div class="invalid-feedback">
                                                            Campo Obrigatório.
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="categoria">Categoria</label>
                                                        <select class="custom-select" id="categoria" name="categoria" required>
                                                            <option selected disabled value="">Escolhe...</option>
                                                            <?php
                                                            if (isset($this->dadosPaginacao)) {
                                                                foreach ($this->dadosPaginacao as $valor) {
                                                                    ?>
                                                                    <option value="<?php echo $valor['idcategoria']; ?>"><?php echo $valor['nome']; ?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label for="fornecedor">Fornecedor</label>
                                                        <select class="custom-select" id="fornecedor" name="fornecedor" required>
                                                            <option selected disabled value="">Escolhe...</option>
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
                                                </div> 
                                                <div class="row">
                                                    <div class="col-md-4 mb-3">
                                                        <label for="estoque">Estoque <span class="text-muted"></span></label>
                                                        <input type="text" class="form-control" id="estoque" placeholder="Número Estoque" name="estoque" value="<?php
                                                        if (isset($valorForm['estoque'])) {
                                                            echo $valorForm['estoque'];
                                                        }
                                                        ?>" required disabled>
                                                        <div class="invalid-feedback">
                                                            Campo Obrigatório.
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="valor_compra">Valor Compra</label>
                                                        <input type="text" class="form-control" id="tel1" placeholder="Valor da Compra"  name="valor_compra" value="<?php
                                                        if (isset($valorForm['valor_compra'])) {
                                                            echo $valorForm['valor_compra'];
                                                        }
                                                        ?>" required>
                                                        <div class="invalid-feedback">
                                                            Campo Obrigatório.
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="valor_venda">Valor Venda</label>
                                                        <input type="text" class="form-control" id="tel1" placeholder="Valor da Venda"  name="valor_venda" value="<?php
                                                        if (isset($valorForm['valor_venda'])) {
                                                            echo $valorForm['valor_venda'];
                                                        }
                                                        ?>" required>
                                                        <div class="invalid-feedback">
                                                            Campo Obrigatório.
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class=" mb-3">
                                                    <label for="descricao">Descrição</label>
                                                    <textarea class="form-control" id="descricao" name="descricao" placeholder="Escreva A Discrição da Peça" required><?php echo $valorForm['descricao']; ?></textarea>
                                                    <div class="invalid-feedback">
                                                        Campo Obrigatório.
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                                <input type="hidden" name="idproduto" value="<?php echo $valorForm['idproduto']; ?>">
                                                <input type="hidden" name="estoque_antigo" value="<?php echo $valorForm['estoque']; ?>">
                                                <button class="btn btn-primary" name="btnEditProduto">Sim</button>
                                            </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                            <!--  Modal Descrição do Produto-->
                            <div class="modal fade" id="info<?php echo $valorForm['idproduto']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                 aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Descrição do Produto</h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <img src="<?php echo URLADM . "app/adms/assets/foto/" . $valorForm['foto']; ?>" class="card-img shadow" alt="..." width="500rem" height="300rem">
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="card-body">
                                                        <p class="" style="font-size: 20px;"><?php echo $valorForm['descricao']; ?></p>
                                                        <p class="" style="font-size: 20px;"><small class="text-muted">Ultima Atualização: <?php
                                                            $__prod_modified = $valorForm['modified'] ?? $valorForm['created'] ?? null;
                                                            if (!empty($__prod_modified)) {
                                                                echo date('d/m/Y H:i', strtotime($__prod_modified));
                                                            } else {
                                                                echo '-';
                                                            }
                                                            ?></small></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--  Modal Dados Do Fornecedor-->
                            <div class="modal fade" id="fornecedor<?php echo $valorForm['idproduto']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                 aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Dados do Fornecedor</h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="container">
                                                <p><strong>Nome: </strong><?php echo $valorForm['fornecedor']; ?></p>                                                
                                                <p><strong>Nif: </strong><?php echo $valorForm['nif']; ?></p>
                                                <p><strong>Tipo Pessoa: </strong><?php echo $valorForm['tipo_pessoa']; ?></p>
                                                <p><strong>Telefone: </strong><?php echo $valorForm['telefone']; ?></p>
                                                <p><strong>Email: </strong><?php echo $valorForm['email']; ?></p>
                                                <p><strong>Andereço: </strong><?php echo $valorForm['morada']; ?></p>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
