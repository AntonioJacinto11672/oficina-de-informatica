<?php

namespace App\adms\Controllers;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

use PDO;

/**
 * Description of Recepcionista
 *
 * @author Double
 */
class Recepcionista {

    private $dados;
    private $dadosForm;

    public function index() {
        if (!empty(filter_input_array(INPUT_POST, FILTER_DEFAULT))) {
            $this->dadosForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            //$this->dadosForm['idusuario'] = $_SESSION['idlogado']; 
            //var_dump($this->dadosForm);
            //var_dump($_FILES);
            if (isset($this->dadosForm['btnCdsRecepcionista'])) {
                $this->dadosForm['foto'] = ($_FILES['foto'] ? $_FILES['foto'] : null);
                $cdsRecepcionista = new \App\adms\Models\AdmsTecnico();
                $cdsRecepcionista->cdsRecepcionista($this->dadosForm);
            } elseif (isset($this->dadosForm['btnDeletRecepcionista'])) {
                $cdsRecepcionista = new \App\adms\Models\AdmsTecnico();
                $cdsRecepcionista->deleteRecepcionista($this->dadosForm);
            } elseif (isset($this->dadosForm['btnEditRecepcionista'])) {
                $cdsRecepcionista = new \App\adms\Models\AdmsTecnico();
                $cdsRecepcionista->editRecepicionista($this->dadosForm);

                //var_dump($this->dadosForm);
            } elseif (isset($this->dadosForm['btnAtivarConta'])) {
                $cdsRecepcionista = new \App\adms\Models\AdmsTecnico();
                $this->dadosForm['usuario'] = "Recep";
                $cdsRecepcionista->ativarConta($this->dadosForm);
            } elseif (isset($this->dadosForm['btnEditPerfilRecepcionista'])) {
                
                var_dump($this->dadosForm);
            } else {
                $this->dados['form'] = $this->dadosForm;
            }
        }

        $this->dadosRecepcionistas();
        $carregarView = new \Core\ConfigView("adms/Views/Recepcionista/pgRecepcionista", $this->dados);
        $carregarView->renderizar();
    }

    public function dadosRecepcionistas() {
        $dadosRecepcionistas = new \App\adms\Models\AdmsTecnico();
        $this->dados = $dadosRecepcionistas->dadosRecepcionista();
    }

}

