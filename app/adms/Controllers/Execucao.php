<?php

namespace App\adms\Controllers;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Execução da Manutenção (módulo 12).
 */
class Execucao {

    private $dados;
    private $dadosAlter;
    private $dadosForm;

    public function index() {
        if (!empty(filter_input_array(INPUT_POST, FILTER_DEFAULT))) {
            $this->dadosForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

            if (isset($this->dadosForm['btnIniciarExecucao'])) {
                $model = new \App\adms\Models\AdmsExecucao();
                $model->iniciarExecucao($this->dadosForm);
            } elseif (isset($this->dadosForm['btnAddPeca'])) {
                $model = new \App\adms\Models\AdmsExecucao();
                $model->addPeca($this->dadosForm);
            } elseif (isset($this->dadosForm['btnRemoverPeca'])) {
                $model = new \App\adms\Models\AdmsExecucao();
                $model->removerPeca($this->dadosForm);
            } elseif (isset($this->dadosForm['btnEncerrarExecucao'])) {
                $model = new \App\adms\Models\AdmsExecucao();
                $model->encerrarExecucao($this->dadosForm);
            }
        }

        $idOcorrencia = filter_input(INPUT_GET, 'ocorrencia', FILTER_VALIDATE_INT);
        $model = new \App\adms\Models\AdmsExecucao();

        if ($idOcorrencia) {
            $ocorrenciaModel = new \App\adms\Models\AdmsOcorrencia();
            $this->dados['ocorrencia'] = $ocorrenciaModel->dadosOcorrencia($idOcorrencia);
            $this->dados['idocorrencia'] = $idOcorrencia;
            $this->dados['lista'] = $model->dadosExecucoesDaOcorrencia($idOcorrencia);
            $this->dadosAlter['produtos'] = (new \App\adms\Models\AdmsProduto())->dadosProdutos();

            $this->dados['pecasPorExecucao'] = [];
            foreach ($this->dados['lista'] as $ex) {
                $this->dados['pecasPorExecucao'][$ex['idexecucao']] = $model->dadosPecasDaExecucao($ex['idexecucao']);
            }
        } else {
            $this->dados['lista'] = $model->dadosExecucoes();
        }

        $carregarView = new \Core\ConfigView("adms/Views/execucao/pgExecucao", $this->dados, $this->dadosAlter);
        $carregarView->renderizar();
    }
}
