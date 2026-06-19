<?php

namespace App\adms\Controllers;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

use PDO;

/**
 * Description of Mecanico
 *
 * @author Double
 */
class Tecnico {

    private $dados;
    private $dadosForm;

    public function index() {
        if (!empty(filter_input_array(INPUT_POST, FILTER_DEFAULT))) {
            $this->dadosForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            //var_dump($this->dadosForm);
            //var_dump($_FILES);
            if (isset($this->dadosForm['btnCdsTecnico'])) {
                $this->dadosForm['foto'] = ($_FILES['foto'] ? $_FILES['foto'] : null);
                $cdsTecnico = new \App\adms\Models\AdmsTecnico();
                $cdsTecnico->cdsTecnico($this->dadosForm);
            } elseif (isset($this->dadosForm['btnDeleteTecnico'])) {
                $cdsTecnico = new \App\adms\Models\AdmsTecnico();
                $cdsTecnico->deleteTecnico($this->dadosForm);
            } elseif (isset($this->dadosForm['btnEditTecnico'])) {
                $cdsTecnico = new \App\adms\Models\AdmsTecnico();
                $cdsTecnico->editTecnico($this->dadosForm);

                //var_dump($this->dadosForm);
            } elseif (isset($this->dadosForm['btnAtivarConta'])) {
                $cdsTecnico = new \App\adms\Models\AdmsTecnico();
                $this->dadosForm['usuario'] = "Tecnico";
                $cdsTecnico->ativarConta($this->dadosForm);
                
            }  elseif (isset($this->dadosForm['btnEditPerfilTecnico'])) {
                var_dump($this->dadosForm);
            } else {
                $this->dados['form'] = $this->dadosForm;
            }
        }

        $this->dadosTecnicos();
        $carregarView = new \Core\ConfigView("adms/Views/tecnico/pgTecnico", $this->dados);
        $carregarView->renderizar();
    }

    public function dadosTecnicos() {
        $dadosTecnicos = new \App\adms\Models\AdmsTecnico();
        $this->dados = $dadosTecnicos->dadosTecnico();
    }

}

