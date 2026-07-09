<?php

/**
 * Gatilho permanente do Planeamento de Manutenção Preventiva (módulo 13).
 *
 * Este projecto não tem nenhum job runner — "lembretes automáticos" e a
 * geração automática de ocorrências preventivas precisam de um gatilho a
 * nível de sistema operativo. Este script NÃO é descartável (ao contrário
 * dos ficheiros em database/migrations/); mantenha-o.
 *
 * Agendar, por exemplo, uma vez por dia:
 *   Windows (Tarefas Agendadas):
 *     Programa:      C:\xampp\php\php.exe
 *     Argumentos:    C:\xampp\htdocs\oficina-de-informatica\cron_planeamento.php
 *     Frequência:    Diariamente
 *
 *   Linux/cron:
 *     0 6 * * * php /caminho/para/oficina-de-informatica/cron_planeamento.php
 *
 * Uso manual (teste): php cron_planeamento.php
 */

define('R4F5CC', true);

require __DIR__ . '/vendor/autoload.php';

$isCli = (php_sapi_name() === 'cli');

try {
    $model = new \App\adms\Models\AdmsPlaneamento();
    $resultado = $model->gerarOcorrenciasPendentes();
    $mensagem = date('Y-m-d H:i:s') . " — {$resultado['geradas']} ocorrência(s) preventiva(s) gerada(s).";
} catch (\Throwable $e) {
    $mensagem = date('Y-m-d H:i:s') . ' — ERRO: ' . $e->getMessage();
}

echo $mensagem . ($isCli ? "\n" : "<br>\n");
