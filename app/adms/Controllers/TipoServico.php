<?php

namespace App\adms\Controllers;

/**
 * Description of Produto
 *
 * @author Double
 */
class TipoServico {
    private $dados;
    public function index() {
        if (!empty(filter_input_array(INPUT_POST, FILTER_DEFAULT))) {
            $this->dadosForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            //var_dump($this->dadosForm);
            //var_dump($_FILES);
            if (isset($this->dadosForm['btnCdsTipo_servico'])) {
                $cdsTipo_Servico = new \App\adms\Models\AdmsTecnico();
                $cdsTipo_Servico->cdsTipo_servico($this->dadosForm);
            } elseif (isset($this->dadosForm['btnDelettipo_servico'])) {
                $cdsTipo_Servico = new \App\adms\Models\AdmsTecnico();
                $cdsTipo_Servico->deletTipoServico($this->dadosForm);
            } elseif (isset($this->dadosForm['btnEdittipo_servico'])) {
                $cdsTipo_Servico = new \App\adms\Models\AdmsTecnico();
                $cdsTipo_Servico->editTipoServico($this->dadosForm);
                
                //var_dump($this->dadosForm);
            } else {
                $this->dados['form'] = $this->dadosForm;
            }
        }

        $this->dadosTipo_Servico();
        $carregarView = new \Core\ConfigView("adms/Views/tipoServico/pgTipoServico", $this->dados);
        $carregarView->renderizar();
    }
    private function dadosTipo_Servico() {
        $dadosTipo_Servicos = new \App\adms\Models\AdmsTecnico();
        $this->dados = $dadosTipo_Servicos->dadosTipoServico();
    }
}

