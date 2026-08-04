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
            <h6 class="m-0 font-weight-bold text-primary">Utilizadores do Sistema</h6>
        </div>
        <div class="card-body">
            <p class="text-muted small">Contas de Técnico são criadas a partir do módulo <a href="<?= URLADM ?>tecnico">Técnicos</a>. Aqui pode ativar/desativar contas e alterar o papel de um utilizador.</p>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr><th>Nome</th><th>E-mail</th><th>Telefone</th><th>Papel</th><th>Estado</th><th>Ação</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lista as $u): ?>
                            <tr>
                                <td><?= htmlspecialchars($u['nome'] . ' ' . ($u['sobrenome'] ?? '')) ?></td>
                                <td><?= htmlspecialchars($u['email']) ?></td>
                                <td><?= htmlspecialchars($u['telefone'] ?? '—') ?></td>
                                <td>
                                    <span class="badge badge-<?= $u['nivel'] === 'gerente' ? 'primary' : 'secondary' ?>">
                                        <?= $u['nivel'] === 'gerente' ? 'Gerente' : 'Técnico' ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-<?= $u['st_conta'] === 'Ativada' ? 'success' : 'danger' ?>"><?= htmlspecialchars($u['st_conta']) ?></span>
                                </td>
                                <td>
                                    <?php if ((int)$u['idusuario'] !== (int)($_SESSION['idlogado'] ?? 0)): ?>
                                        <form action="" method="post" style="display:inline" onsubmit="return confirm('Alterar o estado desta conta?');">
                                            <input type="hidden" name="idusuario" value="<?= (int)$u['idusuario'] ?>">
                                            <button type="submit" class="btn btn-link p-0" name="btnAlterarEstado" title="Ativar/Desativar"><i class="fas fa-check-square <?= $u['st_conta'] === 'Ativada' ? 'text-success' : 'text-danger' ?> px-1"></i></button>
                                        </form>
                                        <a href="<?= (int)$u['idusuario'] ?>" data-toggle="modal" data-target="#nivel<?= (int)$u['idusuario'] ?>" title="Alterar Papel"><i class="icofont icofont-ui-user px-1"></i></a>
                                    <?php else: ?>
                                        <span class="text-muted">A sua conta</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php if ((int)$u['idusuario'] !== (int)($_SESSION['idlogado'] ?? 0)): ?>
                                <div class="modal fade" id="nivel<?= (int)$u['idusuario'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="" method="post" novalidate>
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Alterar Papel — <?= htmlspecialchars($u['nome']) ?></h5>
                                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                </div>
                                                <div class="modal-body">
                                                    <select class="custom-select" name="nivel">
                                                        <option value="gerente" <?= $u['nivel'] === 'gerente' ? 'selected' : '' ?>>Gerente</option>
                                                        <option value="tecnico" <?= $u['nivel'] === 'tecnico' ? 'selected' : '' ?>>Técnico</option>
                                                    </select>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                                    <input type="hidden" name="idusuario" value="<?= (int)$u['idusuario'] ?>">
                                                    <button class="btn btn-primary" name="btnEditarNivel">Guardar</button>
                                                </div>
                                            </form>
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
