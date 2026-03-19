<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Normaliza payloads inbound recebidos da Resend.
 * Normalizes inbound payloads received from Resend.
 */
class InboundMessageParser
{
    /**
     * @param array<string,mixed> $payload
     * @return array<string,mixed>
     */
    public static function parse(array $payload): array
    {
        $data = isset($payload['data']) && is_array($payload['data']) ? $payload['data'] : $payload;
        $headers = isset($data['headers']) && is_array($data['headers']) ? $data['headers'] : [];

        $from = self::extractEmail($data['from'] ?? $data['mail_from'] ?? '');
        $to = self::extractEmail($data['to'] ?? $data['recipient'] ?? $data['envelope']['to'] ?? '');
        $subject = is_string($data['subject'] ?? null) ? trim((string) $data['subject']) : '';
        $messageId = self::normalizeHeaderValue($data['message_id'] ?? $headers['message-id'] ?? $headers['Message-Id'] ?? '');
        $inReplyTo = self::normalizeHeaderValue($data['in_reply_to'] ?? $headers['in-reply-to'] ?? $headers['In-Reply-To'] ?? '');

        $references = $data['references'] ?? $headers['references'] ?? $headers['References'] ?? '';
        if (is_array($references)) {
            $references = implode(' ', array_map('strval', $references));
        }

        return [
            'event_type' => self::detectEventType($payload),
            'from_email' => strtolower($from),
            'to_email' => strtolower($to),
            'subject' => $subject,
            'body_html' => is_string($data['html'] ?? null) ? $data['html'] : null,
            'body_text' => is_string($data['text'] ?? null) ? $data['text'] : null,
            'message_id' => $messageId,
            'in_reply_to' => $inReplyTo,
            'references_header' => trim((string) $references),
            'headers' => $headers,
            'envelope_from' => self::extractEmail($data['mail_from'] ?? $data['envelope']['from'] ?? $data['return_path'] ?? ''),
            'envelope_to' => strtolower(self::extractEmail($data['envelope']['to'] ?? $data['recipient'] ?? $data['to'] ?? '')),
            'raw' => $payload,
        ];
    }

    /**
     * @param array<string,mixed> $payload
     */
    public static function isInboundEvent(array $payload): bool
    {
        $eventType = self::detectEventType($payload);
        if (in_array($eventType, ['email.received', 'email.inbound', 'inbound.received'], true)) {
            return true;
        }

        $data = isset($payload['data']) && is_array($payload['data']) ? $payload['data'] : $payload;

        return isset($data['from'], $data['to']) || isset($data['mail_from'], $data['recipient']);
    }

    /**
     * @param array<string,mixed> $payload
     */
    private static function detectEventType(array $payload): string
    {
        $eventType = $payload['type'] ?? $payload['event'] ?? '';

        return is_string($eventType) && $eventType !== '' ? strtolower($eventType) : 'unknown';
    }

    /**
     * @param mixed $value
     */
    private static function extractEmail($value): string
    {
        if (is_array($value)) {
            $value = reset($value) ?: '';
        }

        $value = trim((string) $value);
        if ($value === '') {
            return '';
        }

        if (preg_match('/<([^>]+)>/', $value, $matches) === 1) {
            return trim($matches[1]);
        }

        return trim($value, " \t\n\r\0\x0B<>");
    }

    /**
     * @param mixed $value
     */
    private static function normalizeHeaderValue($value): string
    {
        if (is_array($value)) {
            $value = reset($value) ?: '';
        }

        return trim((string) $value);
    }
}
