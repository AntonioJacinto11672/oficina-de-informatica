<?php

namespace Core;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Description of ConfigView
 *
 * @author Double
 */
class ConfigView {

    private $nome;
    private $dados;
    private $dadosAlter;
    private $dadosPaginacao;

    public function __construct($nome, array $dados = null, array $dadosAlter = null, array $dadosPaginacao = null) {
        $this->nome = $nome;
        $this->dados = $dados;
        $this->dadosAlter = $dadosAlter;
        $this->dadosPaginacao = $dadosPaginacao;
    }

    public function renderizar() {
        if (file_exists('app/' . $this->nome . '.php')) {
            include 'app/adms/Views/include/head.php';
            include 'app/adms/Views/include/dashboard.php';
            include 'app/' . $this->nome . '.php';
            include 'app/adms/Views/include/footer.php';
        } else {
            echo '
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Erro:!</strong> Ao Carregar a página.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            ';
        }
    }
    

    public function renderizarLogin() {
        if (file_exists('app/' . $this->nome . '.php')) {
            include 'app/adms/Views/include/head_login.php';
            include 'app/' . $this->nome . '.php';
            include 'app/adms/Views/include/footer_login.php';
        } else {
            echo '
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Erro:!</strong> Ao Carregar a página.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            ';
        }
    }

    public function renderizarFoto() {
        if (file_exists('app/' . $this->nome . '.php')) {
            include 'app/adms/Views/include/head.php';
            //include 'app/adms/Views/include/dashboard.php';
            include 'app/' . $this->nome . '.php';
            include 'app/adms/Views/include/footer_login.php';
        } else {
            echo '
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Erro:!</strong> Ao Carregar a página.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            ';
        }
    }
    public function renderizaRelatorio() {
        if (file_exists('app/' . $this->nome . '.php')) {
            include 'app/adms/Views/include/head_relatorio.php';
            //include 'app/adms/Views/include/dashboard.php';
            include 'app/' . $this->nome . '.php';
            include 'app/adms/Views/include/footer_relatorio.php';
        } else {
            echo '
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Erro:!</strong> Ao Carregar a página.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            ';
        }
    }
    
    public function renderizarchat() {
        if (file_exists('app/' . $this->nome . '.php')) {
            include 'app/adms/Views/include/head_chat.php';
            include 'app/' . $this->nome . '.php';
            include 'app/adms/Views/include/footer_chat.php';
        } else {
            echo '
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Erro:!</strong> Ao Carregar a página.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            ';
        }
    }

}
