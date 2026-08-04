<?php
if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}
$lista = $this->dados ?? [];
?>
<div class="container-fluid">
    <?php if (isset($_SESSION['msg'])) { echo $_SESSION['msg']; unset($_SESSION['msg']); } ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Stock Baixo</h6>
        </div>
        <div class="card-body">
            <?php if (empty($lista)): ?>
                <div class="alert alert-success text-center mb-0">Não existem peças abaixo do stock mínimo.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Nome</th><th>Referência</th><th>Categoria</th><th>Fornecedor</th><th>Stock Atual</th><th>Stock Mínimo</th><th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lista as $p): ?>
                                <tr class="table-danger">
                                    <td><?= htmlspecialchars($p['nome']) ?></td>
                                    <td><?= htmlspecialchars($p['referencia'] ?? '—') ?></td>
                                    <td><?= htmlspecialchars($p['categoria'] ?? '—') ?></td>
                                    <td><?= htmlspecialchars($p['fornecedor'] ?? '—') ?></td>
                                    <td class="font-weight-bold"><?= (int)$p['estoque'] ?></td>
                                    <td><?= (int)$p['estoque_minimo'] ?></td>
                                    <td>
                                        <a href="<?= $p['idproduto'] ?>" data-toggle="modal" data-target="#mais<?= $p['idproduto'] ?>" title="Adicionar Stock"><i class="icofont icofont-plus text-success px-2"></i></a>
                                    </td>
                                </tr>
                                <div class="modal fade" id="mais<?= $p['idproduto'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <form action="" method="post" novalidate>
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Adicionar Stock — <?= htmlspecialchars($p['nome']) ?></h5>
                                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                </div>
                                                <div class="modal-body">
                                                    <label>Quantidade a adicionar</label>
                                                    <input type="number" class="form-control" name="estoque" value="1" min="1" required>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                                    <input type="hidden" name="idproduto" value="<?= $p['idproduto'] ?>">
                                                    <button class="btn btn-primary" name="btnaddEstoque">Confirmar</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
