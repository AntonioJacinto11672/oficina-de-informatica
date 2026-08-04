<?php

namespace App\adms\Controllers;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Histórico de manutenções concluídas/canceladas.
 */
class Historico {

    private $dados;

    public function index() {
        $model = new \App\adms\Models\AdmsOcorrencia();
        $this->dados = $model->dadosHistoricoGeral();
        $carregarView = new \Core\ConfigView("adms/Views/ocorrencia/pgHistoricoGeral", $this->dados);
        $carregarView->renderizar();
    }
}
