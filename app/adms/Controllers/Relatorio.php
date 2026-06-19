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
class Relatorio {

    private $dados;
    private $dadosAlter;
    private $dadosPaginacao;
    private $dadosForm;

    public function index() {

        if (filter_input(INPUT_GET, 'value', FILTER_SANITIZE_SPECIAL_CHARS)) {
            @$this->dadosForm['value'] = filter_input(INPUT_GET, 'value', FILTER_SANITIZE_SPECIAL_CHARS);
            //var_dump($this->dadosForm);
            //echo $this->dadosForm['value'];
            if ($this->dadosForm['value'] == "rveiculo") {

                $this->dadosEntradaEquipamento();
                $this->veiwListarVeiculo();
            } elseif ($this->dadosForm['value'] == "rveiculoimprimir") {

                $this->dadosEntradaEquipamento();
                $this->veiwListarVeiculoImprimir();
            } elseif ($this->dadosForm['value'] == "catalogoProduto") {

                $this->dadosProdutos();
                $this->veiwCatalogoProduto();
            } elseif ($this->dadosForm['value'] == "catalogoProdutoimprimir") {

                $this->dadosProdutos();
                $this->veiwCatalogoProdutoImprimir();
            } elseif ($this->dadosForm['value'] == "pdf_teste") {
                $this->dadosForm['idorcamentos'] = filter_input(INPUT_GET, 'idorcamentos', FILTER_SANITIZE_SPECIAL_CHARS);
                $modal_new = new \App\adms\Models\AdmsMpdf();
                    $modal_new->teste();
                //var_dump($this->dadosForm);
                $modal_new = new \App\adms\Models\AdmsTecnico();
                $modal_new->gerar($this->dadosForm);
                
                
                //$this->veiwListarPdfTeste();
            } else {
                $destino = URLADM . "home";
                header("Location: $destino");
            }
        } else {
            $destino = URLADM . "home";
            header("Location: $destino");
        }
    }

    private function dadosProdutos() {
        $dados = new \App\adms\Models\AdmsTecnico();
        $this->dados = $dados->dadosProdutos();
    }

    private function veiwListarVeiculo() {
        $carregarView = new \Core\ConfigView("adms/Views/relatorio/relatorioEquipamento", $this->dados, $this->dadosAlter);
        $carregarView->renderizaRelatorio();
    }

    private function veiwCatalogoProduto() {
        $carregarView = new \Core\ConfigView("adms/Views/relatorio/relatorioCatalogoProduto", $this->dados, $this->dadosAlter);
        $carregarView->renderizaRelatorio();
    }

    private function veiwCatalogoProdutoImprimir() {
        $carregarView = new \Core\ConfigView("adms/Views/relatorio/imprimirCatalogoProduto", $this->dados, $this->dadosAlter);
        $carregarView->renderizaRelatorio();
    }

    private function veiwListarVeiculoImprimir() {
        $carregarView = new \Core\ConfigView("adms/Views/relatorio/imprimirEquipamento", $this->dados, $this->dadosAlter);
        $carregarView->renderizaRelatorio();
    }

    private function veiwListarPdfTeste() {
        $carregarView = new \Core\ConfigView("adms/Views/relatorio/pdf/pdf_teste", $this->dados, $this->dadosAlter);
        $carregarView->renderizaRelatorio();
    }

    private function dadosEntradaEquipamento() {
        $dadosVendas = new \App\adms\Models\AdmsTecnico();
        $this->dados = $dadosVendas->dadosEntradaEquipamento();
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

