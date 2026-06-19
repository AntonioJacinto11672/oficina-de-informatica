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
class Produto {

    private $dados;
    private $dadosAlter;
    private $dadosPaginacao;
    private $dadosForm;

    public function index() {
        if (!empty(filter_input_array(INPUT_POST, FILTER_DEFAULT))) {
            $this->dadosForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

            //var_dump($this->dadosForm);
            //var_dump($_FILES);
            if (isset($this->dadosForm['btnCdsProduto'])) {
                if ($this->filtararCampo()) {
                    //var_dump($this->dadosForm);
                    $this->dadosForm['foto'] = ($_FILES['foto'] ? $_FILES['foto'] : null);
                    $cdsProduto = new \App\adms\Models\AdmsTecnico();
                    $cdsProduto->cdsProduto($this->dadosForm);
                }
            } elseif (isset($this->dadosForm['btnDeletProduto'])) {
                $cdsProduto = new \App\adms\Models\AdmsTecnico();
                //var_dump($this->dadosForm);
                $cdsProduto->deletProduto($this->dadosForm);
            } elseif (isset($this->dadosForm['btnEditProduto'])) {
                //if ($this->filtararCampo()) {
                    $cdsProduto = new \App\adms\Models\AdmsTecnico();
                    $cdsProduto->editProduto($this->dadosForm);
                //}

                //var_dump($this->dadosForm);
            } elseif (isset($this->dadosForm['btnaddEstoque'])) {
                if ($this->filtararCampo()) {
                    $cdsProduto = new \App\adms\Models\AdmsTecnico();
                    $cdsProduto->addEstoque($this->dadosForm);
                }
            } else {
                $this->dados['form'] = $this->dadosForm;
            }
        }

        $this->dadosFornecedor();
        $this->dadosCategoria();
        $this->dadosProdutos();
        $carregarView = new \Core\ConfigView("adms/Views/produto/pgProduto", $this->dados, $this->dadosAlter, $this->dadosPaginacao);
        $carregarView->renderizar();
    }

    private function dadosFornecedor() {
        $dados = new \App\adms\Models\AdmsTecnico();
        $this->dadosAlter = $dados->dadosFornecedorId();
    }

    private function dadosCategoria() {
        $dados = new \App\adms\Models\AdmsTecnico();
        $this->dadosPaginacao = $dados->dadosCategoriaId();
    }

    private function dadosProdutos() {
        $dados = new \App\adms\Models\AdmsTecnico();
        $this->dados = $dados->dadosProdutos();
    }

    private function filtararCampo() {
        if (!$this->dadosForm['estoque'] = filter_input(INPUT_POST, "estoque", FILTER_VALIDATE_FLOAT)) {
            $_SESSION['msg'] = "<div class='alert alert-danger text-center'>O Valor do Estoque Precissa Ser Um Número Real</div>";
            return false;
        } elseif (!$this->dadosForm['valor_compra'] = filter_input(INPUT_POST, "valor_compra", FILTER_VALIDATE_FLOAT)) {
            $_SESSION['msg'] = "<div class='alert alert-danger text-center'>O Valor da compra Precissa Ser Um Número Real</div>";
            return false;
        } elseif (!$this->dadosForm['valor_venda'] = filter_input(INPUT_POST, "valor_venda", FILTER_VALIDATE_FLOAT)) {
            $_SESSION['msg'] = "<div class='alert alert-danger text-center'>O Valor da Venda Precissa Ser Um Número Real</div>";
            return false;
        } else {
            return true;
        }
    }

}

