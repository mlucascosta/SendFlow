<?php

declare(strict_types=1);

namespace App\Services;

use PDO;
use RuntimeException;

/**
 * Classe de acesso ao banco via PDO.
 * Database access class using PDO.
 *
 * Esta classe centraliza a criação de conexões MySQL/MariaDB
 * e SQLite com opções seguras para o projeto SendFlow.
 * This class centralizes MySQL/MariaDB and SQLite connection creation
 * with secure defaults for the SendFlow project.
 */
class Database
{
    /**
     * Cria e retorna uma conexão PDO configurada.
     * Creates and returns a configured PDO connection.
     *
     * @param array<string,mixed> $config Configuração de banco.
     */
    public static function connection(array $config): PDO
    {
        $driver = strtolower((string) ($config['driver'] ?? 'mysql'));

        if ($driver === 'sqlite') {
            return self::sqliteConnection($config);
        }

        return self::mysqlConnection($config);
    }

    /**
     * @param array<string,mixed> $config
     */
    private static function mysqlConnection(array $config): PDO
    {
        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            (string) $config['host'],
            (int) $config['port'],
            (string) $config['database'],
            (string) ($config['charset'] ?? 'utf8mb4')
        );

        return new PDO($dsn, (string) ($config['username'] ?? ''), (string) ($config['password'] ?? ''), [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }

    /**
     * @param array<string,mixed> $config
     */
    private static function sqliteConnection(array $config): PDO
    {
        $path = trim((string) ($config['path'] ?? ''));
        if ($path === '') {
            throw new RuntimeException('SQLite path is required.');
        }

        $directory = dirname($path);
        if (!is_dir($directory) && !mkdir($directory, 0700, true) && !is_dir($directory)) {
            throw new RuntimeException('Unable to create SQLite directory: ' . $directory);
        }

        $pdo = new PDO('sqlite:' . $path, null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        $pdo->exec('PRAGMA foreign_keys = ON');
        $pdo->exec('PRAGMA journal_mode = WAL');
        $pdo->exec('PRAGMA busy_timeout = 5000');

        if (!file_exists($path)) {
            touch($path);
        }
        @chmod($path, 0600);
        @chmod($directory, 0700);

        return $pdo;
    }
}
