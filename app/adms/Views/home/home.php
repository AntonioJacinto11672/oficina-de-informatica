<?php
if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}
?>
<!--Begin Page Content -->
<div class = "container-fluid">
    <?php
    //var_dump($_SESSION);

    if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }

    if ($_SESSION['usuario'] == 'adimin') {
        //var_dump($this->dadosAlter);
        ?>
        <!-- Page Heading -->
        <!--<div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800 section-title">Cards</h1>
        </div>-->

        <div class="row">

            <!-- Earnings (Annual) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Entrada do Dia</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                    if (isset($this->dados['entrada_dia'])) {
                                        echo number_format($this->dados['entrada_dia'], 2, ",", ".");
                                    } else {
                                        echo 0;
                                    }
                                    ?> Kz</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-3x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Earnings (Annual) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Saída do Dia</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                    if (isset($this->dados['saida_dia'])) {
                                        echo number_format($this->dados['saida_dia'], 2, ",", ".");
                                    } else {
                                        echo 0;
                                    }
                                    ?> Kz</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-3x text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Earnings (Annual) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Saldo do Dia</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                    if (isset($this->dados['saldo_dia'])) {
                                        echo number_format($this->dados['saldo_dia'], 2, ",", ".");
                                    } else {
                                        echo 0;
                                    }
                                    ?> Kz</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-3x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Earnings (Annual) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Saldo do Mês</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                    if (isset($this->dados['saldo_mes'])) {
                                        echo number_format($this->dados['saldo_mes'], 2, ",", ".");
                                    } else {
                                        echo 0;
                                    }
                                    ?> Kz</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-3x text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>





        </div>

        <div class="row">

            <!-- Earnings (Annual) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Orçamentos Concluidos</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                    if (isset($this->dados['orc_concluidos'])) {
                                        echo $this->dados['orc_concluidos'];
                                    } else {
                                        echo 0;
                                    }
                                    ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-3x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Earnings (Annual) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Orçamentos Pendentes</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                    if (isset($this->dados['orc_pendentes'])) {
                                        echo $this->dados['orc_pendentes'];
                                    } else {
                                        echo 0;
                                    }
                                    ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-3x text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Earnings (Annual) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Orçamento Aprovados</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                    if (isset($this->dados['orc_aprovados'])) {
                                        echo $this->dados['orc_aprovados'];
                                    } else {
                                        echo 0;
                                    }
                                    ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-3x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Earnings (Annual) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Serviço Pendente</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                    if (isset($this->dados['serv_pendentes'])) {
                                        echo $this->dados['serv_pendentes'];
                                    } else {
                                        echo 0;
                                    }
                                    ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-3x text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>





        </div>

        <?php if (!empty($this->dados['ocorrencias_preventivas'])): ?>
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Manutenções Preventivas Próximas</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <?php foreach ($this->dados['ocorrencias_preventivas'] as $ocorrencia): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?php echo htmlspecialchars($ocorrencia['numero_serie'] ?? 'Sem número de série'); ?></strong>
                                        <div class="small text-muted">
                                            <?php echo htmlspecialchars($ocorrencia['tipo_servico'] ?? 'Serviço'); ?> ·
                                            <?php echo htmlspecialchars($ocorrencia['tipo_manutencao'] ?? 'Corretiva'); ?>
                                        </div>
                                    </div>
                                    <span class="badge badge-warning text-dark">
                                        Prevista: <?php echo date('d/m/Y', strtotime($ocorrencia['data_prevista'])); ?>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="row">

            <!-- Earnings (Annual) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Produtos Cadastrados</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                    if (isset($this->dados['produto'])) {
                                        echo $this->dados['produto'];
                                    } else {
                                        echo 0;
                                    }
                                    ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-3x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Earnings (Annual) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Total Cliente</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                    if (isset($this->dados['clientes'])) {
                                        echo $this->dados['clientes'];
                                    } else {
                                        echo 0;
                                    }
                                    ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-3x text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Earnings (Annual) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total Técnicos</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                    if (isset($this->dados['mecanicos'])) {
                                        echo $this->dados['mecanicos'];
                                    } else {
                                        echo 0;
                                    }
                                    ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-3x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Earnings (Annual) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Total de Recepcionista</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                    if (isset($this->dados['recepcionista'])) {
                                        echo $this->dados['recepcionista'];
                                    } else {
                                        echo 0;
                                    }
                                    ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-3x text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    <?php } elseif ($_SESSION['usuario'] == 'recep') {
        ?>
        <div class="row">

            <!-- Earnings (Annual) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Entrada do Dia</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                    if (isset($this->dados['entrada_dia'])) {
                                        echo number_format($this->dados['entrada_dia'], 2, ",", ".");
                                    } else {
                                        echo 0;
                                    }
                                    ?> Kz</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-3x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Earnings (Annual) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Saída do Dia</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                    if (isset($this->dados['saida_dia'])) {
                                        echo number_format($this->dados['saida_dia'], 2, ",", ".");
                                    } else {
                                        echo 0;
                                    }
                                    ?> Kz</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-3x text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Earnings (Annual) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Saldo do Dia</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                    if (isset($this->dados['saldo_dia'])) {
                                        echo number_format($this->dados['saldo_dia'], 2, ",", ".");
                                    } else {
                                        echo 0;
                                    }
                                    ?> Kz</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-3x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Earnings (Annual) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Saldo do Mês</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                    if (isset($this->dados['saldo_mes'])) {
                                        echo number_format($this->dados['saldo_mes'], 2, ",", ".");
                                    } else {
                                        echo 0;
                                    }
                                    ?> Kz</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-3x text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>





        </div>

        <div class="row">

            <!-- Earnings (Annual) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Orçamentos Concluidos</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                    if (isset($this->dados['orc_concluidos'])) {
                                        echo $this->dados['orc_concluidos'];
                                    } else {
                                        echo 0;
                                    }
                                    ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-3x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Earnings (Annual) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Orçamentos Pendentes</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                    if (isset($this->dados['orc_pendentes'])) {
                                        echo $this->dados['orc_pendentes'];
                                    } else {
                                        echo 0;
                                    }
                                    ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-3x text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Earnings (Annual) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Orçamento Aprovados</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                    if (isset($this->dados['orc_aprovados'])) {
                                        echo $this->dados['orc_aprovados'];
                                    } else {
                                        echo 0;
                                    }
                                    ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-3x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Earnings (Annual) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Serviço Pendente</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                    if (isset($this->dados['serv_pendentes'])) {
                                        echo $this->dados['serv_pendentes'];
                                    } else {
                                        echo 0;
                                    }
                                    ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-3x text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>





        </div>
    <?php } elseif ($_SESSION['usuario'] == 'tecnico') {
        ?>
        <div class="row">

            <!-- Earnings (Annual) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Serviços Concluídos</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                    if (isset($this->dados['orc_concluidos'])) {
                                        echo $this->dados['orc_concluidos'];
                                    }
                                    ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-3x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Earnings (Annual) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Orçamentos Abertos</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                    if (isset($this->dados['serv_pendentes'])) {
                                        echo $this->dados['serv_pendentes'];
                                    }
                                    ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-3x text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Earnings (Annual) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Comissões Hoje</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                    if (isset($this->dados['comissao_hoje'])) {
                                        echo number_format($this->dados['comissao_hoje'], 2, ",", ".");
                                    } else {
                                        echo 0;
                                    }
                                    ?> Kz</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-3x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Earnings (Annual) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Comissões Mês</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                    if (isset($this->dados['comissao_mes'])) {
                                        echo number_format($this->dados['comissao_mes'], 2, ",", ".");
                                    } else {
                                        echo 0;
                                    }
                                    ?> Kz</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-3x text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>





        </div>

        <p class="text-muted lead">Serviços Pendentes</p>
        <hr class="mb-3">

        <div class="row">
            <?php
            if (isset($this->dados['veiculos_atrasado'])) {
                foreach ($this->dados['veiculos_atrasado'] as $value) {
                    ?>

                    <!-- Earnings (Annual) Card Example -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-danger shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                            <?php echo $value['marca'] . " - " . $value['modelo']; ?>    
                                        </div>
                                        <small><?php echo $value['tipo_servico']; ?></small>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"></div>
                                    </div>
                                    <div class="col-auto">

                                        <i class="fas fa-clipboard-list fa-3x text-danger mb-1"></i><br>
                                        <div><small class="text-center float-left row justify-content-center"><?php echo date("d/m/Y", strtotime($value['data_entrega'])); ?></small></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
            <!-- Earnings (Annual) Card Example -->
            <?php
            if (isset($this->dados['veiculos_adiantados'])) {
                foreach ($this->dados['veiculos_adiantados'] as $value) {
                    ?>

                    <!-- Earnings (Annual) Card Example -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            <?php echo $value['marca'] . " - " . $value['modelo']; ?>    
                                        </div>
                                        <small><?php echo $value['tipo_servico']; ?></small>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"></div>
                                    </div>
                                    <div class="col-auto">

                                        <i class="fas fa-clipboard-list fa-3x text-warning mb-1"></i><br>
                                        <div><small class="text-center float-left row justify-content-center"><?php echo date("d/m/Y", strtotime($value['data_entrega'])); ?></small></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>

        </div>


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
</div>
<!-- /.container-fluid -->


