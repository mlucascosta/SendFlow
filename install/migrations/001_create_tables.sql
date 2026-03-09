-- Compatibilidade: MariaDB/MySQL | Compatibility: MariaDB/MySQL
-- Migration 001: Criar tabelas principais | Create core tables
-- Compatível com MySQL 5.7+ e MariaDB 10.3+

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Tabela `users` | Table `users`
CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    resend_api_key VARBINARY(255) NULL,
    resend_domain VARCHAR(255) NULL,
    resend_api_key_last_digits CHAR(4) NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    must_change_password TINYINT(1) NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    email_verified TINYINT(1) NOT NULL DEFAULT 0,
    last_login_at DATETIME NULL,
    last_login_ip VARCHAR(45) NULL,
    last_login_user_agent TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME NULL,
    UNIQUE KEY uq_users_email (email),
    KEY idx_users_email_active (email, is_active),
    KEY idx_users_deleted (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela `sessions` | Table `sessions`
CREATE TABLE IF NOT EXISTS sessions (
    id VARCHAR(128) PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    user_agent TEXT NOT NULL,
    payload TEXT NULL,
    last_activity INT UNSIGNED NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    expires_at DATETIME GENERATED ALWAYS AS (FROM_UNIXTIME(last_activity + 7200)) VIRTUAL,
    KEY idx_sessions_last_activity (last_activity),
    KEY idx_sessions_user_active (user_id, is_active),
    CONSTRAINT fk_sessions_user FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela `emails` | Table `emails`
CREATE TABLE IF NOT EXISTS emails (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    session_id VARCHAR(128) NULL,
    resend_message_id VARCHAR(255) NULL,
    from_email VARCHAR(255) NOT NULL,
    to_email VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    body_html LONGTEXT NULL,
    body_text LONGTEXT NULL,
    status ENUM('draft', 'queued', 'sent', 'failed', 'received') NOT NULL DEFAULT 'draft',
    direction ENUM('outbound', 'inbound') NOT NULL DEFAULT 'outbound',
    opens INT UNSIGNED NOT NULL DEFAULT 0,
    clicks INT UNSIGNED NOT NULL DEFAULT 0,
    sent_at DATETIME NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_emails_user_status (user_id, status),
    KEY idx_emails_resend_id (resend_message_id),
    KEY idx_emails_created (created_at),
    CONSTRAINT fk_emails_user FOREIGN KEY (user_id) REFERENCES users(id),
    CONSTRAINT fk_emails_session FOREIGN KEY (session_id) REFERENCES sessions(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela `email_attachments` | Table `email_attachments`
CREATE TABLE IF NOT EXISTS email_attachments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email_id INT UNSIGNED NOT NULL,
    filename VARCHAR(255) NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    file_size INT UNSIGNED NOT NULL,
    mime_type VARCHAR(100) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_attachments_email FOREIGN KEY (email_id) REFERENCES emails(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela `audit_logs` | Table `audit_logs`
CREATE TABLE IF NOT EXISTS audit_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NULL,
    session_id VARCHAR(128) NULL,
    action VARCHAR(100) NOT NULL,
    entity_type VARCHAR(50) NOT NULL,
    entity_id INT UNSIGNED NULL,
    old_values JSON NULL,
    new_values JSON NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_audit_user_time (user_id, created_at),
    KEY idx_audit_action (action)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela `failed_logins` | Table `failed_logins`
CREATE TABLE IF NOT EXISTS failed_logins (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    user_agent TEXT NULL,
    attempted_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_failed_ip_time (ip_address, attempted_at),
    KEY idx_failed_email_time (email, attempted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela `session_logs` | Table `session_logs`
CREATE TABLE IF NOT EXISTS session_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NULL,
    session_id VARCHAR(128) NULL,
    action ENUM('login', 'logout', 'expired', 'revoked', 'failed_attempt') NOT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    details JSON NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_session_logs_user_time (user_id, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela `webhook_logs` | Table `webhook_logs`
CREATE TABLE IF NOT EXISTS webhook_logs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    event_type VARCHAR(50) NOT NULL,
    resend_message_id VARCHAR(255) NULL,
    payload JSON NOT NULL,
    processed TINYINT(1) NOT NULL DEFAULT 0,
    signature_valid TINYINT(1) NOT NULL DEFAULT 0,
    processing_error TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_webhook_message_id (resend_message_id),
    KEY idx_webhook_processed (processed)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela `user_settings` | Table `user_settings`
CREATE TABLE IF NOT EXISTS user_settings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    setting_key VARCHAR(100) NOT NULL,
    setting_value TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_user_settings (user_id, setting_key),
    CONSTRAINT fk_user_settings_user FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela `system_settings` | Table `system_settings`
CREATE TABLE IF NOT EXISTS system_settings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL,
    setting_value TEXT NULL,
    setting_type VARCHAR(20) NOT NULL DEFAULT 'string',
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_system_setting_key (setting_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
