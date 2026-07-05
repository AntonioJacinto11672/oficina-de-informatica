<?php

namespace App\adms\Controllers;

/**
 * Description of Produto
 *
 * @author Double
 */
class EntradaEquipamento {
    private $dados;
    private $dadosForm;

    public function index() {
        if (!empty(filter_input_array(INPUT_POST, FILTER_DEFAULT))) {
            $this->dadosForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            //var_dump($this->dadosForm);
            //var_dump($_FILES);
            if (isset($this->dadosForm['btnDeleteEquipamento'])) {
                $dadosVendas = new \App\adms\Models\AdmsTecnico();
                $dadosVendas->deletEntradaEquipamento($this->dadosForm);
                
                //var_dump($this->dadosForm);
            }  else {
                $this->dados['form'] = $this->dadosForm;
            }
        }

        $this->dadosEntradaEquipamento();
        $carregarView = new \Core\ConfigView("adms/Views/entradaEquipamento/pgEntradaEquipamento", $this->dados);
        $carregarView->renderizar();
    }
    private function dadosEntradaEquipamento() {
        $dadosVendas = new \App\adms\Models\AdmsTecnico();
        $this->dados = $dadosVendas->dadosEntradaEquipamento();
    }
}

