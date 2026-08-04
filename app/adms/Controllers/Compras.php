<?php

namespace App\adms\Controllers;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Compras a Fornecedores (equipamentos, peças e consumíveis).
 */
class Compras {

    private $dados;
    private $dadosAlter;
    private $dadosForm;

    public function index() {
        if (!empty(filter_input_array(INPUT_POST, FILTER_DEFAULT))) {
            $this->dadosForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            $model = new \App\adms\Models\AdmsCompras();
            if (isset($this->dadosForm['btnCdsCompra'])) {
                $model->cdsCompra($this->dadosForm);
            } elseif (isset($this->dadosForm['btnDeletCompra'])) {
                $model->deleteCompra($this->dadosForm);
            } else {
                $this->dados['form'] = $this->dadosForm;
            }
        }

        $model = new \App\adms\Models\AdmsCompras();
        $this->dados = $model->dadosCompras();
        $this->dadosAlter = [
            'fornecedores' => $model->dadosFornecedores(),
            'produtos' => $model->dadosProdutos(),
        ];
        $carregarView = new \Core\ConfigView("adms/Views/compras/pgCompras", $this->dados, $this->dadosAlter);
        $carregarView->renderizar();
    }

}
