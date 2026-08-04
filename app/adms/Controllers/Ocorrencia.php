<?php

namespace App\adms\Controllers;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Gestão de Ocorrências (módulo 9) — ponto de entrada do fluxo de manutenção.
 */
class Ocorrencia {

    private $dados;
    private $dadosAlter;
    private $dadosForm;

    public function index() {
        if (!empty(filter_input_array(INPUT_POST, FILTER_DEFAULT))) {
            $this->dadosForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

            if (isset($this->dadosForm['btnCdsOcorrencia'])) {
                $model = new \App\adms\Models\AdmsOcorrencia();
                $model->cdsOcorrencia($this->dadosForm);
            } elseif (isset($this->dadosForm['btnEditOcorrencia'])) {
                $model = new \App\adms\Models\AdmsOcorrencia();
                $model->editOcorrencia($this->dadosForm);
            } elseif (isset($this->dadosForm['btnDeleteOcorrencia'])) {
                $model = new \App\adms\Models\AdmsOcorrencia();
                $model->deleteOcorrencia($this->dadosForm);
            } elseif (isset($this->dadosForm['btnAtribuirTecnico'])) {
                $model = new \App\adms\Models\AdmsOcorrencia();
                $model->atribuirTecnico($this->dadosForm);
            } elseif (isset($this->dadosForm['btnAlterarEstadoOcorrencia'])) {
                $model = new \App\adms\Models\AdmsOcorrencia();
                $model->alterarEstado($this->dadosForm);
            } else {
                $this->dados['form'] = $this->dadosForm;
            }
        }

        if (filter_input(INPUT_GET, 'historico', FILTER_VALIDATE_INT)) {
            $this->viewHistorico((int)filter_input(INPUT_GET, 'historico', FILTER_VALIDATE_INT));
            return;
        }

        $this->carregarDadosLista();
        $carregarView = new \Core\ConfigView("adms/Views/ocorrencia/pgOcorrencia", $this->dados, $this->dadosAlter);
        $carregarView->renderizar();
    }

    private function carregarDadosLista(): void {
        $model = new \App\adms\Models\AdmsOcorrencia();
        $this->dados['lista'] = $model->dadosOcorrencias();
        $this->dadosAlter['equipamentos'] = $model->dadosEquipamentosDisponiveis();
        $this->dadosAlter['tiposManutencao'] = $model->dadosTiposManutencao();
        $this->dadosAlter['tecnicos'] = $model->dadosTecnicos();
        $this->dadosAlter['estados'] = \App\adms\Models\AdmsOcorrencia::ESTADOS;
        $this->dadosAlter['prioridades'] = \App\adms\Models\AdmsOcorrencia::PRIORIDADES;

        // equipamentos/histórico já associados a cada ocorrência, para preencher os modais de edição
        $this->dadosAlter['equipamentosPorOcorrencia'] = [];
        foreach ($this->dados['lista'] as $ocorrencia) {
            $id = $ocorrencia['idocorrencia'];
            $this->dadosAlter['equipamentosPorOcorrencia'][$id] = $model->dadosEquipamentosDaOcorrencia($id);
        }
    }

    private function viewHistorico(int $idocorrencia): void {
        $model = new \App\adms\Models\AdmsOcorrencia();
        $this->dados['ocorrencia'] = $model->dadosOcorrencia($idocorrencia);
        $this->dados['historico'] = $model->dadosHistoricoOcorrencia($idocorrencia);
        $this->dados['equipamentos'] = $model->dadosEquipamentosDaOcorrencia($idocorrencia);
        $carregarView = new \Core\ConfigView("adms/Views/ocorrencia/pgHistoricoOcorrencia", $this->dados);
        $carregarView->renderizar();
    }
}
