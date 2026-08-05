<?php

namespace App\adms\Controllers;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Relatórios institucionais: Equipamentos, Técnicos, Ocorrências,
 * Diagnósticos, Manutenções, Planeamentos Preventivos, Histórico,
 * Fornecedores, Stock e Compras. Suporta impressão e exportação CSV.
 */
class Relatorio {

    public function index() {
        $tipo = filter_input(INPUT_GET, 'tipo', FILTER_DEFAULT);

        if (!$tipo || !array_key_exists($tipo, \App\adms\Models\AdmsRelatorio::TIPOS)) {
            $carregarView = new \Core\ConfigView("adms/Views/relatorio/pgRelatorios", ['tipos' => \App\adms\Models\AdmsRelatorio::TIPOS]);
            $carregarView->renderizar();
            return;
        }

        $model = new \App\adms\Models\AdmsRelatorio();
        $relatorio = $model->obterRelatorio($tipo);

        if (filter_input(INPUT_GET, 'export', FILTER_DEFAULT) === 'csv') {
            $this->exportarCsv($relatorio);
            return;
        }

        if (filter_input(INPUT_GET, 'imprimir', FILTER_VALIDATE_INT)) {
            $carregarView = new \Core\ConfigView("adms/Views/relatorio/pgRelatorio", $relatorio);
            $carregarView->renderizaRelatorio();
            return;
        }

        // Vista intermédia: tabela com filtros, antes de imprimir ou exportar.
        $relatorio['tipo'] = $tipo;
        $carregarView = new \Core\ConfigView("adms/Views/relatorio/pgRelatorioTabela", $relatorio);
        $carregarView->renderizar();
    }

    private function exportarCsv(array $relatorio): void {
        $nomeFicheiro = 'relatorio_' . preg_replace('/[^a-z0-9]+/i', '_', $relatorio['titulo']) . '_' . date('Y-m-d') . '.csv';

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $nomeFicheiro . '"');

        $saida = fopen('php://output', 'w');
        fwrite($saida, "\xEF\xBB\xBF"); // BOM UTF-8, para o Excel reconhecer os acentos
        fputcsv($saida, array_values($relatorio['colunas']));

        foreach ($relatorio['linhas'] as $linha) {
            $registo = [];
            foreach (array_keys($relatorio['colunas']) as $chave) {
                $registo[] = $linha[$chave] ?? '';
            }
            fputcsv($saida, $registo);
        }

        fclose($saida);
        exit;
    }
}
