<?php
define('R4F5CC', true);

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Cache-Control: no-cache, no-store, must-revalidate');

require_once './vendor/autoload.php';

$status = 'ok';
$httpCode = 200;
$checks = [];

// Verificar ficheiro .env
if (file_exists(__DIR__ . '/.env')) {
    $checks['config'] = ['status' => 'ok', 'message' => 'Ficheiro .env encontrado'];
} else {
    $checks['config'] = ['status' => 'error', 'message' => 'Ficheiro .env não encontrado. Copie .env.example para .env'];
    $status = 'degraded';
}

// Verificar dependências Composer
if (is_dir(__DIR__ . '/vendor') && file_exists(__DIR__ . '/vendor/autoload.php')) {
    $checks['dependencies'] = ['status' => 'ok', 'message' => 'Dependências instaladas'];
} else {
    $checks['dependencies'] = ['status' => 'error', 'message' => 'Execute: composer install'];
    $status = 'error';
    $httpCode = 503;
}

// Verificar conexão com banco de dados
try {
    $config = \Core\Config::load();
    $dbHost = $config['DB_HOST'] ?? 'localhost';
    $dbPort = $config['DB_PORT'] ?? '3306';
    $dbName = $config['DB_NAME'] ?? 'manutencao';
    $dbUser = $config['DB_USER'] ?? 'root';
    $dbPass = $config['DB_PASS'] ?? '';

    $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";
    $pdo = new PDO($dsn, $dbUser, $dbPass, [PDO::ATTR_TIMEOUT => 5]);
    $pdo->query('SELECT 1');

    $checks['database'] = [
        'status'   => 'ok',
        'message'  => 'Conexão com MySQL estabelecida',
        'database' => $dbName,
        'host'     => $dbHost,
    ];
} catch (PDOException $e) {
    $checks['database'] = [
        'status'  => 'error',
        'message' => 'Falha na conexão: ' . $e->getMessage(),
    ];
    $status = 'error';
    $httpCode = 503;
} catch (Throwable $e) {
    $checks['database'] = [
        'status'  => 'error',
        'message' => $e->getMessage(),
    ];
    $status = 'error';
    $httpCode = 503;
}

// Verificar extensões PHP necessárias
$requiredExtensions = ['pdo', 'pdo_mysql', 'mbstring', 'gd', 'fileinfo'];
$missingExtensions = [];
foreach ($requiredExtensions as $ext) {
    if (!extension_loaded($ext)) {
        $missingExtensions[] = $ext;
    }
}

if (empty($missingExtensions)) {
    $checks['php_extensions'] = ['status' => 'ok', 'message' => 'Todas as extensões necessárias estão activas'];
} else {
    $checks['php_extensions'] = [
        'status'  => 'warning',
        'message' => 'Extensões em falta: ' . implode(', ', $missingExtensions),
    ];
    if ($status === 'ok') {
        $status = 'degraded';
    }
}

http_response_code($httpCode);

echo json_encode([
    'status'    => $status,
    'app'       => 'Sistema de Gestão de Manutenção Preventiva e Corretiva de Equipamentos Informáticos da Universidade Lusíada de Angola',
    'version'   => '5.0.0',
    'php'       => PHP_VERSION,
    'timestamp' => date('Y-m-d H:i:s'),
    'checks'    => $checks,
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
