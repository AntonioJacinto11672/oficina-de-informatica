<?php
if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}
$titulo = $this->dados['titulo'] ?? 'Relatório';
$colunas = $this->dados['colunas'] ?? [];
$linhas = $this->dados['linhas'] ?? [];
$tipo = filter_input(INPUT_GET, 'tipo', FILTER_DEFAULT);
?>
<div class="wrapper">
    <div class="content-wrapper">
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="invoice p-3 mb-3">
                            <div class="row">
                                <div class="col-12">
                                    <h4 class="text-center">
                                        <img src="<?php echo URLADM; ?>app/adms/assets/imagens/login/logo_novo.png" width="100px" height="60px" alt="Universidade Lusíada de Angola" />
                                        <span class="text-muted"><?php echo NOME_INSTITUICAO; ?></span>
                                    </h4>
                                    <div class="row justify-content-center">
                                        <small class="badge badge-primary text-wrap text-center" style="width: 28rem;font-size: 13px;">
                                            <?php echo ENDERECO_INSTITUICAO; ?><br>
                                            Tel: <?php echo TELEFONE_INSTITUICAO; ?> — E-mail: <?php echo EMAIL_INSTITUICAO; ?>
                                        </small>
                                    </div>
                                    <hr>
                                </div>
                                <div class="col-12">
                                    <h4>
                                        <span class="text-muted p-5"><?php echo htmlspecialchars($titulo); ?></span>
                                        <small class="float-right">Data: <?php echo date("d/m/Y"); ?></small><br>
                                    </h4>
                                    <hr>
                                </div>
                            </div>

                            <div class="row p-5">
                                <div class="col-12 table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <?php foreach ($colunas as $rotulo): ?>
                                                    <th><?php echo htmlspecialchars($rotulo); ?></th>
                                                <?php endforeach; ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($linhas as $linha): ?>
                                                <tr>
                                                    <?php foreach (array_keys($colunas) as $chave): ?>
                                                        <td><?php echo htmlspecialchars((string)($linha[$chave] ?? '—')); ?></td>
                                                    <?php endforeach; ?>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php if (empty($linhas)): ?>
                                                <tr><td colspan="<?php echo max(1, count($colunas)); ?>" class="text-center text-muted">Não existem dados para apresentar.</td></tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="row no-print">
                                <div class="col-12">
                                    <a href="<?php echo URLADM; ?>relatorio" class="btn btn-default border"><i class="fas fa-arrow-left"></i> Voltar aos Relatórios</a>
                                    <button type="button" onclick="window.print();" class="btn btn-primary float-right ml-2"><i class="fas fa-print"></i> Imprimir</button>
                                    <a href="<?php echo URLADM; ?>relatorio?tipo=<?php echo htmlspecialchars($tipo); ?>&export=csv" class="btn btn-success float-right"><i class="fas fa-file-csv"></i> Exportar CSV</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
