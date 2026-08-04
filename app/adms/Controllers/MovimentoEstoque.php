<?php

namespace App\adms\Controllers;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Movimentações de Stock — usado pelos ecrãs "Entradas" e "Saídas" (filtro
 * por tipo via ?tipo=Entrada|Saida) e pelo histórico por peça (?produto=id).
 */
class MovimentoEstoque {

    private $dados;

    public function index() {
        $idProduto = filter_input(INPUT_GET, 'produto', FILTER_VALIDATE_INT);
        $tipo = filter_input(INPUT_GET, 'tipo', FILTER_DEFAULT);

        $model = new \App\adms\Models\AdmsMovimentoEstoque();

        if ($idProduto) {
            $this->dados['movimentos'] = $model->dadosMovimentosProduto($idProduto);
            $this->dados['produto'] = $model->dadosProduto($idProduto);
            $this->dados['titulo'] = 'Histórico de Movimentações';
        } else {
            $tipo = in_array($tipo, ['Entrada', 'Saida'], true) ? $tipo : null;
            $this->dados['movimentos'] = $model->dadosMovimentos($tipo);
            $this->dados['produto'] = null;
            $this->dados['titulo'] = $tipo === 'Entrada' ? 'Entradas de Stock' : ($tipo === 'Saida' ? 'Saídas de Stock' : 'Movimentações de Stock');
        }

        $carregarView = new \Core\ConfigView("adms/Views/produto/pgMovimentoEstoque", $this->dados);
        $carregarView->renderizar();
    }
}
