<?php

namespace App\adms\Controllers;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Equipamentos Abatidos (módulo 14).
 */
class Abatimento {

    private $dados;
    private $dadosAlter;

    public function index() {
        if (!empty(filter_input_array(INPUT_POST, FILTER_DEFAULT))) {
            $dadosForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

            if (isset($dadosForm['btnSolicitarAbatimento'])) {
                $model = new \App\adms\Models\AdmsAbatimento();
                $model->solicitarAbatimento($dadosForm);
            } elseif (isset($dadosForm['btnAprovarAbatimento'])) {
                $model = new \App\adms\Models\AdmsAbatimento();
                $model->aprovarAbatimento($dadosForm);
            } elseif (isset($dadosForm['btnRejeitarAbatimento'])) {
                $model = new \App\adms\Models\AdmsAbatimento();
                $model->rejeitarAbatimento($dadosForm);
            }
        }

        $model = new \App\adms\Models\AdmsAbatimento();
        $this->dados['lista'] = $model->dadosAbatimentos();
        $this->dadosAlter['equipamentos'] = $model->dadosEquipamentosParaAbater();

        $carregarView = new \Core\ConfigView("adms/Views/abatimento/pgAbatimento", $this->dados, $this->dadosAlter);
        $carregarView->renderizar();
    }
}
