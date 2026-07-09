<?php
if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}
$valorForm = [];
if (isset($this->dados['form']) && is_array($this->dados['form'])) {
    $valorForm = $this->dados['form'];
}
$lista = $this->dados['lista'] ?? [];
?>
<div class="modal fade" id="novaCategoriaEquipamento" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" method="post" class="needs-validation" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cadastrar Categoria de Equipamento</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12 mb-3">
                        <label for="nome">Nome</label>
                        <input type="text" class="form-control" name="nome" placeholder="Ex: Projetor" value="<?php echo $valorForm['nome'] ?? ''; ?>" required>
                        <div class="invalid-feedback">Insira o Nome.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary text-white" name="btnCdsCategoriaEquipamento">Salvar</button>
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
        <a type="button" class="btn-primary btn-sm ml-3 d-none d-md-block text-white" href="" data-toggle="modal" data-target="#novaCategoriaEquipamento"> Nova Categoria</a>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Categorias de Equipamento</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr><th>Nome</th><th>Equipamentos</th><th>Acção</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lista as $c): $id = (int)$c['idcategoria_equipamento']; ?>
                            <tr>
                                <td><?php echo htmlspecialchars($c['nome']); ?></td>
                                <td><?php echo (int)$c['total_equipamentos']; ?></td>
                                <td>
                                    <a href="<?php echo $id; ?>" data-toggle="modal" data-target="#edit<?php echo $id; ?>"><i class="icofont icofont-edit px-2"></i></a>
                                    <a href="<?php echo $id; ?>" data-toggle="modal" data-target="#delete<?php echo $id; ?>"><i class="icofont icofont-trash text-danger"></i></a>
                                </td>
                            </tr>
                            <div class="modal fade" id="delete<?php echo $id; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Tens a certeza que queres apagar?</h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                        </div>
                                        <div class="modal-body">Vais apagar a categoria "<?php echo htmlspecialchars($c['nome']); ?>".<?php if ((int)$c['total_equipamentos'] > 0): ?><br><strong class="text-danger">Atenção: há <?php echo (int)$c['total_equipamentos']; ?> equipamento(s) nesta categoria — ficarão sem categoria.</strong><?php endif; ?></div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                            <form action="" method="post">
                                                <input type="hidden" name="idcategoria_equipamento" value="<?php echo $id; ?>">
                                                <button class="btn btn-primary" name="btnDeleteCategoriaEquipamento">Sim</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="edit<?php echo $id; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form action="" method="post" novalidate>
                                            <div class="modal-header">
                                                <h5 class="modal-title">Editar Categoria</h5>
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                            </div>
                                            <div class="modal-body">
                                                <label>Nome</label>
                                                <input type="text" class="form-control" name="nome" value="<?php echo htmlspecialchars($c['nome']); ?>" required>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                                <input type="hidden" name="idcategoria_equipamento" value="<?php echo $id; ?>">
                                                <button class="btn btn-primary" name="btnEditCategoriaEquipamento">Sim</button>
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
