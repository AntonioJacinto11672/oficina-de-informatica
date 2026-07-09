<?php

/**
 * Runner de migrações da base de dados.
 *
 * Aplica, por ordem, os ficheiros pendentes em database/migrations/*.php
 * e regista cada um na tabela schema_migrations, para nunca voltar a
 * correr o mesmo ficheiro duas vezes.
 *
 * Cada ficheiro de migração devolve um array:
 *   return [
 *       'up' => function (PDO $pdo): array {
 *           // ... alterações à BD ...
 *           return ['mensagens de relatório, ex: contagens de órfãos'];
 *       },
 *   ];
 *
 * Uso:
 *   - CLI:     php migrate.php
 *   - Browser: http://localhost/oficina-de-informatica/migrate.php
 */

define('R4F5CC', true);

require __DIR__ . '/vendor/autoload.php';

$config = \Core\Config::load();
$host   = $config['DB_HOST'] ?? 'localhost';
$port   = $config['DB_PORT'] ?? '3306';
$dbname = $config['DB_NAME'] ?? 'manutencao';
$user   = $config['DB_USER'] ?? 'root';
$pass   = $config['DB_PASS'] ?? '';

$isCli = (php_sapi_name() === 'cli');

function saida(string $linha, bool $isCli): void {
    echo $linha . ($isCli ? "\n" : "<br>\n");
}

if (!$isCli) {
    echo '<pre style="font-family:monospace;font-size:14px;">';
}

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (\Throwable $e) {
    saida('Erro de ligação à base de dados: ' . $e->getMessage(), $isCli);
    exit(1);
}

$pdo->exec("
    CREATE TABLE IF NOT EXISTS `schema_migrations` (
        `id`         INT(11)      NOT NULL AUTO_INCREMENT,
        `migration`  VARCHAR(191) NOT NULL,
        `applied_at` DATETIME     DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE KEY `migration` (`migration`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
");

$aplicadas = $pdo->query('SELECT migration FROM schema_migrations')->fetchAll(PDO::FETCH_COLUMN);

$ficheiros = glob(__DIR__ . '/database/migrations/*.php');
sort($ficheiros);

if (empty($ficheiros)) {
    saida('Nenhum ficheiro de migração encontrado em database/migrations/.', $isCli);
    exit(0);
}

$pendentes = 0;
foreach ($ficheiros as $ficheiro) {
    $nome = basename($ficheiro, '.php');
    if (in_array($nome, $aplicadas, true)) {
        continue;
    }
    $pendentes++;

    $migracao = require $ficheiro;
    if (!isset($migracao['up']) || !is_callable($migracao['up'])) {
        saida("ERRO: $nome não devolve uma função 'up' válida — ignorado.", $isCli);
        continue;
    }

    saida("=== A aplicar: $nome ===", $isCli);
    // Nota: instruções DDL (CREATE/ALTER TABLE) fazem commit implícito no MySQL,
    // por isso as migrações não são envolvidas numa transação — cada migração deve
    // ser escrita de forma idempotente (IF NOT EXISTS / verificações antes de alterar).
    try {
        $mensagens = ($migracao['up'])($pdo);
        $stmt = $pdo->prepare('INSERT INTO schema_migrations (migration) VALUES (?)');
        $stmt->execute([$nome]);
        saida("OK: $nome", $isCli);
        foreach ((array)$mensagens as $msg) {
            saida("  - $msg", $isCli);
        }
    } catch (\Throwable $e) {
        saida("ERRO em $nome: " . $e->getMessage(), $isCli);
        saida('A parar — corrija o problema antes de tentar novamente.', $isCli);
        if (!$isCli) {
            echo '</pre>';
        }
        exit(1);
    }
}

if ($pendentes === 0) {
    saida('Nada a fazer — todas as migrações já foram aplicadas.', $isCli);
} else {
    saida('Concluído.', $isCli);
}

if (!$isCli) {
    echo '</pre>';
}
