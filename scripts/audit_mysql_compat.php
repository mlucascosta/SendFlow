<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$sqlFiles = glob($root . '/install/migrations/*.sql') ?: [];

$forbiddenPatterns = [
    '/\bLANGUAGE\s+plpgsql\b/i' => 'PostgreSQL LANGUAGE plpgsql clause found',
    '/\bRETURN\s+QUERY\b/i' => 'PostgreSQL RETURN QUERY found',
    '/::[a-z_][a-z0-9_]*/i' => 'PostgreSQL cast operator (::) found',
    '/\bSERIAL\b/i' => 'PostgreSQL SERIAL type found',
    '/\bJSONB\b/i' => 'PostgreSQL JSONB type found',
    '/\bILIKE\b/i' => 'PostgreSQL ILIKE operator found',
    '/\bRETURNING\b/i' => 'PostgreSQL RETURNING clause found',
    '/\bUUID_GENERATE_[A-Z0-9_]*\b/i' => 'PostgreSQL UUID function found',
    '/\bGEN_RANDOM_UUID\b/i' => 'PostgreSQL random UUID function found',
];

$errors = [];

foreach ($sqlFiles as $sqlFile) {
    $contents = (string) file_get_contents($sqlFile);
    foreach ($forbiddenPatterns as $pattern => $message) {
        if (preg_match($pattern, $contents) === 1) {
            $errors[] = basename($sqlFile) . ': ' . $message;
        }
    }

    if (preg_match('/^\s*DELIMITER\s+\$\$/mi', $contents) === 1) {
        $errors[] = basename($sqlFile) . ': uses $$ delimiter; prefer MySQL/MariaDB-style // delimiter';
    }
}

if ($errors !== []) {
    fwrite(STDERR, "MySQL/MariaDB compatibility audit failed:\n");
    foreach ($errors as $error) {
        fwrite(STDERR, ' - ' . $error . "\n");
    }
    exit(1);
}

fwrite(STDOUT, "MySQL/MariaDB compatibility audit passed for " . count($sqlFiles) . " SQL files.\n");
