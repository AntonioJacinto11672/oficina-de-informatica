<?php
if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}
$c = $this->dados ?? [];
?>
<div class="container-fluid">
    <?php if (isset($_SESSION['msg'])) { echo $_SESSION['msg']; unset($_SESSION['msg']); } ?>
    <div class="card shadow mb-4 mt-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Configurações Institucionais</h6>
        </div>
        <div class="card-body">
            <p class="text-muted">Estas definições são geridas pelo ficheiro <code>.env</code> do servidor. Para as alterar, contacte o administrador do sistema.</p>
            <table class="table table-bordered w-100">
                <tbody>
                    <tr><th style="width:280px">Nome do Sistema</th><td><?= htmlspecialchars($c['nome_instituicao'] ?? '—') ?></td></tr>
                    <tr><th>Morada da Universidade</th><td><?= htmlspecialchars($c['endereco_instituicao'] ?? '—') ?></td></tr>
                    <tr><th>E-mail do Departamento de TI</th><td><?= htmlspecialchars($c['email_instituicao'] ?? '—') ?></td></tr>
                    <tr><th>Telefone do Departamento de TI</th><td><?= htmlspecialchars($c['telefone_instituicao'] ?? '—') ?></td></tr>
                    <tr><th>Limiar de Stock Baixo (unidades)</th><td><?= (int)($c['nivel_stock'] ?? 5) ?></td></tr>
                    <tr><th>URL da Aplicação</th><td><?= htmlspecialchars($c['url_app'] ?? '—') ?></td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
