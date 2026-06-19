<?php

namespace App\adms\Controllers;

/**
 * Description of RelatorioTecnico
 *
 * @author Double
 */
class Dashboard {

    private $dados;
    private $dadosAlter;
    private $dadosRelatorio;
    private $dadosForm;
    private $dadosPaginacao;

    public function index() {
        $madal = new \App\adms\Models\AdmsHome();
        //var_dump($_GET);



        if (!empty(filter_input_array(INPUT_GET, FILTER_DEFAULT))) {
            $this->dadosForm = filter_input_array(INPUT_GET, FILTER_DEFAULT);
            //var_dump($this->dadosForm);
            if (isset($this->dadosForm['btnRelatorioServico'])) {

                $this->dadosServico();
                //var_dump($this->dados);
                //var_dump($this->dados);
                $this->dadosAlter = $this->dadosForm;
                $carregarView = new \Core\ConfigView("adms/Views/relatorio/relatorioServico", $this->dados, $this->dadosAlter);
                $carregarView->renderizaRelatorio();
            } elseif (isset($this->dadosForm['btnRelatorioOrcamento'])) {

                $this->dadosServico();
                //var_dump($this->dados);
                //var_dump($this->dados);
                $this->dadosAlter = $this->dadosForm;
                $carregarView = new \Core\ConfigView("adms/Views/relatorio/relatorioOrcamento", $this->dados, $this->dadosAlter);
                $carregarView->renderizaRelatorio();
            } elseif (isset($this->dadosForm['btnRelatorioMovimentacao'])) {

                $this->dadosMovimentacao();
                $this->dadosOperacaoMovimentacao();
                $this->dadosAlter = $this->dadosForm;
                $carregarView = new \Core\ConfigView("adms/Views/relatorio/relatorioMovimentacao", $this->dados, $this->dadosAlter, $this->dadosPaginacao);
                $carregarView->renderizaRelatorio();
            } elseif (isset($this->dadosForm['btnRelatorioCompras'])) {

                $this->dadosCompras();
                $this->dadosAlter = $this->dadosForm;
                $carregarView = new \Core\ConfigView("adms/Views/relatorio/relatorioCompras", $this->dados, $this->dadosAlter);
                $carregarView->renderizaRelatorio();
            } elseif (isset($this->dadosForm['btnRelatorioVendas'])) {

                $this->dadosVendas();
                $this->dadosAlter = $this->dadosForm;
                $carregarView = new \Core\ConfigView("adms/Views/relatorio/relatorioVendas", $this->dados, $this->dadosAlter);
                $carregarView->renderizaRelatorio();
            } elseif (isset($this->dadosForm['btnRelatorioContaReceber'])) {

                $this->dadosContaReceber();
                $this->dadosAlter = $this->dadosForm;
                $carregarView = new \Core\ConfigView("adms/Views/relatorio/relatorioConta_receber", $this->dados, $this->dadosAlter);
                $carregarView->renderizaRelatorio();
            } elseif (isset($this->dadosForm['btnRelatorioContaPagar'])) {

                $this->dadosContasApagar();
                $this->dadosAlter = $this->dadosForm;
                $carregarView = new \Core\ConfigView("adms/Views/relatorio/relatorioConta_pagar", $this->dados, $this->dadosAlter);
                $carregarView->renderizaRelatorio();
            } elseif (isset($this->dadosForm['btnRelatorioComissao'])) {

                $this->dadosComissao();
                $this->dadosAlter = $this->dadosForm;
                $carregarView = new \Core\ConfigView("adms/Views/relatorio/relatorioComissao", $this->dados, $this->dadosAlter);
                $carregarView->renderizaRelatorio();
            } else {
                $destino = URLADM . "home";
                header("Location: $destino");
            }
        }


        //$this->dadosMovimentacao();
    }

    private function dadosOrcamentos() {
        $dados = new \App\adms\Models\AdmsTecnico();
        $this->dados = $dados->dadosOrcamentosCompletoId($this->dadosForm);
    }

    private function dadosServico() {
        $madal = new \App\adms\Models\AdmsHome();
        $this->dados = $madal->filterROrcamento($this->dadosForm);
    }

    private function dadosMovimentacao() {
        $dados = new \App\adms\Models\AdmsHome();
        $this->dados = $dados->dadosMovimentacao($this->dadosForm);
    }

    private function dadosOperacaoMovimentacao() {
        $dados = new \App\adms\Models\AdmsHome();
        $this->dadosPaginacao = $dados->dadosOperacaoMovimentacao($this->dadosForm);
    }

    private function dadosContasApagar() {
        $dados = new \App\adms\Models\AdmsHome();
        $this->dados = $dados->dadoscontaPagar($this->dadosForm);
    }

    private function dadosContaReceber() {
        $dados = new \App\adms\Models\AdmsHome();
        $this->dados = $dados->dadosContaReceber($this->dadosForm);
    }

    private function dadosCompras() {
        $dadosCompras = new \App\adms\Models\AdmsHome();
        $this->dados = $dadosCompras->dadosCompras($this->dadosForm);
    }

    private function dadosVendas() {
        $dadosVendas = new \App\adms\Models\AdmsHome();
        $this->dados = $dadosVendas->dadosVendas($this->dadosForm);
    }
   
    private function dadosComissao() {
        $dados = new \App\adms\Models\AdmsHome();
        $this->dados = $dados->dadosComissao($this->dadosForm);
    }

}

