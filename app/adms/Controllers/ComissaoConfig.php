<?php

namespace App\adms\Controllers;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Configuração de Comissões (módulo 16) — apenas administrador.
 */
class ComissaoConfig {

    private $dados;
    private $dadosAlter;

    public function index() {
        if (!empty(filter_input_array(INPUT_POST, FILTER_DEFAULT))) {
            $dadosForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

            if (isset($dadosForm['btnCdsConfiguracao'])) {
                $model = new \App\adms\Models\AdmsComissaoConfig();
                $model->cdsConfiguracao($dadosForm);
            } elseif (isset($dadosForm['btnEditConfiguracao'])) {
                $model = new \App\adms\Models\AdmsComissaoConfig();
                $model->editConfiguracao($dadosForm);
            } elseif (isset($dadosForm['btnToggleAtivo'])) {
                $model = new \App\adms\Models\AdmsComissaoConfig();
                $model->toggleAtivo($dadosForm);
            } elseif (isset($dadosForm['btnDeleteConfiguracao'])) {
                $model = new \App\adms\Models\AdmsComissaoConfig();
                $model->deleteConfiguracao($dadosForm);
            }
        }

        $model = new \App\adms\Models\AdmsComissaoConfig();
        $this->dados['lista'] = $model->dadosConfiguracoes();

        $this->dadosAlter['tecnicos'] = (new \App\adms\Models\AdmsOcorrencia())->dadosTecnicos();
        $this->dadosAlter['tiposServico'] = (new \App\adms\Models\AdmsOcorrencia())->dadosTiposServico();

        $carregarView = new \Core\ConfigView("adms/Views/comissaoConfig/pgComissaoConfig", $this->dados, $this->dadosAlter);
        $carregarView->renderizar();
    }
}
