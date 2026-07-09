<?php
if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}
$produto = $this->dados['produto'] ?? null;
$movimentos = $this->dados['movimentos'] ?? [];

if (!function_exists('movimento_badge_tipo')) {
    function movimento_badge_tipo($tipo) {
        $classe = $tipo === 'Entrada' ? 'success' : 'danger';
        $sinal = $tipo === 'Entrada' ? '+' : '−';
        return '<span class="badge badge-' . $classe . '">' . $sinal . ' ' . htmlspecialchars($tipo) . '</span>';
    }
}
?>
<div class="container-fluid">
    <?php
    if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
    ?>
    <div class="row mt-4 mb-4">
        <a class="btn btn-secondary btn-sm ml-3" href="<?php echo URLADM; ?>produto"><i class="icofont icofont-arrow-left mr-1"></i>Voltar a Produtos</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Histórico de Movimentações
                <?php if ($produto): ?>— <?php echo htmlspecialchars($produto['nome']); ?> (stock actual: <?php echo (int)$produto['estoque']; ?>)<?php endif; ?>
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr><th>Data</th><th>Tipo</th><th>Quantidade</th><th>Origem</th><th>Utilizador</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($movimentos as $m): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($m['created']); ?></td>
                                <td><?php echo movimento_badge_tipo($m['tipo']); ?></td>
                                <td><?php echo (int)$m['quantidade']; ?></td>
                                <td><?php echo htmlspecialchars($m['origem'] ?? '—'); ?><?php echo $m['id_referencia'] ? ' #' . (int)$m['id_referencia'] : ''; ?></td>
                                <td><?php echo htmlspecialchars(trim(($m['usuario_nome'] ?? '') . ' ' . ($m['usuario_sobrenome'] ?? '')) ?: '—'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($movimentos)): ?>
                            <tr><td colspan="5" class="text-center text-muted">Sem movimentações registadas para este produto.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
