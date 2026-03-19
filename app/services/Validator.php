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

    /**
     * Valida domínio simples sem protocolo.
     * Validates a simple domain without protocol.
     */
    public static function domain(string $domain): bool
    {
        return (bool) preg_match('/^(?=.{1,253}$)(?!-)(?:[a-z0-9-]{1,63}\.)+[a-z]{2,63}$/i', trim($domain));
    }

    /**
     * Valida senha mínima para onboarding inicial.
     * Validates minimum password strength for initial onboarding.
     */
    public static function password(string $password): bool
    {
        if (strlen($password) < 12) {
            return false;
        }

        return preg_match('/[A-Z]/', $password) === 1
            && preg_match('/[a-z]/', $password) === 1
            && preg_match('/[0-9]/', $password) === 1;
    }

    /**
     * Valida hostname/IP de banco.
     * Validates database hostname/IP.
     */
    public static function host(string $host): bool
    {
        $host = trim($host);
        if ($host === 'localhost') {
            return true;
        }

        return filter_var($host, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) !== false
            || filter_var($host, FILTER_VALIDATE_IP) !== false;
    }
}
