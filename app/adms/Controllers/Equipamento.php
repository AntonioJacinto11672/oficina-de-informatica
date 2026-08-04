<?php

namespace App\adms\Controllers;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Equipamentos Informáticos da Universidade.
 */
class Equipamento {

    private $dados;
    private $dadosAlter;
    private $dadosForm;

    public function index() {
        if (!empty(filter_input_array(INPUT_POST, FILTER_DEFAULT))) {
            $this->dadosForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            $model = new \App\adms\Models\AdmsEquipamento();
            if (isset($this->dadosForm['btnCdsEquipamento'])) {
                $model->cdsEquipamento($this->dadosForm);
            } elseif (isset($this->dadosForm['btnEditEquipamento'])) {
                $model->editEquipamento($this->dadosForm);
            } elseif (isset($this->dadosForm['btnDeleteEquipamento'])) {
                $model->deleteEquipamento($this->dadosForm);
            } else {
                $this->dados['form'] = $this->dadosForm;
            }
        }

        $idHistorico = filter_input(INPUT_GET, 'historico', FILTER_VALIDATE_INT);
        if (!empty($idHistorico)) {
            $this->viewHistorico((int)$idHistorico);
            return;
        }

        $this->dadosEquipamentos();
        $this->dadosListas();
        $carregarView = new \Core\ConfigView("adms/Views/equipamento/pgEquipamento", $this->dados, $this->dadosAlter);
        $carregarView->renderizar();
    }

    private function viewHistorico(int $idEquipamento) {
        $model = new \App\adms\Models\AdmsEquipamento();
        $dados = [
            'equipamento' => $model->dadosEquipamento($idEquipamento),
            'historico' => $model->dadosHistorico($idEquipamento),
        ];
        $carregarView = new \Core\ConfigView("adms/Views/equipamento/pgHistoricoEquipamento", $dados);
        $carregarView->renderizar();
    }

    private function dadosEquipamentos() {
        $model = new \App\adms\Models\AdmsEquipamento();
        $this->dados = $model->dadosEquipamentos();
    }

    private function dadosListas() {
        $model = new \App\adms\Models\AdmsEquipamento();
        $this->dadosAlter = [
            'departamentos' => $model->dadosDepartamentos(),
            'categorias' => $model->dadosCategorias(),
            'fornecedores' => $model->dadosFornecedores(),
            'responsaveis' => $model->dadosResponsaveis(),
        ];
    }

}
