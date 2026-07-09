<?php
if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}
$lista = $this->dados['lista'] ?? [];
$tecnicos = $this->dadosAlter['tecnicos'] ?? [];
$tiposServico = $this->dadosAlter['tiposServico'] ?? [];
?>
<div class="modal fade" id="novaConfiguracao" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" method="post" class="needs-validation" novalidate>
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-percentage mr-2"></i>Nova Configuração de Comissão</h5>
                    <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-5">
                            <label>Técnico</label>
                            <select class="custom-select" name="idusuario_tecnico">
                                <option value="">Todos (aplica a qualquer técnico)</option>
                                <?php foreach ($tecnicos as $t): ?>
                                    <option value="<?php echo (int)$t['idusuario']; ?>"><?php echo htmlspecialchars($t['nome'] . ' ' . $t['sobrenome']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Tipo de Serviço</label>
                            <select class="custom-select" name="id_tipo_servico">
                                <option value="">Todos os serviços</option>
                                <?php foreach ($tiposServico as $ts): ?>
                                    <option value="<?php echo (int)$ts['idtipo_servico']; ?>"><?php echo htmlspecialchars($ts['nome']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Percentagem (%)</label>
                            <input type="text" class="form-control" name="percentual" placeholder="Ex: 35" required>
                        </div>
                    </div>
                    <small class="form-text text-muted">Prioridade ao calcular: técnico+serviço específico &gt; técnico &gt; serviço &gt; global.</small>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary" name="btnCdsConfiguracao"><i class="fas fa-save mr-1"></i>Criar</button>
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
        <a type="button" class="btn-primary btn-sm ml-3 d-none d-md-block text-white" href="" data-toggle="modal" data-target="#novaConfiguracao"> Nova Configuração</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Configuração de Comissões</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr><th>Técnico</th><th>Tipo de Serviço</th><th>Percentagem</th><th>Estado</th><th>Acção</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lista as $c): $id = (int)$c['idconfig']; $global = empty($c['idusuario_tecnico']) && empty($c['id_tipo_servico']); ?>
                            <tr>
                                <td><?php echo $c['tecnico_nome'] ? htmlspecialchars($c['tecnico_nome'] . ' ' . $c['tecnico_sobrenome']) : '<em class="text-muted">Todos</em>'; ?></td>
                                <td><?php echo $c['tipo_servico'] ? htmlspecialchars($c['tipo_servico']) : '<em class="text-muted">Todos</em>'; ?></td>
                                <td><strong><?php echo number_format((float)$c['percentual'], 2); ?>%</strong></td>
                                <td><?php echo $c['ativo'] ? '<span class="badge badge-success">Activa</span>' : '<span class="badge badge-secondary">Inactiva</span>'; ?></td>
                                <td>
                                    <a href="<?php echo $id; ?>" data-toggle="modal" data-target="#edit<?php echo $id; ?>" title="Editar"><i class="icofont icofont-edit px-2"></i></a>
                                    <?php if (!$global): ?>
                                        <form action="" method="post" style="display:inline">
                                            <input type="hidden" name="idconfig" value="<?php echo $id; ?>">
                                            <button type="submit" class="btn btn-link p-0" name="btnToggleAtivo" title="Activar/Inactivar"><i class="icofont icofont-ui-power"></i></button>
                                        </form>
                                        <a href="<?php echo $id; ?>" data-toggle="modal" data-target="#delete<?php echo $id; ?>" title="Eliminar"><i class="icofont icofont-trash text-danger"></i></a>
                                    <?php else: ?>
                                        <span class="text-muted small">(global)</span>
                                    <?php endif; ?>
                                </td>
                            </tr>

                            <div class="modal fade" id="edit<?php echo $id; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form action="" method="post" novalidate>
                                            <div class="modal-header">
                                                <h5 class="modal-title">Editar Percentagem</h5>
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            </div>
                                            <div class="modal-body">
                                                <label>Percentagem (%)</label>
                                                <input type="text" class="form-control" name="percentual" value="<?php echo number_format((float)$c['percentual'], 2); ?>" required>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                                <input type="hidden" name="idconfig" value="<?php echo $id; ?>">
                                                <button class="btn btn-primary" name="btnEditConfiguracao">Guardar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <?php if (!$global): ?>
                            <div class="modal fade" id="delete<?php echo $id; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Tens a certeza que queres eliminar?</h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        </div>
                                        <div class="modal-body">Vais eliminar esta configuração de comissão.</div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                            <form action="" method="post">
                                                <input type="hidden" name="idconfig" value="<?php echo $id; ?>">
                                                <button class="btn btn-primary" name="btnDeleteConfiguracao">Sim</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
