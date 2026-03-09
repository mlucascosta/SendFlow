<?php

declare(strict_types=1);

function view(string $template, array $data = []): string
{
    extract($data, EXTR_SKIP);
    ob_start();
    require __DIR__ . '/../../public/views/' . $template . '.php';

    return (string) ob_get_clean();
}
