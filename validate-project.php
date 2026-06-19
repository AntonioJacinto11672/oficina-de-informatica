<?php

session_start();
ob_start();
define('R4F5CC', true);

require './vendor/autoload.php';
require './core/Config.php';

class ProjetoValidator {
    private $errors = [];
    private $warnings = [];
    private $success = [];
    private $config;
    
    public function __construct() {
        try {
            $this->config = \Core\Config::load();
        } catch (Exception $e) {
            die("❌ Erro crítico ao carregar configurações: " . $e->getMessage());
        }
    }
    
    public function run() {
        echo "\n╔════════════════════════════════════════════════════════════╗\n";
        echo "║          🔍 VALIDAÇÃO COMPLETA DO PROJETO              ║\n";
        echo "╚════════════════════════════════════════════════════════════╝\n\n";
        
        $this->validarPHP();
        $this->validarComposer();
        $this->validarBancoDados();
        $this->validarPermissoes();
        $this->validarCodigo();
        $this->validarSeguranca();
        
        $this->exibirResultados();
    }
    
    private function validarPHP() {
        echo "📌 VALIDANDO AMBIENTE PHP...\n";
        
        // Versão PHP
        $phpVersion = phpversion();
        if (version_compare($phpVersion, '7.4.0') >= 0) {
            $this->success[] = "PHP versão: $phpVersion";
        } else {
            $this->errors[] = "PHP versão: $phpVersion (mínimo 7.4.0 requerido)";
        }
        
        // Extensões requeridas
        $extensoes_obrigatorias = ['pdo', 'pdo_mysql', 'json', 'curl'];
        foreach ($extensoes_obrigatorias as $ext) {
            if (extension_loaded($ext)) {
                $this->success[] = "Extensão $ext: ✓";
            } else {
                $this->errors[] = "Extensão $ext: ✗ (FALTA)";
            }
        }
        
        // Extensões opcionais
        $extensoes_opcionais = ['gd', 'mbstring', 'fileinfo'];
        foreach ($extensoes_opcionais as $ext) {
            if (extension_loaded($ext)) {
                $this->success[] = "Extensão $ext (opcional): ✓";
            } else {
                $this->warnings[] = "Extensão $ext: não instalada (necessária para mPDF com imagens)";
            }
        }
        
        echo "\n";
    }
    
    private function validarComposer() {
        echo "📦 VALIDANDO COMPOSER...\n";
        
        if (!file_exists('./vendor/autoload.php')) {
            $this->errors[] = "Composer autoloader não encontrado";
            return;
        }
        
        $this->success[] = "Composer instalado";
        
        // Verificar dependências principais
        $dependencias = [
            'PHPMailer\\PHPMailer\\PHPMailer' => 'PHPMailer',
            'Mpdf\\Mpdf' => 'mPDF'
        ];
        
        foreach ($dependencias as $classe => $nome) {
            if (class_exists($classe)) {
                $this->success[] = "$nome: ✓";
            } else {
                $this->errors[] = "$nome: ✗ (não carregado)";
            }
        }
        
        echo "\n";
    }
    
    private function validarBancoDados() {
        echo "🗄️  VALIDANDO BANCO DE DADOS...\n";
        
        try {
            $pdo = new PDO(
                'mysql:host=' . ($this->config['DB_HOST'] ?? 'localhost') . 
                ';port=' . ($this->config['DB_PORT'] ?? '3306') . 
                ';dbname=' . ($this->config['DB_NAME'] ?? 'manutencao'),
                $this->config['DB_USER'] ?? 'root',
                $this->config['DB_PASS'] ?? ''
            );
            
            $this->success[] = "Conexão MySQL: ✓";
            
            // Contar tabelas
            $stmt = $pdo->query("SHOW TABLES");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $this->success[] = "Tabelas no banco: " . count($tables);
            
            // Verificar tabelas críticas
            $tabelas_criticas = ['categoria', 'clientes', 'orcamentos', 'equipamento', 'tecnicos'];
            foreach ($tabelas_criticas as $table) {
                if (in_array($table, $tables)) {
                    $this->success[] = "Tabela $table: ✓";
                } else {
                    $this->warnings[] = "Tabela $table: não encontrada";
                }
            }
            
        } catch (PDOException $e) {
            $this->errors[] = "Erro MySQL: " . $e->getMessage();
        }
        
        echo "\n";
    }
    
