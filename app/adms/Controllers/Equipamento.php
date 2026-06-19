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
class Equipamento {

    private $dados;
    private $dadosAlter;
    private $dadosForm;

    public function index() {
        if (!empty(filter_input_array(INPUT_POST, FILTER_DEFAULT))) {
            $this->dadosForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            //var_dump($this->dadosForm);
            //var_dump($_FILES);
            if (isset($this->dadosForm['btnCdsEquipamento'])) {
                $dadosClisente = new \App\adms\Models\AdmsRecepcionista();
                $dadosClisente->cdsEquipamento($this->dadosForm);
            } elseif (isset($this->dadosForm['btnEditEquipamento'])) {
                $dadosClisente = new \App\adms\Models\AdmsRecepcionista();
                $dadosClisente->editEquipamento($this->dadosForm);
            } elseif (isset($this->dadosForm['btnDeleteEquipamento'])) {
                $dadosClisente = new \App\adms\Models\AdmsRecepcionista();
                $dadosClisente->deleteEquipamento($this->dadosForm);

                //var_dump($this->dadosForm);
            } elseif (isset($this->dadosForm['dadosClisente'])) {
                var_dump($this->dadosForm);
            } else {
                $this->dados['form'] = $this->dadosForm;
            }
        }

        $this->dadosEquipamentos();
        $this->dadosCliente();
        $carregarView = new \Core\ConfigView("adms/Views/cliente/pgEquipamento", $this->dados,$this->dadosAlter);
        $carregarView->renderizar();
    }

    public function dadosEquipamentos() {
        $dadosEquipamentos = new \App\adms\Models\AdmsRecepcionista();
        $this->dados = $dadosEquipamentos->dadosEquipamento();
    }

    public function dadosCliente() {
        $dadosEquipamentos = new \App\adms\Models\AdmsRecepcionista();
        $this->dadosAlter = $dadosEquipamentos->dadosClientes();
    }

}

