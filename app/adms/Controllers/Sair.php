<?php

namespace App\adms\Controllers;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Termina a sessão do utilizador.
 */
class Sair {

    public function index() {
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        }

        session_destroy();
        session_start();

        $_SESSION['msg'] = '
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Sessão terminada</strong> com sucesso.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            ';
        $urlDestino = URLADM . "login";
        header("Location: $urlDestino");
    }

}
