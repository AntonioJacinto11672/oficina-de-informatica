<?php
if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: PÃ¡gina nÃ£o encontrada!");
}
$formData = isset($this->dados['form']) ? $this->dados['form'] : [];
if (isset($_SESSION['idlogado'])) {
    //var_dump($this->dados);
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
    <div class="row mt-4 mb-4">
        <a type="button" class="btn-primary btn-sm ml-3 d-none d-md-block text-white" href="#" data-toggle="modal" data-target="#novocontas_apagar">Nova Contas Ã  Pagar</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Contas Ã  Pagar</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>DescriÃ§Ã£o</th>
                            <th>Valor</th>
                            <th>FuncionÃ¡rio</th>
                            <th>Data Vencimento</th>
                            <th>Arquivo</th>
                            <th>AcÃ§Ã£o</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>DescriÃ§Ã£o</th>
                            <th>Valor</th>
                            <th>FuncionÃ¡rio</th>
                            <th>Data Vencimento</th>
                            <th>Arquivo</th>
                            <th>AcÃ§Ã£o</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                        if (isset($this->dados)) {
                            for ($index = 0; $index < count($this->dados); $index++) {
                                $valorForm = $this->dados[$index];
                                if (!is_array($valorForm) || !isset($valorForm['idcontas_apagar'])) continue;
                                $newConn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME, DBPORT);
                                $query = "SELECT * FROM usuario WHERE nif='{$valorForm['funcionario']}' LIMIT 1";
                                $result = mysqli_query($newConn, $query);
                                $mecRow = ($result !== false) ? mysqli_fetch_assoc($result) : [];
                                $mecanico = @$mecRow['nome'] . " " . @$mecRow['sobrenome'];
                                ?>
                                <tr>
                                    <td><?php
                                        if ($valorForm['pago'] === "sim") {
                                            echo '<i class="fa fa-square text-success px-1"></i>';
                                        } else {
                                            echo '<i class="fa fa-square text-danger px-1"></i>';
                                        }
                                        if ($valorForm['descricao'] === "Compra de Produto") {
                                            echo '<a data-toggle="modal" data-target="#produto' . $valorForm['idcontas_apagar'] . '" href="#" style="color: #858796;">' . $valorForm['descricao'] . '</a>';
                                        } else {
                                            echo ' ' . $valorForm['descricao'];
                                        }
                                        ?></td>
                                    <td><?php
                                        echo number_format($valorForm['valor'], 2, ',', '.');
                                        ?> KZ
                                    </td>
                                    <td><?php echo $mecanico; ?></td>
                                    <td><?php echo $valorForm['data_venci']; ?></td>
                                    <td>
                                        <?php if (!empty($valorForm['foto'])) { ?>
                                            <a href="<?php echo URLADM . "app/adms/assets/foto/" . $valorForm['foto']; ?>" target="_blank">ver arquivo</a>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php if ($valorForm['descricao'] != "Compra de Produto" && $valorForm['descricao'] != "ComissÃ£o") { ?>
                                            <a href="#" data-toggle="modal" data-target="#edit<?php echo $valorForm['idcontas_apagar']; ?>" title="Editar Registo"><i class="icofont icofont-edit px-1"></i></a>
                                        <?php } ?>
                                        <a href="#" data-toggle="modal" data-target="#delete<?php echo $valorForm['idcontas_apagar']; ?>" title="Apagar Registo"><i class="icofont icofont-trash text-danger px-1"></i></a>
                                        <?php if ($valorForm['pago'] === "nao") { ?>
                                            <a href="#" data-toggle="modal" data-target="#info<?php echo $valorForm['idcontas_apagar']; ?>" title="Aprovar Conta"><i class="icofont icofont-ui-clip-board text-success px-1"></i></a>
                                        <?php } ?>
                                    </td>
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

    <?php
    /* Modals placed OUTSIDE the table â€” inside tbody is invalid HTML */
    if (isset($this->dados)) {
        for ($index = 0; $index < count($this->dados); $index++) {
            $valorForm = $this->dados[$index];
            if (!is_array($valorForm) || !isset($valorForm['idcontas_apagar'])) continue;
            $newConn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME, DBPORT);
            $query = "SELECT * FROM usuario WHERE nif='{$valorForm['funcionario']}' LIMIT 1";
            $result = mysqli_query($newConn, $query);
            $mecRow = ($result !== false) ? mysqli_fetch_assoc($result) : [];
            $mecanico = @$mecRow['nome'] . " " . @$mecRow['sobrenome'];
            ?>

            <!-- Modal Deletar -->
            <div class="modal fade" id="delete<?php echo $valorForm['idcontas_apagar']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">Tens A Certeza Que Queres Apagar?</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Ã—</span></button>
                        </div>
                        <div class="modal-body">Clica "Sim" Para Apagar Essa Conta Ã  Pagar <?php echo $valorForm['descricao']; ?>.</div>
                        <div class="modal-footer bg-danger text-white">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                            <form action="" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="idcontas_apagar" value="<?php echo $valorForm['idcontas_apagar']; ?>">
                                <input type="hidden" name="descricao" value="<?php echo $valorForm['descricao']; ?>">
                                <input type="hidden" name="pago" value="<?php echo $valorForm['pago']; ?>">
                                <button class="btn btn-primary" name="btnDeletcontas_apagar">Sim Excluir</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Editar -->
            <div class="modal fade" id="edit<?php echo $valorForm['idcontas_apagar']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="modal-header">
                                <h5 class="modal-title">Editar Conta Ã  Pagar</h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Ã—</span></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>DescriÃ§Ã£o</label>
                                        <input type="text" class="form-control" placeholder="DescriÃ§Ã£o" name="descricao" value="<?php echo isset($valorForm['descricao']) ? htmlspecialchars($valorForm['descricao']) : ''; ?>" required>
                                        <div class="invalid-feedback">Insira a descriÃ§Ã£o.</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Valor Compra</label>
                                        <input type="text" class="form-control" placeholder="Valor da Compra" name="valor" value="<?php echo isset($valorForm['valor']) ? $valorForm['valor'] : ''; ?>" required>
                                        <div class="invalid-feedback">Campo ObrigatÃ³rio.</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Data Vencimento</label>
                                        <input type="date" class="form-control" name="data_venci" value="<?php echo isset($valorForm['data_venci']) ? $valorForm['data_venci'] : ''; ?>" required>
                                        <div class="invalid-feedback">Campo ObrigatÃ³rio.</div>
                                    </div>
                                    <div class="col-md-5 mb-3">
                                        <img src="<?php echo URLADM . "/app/adms/assets/foto/" . (isset($valorForm['foto']) ? $valorForm['foto'] : ''); ?>" style="width:150px;height:150px;" class="img-fluid rounded mx-auto d-block img-thumbnail" alt="">
                                        <br>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="fotoEdit<?php echo $valorForm['idcontas_apagar']; ?>" name="foto" onchange="previewImagem();" disabled>
                                            <label class="custom-file-label" for="fotoEdit<?php echo $valorForm['idcontas_apagar']; ?>" data-browse="Procurar">Escolha a Foto</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                <input type="hidden" name="idcontas_apagar" value="<?php echo $valorForm['idcontas_apagar']; ?>">
                                <input type="hidden" name="data_venci_antigo" value="<?php echo $valorForm['data_venci']; ?>">
                                <button class="btn btn-primary" name="btnEditcontas_apagar">Sim</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal Aprovar -->
            <div class="modal fade" id="info<?php echo $valorForm['idcontas_apagar']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-info text-white">
                            <h5 class="modal-title">Aprovar Pagamento</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Ã—</span></button>
                        </div>
                        <div class="modal-body">
                            <p>Desejas Realmente Aprovar Esse Pagamento?</p>
                        </div>
                        <div class="modal-footer">
                            <form action="" method="post" enctype="multipart/form-data">
                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                <input type="hidden" name="idcontas_apagar" value="<?php echo $valorForm['idcontas_apagar']; ?>">
                                <input type="hidden" name="descricao" value="<?php echo $valorForm['descricao']; ?>">
                                <input type="hidden" name="valor" value="<?php echo $valorForm['valor']; ?>">
                                <button class="btn btn-success" name="btnAprovarConta">Sim Aprovar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Dados Adicionais (mais) -->
            <div class="modal fade" id="mais<?php echo $valorForm['idcontas_apagar']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="modal-header">
                                <h5 class="modal-title">Dados do FuncionÃ¡rio</h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Ã—</span></button>
                            </div>
                            <div class="modal-body">
                                <div class="col-md-12 mb-3">
                                    <label>FuncionÃ¡rio</label>
                                    <select class="custom-select" name="usuario" required>
                                        <option selected value="<?php echo $valorForm['funcionario'] ?? ''; ?>"><?php echo $mecanico; ?></option>
                                        <?php
                                        if (isset($this->dadosAlter)) {
                                            foreach ($this->dadosAlter as $valor) {
                                                echo '<option value="' . $valor['idusuario'] . '">' . $valor['nome'] . ' ' . $valor['sobrenome'] . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label>Valor Compra</label>
                                    <input type="text" class="form-control" placeholder="Valor da Compra" name="valor" value="<?php echo isset($valorForm['valor']) ? $valorForm['valor'] : ''; ?>" required>
                                    <div class="invalid-feedback">Campo ObrigatÃ³rio.</div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label>Valor Venda</label>
                                    <input type="text" class="form-control" placeholder="Valor da Venda" name="valor_venda" value="<?php echo isset($valorForm['valor_venda']) ? $valorForm['valor_venda'] : ''; ?>" required>
                                    <div class="invalid-feedback">Campo ObrigatÃ³rio.</div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label>Adicionar Dias ao Vencimento</label>
                                    <input type="number" class="form-control" placeholder="NÂº de dias a adicionar" name="data_venci" value="" required>
                                    <div class="invalid-feedback">Campo ObrigatÃ³rio.</div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                <input type="hidden" name="idcontas_apagar" value="<?php echo $valorForm['idcontas_apagar']; ?>">
                                <input type="hidden" name="data_venci_antigo" value="<?php echo $valorForm['data_venci']; ?>">
                                <input type="hidden" name="descricao" value="<?php echo $valorForm['descricao']; ?>">
                                <button class="btn btn-primary" name="btnadddata_venci">Sim</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal Dados Da Compra (produto) -->
            <div class="modal fade" id="produto<?php echo $valorForm['idcontas_apagar']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">Dados da Compra</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Ã—</span></button>
                        </div>
                        <div class="modal-body">
                            <ul class="list-unstyled">
                                <li class="media my-4">
                                    <?php
                                    $connProd = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME, DBPORT);
                                    $qProd = "SELECT * FROM compras_contaspagar_dadosproduto WHERE idcontas_apagar='{$valorForm['idcontas_apagar']}' LIMIT 1";
                                    $rProd = mysqli_query($connProd, $qProd);
                                    $teste = ($rProd !== false) ? mysqli_fetch_assoc($rProd) : null;
                                    if (!empty($teste)) {
                                        ?>
                                        <img src="<?php echo URLADM . "app/adms/assets/foto/" . $teste['foto']; ?>" class="mr-3" alt="Imagem do Produto" width="200" height="200">
                                        <div class="media-body">
                                            <strong>Nome do Produto: </strong> <?php echo $teste['produto']; ?><br>
                                            <span class="pr-3"><strong>Categoria: </strong> <?php echo $teste['categoria']; ?></span><br>
                                            <strong>ReferÃªncia: </strong> <?php echo $teste['referencia']; ?><br>
                                            <span class="pr-3"><strong>Quantidade: </strong> <?php echo $teste['quantidade_estoque']; ?></span><br>
                                            <strong>Valor de Cada Produto: </strong><?php echo number_format($teste['valor_compras'], 2, ',', '.'); ?> Kz<br>
                                            <strong>Total Ã  Pagar: </strong><?php echo number_format($teste['total_apagar'], 2, ',', '.'); ?> Kz<br>
                                            <span class="pr-3"><strong>Fornecedor: </strong><?php echo $teste['fornecedor']; ?></span><br>
                                            <strong>FuncionÃ¡rio: </strong><?php echo $mecanico; ?><br>
                                            <strong>Data: </strong><?php echo $teste['data_venci']; ?><br>
                                            <?php
                                            $status = ($teste['pago'] == "nao")
                                                ? "<span class='text-danger'>NÃ£o EstÃ¡ Paga</span>"
                                                : "<span class='text-success'>EstÃ¡ Paga</span>";
                                            ?>
                                            <p><strong>Status:</strong> <?php echo $status; ?></p>
                                        </div>
                                        <?php
                                    } else {
                                        ?>
                                        <img src="<?php echo URLADM . "app/adms/assets/foto/" . (isset($valorForm['foto']) ? $valorForm['foto'] : ''); ?>" class="mr-3" alt="..." width="200" height="200">
                                        <div class="media-body">
                                            <strong>Nome do Produto: </strong> <?php echo isset($valorForm['nome']) ? $valorForm['nome'] : ''; ?><br>
                                            <strong>Valor do Produto: </strong><?php echo number_format($valorForm['valor'], 2, ',', '.'); ?> Kz<br>
                                            <strong>Data: </strong><?php echo $valorForm['data_venci']; ?><br>
                                            <strong>FuncionÃ¡rio: </strong><?php echo $mecanico; ?><br>
                                            <?php
                                            $mensagem = '<cite class="text-danger">Esse Produto Foi Apagado do Estoque. PeÃ§a ao Administrador para apagar o registo.</cite>';
                                            $status = ($valorForm['pago'] == "nao") ? $mensagem : "<span class='text-success'>EstÃ¡ Paga</span>";
                                            ?>
                                            <p><strong>Status:</strong> <?php echo $status; ?></p>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </li>
                            </ul>
                        </div>
                        <div class="modal-footer bg-primary text-white">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>

            <?php
        }
    }
    ?>

</div>
<!-- /.container-fluid -->

<!-- Modal Nova Conta Ã  Pagar -->
<div class="modal fade" id="novocontas_apagar" tabindex="-1" role="dialog" aria-labelledby="novocontasLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="novocontasLabel">Cadastrar Conta Ã  Pagar</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">Ã—</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>DescriÃ§Ã£o</label>
                            <input type="text" class="form-control" placeholder="DescriÃ§Ã£o da conta" name="descricao" value="<?php echo isset($formData['descricao']) ? htmlspecialchars($formData['descricao']) : ''; ?>" required>
                            <div class="invalid-feedback">Insira a descriÃ§Ã£o.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Valor Compra</label>
                            <input type="text" class="form-control" placeholder="Valor da Compra" name="valor" value="<?php echo isset($formData['valor']) ? $formData['valor'] : ''; ?>" required>
                            <div class="invalid-feedback">Campo ObrigatÃ³rio.</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Data Vencimento</label>
                            <input type="date" class="form-control" name="data_venci" value="<?php echo isset($formData['data_venci']) ? $formData['data_venci'] : ''; ?>" required>
                            <div class="invalid-feedback">Campo ObrigatÃ³rio.</div>
                        </div>
                        <div class="col-md-5 mb-3">
                            <img src="<?php echo URLADM . "/app/adms/assets/foto/" . (isset($formData['foto']) ? $formData['foto'] : ''); ?>" style="width:150px;height:150px;" class="img-fluid rounded mx-auto d-block img-thumbnail prev-img" id="preview-img" alt="">
                            <br>
                            <div class="">
                                <input type="file" class="form-control-file pb-4" name="foto" onchange="previewImagem();" required>
                                <div class="invalid-feedback">Escolha Uma foto</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary text-white" name="btnCdsContas_apagar">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
