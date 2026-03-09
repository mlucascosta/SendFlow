<?php

declare(strict_types=1);

namespace App\Services;

use PDO;

/**
 * Classe de acesso ao banco via PDO.
 * Database access class using PDO.
 *
 * Esta classe centraliza a criação de conexões MySQL/MariaDB
 * com opções seguras para o projeto SendFlow.
 * This class centralizes MySQL/MariaDB connection creation
 * with secure defaults for the SendFlow project.
 */
class Database
{
    /**
     * Cria e retorna uma conexão PDO configurada.
     * Creates and returns a configured PDO connection.
     *
     * @param array<string,mixed> $config Configuração de banco (host, port, database, charset, username, password).
     * @return PDO
     */
    public static function connection(array $config): PDO
    {
        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            $config['host'],
            $config['port'],
            $config['database'],
            $config['charset']
        );

        return new PDO($dsn, $config['username'], $config['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }
}
