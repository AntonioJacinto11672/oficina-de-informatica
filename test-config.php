<?php

session_start();
ob_start();
define('R4F5CC', true);

require './vendor/autoload.php';
require './core/Config.php';

echo "=== TESTE DE CONFIGURAÇÃO DO PROJETO ===\n\n";

// 1. Verificar configuração
echo "1. CARREGANDO CONFIGURAÇÕES:\n";
$config = \Core\Config::load();
echo "   ✓ Arquivo .env carregado com sucesso\n";
echo "   - App: " . $config['APP_NAME'] . "\n";
echo "   - URL: " . $config['APP_URL'] . "\n";
echo "   - Banco: " . $config['DB_NAME'] . "\n\n";

// 2. Verificar Composer
echo "2. VERIFICANDO DEPENDÊNCIAS:\n";
if (file_exists('./vendor/autoload.php')) {
    echo "   ✓ Composer autoloader encontrado\n";
    echo "   - PHPMailer: " . (class_exists('PHPMailer\PHPMailer\PHPMailer') ? "✓" : "✗") . "\n";
    echo "   - mPDF: " . (class_exists('Mpdf\Mpdf') ? "✓" : "✗") . "\n\n";
} else {
    echo "   ✗ Composer não está instalado\n\n";
}

// 3. Testar conexão com banco de dados
echo "3. TESTANDO CONEXÃO COM BANCO DE DADOS:\n";
try {
    $db = $config['DB_HOST'] ?? "localhost";
    $port = $config['DB_PORT'] ?? "3306";
    $dbname = $config['DB_NAME'] ?? "manutencao";
    $user = $config['DB_USER'] ?? "root";
    $pass = $config['DB_PASS'] ?? "";
    
    $connection = new PDO(
        'mysql:host=' . $db . ';port=' . $port . ';dbname=' . $dbname,
        $user,
        $pass
    );
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "   ✓ Conexão com MySQL bem-sucedida\n";
    echo "   - Host: " . $db . "\n";
    echo "   - Banco: " . $dbname . "\n";
    echo "   - Usuário: " . $user . "\n\n";
    
    // Tentar listar tabelas
    $stmt = $connection->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "   Tabelas encontradas: " . count($tables) . "\n";
    if (count($tables) > 0) {
        echo "   - " . implode("\n   - ", array_slice($tables, 0, 5));
        if (count($tables) > 5) {
            echo "\n   ... e mais " . (count($tables) - 5) . " tabelas\n";
        }
        echo "\n\n";
    }
    
} catch (PDOException $e) {
    echo "   ✗ Erro na conexão:\n";
    echo "   " . $e->getMessage() . "\n";
    echo "   Verifique suas credenciais no arquivo .env\n\n";
} catch (Exception $e) {
    echo "   ✗ Erro: " . $e->getMessage() . "\n\n";
}

// 4. Verificar direitos de arquivo
echo "4. VERIFICANDO PERMISSÕES:\n";
$dirs_to_check = [
    'app/adms/Views',
    'vendor'
];

foreach ($dirs_to_check as $dir) {
    if (is_writable($dir)) {
        echo "   ✓ " . $dir . " - permissão OK\n";
    } else {
        echo "   ⚠ " . $dir . " - verifique permissões\n";
    }
}
echo "\n";

// 5. Verificar constantes importantes
echo "5. VERIFICANDO CONSTANTES DO SISTEMA:\n";
if (!defined('R4F5CC')) {
    echo "   ⚠ Constante de segurança não definida\n";
} else {
    echo "   ✓ Sistema seguro\n";
}
echo "\n";

echo "=== FIM DO TESTE ===\n";
echo "Se todos os testes passaram, o projeto está pronto para usar!\n";
?>
