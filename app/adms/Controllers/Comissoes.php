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
class Comissoes {

    private $dados;
    private $dadosAlter;
    private $dadosPaginacao;
    private $dadosForm;

    public function index() {

        $this->dadosOrcamento();
        $carregarView = new \Core\ConfigView("adms/Views/orcamento/pgComissao", $this->dados, $this->dadosAlter, $this->dadosPaginacao);
        $carregarView->renderizar();
    }

    private function dadosOrcamento() {
        $dados = new \App\adms\Models\AdmsTecnico();
        $this->dados = $dados->dadosComissao();
    }
}

