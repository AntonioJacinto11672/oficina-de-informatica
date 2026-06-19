<?php

namespace App\adms\Controllers;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Description of Home
 *
 * @author Double
 */
class Sair {

    public function index() {

        unset($_SESSION['idlogado'], $_SESSION['nome'], $_SESSION['email'], $_SESSION['usuario'], $_SESSION['telefone'], $_SESSION['sobrenome'], $_SESSION['logado'],$_SESSION['nbi'],$_SESSION['nif']);
        unset($_SESSION['msg_gerafico1'], $_SESSION['msg_gerafico2']);
        $_SESSION['msg'] = '
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Sessão Terminada!</strong> Com Sucesso.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            ';
        $urlDestino = URLADM . "login";
        header("Location: $urlDestino");
    }

}
