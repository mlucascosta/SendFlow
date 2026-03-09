<?php

declare(strict_types=1);

namespace App\Services;

class Encryption
{
    public static function encrypt(string $value, string $key): string
    {
        $iv = random_bytes(16);
        $cipher = openssl_encrypt($value, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

        return base64_encode($iv . $cipher);
    }

    public static function decrypt(string $payload, string $key): string
    {
        $decoded = base64_decode($payload, true);
        if ($decoded === false) {
            return '';
        }

        $iv = substr($decoded, 0, 16);
        $ciphertext = substr($decoded, 16);

        return openssl_decrypt($ciphertext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv) ?: '';
    }
}
