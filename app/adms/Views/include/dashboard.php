<!--MODAL-->
<!--  Modal Deletar-->
<!--  Modal Deletar-->
<div class="modal fade" id="orcamentodash" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="<?php echo URLADM; ?>dashboard" method="get" class="needs-validation" enctype="multipart/form-data" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Relatório de Orçamentos</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <input type="hidden" name="relatorio" value="sevicodash">
                        <div class="col-md-4">
                            <label for="data_inicio"> Data Inicio</label>
                            <input type="date" id="data_inicio" name="data_inicio" class="form-control" value="<?php echo date("Y-m-d"); ?>" required="">
                        </div>
                        <div class="col-md-4">
                            <label for="data_inicio"> Data Final</label>
                            <input type="date" id="data_inicio" name="data_final" class="form-control" value="<?php echo date("Y-m-d"); ?>" required="">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="validationCustom04">status</label>
                            <select class="custom-select" id="validationCustom04" name="status" required>
                                <option>Todos</option>
                                <option>Aberto</option>
                                <option>Aprovado</option>
                                <option>Concluído</option>
                            </select>
                            <div class="invalid-feedback">
                                Please select a valid status.
                            </div>
                        </div>
                        <input type="hidden" name="tipo" value="Orçamento">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-success" name="btnRelatorioOrcamento">Gerar Relatório</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--./modal Orcamento-->
<!--Modal Movimentacao-->
<!--  Modal Deletar-->
<!--  Modal Deletar-->
<div class="modal fade" id="servicodash" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="<?php echo URLADM; ?>dashboard" method="get" class="needs-validation" enctype="multipart/form-data" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Relatório de Serviços</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <input type="hidden" name="relatorio" value="sevicodash">
                        <div class="col-md-4">
                            <label for="data_inicio"> Data Inicio</label>
                            <input type="date" id="data_inicio" name="data_inicio" class="form-control" value="<?php echo date("Y-m-d"); ?>" required="">
                        </div>
                        <div class="col-md-4">
                            <label for="data_inicio"> Data Final</label>
                            <input type="date" id="data_inicio" name="data_final" class="form-control" value="<?php echo date("Y-m-d"); ?>" required="">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="validationCustom04">status</label>
                            <select class="custom-select" id="validationCustom04" name="status" required>
                                <option>Todos</option>
                                <option>Aberto</option>
                                <option>Aprovado</option>
                                <option>Concluído</option>
                            </select>
                            <div class="invalid-feedback">
                                Please select a valid status.
                            </div>
                        </div>
                        <input type="hidden" name="tipo" value="Servico">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-success" name="btnRelatorioServico">Gerar Relatório</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--  Modal Deletar-->
