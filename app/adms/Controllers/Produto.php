<?php

namespace App\adms\Controllers;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Peças e Consumíveis.
 */
class Produto {

    private $dados;
    private $dadosAlter;
    private $dadosPaginacao;
    private $dadosForm;

    public function index() {
        if (!empty(filter_input_array(INPUT_POST, FILTER_DEFAULT))) {
            $this->dadosForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            $model = new \App\adms\Models\AdmsProduto();
            if (isset($this->dadosForm['btnCdsProduto'])) {
                $this->dadosForm['foto'] = $_FILES['foto'] ?? [];
                $model->cdsProduto($this->dadosForm);
            } elseif (isset($this->dadosForm['btnDeletProduto'])) {
                $model->deleteProduto($this->dadosForm);
            } elseif (isset($this->dadosForm['btnEditProduto'])) {
                $model->editProduto($this->dadosForm);
            } elseif (isset($this->dadosForm['btnaddEstoque'])) {
                $model->addEstoque($this->dadosForm);
            } else {
                $this->dados['form'] = $this->dadosForm;
            }
        }

        $this->dadosFornecedor();
        $this->dadosCategoria();
        $this->dadosProdutos();
        $carregarView = new \Core\ConfigView("adms/Views/produto/pgProduto", $this->dados, $this->dadosAlter, $this->dadosPaginacao);
        $carregarView->renderizar();
    }

    private function dadosFornecedor() {
        $model = new \App\adms\Models\AdmsFornecedor();
        $this->dadosAlter = $model->dadosFornecedores();
    }

    private function dadosCategoria() {
        $model = new \App\adms\Models\AdmsCategoria();
        $this->dadosPaginacao = $model->dadosCategorias();
    }

    private function dadosProdutos() {
        $model = new \App\adms\Models\AdmsProduto();
        $this->dados = $model->dadosProdutos();
    }

}
