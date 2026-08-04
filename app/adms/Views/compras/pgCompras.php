<?php
if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}
$lista = $this->dados ?? [];
$fornecedores = $this->dadosAlter['fornecedores'] ?? [];
$produtos = $this->dadosAlter['produtos'] ?? [];
?>
<div class="modal fade" id="novaCompra" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="post" class="needs-validation" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title">Registar Compra</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Fornecedor</label>
                        <select class="custom-select" name="idfornecedor">
                            <option value="">Selecione...</option>
                            <?php foreach ($fornecedores as $f): ?>
                                <option value="<?= (int)$f['idfornecedor'] ?>"><?= htmlspecialchars($f['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Peça / Consumível <span class="text-danger">*</span></label>
                        <select class="custom-select" name="idproduto" required>
                            <option value="">Selecione...</option>
                            <?php foreach ($produtos as $p): ?>
                                <option value="<?= (int)$p['idproduto'] ?>"><?= htmlspecialchars($p['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Quantidade <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="quantidade" value="1" min="1" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Custo Unitário (Kz)</label>
                            <input type="number" step="0.01" class="form-control" name="custo_unitario" value="0">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Observações</label>
                        <textarea class="form-control" name="observacoes"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary text-white" name="btnCdsCompra">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="container-fluid">
    <?php if (isset($_SESSION['msg'])) { echo $_SESSION['msg']; unset($_SESSION['msg']); } ?>
    <div class="row mt-4 mb-4">
        <a type="button" class="btn-primary btn-sm ml-3 d-none d-md-block text-white" href="" data-toggle="modal" data-target="#novaCompra">Nova Compra</a>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Compras a Fornecedores</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr><th>Data</th><th>Peça</th><th>Fornecedor</th><th>Quantidade</th><th>Registado por</th><th>Ação</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lista as $c): if (!isset($c['idcompras'])) { continue; } ?>
                            <tr>
                                <td><?= htmlspecialchars($c['data']) ?></td>
                                <td><?= htmlspecialchars($c['produto'] ?? '—') ?></td>
                                <td><?= htmlspecialchars($c['fornecedor'] ?? '—') ?></td>
                                <td><?= (int)$c['quantidade'] ?></td>
                                <td><?= htmlspecialchars(trim(($c['usuario_nome'] ?? '') . ' ' . ($c['usuario_sobrenome'] ?? '')) ?: '—') ?></td>
                                <td>
                                    <a href="<?= $c['idcompras'] ?>" data-toggle="modal" data-target="#delete<?= $c['idcompras'] ?>" title="Eliminar"><i class="icofont icofont-trash text-danger"></i></a>
                                </td>
                            </tr>
                            <div class="modal fade" id="delete<?= $c['idcompras'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Tem a certeza que quer eliminar?</h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        </div>
                                        <div class="modal-body">Vai eliminar esta compra e repor o stock correspondente.</div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                            <form action="" method="post">
                                                <input type="hidden" name="idcompras" value="<?= $c['idcompras'] ?>">
                                                <button class="btn btn-primary" name="btnDeletCompra">Sim</button>
                                            </form>
                                        </div>
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
