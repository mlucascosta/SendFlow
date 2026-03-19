<?php

declare(strict_types=1);

namespace App\Services;

use PDO;

/**
 * Repositório de integrações opcionais salvas no banco.
 * Repository for optional integrations stored in the database.
 */
class IntegrationSettingsRepository
{
    public function __construct(
        private PDO $pdo,
        private string $appKey
    ) {
    }

    /**
     * @return array<string,mixed>|null
     */
    public function getAiProvider(string $provider): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM ai_provider_configs WHERE provider = :provider LIMIT 1');
        $stmt->execute(['provider' => $provider]);
        $config = $stmt->fetch();

        if (!is_array($config)) {
            return null;
        }

        return $this->decodeConfig($config);
    }

    /**
     * @return array<string,mixed>|null
     */
    public function getFeatureConfig(string $featureKey): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM ai_feature_configs WHERE feature_key = :feature_key LIMIT 1');
        $stmt->execute(['feature_key' => $featureKey]);
        $config = $stmt->fetch();

        if (!is_array($config)) {
            return null;
        }

        $config['fallback_models'] = $this->decodeJsonArray($config['fallback_models'] ?? null);
        $config['metadata'] = $this->decodeJsonObject($config['metadata'] ?? null);

        return $config;
    }

    /**
     * @return array<string,mixed>|null
     */
    public function getScheduler(string $provider): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM scheduler_integrations WHERE provider = :provider LIMIT 1');
        $stmt->execute(['provider' => $provider]);
        $config = $stmt->fetch();

        if (!is_array($config)) {
            return null;
        }

        $config['metadata'] = $this->decodeJsonObject($config['metadata'] ?? null);
        $config['api_key'] = $this->decryptSecret($config['encrypted_api_key'] ?? null);

        return $config;
    }

    public function saveAiApiKey(string $provider, string $apiKey): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE ai_provider_configs
             SET encrypted_api_key = :encrypted_api_key, is_enabled = 1, updated_at = CURRENT_TIMESTAMP
             WHERE provider = :provider'
        );
        $stmt->execute([
            'provider' => $provider,
            'encrypted_api_key' => Encryption::encrypt($apiKey, $this->appKey),
        ]);
    }

    public function saveSchedulerApiKey(string $provider, string $apiKey): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE scheduler_integrations
             SET encrypted_api_key = :encrypted_api_key, is_enabled = 1, updated_at = CURRENT_TIMESTAMP
             WHERE provider = :provider'
        );
        $stmt->execute([
            'provider' => $provider,
            'encrypted_api_key' => Encryption::encrypt($apiKey, $this->appKey),
        ]);
    }

    /**
     * @param array<string,mixed> $config
     * @return array<string,mixed>
     */
    private function decodeConfig(array $config): array
    {
        $config['fallback_models'] = $this->decodeJsonArray($config['fallback_models'] ?? null);
        $config['available_models'] = $this->decodeJsonArray($config['available_models'] ?? null);
        $config['metadata'] = $this->decodeJsonObject($config['metadata'] ?? null);
        $config['api_key'] = $this->decryptSecret($config['encrypted_api_key'] ?? null);

        return $config;
    }

    /**
     * @param mixed $value
     * @return array<int,string>
     */
    private function decodeJsonArray($value): array
    {
        if (!is_string($value) || $value === '') {
            return [];
        }

        $decoded = json_decode($value, true);
        if (!is_array($decoded)) {
            return [];
        }

        return array_values(array_map('strval', $decoded));
    }

    /**
     * @param mixed $value
     * @return array<string,mixed>
     */
    private function decodeJsonObject($value): array
    {
        if (!is_string($value) || $value === '') {
            return [];
        }

        $decoded = json_decode($value, true);

        return is_array($decoded) ? $decoded : [];
    }

    /**
     * @param mixed $value
     */
    private function decryptSecret($value): string
    {
        if (!is_string($value) || $value === '') {
            return '';
        }

        return Encryption::decrypt($value, $this->appKey);
    }
}
