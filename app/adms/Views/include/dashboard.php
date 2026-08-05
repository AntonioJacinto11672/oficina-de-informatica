<?php
// Rota actual (controlador pedido em ?url=...) — usada para realçar o item de
// menu correspondente e para abrir automaticamente o submenu que o contém.
$rotaAtual = filter_input(INPUT_GET, 'url', FILTER_DEFAULT) ?: 'home';

// Devolve ' active' se $rotas contiver a rota actual (comparação exacta do
// nome do controlador, ignorando querystring).
$ehActiva = function (array $rotas) use ($rotaAtual): bool {
    return in_array($rotaAtual, $rotas, true);
};
?>
<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo URLADM; ?>home">
            <div class="sidebar-brand-icon">
                <div class="icon"><i class="icofont-user"></i></div>
            </div>
            <div class="sidebar-brand-text mx-3">
                <?php echo $_SESSION['nivel'] === 'gerente' ? 'Gerente' : 'Técnico'; ?>
            </div>
        </a>

        <!-- Dashboard -->
        <li class="nav-item<?php echo $ehActiva(['home']) ? ' active' : ''; ?>">
            <a class="nav-link" href="<?php echo URLADM; ?>home">
                <i class="fas fa-fw fa-home"></i>
                <span>Dashboard</span></a>
        </li>

        <?php if ($_SESSION['nivel'] === 'gerente') {
            $rotasCadastros = ['utilizador', 'tecnico', 'departamento', 'equipamento', 'fornecedor', 'tipoManutencao', 'categoria', 'produto', 'categoriaEquipamento'];
            $rotasManutencao = ['ocorrencia', 'diagnostico', 'execucao', 'planeamento', 'historico', 'abatimento'];
            $rotasStock = ['movimentoEstoque', 'compras', 'estoque'];
            ?>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Cadastros -->
            <li class="nav-item<?php echo $ehActiva($rotasCadastros) ? ' active' : ''; ?>">
                <a class="nav-link<?php echo $ehActiva($rotasCadastros) ? '' : ' collapsed'; ?>" href="#" data-toggle="collapse" data-target="#collapseCadastros"
                    aria-expanded="<?php echo $ehActiva($rotasCadastros) ? 'true' : 'false'; ?>" aria-controls="collapseCadastros">
                    <i class="icon icofont-file-text"></i>
                    <span>Cadastros</span>
                </a>
                <div id="collapseCadastros" class="collapse<?php echo $ehActiva($rotasCadastros) ? ' show' : ''; ?>" aria-labelledby="headingCadastros" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Cadastros</h6>
                        <a class="collapse-item<?php echo $ehActiva(['utilizador']) ? ' active' : ''; ?>" href="<?php echo URLADM; ?>utilizador"><i class="icon icofont-key mr-1"></i> Utilizadores</a>
                        <a class="collapse-item<?php echo $ehActiva(['tecnico']) ? ' active' : ''; ?>" href="<?php echo URLADM; ?>tecnico"><i class="icon icofont-users-alt-4 mr-1"></i> Técnicos</a>
                        <a class="collapse-item<?php echo $ehActiva(['departamento']) ? ' active' : ''; ?>" href="<?php echo URLADM; ?>departamento"><i class="icon icofont-building-alt mr-1"></i> Departamentos</a>
                        <a class="collapse-item<?php echo $ehActiva(['equipamento']) ? ' active' : ''; ?>" href="<?php echo URLADM; ?>equipamento"><i class="icon icofont-laptop mr-1"></i> Equipamentos</a>
                        <a class="collapse-item<?php echo $ehActiva(['fornecedor']) ? ' active' : ''; ?>" href="<?php echo URLADM; ?>fornecedor"><i class="icon icofont-truck mr-1"></i> Fornecedores</a>
                        <a class="collapse-item<?php echo $ehActiva(['tipoManutencao']) ? ' active' : ''; ?>" href="<?php echo URLADM; ?>tipoManutencao"><i class="icon icofont-settings-alt mr-1"></i> Tipos de Manutenção</a>
                        <div class="dropdown-divider"></div>
                        <a class="collapse-item<?php echo $ehActiva(['categoriaEquipamento']) ? ' active' : ''; ?>" href="<?php echo URLADM; ?>categoriaEquipamento">Categorias de <br>Equipamento</a>
                        <a class="collapse-item<?php echo $ehActiva(['categoria']) ? ' active' : ''; ?>" href="<?php echo URLADM; ?>categoria">Categorias de Peças</a>
                        <a class="collapse-item<?php echo $ehActiva(['produto']) ? ' active' : ''; ?>" href="<?php echo URLADM; ?>produto">Peças e Consumíveis</a>
                    </div>
                </div>
            </li>

            <!-- Manutenção -->
            <li class="nav-item<?php echo $ehActiva($rotasManutencao) ? ' active' : ''; ?>">
                <a class="nav-link<?php echo $ehActiva($rotasManutencao) ? '' : ' collapsed'; ?>" href="#" data-toggle="collapse" data-target="#collapseManutencao"
                    aria-expanded="<?php echo $ehActiva($rotasManutencao) ? 'true' : 'false'; ?>" aria-controls="collapseManutencao">
                    <i class="icon icofont-tools-bag"></i>
                    <span>Manutenção</span>
                </a>
                <div id="collapseManutencao" class="collapse<?php echo $ehActiva($rotasManutencao) ? ' show' : ''; ?>" aria-labelledby="headingManutencao" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Manutenção</h6>
                        <a class="collapse-item<?php echo $ehActiva(['ocorrencia']) ? ' active' : ''; ?>" href="<?php echo URLADM; ?>ocorrencia"><i class="icon icofont-exclamation-circle mr-1"></i> Ocorrências</a>
                        <a class="collapse-item<?php echo $ehActiva(['diagnostico']) ? ' active' : ''; ?>" href="<?php echo URLADM; ?>diagnostico"><i class="icon icofont-stethoscope mr-1"></i> Diagnósticos</a>
                        <a class="collapse-item<?php echo $ehActiva(['execucao']) ? ' active' : ''; ?>" href="<?php echo URLADM; ?>execucao"><i class="icon icofont-ui-settings mr-1"></i> Execuções</a>
                        <a class="collapse-item<?php echo $ehActiva(['planeamento']) ? ' active' : ''; ?>" href="<?php echo URLADM; ?>planeamento"><i class="icon icofont-calendar mr-1"></i> Planeamento <br>s Preventivo</a>
                        <a class="collapse-item<?php echo $ehActiva(['historico']) ? ' active' : ''; ?>" href="<?php echo URLADM; ?>historico"><i class="icon icofont-history mr-1"></i> Histórico</a>
                        <a class="collapse-item<?php echo $ehActiva(['abatimento']) ? ' active' : ''; ?>" href="<?php echo URLADM; ?>abatimento"><i class="icon icofont-archive mr-1"></i> Abatimento de <br> Equipamentos</a>
                    </div>
                </div>
            </li>

            <!-- Stock -->
            <li class="nav-item<?php echo $ehActiva($rotasStock) ? ' active' : ''; ?>">
                <a class="nav-link<?php echo $ehActiva($rotasStock) ? '' : ' collapsed'; ?>" href="#" data-toggle="collapse" data-target="#collapseStock"
                    aria-expanded="<?php echo $ehActiva($rotasStock) ? 'true' : 'false'; ?>" aria-controls="collapseStock">
                    <i class="icon icofont-box"></i>
                    <span>Stock</span>
                </a>
                <div id="collapseStock" class="collapse<?php echo $ehActiva($rotasStock) ? ' show' : ''; ?>" aria-labelledby="headingStock" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Stock</h6>
                        <a class="collapse-item" href="<?php echo URLADM; ?>movimentoEstoque?tipo=Entrada"><i class="icon icofont-download mr-1"></i> Entradas</a>
                        <a class="collapse-item" href="<?php echo URLADM; ?>movimentoEstoque?tipo=Saida"><i class="icon icofont-upload mr-1"></i> Saídas</a>
                        <a class="collapse-item<?php echo $ehActiva(['compras']) ? ' active' : ''; ?>" href="<?php echo URLADM; ?>compras"><i class="fas fa-fw fa-table mr-1"></i> Compras</a>
                        <a class="collapse-item<?php echo $ehActiva(['estoque']) ? ' active' : ''; ?>" href="<?php echo URLADM; ?>estoque"><i class="fas fa-fw fa-chart-area text-warning mr-1"></i> Stock Baixo</a>
                    </div>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <li class="nav-item<?php echo $ehActiva(['relatorio']) ? ' active' : ''; ?>">
                <a class="nav-link" href="<?php echo URLADM; ?>relatorio">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Relatórios</span></a>
            </li>
            <li class="nav-item<?php echo $ehActiva(['graficos']) ? ' active' : ''; ?>">
                <a class="nav-link" href="<?php echo URLADM; ?>graficos">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Estatísticas</span></a>
            </li>
            <li class="nav-item<?php echo $ehActiva(['configuracoes']) ? ' active' : ''; ?>">
                <a class="nav-link" href="<?php echo URLADM; ?>configuracoes">
                    <i class="icon icofont-settings"></i>
                    <span>Configurações</span></a>
            </li>

        <?php } elseif ($_SESSION['nivel'] === 'tecnico') { ?>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Manutenção
            </div>
            <li class="nav-item<?php echo $ehActiva(['ocorrencia']) ? ' active' : ''; ?>">
                <a class="nav-link" href="<?php echo URLADM; ?>ocorrencia">
                    <i class="icon icofont-exclamation-circle"></i>
                    <span>Ocorrências</span></a>
            </li>
            <li class="nav-item<?php echo $ehActiva(['diagnostico']) ? ' active' : ''; ?>">
                <a class="nav-link" href="<?php echo URLADM; ?>diagnostico">
                    <i class="icon icofont-stethoscope"></i>
                    <span>Diagnósticos</span></a>
            </li>
            <li class="nav-item<?php echo $ehActiva(['execucao']) ? ' active' : ''; ?>">
                <a class="nav-link" href="<?php echo URLADM; ?>execucao">
                    <i class="icon icofont-tools"></i>
                    <span>Execuções</span></a>
            </li>
            <li class="nav-item<?php echo $ehActiva(['planeamento']) ? ' active' : ''; ?>">
                <a class="nav-link" href="<?php echo URLADM; ?>planeamento">
                    <i class="icon icofont-calendar"></i>
                    <span>Planeamento Preventivo</span></a>
            </li>
            <li class="nav-item<?php echo $ehActiva(['equipamento']) ? ' active' : ''; ?>">
                <a class="nav-link" href="<?php echo URLADM; ?>equipamento">
                    <i class="icon icofont-laptop"></i>
                    <span>Equipamentos</span></a>
            </li>
            <li class="nav-item<?php echo $ehActiva(['historico']) ? ' active' : ''; ?>">
                <a class="nav-link" href="<?php echo URLADM; ?>historico">
                    <i class="icon icofont-history"></i>
                    <span>Histórico</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <li class="nav-item">
                <a class="nav-link" href="#" data-toggle="modal" data-target="#editperfil<?php echo $_SESSION['idlogado']; ?>">
                    <i class="icon icofont-user"></i>
                    <span>Perfil</span></a>
            </li>

        <?php
        } else {
            $_SESSION['msg'] = '
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                       <strong>Erro:</strong> Papel de utilizador desconhecido. Contacte o Gerente de TI.
                           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                               <span aria-hidden="true">&times;</span>
                           </button>
                   </div>
                  ';
            $destino = URLADM . "sair";
            header("Location: $destino");
            exit;
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
            <nav class="navbar navbar-expand navbar-light bg-white topbar p-3 p-md-5 mb-4 static-top shadow">

                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>

                <img src="<?php echo URLADM; ?>app/adms/assets/imagens/login/logo_novo.png" class="img-fluid" style="max-width:150px;height:auto;" alt="Universidade Lusíada de Angola" />

                <!-- Topbar Navbar -->
                <ul class="navbar-nav ml-auto">

                    <div class="topbar-divider d-none d-sm-block"></div>

                    <!-- Nav Item - User Information -->
                    <li class="nav-item dropdown no-arrow p-4">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small" style="font-size: 16px;"><?php
                                if (isset($_SESSION['nome']) && isset($_SESSION['sobrenome'])) {
                                    echo htmlspecialchars($_SESSION['nome'] . " " . $_SESSION['sobrenome']);
                                }
                                ?></span>
                            <img class="img-profile rounded-circle"
                                src="<?php echo URLADM; ?>app/adms/assets/foto/<?php
                                    echo htmlspecialchars($_SESSION['foto'] ?? 'img_avatar3.png');
                                ?>">
                        </a>
                        <!-- Dropdown - User Information -->
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                            aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#editperfil<?php echo $_SESSION['idlogado']; ?>">
                                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400" style="color: blue!important"></i>
                                Editar Perfil
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
