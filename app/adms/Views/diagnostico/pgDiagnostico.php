<?php
if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

$idOcorrencia = $this->dados['idocorrencia'] ?? null;
$ocorrencia = $this->dados['ocorrencia'] ?? null;
$lista = $this->dados['lista'] ?? [];
$equipamentos = $this->dadosAlter['equipamentos'] ?? [];
?>
<div class="container-fluid">
    <?php
    if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
    ?>

    <?php if ($idOcorrencia): ?>
        <div class="row mt-4 mb-4">
            <a class="btn btn-secondary btn-sm ml-3" href="<?= URLADM ?>ocorrencia"><i class="icofont icofont-arrow-left mr-1"></i>Voltar às Ocorrências</a>
            <a class="btn-primary btn-sm ml-3 d-none d-md-block text-white" style="cursor:pointer;padding:0.375rem 1rem;border-radius:0.35rem;" data-toggle="modal" data-target="#novoDiagnostico">Novo Diagnóstico</a>
        </div>

        <?php if (!$ocorrencia): ?>
            <div class="alert alert-danger">Ocorrência não encontrada.</div>
        <?php else: ?>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Ocorrência #<?= (int)$ocorrencia['idocorrencia'] ?> — Estado: <?= htmlspecialchars($ocorrencia['estado']) ?></h6>
                </div>
                <div class="card-body">
                    <?= nl2br(htmlspecialchars($ocorrencia['descricao'] ?? 'Sem descrição.')) ?>
                </div>
            </div>

            <!-- Modal: Novo Diagnóstico -->
            <div class="modal fade" id="novoDiagnostico" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <form action="" method="post" class="needs-validation" novalidate>
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title"><i class="fas fa-stethoscope mr-2"></i>Registar Diagnóstico</h5>
                                <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="id_equipamento">Equipamento <span class="text-danger">*</span></label>
                                    <select class="custom-select" id="id_equipamento" name="id_equipamento" required>
                                        <option value="">Selecione...</option>
                                        <?php foreach ($equipamentos as $eq): ?>
                                            <option value="<?= (int)$eq['idequipamento'] ?>"><?= htmlspecialchars($eq['numero_serie'] . ' — ' . $eq['marca'] . ' ' . $eq['modelo']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="problema_descrito">Problema Encontrado</label>
                                    <textarea class="form-control" id="problema_descrito" name="problema_descrito" rows="3"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="solucao_proposta">Solução Proposta</label>
                                    <textarea class="form-control" id="solucao_proposta" name="solucao_proposta" rows="3"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="pecas_solicitadas">Peças Solicitadas</label>
                                    <textarea class="form-control" id="pecas_solicitadas" name="pecas_solicitadas" rows="2" placeholder="Ex: Fonte de alimentação, memória RAM 8GB..."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                <input type="hidden" name="id_ocorrencia" value="<?= (int)$idOcorrencia ?>">
                                <button class="btn btn-primary" name="btnCdsDiagnostico"><i class="fas fa-save mr-1"></i>Registar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="row mt-4 mb-4">
            <h6 class="ml-3 font-weight-bold text-primary">Todos os Diagnósticos</h6>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Diagnósticos</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Equipamento</th>
                            <?php if (!$idOcorrencia): ?><th>Ocorrência</th><?php endif; ?>
                            <th>Problema</th>
                            <th>Solução Proposta</th>
                            <th>Peças</th>
                            <th>Técnico</th>
                            <th>Encaminhado?</th>
                            <?php if ($idOcorrencia): ?><th>Acção</th><?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lista as $d): $id = (int)$d['iddiagnostico']; ?>
                            <tr>
                                <td><?= htmlspecialchars($d['created']) ?></td>
                                <td><?= htmlspecialchars($d['numero_serie'] . ' — ' . $d['marca'] . ' ' . $d['modelo']) ?></td>
                                <?php if (!$idOcorrencia): ?>
                                    <td><a href="<?= URLADM ?>diagnostico?ocorrencia=<?= (int)$d['id_ocorrencia'] ?>">#<?= (int)$d['id_ocorrencia'] ?></a></td>
                                <?php endif; ?>
                                <td><?= htmlspecialchars($d['problema_descrito'] ?? '—') ?></td>
                                <td><?= htmlspecialchars($d['solucao_proposta'] ?? '—') ?></td>
                                <td><?= htmlspecialchars($d['pecas_solicitadas'] ?? '—') ?></td>
                                <td><?= htmlspecialchars(trim(($d['tecnico_nome'] ?? '') . ' ' . ($d['tecnico_sobrenome'] ?? '')) ?: '—') ?></td>
                                <td><?= $d['encaminhado_execucao'] ? '<span class="badge badge-success">Sim</span>' : '<span class="badge badge-secondary">Não</span>' ?></td>
                                <?php if ($idOcorrencia): ?>
                                    <td>
                                        <?php if (!$d['encaminhado_execucao']): ?>
                                            <a href="<?= $id ?>" data-toggle="modal" data-target="#edit<?= $id ?>" title="Editar"><i class="icofont icofont-edit px-2"></i></a>
                                            <form action="" method="post" style="display:inline" onsubmit="return confirm('Encaminhar este diagnóstico para execução da manutenção?');">
                                                <input type="hidden" name="iddiagnostico" value="<?= $id ?>">
                                                <button type="submit" class="btn btn-link p-0" name="btnEncaminharExecucao" title="Encaminhar para Execução"><i class="icofont icofont-paper-plane"></i></button>
                                            </form>
                                        <?php else: ?>
                                            <span class="text-muted">—</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endif; ?>
                            </tr>

                            <?php if ($idOcorrencia && !$d['encaminhado_execucao']): ?>
                                <!-- Modal: Editar Diagnóstico -->
                                <div class="modal fade" id="edit<?= $id ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <form action="" method="post" novalidate>
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Editar Diagnóstico</h5>
                                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>Problema Encontrado</label>
                                                        <textarea class="form-control" name="problema_descrito" rows="3"><?= htmlspecialchars($d['problema_descrito'] ?? '') ?></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Solução Proposta</label>
                                                        <textarea class="form-control" name="solucao_proposta" rows="3"><?= htmlspecialchars($d['solucao_proposta'] ?? '') ?></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Peças Solicitadas</label>
                                                        <textarea class="form-control" name="pecas_solicitadas" rows="2"><?= htmlspecialchars($d['pecas_solicitadas'] ?? '') ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                                    <input type="hidden" name="iddiagnostico" value="<?= $id ?>">
                                                    <button class="btn btn-primary" name="btnEditDiagnostico">Guardar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <?php if (empty($lista)): ?>
                            <tr><td colspan="8" class="text-center text-muted">Sem diagnósticos registados.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
