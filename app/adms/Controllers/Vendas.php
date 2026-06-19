<?php

namespace App\adms\Controllers;

/**
 * Description of Produto
 *
 * @author Double
 */
class Vendas {
    private $dados;
    public function index() {
        if (!empty(filter_input_array(INPUT_POST, FILTER_DEFAULT))) {
            $this->dadosForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            //var_dump($this->dadosForm);
            //var_dump($_FILES);
            if (isset($this->dadosForm['btndeletVendas'])) {
                $dadosVendas = new \App\adms\Models\AdmsTecnico();
                $dadosVendas->dadosVendas($this->dadosForm);
                
                //var_dump($this->dadosForm);
            } else {
                $this->dados['form'] = $this->dadosForm;
            }
        }

        $this->dadosVendas();
        $carregarView = new \Core\ConfigView("adms/Views/vendas/pgVendas", $this->dados);
        $carregarView->renderizar();
    }
    private function dadosVendas() {
        $dadosVendas = new \App\adms\Models\AdmsTecnico();
        $this->dados = $dadosVendas->dadosVendas();
    }
}

