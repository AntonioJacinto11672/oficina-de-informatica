<?php

namespace Core;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Classe para carregar configurações do arquivo .env
 */
class Config {
    private static $config = [];
    
    public static function load() {
        if (empty(self::$config)) {
            self::loadEnv();
        }
        return self::$config;
    }
    
    private static function loadEnv() {
        $envFile = dirname(__DIR__) . '/.env';
        
        if (!file_exists($envFile)) {
            die('Erro: Arquivo .env não encontrado. Copie .env.example para .env');
        }
        
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            if (strpos($line, '#') === 0) {
                continue; // Pula comentários
            }
            
            if (strpos($line, '=') !== false) {
                [$key, $value] = explode('=', $line, 2);
                self::$config[trim($key)] = trim($value);
            }
        }
    }
    
    public static function get($key, $default = null) {
        if (empty(self::$config)) {
            self::loadEnv();
        }
        return self::$config[$key] ?? $default;
    }
}
