<?php

namespace App\adms\Controllers;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Alterar fotografia de uma peça/consumível.
 */
class EditarFoto {

    private $dados;
    private $dadosForm;

    public function index() {
        $idProduto = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$idProduto) {
            $destino = URLADM . "produto";
            header("Location: $destino");
            return;
        }

        if (!empty(filter_input_array(INPUT_POST, FILTER_DEFAULT))) {
            $this->dadosForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            if (isset($this->dadosForm['btnEditFoto'])) {
                $this->dadosForm['idproduto'] = $idProduto;
                $this->dadosForm['foto'] = $_FILES['foto'] ?? [];
                $model = new \App\adms\Models\AdmsProduto();
                if ($model->editFotoProduto($this->dadosForm)) {
                    $destino = URLADM . "produto";
                    header("Location: $destino");
                    return;
                }
            }
        }

        $model = new \App\adms\Models\AdmsProduto();
        $this->dados = $model->dadosProduto($idProduto);
        $carregarView = new \Core\ConfigView("adms/Views/produto/editFoto", $this->dados);
        $carregarView->renderizar();
    }

}
