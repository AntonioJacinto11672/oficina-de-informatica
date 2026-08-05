<?php
if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

$ocorrencia = $this->dados['ocorrencia'] ?? null;
$historico = $this->dados['historico'] ?? [];
$equipamentos = $this->dados['equipamentos'] ?? [];
$tecnicos = $this->dadosAlter['tecnicos'] ?? [];
$estadosCancelaveis = $this->dadosAlter['estadosCancelaveis'] ?? [];
$estadosFinais = ['Concluída', 'Cancelada'];
$id = (int)($ocorrencia['idocorrencia'] ?? 0);
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
        <?php if ($ocorrencia): ?>
            <a class="btn btn-info btn-sm text-white ml-2" href="<?= URLADM ?>diagnostico?ocorrencia=<?= $id ?>"><i class="icofont icofont-stethoscope mr-1"></i>Diagnóstico</a>
            <a class="btn btn-primary btn-sm text-white ml-2" href="<?= URLADM ?>execucao?ocorrencia=<?= $id ?>"><i class="icofont icofont-tools mr-1"></i>Execução</a>
            <?php if (!in_array($ocorrencia['estado'], $estadosFinais, true)): ?>
                <a class="btn btn-secondary btn-sm ml-2" href="#" data-toggle="modal" data-target="#tecnico<?= $id ?>"><i class="icofont icofont-user mr-1"></i>Atribuir Técnico</a>
            <?php endif; ?>
            <?php if (in_array($ocorrencia['estado'], $estadosCancelaveis, true)): ?>
                <a class="btn btn-warning btn-sm ml-2" href="#" data-toggle="modal" data-target="#cancelar<?= $id ?>"><i class="icofont icofont-close-circled mr-1"></i>Cancelar Ocorrência</a>
            <?php endif; ?>
        <?php endif; ?>
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
                    <div class="col-md-3"><strong>Categoria:</strong> <?= htmlspecialchars($ocorrencia['categoria_manutencao'] ?? '—') ?></div>
                    <div class="col-md-3"><strong>Tipo:</strong> <?= htmlspecialchars($ocorrencia['tipo_manutencao'] ?? '—') ?></div>
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

        <!-- Modal: Atribuir Técnico -->
        <div class="modal fade" id="tecnico<?= $id ?>" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="" method="post" novalidate>
                        <div class="modal-header">
                            <h5 class="modal-title">Atribuir Técnico — Ocorrência #<?= $id ?></h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <select class="custom-select" name="idtecnico_responsavel">
                                <option value="">Por atribuir</option>
                                <?php foreach ($tecnicos as $t): ?>
                                    <option value="<?= (int)$t['idusuario'] ?>" <?= (int)($ocorrencia['idtecnico_responsavel'] ?? 0) === (int)$t['idusuario'] ? 'selected' : '' ?>><?= htmlspecialchars($t['nome'] . ' ' . $t['sobrenome']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                            <input type="hidden" name="idocorrencia" value="<?= $id ?>">
                            <button class="btn btn-primary" name="btnAtribuirTecnico">Atribuir</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php if (in_array($ocorrencia['estado'], $estadosCancelaveis, true)): ?>
        <!-- Modal: Cancelar -->
        <div class="modal fade" id="cancelar<?= $id ?>" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cancelar Ocorrência #<?= $id ?>?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <form action="" method="post" novalidate>
                        <div class="modal-body">
                            <p>O equipamento associado fica novamente disponível para outras ocorrências.</p>
                            <div class="form-group">
                                <label>Motivo (opcional)</label>
                                <textarea class="form-control" name="observacao" rows="2" placeholder="Ex: pedido duplicado, resolvido de outra forma..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Voltar</button>
                            <input type="hidden" name="idocorrencia" value="<?= $id ?>">
                            <button class="btn btn-warning" name="btnCancelarOcorrencia">Cancelar Ocorrência</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
