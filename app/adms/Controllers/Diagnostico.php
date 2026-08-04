<?php

namespace App\adms\Controllers;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Diagnóstico Técnico (módulo 10).
 */
class Diagnostico {

    private $dados;
    private $dadosAlter;
    private $dadosForm;

    public function index() {
        if (!empty(filter_input_array(INPUT_POST, FILTER_DEFAULT))) {
            $this->dadosForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

            if (isset($this->dadosForm['btnCdsDiagnostico'])) {
                $model = new \App\adms\Models\AdmsDiagnostico();
                $model->cdsDiagnostico($this->dadosForm);
            } elseif (isset($this->dadosForm['btnEditDiagnostico'])) {
                $model = new \App\adms\Models\AdmsDiagnostico();
                $model->editDiagnostico($this->dadosForm);
            } elseif (isset($this->dadosForm['btnEncaminharExecucao'])) {
                $model = new \App\adms\Models\AdmsDiagnostico();
                $model->encaminharParaExecucao($this->dadosForm);
            } else {
                $this->dados['form'] = $this->dadosForm;
            }
        }

        $idOcorrencia = filter_input(INPUT_GET, 'ocorrencia', FILTER_VALIDATE_INT);

        $model = new \App\adms\Models\AdmsDiagnostico();
        $ocorrenciaModel = new \App\adms\Models\AdmsOcorrencia();

        if ($idOcorrencia) {
            $this->dados['ocorrencia'] = $ocorrenciaModel->dadosOcorrencia($idOcorrencia);
            $this->dados['lista'] = $model->dadosDiagnosticosDaOcorrencia($idOcorrencia);
            $this->dadosAlter['equipamentos'] = $ocorrenciaModel->dadosEquipamentosDaOcorrencia($idOcorrencia);
            $this->dados['idocorrencia'] = $idOcorrencia;
        } else {
            $this->dados['lista'] = $model->dadosDiagnosticos();
        }

        $carregarView = new \Core\ConfigView("adms/Views/diagnostico/pgDiagnostico", $this->dados, $this->dadosAlter);
        $carregarView->renderizar();
    }
}
