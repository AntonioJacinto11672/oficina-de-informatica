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
class Movimentacao {

    private $dados;
    private $dadosAlter;
    private $dadosPaginacao;
    private $dadosForm;

    public function index() {

        $this->dadosOperacaoMovimentacao();
        $this->dadosMovimentacao();
        $carregarView = new \Core\ConfigView("adms/Views/consulta/pgMovimentacao", $this->dados, $this->dadosAlter, $this->dadosPaginacao);
        $carregarView->renderizar();
    }


    private function dadosMovimentacao() {
        $dados = new \App\adms\Models\AdmsRecepcionista();
        $this->dados = $dados->dadosMovimentacao();
    }
     private function dadosOperacaoMovimentacao() {
        $dados = new \App\adms\Models\AdmsRecepcionista();
        $this->dadosAlter = $dados->dadosOperacaoMovimentacao();
    }


}
