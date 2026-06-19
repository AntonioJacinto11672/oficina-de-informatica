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
class AddProdutoOrcamento {

    private $dados;
    private $dadosAlter;
    private $dadosPaginacao;
    private $dadosForm;

    public function index() {
        if (!empty(filter_input(INPUT_GET, 'cliente', FILTER_SANITIZE_SPECIAL_CHARS))) {
            @$this->dadosForm['idorcamentos'] = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            //$this->dadosForm['idorc_prod'] = filter_input(INPUT_GET, 'idorc_prod', FILTER_VALIDATE_INT);
            //var_dump($this->dadosForm);
            if (!empty(filter_input_array(INPUT_POST, FILTER_DEFAULT))) {
                $this->dadosForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                $this->dadosForm['idorcamentos'] = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                @$this->dadosForm['cliente'] = filter_input(INPUT_GET, 'cliente', FILTER_SANITIZE_SPECIAL_CHARS);
                //var_dump($this->dadosForm);
                //var_dump($this->dadosForm);
                //var_dump($_FILES);
                //var_dump($this->dadosForm);
                if (isset($this->dadosForm['btnAddProduto'])) {
                    $cds = new \App\adms\Models\AdmsTecnico();
                    $cds->addProdutoOrcamento($this->dadosForm);
                } else {
                    $this->dados['form'] = $this->dadosForm;
                }
            }


            $this->dadosProdutos();
            $this->dadosProdutosOrc();
            $carregarView = new \Core\ConfigView("adms/Views/orcamento/pgAddProduto", $this->dados, $this->dadosAlter, $this->dadosPaginacao);
            $carregarView->renderizar();
        } elseif (filter_input(INPUT_GET, 'idorc_prod', FILTER_VALIDATE_INT)) {
            $this->dadosForm['idorc_prod'] = filter_input(INPUT_GET, 'idorc_prod', FILTER_VALIDATE_INT);
            @$this->dadosForm['idorcamentos'] = filter_input(INPUT_GET, 'idorcamentos', FILTER_VALIDATE_INT);

            //var_dump($this->dadosForm);
            $cds = new \App\adms\Models\AdmsTecnico();
             if ($cds->deleteAddProduto($this->dadosForm)) {
              $destino = URLADM . "addProdutoOrcamento?id=" . $this->dadosForm['idorcamentos'];
              header("Location: $destino");
              } else {
              $destino = URLADM . "addProdutoOrcamento?id=" . $this->dadosForm['idorcamentos'];
              header("Location: $destino");
              } 
        } elseif (filter_input(INPUT_GET, 'idorc_prod_reduzir', FILTER_VALIDATE_INT)) {
            $this->dadosForm['idorc_prod'] = filter_input(INPUT_GET, 'idorc_prod_reduzir', FILTER_VALIDATE_INT);
            @$this->dadosForm['idorcamentos'] = filter_input(INPUT_GET, 'idorcamentos', FILTER_VALIDATE_INT);
            @$this->dadosForm['quantidade'] = filter_input(INPUT_GET, 'quantidade', FILTER_VALIDATE_INT);
            //var_dump($this->dadosForm);
            $cds = new \App\adms\Models\AdmsTecnico();
            if ($cds->reduzirAddProduto($this->dadosForm)) {
                $destino = URLADM . "addProdutoOrcamento?id=" . $this->dadosForm['idorcamentos'];
                header("Location: $destino");
            } else {
                $destino = URLADM . "addProdutoOrcamento?id=" . $this->dadosForm['idorcamentos'];
                header("Location: $destino");
            }
        } else {
            $destino = URLADM . "orcamento";
            header("Location: $destino");
        }
    }

    private function dadosProdutos() {
        $dados = new \App\adms\Models\AdmsTecnico();
        $this->dados = $dados->dadosProdutos();
    }

    private function dadosProdutosOrc() {
        $dados = new \App\adms\Models\AdmsTecnico();
        $this->dadosAlter = $dados->dadosOrcamentosCompletoAlter($this->dadosForm);
    }

}

