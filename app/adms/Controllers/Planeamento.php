<?php

namespace App\adms\Controllers;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Planeamento de Manutenção Preventiva (módulo 13).
 */
class Planeamento {

    private $dados;
    private $dadosAlter;

    public function index() {
        if (!empty(filter_input_array(INPUT_POST, FILTER_DEFAULT))) {
            $dadosForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

            if (isset($dadosForm['btnCdsPlano'])) {
                $model = new \App\adms\Models\AdmsPlaneamento();
                $model->cdsPlano($dadosForm);
            } elseif (isset($dadosForm['btnEditPlano'])) {
                $model = new \App\adms\Models\AdmsPlaneamento();
                $model->editPlano($dadosForm);
            } elseif (isset($dadosForm['btnToggleAtivo'])) {
                $model = new \App\adms\Models\AdmsPlaneamento();
                $model->toggleAtivo($dadosForm);
            } elseif (isset($dadosForm['btnDeletePlano'])) {
                $model = new \App\adms\Models\AdmsPlaneamento();
                $model->deletePlano($dadosForm);
            } elseif (isset($dadosForm['btnGerarOcorrencias'])) {
                $model = new \App\adms\Models\AdmsPlaneamento();
                $resultado = $model->gerarOcorrenciasPendentes();
                $_SESSION['msg'] = '<div class="alert alert-success text-center">' . (int)$resultado['geradas'] . ' ocorrência(s) preventiva(s) gerada(s)!</div>';
            }
        }

        $model = new \App\adms\Models\AdmsPlaneamento();
        $this->dados['lista'] = $model->dadosPlanos();
        $this->dados['pendentes'] = count($model->dadosPlanosPendentes());

        $this->dadosAlter['equipamentos'] = (new \App\adms\Models\AdmsOcorrencia())->dadosEquipamentosDisponiveis();
        $this->dadosAlter['tiposManutencao'] = (new \App\adms\Models\AdmsOcorrencia())->dadosTiposManutencao();
        $this->dadosAlter['tecnicos'] = (new \App\adms\Models\AdmsOcorrencia())->dadosTecnicos();

        $carregarView = new \Core\ConfigView("adms/Views/planeamento/pgPlaneamento", $this->dados, $this->dadosAlter);
        $carregarView->renderizar();
    }
}
