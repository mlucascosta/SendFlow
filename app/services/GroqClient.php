<?php

declare(strict_types=1);

namespace App\Services;

use RuntimeException;

/**
 * Cliente mínimo da API Groq com fallback entre modelos.
 * Minimal Groq API client with model fallback.
 */
class GroqClient
{
    public function __construct(
        private IntegrationSettingsRepository $settingsRepository
    ) {
    }

    /**
     * @param array<string,mixed> $options
     * @return array<string,mixed>
     */
    public function respond(string $feature, string $input, array $options = []): array
    {
        $provider = $this->settingsRepository->getAiProvider('groq');
        if ($provider === null || (int) ($provider['is_enabled'] ?? 0) !== 1 || ($provider['api_key'] ?? '') === '') {
            return [
                'status' => 'disabled',
                'provider' => 'groq',
                'feature' => $feature,
                'message' => 'Groq is disabled until an API key is saved in the database.',
            ];
        }

        $featureConfig = $this->settingsRepository->getFeatureConfig($feature);
        $chain = $this->resolveModelChain($featureConfig, $provider, $feature);
        $lastError = null;

        foreach ($chain as $index => $model) {
            try {
                $response = $this->request($provider['api_key'], $model, $input, $options);
                $response['status'] = $index === 0 ? 'success' : 'fallback';
                $response['model_chain'] = $chain;

                return $response;
            } catch (RuntimeException $exception) {
                $lastError = $exception->getMessage();

                if (!$this->shouldFallback($lastError)) {
                    break;
                }
            }
        }

        return [
            'status' => 'failed',
            'provider' => 'groq',
            'feature' => $feature,
            'message' => $lastError ?? 'Unknown Groq error',
            'model_chain' => $chain,
        ];
    }

    /**
     * @param array<string,mixed>|null $featureConfig
     * @param array<string,mixed> $provider
     * @return array<int,string>
     */
    private function resolveModelChain(?array $featureConfig, array $provider, string $feature): array
    {
        $featurePrimary = is_array($featureConfig) ? (string) ($featureConfig['primary_model'] ?? '') : '';
        $featureFallbacks = is_array($featureConfig) ? ($featureConfig['fallback_models'] ?? []) : [];
        $providerDefault = (string) ($provider['default_model'] ?? '');
        $providerFallbacks = $provider['fallback_models'] ?? [];

        $chain = array_merge(
            array_filter([$featurePrimary, $providerDefault]),
            is_array($featureFallbacks) ? $featureFallbacks : [],
            is_array($providerFallbacks) ? $providerFallbacks : [],
            GroqModelCatalog::featureFallbackChain($feature)
        );

        return array_values(array_unique(array_filter(array_map('strval', $chain))));
    }

    /**
     * @param array<string,mixed> $options
     * @return array<string,mixed>
     */
    private function request(string $apiKey, string $model, string $input, array $options): array
    {
        if (!function_exists('curl_init')) {
            throw new RuntimeException('cURL extension is required for Groq integration.');
        }

        $payload = [
            'model' => $model,
            'input' => $input,
        ];

        if (isset($options['temperature'])) {
            $payload['temperature'] = (float) $options['temperature'];
        }

        $headers = [];
        $ch = curl_init('https://api.groq.com/openai/v1/responses');
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $apiKey,
                'Content-Type: application/json',
            ],
            CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR),
            CURLOPT_HEADERFUNCTION => static function ($curl, string $header) use (&$headers): int {
                $length = strlen($header);
                $parts = explode(':', $header, 2);
                if (count($parts) === 2) {
                    $headers[strtolower(trim($parts[0]))] = trim($parts[1]);
                }

                return $length;
            },
        ]);

        $body = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if (!is_string($body)) {
            throw new RuntimeException($error !== '' ? $error : 'Groq request failed.');
        }

        $decoded = json_decode($body, true);
        if ($httpCode >= 400) {
            $message = is_array($decoded) ? (string) (($decoded['error']['message'] ?? $decoded['message'] ?? 'Groq error')) : 'Groq error';
            throw new RuntimeException(sprintf('HTTP %d: %s', $httpCode, $message));
        }

        return [
            'provider' => 'groq',
            'model' => $model,
            'output' => $decoded,
            'rate_limit_requests_remaining' => isset($headers['x-ratelimit-remaining-requests']) ? (int) $headers['x-ratelimit-remaining-requests'] : null,
            'rate_limit_tokens_remaining' => isset($headers['x-ratelimit-remaining-tokens']) ? (int) $headers['x-ratelimit-remaining-tokens'] : null,
        ];
    }

    private function shouldFallback(string $message): bool
    {
        return str_contains($message, 'HTTP 429')
            || str_contains($message, 'rate limit')
            || str_contains($message, 'capacity')
            || str_contains($message, 'unavailable');
    }
}
