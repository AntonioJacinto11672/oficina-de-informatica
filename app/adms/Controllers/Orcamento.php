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
class Orcamento {

    private $dados;
    private $dadosAlter;
    private $dadosPaginacao;
    private $dadosForm;

    public function index() {
        if (!empty(filter_input_array(INPUT_POST, FILTER_DEFAULT))) {
            $this->dadosForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

            //var_dump($this->dadosForm);
            //var_dump($_FILES);
            if (isset($this->dadosForm['btnAbriOrcamento'])) {
                $this->dadosForm['tipo'] = "Orçamento";
                if ($this->filtararCampo()) {
                    //var_dump($this->dadosForm);
                    //$this->dadosForm['foto'] = ($_FILES['foto'] ? $_FILES['foto'] : null);
                    $cdsProduto = new \App\adms\Models\AdmsTecnico();
                    $cdsProduto->abrirOrcamento($this->dadosForm);
                }
            } elseif (isset($this->dadosForm['btnDeletOrcamento'])) {
                $cdsProduto = new \App\adms\Models\AdmsTecnico();
                $cdsProduto->deletOrcamento($this->dadosForm);
            } elseif (isset($this->dadosForm['btnEditOrcamento'])) {
                if ($this->filtararCampo()) {
                    $cdsProduto = new \App\adms\Models\AdmsTecnico();
                    $cdsProduto->editOrcamento($this->dadosForm);
                    //var_dump($this->dadosForm);
                }

                //var_dump($this->dadosForm);
            } elseif (isset($this->dadosForm['btnAprovar'])) {
                $cdsProduto = new \App\adms\Models\AdmsTecnico();
                $this->dadosForm['status'] = "Aprovado";
                $cdsProduto->aprovarOrcamento($this->dadosForm);
            } elseif (isset($this->dadosForm['btnEnviar'])) {
                $dados = new \App\adms\Models\AdmsTecnico();
                //var_dump($this->dadosForm);
                $dados->enviar($this->dadosForm);
            } else {
                $this->dados['form'] = $this->dadosForm;
            }
        }

        $this->dadosTipoServico();
        $this->dadosOrcamento();
        $carregarView = new \Core\ConfigView("adms/Views/orcamento/pgOrcamento", $this->dados, $this->dadosAlter, $this->dadosPaginacao);
        $carregarView->renderizar();
    }

    private function dadosTipoServico() {
        $dados = new \App\adms\Models\AdmsTecnico();
        $this->dadosAlter = $dados->dadosTipoServico();
    }

    private function dadosUsuario() {
        $dados = new \App\adms\Models\AdmsTecnico();
        $this->dadosPaginacao = $dados->dadosCategoriaId();
    }

    private function dadosOrcamento() {
        $dados = new \App\adms\Models\AdmsTecnico();
        $this->dados = $dados->dadosOrcamentosTipo();
    }

    private function filtararCampo() {
        if (!$this->dadosForm['valor'] = filter_input(INPUT_POST, "valor", FILTER_VALIDATE_FLOAT)) {
            $_SESSION['msg'] = "<div class='alert alert-danger text-center'>O Valor da Garatia Precissa Ser Um Número Inteiro</div>";
            return false;
        } else {
            return true;
        }
    }

}

