<?php

namespace App\adms\Controllers;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

class RecuperarSenha {

    private $dados = [];

    public function index() {
        $post = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        // Passo 1 — receber email e enviar código
        if (!empty($post) && isset($post['btnEnviarCodigo'])) {
            $this->processarEnvioEmail($post);
            return;
        }

        // Passo 2 — verificar código
        if (!empty($post) && isset($post['btnVerificarCodigo'])) {
            $this->processarVerificacaoCodigo($post);
            return;
        }

        // Passo 2 — reenviar código
        if (!empty($post) && isset($post['btnReenviarCodigo'])) {
            $this->processarReenvio();
            return;
        }

        // Passo 3 — alterar senha
        if (!empty($post) && isset($post['btnAlterarSenha'])) {
            $this->processarAlteracaoSenha($post);
            return;
        }

        // Cancelar — limpar sessão e voltar ao login
        if (!empty($post) && isset($post['btnCancelar'])) {
            $this->limparSessaoReset();
            header("Location: " . URLADM . "login");
            exit;
        }

        $this->renderizar();
    }

    private function processarEnvioEmail(array $post): void {
        $email = trim($post['email'] ?? '');

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">Insira um endereço de email válido.</div>';
            $this->renderizar('email');
            return;
        }

        $model = new \App\adms\Models\AdmsRecuperarSenha();

        if (!$model->verificarEmail($email)) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">Email não encontrado ou conta desativada.</div>';
            $this->renderizar('email');
            return;
        }

        if ($model->gerarEnviarCodigo($email)) {
            $_SESSION['reset_email'] = $email;
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Código enviado para <strong>' . htmlspecialchars($email) . '</strong>. Verifique a sua caixa de entrada.</div>';
            header("Location: " . URLADM . "recuperarSenha");
            exit;
        }

        $this->renderizar('email');
    }

    private function processarVerificacaoCodigo(array $post): void {
        if (!isset($_SESSION['reset_email'])) {
            header("Location: " . URLADM . "recuperarSenha");
            exit;
        }

        $codigo = trim($post['codigo'] ?? '');

        if (empty($codigo) || !ctype_digit($codigo) || strlen($codigo) !== 6) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">O código deve ter 6 dígitos numéricos.</div>';
            $this->renderizar('codigo');
            return;
        }

        $model = new \App\adms\Models\AdmsRecuperarSenha();

        if ($model->verificarCodigo($_SESSION['reset_email'], $codigo)) {
            $_SESSION['reset_verificado'] = true;
            header("Location: " . URLADM . "recuperarSenha");
            exit;
        }

        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Código inválido ou expirado. Tente novamente.</div>';
        $this->renderizar('codigo');
    }

    private function processarReenvio(): void {
        if (!isset($_SESSION['reset_email'])) {
            header("Location: " . URLADM . "recuperarSenha");
            exit;
        }

        $model = new \App\adms\Models\AdmsRecuperarSenha();

        if ($model->gerarEnviarCodigo($_SESSION['reset_email'])) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Novo código enviado para <strong>' . htmlspecialchars($_SESSION['reset_email']) . '</strong>.</div>';
        }

        header("Location: " . URLADM . "recuperarSenha");
        exit;
    }

    private function processarAlteracaoSenha(array $post): void {
        if (!isset($_SESSION['reset_email']) || !isset($_SESSION['reset_verificado'])) {
            header("Location: " . URLADM . "recuperarSenha");
            exit;
        }

        $nova     = $post['nova_senha']      ?? '';
        $confirma = $post['confirmar_senha'] ?? '';

        if (strlen($nova) < 6) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">A senha deve ter no mínimo 6 caracteres.</div>';
            $this->renderizar('nova_senha');
            return;
        }

        if ($nova !== $confirma) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">As senhas não coincidem.</div>';
            $this->renderizar('nova_senha');
            return;
        }

        $model = new \App\adms\Models\AdmsRecuperarSenha();

        if ($model->alterarSenha($_SESSION['reset_email'], $nova)) {
            $this->limparSessaoReset();
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Senha alterada com sucesso! Pode fazer login com a nova senha.</div>';
            header("Location: " . URLADM . "login");
            exit;
        }

        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Erro ao alterar a senha. Tente novamente.</div>';
        $this->renderizar('nova_senha');
    }

    private function renderizar(string $forcar = ''): void {
        if ($forcar === 'email' || (!isset($_SESSION['reset_email']) && $forcar === '')) {
            $view = new \Core\ConfigView("adms/Views/recuperarSenha/pgEmail", $this->dados);
            $view->renderizarLogin();
            return;
        }

        if ($forcar === 'codigo' || (!isset($_SESSION['reset_verificado']) && $forcar === '')) {
            $view = new \Core\ConfigView("adms/Views/recuperarSenha/pgCodigo", $this->dados);
            $view->renderizarLogin();
            return;
        }

        $view = new \Core\ConfigView("adms/Views/recuperarSenha/pgNovaSenha", $this->dados);
        $view->renderizarLogin();
    }

    private function limparSessaoReset(): void {
        unset($_SESSION['reset_email'], $_SESSION['reset_verificado']);
    }
}
