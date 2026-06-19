<?php

session_start();
ob_start();
define('R4F5CC', true);

require './vendor/autoload.php';
$url = new Core\ConfigController();
$url->carregar();
// Excluir Orçamento Após XX Dias
$data_hoje = date('Y-m-d');

//$data_15 = date('Y-m-d', strtotime("".EXCLUIR_ORCAMENTO_DIAS." days",
//strtotime($data_hoje)));
//echo $data_15;
?>