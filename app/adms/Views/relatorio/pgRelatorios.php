<?php
if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}
$tipos = $this->dados['tipos'] ?? [];
?>
<div class="container-fluid">
    <?php if (isset($_SESSION['msg'])) { echo $_SESSION['msg']; unset($_SESSION['msg']); } ?>
    <div class="card shadow mb-4 mt-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Relatórios</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <?php foreach ($tipos as $chave => $nome): ?>
                    <div class="col-md-4 mb-3">
                        <div class="card border-left-primary h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <span><?= htmlspecialchars($nome) ?></span>
                                <div>
                                    <a class="btn btn-sm btn-primary text-white" href="<?= URLADM ?>relatorio?tipo=<?= $chave ?>" target="_blank" title="Ver / Imprimir"><i class="fas fa-print"></i></a>
                                    <a class="btn btn-sm btn-outline-secondary" href="<?= URLADM ?>relatorio?tipo=<?= $chave ?>&export=csv" title="Exportar CSV"><i class="fas fa-file-csv"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
