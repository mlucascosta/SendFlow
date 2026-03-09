<?php

declare(strict_types=1);

return [
    'app' => [
        'name' => 'SendFlow',
        'env' => 'production',
        'debug' => false,
        'url' => 'https://seudominio.com',
        'key' => 'base64:change-me',
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
