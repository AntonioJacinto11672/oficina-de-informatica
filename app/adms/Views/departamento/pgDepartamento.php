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
<div class="modal fade" id="novoDepartamento" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="post" class="needs-validation" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title">Registar Departamento</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nome <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nome" required>
                    </div>
                    <div class="mb-3">
                        <label>Responsável</label>
                        <input type="text" class="form-control" name="responsavel">
                    </div>
                    <div class="mb-3">
                        <label>Telefone</label>
                        <input type="text" class="form-control" name="telefone">
                    </div>
                    <div class="mb-3">
                        <label>E-mail</label>
                        <input type="email" class="form-control" name="email">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary text-white" name="btnCdsDepartamento">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="container-fluid">
    <?php if (isset($_SESSION['msg'])) { echo $_SESSION['msg']; unset($_SESSION['msg']); } ?>
    <div class="row mt-4 mb-4">
        <a type="button" class="btn-primary btn-sm ml-3 d-none d-md-block text-white" href="" data-toggle="modal" data-target="#novoDepartamento">Novo Departamento</a>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Departamentos</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr><th>Nome</th><th>Responsável</th><th>Telefone</th><th>E-mail</th><th>Equipamentos</th><th>Ação</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lista as $d): if (!isset($d['iddepartamento'])) { continue; } ?>
                            <tr>
                                <td><?= htmlspecialchars($d['nome']) ?></td>
                                <td><?= htmlspecialchars($d['responsavel'] ?? '—') ?></td>
                                <td><?= htmlspecialchars($d['telefone'] ?? '—') ?></td>
                                <td><?= htmlspecialchars($d['email'] ?? '—') ?></td>
                                <td><?= (int)$d['total_equipamentos'] ?></td>
                                <td>
                                    <a href="<?= $d['iddepartamento'] ?>" data-toggle="modal" data-target="#edit<?= $d['iddepartamento'] ?>" title="Editar"><i class="icofont icofont-edit px-2"></i></a>
                                    <a href="<?= $d['iddepartamento'] ?>" data-toggle="modal" data-target="#delete<?= $d['iddepartamento'] ?>" title="Eliminar"><i class="icofont icofont-trash text-danger"></i></a>
                                </td>
                            </tr>
                            <div class="modal fade" id="delete<?= $d['iddepartamento'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Tem a certeza que quer eliminar?</h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        </div>
                                        <div class="modal-body">Vai eliminar o departamento <?= htmlspecialchars($d['nome']) ?>.</div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                            <form action="" method="post">
                                                <input type="hidden" name="iddepartamento" value="<?= $d['iddepartamento'] ?>">
                                                <button class="btn btn-primary" name="btnDeletDepartamento">Sim</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="edit<?= $d['iddepartamento'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form action="" method="post" novalidate>
                                            <div class="modal-header">
                                                <h5 class="modal-title">Editar Departamento</h5>
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label>Nome</label>
                                                    <input type="text" class="form-control" name="nome" value="<?= htmlspecialchars($d['nome']) ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Responsável</label>
                                                    <input type="text" class="form-control" name="responsavel" value="<?= htmlspecialchars($d['responsavel'] ?? '') ?>">
                                                </div>
                                                <div class="mb-3">
                                                    <label>Telefone</label>
                                                    <input type="text" class="form-control" name="telefone" value="<?= htmlspecialchars($d['telefone'] ?? '') ?>">
                                                </div>
                                                <div class="mb-3">
                                                    <label>E-mail</label>
                                                    <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($d['email'] ?? '') ?>">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                                <input type="hidden" name="iddepartamento" value="<?= $d['iddepartamento'] ?>">
                                                <button class="btn btn-primary" name="btnEditDepartamento">Guardar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <?php if (empty($lista)): ?>
                            <tr><td colspan="6" class="text-center text-muted">Não existem dados para apresentar.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
