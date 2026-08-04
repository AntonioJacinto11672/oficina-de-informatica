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
<div class="modal fade" id="novofornecedor" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" method="post" class="needs-validation" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title">Registar Fornecedor</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="nome">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" required>
                            <div class="invalid-feedback">Campo obrigatório.</div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="tipo_pessoa">Tipo</label>
                            <select id="tipo_pessoa" class="form-control" name="tipo_pessoa">
                                <option value="Coletiva" selected>Coletiva (empresa)</option>
                                <option value="Singular">Singular</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nif">NIF</label>
                            <input type="text" class="form-control" id="nif" name="nif" required>
                            <div class="invalid-feedback">Campo obrigatório.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email">E-mail</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="morada">Morada</label>
                            <input type="text" class="form-control" id="morada" name="morada">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telefone">Telefone</label>
                            <input type="text" class="form-control" id="telefone" name="telefone">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary text-white" name="btnCdsFornecedor">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="container-fluid">
    <?php if (isset($_SESSION['msg'])) { echo $_SESSION['msg']; unset($_SESSION['msg']); } ?>
    <div class="row mt-4 mb-4">
        <a type="button" class="btn-primary btn-sm ml-3 d-none d-md-block text-white" href="" data-toggle="modal" data-target="#novofornecedor">Novo Fornecedor</a>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Fornecedores</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nome</th><th>Tipo</th><th>NIF</th><th>Telefone</th><th>E-mail</th><th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lista as $f): if (!isset($f['idfornecedor'])) { continue; } ?>
                            <tr>
                                <td><?= htmlspecialchars($f['nome']) ?></td>
                                <td><?= htmlspecialchars($f['tipo_pessoa']) ?></td>
                                <td><?= htmlspecialchars($f['nif'] ?? '—') ?></td>
                                <td><?= htmlspecialchars($f['telefone'] ?? '—') ?></td>
                                <td><?= htmlspecialchars($f['email'] ?? '—') ?></td>
                                <td>
                                    <a href="<?= $f['idfornecedor'] ?>" data-toggle="modal" data-target="#edit<?= $f['idfornecedor'] ?>" title="Editar"><i class="icofont icofont-edit px-2"></i></a>
                                    <a href="<?= $f['idfornecedor'] ?>" data-toggle="modal" data-target="#delete<?= $f['idfornecedor'] ?>" title="Eliminar"><i class="icofont icofont-trash text-danger"></i></a>
                                </td>
                            </tr>
                            <div class="modal fade" id="delete<?= $f['idfornecedor'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Tem a certeza que quer eliminar?</h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        </div>
                                        <div class="modal-body">Vai eliminar o fornecedor <?= htmlspecialchars($f['nome']) ?>.</div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                            <form action="" method="post">
                                                <input type="hidden" name="idfornecedor" value="<?= $f['idfornecedor'] ?>">
                                                <button class="btn btn-primary" name="btnDeletFornecedor">Sim</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="edit<?= $f['idfornecedor'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <form action="" method="post" novalidate>
                                            <div class="modal-header">
                                                <h5 class="modal-title">Editar Fornecedor</h5>
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-8 mb-3">
                                                        <label>Nome</label>
                                                        <input type="text" class="form-control" name="nome" value="<?= htmlspecialchars($f['nome']) ?>" required>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label>Tipo</label>
                                                        <select class="form-control" name="tipo_pessoa">
                                                            <option value="Coletiva" <?= $f['tipo_pessoa'] === 'Coletiva' ? 'selected' : '' ?>>Coletiva (empresa)</option>
                                                            <option value="Singular" <?= $f['tipo_pessoa'] === 'Singular' ? 'selected' : '' ?>>Singular</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label>NIF</label>
                                                        <input type="text" class="form-control" name="nif" value="<?= htmlspecialchars($f['nif'] ?? '') ?>" required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label>E-mail</label>
                                                        <input type="email" class="form-control" name="emailnovo" value="<?= htmlspecialchars($f['email'] ?? '') ?>">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label>Morada</label>
                                                        <input type="text" class="form-control" name="morada" value="<?= htmlspecialchars($f['morada'] ?? '') ?>">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label>Telefone</label>
                                                        <input type="text" class="form-control" name="telefone" value="<?= htmlspecialchars($f['telefone'] ?? '') ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                                <input type="hidden" name="idfornecedor" value="<?= $f['idfornecedor'] ?>">
                                                <button class="btn btn-primary" name="btnEditFornecedor">Guardar</button>
                                            </div>
                                        </form>
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