    private function validarPermissoes() {
        echo "🔐 VALIDANDO PERMISSÕES...\n";
        
        $diretorios = [
            'app/adms/Views' => 'Views',
            'vendor' => 'Vendor',
            '.' => 'Raiz'
        ];
        
        foreach ($diretorios as $dir => $nome) {
            if (is_readable($dir)) {
                $this->success[] = "$nome: leitura ✓";
            } else {
                $this->warnings[] = "$nome: sem permissão de leitura";
            }
            
            if (is_writable($dir)) {
                $this->success[] = "$nome: escrita ✓";
            } else {
                $this->warnings[] = "$nome: sem permissão de escrita";
            }
        }
        
        echo "\n";
    }
    
    private function validarCodigo() {
        echo "📝 VALIDANDO CÓDIGO PHP...\n";
        
        $files_checked = 0;
        $files_errors = 0;
        
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator('./app'),
            RecursiveIteratorIterator::LEAVES_ONLY
        );
        
        foreach ($iterator as $file) {
            if ($file->getExtension() === 'php') {
                $files_checked++;
                $output = [];
                exec('php -l ' . escapeshellarg($file->getPathname()), $output, $return);
                
                if ($return !== 0) {
                    $files_errors++;
                    $this->errors[] = "Erro em " . $file->getFilename() . ": " . implode(' ', $output);
                }
            }
        }
        
        if ($files_errors === 0) {
            $this->success[] = "Validação PHP: $files_checked arquivos ✓";
        } else {
            $this->errors[] = "Encontrados $files_errors erros em $files_checked arquivos";
        }
        
        echo "\n";
    }
    
    private function validarSeguranca() {
        echo "🛡️  VALIDANDO SEGURANÇA...\n";
        
        // Verificar .env
        if (file_exists('./.env')) {
            if (!file_exists('./.gitignore') || !preg_match('/.env/', file_get_contents('./.gitignore'))) {
                $this->warnings[] = ".env pode estar versionado no Git";
            } else {
                $this->success[] = ".env protegido no .gitignore ✓";
            }
        }
        
        // Verificar credenciais hardcoded
        if (file_exists('./index.php')) {
            $content = file_get_contents('./index.php');
            if (strpos($content, '$pass = ""') !== false || strpos($content, "password") !== false) {
                $this->warnings[] = "Possível credencial em index.php - verifique segurança";
            }
        }
        
        // Verificar debug
        if (($this->config['DEBUG'] ?? 'false') === 'true') {
            $this->warnings[] = "DEBUG ativado - desative em produção";
        } else {
            $this->success[] = "DEBUG desativado ✓";
        }
        
        echo "\n";
    }
    
    private function exibirResultados() {
        echo "╔════════════════════════════════════════════════════════════╗\n";
        echo "║                      RESULTADO FINAL                       ║\n";
        echo "╚════════════════════════════════════════════════════════════╝\n\n";
        
        // Sucessos
        if (!empty($this->success)) {
            echo "✅ SUCESSOS (" . count($this->success) . "):\n";
            foreach ($this->success as $msg) {
                echo "   • $msg\n";
            }
            echo "\n";
        }
        
        // Avisos
        if (!empty($this->warnings)) {
            echo "⚠️  AVISOS (" . count($this->warnings) . "):\n";
            foreach ($this->warnings as $msg) {
                echo "   • $msg\n";
            }
            echo "\n";
        }
        
        // Erros
        if (!empty($this->errors)) {
            echo "❌ ERROS (" . count($this->errors) . "):\n";
            foreach ($this->errors as $msg) {
                echo "   • $msg\n";
            }
            echo "\n";
        }
        
        // Status final
        echo "╔════════════════════════════════════════════════════════════╗\n";
        if (empty($this->errors)) {
            echo "║ ✅ PROJETO PRONTO PARA USAR!                             ║\n";
        } elseif (count($this->errors) <= 2) {
            echo "║ ⚠️  CORREÇÕES NECESSÁRIAS (veja acima)                   ║\n";
        } else {
            echo "║ ❌ ERROS CRÍTICOS (correção obrigatória)                ║\n";
        }
        echo "╚════════════════════════════════════════════════════════════╝\n\n";
    }
}

$validator = new ProjetoValidator();
$validator->run();

?>
