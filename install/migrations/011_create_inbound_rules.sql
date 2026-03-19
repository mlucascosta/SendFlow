-- Compatibilidade: MariaDB/MySQL | Compatibility: MariaDB/MySQL
-- Migration 011: Regras de recebimento inbound | Inbound receiving rules

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS inbound_mailboxes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    email VARCHAR(255) NOT NULL,
    mailbox_type ENUM('inbox', 'reply_only', 'noreply', 'system') NOT NULL DEFAULT 'inbox',
    accept_inbound TINYINT(1) NOT NULL DEFAULT 0,
    sender_policy ENUM('deny', 'allow_all', 'allow_domains', 'allow_senders', 'reply_only') NOT NULL DEFAULT 'deny',
    allowed_domains JSON NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_inbound_mailboxes_email (email),
    KEY idx_inbound_mailboxes_user_active (user_id, is_active),
    CONSTRAINT fk_inbound_mailboxes_user FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS inbound_allowed_senders (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    mailbox_id INT UNSIGNED NOT NULL,
    sender_email VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_allowed_sender_mailbox_email (mailbox_id, sender_email),
    KEY idx_allowed_sender_email (sender_email),
    CONSTRAINT fk_allowed_senders_mailbox FOREIGN KEY (mailbox_id) REFERENCES inbound_mailboxes(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE emails
    MODIFY COLUMN status ENUM('draft', 'queued', 'sent', 'failed', 'received', 'rejected') NOT NULL DEFAULT 'draft';

ALTER TABLE emails
    ADD COLUMN message_id VARCHAR(255) NULL AFTER resend_message_id,
    ADD COLUMN in_reply_to VARCHAR(255) NULL AFTER message_id,
    ADD COLUMN references_header LONGTEXT NULL AFTER in_reply_to,
    ADD COLUMN envelope_from VARCHAR(255) NULL AFTER to_email,
    ADD COLUMN envelope_to VARCHAR(255) NULL AFTER envelope_from,
    ADD COLUMN raw_headers JSON NULL AFTER body_text,
    ADD COLUMN rejection_reason VARCHAR(100) NULL AFTER direction;
