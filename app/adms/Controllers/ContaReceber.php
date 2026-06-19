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
class ContaReceber {

    private $dados;
    private $dadosAlter;
    private $dadosPaginacao;
    private $dadosForm;

    public function index() {
        if (!empty(filter_input_array(INPUT_POST, FILTER_DEFAULT))) {
            $this->dadosForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

            //var_dump($this->dadosForm);
            //var_dump($_FILES);
            $modo = new \App\adms\Models\AdmsRecepcionista();
            if (isset($this->dadosForm['btnAdiantamento'])) {
                if ($this->filtararCampo()) {
                    $modo->adiantarConta($this->dadosForm);
                }
            } elseif (isset($this->dadosForm['btnDeleteConta'])) {
                $modo->deleteConta($this->dadosForm);
                
            } elseif (isset($this->dadosForm['btnPagar'])) {
                $modo->pagarConta($this->dadosForm);
            } else {
                $this->dados['form'] = $this->dadosForm;
            }
        }


        $this->dadosContaReceber();
        $carregarView = new \Core\ConfigView("adms/Views/contas/pgReceber", $this->dados, $this->dadosAlter, $this->dadosPaginacao);
        $carregarView->renderizar();
    }

    private function dadosContaReceber() {
        $dados = new \App\adms\Models\AdmsRecepcionista();
        $this->dados = $dados->dadosContaReceber();
    }

    private function filtararCampo() {
        if (($this->dadosForm['adiantameto'] = filter_input(INPUT_POST, "adiantameto", FILTER_VALIDATE_FLOAT))) {
            return true;
        } else {
            $_SESSION['msg'] = "<div class='alert alert-danger text-center'>O adiantamentodo Serviço Precissa Ser Um Número Real</div>";
            return false;
        }
    }

}
