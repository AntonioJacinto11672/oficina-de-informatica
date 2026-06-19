<?php

namespace App\adms\Controllers;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

use PDO;

/**
 * Description of dadosClisente
 *
 * @author Double
 */
class Cliente {

    private $dados;
    private $dadosForm;

    public function index() {
        if (!empty(filter_input_array(INPUT_POST, FILTER_DEFAULT))) {
            $this->dadosForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            //var_dump($this->dadosForm);
            //var_dump($_FILES);
            if (isset($this->dadosForm['btnCdscliente'])) {
                $dadosClisente = new \App\adms\Models\AdmsRecepcionista();
                $dadosClisente->cdsCliente($this->dadosForm);
            } elseif (isset($this->dadosForm['btnEditCliente'])) {
                $dadosClisente = new \App\adms\Models\AdmsRecepcionista();
                $dadosClisente->editCliente($this->dadosForm);
            } elseif (isset($this->dadosForm['btnDeletCliente'])) {
                $dadosClisente = new \App\adms\Models\AdmsRecepcionista();
                $dadosClisente->deleteCliente($this->dadosForm);
                
                //var_dump($this->dadosForm);
            } elseif (isset ($this->dadosForm['dadosClisente'])) {
                var_dump($this->dadosForm);
            } else {
                $this->dados['form'] = $this->dadosForm;
            }
        }

        $this->dadosClientes();
        $carregarView = new \Core\ConfigView("adms/Views/cliente/pgCliente", $this->dados);
        $carregarView->renderizar();
    }

    public function dadosClientes() {
        $dadosClientes = new \App\adms\Models\AdmsRecepcionista();
        $this->dados = $dadosClientes->dadosClientes();
    }

}
