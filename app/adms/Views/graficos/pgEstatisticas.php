<?php
if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

if (!function_exists('estat_card')) {
    function estat_card($cor, $titulo, $valor, $icone) { ?>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-<?= $cor ?> shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-<?= $cor ?> text-uppercase mb-1"><?= htmlspecialchars($titulo) ?></div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= htmlspecialchars((string)$valor) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas <?= $icone ?> fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php }
}

$d = $this->dados ?? [];
$meses = json_encode(['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez']);
$ocorrenciasPorMes = array_values($d['ocorrencias_por_mes'] ?? array_fill(0, 12, 0));
$prevCorretiva = $d['preventiva_vs_corretiva'] ?? ['Preventiva' => 0, 'Corretiva' => 0];
$estadoEquip = $d['equipamentos_por_estado'] ?? [];
$pecas = $d['pecas_mais_utilizadas'] ?? [];

$totalOcorrencias = array_sum($ocorrenciasPorMes);
$totalPreventiva = (int)($prevCorretiva['Preventiva'] ?? 0);
$totalCorretiva = (int)($prevCorretiva['Corretiva'] ?? 0);
$totalEquipamentos = array_sum($estadoEquip);
$percDisponiveis = $totalEquipamentos > 0 ? round((($estadoEquip['Disponível'] ?? 0) / $totalEquipamentos) * 100) : 0;
?>
<div class="container-fluid">
    <?php if (isset($_SESSION['msg'])) { echo $_SESSION['msg']; unset($_SESSION['msg']); } ?>
    <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
        <h1 class="h3 mb-0 text-gray-800">Estatísticas de Manutenção — <?= date('Y') ?></h1>
    </div>

    <div class="row">
        <?php
        estat_card('primary', 'Ocorrências (ano)', $totalOcorrencias, 'fa-exclamation-circle');
        estat_card('info', 'Manutenções Preventivas', $totalPreventiva, 'fa-calendar-check');
        estat_card('warning', 'Manutenções Corretivas', $totalCorretiva, 'fa-tools');
        estat_card('success', 'Equipamentos Disponíveis', $percDisponiveis . '% (' . ($estadoEquip['Disponível'] ?? 0) . '/' . $totalEquipamentos . ')', 'fa-laptop');
        ?>
    </div>

    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Ocorrências por Mês — <?php echo date('Y'); ?></h6>
                </div>
                <div class="card-body">
                    <div id="graf-ocorrencias-mes" style="min-height:320px;"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Preventiva vs. Corretiva</h6>
                </div>
                <div class="card-body">
                    <div id="graf-categoria" style="min-height:320px;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Equipamentos por Estado</h6>
                </div>
                <div class="card-body">
                    <div id="graf-estado-equip" style="min-height:300px;"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Peças Mais Utilizadas (Top 10)</h6>
                </div>
                <div class="card-body">
                    <div id="graf-pecas" style="min-height:300px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var CORES = { azul: '#4e73df', verde: '#1cc88a', amarelo: '#f6c23e', vermelho: '#e74a3b', cinza: '#858796', ciano: '#36b9cc' };

    Highcharts.chart('graf-ocorrencias-mes', {
        chart: { style: { fontFamily: 'inherit' } },
        title: { text: null },
        subtitle: { text: 'Total no ano: <?= $totalOcorrencias ?>' },
        xAxis: { categories: <?php echo $meses; ?> },
        yAxis: { title: { text: 'Ocorrências' }, allowDecimals: false },
        colors: [CORES.azul],
        plotOptions: { column: { dataLabels: { enabled: true } } },
        series: [{ name: 'Ocorrências', type: 'column', data: <?php echo json_encode($ocorrenciasPorMes); ?>, showInLegend: false }],
        credits: { enabled: false }
    });

    Highcharts.chart('graf-categoria', {
        chart: { type: 'pie', style: { fontFamily: 'inherit' } },
        title: { text: null },
        colors: [CORES.ciano, CORES.amarelo],
        plotOptions: {
            pie: {
                dataLabels: { enabled: true, format: '{point.name}: {point.percentage:.0f}%' },
                showInLegend: true
            }
        },
        legend: { verticalAlign: 'bottom' },
        series: [{
            name: 'Ocorrências',
            data: [
                { name: 'Preventiva', y: <?php echo $totalPreventiva; ?> },
                { name: 'Corretiva', y: <?php echo $totalCorretiva; ?> }
            ]
        }],
        credits: { enabled: false }
    });

    Highcharts.chart('graf-estado-equip', {
        chart: { type: 'bar', style: { fontFamily: 'inherit' } },
        title: { text: null },
        xAxis: { categories: ['Disponível', 'Em Manutenção', 'Avariado', 'Abatido'] },
        yAxis: { title: { text: 'Equipamentos' }, allowDecimals: false },
        plotOptions: { series: { dataLabels: { enabled: true } } },
        series: [{
            name: 'Equipamentos',
            data: [
                { y: <?php echo (int)($estadoEquip['Disponível'] ?? 0); ?>, color: CORES.verde },
                { y: <?php echo (int)($estadoEquip['Em Manutenção'] ?? 0); ?>, color: CORES.ciano },
                { y: <?php echo (int)($estadoEquip['Avariado'] ?? 0); ?>, color: CORES.amarelo },
                { y: <?php echo (int)($estadoEquip['Abatido'] ?? 0); ?>, color: CORES.cinza }
            ],
            showInLegend: false
        }],
        credits: { enabled: false }
    });

    var pecasCategorias = <?php echo json_encode(array_map(fn($p) => $p['nome'], $pecas)); ?>;
    var pecasValores = <?php echo json_encode(array_map(fn($p) => (int)$p['total'], $pecas)); ?>;
    if (pecasCategorias.length > 0) {
        Highcharts.chart('graf-pecas', {
            chart: { type: 'bar', style: { fontFamily: 'inherit' } },
            title: { text: null },
            xAxis: { categories: pecasCategorias },
            yAxis: { title: { text: 'Quantidade Utilizada' }, allowDecimals: false },
            colors: [CORES.azul],
            plotOptions: { series: { dataLabels: { enabled: true } } },
            series: [{ name: 'Quantidade', data: pecasValores, showInLegend: false }],
            credits: { enabled: false }
        });
    } else {
        document.getElementById('graf-pecas').outerHTML = '<p class="text-center text-muted">Não existem dados para apresentar.</p>';
    }
</script>
