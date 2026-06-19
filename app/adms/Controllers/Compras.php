<?php

namespace App\adms\Controllers;

/**
 * Description of Produto
 *
 * @author Double
 */
class Compras {
    private $dados;
    private $dadosForrm;
    public function index() {
        if (!empty(filter_input_array(INPUT_POST, FILTER_DEFAULT))) {
            $this->dadosForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            //var_dump($this->dadosForm);
            //var_dump($_FILES);
            if (isset($this->dadosForm['btndeletCompras'])) {
                $cdsCompras = new \App\adms\Models\AdmsTecnico();
                $cdsCompras->deletCompras($this->dadosForm);
                
                //var_dump($this->dadosForm);
            } else {
                $this->dados['form'] = $this->dadosForm;
            }
        }

        $this->dadosCompras();
        $carregarView = new \Core\ConfigView("adms/Views/compras/pgCompras", $this->dados);
        $carregarView->renderizar();
    }
    private function dadosCompras() {
        $dadosCompras = new \App\adms\Models\AdmsTecnico();
        $this->dados = $dadosCompras->dadosCompras();
    }
}

