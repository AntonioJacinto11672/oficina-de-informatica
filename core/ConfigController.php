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
        $this->config();
        $valPermissao = new \Core\Permissao();
        $valPermissao->index($this->url);
        $urlController = ucwords($this->url);
        $classe = "\\App\\adms\\Controllers\\" . $urlController;
        $classeCarregar = new $classe;
        $classeCarregar->index();
    }

    private function config() {
        $config = \Core\Config::load();
        define("NOME_OFICINA", $config['APP_NAME'] ?? "OFICINA DO BAIRRO");
        define('URLADM', $config['APP_URL'] ?? "http://localhost/oficinamecanica.co.ao/");
        define('ENDERECO_OFICINA', $config['OFFICE_ADDRESS'] ?? "Luanda Rua da CTT, Rangel");
        define("EMAIL_OFICINA", $config['OFFICE_EMAIL'] ?? "antjacinto11672@gmail.com");
        define("TELEFONE", $config['OFFICE_PHONE'] ?? "+244 931 950 857");
        define('NIVEL_STOQUE', (int)($config['STOCK_LEVEL'] ?? 5)); // A Partir De X Produtos O Nível de Estoue Estará Baixo
        define('DESCONTO_ORC', $config['DISCOUNT_ORC'] ?? "SIM");
        define('VALOR_DESCONTO', (float)($config['DISCOUNT_VALUE'] ?? 0.05)); // Valor Em Percetagem, Por Exemplo 5 vai ser 5%
        define('VALIDAR_ORCAMENTO_DIAS', (int)($config['VALIDATE_QUOTE_DAYS'] ?? 5));
        define('EXCLUIR_ORCAMENTO_DIAS', (int)($config['DELETE_QUOTE_DAYS'] ?? 15)); // Excluir Orçamento após 15 dias orcamento que estiver Aberto
        define('COMISSAO_TECNICO', $config['TECHNICIAN_COMMISSION'] ?? "SIM"); // Se não Existir Comissão no Sistema Muda Para Não
        define('VALOR_COMISSAO', (float)($config['COMMISSION_VALUE'] ?? 0.30));  // Colocar o vaor da comissão com a percetangem matendo 0 na frente, 0.30  corresponde a 30%
        define('DEBUG_MODE', $config['DEBUG'] === 'true' ? true : false);

        define('DBHOST', $config['DB_HOST'] ?? 'localhost');
        define('DBPORT', $config['DB_PORT'] ?? '3308');
        define('DBNAME', $config['DB_NAME'] ?? 'manutencao');
        define('DBUSER', $config['DB_USER'] ?? 'root');
        define('DBPASS', $config['DB_PASS'] ?? '');

        define('DIAS_ALERTA_RETORNO', 180); // Dias para avisar a recepção que o equipamento não retornou ao serviço — alerta após 180 dias
        define('MENAGEM_RETORNO', "Verificámos que já faz algum tempo que não realizámos nenhuma
                                  intervenção no seu equipamento. Temos promoções em diagnóstico,
                                  limpeza, actualização de software e vários outros serviços. Aproveite!");



        $newConn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME, DBPORT);
        if (!$newConn) {
            if (DEBUG_MODE) {
                die('Erro de conexão ao banco de dados: ' . mysqli_connect_error());
            }
            die('Erro: não foi possível conectar ao banco de dados. Verifique as credenciais em .env.');
        }

        $query = "CREATE TABLE IF NOT EXISTS ocorrencias (
            idocorrencia INT(11) NOT NULL AUTO_INCREMENT,
            id_orcamento INT(11) DEFAULT NULL,
            id_equipamento INT(11) DEFAULT NULL,
            id_tipo_servico INT(11) DEFAULT NULL,
            tecnico VARCHAR(100) DEFAULT NULL,
            tipo_manutencao VARCHAR(30) DEFAULT 'Corretiva',
            descricao TEXT DEFAULT NULL,
            estado VARCHAR(30) DEFAULT 'Aberta',
            data_abertura DATE DEFAULT NULL,
            data_prevista DATE DEFAULT NULL,
            data_encerramento DATE DEFAULT NULL,
            observacoes TEXT DEFAULT NULL,
            status VARCHAR(30) DEFAULT 'Aberta',
            created DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (idocorrencia),
            KEY idx_ocorrencias_data_prevista (data_prevista),
            KEY idx_ocorrencias_status (status)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        mysqli_query($newConn, $query);

        $query = "SELECT * FROM usuario WHERE nivel='adimin'";
        $result = mysqli_query($newConn, $query);
        if ($result && mysqli_num_rows($result) == 0) {
            $query = "INSERT INTO usuario (nbi,nif,nome,sobrenome,email,telefone,senha,nivel,st_conta) VALUES ('ALDADL1222334','ALDADL1222334','Antonio','Jacinto','antjacinto11672@gmail.com','937585960','827ccb0eea8a706c4c34a16891f84e7b','adimin','Ativada')";
            mysqli_query($newConn, $query);
        }
    }

}

