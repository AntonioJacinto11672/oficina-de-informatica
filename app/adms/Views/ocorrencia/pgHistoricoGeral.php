<?php
if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}
$lista = $this->dados ?? [];
?>
<div class="container-fluid">
    <?php if (isset($_SESSION['msg'])) { echo $_SESSION['msg']; unset($_SESSION['msg']); } ?>
    <div class="card shadow mb-4 mt-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Histórico de Manutenções Concluídas / Canceladas</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th><th>Equipamento</th><th>Tipo</th><th>Categoria</th><th>Estado</th><th>Técnico</th><th>Encerrada em</th><th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lista as $o): ?>
                            <tr>
                                <td><?= (int)$o['idocorrencia'] ?></td>
                                <td><?= htmlspecialchars(trim(($o['numero_serie'] ?? '') . ' ' . ($o['marca'] ?? '') . ' ' . ($o['modelo'] ?? '')) ?: '—') ?></td>
                                <td><?= htmlspecialchars($o['tipo_manutencao'] ?? '—') ?></td>
                                <td><?= htmlspecialchars($o['categoria_manutencao'] ?? '—') ?></td>
                                <td><span class="badge badge-<?= $o['estado'] === 'Concluída' ? 'success' : 'secondary' ?>"><?= htmlspecialchars($o['estado']) ?></span></td>
                                <td><?= htmlspecialchars(trim(($o['tecnico_nome'] ?? '') . ' ' . ($o['tecnico_sobrenome'] ?? '')) ?: '—') ?></td>
                                <td><?= htmlspecialchars($o['data_encerramento'] ?? '—') ?></td>
                                <td><a href="<?= URLADM ?>ocorrencia?historico=<?= (int)$o['idocorrencia'] ?>" title="Ver detalhe"><i class="icofont icofont-eye-alt px-2"></i></a></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($lista)): ?>
                            <tr><td colspan="8" class="text-center text-muted">Não existem dados para apresentar.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
