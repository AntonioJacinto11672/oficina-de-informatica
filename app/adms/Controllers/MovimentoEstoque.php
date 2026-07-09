<?php

namespace App\adms\Controllers;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Histórico de Movimentações de Stock (módulo 7).
 */
class MovimentoEstoque {

    private $dados;

    public function index() {
        $idProduto = filter_input(INPUT_GET, 'produto', FILTER_VALIDATE_INT);
        if (!$idProduto) {
            $destino = URLADM . "produto";
            header("Location: $destino");
            return;
        }

        $model = new \App\adms\Models\AdmsMovimentoEstoque();
        $this->dados['movimentos'] = $model->dadosMovimentosProduto($idProduto);
        $this->dados['produto'] = $model->dadosProduto($idProduto);

        $carregarView = new \Core\ConfigView("adms/Views/produto/pgMovimentoEstoque", $this->dados);
        $carregarView->renderizar();
    }
}
