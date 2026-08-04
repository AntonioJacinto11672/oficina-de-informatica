<?php
if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

$valorForm = [];
if (isset($this->dados['form']) && is_array($this->dados['form'])) {
    $valorForm = $this->dados['form'];
}

$lista = $this->dados['lista'] ?? [];
$equipamentosDisponiveis = $this->dadosAlter['equipamentos'] ?? [];
$tiposManutencao = $this->dadosAlter['tiposManutencao'] ?? [];
$tecnicos = $this->dadosAlter['tecnicos'] ?? [];
$prioridades = $this->dadosAlter['prioridades'] ?? [];
$equipamentosPorOcorrencia = $this->dadosAlter['equipamentosPorOcorrencia'] ?? [];
$equipamentosDisponiveisPorOcorrencia = $this->dadosAlter['equipamentosDisponiveisPorOcorrencia'] ?? [];
$estadosCancelaveis = $this->dadosAlter['estadosCancelaveis'] ?? [];

if (!function_exists('ocorrencia_badge_prioridade')) {
    function ocorrencia_badge_prioridade($prioridade) {
        $classe = ['Baixa' => 'secondary', 'Média' => 'info', 'Alta' => 'warning', 'Urgente' => 'danger'][$prioridade] ?? 'secondary';
        return '<span class="badge badge-' . $classe . '">' . htmlspecialchars($prioridade) . '</span>';
    }
}

