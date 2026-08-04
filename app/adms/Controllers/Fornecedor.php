<?php

namespace App\adms\Controllers;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Fornecedores de equipamentos, peças e consumíveis.
 */
class Fornecedor {

    private $dados;
    private $dadosForm;

    public function index() {
        if (!empty(filter_input_array(INPUT_POST, FILTER_DEFAULT))) {
            $this->dadosForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            $model = new \App\adms\Models\AdmsFornecedor();
            if (isset($this->dadosForm['btnCdsFornecedor'])) {
                $model->cdsFornecedor($this->dadosForm);
            } elseif (isset($this->dadosForm['btnDeletFornecedor'])) {
                $model->deleteFornecedor($this->dadosForm);
            } elseif (isset($this->dadosForm['btnEditFornecedor'])) {
                $model->editFornecedor($this->dadosForm);
            } else {
                $this->dados['form'] = $this->dadosForm;
            }
        }

        $this->dadosFornecedores();
        $carregarView = new \Core\ConfigView("adms/Views/fornecedor/pgFornecedor", $this->dados);
        $carregarView->renderizar();
    }

    private function dadosFornecedores() {
        $model = new \App\adms\Models\AdmsFornecedor();
        $this->dados = $model->dadosFornecedores();
    }

}