<div class="modal fade" id="movimentacao" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="<?php echo URLADM; ?>dashboard" method="get" class="needs-validation" enctype="multipart/form-data" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Relatório de Movimentação</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <input type="hidden" name="relatorio" value="movimentacao">
                        <div class="col-md-4">
                            <label for="data_inicio"> Data Inicio</label>
                            <input type="date" id="data_inicio" name="data_inicio" class="form-control" value="<?php echo date("Y-m-d"); ?>" required="">
                        </div>
                        <div class="col-md-4">
                            <label for="data_inicio"> Data Final</label>
                            <input type="date" id="data_inicio" name="data_final" class="form-control" value="<?php echo date("Y-m-d"); ?>" required="">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="validationCustom04">status</label>
                            <select class="custom-select" id="validationCustom04" name="status" required>
                                <option>Todos</option>
                                <option>Entrada</option>
                                <option>Saída</option>
                            </select>
                            <div class="invalid-feedback">
                                Please select a valid status.
                            </div>
                        </div>
                        <!--<input type="hidden" name="tipo" value="Servico">-->
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-success" name="btnRelatorioMovimentacao">Gerar Relatório</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--  Modal Deletar-->
<div class="modal fade" id="compras" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="<?php echo URLADM; ?>dashboard" method="get" class="needs-validation" enctype="multipart/form-data" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Relatório de Compras</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <input type="hidden" name="relatorio" value="compras">
                        <div class="col-md-6">
                            <label for="data_inicio"> Data Inicio</label>
                            <input type="date" id="data_inicio" name="data_inicio" class="form-control" value="<?php echo date("Y-m-d"); ?>" required="">
                        </div>
                        <div class="col-md-6">
                            <label for="data_inicio"> Data Final</label>
                            <input type="date" id="data_inicio" name="data_final" class="form-control" value="<?php echo date("Y-m-d"); ?>" required="">
                        </div>
                        <!--<input type="hidden" name="tipo" value="Orçamento">-->
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-success" name="btnRelatorioCompras">Gerar Relatório</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!--  Modal Deletar-->
<div class="modal fade" id="vendasr" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="<?php echo URLADM; ?>dashboard" method="get" class="needs-validation" enctype="multipart/form-data" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Relatório de Vendas</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <input type="hidden" name="relatorio" value="vendas">
                        <div class="col-md-6">
                            <label for="data_inicio"> Data Inicio</label>
                            <input type="date" id="data_inicio" name="data_inicio" class="form-control" value="<?php echo date("Y-m-d"); ?>" required="">
                        </div>
                        <div class="col-md-6">
                            <label for="data_inicio"> Data Final</label>
                            <input type="date" id="data_inicio" name="data_final" class="form-control" value="<?php echo date("Y-m-d"); ?>" required="">
                        </div>
                        <!--<input type="hidden" name="tipo" value="Orçamento">-->
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-success" name="btnRelatorioVendas">Gerar Relatório</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--  Modal Deletar-->
<div class="modal fade" id="conta_receber" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="<?php echo URLADM; ?>dashboard" method="get" class="needs-validation" enctype="multipart/form-data" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Relatório de Contas Á Receber</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <input type="hidden" name="relatorio" value="conta_receber">
                        <div class="col-md-6">
                            <label for="data_inicio"> Data Inicio</label>
                            <input type="date" id="data_inicio" name="data_inicio" class="form-control" value="<?php echo date("Y-m-d"); ?>" required="">
                        </div>
                        <div class="col-md-6">
                            <label for="data_inicio"> Data Final</label>
                            <input type="date" id="data_inicio" name="data_final" class="form-control" value="<?php echo date("Y-m-d"); ?>" required="">
                        </div>
                        <!--<input type="hidden" name="tipo" value="Orçamento">-->
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-success" name="btnRelatorioContaReceber">Gerar Relatório</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--  Modal Deletar-->
<div class="modal fade" id="conta_pagar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="<?php echo URLADM; ?>dashboard" method="get" class="needs-validation" enctype="multipart/form-data" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Relatório de Contas À Pagar</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <input type="hidden" name="relatorio" value="conta_pagar">
                        <div class="col-md-4">
                            <label for="data_inicio"> Data Inicio</label>
                            <input type="date" id="data_inicio" name="data_inicio" class="form-control" value="<?php echo date("Y-m-d"); ?>" required="">
                        </div>
                        <div class="col-md-4">
                            <label for="data_inicio"> Data Final</label>
                            <input type="date" id="data_inicio" name="data_final" class="form-control" value="<?php echo date("Y-m-d"); ?>" required="">
                        </div>
                        <!--<input type="hidden" name="tipo" value="Orçamento">-->
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-success" name="btnRelatorioContaPagar">Gerar Relatório</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--  Modal Deletar-->
<div class="modal fade" id="comissao" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="<?php echo URLADM; ?>dashboard" method="get" class="needs-validation" enctype="multipart/form-data" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Relatório de Comissões</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <input type="hidden" name="relatorio" value="comissao">
                        <div class="col-md-6">
                            <label for="data_inicio"> Data Inicio</label>
                            <input type="date" id="data_inicio" name="data_inicio" class="form-control" value="<?php echo date("Y-m-d"); ?>" required="">
                        </div>
                        <div class="col-md-6">
                            <label for="data_inicio"> Data Final</label>
                            <input type="date" id="data_inicio" name="data_final" class="form-control" value="<?php echo date("Y-m-d"); ?>" required="">
                        </div>
                        <!--<input type="hidden" name="tipo" value="Orçamento">-->
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-success" name="btnRelatorioComissao">Gerar Relatório</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="">
            <div class="sidebar-brand-icon">
                <div class="icon"><i class="icofont-user"></i></div>
            </div>
            <div class="sidebar-brand-text mx-3">
                <?php
                if (isset($_SESSION['usuario'])) {
                    if ($_SESSION['usuario'] == "adimin") {
                        echo 'Gerente';
                    } elseif ($_SESSION['usuario'] == "recep") {
                        echo "Recepcionista";
                    } elseif ($_SESSION['usuario'] == "tecnico") {
                        echo "Técnico";
                    }
                }
                ?>
            </div>
        </a>

        <!-- Divider -->
        <li class="nav-item">
            <a class="nav-link" href="<?php echo URLADM; ?>home">
                <i class="fas fa-fw fa-home"></i>
                <span>Home</span></a>
        </li>
        <?php
        //var_dump($_SESSION);
        if ($_SESSION['usuario'] == 'adimin') {
        ?>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Cadastrar
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="icon icofont-users"></i>
                    <span>Pessoas</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Pessoas</h6>
                        <a class="collapse-item" href="<?php echo URLADM; ?>tecnico">Técnicos</a>
                        <a class="collapse-item" href="<?php echo URLADM; ?>recepcionista">Recepsionistas</a>
                        <a class="collapse-item" href="<?php echo URLADM; ?>fornecedor">Fornecedores</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Manutenção Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseManutencao"
                    aria-expanded="true" aria-controls="collapseManutencao">
                    <i class="icon icofont-tools"></i>
                    <span>Manutenção</span>
                </a>
                <div id="collapseManutencao" class="collapse" aria-labelledby="headingManutencao" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Manutenção</h6>
                        <a class="collapse-item" href="<?php echo URLADM; ?>ocorrencia">Ocorrências</a>
                        <a class="collapse-item" href="<?php echo URLADM; ?>diagnostico">Diagnósticos</a>
                        <a class="collapse-item" href="<?php echo URLADM; ?>execucao">Execuções</a>
                        <a class="collapse-item" href="<?php echo URLADM; ?>planeamento">Planeamento Preventivo</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Comissões Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseComissoes"
                    aria-expanded="true" aria-controls="collapseComissoes">
                    <i class="icon icofont-money-bag"></i>
                    <span>Comissões</span>
                </a>
                <div id="collapseComissoes" class="collapse" aria-labelledby="headingComissoes" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Comissões</h6>
                        <a class="collapse-item" href="<?php echo URLADM; ?>comissaoConfig">Configurar %</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="icon icofont-plus"></i>
                    <span>Produtos</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Produtos</h6>
                        <a class="collapse-item" href="<?php echo URLADM; ?>categoria">Categorias de Peças</a>
                        <a class="collapse-item" href="<?php echo URLADM; ?>produto">Produtos</a>
                        <a class="collapse-item" href="<?php echo URLADM; ?>equipamento">Equipamentos</a>
                        <a class="collapse-item" href="<?php echo URLADM; ?>categoriaEquipamento">Categorias de Equipamentos</a>
                        <a class="collapse-item" href="<?php echo URLADM; ?>abatimento">Abatimentos</a>
                    </div>
                </div>
            </li>

              <!-- Heading -->
            <div class="sidebar-heading">
                Contas
            </div>
            <!-- COnta á pagar a receber menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwoContas"
                    aria-expanded="true" aria-controls="collapseTwoContas">
                    <i class="icon icofont-users"></i>
                    <span>Pagar e Receber</span>
                </a>
                <div id="collapseTwoContas" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Pessoas</h6>
                        <a class="collapse-item" href="<?php echo URLADM; ?>contasPagar">Contas à Pagar</a>
                        <a class="collapse-item" href="<?php echo URLADM; ?>contaReceber">Conta à Receber</a>
                    </div>
                </div>
            </li>
            <!-- Nav Item - Tables -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo URLADM; ?>tipoServico">
                    <i class="icon icofont-ui-settings"></i>
                    <span>Tipo Serviço</span></a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Consultas
            </div>

            <!-- Nav Item - Charts -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo URLADM; ?>estoque">
                    <i class="fas fa-fw fa-chart-area text-warning"></i>
                    <span>Estoque Baixo</span></a>
            </li>

            <!-- Nav Item - Compras -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo URLADM; ?>compras">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Compras</span></a>
            </li>

            <!-- Nav Item - Compras -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo URLADM; ?>vendas">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Vendas</span></a>
            </li>


            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#consultas"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-search"></i>
                    <span>Consultas</span>
                </a>
                <div id="consultas" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Consultar:</h6>
                        <a class="collapse-item" href="<?php echo URLADM; ?>orcamentoRecepcao">Orçamentos</a>
                        <a class="collapse-item" href="<?php echo URLADM; ?>consultas?value=servico">Serviço</a>
                        <a class="collapse-item" href="<?php echo URLADM; ?>movimentacao">Movimentação</a>
                        <a class="collapse-item" href="<?php echo URLADM; ?>compras">Compras</a>
                        <a class="collapse-item" href="<?php echo URLADM; ?>entradaEquipamento">Entrada Equipamentos</a>

                    </div>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Relatório</span>
                </a>
                <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Relatórios:</h6>
                        <a class="collapse-item" href="#" data-toggle="modal" data-target="#servicodash">Serviços</a>
                        <a class="collapse-item" href="" data-toggle="modal" data-target="#orcamentodash">Orçamentos</a>
                        <a class="collapse-item" href="" data-toggle="modal" data-target="#movimentacao">Movimentação</a>
                        <a class="collapse-item" href="#" data-toggle="modal" data-target="#conta_pagar">Contas à Pagar</a>
                        <a class="collapse-item" href="" data-toggle="modal" data-target="#conta_receber">Contas à Receber</a>
                        <a class="collapse-item" href="" data-toggle="modal" data-target="#compras"> Compras</a>
                        <a class="collapse-item" href="" data-toggle="modal" data-target="#vendasr">Vendas</a>
                        <a class="collapse-item" href="<?php echo URLADM ?>relatorio?value=rveiculo">Equipamentos Oficina</a>
                        <a class="collapse-item" href="<?php echo URLADM ?>relatorio?value=catalogoProduto">Catalogo Produtos</a>

                    </div>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#estatistica"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-chart-area text-warning"></i>
                    <span>Estatística</span>
                </a>
                <div id="estatistica" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Consultar:</h6>
                        <a class="collapse-item" href="<?php echo URLADM; ?>graficos?value=entrada_movimentacao">Movimentação Entrada</a>
                        <a class="collapse-item" href="<?php echo URLADM; ?>graficos?value=saida_movimentacao">Movimentação Saída </a>
                        <a class="collapse-item" href="<?php echo URLADM; ?>graficos?value=servico_maisprestados">Serviços Mais prestado</a>
                    </div>
                </div>
            </li>
        <?php } elseif ($_SESSION['usuario'] == 'recep') {
        ?>
            <!-- Heading -->
            <div class="sidebar-heading">
                Contas
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="icon icofont-users"></i>
                    <span>Pagar e Receber</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Pessoas</h6>
                        <a class="collapse-item" href="<?php echo URLADM; ?>contasPagar">Contas à Pagar</a>
                        <a class="collapse-item" href="<?php echo URLADM; ?>contaReceber">Conta à Receber</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="icon icofont-plus"></i>
                    <span>Cadastro</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Cadastro</h6>
                        <a class="collapse-item" href="<?php echo URLADM; ?>cliente">Clientes</a>
                        <a class="collapse-item" href="<?php echo URLADM; ?>equipamento">Equipamentos</a>
                        <a class="collapse-item" href="<?php echo URLADM; ?>ocorrencia">Ocorrências</a>
                        <a class="collapse-item" href="<?php echo URLADM; ?>abatimento">Abatimentos</a>
                    </div>
                </div>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Consultas
            </div>

            <!-- Nav Item - Charts -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo URLADM; ?>movimentacao">
                    <i class="fas fa-fw fa-dollar-sign text-warning"></i>
                    <span>Movimentação</span></a>
            </li>

            <!-- Nav Item - Compras -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo URLADM; ?>compras">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Compras</span></a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#consultas"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-search"></i>
                    <span>Consultas</span>
                </a>
                <div id="consultas" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Consultar:</h6>
                        <a class="collapse-item" href="<?php echo URLADM; ?>orcamentoRecepcao">Orçamentos</a>
                        <a class="collapse-item" href="<?php echo URLADM; ?>consultas?value=servico">Serviço</a>
                        <a class="collapse-item" href="<?php echo URLADM; ?>movimentacao">Movimentação</a>
                        <a class="collapse-item" href="<?php echo URLADM; ?>compras">Compras</a>
                        <a class="collapse-item" href="<?php echo URLADM; ?>entradaEquipamento">Entrada Equipamentos</a>
                    </div>
                </div>
            </li>
            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Relatório</span>
                </a>
                <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Relatórios:</h6>
                        <a class="collapse-item" href="#" data-toggle="modal" data-target="#servicodash">Serviços</a>
                        <a class="collapse-item" href="" data-toggle="modal" data-target="#orcamentodash">Orçamentos</a>
                        <a class="collapse-item" href="" data-toggle="modal" data-target="#movimentacao">Movimentação</a>
                        <a class="collapse-item" href="#" data-toggle="modal" data-target="#conta_pagar">Contas à Pagar</a>
                        <a class="collapse-item" href="" data-toggle="modal" data-target="#conta_receber">Contas à Receber</a>
                        <a class="collapse-item" href="" data-toggle="modal" data-target="#compras"> Compras</a>
                        <a class="collapse-item" href="" data-toggle="modal" data-target="#vendasr">Vendas</a>
                        <a class="collapse-item" href="<?php echo URLADM ?>relatorio?value=rveiculo">Equipamentos Oficina</a>
                        <a class="collapse-item" href="<?php echo URLADM ?>relatorio?value=catalogoProduto">Catalogo Produtos</a>

                    </div>
                </div>
            </li>

        <?php } elseif ($_SESSION['usuario'] == 'tecnico') {
        ?>
            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Manutenção
            </div>
            <!-- Nav Item - Ocorrências -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo URLADM; ?>ocorrencia">
                    <i class="icon icofont-tools"></i>
                    <span>Ocorrências</span></a>
            </li>
            <!-- Nav Item - Diagnósticos -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo URLADM; ?>diagnostico">
                    <i class="icon icofont-stethoscope"></i>
                    <span>Diagnósticos</span></a>
            </li>
            <!-- Nav Item - Execuções -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo URLADM; ?>execucao">
                    <i class="icon icofont-tools"></i>
                    <span>Execuções</span></a>
            </li>
            <!-- Nav Item - Abatimentos -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo URLADM; ?>abatimento">
                    <i class="icon icofont-archive"></i>
                    <span>Abatimentos</span></a>
            </li>
            <!-- Nav Item - Planeamento Preventivo -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo URLADM; ?>planeamento">
                    <i class="icon icofont-calendar"></i>
                    <span>Planeamento Preventivo</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Orçamentos e Serviços
            </div>
            <!-- Nav Item - Compras -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo URLADM; ?>orcamento">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Orçamentos</span></a>
            </li>
            <!-- Nav Item - Compras -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo URLADM; ?>servico">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Serviços</span></a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Consultas
            </div>
            <!-- Nav Item - Compras -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo URLADM; ?>comissoes">
                    <i class="fas fa-fw fa-dollar-sign text-warning"></i>
                    <span>Comissões</span></a>
            </li>
            <!-- Nav Item - Compras -->
            <li class="nav-item">
                <a class="nav-link" href="" href="#" data-toggle="modal" data-target="#comissao">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Relatório Comissão</span></a>
            </li>

        <?php
        } else {
            $_SESSION['msg'] = '
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                       <strong>Erro:!</strong> Tipo De Conta  ' . $this->resultadoBd["nivel"] . '  Não está fazer control dos dados.
                           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                               <span aria-hidden="true">&times;</span>
                           </button>
                   </div>
                  ';
            $destino = URLADM . "sair";
            header("Location: $destino");
        }
        ?>
        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar p-5 mb-4 static-top shadow">

                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>

                <!-- Topbar Search -->
                <img src="<?php echo URLADM; ?>app/adms/assets/imagens/login/logo_novo.png" width="150" height="55" />

                <!-- Topbar Navbar -->
                <ul class="navbar-nav ml-auto">

                    <!-- Nav Item - Search Dropdown (Visible Only XS) -->




                    <div class="topbar-divider d-none d-sm-block"></div>

                    <!-- Nav Item - User Information -->
                    <li class="nav-item dropdown no-arrow p-4">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small" style="font-size: 16px;"><?php
                                                                                                                if (isset($_SESSION['nome']) && isset($_SESSION['sobrenome'])) {
                                                                                                                    echo $_SESSION['nome'] . " " . $_SESSION['sobrenome'];
                                                                                                                }
                                                                                                                ?></span>
                            <img class="img-profile rounded-circle"
                                src="<?php echo URLADM; ?>app/adms/assets/foto/<?php
                                                                                if (isset($_SESSION['foto'])) {
                                                                                    echo $_SESSION['foto'];
                                                                                } else {
                                                                                    echo "img_avatar3.png";
                                                                                }
                                                                                ?>">
                        </a>
                        <!-- Dropdown - User Information -->
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                            aria-labelledby="userDropdown">
                            <!--<a class="dropdown-item" href="#" data-toggle="modal" data-target="#editperfil<?php echo $_SESSION['idlogado']; ?>">
                                <i class="fas fa-eye fa-sm fa-fw mr-2 text-gray-400" style="color: blue!important" ></i>
                                Ver Perfil
                            </a>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#editperfil<?php echo $_SESSION['idlogado']; ?>">
                                <i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400" style="color: blue!important" ></i>
                                Editar Perfil
                            </a>-->
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#editperfil<?php echo $_SESSION['idlogado']; ?>">
                                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400" style="color: blue!important"></i>
                                Editar Foto de Perfil
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="" data-toggle="modal" data-target="#logoutModal">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400" style="color: red!important"></i>
                                Terminar Sessão
                            </a>
                        </div>
                    </li>

                </ul>

            </nav>
            <!-- End of Topbar -->