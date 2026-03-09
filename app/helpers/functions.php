<?php

declare(strict_types=1);

/**
 * Renderiza um template PHP com dados isolados.
 * Renders a PHP template with isolated data.
 *
 * @param string $template Nome do template sem extensão.
 * @param array<string,mixed> $data Dados disponíveis para a view.
 * @return string HTML renderizado.
 */
function view(string $template, array $data = []): string
{
    extract($data, EXTR_SKIP);
    ob_start();
    require __DIR__ . '/../../public/views/' . $template . '.php';

    return (string) ob_get_clean();
}
