<?php

namespace App\adms\Controllers;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Técnicos de Informática.
 */
class Tecnico {

    private $dados;
    private $dadosForm;

    public function index() {
        if (!empty(filter_input_array(INPUT_POST, FILTER_DEFAULT))) {
            $this->dadosForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            $model = new \App\adms\Models\AdmsTecnico();
            if (isset($this->dadosForm['btnCdsTecnico'])) {
                $this->dadosForm['foto'] = $_FILES['foto'] ?? [];
                $model->cdsTecnico($this->dadosForm);
            } elseif (isset($this->dadosForm['btnDeleteTecnico'])) {
                $model->deleteTecnico($this->dadosForm);
            } elseif (isset($this->dadosForm['btnEditTecnico'])) {
                $model->editTecnico($this->dadosForm);
            } elseif (isset($this->dadosForm['btnAtivarConta'])) {
                $model->ativarConta($this->dadosForm);
            } else {
                $this->dados['form'] = $this->dadosForm;
            }
        }

        $this->dadosTecnicos();
        $carregarView = new \Core\ConfigView("adms/Views/tecnico/pgTecnico", $this->dados);
        $carregarView->renderizar();
    }

    public function dadosTecnicos() {
        $model = new \App\adms\Models\AdmsTecnico();
        $this->dados = $model->dadosTecnico();
    }

}
