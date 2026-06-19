<?php

namespace App\adms\Controllers;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Description of Login
 *
 * @author Double
 */
class Home {

    private $dados;
    private $dadosAlter;

    public function index() {

        $this->DadosHome();
        $this->DadosHome1();
        $carregarView = new \Core\ConfigView("adms/Views/home/home", $this->dados, $this->dadosAlter);
        $carregarView->renderizar();
    }

    public function DadosHome() {
        $modal = new \App\adms\Models\AdmsHome();
        $this->dados = $modal->index();
    }

    public function DadosHome1() {
        $modal = new \App\adms\Models\AdmsHome();
        $this->dadosAlter = $modal->dadosGrafico();
    }

}
