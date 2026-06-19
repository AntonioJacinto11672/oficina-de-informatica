<?php

namespace App\adms\Controllers;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Description of Perfil
 *
 * @author Double
 */
class Perfil {

    private $dadosForm;
    private $url;

    public function index() {
        if (!empty(filter_input_array(INPUT_GET, FILTER_DEFAULT))) {
            $this->dadosForm = filter_input_array(INPUT_GET, FILTER_DEFAULT);
            //var_dump($this->dadosForm);

            if (isset($this->dadosForm['btnEditPerfil'])) {
                
                $editPerfil = new \App\adms\Models\AdmsPerfil();
                //$editPerfil->editarPerfil($this->dadosForm);
                
                if ($editPerfil->editarPerfil($this->dadosForm)) {
                    $destino = URLADM . "home";
                    header("Location: $destino");
                } else {
                    $destino = URLADM . "tecnico";
                    header("Location: $destino");
                }
                //var_dump($editPerfil);
                //var_dump($this->dadosForm);
            }
        }
    }

}

