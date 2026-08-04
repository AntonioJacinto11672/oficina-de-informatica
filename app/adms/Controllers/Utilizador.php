<?php

namespace App\adms\Controllers;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Gestão de contas de utilizador (Gerente/Técnico).
 */
class Utilizador {

    private $dados;
    private $dadosForm;

    public function index() {
        if (!empty(filter_input_array(INPUT_POST, FILTER_DEFAULT))) {
            $this->dadosForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            $model = new \App\adms\Models\AdmsUtilizador();
            if (isset($this->dadosForm['btnAlterarEstado'])) {
                $model->alterarEstado($this->dadosForm);
            } elseif (isset($this->dadosForm['btnEditarNivel'])) {
                $model->editarNivel($this->dadosForm);
            }
        }

        $model = new \App\adms\Models\AdmsUtilizador();
        $this->dados = $model->dadosUtilizadores();
        $carregarView = new \Core\ConfigView("adms/Views/utilizador/pgUtilizador", $this->dados);
        $carregarView->renderizar();
    }

}
