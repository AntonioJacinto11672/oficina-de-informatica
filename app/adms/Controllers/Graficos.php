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
class Graficos {

    private $dados;
    private $dadosAlter;
    private $dadosPaginacao;
    private $dadosForm;

    public function index() {

        if (filter_input(INPUT_GET, 'value', FILTER_SANITIZE_SPECIAL_CHARS)) {
            @$this->dadosForm['value'] = filter_input(INPUT_GET, 'value', FILTER_SANITIZE_SPECIAL_CHARS);
            //var_dump($this->dadosForm);
            //echo $this->dadosForm['value'];
            if ($this->dadosForm['value'] == "toda_movimentacao") {

                $this->viewTodaMovimentacao();
            } elseif ($this->dadosForm['value'] == "entrada_movimentacao") {
                
                $this->dadosMovimentacaoEntrada();
                $this->viewMovimentacaoEntrada();
            } elseif ($this->dadosForm['value'] == "saida_movimentacao") {
                
                $this->dadosMovimentacaoSaida();
            $this->viewMovimentacaoSaida();
            } elseif ($this->dadosForm['value'] == "servico_maisprestados") {
                
                $this->dadosServicoMaisPrestados();
                $this->viewServicoMaisPrestados();  
            }
        } else {
            $destino = URLADM . "home";
            header("Location: $destino");
        }
    }

    private function viewMovimentacaoEntrada() {
        $carregarView = new \Core\ConfigView("adms/Views/graficos/pgEntrada", $this->dados, $this->dadosAlter);
        $carregarView->renderizar();
    }

    private function dadosMovimentacaoEntrada() {
        $modal = new \App\adms\Models\AdmsGraficos();
        $this->dados = $modal->dadosGraficoMoviEntrada();
    }

    private function viewTodaMovimentacao() {
        $carregarView = new \Core\ConfigView("adms/Views/graficos/pgMovimentacaoTotal", $this->dados, $this->dadosAlter);
        $carregarView->renderizar();
    }
    private function dadosTodaMovimentacao() {
        $modal = new \App\adms\Models\AdmsGraficos();
        $this->dados = $modal->dadosGraficoMoviSaida();
    }
    

    private function viewMovimentacaoSaida() {
        $carregarView = new \Core\ConfigView("adms/Views/graficos/pgSaida", $this->dados, $this->dadosAlter);
        $carregarView->renderizar();
    }

    private function dadosMovimentacaoSaida() {
        $modal = new \App\adms\Models\AdmsGraficos();
        $this->dados = $modal->dadosGraficoMoviSaida();
    }
    
    private function viewServicoMaisPrestados() {
        $carregarView = new \Core\ConfigView("adms/Views/graficos/pgServicosMaisPrestados", $this->dados, $this->dadosAlter);
        $carregarView->renderizar();
    }
    
    private function dadosServicoMaisPrestados() {
        $modal = new \App\adms\Models\AdmsGraficos();
        $this->dados = $modal->dadosServicoMaisPrestado();
    }

}
