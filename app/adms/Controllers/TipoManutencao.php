<?php

namespace App\adms\Controllers;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Tipos de Manutenção (catálogo técnico).
 */
class TipoManutencao {

    private $dados;
    private $dadosForm;

    public function index() {
        if (!empty(filter_input_array(INPUT_POST, FILTER_DEFAULT))) {
            $this->dadosForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            $model = new \App\adms\Models\AdmsTipoManutencao();
            if (isset($this->dadosForm['btnCdsTipoManutencao'])) {
                $model->cdsTipo($this->dadosForm);
            } elseif (isset($this->dadosForm['btnDeletTipoManutencao'])) {
                $model->deleteTipo($this->dadosForm);
            } elseif (isset($this->dadosForm['btnEditTipoManutencao'])) {
                $model->editTipo($this->dadosForm);
            } else {
                $this->dados['form'] = $this->dadosForm;
            }
        }

        $model = new \App\adms\Models\AdmsTipoManutencao();
        $this->dados = $model->dadosTipos();
        $carregarView = new \Core\ConfigView("adms/Views/tipoManutencao/pgTipoManutencao", $this->dados);
        $carregarView->renderizar();
    }

}
