<?php
if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}
$lista = $this->dados['lista'] ?? [];
$pendentes = $this->dados['pendentes'] ?? 0;
$equipamentos = $this->dadosAlter['equipamentos'] ?? [];
$tiposManutencao = $this->dadosAlter['tiposManutencao'] ?? [];
$tecnicos = $this->dadosAlter['tecnicos'] ?? [];
?>
<div class="modal fade" id="novoPlano" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" method="post" class="needs-validation" novalidate>
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-calendar-check mr-2"></i>Novo Plano de Manutenção Preventiva</h5>
                    <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Equipamento <span class="text-danger">*</span></label>
                            <select class="custom-select" name="id_equipamento" required>
                                <option value="">Selecione...</option>
                                <?php foreach ($equipamentos as $eq): ?>
                                    <option value="<?php echo (int)$eq['idequipamento']; ?>"><?php echo htmlspecialchars($eq['numero_serie'] . ' — ' . $eq['marca'] . ' ' . $eq['modelo']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Tipo de Manutenção</label>
                            <select class="custom-select" name="id_tipo_manutencao">
                                <option value="">Selecione...</option>
                                <?php foreach ($tiposManutencao as $tm): ?>
                                    <option value="<?php echo (int)$tm['idtipo_manutencao']; ?>"><?php echo htmlspecialchars($tm['nome']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>Técnico Responsável</label>
                            <select class="custom-select" name="idusuario_tecnico">
                                <option value="">Por atribuir</option>
                                <?php foreach ($tecnicos as $t): ?>
                                    <option value="<?php echo (int)$t['idusuario']; ?>"><?php echo htmlspecialchars($t['nome'] . ' ' . $t['sobrenome']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Periodicidade (dias) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="periodicidade_dias" min="1" value="90" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Data de Início <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="data_inicio" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Observações</label>
                        <textarea class="form-control" name="observacoes" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary" name="btnCdsPlano"><i class="fas fa-save mr-1"></i>Criar Plano</button>
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
        <a type="button" class="btn-primary btn-sm ml-3 d-none d-md-block text-white" href="" data-toggle="modal" data-target="#novoPlano"> Novo Plano</a>
        <?php if ($pendentes > 0): ?>
            <form action="" method="post" class="ml-3 d-inline">
                <button type="submit" class="btn btn-warning btn-sm" name="btnGerarOcorrencias">
                    <i class="fas fa-bolt mr-1"></i>Gerar Ocorrências Pendentes (<?php echo (int)$pendentes; ?>)
                </button>
            </form>
        <?php endif; ?>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Planos de Manutenção Preventiva</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr><th>Equipamento</th><th>Tipo</th><th>Técnico</th><th>Periodicidade</th><th>Próxima Execução</th><th>Estado</th><th>Acção</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lista as $p): $id = (int)$p['idplano']; $vencido = $p['proxima_execucao'] <= date('Y-m-d'); ?>
                            <tr class="<?php echo ($vencido && $p['ativo']) ? 'table-warning' : ''; ?>">
                                <td><?php echo htmlspecialchars($p['numero_serie'] . ' — ' . $p['marca'] . ' ' . $p['modelo']); ?></td>
                                <td><?php echo htmlspecialchars($p['tipo_manutencao'] ?? '—'); ?></td>
                                <td><?php echo htmlspecialchars(trim(($p['tecnico_nome'] ?? '') . ' ' . ($p['tecnico_sobrenome'] ?? '')) ?: 'Por atribuir'); ?></td>
                                <td><?php echo (int)$p['periodicidade_dias']; ?> dias</td>
                                <td><?php echo htmlspecialchars($p['proxima_execucao']); ?><?php if ($vencido && $p['ativo']): ?> <span class="badge badge-warning">Vencido</span><?php endif; ?></td>
                                <td><?php echo $p['ativo'] ? '<span class="badge badge-success">Activo</span>' : '<span class="badge badge-secondary">Inactivo</span>'; ?></td>
                                <td>
                                    <a href="<?php echo $id; ?>" data-toggle="modal" data-target="#edit<?php echo $id; ?>" title="Editar"><i class="icofont icofont-edit px-2"></i></a>
                                    <form action="" method="post" style="display:inline">
                                        <input type="hidden" name="idplano" value="<?php echo $id; ?>">
                                        <button type="submit" class="btn btn-link p-0" name="btnToggleAtivo" title="Activar/Inactivar"><i class="icofont icofont-ui-power"></i></button>
                                    </form>
                                    <a href="<?php echo $id; ?>" data-toggle="modal" data-target="#delete<?php echo $id; ?>" title="Eliminar"><i class="icofont icofont-trash text-danger"></i></a>
                                </td>
                            </tr>

                            <div class="modal fade" id="edit<?php echo $id; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <form action="" method="post" novalidate>
                                            <div class="modal-header">
                                                <h5 class="modal-title">Editar Plano</h5>
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label>Tipo de Manutenção</label>
                                                        <select class="custom-select" name="id_tipo_manutencao">
                                                            <option value="">Selecione...</option>
                                                            <?php foreach ($tiposManutencao as $tm): ?>
                                                                <option value="<?php echo (int)$tm['idtipo_manutencao']; ?>" <?php echo (int)($p['id_tipo_manutencao'] ?? 0) === (int)$tm['idtipo_manutencao'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($tm['nome']); ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label>Técnico Responsável</label>
                                                        <select class="custom-select" name="idusuario_tecnico">
                                                            <option value="">Por atribuir</option>
                                                            <?php foreach ($tecnicos as $t): ?>
                                                                <option value="<?php echo (int)$t['idusuario']; ?>" <?php echo (int)($p['idusuario_tecnico'] ?? 0) === (int)$t['idusuario'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($t['nome'] . ' ' . $t['sobrenome']); ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label>Periodicidade (dias)</label>
                                                        <input type="number" class="form-control" name="periodicidade_dias" min="1" value="<?php echo (int)$p['periodicidade_dias']; ?>">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label>Próxima Execução</label>
                                                        <input type="date" class="form-control" name="proxima_execucao" value="<?php echo htmlspecialchars($p['proxima_execucao']); ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Observações</label>
                                                    <textarea class="form-control" name="observacoes" rows="2"><?php echo htmlspecialchars($p['observacoes'] ?? ''); ?></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                                <input type="hidden" name="idplano" value="<?php echo $id; ?>">
                                                <button class="btn btn-primary" name="btnEditPlano">Guardar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="delete<?php echo $id; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Tens a certeza que queres eliminar?</h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        </div>
                                        <div class="modal-body">Vais eliminar este plano de manutenção preventiva.</div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                            <form action="" method="post">
                                                <input type="hidden" name="idplano" value="<?php echo $id; ?>">
                                                <button class="btn btn-primary" name="btnDeletePlano">Sim</button>
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
