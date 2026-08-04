<?php

namespace App\adms\Controllers;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Categorias de Peças e Consumíveis.
 */
class Categoria {

    private $dados;
    private $dadosForm;

    public function index() {
        if (!empty(filter_input_array(INPUT_POST, FILTER_DEFAULT))) {
            $this->dadosForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            $model = new \App\adms\Models\AdmsCategoria();
            if (isset($this->dadosForm['btnCdsCategoria'])) {
                $model->cdsCategoria($this->dadosForm);
            } elseif (isset($this->dadosForm['btnDeletCategoria'])) {
                $model->deleteCategoria($this->dadosForm);
            } elseif (isset($this->dadosForm['btnEditCategoria'])) {
                $model->editCategoria($this->dadosForm);
            } else {
                $this->dados['form'] = $this->dadosForm;
            }
        }

        $this->dadosCategorias();
        $carregarView = new \Core\ConfigView("adms/Views/produto/pgCategoria", $this->dados);
        $carregarView->renderizar();
    }

    private function dadosCategorias() {
        $model = new \App\adms\Models\AdmsCategoria();
        $this->dados = $model->dadosCategorias();
    }

}
