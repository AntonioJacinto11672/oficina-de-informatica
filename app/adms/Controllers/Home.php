<?php

namespace App\adms\Controllers;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Dashboard inicial — Gerente ou Técnico.
 */
class Home {

    private $dados;

    public function index() {
        $model = new \App\adms\Models\AdmsHome();
        $this->dados = $model->index();
        $carregarView = new \Core\ConfigView("adms/Views/home/home", $this->dados);
        $carregarView->renderizar();
    }

}
