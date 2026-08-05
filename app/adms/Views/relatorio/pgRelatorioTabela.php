<?php
if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

$titulo = $this->dados['titulo'] ?? 'Relatório';
$colunas = $this->dados['colunas'] ?? [];
$linhas = $this->dados['linhas'] ?? [];
$filtros = $this->dados['filtros'] ?? [];
$tipo = $this->dados['tipo'] ?? '';

$chavesColunas = array_keys($colunas);

// Índice (posição) de cada coluna usada como filtro, e os seus valores
// distintos (para preencher os <select>). Só se aplicam os filtros cujas
// colunas realmente existem neste relatório (definidos em AdmsRelatorio).
$indiceColuna = function (string $chave) use ($chavesColunas) {
    $pos = array_search($chave, $chavesColunas, true);
    return $pos === false ? null : $pos;
};

$filtroSelects = []; // role => ['indice' => int, 'rotulo' => string, 'valores' => [...]]
$rotulosFiltro = ['estado' => 'Estado', 'tecnico' => 'Técnico Responsável', 'categoria' => 'Categoria', 'tipo' => 'Tipo de Manutenção'];
foreach (['estado', 'tecnico', 'categoria', 'tipo'] as $role) {
    if (!isset($filtros[$role])) {
        continue;
    }
    $chave = $filtros[$role];
    $idx = $indiceColuna($chave);
    if ($idx === null) {
        continue;
    }
    $valores = array_values(array_unique(array_filter(array_map(fn($l) => (string)($l[$chave] ?? ''), $linhas), fn($v) => $v !== '')));
    sort($valores, SORT_STRING | SORT_FLAG_CASE);
    $filtroSelects[$role] = ['indice' => $idx, 'rotulo' => $rotulosFiltro[$role], 'valores' => $valores];
}

$indiceData = isset($filtros['data']) ? $indiceColuna($filtros['data']) : null;
?>
<div class="container-fluid">
    <?php if (isset($_SESSION['msg'])) { echo $_SESSION['msg']; unset($_SESSION['msg']); } ?>

    <div class="row mt-4 mb-4">
        <a class="btn btn-secondary btn-sm ml-3" href="<?= URLADM ?>relatorio"><i class="fas fa-arrow-left mr-1"></i>Voltar aos Relatórios</a>
        <a class="btn btn-primary btn-sm text-white ml-2" href="<?= URLADM ?>relatorio?tipo=<?= htmlspecialchars($tipo) ?>&imprimir=1" target="_blank"><i class="fas fa-print mr-1"></i>Imprimir</a>
        <a class="btn btn-success btn-sm ml-2" href="<?= URLADM ?>relatorio?tipo=<?= htmlspecialchars($tipo) ?>&export=csv"><i class="fas fa-file-csv mr-1"></i>Exportar CSV</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><?= htmlspecialchars($titulo) ?> <span class="text-muted font-weight-normal">(<?= count($linhas) ?> registos)</span></h6>
        </div>
        <div class="card-body">
            <div class="row mb-3" id="filtrosRelatorio">
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold mb-1">Pesquisa</label>
                    <input type="text" class="form-control form-control-sm" id="filtroPesquisa" placeholder="Pesquisar em qualquer coluna...">
                </div>
                <?php if ($indiceData !== null): ?>
                    <div class="col-md-2 mb-2">
                        <label class="small font-weight-bold mb-1">De</label>
                        <input type="date" class="form-control form-control-sm" id="filtroDataDe">
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="small font-weight-bold mb-1">Até</label>
                        <input type="date" class="form-control form-control-sm" id="filtroDataAte">
                    </div>
                <?php endif; ?>
                <?php foreach ($filtroSelects as $role => $f): ?>
                    <div class="col-md-2 mb-2">
                        <label class="small font-weight-bold mb-1"><?= htmlspecialchars($f['rotulo']) ?></label>
                        <select class="custom-select custom-select-sm filtro-coluna" data-indice="<?= (int)$f['indice'] ?>">
                            <option value="">Todos</option>
                            <?php foreach ($f['valores'] as $v): ?>
                                <option value="<?= htmlspecialchars($v) ?>"><?= htmlspecialchars($v) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endforeach; ?>
                <div class="col-md-1 mb-2 d-flex align-items-end">
                    <button type="button" id="filtroLimpar" class="btn btn-outline-secondary btn-sm">Limpar</button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <?php foreach ($colunas as $rotulo): ?>
                                <th><?= htmlspecialchars($rotulo) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($linhas as $linha): ?>
                            <tr>
                                <?php foreach ($chavesColunas as $chave): ?>
                                    <td><?= htmlspecialchars((string)($linha[$chave] ?? '—')) ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php if ($indiceData !== null): ?>
<script>
$(document).ready(function () {
    var idxData = <?= (int)$indiceData ?>;

    $.fn.dataTable.ext.search.push(function (settings, data) {
        var de = $('#filtroDataDe').val();
        var ate = $('#filtroDataAte').val();
        if (!de && !ate) { return true; }
        var valorCelula = (data[idxData] || '').substring(0, 10); // YYYY-MM-DD
        if (!valorCelula) { return true; }
        if (de && valorCelula < de) { return false; }
        if (ate && valorCelula > ate) { return false; }
        return true;
    });

    var tabela = $('#dataTable').DataTable();
    $('#filtroDataDe, #filtroDataAte').on('change', function () { tabela.draw(); });
});
</script>
<?php endif; ?>
<script>
$(document).ready(function () {
    var tabela = $('#dataTable').DataTable();

    $('#filtroPesquisa').on('keyup', function () {
        tabela.search(this.value).draw();
    });

    $('.filtro-coluna').on('change', function () {
        var idx = $(this).data('indice');
        var valor = this.value;
        tabela.column(idx).search(valor ? '^' + $.fn.dataTable.util.escapeRegex(valor) + '$' : '', true, false).draw();
    });

    $('#filtroLimpar').on('click', function () {
        $('#filtroPesquisa').val('');
        $('#filtroDataDe').val('');
        $('#filtroDataAte').val('');
        $('.filtro-coluna').val('');
        tabela.search('').columns().search('').draw();
    });
});
</script>
