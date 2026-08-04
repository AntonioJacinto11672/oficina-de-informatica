<?php

namespace Core;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Description of ConfigController
 *
 * @author Double
 */
class ConfigController {

    //put your code here
    private $url;

    public function __construct() {
        if (!empty(filter_input(INPUT_GET, "url", FILTER_DEFAULT))) {
            $this->url = filter_input(INPUT_GET, "url", FILTER_DEFAULT);
        } else {
            $this->url = "login";
        }
    }

    public function carregar() {
        try {
            $this->config();
        } catch (\Throwable $e) {
            http_response_code(503);
            echo '<!DOCTYPE html><html lang="pt"><head><meta charset="utf-8"><title>Serviço indisponível</title><style>body{font-family:Arial,sans-serif;padding:2rem;line-height:1.6;} .card{max-width:640px;margin:2rem auto;padding:1.5rem;border:1px solid #ddd;border-radius:8px;}</style></head><body><div class="card"><h1>Serviço indisponível</h1><p>Não foi possível estabelecer ligação ao banco de dados neste momento.</p><p>Verifique a configuração do sistema e tente novamente mais tarde.</p></div></body></html>';
            return;
        }

        $valPermissao = new \Core\Permissao();
        $valPermissao->index($this->url);
        $urlController = ucwords($this->url);
        $classe = "\\App\\adms\\Controllers\\" . $urlController;
        $classeCarregar = new $classe;
        $classeCarregar->index();
    }

    private function config() {
        $config = \Core\Config::load();
        define("NOME_INSTITUICAO", $config['APP_NAME'] ?? "Universidade Lusíada de Angola");
        define('URLADM', $config['APP_URL'] ?? "http://localhost/oficina-de-informatica/");
        define('ENDERECO_INSTITUICAO', $config['UNIVERSITY_ADDRESS'] ?? "Luanda, Mutamba Largo do Lumeji, nº 11/12");
        define("EMAIL_INSTITUICAO", $config['IT_DEPT_EMAIL'] ?? "geral@ula.co.ao");
        define("TELEFONE_INSTITUICAO", $config['IT_DEPT_PHONE'] ?? "+244 930 038 044");
        define('NIVEL_STOQUE', (int)($config['STOCK_LEVEL'] ?? 5)); // A partir de quantas unidades o stock de uma peça é considerado baixo
        define('DEBUG_MODE', $config['DEBUG'] === 'true' ? true : false);

        define('DBHOST', $config['DB_HOST'] ?? 'localhost');
        define('DBPORT', $config['DB_PORT'] ?? '3306');
        define('DBNAME', $config['DB_NAME'] ?? 'manutencao');
        define('DBUSER', $config['DB_USER'] ?? 'root');
        define('DBPASS', $config['DB_PASS'] ?? '');

        // A estrutura da base de dados é gerida por database/schema.sql (instalação de
        // raiz) + database/migrations/ (via migrate.php) — nunca aqui. DDL/seed não deve
        // correr a cada pedido HTTP; o utilizador Gerente inicial é criado apenas pelo
        // schema.sql ou por `php migrate.php`.
        mysqli_report(MYSQLI_REPORT_OFF);

        $newConn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME, DBPORT);
        if (!$newConn) {
            if (DEBUG_MODE) {
                throw new \RuntimeException('Erro de conexão ao banco de dados: ' . mysqli_connect_error());
            }
            throw new \RuntimeException('Não foi possível estabelecer ligação à base de dados. Verifique as credenciais em .env.');
        }
        mysqli_close($newConn);
    }

}

