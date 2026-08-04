<?php

namespace Core;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Controlo de acesso: rotas públicas, rotas que exigem sessão iniciada, e
 * rotas restritas ao papel de Gerente (o papel Técnico só acede às rotas
 * partilhadas listadas no seu menu).
 */
class Permissao {

    private $urlController;
    private $pgPublica;
    private $pgRestrita;
    private $pgSomenteGerente;
    private $resultado;

    function getResultado(): string {
        return $this->resultado;
    }

    public function index($urlController) {
        $this->urlController = $urlController;

        $this->pgPublica = ['login', 'sair', 'recuperarSenha'];
        if (in_array($this->urlController, $this->pgPublica)) {
            $this->resultado = $this->urlController;
        } else {
            $this->pgRestrita();
        }
    }

    private function pgRestrita() {
        $this->pgRestrita = [
            // Partilhadas por Gerente e Técnico
            'home', 'perfil', 'editarFoto', 'equipamento', 'ocorrencia', 'diagnostico',
            'execucao', 'planeamento', 'historico',
            // Exclusivas do Gerente
            'utilizador', 'tecnico', 'departamento', 'fornecedor', 'tipoManutencao', 'produto', 'categoria',
            'categoriaEquipamento', 'abatimento', 'compras', 'estoque', 'movimentoEstoque', 'relatorio',
            'graficos', 'configuracoes',
        ];
        $this->pgSomenteGerente = [
            'utilizador', 'tecnico', 'departamento', 'fornecedor', 'tipoManutencao', 'produto', 'categoria',
            'categoriaEquipamento', 'abatimento', 'compras', 'estoque', 'movimentoEstoque', 'relatorio',
            'graficos', 'configuracoes',
        ];

        if (in_array($this->urlController, $this->pgRestrita)) {
            $this->verificarLogin();
        } else {
            $_SESSION['msg'] = '
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Erro:</strong> Página não encontrada.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            ';
            $urlDestino = URLADM . "login";
            header("Location: $urlDestino");
            exit;
        }
    }

    private function verificarLogin() {
        if (!isset($_SESSION['logado']) || !isset($_SESSION['nivel']) || !isset($_SESSION['idlogado'])) {
            $_SESSION['msg'] = '
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Erro:</strong> A sua sessão expirou. Inicie sessão novamente.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            ';
            $urlDestino = URLADM . "login";
            header("Location: $urlDestino");
            exit;
        }

        if (in_array($this->urlController, $this->pgSomenteGerente) && $_SESSION['nivel'] !== 'gerente') {
            $_SESSION['msg'] = '
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Erro:</strong> Acesso negado. Esta área está reservada ao Gerente de TI.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            ';
            $urlDestino = URLADM . "home";
            header("Location: $urlDestino");
            exit;
        }

        $this->resultado = $this->urlController;
    }

}
