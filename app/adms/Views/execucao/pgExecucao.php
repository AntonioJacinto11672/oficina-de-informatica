<?php
if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

$idOcorrencia = $this->dados['idocorrencia'] ?? null;
$ocorrencia = $this->dados['ocorrencia'] ?? null;
$lista = $this->dados['lista'] ?? [];
$produtos = $this->dadosAlter['produtos'] ?? [];
$pecasPorExecucao = $this->dados['pecasPorExecucao'] ?? [];
$equipamentosParaExecucao = $this->dadosAlter['equipamentosParaExecucao'] ?? [];
?>
<div class="container-fluid">
    <?php
    if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
    ?>

    <?php if ($idOcorrencia): ?>
        <div class="row mt-4 mb-4">
            <a class="btn btn-secondary btn-sm ml-3" href="<?= URLADM ?>ocorrencia"><i class="icofont icofont-arrow-left mr-1"></i>Voltar às Ocorrências</a>
            <?php if ($ocorrencia && !in_array($ocorrencia['estado'], ['Concluída', 'Cancelada'], true) && !empty($equipamentosParaExecucao)): ?>
                <form action="" method="post" style="display:inline" class="form-inline ml-3">
                    <input type="hidden" name="id_ocorrencia" value="<?= (int)$idOcorrencia ?>">
                    <select class="custom-select mr-2" name="id_equipamento" required>
                        <option value="">Equipamento a executar...</option>
                        <?php foreach ($equipamentosParaExecucao as $eq): ?>
                            <option value="<?= (int)$eq['idequipamento'] ?>"><?= htmlspecialchars($eq['numero_serie'] . ' — ' . $eq['marca'] . ' ' . $eq['modelo']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm text-white" name="btnIniciarExecucao"><i class="fas fa-play mr-1"></i>Iniciar Execução</button>
                </form>
            <?php endif; ?>
        </div>

        <?php if ($ocorrencia && !in_array($ocorrencia['estado'], ['Concluída', 'Cancelada'], true) && empty($equipamentosParaExecucao)): ?>
            <div class="alert alert-info mx-3">Todos os equipamentos desta ocorrência já têm execução iniciada — só é possível encerrar as execuções em curso.</div>
        <?php endif; ?>

        <?php if ($ocorrencia): ?>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Ocorrência #<?= (int)$ocorrencia['idocorrencia'] ?> — Estado: <?= htmlspecialchars($ocorrencia['estado']) ?></h6>
                </div>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="row mt-4 mb-4">
            <h6 class="ml-3 font-weight-bold text-primary">Todas as Execuções</h6>
        </div>
    <?php endif; ?>

    <?php foreach ($lista as $ex): $idExec = (int)$ex['idexecucao']; ?>
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    Execução #<?= $idExec ?> — <?= htmlspecialchars($ex['estado']) ?>
                    <small class="text-muted">
                        Equipamento: <?= !empty($ex['numero_serie']) ? htmlspecialchars($ex['numero_serie'] . ' — ' . $ex['marca'] . ' ' . $ex['modelo']) : '—' ?>
                        &nbsp;|&nbsp; Técnico: <?= htmlspecialchars(trim(($ex['tecnico_nome'] ?? '') . ' ' . ($ex['tecnico_sobrenome'] ?? '')) ?: '—') ?>
                    </small>
                </h6>
                <?php if ($idOcorrencia && in_array($ex['estado'], ['Em execução', 'Pausada'], true)): ?>
                    <div>
                        <?php if ($ex['estado'] === 'Em execução'): ?>
                            <form action="" method="post" style="display:inline">
                                <input type="hidden" name="idexecucao" value="<?= $idExec ?>">
                                <button type="submit" class="btn btn-warning btn-sm" name="btnPausarExecucao"><i class="fas fa-pause mr-1"></i>Interromper</button>
                            </form>
                        <?php else: ?>
                            <form action="" method="post" style="display:inline">
                                <input type="hidden" name="idexecucao" value="<?= $idExec ?>">
                                <button type="submit" class="btn btn-info btn-sm text-white" name="btnRetomarExecucao"><i class="fas fa-play mr-1"></i>Retomar</button>
                            </form>
                        <?php endif; ?>
                        <form action="" method="post" style="display:inline" onsubmit="return confirm('Encerrar esta execução? Só faça isto quando o trabalho estiver mesmo concluído.');">
                            <input type="hidden" name="idexecucao" value="<?= $idExec ?>">
                            <button type="submit" class="btn btn-success btn-sm" name="btnEncerrarExecucao"><i class="fas fa-check mr-1"></i>Encerrar</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <p><strong>Início:</strong> <?= htmlspecialchars($ex['data_inicio'] ?? '—') ?> &nbsp; <strong>Fim:</strong> <?= htmlspecialchars($ex['data_fim'] ?? '—') ?></p>

                <?php if ($idOcorrencia): ?>
                    <h6 class="font-weight-bold">Peças Utilizadas</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead><tr><th>Peça</th><th>Quantidade</th><?php if ($ex['estado'] === 'Em execução'): ?><th>Acção</th><?php endif; ?></tr></thead>
                            <tbody>
                                <?php foreach (($pecasPorExecucao[$idExec] ?? []) as $p): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($p['produto_nome']) ?></td>
                                        <td><?= (int)$p['quantidade'] ?></td>
                                        <?php if ($ex['estado'] === 'Em execução'): ?>
                                            <td>
                                                <form action="" method="post" onsubmit="return confirm('Remover esta peça e repor o stock?');">
                                                    <input type="hidden" name="idexecucao_peca" value="<?= (int)$p['idexecucao_peca'] ?>">
                                                    <button type="submit" class="btn btn-link p-0 text-danger" name="btnRemoverPeca"><i class="icofont icofont-trash"></i></button>
                                                </form>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($pecasPorExecucao[$idExec])): ?>
                                    <tr><td colspan="<?= $ex['estado'] === 'Em execução' ? 3 : 2 ?>" class="text-center text-muted">Sem peças registadas.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if ($ex['estado'] === 'Em execução'): ?>
                        <form action="" method="post" class="form-inline">
                            <input type="hidden" name="id_execucao" value="<?= $idExec ?>">
                            <select class="custom-select mr-2 mb-2" name="id_produto" required>
                                <option value="">Peça...</option>
                                <?php foreach ($produtos as $p): ?>
                                    <option value="<?= (int)$p['idproduto'] ?>"><?= htmlspecialchars($p['nome']) ?> (stock: <?= (int)$p['estoque'] ?>)</option>
                                <?php endforeach; ?>
                            </select>
                            <input type="number" class="form-control mr-2 mb-2" name="quantidade" value="1" min="1" style="width:90px" required>
                            <button type="submit" class="btn btn-primary mb-2" name="btnAddPeca">Adicionar Peça</button>
                        </form>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if (empty($lista)): ?>
        <div class="alert alert-secondary text-center">Sem execuções registadas.</div>
    <?php endif; ?>
</div>
