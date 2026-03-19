<?php

declare(strict_types=1);

namespace App\Services;

use RuntimeException;

/**
 * Cliente mínimo para cron-job.org com ativação opcional.
 * Minimal cron-job.org client with optional activation.
 */
class CronJobOrgClient
{
    public function __construct(
        private IntegrationSettingsRepository $settingsRepository
    ) {
    }

    /**
     * @return array<string,mixed>
     */
    public function listJobs(): array
    {
        return $this->request('GET', '/jobs');
    }

    /**
     * @param array<string,mixed> $job
     * @return array<string,mixed>
     */
    public function createJob(array $job): array
    {
        return $this->request('PUT', '/jobs', ['job' => $job]);
    }

    /**
     * @param array<string,mixed> $job
     * @return array<string,mixed>
     */
    public function updateJob(int $jobId, array $job): array
    {
        return $this->request('PATCH', '/jobs/' . $jobId, ['job' => $job]);
    }

    /**
     * @return array<string,mixed>
     */
    public function deleteJob(int $jobId): array
    {
        return $this->request('DELETE', '/jobs/' . $jobId);
    }

    /**
     * @param array<string,mixed>|null $payload
     * @return array<string,mixed>
     */
    private function request(string $method, string $path, ?array $payload = null): array
    {
        $integration = $this->settingsRepository->getScheduler('cron-job.org');
        if ($integration === null || (int) ($integration['is_enabled'] ?? 0) !== 1 || ($integration['api_key'] ?? '') === '') {
            return [
                'status' => 'disabled',
                'provider' => 'cron-job.org',
                'message' => 'cron-job.org is disabled until an API key is saved in the database.',
            ];
        }

        if (!function_exists('curl_init')) {
            throw new RuntimeException('cURL extension is required for cron-job.org integration.');
        }

        $url = rtrim((string) ($integration['base_url'] ?? 'https://api.cron-job.org'), '/') . $path;
        $headers = [];
        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $integration['api_key'],
                'Content-Type: application/json',
            ],
            CURLOPT_HEADERFUNCTION => static function ($curl, string $header) use (&$headers): int {
                $length = strlen($header);
                $parts = explode(':', $header, 2);
                if (count($parts) === 2) {
                    $headers[strtolower(trim($parts[0]))] = trim($parts[1]);
                }

                return $length;
            },
        ]);

        if ($payload !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR));
        }

        $body = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if (!is_string($body)) {
            throw new RuntimeException($error !== '' ? $error : 'cron-job.org request failed.');
        }

        $decoded = json_decode($body, true);
        if ($httpCode >= 400) {
            $message = is_array($decoded) ? (string) ($decoded['message'] ?? 'cron-job.org error') : 'cron-job.org error';
            throw new RuntimeException(sprintf('HTTP %d: %s', $httpCode, $message));
        }

        return [
            'status' => 'success',
            'provider' => 'cron-job.org',
            'headers' => $headers,
            'payload' => $decoded,
        ];
    }
}
