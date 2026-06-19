<?php

namespace App\adms\Controllers;

/**
 * Description of RelatorioTecnico
 *
 * @author Double
 */
class RelatorioTecnico {

    private $dados;
    private $dadosAlter;
    private $dadosPaginacao;
    private $dadosForm;

    public function index() {
        if (filter_input(INPUT_GET, 'relatorio', FILTER_SANITIZE_SPECIAL_CHARS)) {
            $this->dadosForm['tipo'] = filter_input(INPUT_GET, 'relatorio', FILTER_SANITIZE_SPECIAL_CHARS);

            //echo $this->dadosForm['tipo'];
            if ($this->dadosForm['tipo'] == "orcamento") {
                $this->dadosForm['idorcamentos'] = filter_input(INPUT_GET, 'idorcamentos', FILTER_VALIDATE_INT);

                //var_dump($this->dadosForm);

                $this->dadosOrcamentos();
                $this->dadosOrcamentosAlter();


                $carregarView = new \Core\ConfigView("adms/Views/relatorio/tecnico/relatorioEquipamento", $this->dados, $this->dadosAlter);
                $carregarView->renderizaRelatorio();
            } elseif ($this->dadosForm['tipo'] == "imprimirorcamento") {
            $this->dadosForm['idorcamentos'] = filter_input(INPUT_GET, 'idorcamentos', FILTER_VALIDATE_INT);

                //var_dump($this->dadosForm);

                $this->dadosOrcamentos();
                $this->dadosOrcamentosAlter();
                
                
                $carregarView = new \Core\ConfigView("adms/Views/relatorio/tecnico/imprimirRelatorioEquipamento", $this->dados, $this->dadosAlter);
                $carregarView->renderizaRelatorio();    
            }
        }
        //$this->dadosOperacaoMovimentacao();
        //$this->dadosMovimentacao();
    }

    private function dadosOrcamentos() {
        $dados = new \App\adms\Models\AdmsTecnico();
        $this->dados = $dados->dadosOrcamentosCompletoId($this->dadosForm);
    }

    private function dadosOrcamentosAlter() {
        $dados = new \App\adms\Models\AdmsTecnico();
        $this->dadosAlter = $dados->dadosOrcamentosCompletoAlter($this->dadosForm);
    }

}

