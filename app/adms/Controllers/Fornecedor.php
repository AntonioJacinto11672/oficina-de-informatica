<?php

namespace App\adms\Controllers;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

use PDO;

/**
 * Description of Fornecedor
 *
 * @author Double
 */
class Fornecedor {

    private $dados;
    private $dadosForm;

    public function index() {
        if (!empty(filter_input_array(INPUT_POST, FILTER_DEFAULT))) {
            $this->dadosForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            //$this->dadosForm['idusuario'] = $_SESSION['idlogado']; 
            //var_dump($this->dadosForm);
            //var_dump($_FILES);
            if (isset($this->dadosForm['btnCdsFornecedor'])) {
                //$this->dadosForm['foto'] = ($_FILES['foto'] ? $_FILES['foto'] : null);
                $cdsFornecedor = new \App\adms\Models\AdmsTecnico();
                $cdsFornecedor->cdsFornecedor($this->dadosForm);
            } elseif (isset($this->dadosForm['btnDeletFornecedor'])) {
                $cdsFornecedor = new \App\adms\Models\AdmsTecnico();
                $cdsFornecedor->deletFornecedor($this->dadosForm);
            } elseif (isset($this->dadosForm['btnEditFornecedor'])) {
                $cdsFornecedor = new \App\adms\Models\AdmsTecnico();
                $cdsFornecedor->editFornecedor($this->dadosForm);

                //var_dump($this->dadosForm);
            } else {
                $this->dados['form'] = $this->dadosForm;
            }
        }

        $this->dadosFornecedors();
        $carregarView = new \Core\ConfigView("adms/Views/fornecedor/pgFornecedor", $this->dados);
        $carregarView->renderizar();
    }

    public function dadosFornecedors() {
        $dadosFornecedors = new \App\adms\Models\AdmsTecnico();
        $this->dados = $dadosFornecedors->dadosFornecedor();
    }

}

