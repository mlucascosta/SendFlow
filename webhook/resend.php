<?php

declare(strict_types=1);

use App\Services\Database;
use App\Services\InboundMessageParser;
use App\Services\InboundPolicy;
use App\Services\ResendWebhookVerifier;

require_once __DIR__ . '/../app/services/Database.php';
require_once __DIR__ . '/../app/services/InboundMessageParser.php';
require_once __DIR__ . '/../app/services/InboundPolicy.php';
require_once __DIR__ . '/../app/services/ResendWebhookVerifier.php';

/**
 * Endpoint seguro de webhook da Resend.
 * Secure Resend webhook endpoint.
 */

header('Content-Type: application/json; charset=utf-8');

$envFile = __DIR__ . '/../config/env.php';
$env = file_exists($envFile)
    ? require $envFile
    : require __DIR__ . '/../config/env.example.php';

$rawBody = file_get_contents('php://input');
if (!is_string($rawBody) || $rawBody === '') {
    respond(400, ['status' => 'error', 'message' => 'Empty request body']);
}

$payload = json_decode($rawBody, true);
if (!is_array($payload)) {
    respond(400, ['status' => 'error', 'message' => 'Invalid JSON payload']);
}

$webhookSecret = (string) (($env['resend']['webhook_secret'] ?? ''));
$verification = ResendWebhookVerifier::verify($rawBody, $_SERVER, $webhookSecret);

try {
    $pdo = Database::connection($env['database'] ?? []);
} catch (Throwable $exception) {
    respond(500, [
        'status' => 'error',
        'message' => 'Database connection failed',
        'reason' => $exception->getMessage(),
    ]);
}

$eventType = detectEventType($payload);
$resendMessageId = extractResendMessageId($payload);
$webhookId = logWebhook($pdo, $eventType, $resendMessageId, $payload, $verification['valid']);

if (!$verification['valid']) {
    markWebhookError($pdo, $webhookId, $verification['reason']);
    respond(401, [
        'status' => 'rejected',
        'message' => 'Invalid webhook signature',
        'reason' => $verification['reason'],
    ]);
}

if (InboundMessageParser::isInboundEvent($payload)) {
    handleInboundEvent($pdo, $webhookId, $payload);
}

handleTrackingEvent($pdo, $webhookId, $eventType, $resendMessageId);

respond(200, [
    'status' => 'ok',
    'message' => 'Webhook processed',
    'event_type' => $eventType,
]);

/**
 * @param array<string,mixed> $payload
 */
function detectEventType(array $payload): string
{
    $eventType = $payload['type'] ?? $payload['event'] ?? 'unknown';

    return is_string($eventType) && $eventType !== '' ? strtolower($eventType) : 'unknown';
}

/**
 * @param array<string,mixed> $payload
 */
function extractResendMessageId(array $payload): ?string
{
    $data = isset($payload['data']) && is_array($payload['data']) ? $payload['data'] : $payload;
    $resendMessageId = $data['email_id'] ?? $data['resend_message_id'] ?? $data['id'] ?? null;

    return is_string($resendMessageId) && $resendMessageId !== '' ? $resendMessageId : null;
}

/**
 * @param array<string,mixed> $payload
 */
function logWebhook(PDO $pdo, string $eventType, ?string $resendMessageId, array $payload, bool $signatureValid): int
{
    $stmt = $pdo->prepare(
        'INSERT INTO webhook_logs (event_type, resend_message_id, payload, processed, signature_valid)
         VALUES (:event_type, :resend_message_id, :payload, 0, :signature_valid)'
    );
    $stmt->execute([
        'event_type' => $eventType,
        'resend_message_id' => $resendMessageId,
        'payload' => json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR),
        'signature_valid' => $signatureValid ? 1 : 0,
    ]);

    return (int) $pdo->lastInsertId();
}

function markWebhookProcessed(PDO $pdo, int $webhookId): void
{
    $stmt = $pdo->prepare('UPDATE webhook_logs SET processed = 1, processing_error = NULL WHERE id = :id');
    $stmt->execute(['id' => $webhookId]);
}

function markWebhookError(PDO $pdo, int $webhookId, string $reason): void
{
    $stmt = $pdo->prepare('UPDATE webhook_logs SET processed = 1, processing_error = :reason WHERE id = :id');
    $stmt->execute([
        'id' => $webhookId,
        'reason' => $reason,
    ]);
}

/**
 * @param array<string,mixed> $payload
 */
function handleInboundEvent(PDO $pdo, int $webhookId, array $payload): void
{
    $message = InboundMessageParser::parse($payload);
    $recipient = (string) ($message['to_email'] ?? '');
    $mailbox = InboundPolicy::resolveMailbox($pdo, $recipient);

    if ($mailbox === null) {
        markWebhookError($pdo, $webhookId, 'unknown_mailbox');
        respond(202, [
            'status' => 'discarded',
            'message' => 'Inbound mailbox not allowed',
            'reason' => 'unknown_mailbox',
        ]);
    }

    $decision = InboundPolicy::evaluate($pdo, $mailbox, $message);
    if (!$decision['allowed']) {
        persistRejectedInbound($pdo, $mailbox, $message, $decision['reason']);
        markWebhookError($pdo, $webhookId, $decision['reason']);

        respond(202, [
            'status' => 'discarded',
            'message' => 'Inbound rejected by policy',
            'reason' => $decision['reason'],
        ]);
    }

    persistAcceptedInbound($pdo, $mailbox, $message);
    markWebhookProcessed($pdo, $webhookId);
    respond(200, [
        'status' => 'accepted',
        'message' => 'Inbound accepted',
        'reason' => $decision['reason'],
    ]);
}

