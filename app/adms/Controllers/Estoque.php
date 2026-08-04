<?php

namespace App\adms\Controllers;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Stock Baixo — peças e consumíveis abaixo do stock mínimo definido.
 */
class Estoque {

    private $dados;
    private $dadosForm;

    public function index() {
        if (!empty(filter_input_array(INPUT_POST, FILTER_DEFAULT))) {
            $this->dadosForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            if (isset($this->dadosForm['btnaddEstoque'])) {
                $model = new \App\adms\Models\AdmsProduto();
                $model->addEstoque($this->dadosForm);
            }
        }

        $this->dadosProdutos();
        $carregarView = new \Core\ConfigView("adms/Views/produto/pgEstoque", $this->dados);
        $carregarView->renderizar();
    }

    private function dadosProdutos() {
        $model = new \App\adms\Models\AdmsProduto();
        $this->dados = $model->dadosProdutosEstoqueBaixo();
    }

}
