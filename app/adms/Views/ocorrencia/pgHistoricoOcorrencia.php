<?php
if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

$ocorrencia = $this->dados['ocorrencia'] ?? null;
$historico = $this->dados['historico'] ?? [];
$equipamentos = $this->dados['equipamentos'] ?? [];
?>
<div class="container-fluid">
    <?php
    if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
    ?>

    <div class="row mt-4 mb-4">
        <a class="btn btn-secondary btn-sm ml-3" href="<?= URLADM ?>ocorrencia"><i class="icofont icofont-arrow-left mr-1"></i>Voltar às Ocorrências</a>
    </div>

    <?php if (!$ocorrencia): ?>
        <div class="alert alert-danger">Ocorrência não encontrada.</div>
    <?php else: ?>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Ocorrência #<?= (int)$ocorrencia['idocorrencia'] ?></h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3"><strong>Estado:</strong> <?= htmlspecialchars($ocorrencia['estado']) ?></div>
                    <div class="col-md-3"><strong>Prioridade:</strong> <?= htmlspecialchars($ocorrencia['prioridade'] ?? '—') ?></div>
                    <div class="col-md-3"><strong>Tipo:</strong> <?= htmlspecialchars($ocorrencia['tipo_manutencao'] ?? '—') ?></div>
                    <div class="col-md-3"><strong>Serviço:</strong> <?= htmlspecialchars($ocorrencia['tipo_servico'] ?? '—') ?></div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-4"><strong>Aberta em:</strong> <?= htmlspecialchars($ocorrencia['data_abertura'] ?? '—') ?></div>
                    <div class="col-md-4"><strong>Prevista:</strong> <?= htmlspecialchars($ocorrencia['data_prevista'] ?? '—') ?></div>
                    <div class="col-md-4"><strong>Encerrada em:</strong> <?= htmlspecialchars($ocorrencia['data_encerramento'] ?? '—') ?></div>
                </div>
                <?php if (!empty($ocorrencia['descricao'])): ?>
                    <div class="row mt-2">
                        <div class="col-md-12"><strong>Descrição:</strong> <?= nl2br(htmlspecialchars($ocorrencia['descricao'])) ?></div>
                    </div>
                <?php endif; ?>

                <hr>
                <h6 class="font-weight-bold">Equipamentos associados</h6>
                <ul>
                    <?php foreach ($equipamentos as $eq): ?>
                        <li><?= htmlspecialchars($eq['numero_serie'] . ' — ' . $eq['marca'] . ' ' . $eq['modelo']) ?></li>
                    <?php endforeach; ?>
                    <?php if (empty($equipamentos)): ?>
                        <li class="text-muted">Nenhum equipamento associado.</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Histórico de Estados</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Estado Anterior</th>
                                <th>Estado Novo</th>
                                <th>Utilizador</th>
                                <th>Observação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($historico as $h): ?>
                                <tr>
                                    <td><?= htmlspecialchars($h['created']) ?></td>
                                    <td><?= htmlspecialchars($h['estado_anterior'] ?? '—') ?></td>
                                    <td><?= htmlspecialchars($h['estado_novo']) ?></td>
                                    <td><?= htmlspecialchars(trim(($h['usuario_nome'] ?? '') . ' ' . ($h['usuario_sobrenome'] ?? '')) ?: '—') ?></td>
                                    <td><?= htmlspecialchars($h['observacao'] ?? '') ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($historico)): ?>
                                <tr><td colspan="5" class="text-center text-muted">Sem registos.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
