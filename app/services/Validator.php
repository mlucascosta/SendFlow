<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Validador simples para entradas comuns da aplicação.
 * Simple validator for common application inputs.
 */
class Validator
{
    /**
     * Valida formato de email.
     * Validates email format.
     */
    public static function email(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Verifica se um valor obrigatório não está vazio.
     * Checks whether a required value is not empty.
     */
    public static function required(string $value): bool
    {
        return trim($value) !== '';
    }
}
