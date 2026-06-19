<?php

namespace App\adms\Controllers;

/**
 * Description of Produto
 *
 * @author Double
 */
class Categoria {
    private $dados;
    public function index() {
        if (!empty(filter_input_array(INPUT_POST, FILTER_DEFAULT))) {
            $this->dadosForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            //var_dump($this->dadosForm);
            //var_dump($_FILES);
            if (isset($this->dadosForm['btnCdsCategoria'])) {
                $cdsCategoria = new \App\adms\Models\AdmsTecnico();
                $cdsCategoria->cdsCategoria($this->dadosForm);
            } elseif (isset($this->dadosForm['btnDeletCategoria'])) {
                $cdsCategoria = new \App\adms\Models\AdmsTecnico();
                $cdsCategoria->deletCategoria($this->dadosForm);
            } elseif (isset($this->dadosForm['btnEditCategoria'])) {
                $cdsCategoria = new \App\adms\Models\AdmsTecnico();
                $cdsCategoria->editCategoria($this->dadosForm);
                
                //var_dump($this->dadosForm);
            } else {
                $this->dados['form'] = $this->dadosForm;
            }
        }

        $this->dadosCategoria();
        $carregarView = new \Core\ConfigView("adms/Views/produto/pgCategoria", $this->dados);
        $carregarView->renderizar();
    }
    private function dadosCategoria() {
        $dadosCategorias = new \App\adms\Models\AdmsTecnico();
        $this->dados = $dadosCategorias->dadosCategoria();
    }
}

