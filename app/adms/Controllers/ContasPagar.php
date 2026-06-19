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
class ContasPagar {

    private $dados;
    private $dadosAlter;
    private $dadosPaginacao;
    private $dadosForm;

    public function index() {
        if (!empty(filter_input_array(INPUT_POST, FILTER_DEFAULT))) {
            $this->dadosForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

            //var_dump($this->dadosForm);
            //var_dump($_FILES);
            if (isset($this->dadosForm['btnCdsContas_apagar'])) {
                if ($this->filtararCampo()) {
                    //var_dump($this->dadosForm);
                    $this->dadosForm['foto'] = ($_FILES['foto'] ? $_FILES['foto'] : null);
                    //var_dump($_FILES);
                    $cdsContaApagar = new \App\adms\Models\AdmsRecepcionista();
                    $cdsContaApagar->cdsContaApagar($this->dadosForm);
                }
            } elseif (isset($this->dadosForm['btnDeletcontas_apagar'])) {
                $cdsContaApagar = new \App\adms\Models\AdmsRecepcionista();
                $cdsContaApagar->deleteContaApagar($this->dadosForm);
            } elseif (isset($this->dadosForm['btnEditcontas_apagar'])) {
                if ($this->filtararCampo()) {
                    $cdsContaApagar = new \App\adms\Models\AdmsRecepcionista();
                    $cdsContaApagar->editContaApagar($this->dadosForm);
                }

                //var_dump($this->dadosForm);
            } elseif (isset($this->dadosForm['btnAprovarConta'])) {
                $cdsContaApagar = new \App\adms\Models\AdmsRecepcionista();
                $cdsContaApagar->aprovarContaPagar($this->dadosForm);
            } else {
                $this->dados['form'] = $this->dadosForm;
            }
        }

        
        $this->dadosContasApagar();
        $carregarView = new \Core\ConfigView("adms/Views/contas/pgPagar", $this->dados, $this->dadosAlter, $this->dadosPaginacao);
        $carregarView->renderizar();
    }

    private function dadosContasApagar() {
        $dados = new \App\adms\Models\AdmsRecepcionista();
        $this->dados = $dados->dadoscontaPagar();
    }

    private function filtararCampo() {
        if (!$this->dadosForm['valor'] = filter_input(INPUT_POST, "valor", FILTER_VALIDATE_FLOAT)) {
            $_SESSION['msg'] = "<div class='alert alert-danger text-center'>O Valor do Estoque Precissa Ser Um Número Real</div>";
            return false;
        } else {
            return true;
        }
    }

}