function handleTrackingEvent(PDO $pdo, int $webhookId, string $eventType, ?string $resendMessageId): void
{
    if ($resendMessageId === null) {
        markWebhookProcessed($pdo, $webhookId);

        return;
    }

    if ($eventType === 'email.opened') {
        $stmt = $pdo->prepare('UPDATE emails SET opens = opens + 1 WHERE resend_message_id = :id');
        $stmt->execute(['id' => $resendMessageId]);
    } elseif ($eventType === 'email.clicked') {
        $stmt = $pdo->prepare('UPDATE emails SET clicks = clicks + 1 WHERE resend_message_id = :id');
        $stmt->execute(['id' => $resendMessageId]);
    } elseif ($eventType === 'email.sent') {
        $stmt = $pdo->prepare(
            'UPDATE emails SET status = :status, sent_at = COALESCE(sent_at, NOW()), updated_at = CURRENT_TIMESTAMP WHERE resend_message_id = :id'
        );
        $stmt->execute([
            'status' => 'sent',
            'id' => $resendMessageId,
        ]);
    } elseif (in_array($eventType, ['email.bounced', 'email.complained'], true)) {
        $stmt = $pdo->prepare('UPDATE emails SET status = :status, updated_at = CURRENT_TIMESTAMP WHERE resend_message_id = :id');
        $stmt->execute([
            'status' => 'failed',
            'id' => $resendMessageId,
        ]);
    }

    markWebhookProcessed($pdo, $webhookId);
}

/**
 * @param array<string,mixed> $mailbox
 * @param array<string,mixed> $message
 */
function persistAcceptedInbound(PDO $pdo, array $mailbox, array $message): void
{
    $stmt = $pdo->prepare(
        'INSERT INTO emails (
            user_id, from_email, to_email, envelope_from, envelope_to, subject,
            body_html, body_text, raw_headers, status, direction, message_id,
            in_reply_to, references_header, rejection_reason
        ) VALUES (
            :user_id, :from_email, :to_email, :envelope_from, :envelope_to, :subject,
            :body_html, :body_text, :raw_headers, :status, :direction, :message_id,
            :in_reply_to, :references_header, NULL
        )'
    );
    $stmt->execute([
        'user_id' => $mailbox['user_id'] ?? null,
        'from_email' => $message['from_email'],
        'to_email' => $message['to_email'],
        'envelope_from' => $message['envelope_from'] ?: null,
        'envelope_to' => $message['envelope_to'] ?: null,
        'subject' => (string) ($message['subject'] ?: '(sem assunto)'),
        'body_html' => $message['body_html'],
        'body_text' => $message['body_text'],
        'raw_headers' => json_encode($message['headers'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR),
        'status' => 'received',
        'direction' => 'inbound',
        'message_id' => $message['message_id'] ?: null,
        'in_reply_to' => $message['in_reply_to'] ?: null,
        'references_header' => $message['references_header'] ?: null,
    ]);
}

/**
 * @param array<string,mixed> $mailbox
 * @param array<string,mixed> $message
 */
function persistRejectedInbound(PDO $pdo, array $mailbox, array $message, string $reason): void
{
    $stmt = $pdo->prepare(
        'INSERT INTO emails (
            user_id, from_email, to_email, envelope_from, envelope_to, subject,
            body_html, body_text, raw_headers, status, direction, message_id,
            in_reply_to, references_header, rejection_reason
        ) VALUES (
            :user_id, :from_email, :to_email, :envelope_from, :envelope_to, :subject,
            :body_html, :body_text, :raw_headers, :status, :direction, :message_id,
            :in_reply_to, :references_header, :rejection_reason
        )'
    );
    $stmt->execute([
        'user_id' => $mailbox['user_id'] ?? null,
        'from_email' => $message['from_email'],
        'to_email' => $message['to_email'],
        'envelope_from' => $message['envelope_from'] ?: null,
        'envelope_to' => $message['envelope_to'] ?: null,
        'subject' => (string) ($message['subject'] ?: '(sem assunto)'),
        'body_html' => $message['body_html'],
        'body_text' => $message['body_text'],
        'raw_headers' => json_encode($message['headers'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR),
        'status' => 'rejected',
        'direction' => 'inbound',
        'message_id' => $message['message_id'] ?: null,
        'in_reply_to' => $message['in_reply_to'] ?: null,
        'references_header' => $message['references_header'] ?: null,
        'rejection_reason' => $reason,
    ]);
}

/**
 * @param array<string,mixed> $payload
 */
function respond(int $statusCode, array $payload): void
{
    http_response_code($statusCode);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}
