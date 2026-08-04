<?php
if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}
$equipamento = $this->dados['equipamento'] ?? null;
$historico = $this->dados['historico'] ?? [];
?>
<div class="container-fluid">
    <?php if (isset($_SESSION['msg'])) { echo $_SESSION['msg']; unset($_SESSION['msg']); } ?>
    <div class="row mt-4 mb-4">
        <a class="btn btn-secondary btn-sm ml-3" href="<?= URLADM ?>equipamento"><i class="icofont icofont-arrow-left mr-1"></i>Voltar a Equipamentos</a>
    </div>

    <?php if (!$equipamento): ?>
        <div class="alert alert-danger">Equipamento não encontrado.</div>
    <?php else: ?>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?= htmlspecialchars($equipamento['nome']) ?> — <?= htmlspecialchars($equipamento['numero_serie']) ?></h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3"><strong>Estado:</strong> <?= htmlspecialchars($equipamento['estado']) ?></div>
                    <div class="col-md-3"><strong>Departamento:</strong> <?= htmlspecialchars($equipamento['departamento'] ?? '—') ?></div>
                    <div class="col-md-3"><strong>Localização:</strong> <?= htmlspecialchars($equipamento['localizacao'] ?? '—') ?></div>
                    <div class="col-md-3"><strong>Responsável:</strong> <?= htmlspecialchars(trim(($equipamento['responsavel_nome'] ?? '') . ' ' . ($equipamento['responsavel_sobrenome'] ?? '')) ?: '—') ?></div>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Histórico de Manutenção</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Ocorrência</th><th>Categoria</th><th>Prioridade</th><th>Estado</th><th>Técnico</th><th>Aberta em</th><th>Encerrada em</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($historico as $h): ?>
                                <tr>
                                    <td><a href="<?= URLADM ?>ocorrencia?historico=<?= (int)$h['idocorrencia'] ?>">#<?= (int)$h['idocorrencia'] ?></a></td>
                                    <td><?= htmlspecialchars($h['categoria_manutencao'] ?? '—') ?></td>
                                    <td><?= htmlspecialchars($h['prioridade'] ?? '—') ?></td>
                                    <td><?= htmlspecialchars($h['estado']) ?></td>
                                    <td><?= htmlspecialchars(trim(($h['tecnico_nome'] ?? '') . ' ' . ($h['tecnico_sobrenome'] ?? '')) ?: 'Por atribuir') ?></td>
                                    <td><?= htmlspecialchars($h['data_abertura'] ?? '—') ?></td>
                                    <td><?= htmlspecialchars($h['data_encerramento'] ?? '—') ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($historico)): ?>
                                <tr><td colspan="7" class="text-center text-muted">Não existem dados para apresentar.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
