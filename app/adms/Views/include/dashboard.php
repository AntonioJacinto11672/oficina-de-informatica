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
        <li class="nav-item">
            <a class="nav-link" href="<?php echo URLADM; ?>home">
                <i class="fas fa-fw fa-home"></i>
                <span>Dashboard</span></a>
        </li>

        <?php if ($_SESSION['nivel'] === 'gerente') { ?>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Cadastros
            </div>

            <li class="nav-item">
                <a class="nav-link" href="<?php echo URLADM; ?>utilizador">
                    <i class="icon icofont-key"></i>
                    <span>Utilizadores</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo URLADM; ?>tecnico">
                    <i class="icon icofont-users-alt-4"></i>
                    <span>Técnicos</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo URLADM; ?>departamento">
                    <i class="icon icofont-building-alt"></i>
                    <span>Departamentos</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo URLADM; ?>equipamento">
                    <i class="icon icofont-laptop"></i>
                    <span>Equipamentos</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo URLADM; ?>fornecedor">
                    <i class="icon icofont-truck"></i>
                    <span>Fornecedores</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo URLADM; ?>tipoManutencao">
                    <i class="icon icofont-settings-alt"></i>
                    <span>Tipos de Manutenção</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePecas"
                    aria-expanded="false" aria-controls="collapsePecas">
                    <i class="icon icofont-plus"></i>
                    <span>Peças e Consumíveis</span>
                </a>
                <div id="collapsePecas" class="collapse" aria-labelledby="headingPecas" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Peças e Consumíveis</h6>
                        <a class="collapse-item" href="<?php echo URLADM; ?>categoria">Categorias</a>
                        <a class="collapse-item" href="<?php echo URLADM; ?>produto">Peças e Consumíveis</a>
                        <a class="collapse-item" href="<?php echo URLADM; ?>categoriaEquipamento">Categorias de Equipamento</a>
                    </div>
                </div>
            </li>

            <!-- Heading -->
            <div class="sidebar-heading">
                Manutenção
            </div>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo URLADM; ?>ocorrencia">
                    <i class="icon icofont-exclamation-circle"></i>
                    <span>Ocorrências</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo URLADM; ?>diagnostico">
                    <i class="icon icofont-stethoscope"></i>
                    <span>Diagnósticos</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo URLADM; ?>execucao">
                    <i class="icon icofont-tools"></i>
                    <span>Execuções</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo URLADM; ?>planeamento">
                    <i class="icon icofont-calendar"></i>
                    <span>Planeamento Preventivo</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo URLADM; ?>ocorrencia">
                    <i class="icon icofont-clipboard"></i>
                    <span>Ordens de Manutenção</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo URLADM; ?>historico">
                    <i class="icon icofont-history"></i>
                    <span>Histórico</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo URLADM; ?>abatimento">
                    <i class="icon icofont-archive"></i>
                    <span>Abatimento de Equipamentos</span></a>
            </li>

            <!-- Heading -->
            <div class="sidebar-heading">
                Stock
            </div>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo URLADM; ?>movimentoEstoque?tipo=Entrada">
                    <i class="icon icofont-download"></i>
                    <span>Entradas</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo URLADM; ?>movimentoEstoque?tipo=Saida">
                    <i class="icon icofont-upload"></i>
                    <span>Saídas</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo URLADM; ?>compras">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Compras</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo URLADM; ?>estoque">
                    <i class="fas fa-fw fa-chart-area text-warning"></i>
                    <span>Stock Baixo</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <li class="nav-item">
                <a class="nav-link" href="<?php echo URLADM; ?>relatorio">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Relatórios</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo URLADM; ?>graficos">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Estatísticas</span></a>
            </li>
            <li class="nav-item">
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
            <li class="nav-item">
                <a class="nav-link" href="<?php echo URLADM; ?>ocorrencia">
                    <i class="icon icofont-exclamation-circle"></i>
                    <span>Ocorrências</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo URLADM; ?>diagnostico">
                    <i class="icon icofont-stethoscope"></i>
                    <span>Diagnósticos</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo URLADM; ?>execucao">
                    <i class="icon icofont-tools"></i>
                    <span>Execuções</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo URLADM; ?>planeamento">
                    <i class="icon icofont-calendar"></i>
                    <span>Planeamento Preventivo</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo URLADM; ?>equipamento">
                    <i class="icon icofont-laptop"></i>
                    <span>Equipamentos</span></a>
            </li>
            <li class="nav-item">
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
            <nav class="navbar navbar-expand navbar-light bg-white topbar p-5 mb-4 static-top shadow">

                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>

                <img src="<?php echo URLADM; ?>app/adms/assets/imagens/login/logo_novo.png" width="150" height="55" alt="Universidade Lusíada de Angola" />

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
