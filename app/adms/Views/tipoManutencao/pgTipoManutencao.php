<?php
if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}
$lista = $this->dados ?? [];
if (isset($lista['form'])) {
    $lista = [];
}
?>
<div class="modal fade" id="novoTipo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="post" class="needs-validation" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title">Registar Tipo de Manutenção</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nome <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nome" required>
                    </div>
                    <div class="mb-3">
                        <label>Categoria</label>
                        <select class="form-control" name="categoria">
                            <option value="Corretiva">Corretiva</option>
                            <option value="Preventiva">Preventiva</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Descrição</label>
                        <textarea class="form-control" name="descricao"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary text-white" name="btnCdsTipoManutencao">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="container-fluid">
    <?php if (isset($_SESSION['msg'])) { echo $_SESSION['msg']; unset($_SESSION['msg']); } ?>
    <div class="row mt-4 mb-4">
        <a type="button" class="btn-primary btn-sm ml-3 d-none d-md-block text-white" href="" data-toggle="modal" data-target="#novoTipo">Novo Tipo de Manutenção</a>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tipos de Manutenção</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr><th>Nome</th><th>Categoria</th><th>Descrição</th><th>Ação</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lista as $t): if (!isset($t['idtipo_manutencao'])) { continue; } ?>
                            <tr>
                                <td><?= htmlspecialchars($t['nome']) ?></td>
                                <td><span class="badge badge-<?= $t['categoria'] === 'Preventiva' ? 'info' : 'warning' ?>"><?= htmlspecialchars($t['categoria']) ?></span></td>
                                <td><?= htmlspecialchars($t['descricao'] ?? '—') ?></td>
                                <td>
                                    <a href="<?= $t['idtipo_manutencao'] ?>" data-toggle="modal" data-target="#edit<?= $t['idtipo_manutencao'] ?>" title="Editar"><i class="icofont icofont-edit px-2"></i></a>
                                    <a href="<?= $t['idtipo_manutencao'] ?>" data-toggle="modal" data-target="#delete<?= $t['idtipo_manutencao'] ?>" title="Eliminar"><i class="icofont icofont-trash text-danger"></i></a>
                                </td>
                            </tr>
                            <div class="modal fade" id="delete<?= $t['idtipo_manutencao'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Tem a certeza que quer eliminar?</h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        </div>
                                        <div class="modal-body">Vai eliminar o tipo de manutenção <?= htmlspecialchars($t['nome']) ?>.</div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                            <form action="" method="post">
                                                <input type="hidden" name="idtipo_manutencao" value="<?= $t['idtipo_manutencao'] ?>">
                                                <button class="btn btn-primary" name="btnDeletTipoManutencao">Sim</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="edit<?= $t['idtipo_manutencao'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form action="" method="post" novalidate>
                                            <div class="modal-header">
                                                <h5 class="modal-title">Editar Tipo de Manutenção</h5>
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label>Nome</label>
                                                    <input type="text" class="form-control" name="nome" value="<?= htmlspecialchars($t['nome']) ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Categoria</label>
                                                    <select class="form-control" name="categoria">
                                                        <option value="Corretiva" <?= $t['categoria'] === 'Corretiva' ? 'selected' : '' ?>>Corretiva</option>
                                                        <option value="Preventiva" <?= $t['categoria'] === 'Preventiva' ? 'selected' : '' ?>>Preventiva</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Descrição</label>
                                                    <textarea class="form-control" name="descricao"><?= htmlspecialchars($t['descricao'] ?? '') ?></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                                <input type="hidden" name="idtipo_manutencao" value="<?= $t['idtipo_manutencao'] ?>">
                                                <button class="btn btn-primary" name="btnEditTipoManutencao">Guardar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <?php if (empty($lista)): ?>
                            <tr><td colspan="4" class="text-center text-muted">Não existem dados para apresentar.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
