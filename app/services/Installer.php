<?php

declare(strict_types=1);

namespace App\Services;

use PDO;
use RuntimeException;

/**
 * Serviço de onboarding/instalação do SendFlow.
 * SendFlow onboarding/installation service.
 */
class Installer
{
    /**
     * @param array<string,mixed> $databaseConfig
     * @return array<int,string>
     */
    public function runMigrations(array $databaseConfig, string $migrationsDir): array
    {
        $pdo = Database::connection($databaseConfig);
        $this->ensureMigrationTable($pdo);

        $files = glob(rtrim($migrationsDir, '/') . '/*.sql') ?: [];
        sort($files, SORT_STRING);

        $executed = [];
        foreach ($files as $file) {
            $filename = basename($file);
            if ($this->migrationAlreadyRan($pdo, $filename)) {
                continue;
            }

            $statements = $this->parseSqlStatements((string) file_get_contents($file));
            foreach ($statements as $statement) {
                $pdo->exec($statement);
            }

            $stmt = $pdo->prepare('INSERT INTO schema_migrations (migration_name) VALUES (:migration_name)');
            $stmt->execute(['migration_name' => $filename]);
            $executed[] = $filename;
        }

        return $executed;
    }

    /**
     * @param array<string,mixed> $databaseConfig
     * @return array<string,mixed>
     */
    public function buildEnvConfig(array $databaseConfig): array
    {
        return [
            'app' => [
                'name' => 'SendFlow',
                'env' => 'production',
                'debug' => false,
                'url' => 'http://localhost',
                'key' => $this->generateAppKey(),
            ],
            'resend' => [
                'webhook_secret' => bin2hex(random_bytes(24)),
            ],
            'database' => [
                'driver' => 'mysql',
                'host' => (string) $databaseConfig['host'],
                'port' => (int) $databaseConfig['port'],
                'database' => (string) $databaseConfig['database'],
                'username' => (string) $databaseConfig['username'],
                'password' => (string) $databaseConfig['password'],
                'charset' => 'utf8mb4',
            ],
        ];
    }

    /**
     * @param array<string,mixed> $envConfig
     */
    public function writeEnvFile(string $path, array $envConfig): void
    {
        $export = "<?php\n\ndeclare(strict_types=1);\n\nreturn " . var_export($envConfig, true) . ";\n";
        if (file_put_contents($path, $export, LOCK_EX) === false) {
            throw new RuntimeException('Unable to write config/env.php.');
        }

        @chmod($path, 0640);
    }

    /**
     * @param array<string,mixed> $databaseConfig
     */
    public function createAdmin(array $databaseConfig, string $name, string $email, string $password): int
    {
        $pdo = Database::connection($databaseConfig);

        $check = $pdo->query("SELECT id FROM users WHERE role = 'admin' ORDER BY id ASC LIMIT 1");
        $existing = $check !== false ? $check->fetch() : false;
        if (is_array($existing)) {
            return (int) $existing['id'];
        }

        $stmt = $pdo->prepare(
            'INSERT INTO users (
                name, email, password_hash, resend_domain, role, must_change_password,
                email_verified, is_active
            ) VALUES (
                :name, :email, :password_hash, NULL, :role, 0, 1, 1
            )'
        );
        $stmt->execute([
            'name' => trim($name),
            'email' => strtolower(trim($email)),
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'admin',
        ]);

        return (int) $pdo->lastInsertId();
    }

    /**
     * @param array<string,mixed> $databaseConfig
     */
    public function saveResendConfig(array $databaseConfig, array $envConfig, int $userId, string $domain, string $apiKey): void
    {
        $pdo = Database::connection($databaseConfig);
        $encrypted = Encryption::encrypt($apiKey, (string) $envConfig['app']['key']);
        $lastDigits = substr($apiKey, -4);

        $stmt = $pdo->prepare(
            'UPDATE users
             SET resend_domain = :resend_domain,
                 resend_api_key = :resend_api_key,
                 resend_api_key_last_digits = :resend_api_key_last_digits,
                 updated_at = CURRENT_TIMESTAMP
             WHERE id = :id'
        );
        $stmt->execute([
            'id' => $userId,
            'resend_domain' => strtolower(trim($domain)),
            'resend_api_key' => $encrypted,
            'resend_api_key_last_digits' => $lastDigits,
        ]);

        $this->upsertSystemSetting($pdo, 'installation_completed_at', date('c'));
        $this->upsertSystemSetting($pdo, 'mail_driver', 'resend');
        $this->upsertSystemSetting($pdo, 'resend_domain', strtolower(trim($domain)));
    }

    /**
     * @param array<string,mixed> $databaseConfig
     */
    public function installationAlreadyConfigured(array $databaseConfig): bool
    {
        $pdo = Database::connection($databaseConfig);
        $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
        if ($stmt === false || $stmt->fetch() === false) {
            return false;
        }

        $admin = $pdo->query("SELECT id FROM users WHERE role = 'admin' LIMIT 1");

        return $admin !== false && $admin->fetch() !== false;
    }

    private function ensureMigrationTable(PDO $pdo): void
    {
        $pdo->exec(
            'CREATE TABLE IF NOT EXISTS schema_migrations (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                migration_name VARCHAR(255) NOT NULL,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY uq_schema_migrations_name (migration_name)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
        );
    }

    private function migrationAlreadyRan(PDO $pdo, string $migrationName): bool
    {
        $stmt = $pdo->prepare('SELECT id FROM schema_migrations WHERE migration_name = :migration_name LIMIT 1');
        $stmt->execute(['migration_name' => $migrationName]);

        return $stmt->fetch() !== false;
    }

    /**
     * @return array<int,string>
     */
    private function parseSqlStatements(string $sql): array
    {
        $sql = preg_replace('/^\xEF\xBB\xBF/', '', $sql) ?? $sql;
        $lines = preg_split("/(\r\n|\r|\n)/", $sql) ?: [];
        $delimiter = ';';
        $buffer = '';
        $statements = [];

        foreach ($lines as $line) {
            if (preg_match('/^\s*DELIMITER\s+(.+)\s*$/i', $line, $matches) === 1) {
                $delimiter = trim($matches[1]);
                continue;
            }

            $buffer .= $line . "\n";
            while (($position = strpos($buffer, $delimiter)) !== false) {
                $statement = trim(substr($buffer, 0, $position));
                $buffer = substr($buffer, $position + strlen($delimiter));

                if ($statement !== '') {
                    $statements[] = $statement;
                }
            }
        }

        $remainder = trim($buffer);
        if ($remainder !== '') {
            $statements[] = $remainder;
        }

        return $statements;
    }

    private function generateAppKey(): string
    {
        return 'base64:' . base64_encode(random_bytes(32));
    }

    private function upsertSystemSetting(PDO $pdo, string $key, string $value): void
    {
        $stmt = $pdo->prepare(
            'INSERT INTO system_settings (setting_key, setting_value, setting_type)
             VALUES (:setting_key, :setting_value, :setting_type)
             ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value), updated_at = CURRENT_TIMESTAMP'
        );
        $stmt->execute([
            'setting_key' => $key,
            'setting_value' => $value,
            'setting_type' => 'string',
        ]);
    }
}
