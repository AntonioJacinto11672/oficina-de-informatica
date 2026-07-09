<?php
if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}
$lista = $this->dados['lista'] ?? [];
$equipamentos = $this->dadosAlter['equipamentos'] ?? [];
$souAdmin = isset($_SESSION['usuario']) && $_SESSION['usuario'] === 'adimin';

if (!function_exists('abatimento_badge_estado')) {
    function abatimento_badge_estado($estado) {
        $classe = ['Solicitado' => 'warning', 'Aprovado' => 'success', 'Rejeitado' => 'danger'][$estado] ?? 'secondary';
        return '<span class="badge badge-' . $classe . '">' . htmlspecialchars($estado) . '</span>';
    }
}
?>
<div class="modal fade" id="novoAbatimento" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" method="post" class="needs-validation" novalidate>
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-box-archive mr-2"></i>Solicitar Abatimento</h5>
                    <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="id_equipamento">Equipamento <span class="text-danger">*</span></label>
                        <select class="custom-select" id="id_equipamento" name="id_equipamento" required>
                            <option value="">Selecione...</option>
                            <?php foreach ($equipamentos as $eq): ?>
                                <option value="<?php echo (int)$eq['idequipamento']; ?>"><?php echo htmlspecialchars($eq['numero_serie'] . ' — ' . $eq['marca'] . ' ' . $eq['modelo']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="motivo">Motivo <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="motivo" name="motivo" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary" name="btnSolicitarAbatimento"><i class="fas fa-save mr-1"></i>Solicitar</button>
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
        <a type="button" class="btn-primary btn-sm ml-3 d-none d-md-block text-white" href="" data-toggle="modal" data-target="#novoAbatimento"> Solicitar Abatimento</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Pedidos de Abatimento</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Equipamento</th><th>Motivo</th><th>Solicitante</th><th>Estado</th><th>Decisão</th>
                            <?php if ($souAdmin): ?><th>Acção</th><?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lista as $a): $id = (int)$a['idabatimento']; ?>
                            <tr>
                                <td><?php echo htmlspecialchars($a['numero_serie'] . ' — ' . $a['marca'] . ' ' . $a['modelo']); ?></td>
                                <td><?php echo htmlspecialchars($a['motivo']); ?></td>
                                <td><?php echo htmlspecialchars(trim(($a['solicitante_nome'] ?? '') . ' ' . ($a['solicitante_sobrenome'] ?? '')) ?: '—'); ?></td>
                                <td><?php echo abatimento_badge_estado($a['estado']); ?></td>
                                <td><?php echo $a['data_decisao'] ? htmlspecialchars($a['data_decisao']) . ' — ' . htmlspecialchars(trim(($a['aprovador_nome'] ?? '') . ' ' . ($a['aprovador_sobrenome'] ?? ''))) : '—'; ?></td>
                                <?php if ($souAdmin): ?>
                                    <td>
                                        <?php if ($a['estado'] === 'Solicitado'): ?>
                                            <form action="" method="post" style="display:inline" onsubmit="return confirm('Aprovar este abatimento? O equipamento passa a Abatido.');">
                                                <input type="hidden" name="idabatimento" value="<?php echo $id; ?>">
                                                <button type="submit" class="btn btn-link p-0 text-success" name="btnAprovarAbatimento" title="Aprovar"><i class="icofont icofont-check-circled"></i></button>
                                            </form>
                                            <form action="" method="post" style="display:inline" onsubmit="return confirm('Rejeitar este abatimento?');">
                                                <input type="hidden" name="idabatimento" value="<?php echo $id; ?>">
                                                <button type="submit" class="btn btn-link p-0 text-danger" name="btnRejeitarAbatimento" title="Rejeitar"><i class="icofont icofont-close-circled"></i></button>
                                            </form>
                                        <?php else: ?>
                                            <span class="text-muted">—</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($lista)): ?>
                            <tr><td colspan="6" class="text-center text-muted">Sem pedidos de abatimento.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
