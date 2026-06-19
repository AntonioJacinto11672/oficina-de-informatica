<?php

namespace App\adms\Controllers;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Description of Produto
 *
 * @author Double
 */
class EditarFoto {

    private $dados;
    private $dadosAlter;
    private $dadosPaginacao;
    private $dadosForm;

    public function index() {
        if (filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)) {
            @$this->dadosForm['idproduto'] = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            //var_dump($this->dadosForm);
            if (!empty(filter_input_array(INPUT_POST, FILTER_DEFAULT))) {
                $this->dadosForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

                //var_dump($this->dadosForm);
                //var_dump($_FILES);
                if (isset($this->dadosForm['btnEditFoto'])) {
                    $this->dadosForm['foto'] = ($_FILES['foto'] ? $_FILES['foto'] : null);
                    $cdsProduto = new \App\adms\Models\AdmsTecnico();
                    if ($cdsProduto->editFotoProduto($this->dadosForm)) {
                       $destino = URLADM. "produto";
                       header("Location: $destino");
                    }
                } else {
                    $this->dados['form'] = $this->dadosForm;
                }
            }


            $this->dadosProdutos();
            $carregarView = new \Core\ConfigView("adms/Views/produto/editFoto", $this->dados, $this->dadosAlter, $this->dadosPaginacao);
            $carregarView->renderizar();
        } else {
            $destino = URLADM . "produto";
            header("Location: $destino");
        }
    }

    private function dadosProdutos() {

        $dados = new \App\adms\Models\AdmsTecnico();
        $this->dados = $dados->dadosProdutosEditFoto($this->dadosForm);
    }

}

