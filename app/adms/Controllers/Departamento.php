<?php

namespace App\adms\Controllers;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Departamentos da Universidade.
 */
class Departamento {

    private $dados;
    private $dadosForm;

    public function index() {
        if (!empty(filter_input_array(INPUT_POST, FILTER_DEFAULT))) {
            $this->dadosForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            $model = new \App\adms\Models\AdmsDepartamento();
            if (isset($this->dadosForm['btnCdsDepartamento'])) {
                $model->cdsDepartamento($this->dadosForm);
            } elseif (isset($this->dadosForm['btnDeletDepartamento'])) {
                $model->deleteDepartamento($this->dadosForm);
            } elseif (isset($this->dadosForm['btnEditDepartamento'])) {
                $model->editDepartamento($this->dadosForm);
            } else {
                $this->dados['form'] = $this->dadosForm;
            }
        }

        $model = new \App\adms\Models\AdmsDepartamento();
        $this->dados = $model->dadosDepartamentos();
        $carregarView = new \Core\ConfigView("adms/Views/departamento/pgDepartamento", $this->dados);
        $carregarView->renderizar();
    }

}
