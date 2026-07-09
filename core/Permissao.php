<?php

namespace Core;

/**
 * Description of Permissao
 *
 * @author Double
 */
if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

class Permissao {

    private $urlController;
    private $pgPublica;
    private $pgRestrita;
    private $resultado;

    function getResultado(): string {
        return $this->resultado;
    }

    public function index($urlController) {
        $this->urlController = $urlController;
        
        $this->pgPublica = ['login','home','sair','recuperarSenha'];
        if (in_array($this->urlController, $this->pgPublica)) {
            $this->resultado = $this->urlController;
        } else {
            $this->pgRestrita(); 
        }
    }
    private function pgRestrita() {
        $this->pgRestrita = ['dashboard','perfil','tecnico','perfil','recepcionista','fornecedor','produto','categoria','editarFoto','estoque','tipoServico','vendas','compras','contasPagar','contaReceber','cliente','equipamento',
            'movimentacao','orcamento','addProdutoOrcamento','orcamentoRecepcao','servico','comissoes','consultas','relatorio','entradaEquipamento','relatorioTecnico','chat','graficos','ocorrencia','diagnostico','execucao','categoriaEquipamento','abatimento','planeamento','comissaoConfig','movimentoEstoque'];
        if (in_array($this->urlController, $this->pgRestrita)) {
            $this->verificarLogin();
        } else {
            $_SESSION['msg'] = '
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Erro:!</strong> Página Não Encotrada.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            ';
            $urlDestino = URLADM . "login";
            header("Location: $urlDestino");
        }
    }

    private function verificarLogin() {
        if (isset($_SESSION['logado']) && isset($_SESSION['usuario']) && isset($_SESSION['idlogado'])) {
            $this->resultado = $this->urlController;
        } else {
            $_SESSION['msg'] = '
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Erro:!</strong> Para Acessar A Página tem que realizar o login.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            ';
            $urlDestino = URLADM . "login";
            header("Location: $urlDestino");
        }
        
    }

}

?>