if (!function_exists('ocorrencia_badge_estado')) {
    function ocorrencia_badge_estado($estado) {
        $classe = [
            'Aberta' => 'primary',
            'Em diagnóstico' => 'info',
            'Aguardando execução' => 'warning',
            'Em execução' => 'info',
            'Concluída' => 'success',
            'Cancelada' => 'secondary',
        ][$estado] ?? 'secondary';
        return '<span class="badge badge-' . $classe . '">' . htmlspecialchars($estado) . '</span>';
    }
}
?>
<!-- Modal: Nova Ocorrência -->
<div class="modal fade" id="novaOcorrencia" tabindex="-1" role="dialog" aria-labelledby="modalOcorrenciaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" method="post" class="needs-validation" enctype="multipart/form-data" novalidate>
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalOcorrenciaLabel"><i class="fas fa-exclamation-circle mr-2"></i>Abrir Ocorrência</h5>
                    <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="equipamentos">Equipamento(s) <span class="text-danger">*</span></label>
                        <select class="custom-select" id="equipamentos" name="equipamentos[]" multiple size="5" required>
                            <?php foreach ($equipamentosDisponiveis as $eq): ?>
                                <option value="<?= (int)$eq['idequipamento'] ?>">
                                    <?= htmlspecialchars($eq['numero_serie'] . ' — ' . $eq['marca'] . ' ' . $eq['modelo']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="form-text text-muted">Ctrl+clique (ou Cmd+clique) para seleccionar mais do que um equipamento.</small>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="id_tipo_manutencao">Tipo de Manutenção</label>
                            <select class="custom-select" id="id_tipo_manutencao" name="id_tipo_manutencao">
                                <option value="">Selecione...</option>
                                <?php foreach ($tiposManutencao as $tm): ?>
                                    <option value="<?= (int)$tm['idtipo_manutencao'] ?>"><?= htmlspecialchars($tm['nome']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small class="form-text text-muted">Ocorrências abertas manualmente são sempre de manutenção corretiva. As manutenções preventivas são geradas pelo Planeamento Preventivo.</small>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="prioridade">Prioridade</label>
                            <select class="custom-select" id="prioridade" name="prioridade">
                                <?php foreach ($prioridades as $p): ?>
                                    <option value="<?= htmlspecialchars($p) ?>" <?= $p === 'Média' ? 'selected' : '' ?>><?= htmlspecialchars($p) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="idtecnico_responsavel">Técnico Responsável</label>
                            <select class="custom-select" id="idtecnico_responsavel" name="idtecnico_responsavel">
                                <option value="">Por atribuir</option>
                                <?php foreach ($tecnicos as $t): ?>
                                    <option value="<?= (int)$t['idusuario'] ?>"><?= htmlspecialchars($t['nome'] . ' ' . $t['sobrenome']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="data_prevista">Data Prevista</label>
                            <input type="date" class="form-control" id="data_prevista" name="data_prevista">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="descricao">Descrição</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="3" placeholder="Descreva a avaria/pedido..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary" name="btnCdsOcorrencia"><i class="fas fa-save mr-1"></i>Abrir Ocorrência</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="container-fluid">
    <?php
    if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
    ?>
    <div class="row mt-4 mb-4">
        <a type="button" class="btn-primary btn-sm ml-3 d-none d-md-block text-white" href="" data-toggle="modal" data-target="#novaOcorrencia"> Nova Ocorrência</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Ocorrências</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Equipamento(s)</th>
                            <th>Tipo de Manutenção</th>
                            <th>Prioridade</th>
                            <th>Estado</th>
                            <th>Técnico</th>
                            <th>Prevista</th>
                            <th>Acção</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lista as $o): $id = (int)$o['idocorrencia']; ?>
                            <tr>
                                <td><?= $id ?></td>
                                <td><?= htmlspecialchars($o['numero_serie'] ?? '—') ?></td>
                                <td><?= htmlspecialchars($o['tipo_manutencao'] ?? '—') ?> <span class="badge badge-<?= $o['categoria_manutencao'] === 'Preventiva' ? 'info' : 'warning' ?>"><?= htmlspecialchars($o['categoria_manutencao']) ?></span></td>
                                <td><?= ocorrencia_badge_prioridade($o['prioridade']) ?></td>
                                <td><?= ocorrencia_badge_estado($o['estado']) ?></td>
                                <td><?= htmlspecialchars(trim(($o['tecnico_nome'] ?? '') . ' ' . ($o['tecnico_sobrenome'] ?? '')) ?: 'Por atribuir') ?></td>
                                <td><?= $o['data_prevista'] ? htmlspecialchars($o['data_prevista']) : '—' ?></td>
                                <td>
                                    <a href="<?= URLADM ?>ocorrencia?historico=<?= $id ?>" title="Histórico"><i class="icofont icofont-history px-2"></i></a>
                                    <a href="<?= URLADM ?>diagnostico?ocorrencia=<?= $id ?>" title="Diagnóstico"><i class="icofont icofont-stethoscope px-2"></i></a>
                                    <a href="<?= URLADM ?>execucao?ocorrencia=<?= $id ?>" title="Execução"><i class="icofont icofont-tools px-2"></i></a>
                                    <a href="<?= $id ?>" data-toggle="modal" data-target="#edit<?= $id ?>" title="Editar"><i class="icofont icofont-edit px-2"></i></a>
                                    <a href="<?= $id ?>" data-toggle="modal" data-target="#tecnico<?= $id ?>" title="Atribuir Técnico"><i class="icofont icofont-user px-2"></i></a>
                                    <?php if (in_array($o['estado'], $estadosCancelaveis, true)): ?>
                                        <a href="<?= $id ?>" data-toggle="modal" data-target="#cancelar<?= $id ?>" title="Cancelar Ocorrência"><i class="icofont icofont-close-circled text-warning px-2"></i></a>
                                    <?php endif; ?>
                                    <a href="<?= $id ?>" data-toggle="modal" data-target="#delete<?= $id ?>" title="Eliminar"><i class="icofont icofont-trash text-danger"></i></a>
                                </td>
                            </tr>

                            <!-- Modal: Editar -->
                            <div class="modal fade" id="edit<?= $id ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <form action="" method="post" class="needs-validation" enctype="multipart/form-data" novalidate>
                                            <div class="modal-header">
                                                <h5 class="modal-title">Editar Ocorrência #<?= $id ?></h5>
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Equipamento(s)</label>
                                                    <?php $ligados = array_column($equipamentosPorOcorrencia[$id] ?? [], 'idequipamento'); ?>
                                                    <select class="custom-select" name="equipamentos[]" multiple size="5">
                                                        <?php foreach (($equipamentosDisponiveisPorOcorrencia[$id] ?? []) as $eq): ?>
                                                            <option value="<?= (int)$eq['idequipamento'] ?>" <?= in_array((int)$eq['idequipamento'], $ligados, true) ? 'selected' : '' ?>>
                                                                <?= htmlspecialchars($eq['numero_serie'] . ' — ' . $eq['marca'] . ' ' . $eq['modelo']) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label>Tipo de Manutenção</label>
                                                        <select class="custom-select" name="id_tipo_manutencao">
                                                            <option value="">Selecione...</option>
                                                            <?php foreach ($tiposManutencao as $tm): ?>
                                                                <option value="<?= (int)$tm['idtipo_manutencao'] ?>" <?= (int)($o['id_tipo_manutencao'] ?? 0) === (int)$tm['idtipo_manutencao'] ? 'selected' : '' ?>><?= htmlspecialchars($tm['nome']) ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label>Prioridade</label>
                                                        <select class="custom-select" name="prioridade">
                                                            <?php foreach ($prioridades as $p): ?>
                                                                <option value="<?= htmlspecialchars($p) ?>" <?= $o['prioridade'] === $p ? 'selected' : '' ?>><?= htmlspecialchars($p) ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Data Prevista</label>
                                                    <input type="date" class="form-control" name="data_prevista" value="<?= htmlspecialchars($o['data_prevista'] ?? '') ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label>Descrição</label>
                                                    <textarea class="form-control" name="descricao" rows="3"><?= htmlspecialchars($o['descricao'] ?? '') ?></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                                <input type="hidden" name="idocorrencia" value="<?= $id ?>">
                                                <button class="btn btn-primary" name="btnEditOcorrencia">Guardar</button>
                                            </div>
                                        </form>
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
                                                        <option value="<?= (int)$t['idusuario'] ?>"><?= htmlspecialchars($t['nome'] . ' ' . $t['sobrenome']) ?></option>
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

                            <?php if (in_array($o['estado'], $estadosCancelaveis, true)): ?>
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

                            <!-- Modal: Eliminar -->
                            <div class="modal fade" id="delete<?= $id ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Tens a certeza que queres eliminar?</h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        </div>
                                        <div class="modal-body">Vais eliminar a ocorrência #<?= $id ?> e o respectivo histórico.</div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                            <form action="" method="post" novalidate>
                                                <input type="hidden" name="idocorrencia" value="<?= $id ?>">
                                                <button class="btn btn-primary" name="btnDeleteOcorrencia">Sim</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
