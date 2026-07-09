<?php

namespace App\adms\Controllers;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Categorias de Equipamento (módulo 4).
 */
class CategoriaEquipamento {

    private $dados;

    public function index() {
        if (!empty(filter_input_array(INPUT_POST, FILTER_DEFAULT))) {
            $dadosForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

            if (isset($dadosForm['btnCdsCategoriaEquipamento'])) {
                $model = new \App\adms\Models\AdmsCategoriaEquipamento();
                $model->cdsCategoriaEquipamento($dadosForm);
            } elseif (isset($dadosForm['btnEditCategoriaEquipamento'])) {
                $model = new \App\adms\Models\AdmsCategoriaEquipamento();
                $model->editCategoriaEquipamento($dadosForm);
            } elseif (isset($dadosForm['btnDeleteCategoriaEquipamento'])) {
                $model = new \App\adms\Models\AdmsCategoriaEquipamento();
                $model->deleteCategoriaEquipamento($dadosForm);
            } else {
                $this->dados['form'] = $dadosForm;
            }
        }

        $model = new \App\adms\Models\AdmsCategoriaEquipamento();
        $this->dados['lista'] = $model->dadosCategoriaEquipamento();

        $carregarView = new \Core\ConfigView("adms/Views/categoriaEquipamento/pgCategoriaEquipamento", $this->dados);
        $carregarView->renderizar();
    }
}
