<?php
if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}
$lista = $this->dados ?? [];
if (isset($lista['form'])) {
    $lista = [];
}
$categorias = $this->dadosPaginacao ?? [];
$fornecedores = $this->dadosAlter ?? [];
?>
<div class="modal fade" id="novoproduto" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" method="post" class="needs-validation" enctype="multipart/form-data" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title">Registar Peça / Consumível</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nome">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" required>
                            <div class="invalid-feedback">Campo obrigatório.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="referencia">Referência</label>
                            <input type="text" class="form-control" id="referencia" name="referencia">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="categoria">Categoria</label>
                            <select class="custom-select" id="categoria" name="categoria" required>
                                <option selected disabled value="">Escolha...</option>
                                <?php foreach ($categorias as $c): ?>
                                    <option value="<?= (int)$c['idcategoria'] ?>"><?= htmlspecialchars($c['nome']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="fornecedor">Fornecedor</label>
                            <select class="custom-select" id="fornecedor" name="fornecedor">
                                <option selected value="">Escolha...</option>
                                <?php foreach ($fornecedores as $f): ?>
                                    <option value="<?= (int)$f['idfornecedor'] ?>"><?= htmlspecialchars($f['nome']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="estoque">Stock Inicial</label>
                            <input type="number" class="form-control" id="estoque" name="estoque" value="0" min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="estoque_minimo">Stock Mínimo</label>
                            <input type="number" class="form-control" id="estoque_minimo" name="estoque_minimo" value="5" min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="custo_aquisicao">Custo de Aquisição (Kz)</label>
                            <input type="number" step="0.01" class="form-control" id="custo_aquisicao" name="custo_aquisicao" value="0">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-7 mb-3">
                            <label for="descricao">Descrição</label>
                            <textarea class="form-control" id="descricao" name="descricao"></textarea>
                        </div>
                        <div class="col-md-5 mb-3">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="foto" name="foto">
                                <label class="custom-file-label" for="foto" data-browse="Procurar">Fotografia (opcional)</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary text-white" name="btnCdsProduto">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="container-fluid">
    <?php if (isset($_SESSION['msg'])) { echo $_SESSION['msg']; unset($_SESSION['msg']); } ?>
    <div class="row mt-4 mb-4">
        <a type="button" class="btn-primary btn-sm ml-3 d-none d-md-block text-white" href="" data-toggle="modal" data-target="#novoproduto">Nova Peça</a>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Peças e Consumíveis</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nome</th><th>Referência</th><th>Categoria</th><th>Fornecedor</th><th>Stock</th><th>Stock Mínimo</th><th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lista as $p): if (!isset($p['idproduto'])) { continue; } ?>
                            <tr>
                                <td><?= htmlspecialchars($p['nome']) ?></td>
                                <td><?= htmlspecialchars($p['referencia'] ?? '—') ?></td>
                                <td><?= htmlspecialchars($p['categoria'] ?? '—') ?></td>
                                <td><?= htmlspecialchars($p['fornecedor'] ?? '—') ?></td>
                                <td class="<?= $p['estoque'] < $p['estoque_minimo'] ? 'text-danger font-weight-bold' : '' ?>"><?= (int)$p['estoque'] ?></td>
                                <td><?= (int)$p['estoque_minimo'] ?></td>
                                <td>
                                    <a href="<?= $p['idproduto'] ?>" data-toggle="modal" data-target="#edit<?= $p['idproduto'] ?>" title="Editar"><i class="icofont icofont-edit px-1"></i></a>
                                    <a href="<?= $p['idproduto'] ?>" data-toggle="modal" data-target="#delete<?= $p['idproduto'] ?>" title="Eliminar"><i class="icofont icofont-trash text-danger px-1"></i></a>
                                    <a href="<?= $p['idproduto'] ?>" data-toggle="modal" data-target="#info<?= $p['idproduto'] ?>" title="Descrição"><i class="icofont icofont-info-circle text-primary px-1"></i></a>
                                    <a href="<?= $p['idproduto'] ?>" data-toggle="modal" data-target="#mais<?= $p['idproduto'] ?>" title="Adicionar Stock"><i class="icofont icofont-plus text-success px-1"></i></a>
                                    <a href="<?= URLADM ?>movimentoEstoque?produto=<?= $p['idproduto'] ?>" title="Histórico de Movimentações"><i class="icofont icofont-history text-info px-1"></i></a>
                                </td>
                            </tr>
                            <div class="modal fade" id="delete<?= $p['idproduto'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Tem a certeza que quer eliminar?</h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        </div>
                                        <div class="modal-body">Vai eliminar a peça <?= htmlspecialchars($p['nome']) ?>.</div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                            <form action="" method="post">
                                                <input type="hidden" name="idproduto" value="<?= $p['idproduto'] ?>">
                                                <button class="btn btn-primary" name="btnDeletProduto">Sim</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="edit<?= $p['idproduto'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <form action="" method="post" novalidate>
                                            <div class="modal-header">
                                                <h5 class="modal-title">Editar Peça</h5>
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label>Nome</label>
                                                        <input type="text" class="form-control" name="nome" value="<?= htmlspecialchars($p['nome']) ?>" required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label>Referência</label>
                                                        <input type="text" class="form-control" name="referencia" value="<?= htmlspecialchars($p['referencia'] ?? '') ?>">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label>Categoria</label>
                                                        <select class="custom-select" name="categoria">
                                                            <option value="">Escolha...</option>
                                                            <?php foreach ($categorias as $c): ?>
                                                                <option value="<?= (int)$c['idcategoria'] ?>" <?= (int)($p['idcategoria'] ?? 0) === (int)$c['idcategoria'] ? 'selected' : '' ?>><?= htmlspecialchars($c['nome']) ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label>Fornecedor</label>
                                                        <select class="custom-select" name="fornecedor">
                                                            <option value="">Escolha...</option>
                                                            <?php foreach ($fornecedores as $f): ?>
                                                                <option value="<?= (int)$f['idfornecedor'] ?>" <?= (int)($p['idfornecedor'] ?? 0) === (int)$f['idfornecedor'] ? 'selected' : '' ?>><?= htmlspecialchars($f['nome']) ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label>Stock Mínimo</label>
                                                        <input type="number" class="form-control" name="estoque_minimo" value="<?= (int)$p['estoque_minimo'] ?>" min="0">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label>Custo de Aquisição (Kz)</label>
                                                        <input type="number" step="0.01" class="form-control" name="custo_aquisicao" value="<?= htmlspecialchars($p['custo_aquisicao'] ?? 0) ?>">
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Descrição</label>
                                                    <textarea class="form-control" name="descricao"><?= htmlspecialchars($p['descricao'] ?? '') ?></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                                <input type="hidden" name="idproduto" value="<?= $p['idproduto'] ?>">
                                                <button class="btn btn-primary" name="btnEditProduto">Guardar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="info<?= $p['idproduto'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Descrição da Peça</h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><?= nl2br(htmlspecialchars($p['descricao'] ?? 'Sem descrição.')) ?></p>
                                            <p class="text-muted small">Última atualização: <?= !empty($p['modified']) ? date('d/m/Y H:i', strtotime($p['modified'])) : (!empty($p['created']) ? date('d/m/Y H:i', strtotime($p['created'])) : '—') ?></p>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Fechar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
        </div>
    </div>
</div>
