<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Verificador simples de assinatura do webhook.
 * Simple webhook signature verifier.
 */
class ResendWebhookVerifier
{
    /**
     * @param array<string,string> $server
     * @return array{valid:bool,reason:string}
     */
    public static function verify(string $rawBody, array $server, string $secret): array
    {
        $secret = trim($secret);
        if ($secret === '') {
            return ['valid' => false, 'reason' => 'webhook_secret_not_configured'];
        }

        $signature = self::header($server, 'HTTP_RESEND_SIGNATURE');
        $timestamp = self::header($server, 'HTTP_RESEND_TIMESTAMP');

        if ($signature === '' || $timestamp === '') {
            return ['valid' => false, 'reason' => 'missing_signature_headers'];
        }

        if (!ctype_digit($timestamp)) {
            return ['valid' => false, 'reason' => 'invalid_signature_timestamp'];
        }

        if (abs(time() - (int) $timestamp) > 300) {
            return ['valid' => false, 'reason' => 'signature_timestamp_expired'];
        }

        $expected = hash_hmac('sha256', $timestamp . '.' . $rawBody, $secret);
        $normalized = strtolower(preg_replace('/^sha256=/i', '', trim($signature)) ?? '');

        if (!hash_equals($expected, $normalized)) {
            return ['valid' => false, 'reason' => 'signature_mismatch'];
        }

        return ['valid' => true, 'reason' => 'signature_valid'];
    }

    /**
     * @param array<string,string> $server
     */
    private static function header(array $server, string $key): string
    {
        return isset($server[$key]) && is_string($server[$key]) ? trim($server[$key]) : '';
    }
}
