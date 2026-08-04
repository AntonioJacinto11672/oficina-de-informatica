<?php

namespace App\adms\Controllers;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Estatísticas de manutenção.
 */
class Graficos {

    private $dados;

    public function index() {
        $model = new \App\adms\Models\AdmsGraficos();
        $this->dados = [
            'ocorrencias_por_mes' => $model->dadosOcorrenciasPorMes(),
            'preventiva_vs_corretiva' => $model->dadosPreventivaVsCorretiva(),
            'equipamentos_por_estado' => $model->dadosEquipamentosPorEstado(),
            'pecas_mais_utilizadas' => $model->dadosPecasMaisUtilizadas(),
        ];
        $carregarView = new \Core\ConfigView("adms/Views/graficos/pgEstatisticas", $this->dados);
        $carregarView->renderizar();
    }

}
