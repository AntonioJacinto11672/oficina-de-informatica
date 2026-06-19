<?php

namespace App\adms\Models;
if(!defined('R4F5CC')){
    header("Location: /");
    die("Erro: Página não encontrada!");
}

use PDO;
/**
 * Description of Conn
 *
 * @author Celke
 */
class Conn
{
    private $connect;

    protected function connect() {
        try {
            $host   = \Core\Config::get('DB_HOST', 'localhost');
            $port   = \Core\Config::get('DB_PORT', '3306');
            $dbname = \Core\Config::get('DB_NAME', 'manutencao');
            $user   = \Core\Config::get('DB_USER', 'root');
            $pass   = \Core\Config::get('DB_PASS', '');
            $this->connect = new PDO('mysql:host=' . $host . ';port=' . $port . ';dbname=' . $dbname, $user, $pass);
            return $this->connect;
        } catch (\Exception $ex) {
            die('Erro: Por favor tente novamente. Caso o problema persista, entre em contato o administrador.');
        }
    }
    
}
