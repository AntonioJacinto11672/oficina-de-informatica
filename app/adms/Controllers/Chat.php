<?php

namespace App\adms\Controllers;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Description of Produto
 *
 * @author Double
 */
class Chat {

    private $dados;
    private $dadosAlter;
    private $dadosPaginacao;
    private $dadosForm;

    public function index() {

        $this->dadosOrcamento();
        $carregarView = new \Core\ConfigView("adms/Views/chat/pgChat", $this->dados, $this->dadosAlter, $this->dadosPaginacao);
        $carregarView->renderizarchat();
    }

    private function dadosOrcamento() {
        $dados = new \App\adms\Models\AdmsTecnico();
        $this->dados = $dados->dadosComissao();
    }
}

