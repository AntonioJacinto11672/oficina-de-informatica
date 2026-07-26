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
<!--  Modal Pesqusiar do Cliente-->
<div class="modal fade" id="pesquisar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Pesquisar Cliente</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-inline col-12">
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control" type="search" placeholder="Search" aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn  btn-dark" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <button data-toggle="modal" data-target="#novoorcamento" data-dismiss="modal"
                    class="btn btn-primary">Pesquisar</button>
            </div>
        </div>
    </div>
</div>


<!-- Abrir Orçamento Modal-->
<div class="modal fade" id="novoorcamento" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" method="post" class="needs-validation" enctype="multipart/form-data" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Abrir Ocorrência</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php if (!empty($this->dados['id_ocorrencia_origem'])): ?>
                        <div class="alert alert-info">A criar orçamento para a Ocorrência #<?php echo (int)$this->dados['id_ocorrencia_origem']; ?>.</div>
                        <input type="hidden" name="id_ocorrencia" value="<?php echo (int)$this->dados['id_ocorrencia_origem']; ?>">
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="Cliente">Nif do Cliente Pesquisa </label>
                            <input list="browsers" class="form-control" name="clinete" id="tel1"
                                placeholder="Digete o Nif  do Cliente" value="<?php
                                if (isset($valorForm['clinete'])) {
                                    echo $valorForm['clinete'];
                                }
                                ?>" required>
                            <datalist id="browsers">
                                <?php
                                $newConn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME, DBPORT);
                                $query = "SELECT nif FROM clientes LIMIT 3";
                                $result = mysqli_query($newConn, $query);
                                while ($mecanico = mysqli_fetch_assoc($result)) {
                                    //var_dump($mecanico);
                                    $mecanico = $mecanico['nif'];
                                    ?>
                                    <option value="<?php echo $mecanico; ?>">
                                    <?php } ?>


                            </datalist>
                            <div class="invalid-feedback">
                                Insira O Nome.
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="veiculo">Nº de Série do Equipamento</label>
                            <input type="text" class="form-control" id="referencia"
                                placeholder="Digite o Nº de Série do Equipamento" name="veiculo" value="<?php
                                if (isset($valorForm['veiculo'])) {
                                    echo $valorForm['veiculo'];
                                }
                                ?>" required>
                            <div class="invalid-feedback">
                                Campo Obrigatório.
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="servico">Serviço</label>
                            <select class="custom-select" id="servico" name="id_tipo_servico" required>
                                <option selected disabled value="">Escolhe...</option>
                                <?php
                                if (isset($this->dadosAlter)) {
                                    foreach ($this->dadosAlter as $valor) {
                                        ?>
                                        <option value="<?php echo $valor['idtipo_servico']; ?>"><?php echo $valor['nome']; ?>
                                        </option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tipo_manutencao">Tipo de Manutenção</label>
                            <select class="custom-select" id="tipo_manutencao" name="tipo_manutencao" required>
                                <option value="Corretiva" selected>Corretiva</option>
                                <option value="Preventiva">Preventiva</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="data_prevista">Data Prevista</label>
                            <input type="date" class="form-control" id="data_prevista" name="data_prevista" value="<?php
                            if (isset($valorForm['data_prevista'])) {
                                echo $valorForm['data_prevista'];
                            }
                            ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="descricao">Descrição</label>
                            <textarea class="form-control" id="descricao" name="descricao"
                                placeholder="Escreva A discrição da Peça" required></textarea>
                            <div class="invalid-feedback">
                                Campo Obrigatório.
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="data_entrega">Data da Entrega <span class="text-muted"></span></label>
                            <input type="date" class="form-control" id="data_entrada" name="data_entrega" value="<?php
                            if (isset($valorForm['data_entrada'])) {
                                echo $valorForm['data_entrada'];
                            }
                            ?>" required>
                            <div class="invalid-feedback">
                                Campo Obrigatório.
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="garantia">Garantia</label>
                            <input type="number" class="form-control" id="tel1" placeholder="" min="0" name="garantia"
                                value="<?php
                                if (isset($valorForm['garantia'])) {
                                    echo $valorForm['garantia'];
                                }
                                ?>" required>
                            <div class="invalid-feedback">
                                Campo Obrigatório.
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="valor">Valor (Mão de Obra)</label>
                            <input type="text" class="form-control" id="tel1" placeholder="Valor da Mão de Obra"
                                name="valor" value="<?php
                                if (isset($valorForm['valor'])) {
                                    echo $valorForm['valor'];
                                }
                                ?>" required>
                            <div class="invalid-feedback">
                                Campo Obrigatório.
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="obs">Observação do Equipamento</label>
                            <textarea class="form-control" id="obs" name="obs"
                                placeholder="Escreva A discrição da Peça"></textarea>
                            <div class="invalid-feedback">
                                Campo Obrigatório.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary text-white" name="btnAbriOrcamento">Salvar</button>
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
        <a type="button" class="btn-primary btn-sm ml-3 d-none d-md-block text-white" href="" data-toggle="modal"
            data-target="#novoorcamento"> Abrir Orcamento</a>
        <!--<a type="button" class="btn-primary btn-sm ml-3 d-block d-lg-none"> + </a>-->
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Orçamentos</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Veiculo</th>
                            <th>Valor</th>
                            <th>Serviço</th>
                            <th>Data</th>
                            <th>Técnico</th>
                            <th>Acção</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Cliente</th>
                            <th>Veiculo</th>
                            <th>Valor</th>
                            <th>Serviço</th>
                            <th>Data</th>
                            <th>Técnico</th>
                            <th>Acção</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                        $orcamentos = $this->dados ?? [];
                        if (isset($orcamentos['form'])) {
                            unset($orcamentos['form']);
                        }
                        if (isset($orcamentos['id_ocorrencia_origem'])) {
                            unset($orcamentos['id_ocorrencia_origem']);
                        }

                        if (!empty($orcamentos)) {
                            foreach ($orcamentos as $valorForm) {
                                if (!is_array($valorForm)) {
                                    continue;
                                }

                                $idOrcamento = (int)($valorForm['idorcamentos'] ?? 0);
                                $clienteNome = trim(($valorForm['nome_cliente'] ?? '') . ' ' . ($valorForm['sobrenome'] ?? ''));
                                $veiculo = $valorForm['marca'] ?? '';
                                $valorFormatado = number_format((float)($valorForm['valor'] ?? 0), 2, ',', '.');
                                $servico = $valorForm['tipo_servico'] ?? '';
                                $dataOrcamento = '';
                                if (!empty($valorForm['data_orcamento'])) {
                                    $dataOrcamento = date('d/m/Y', strtotime((string) $valorForm['data_orcamento']));
                                }

                                $tecnicoNome = '';
                                if (!empty($valorForm['tecnico'])) {
                                    $newConn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME, DBPORT);
                                    if ($newConn) {
                                        $query = "SELECT * FROM usuario WHERE nif='" . mysqli_real_escape_string($newConn, (string) $valorForm['tecnico']) . "' LIMIT 1";
                                        $result = mysqli_query($newConn, $query);
                                        if ($result && $mecanico = mysqli_fetch_assoc($result)) {
                                            $tecnicoNome = trim(($mecanico['nome'] ?? '') . ' ' . ($mecanico['sobrenome'] ?? ''));
                                        }
                                    }
                                }

                                $status = $valorForm['status'] ?? '';
                                $nifCliente = $valorForm['nif'] ?? '';
                                $matricula = $valorForm['matricula'] ?? '';
                                $modelo = $valorForm['modelo'] ?? '';
                                $tipo = $valorForm['tipo'] ?? '';
                                $idTipoServico = $valorForm['idtipo_servico'] ?? '';
                                $valorMaoObra = $valorForm['valor'] ?? 0;
                                ?>
                                <tr>
                                    <td><?php echo $clienteNome; ?></td>
                                    <td><?php echo $veiculo; ?></td>
                                    <td><?php echo $valorFormatado; ?> KZ</td>
                                    <td><?php echo $servico; ?></td>
                                    <td><?php echo $dataOrcamento; ?></td>
                                    <td><?php echo $tecnicoNome; ?></td>
                                    <td>
                                        <?php if ($status == "Aberto") { ?>
                                            <a href="<?php echo $idOrcamento; ?>" data-toggle="modal"
                                                data-target="#edit<?php echo $idOrcamento; ?>"
                                                title="Editar Registo"><i class="icofont icofont-edit px-1"></i></a>
                                            <a href="<?php echo $idOrcamento; ?>" data-toggle="modal"
                                                data-target="#delete<?php echo $idOrcamento; ?>"
                                                title="Apagar Registo"><i class="icofont icofont-trash text-danger px-1"></i></a>
                                            <a href="<?php echo URLADM . "addProdutoOrcamento?id=" . $idOrcamento . "&cliente=" . $nifCliente; ?>"
                                                title="Adicionar Produto"><i class="icofont icofont-plus text-success px-2"></i></a>
                                            <a href="<?php echo URLADM . "relatorioTecnico?relatorio=orcamento&idorcamentos=" . $idOrcamento; ?>"
                                                title="Imprimir Relatório"><i
                                                    class="icofont icofont-file-text text-success px-1"></i></a>
                                            <a href="" data-toggle="modal"
                                                data-target="#aprovar<?php echo $idOrcamento; ?>"
                                                title="Aprovar Orcamento"><i class="icofont icofont-ui-check text-success px-2"></i></a>
                                            <a href="" data-toggle="modal"
                                                data-target="#enviar<?php echo $idOrcamento; ?>"
                                                title="Enviar Email"><i class="icofont icofont-envelope text-success px-1"></i></a>
                                        <?php } else { ?>
                                            <a href="<?php echo URLADM . "relatorioTecnico?relatorio=orcamento&idorcamentos=" . $idOrcamento; ?>"
                                                title="Imprimir Relatório"><i
                                                    class="icofont icofont-file-text text-success px-1"></i></a>
                                        <?php } ?>

                                    </td>
                                </tr>
                                <!-- Modal do W3C-->
                                <div class="modal fade" id="mais<?php echo $idOrcamento; ?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content bg-primary">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Primary Modal</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>One fine body&hellip;</p>
                                                <a href="<?php echo URLADM; ?>produto" data-gall="portfolioDetailsGallery"
                                                    data-vbtype="iframe" class="venobox" title="Portfolio Details"><i
                                                        class="bx bx-link"></i></a>
                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-between">
                                            <button type="button" class="btn btn-outline-light"
                                                data-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-outline-light">Save changes</button>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->
                    <!--  Modal Deletar-->
                    <div class="modal fade" id="delete<?php echo $idOrcamento; ?>" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Tens A Certeza Que Queres Apagar?</h5>
                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">Clica "Sim" Para Apagar Esse Orçamento?.</div>
                                <div class="modal-footer">
                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                    <form action="" method="post" class="needs-validation" enctype="multipart/form-data"
                                        novalidate>
                                        <input type="hidden" name="idorcamentos"
                                            value="<?php echo $idOrcamento; ?>">
                                        <button class="btn btn-primary" name="btnDeletOrcamento">Sim</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Aprovar Orçamento Modal-->
                    <div class="modal fade" id="aprovar<?php echo $idOrcamento; ?>" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content text-white">
                                <form action="" method="post" class="needs-validation" enctype="multipart/form-data" novalidate>
                                    <div class="modal-header bg-secondary">
                                        <h5 class="modal-title" id="exampleModalLabel">Aprovar Orcamento</h5>
                                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="text-gray-600"> Tens Certeza Que Queres Aprovar Esse Orçamento??</p>
                                    </div>
                                    <div class="modal-footer bg-success text-gray-600">
                                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                        <input type="hidden" value="<?php echo $idOrcamento; ?>" name="idorcamentos"/>
                                        <input type="hidden" value="<?php echo $matricula; ?>" name="veiculo"/>
                                        <input type="hidden" value="<?php echo $idTipoServico; ?>" name="id_tipo_servico"/>
                                        <input type="hidden" value="<?php echo $valorMaoObra; ?>" name="valor_t_servico"/>
                                        <input type="hidden" value="<?php echo $valorMaoObra; ?>" name="valor_maodeobra"/>
                                        <input type="hidden" value="<?php echo $nifCliente; ?>" name="cliente"/>
                                        <input type="hidden" value="<?php echo $servico; ?>" name="servico"/>
                                        <input type="hidden" value="<?php echo $matricula; ?>" name="matricula"/>
                                        <input type="hidden" value="<?php echo $modelo; ?>" name="modelo"/>
                                        <input type="hidden" value="<?php echo $tipo; ?>" name="tipo"/>
                                        <button class="btn btn-info text-white " name="btnAprovar">Aprovar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Abrir Orçamento Modal-->
                    <div class="modal fade" id="edit<?php echo $idOrcamento; ?>" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <form action="" method="post" class="needs-validation" enctype="multipart/form-data" novalidate>
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Editar Ocorrência</h5>
                                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="Cliente">Nif do Cliente </label>
                                                <input type="text" class="form-control" id="tel1"
                                                    placeholder="Digete o Nif  do Cliente" name="clinete" value="<?php
                                                    echo $nifCliente;
                                                    ?>" required>
                                                <div class="invalid-feedback">
                                                    Insira O Nome.
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="veiculo">Nº de Série do Equipamento</label>
                                                <input type="text" class="form-control" id="referencia"
                                                    placeholder="Digite o Nº de Série do Equipamento" name="veiculo" value="<?php
                                                    echo $matricula;
                                                    ?>" required>
                                                <div class="invalid-feedback">
                                                    Campo Obrigatório.
                                                </div>
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label for="servico">Serviço</label>
                                                <select class="custom-select" id="servico" name="id_tipo_servico" required>
                                                    <option selected disabled value="">Escolhe...</option>
                                                    <?php
                                                    if (isset($this->dadosAlter)) {
                                                        foreach ($this->dadosAlter as $valor) {
                                                            ?>
                                                            <option value="<?php echo $valor['idtipo_servico']; ?>">
                                                                <?php echo $valor['nome']; ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="tipo_manutencao">Tipo de Manutenção</label>
                                                <select class="custom-select" id="tipo_manutencao" name="tipo_manutencao" required>
                                                    <option value="Corretiva" selected>Corretiva</option>
                                                    <option value="Preventiva">Preventiva</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="data_prevista">Data Prevista</label>
                                                <input type="date" class="form-control" id="data_prevista" name="data_prevista" value="<?php
                                                if (isset($valorForm['data_prevista'])) {
                                                    echo $valorForm['data_prevista'];
                                                }
                                                ?>">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label for="descricao">Descrição</label>
                                                <textarea class="form-control" id="descricao" name="descricao"
                                                    placeholder="Escreva A discrição da Peça" required><?php
                                                    if (isset($valorForm['descricao']) AND !empty($valorForm['descricao'])) {
                                                        echo $valorForm['descricao'];
                                                    }
                                                    ?>
                                                        </textarea>
                                                <div class="invalid-feedback">
                                                    Campo Obrigatório.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="data_entrega">Data da Entrega <span
                                                        class="text-muted"></span></label>
                                                <input type="date" class="form-control" id="data_entrada" name="data_entrega"
                                                    value="<?php
                                                    if (isset($valorForm['data_entrega'])) {
                                                        echo $valorForm['data_entrega'];
                                                    }
                                                    ?>" required>
                                                <div class="invalid-feedback">
                                                    Campo Obrigatório.
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="garantia">Garantia</label>
                                                <input type="number" class="form-control" id="tel1" placeholder="" min="0"
                                                    name="garantia" value="<?php
                                                    if (isset($valorForm['garantia'])) {
                                                        echo $valorForm['garantia'];
                                                    }
                                                    ?>" required>
                                                <div class="invalid-feedback">
                                                    Campo Obrigatório.
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="valor">Valor (Mão de Obra)</label>
                                                <input type="text" class="form-control" id="tel1"
                                                    placeholder="Valor da Mão de Obra" name="valor" value="<?php
                                                    if (isset($valorForm['valor'])) {
                                                        echo $valorForm['valor'];
                                                    }
                                                    ?>" required>
                                                <div class="invalid-feedback">
                                                    Campo Obrigatório.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label for="obs">Observação do Equipamento</label>
                                                <textarea class="form-control" id="obs" name="obs"
                                                    placeholder="Escreva A discrição da Peça"><?php
                                                    if (isset($valorForm['obs']) AND !empty($valorForm['obs'])) {
                                                        echo $valorForm['obs'];
                                                    }
                                                    ?></textarea>
                                                <div class="invalid-feedback">
                                                    Campo Obrigatório.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                        <input type="hidden" value="<?php echo $idOrcamento; ?>"
                                            name="idorcamentos" />
                                        <button class="btn btn-primary text-white" name="btnEditOrcamento">Salvar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!--  Modal Dados Do Fornecedor-->
                    <div class="modal fade" id="fornecedor<?php echo $idOrcamento; ?>" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                        <p><strong>Nome: </strong><?php echo $valorForm['fornecedor'] ?? ''; ?></p>
                                        <p><strong>Nif: </strong><?php echo $nifCliente; ?></p>
                                        <p><strong>Tipo Pessoa: </strong><?php echo $valorForm['tipo_pessoa'] ?? ''; ?></p>
                                        <p><strong>Telefone: </strong><?php echo $valorForm['telefone'] ?? ''; ?></p>
                                        <p><strong>Email: </strong><?php echo $valorForm['email'] ?? ''; ?></p>
                                        <p><strong>Andereço: </strong><?php echo $valorForm['morada'] ?? ''; ?></p>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="enviar<?php echo $idOrcamento; ?>" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content text-white">
                                <form action="" method="post" class="needs-validation" enctype="multipart/form-data" novalidate>
                                    <div class="modal-header bg-secondary">
                                        <h5 class="modal-title" id="exampleModalLabel">Enviar Relatório</h5>
                                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="text-gray-600"> Tens Certeza Que Queres Enviar Esse Relatório Ao Cliente??</p>
                                    </div>
                                    <div class="modal-footer bg-success">
                                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                        <input type="hidden" value="<?php echo $idOrcamento; ?>"
                                            name="idorcamentos" />
                                        <button class="btn btn-info text-white " name="btnEnviar">Enviar Relatório</button>
                                    </div>
                                </form>
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

