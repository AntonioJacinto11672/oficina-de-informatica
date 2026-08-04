<?php
if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

if (!function_exists('home_card')) {
    function home_card($cor, $titulo, $valor, $icone) { ?>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-<?= $cor ?> shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-<?= $cor ?> text-uppercase mb-1"><?= htmlspecialchars($titulo) ?></div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= (int)$valor ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas <?= $icone ?> fa-2x text-<?= $cor ?>"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php }
}

$d = $this->dados ?? [];
?>
<div class="container-fluid">
    <?php if (isset($_SESSION['msg'])) { echo $_SESSION['msg']; unset($_SESSION['msg']); } ?>

    <?php if ($_SESSION['nivel'] === 'gerente'): ?>
        <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
            <h1 class="h3 mb-0 text-gray-800">Dashboard do Gerente</h1>
        </div>
        <div class="row">
            <?php
            home_card('primary', 'Total de Equipamentos', $d['total_equipamentos'] ?? 0, 'fa-laptop');
            home_card('info', 'Total de Técnicos', $d['total_tecnicos'] ?? 0, 'fa-user-cog');
            home_card('success', 'Total de Ocorrências', $d['total_ocorrencias'] ?? 0, 'fa-exclamation-circle');
            home_card('warning', 'Total de Diagnósticos', $d['total_diagnosticos'] ?? 0, 'fa-stethoscope');
            home_card('primary', 'Total de Execuções', $d['total_execucoes'] ?? 0, 'fa-tools');
            home_card('info', 'Manutenções Preventivas', $d['manutencoes_preventivas'] ?? 0, 'fa-calendar-check');
            home_card('warning', 'Manutenções Corretivas', $d['manutencoes_corretivas'] ?? 0, 'fa-wrench');
            home_card('danger', 'Equipamentos em Manutenção', $d['equipamentos_em_manutencao'] ?? 0, 'fa-tools');
            home_card('success', 'Equipamentos Disponíveis', $d['equipamentos_disponiveis'] ?? 0, 'fa-check-circle');
            home_card('secondary', 'Fornecedores', $d['total_fornecedores'] ?? 0, 'fa-truck');
            home_card('primary', 'Compras (mês atual)', $d['total_compras'] ?? 0, 'fa-shopping-cart');
            home_card('danger', 'Stock Baixo', $d['stock_baixo'] ?? 0, 'fa-box-open');
            ?>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Manutenções Preventivas Próximas (30 dias)</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead><tr><th>Data Prevista</th><th>Equipamento</th><th>Tipo</th><th>Origem</th></tr></thead>
                        <tbody>
                            <?php foreach (($d['ocorrencias_preventivas'] ?? []) as $p): ?>
                                <tr>
                                    <td><?= htmlspecialchars($p['data_prevista']) ?></td>
                                    <td><?= htmlspecialchars(trim(($p['numero_serie'] ?? '') . ' — ' . ($p['marca'] ?? '') . ' ' . ($p['modelo'] ?? ''))) ?></td>
                                    <td><?= htmlspecialchars($p['tipo_manutencao'] ?? '—') ?></td>
                                    <td><?= $p['origem'] === 'plano' ? 'Plano de Manutenção' : 'Ocorrência' ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($d['ocorrencias_preventivas'])): ?>
                                <tr><td colspan="4" class="text-center text-muted">Não existem manutenções preventivas previstas para os próximos 30 dias.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    <?php elseif ($_SESSION['nivel'] === 'tecnico'): ?>
        <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
            <h1 class="h3 mb-0 text-gray-800">Dashboard do Técnico</h1>
        </div>
        <div class="row">
            <?php
            home_card('primary', 'Ocorrências Atribuídas', $d['ocorrencias_atribuidas'] ?? 0, 'fa-exclamation-circle');
            home_card('warning', 'Diagnósticos Pendentes', $d['diagnosticos_pendentes'] ?? 0, 'fa-stethoscope');
            home_card('info', 'Manutenções em Execução', $d['manutencoes_em_execucao'] ?? 0, 'fa-tools');
            home_card('success', 'Manutenções Concluídas (mês)', $d['manutencoes_concluidas'] ?? 0, 'fa-check-circle');
            home_card('secondary', 'Planeamentos Preventivos', $d['planeamentos_preventivos'] ?? 0, 'fa-calendar-check');
            home_card('danger', 'Equipamentos em Manutenção', $d['equipamentos_em_manutencao'] ?? 0, 'fa-laptop');
            home_card('warning', 'Manutenções Pendentes', $d['manutencoes_pendentes'] ?? 0, 'fa-hourglass-half');
            ?>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">As Minhas Ocorrências em Aberto</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead><tr><th>#</th><th>Equipamento</th><th>Prioridade</th><th>Estado</th><th>Prevista</th></tr></thead>
                        <tbody>
                            <?php foreach (($d['minhas_ocorrencias'] ?? []) as $o): ?>
                                <tr>
                                    <td><a href="<?= URLADM ?>ocorrencia?historico=<?= (int)$o['idocorrencia'] ?>">#<?= (int)$o['idocorrencia'] ?></a></td>
                                    <td><?= htmlspecialchars(trim(($o['numero_serie'] ?? '') . ' — ' . ($o['marca'] ?? '') . ' ' . ($o['modelo'] ?? ''))) ?></td>
                                    <td><?= htmlspecialchars($o['prioridade']) ?></td>
                                    <td><?= htmlspecialchars($o['estado']) ?></td>
                                    <td><?= htmlspecialchars($o['data_prevista'] ?? '—') ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($d['minhas_ocorrencias'])): ?>
                                <tr><td colspan="5" class="text-center text-muted">Não tem ocorrências em aberto.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
