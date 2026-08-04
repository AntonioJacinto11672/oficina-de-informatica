<?php
if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}
$d = $this->dados ?? [];
$meses = json_encode(['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez']);
$ocorrenciasPorMes = json_encode(array_values($d['ocorrencias_por_mes'] ?? array_fill(0, 12, 0)));
$prevCorretiva = $d['preventiva_vs_corretiva'] ?? ['Preventiva' => 0, 'Corretiva' => 0];
$estadoEquip = $d['equipamentos_por_estado'] ?? [];
$pecas = $d['pecas_mais_utilizadas'] ?? [];
?>
<div class="container-fluid">
    <?php if (isset($_SESSION['msg'])) { echo $_SESSION['msg']; unset($_SESSION['msg']); } ?>
    <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
        <h1 class="h3 mb-0 text-gray-800">Estatísticas de Manutenção</h1>
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
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead><tr><th>Peça</th><th>Quantidade Utilizada</th></tr></thead>
                            <tbody>
                                <?php foreach ($pecas as $p): ?>
                                    <tr><td><?= htmlspecialchars($p['nome']) ?></td><td><?= (int)$p['total'] ?></td></tr>
                                <?php endforeach; ?>
                                <?php if (empty($pecas)): ?>
                                    <tr><td colspan="2" class="text-center text-muted">Não existem dados para apresentar.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    Highcharts.chart('graf-ocorrencias-mes', {
        title: { text: null },
        xAxis: { categories: <?php echo $meses; ?> },
        yAxis: { title: { text: 'Ocorrências' }, allowDecimals: false },
        series: [{ name: 'Ocorrências', type: 'column', data: <?php echo $ocorrenciasPorMes; ?>, showInLegend: false }]
    });

    Highcharts.chart('graf-categoria', {
        chart: { type: 'pie' },
        title: { text: null },
        series: [{
            name: 'Ocorrências',
            data: [
                { name: 'Preventiva', y: <?php echo (int)$prevCorretiva['Preventiva']; ?> },
                { name: 'Corretiva', y: <?php echo (int)$prevCorretiva['Corretiva']; ?> }
            ]
        }]
    });

    Highcharts.chart('graf-estado-equip', {
        chart: { type: 'bar' },
        title: { text: null },
        xAxis: { categories: ['Disponível', 'Em Manutenção', 'Avariado', 'Abatido'] },
        yAxis: { title: { text: 'Equipamentos' }, allowDecimals: false },
        series: [{
            name: 'Equipamentos',
            data: [
                <?php echo (int)($estadoEquip['Disponível'] ?? 0); ?>,
                <?php echo (int)($estadoEquip['Em Manutenção'] ?? 0); ?>,
                <?php echo (int)($estadoEquip['Avariado'] ?? 0); ?>,
                <?php echo (int)($estadoEquip['Abatido'] ?? 0); ?>
            ],
            showInLegend: false
        }]
    });
</script>
