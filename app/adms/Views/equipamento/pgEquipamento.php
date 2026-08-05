<?php
if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}
$lista = $this->dados ?? [];
if (isset($lista['form'])) {
    $lista = [];
}
$departamentos = $this->dadosAlter['departamentos'] ?? [];
$categorias = $this->dadosAlter['categorias'] ?? [];
$fornecedores = $this->dadosAlter['fornecedores'] ?? [];
$responsaveis = $this->dadosAlter['responsaveis'] ?? [];
$estados = ['Disponível', 'Em Manutenção', 'Avariado', 'Abatido'];

if (!function_exists('equipamento_badge_estado')) {
    function equipamento_badge_estado($estado) {
        $classe = ['Disponível' => 'success', 'Em Manutenção' => 'info', 'Avariado' => 'warning', 'Abatido' => 'secondary'][$estado] ?? 'secondary';
        return '<span class="badge badge-' . $classe . '">' . htmlspecialchars($estado) . '</span>';
    }
}

if (!function_exists('equipamento_campos_form')):
function equipamento_campos_form($eq, $departamentos, $categorias, $fornecedores, $responsaveis, $estados) { ?>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label>Código Patrimonial</label>
            <input type="text" class="form-control" name="codigo" value="<?= htmlspecialchars($eq['codigo'] ?? '') ?>">
        </div>
        <div class="col-md-4 mb-3">
            <label>Nº de Patrimônio</label>
            <input type="text" class="form-control" name="patrimonio" value="<?= htmlspecialchars($eq['patrimonio'] ?? '') ?>">
        </div>
        <div class="col-md-4 mb-3">
            <label>Número de Série <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="numero_serie" value="<?= htmlspecialchars($eq['numero_serie'] ?? '') ?>" required>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label>Nome / Descrição <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="nome" value="<?= htmlspecialchars($eq['nome'] ?? '') ?>" required>
        </div>
        <div class="col-md-3 mb-3">
            <label>Marca</label>
            <input type="text" class="form-control" name="marca" value="<?= htmlspecialchars($eq['marca'] ?? '') ?>">
        </div>
        <div class="col-md-3 mb-3">
            <label>Modelo</label>
            <input type="text" class="form-control" name="modelo" value="<?= htmlspecialchars($eq['modelo'] ?? '') ?>">
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label>Tipo</label>
            <select class="custom-select" name="idcategoria_equipamento">
                <option value="">Selecione...</option>
                <?php foreach ($categorias as $c): ?>
                    <option value="<?= (int)$c['idcategoria_equipamento'] ?>" <?= (int)($eq['idcategoria_equipamento'] ?? 0) === (int)$c['idcategoria_equipamento'] ? 'selected' : '' ?>><?= htmlspecialchars($c['nome']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4 mb-3">
            <label>Estado</label>
            <select class="custom-select" name="estado">
                <?php foreach ($estados as $e): ?>
                    <option value="<?= $e ?>" <?= ($eq['estado'] ?? 'Disponível') === $e ? 'selected' : '' ?>><?= $e ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4 mb-3">
            <label>Localização</label>
            <input type="text" class="form-control" name="localizacao" value="<?= htmlspecialchars($eq['localizacao'] ?? '') ?>" placeholder="Ex: Bloco A, Sala 12">
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label>Departamento</label>
            <select class="custom-select" name="iddepartamento">
                <option value="">Selecione...</option>
                <?php foreach ($departamentos as $d): ?>
                    <option value="<?= (int)$d['iddepartamento'] ?>" <?= (int)($eq['iddepartamento'] ?? 0) === (int)$d['iddepartamento'] ? 'selected' : '' ?>><?= htmlspecialchars($d['nome']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4 mb-3">
            <label>Responsável</label>
            <select class="custom-select" name="idresponsavel">
                <option value="">Selecione...</option>
                <?php foreach ($responsaveis as $r): ?>
                    <option value="<?= (int)$r['idusuario'] ?>" <?= (int)($eq['idresponsavel'] ?? 0) === (int)$r['idusuario'] ? 'selected' : '' ?>><?= htmlspecialchars($r['nome'] . ' ' . $r['sobrenome']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4 mb-3">
            <label>Fornecedor</label>
            <select class="custom-select" name="idfornecedor">
                <option value="">Selecione...</option>
                <?php foreach ($fornecedores as $f): ?>
                    <option value="<?= (int)$f['idfornecedor'] ?>" <?= (int)($eq['idfornecedor'] ?? 0) === (int)$f['idfornecedor'] ? 'selected' : '' ?>><?= htmlspecialchars($f['nome']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label>Data de Aquisição</label>
            <input type="date" class="form-control" name="data_aquisicao" max="<?= date('Y-m-d') ?>" value="<?= htmlspecialchars($eq['data_aquisicao'] ?? '') ?>">
        </div>
        <div class="col-md-6 mb-3">
            <label>Garantia Até</label>
            <input type="date" class="form-control" name="garantia_ate" value="<?= htmlspecialchars($eq['garantia_ate'] ?? '') ?>">
        </div>
    </div>
    <div class="mb-3">
        <label>Observações</label>
        <textarea class="form-control" name="observacoes"><?= htmlspecialchars($eq['observacoes'] ?? '') ?></textarea>
    </div>
<?php }
endif;
?>
<div class="modal fade" id="novoEquipamento" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" method="post" class="needs-validation" novalidate>
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Registar Equipamento</h5>
                    <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body"><?php equipamento_campos_form([], $departamentos, $categorias, $fornecedores, $responsaveis, $estados); ?></div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary text-white" name="btnCdsEquipamento">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="container-fluid">
    <?php if (isset($_SESSION['msg'])) { echo $_SESSION['msg']; unset($_SESSION['msg']); } ?>
    <div class="row mt-4 mb-4">
        <a type="button" class="btn-primary btn-sm ml-3 d-none d-md-block text-white" href="" data-toggle="modal" data-target="#novoEquipamento">Novo Equipamento</a>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Equipamentos</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Código</th><th>Nome</th><th>Tipo</th><th>Nº Série</th><th>Departamento</th><th>Estado</th><th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lista as $eq): if (!isset($eq['idequipamento'])) { continue; } ?>
                            <tr>
                                <td><?= htmlspecialchars($eq['codigo'] ?? '—') ?></td>
                                <td><?= htmlspecialchars($eq['nome']) ?></td>
                                <td><?= htmlspecialchars($eq['categoria_equipamento'] ?? '—') ?></td>
                                <td><?= htmlspecialchars($eq['numero_serie']) ?></td>
                                <td><?= htmlspecialchars($eq['departamento'] ?? '—') ?></td>
                                <td><?= equipamento_badge_estado($eq['estado']) ?></td>
                                <td>
                                    <a href="<?= URLADM ?>equipamento?historico=<?= $eq['idequipamento'] ?>" title="Histórico de Manutenção"><i class="icofont icofont-history px-1"></i></a>
                                    <a href="<?= $eq['idequipamento'] ?>" data-toggle="modal" data-target="#edit<?= $eq['idequipamento'] ?>" title="Editar"><i class="icofont icofont-edit px-1"></i></a>
                                    <a href="<?= $eq['idequipamento'] ?>" data-toggle="modal" data-target="#delete<?= $eq['idequipamento'] ?>" title="Eliminar"><i class="icofont icofont-trash text-danger px-1"></i></a>
                                </td>
                            </tr>
                            <div class="modal fade" id="delete<?= $eq['idequipamento'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Tem a certeza que quer eliminar?</h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        </div>
                                        <div class="modal-body">Vai eliminar o equipamento <?= htmlspecialchars($eq['nome']) ?> (<?= htmlspecialchars($eq['numero_serie']) ?>).</div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                            <form action="" method="post">
                                                <input type="hidden" name="idequipamento" value="<?= $eq['idequipamento'] ?>">
                                                <button class="btn btn-primary" name="btnDeleteEquipamento">Sim</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="edit<?= $eq['idequipamento'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <form action="" method="post" novalidate>
                                            <div class="modal-header">
                                                <h5 class="modal-title">Editar Equipamento</h5>
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            </div>
                                            <div class="modal-body"><?php equipamento_campos_form($eq, $departamentos, $categorias, $fornecedores, $responsaveis, $estados); ?></div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                                <input type="hidden" name="idequipamento" value="<?= $eq['idequipamento'] ?>">
                                                <button class="btn btn-primary" name="btnEditEquipamento">Guardar</button>
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
