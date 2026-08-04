<?php

namespace App\adms\Controllers;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Configurações institucionais do sistema (leitura — definidas em .env).
 */
class Configuracoes {

    public function index() {
        $dados = [
            'nome_instituicao' => NOME_INSTITUICAO,
            'endereco_instituicao' => ENDERECO_INSTITUICAO,
            'email_instituicao' => EMAIL_INSTITUICAO,
            'telefone_instituicao' => TELEFONE_INSTITUICAO,
            'nivel_stock' => NIVEL_STOQUE,
            'url_app' => URLADM,
        ];
        $carregarView = new \Core\ConfigView("adms/Views/configuracoes/pgConfiguracoes", $dados);
        $carregarView->renderizar();
    }
}
