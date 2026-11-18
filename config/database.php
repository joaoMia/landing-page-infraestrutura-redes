<?php
/**
 * Conexão Segura com Banco de Dados MySQL via PDO
 * Implementa padrão Singleton e boas práticas de segurança contra SQL Injection.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/env.php';

class Database {
    private static ?PDO $instance = null;

    private function __construct() {}
    private function __clone() {}

    /**
     * Retorna a instância única da conexão PDO
     * 
     * @return PDO
     * @throws RuntimeException em caso de falha de conexão sem vazar credenciais
     */
    public static function getConnection(): PDO {
        if (self::$instance === null) {
            $host = env('DB_HOST', '127.0.0.1');
            $port = env('DB_PORT', '3306');
            $dbname = env('DB_NAME', 'infra_redes');
            $user = env('DB_USER', 'root');
            $pass = env('DB_PASS', '');

            $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                // Emulated prepares DESATIVADO: consultas preparadas reais no MySQL, máxima proteção contra SQL Injection
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::ATTR_PERSISTENT         => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
            ];

            try {
                self::$instance = new PDO($dsn, (string)$user, (string)$pass, $options);
            } catch (PDOException $e) {
                // Registra o erro de forma segura nos logs do servidor sem expor detalhes ao usuário
                error_log('[Database Error] Falha de conexão: ' . $e->getMessage());
                
                throw new RuntimeException('Não foi possível estabelecer conexão segura com a base de dados. Tente novamente mais tarde.');
            }
        }

        return self::$instance;
    }
}
