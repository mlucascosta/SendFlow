<?php

declare(strict_types=1);

namespace App\Services;

use PDO;

/**
 * Avalia se uma mensagem inbound pode ser aceita.
 * Evaluates whether an inbound message can be accepted.
 */
class InboundPolicy
{
    /**
     * @return array<string,mixed>|null
     */
    public static function resolveMailbox(PDO $pdo, string $recipient): ?array
    {
        $sql = 'SELECT * FROM inbound_mailboxes WHERE LOWER(email) = :email AND is_active = 1 LIMIT 1';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => strtolower(trim($recipient))]);
        $mailbox = $stmt->fetch();

        return is_array($mailbox) ? $mailbox : null;
    }

    /**
     * @param array<string,mixed> $mailbox
     * @param array<string,mixed> $message
     * @return array{allowed:bool,reason:string}
     */
    public static function evaluate(PDO $pdo, array $mailbox, array $message): array
    {
        $recipient = strtolower(trim((string) ($message['to_email'] ?? '')));
        $sender = strtolower(trim((string) ($message['from_email'] ?? '')));
        $mailboxType = (string) ($mailbox['mailbox_type'] ?? 'inbox');
        $senderPolicy = (string) ($mailbox['sender_policy'] ?? 'deny');
        $acceptInbound = (int) ($mailbox['accept_inbound'] ?? 0) === 1;

        if ($recipient === '' || $sender === '') {
            return ['allowed' => false, 'reason' => 'missing_sender_or_recipient'];
        }

        if (!$acceptInbound) {
            return ['allowed' => false, 'reason' => 'mailbox_disabled'];
        }

        if ($mailboxType === 'noreply' || self::isNoReplyAddress($recipient)) {
            return ['allowed' => false, 'reason' => 'noreply_address'];
        }

        if ($senderPolicy === 'deny') {
            return ['allowed' => false, 'reason' => 'sender_policy_denied'];
        }

        if ($mailboxType === 'reply_only' || $senderPolicy === 'reply_only') {
            return self::evaluateReplyOnly($pdo, $mailbox, $message);
        }

        if ($senderPolicy === 'allow_all') {
            return ['allowed' => true, 'reason' => 'accepted_allow_all'];
        }

        if ($senderPolicy === 'allow_domains') {
            return self::evaluateAllowedDomains($mailbox, $sender);
        }

        if ($senderPolicy === 'allow_senders') {
            return self::evaluateAllowedSenders($pdo, (int) $mailbox['id'], $sender);
        }

        return ['allowed' => false, 'reason' => 'unsupported_sender_policy'];
    }

    private static function isNoReplyAddress(string $recipient): bool
    {
        $localPart = explode('@', $recipient)[0] ?? '';
        $normalized = str_replace(['.', '_'], '', strtolower($localPart));

        return in_array($normalized, ['noreply', 'naoresponda', 'donotreply'], true)
            || str_starts_with($normalized, 'noreply')
            || str_starts_with($normalized, 'donotreply');
    }

    /**
     * @param array<string,mixed> $mailbox
     * @param array<string,mixed> $message
     * @return array{allowed:bool,reason:string}
     */
    private static function evaluateReplyOnly(PDO $pdo, array $mailbox, array $message): array
    {
        $inReplyTo = trim((string) ($message['in_reply_to'] ?? ''));
        $references = trim((string) ($message['references_header'] ?? ''));
        $sender = strtolower(trim((string) ($message['from_email'] ?? '')));
        $recipient = strtolower(trim((string) ($message['to_email'] ?? '')));

        if ($inReplyTo === '' && $references === '') {
            return ['allowed' => false, 'reason' => 'reply_reference_required'];
        }

        $tokens = array_values(array_filter(array_map('trim', preg_split('/\s+/', $references) ?: [])));
        if ($inReplyTo !== '') {
            array_unshift($tokens, $inReplyTo);
        }
        $tokens = array_values(array_unique($tokens));

        $sql = 'SELECT id, to_email FROM emails
                WHERE direction = :direction
                  AND (
                      message_id = :message_id
                      OR resend_message_id = :message_id
                  )
                LIMIT 1';
        $stmt = $pdo->prepare($sql);

        foreach ($tokens as $token) {
            $stmt->execute([
                'direction' => 'outbound',
                'message_id' => $token,
            ]);
            $email = $stmt->fetch();
            if (is_array($email)) {
                if (strtolower((string) $email['to_email']) !== $sender) {
                    return ['allowed' => false, 'reason' => 'reply_sender_mismatch'];
                }

                if (self::resolveMailbox($pdo, $recipient) === null) {
                    return ['allowed' => false, 'reason' => 'unknown_mailbox'];
                }

                return ['allowed' => true, 'reason' => 'accepted_reply_only'];
            }
        }

        return ['allowed' => false, 'reason' => 'unknown_reply_thread'];
    }

    /**
     * @param array<string,mixed> $mailbox
     * @return array{allowed:bool,reason:string}
     */
    private static function evaluateAllowedDomains(array $mailbox, string $sender): array
    {
        $domains = json_decode((string) ($mailbox['allowed_domains'] ?? '[]'), true);
        if (!is_array($domains) || $domains === []) {
            return ['allowed' => false, 'reason' => 'allowed_domains_not_configured'];
        }

        $senderDomain = strtolower(substr(strrchr($sender, '@') ?: '', 1));
        if ($senderDomain === '') {
            return ['allowed' => false, 'reason' => 'invalid_sender_domain'];
        }

        $normalizedDomains = array_map(
            static fn ($domain): string => strtolower(trim((string) $domain)),
            $domains
        );

        if (in_array($senderDomain, $normalizedDomains, true)) {
            return ['allowed' => true, 'reason' => 'accepted_allowed_domain'];
        }

        return ['allowed' => false, 'reason' => 'sender_domain_not_allowed'];
    }

    /**
     * @return array{allowed:bool,reason:string}
     */
    private static function evaluateAllowedSenders(PDO $pdo, int $mailboxId, string $sender): array
    {
        $stmt = $pdo->prepare(
            'SELECT id FROM inbound_allowed_senders WHERE mailbox_id = :mailbox_id AND LOWER(sender_email) = :sender LIMIT 1'
        );
        $stmt->execute([
            'mailbox_id' => $mailboxId,
            'sender' => $sender,
        ]);

        if ($stmt->fetch() !== false) {
            return ['allowed' => true, 'reason' => 'accepted_allowed_sender'];
        }

        return ['allowed' => false, 'reason' => 'sender_not_allowed'];
    }
}
