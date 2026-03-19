<?php

declare(strict_types=1);

/**
 * Exemplo de arquivo de ambiente.
 * Environment example file.
 *
 * Este arquivo serve como referência para geração de `config/env.php`
 * durante o instalador web.
 * This file is a reference used to generate `config/env.php`
 * during the web installer flow.
 *
 * @return array<string,mixed>
 */
return [
    'app' => [
        'name' => 'SendFlow',
        'env' => 'production',
        'debug' => false,
        'url' => 'https://seudominio.com',
        'key' => 'base64:change-me',
    ],
    'resend' => [
        'webhook_secret' => 'whsec_change_me',
    ],
    'database' => [
        'driver' => 'mysql',
        'host' => 'localhost',
        'port' => 3306,
        'database' => 'sendflow_db',
        'username' => 'sendflow_user',
        'password' => '',
        'charset' => 'utf8mb4',
    ],
];
