-- Compatibility: MariaDB/MySQL
-- Migration 012: Optional Groq AI and cron-job.org scheduler integration

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS ai_provider_configs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    provider VARCHAR(50) NOT NULL,
    encrypted_api_key LONGTEXT NULL,
    is_enabled TINYINT(1) NOT NULL DEFAULT 0,
    default_model VARCHAR(150) NULL,
    fallback_models JSON NULL,
    available_models JSON NULL,
    metadata JSON NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_ai_provider_configs_provider (provider)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS ai_feature_configs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    feature_key VARCHAR(100) NOT NULL,
    provider VARCHAR(50) NOT NULL,
    is_enabled TINYINT(1) NOT NULL DEFAULT 0,
    primary_model VARCHAR(150) NULL,
    fallback_models JSON NULL,
    prompt_template LONGTEXT NULL,
    metadata JSON NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_ai_feature_configs_feature_key (feature_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS ai_usage_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    provider VARCHAR(50) NOT NULL,
    feature_key VARCHAR(100) NOT NULL,
    model VARCHAR(150) NOT NULL,
    request_id VARCHAR(255) NULL,
    status ENUM('success', 'fallback', 'rate_limited', 'disabled', 'failed') NOT NULL DEFAULT 'success',
    prompt_tokens INT UNSIGNED NULL,
    completion_tokens INT UNSIGNED NULL,
    total_tokens INT UNSIGNED NULL,
    latency_ms INT UNSIGNED NULL,
    rate_limit_requests_remaining INT NULL,
    rate_limit_tokens_remaining INT NULL,
    error_code VARCHAR(100) NULL,
    error_message TEXT NULL,
    payload_preview TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_ai_usage_logs_feature_time (feature_key, created_at),
    KEY idx_ai_usage_logs_provider_time (provider, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS ai_email_insights (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email_id INT UNSIGNED NOT NULL,
    summary_text LONGTEXT NULL,
    spam_score DECIMAL(5,2) NULL,
    spam_verdict ENUM('unknown', 'safe', 'suspect', 'spam') NOT NULL DEFAULT 'unknown',
    smart_labels JSON NULL,
    extracted_tasks JSON NULL,
    suggested_send_at DATETIME NULL,
    provider VARCHAR(50) NOT NULL DEFAULT 'groq',
    model VARCHAR(150) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_ai_email_insights_email_id (email_id),
    CONSTRAINT fk_ai_email_insights_email FOREIGN KEY (email_id) REFERENCES emails(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS scheduler_integrations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    provider VARCHAR(50) NOT NULL,
    encrypted_api_key LONGTEXT NULL,
    is_enabled TINYINT(1) NOT NULL DEFAULT 0,
    base_url VARCHAR(255) NOT NULL DEFAULT 'https://api.cron-job.org',
    timezone VARCHAR(100) NOT NULL DEFAULT 'UTC',
    metadata JSON NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_scheduler_integrations_provider (provider)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS managed_cron_jobs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    integration_id INT UNSIGNED NOT NULL,
    job_key VARCHAR(100) NOT NULL,
    remote_job_id INT NULL,
    title VARCHAR(255) NOT NULL,
    target_url VARCHAR(500) NOT NULL,
    request_method ENUM('GET', 'POST', 'PATCH', 'PUT', 'DELETE') NOT NULL DEFAULT 'GET',
    schedule_json JSON NOT NULL,
    is_enabled TINYINT(1) NOT NULL DEFAULT 1,
    last_synced_at DATETIME NULL,
    last_error TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_managed_cron_jobs_job_key (job_key),
    KEY idx_managed_cron_jobs_integration (integration_id),
    CONSTRAINT fk_managed_cron_jobs_integration FOREIGN KEY (integration_id) REFERENCES scheduler_integrations(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO ai_provider_configs (provider, is_enabled, default_model, fallback_models, available_models, metadata)
SELECT
    'groq',
    0,
    'openai/gpt-oss-20b',
    JSON_ARRAY('groq/compound-mini', 'llama-3.1-8b-instant'),
    JSON_ARRAY(
        'groq/compound',
        'groq/compound-mini',
        'openai/gpt-oss-120b',
        'openai/gpt-oss-20b',
        'openai/gpt-oss-safeguard-20b',
        'llama-3.1-8b-instant',
        'llama-3.3-70b-versatile',
        'meta-llama/llama-4-maverick-17b-128e-instruct',
        'meta-llama/llama-4-scout-17b-16e-instruct',
        'meta-llama/llama-guard-4-12b',
        'meta-llama/llama-prompt-guard-2-22m',
        'meta-llama/llama-prompt-guard-2-86m',
        'moonshotai/kimi-k2-instruct',
        'moonshotai/kimi-k2-instruct-0905',
        'qwen/qwen3-32b',
        'allam-2-7b',
        'whisper-large-v3',
        'whisper-large-v3-turbo'
    ),
    JSON_OBJECT('optional', true, 'activation', 'requires_api_key_in_db')
WHERE NOT EXISTS (
    SELECT 1 FROM ai_provider_configs WHERE provider = 'groq'
);

INSERT INTO ai_feature_configs (feature_key, provider, is_enabled, primary_model, fallback_models, prompt_template, metadata)
SELECT 'email_summary', 'groq', 0, 'groq/compound-mini', JSON_ARRAY('openai/gpt-oss-20b', 'llama-3.1-8b-instant'),
       'Summarize the email before it is opened. Highlight urgency, requested actions, deadlines and risk signals.',
       JSON_OBJECT('default', true)
WHERE NOT EXISTS (
    SELECT 1 FROM ai_feature_configs WHERE feature_key = 'email_summary'
);

INSERT INTO ai_feature_configs (feature_key, provider, is_enabled, primary_model, fallback_models, prompt_template, metadata)
SELECT 'spam_guard', 'groq', 0, 'meta-llama/llama-guard-4-12b', JSON_ARRAY('openai/gpt-oss-safeguard-20b', 'meta-llama/llama-prompt-guard-2-86m'),
       'Classify the email for spam, phishing and social engineering risk. Return JSON only.',
       JSON_OBJECT('default', true)
WHERE NOT EXISTS (
    SELECT 1 FROM ai_feature_configs WHERE feature_key = 'spam_guard'
);

INSERT INTO ai_feature_configs (feature_key, provider, is_enabled, primary_model, fallback_models, prompt_template, metadata)
SELECT 'task_extraction', 'groq', 0, 'groq/compound', JSON_ARRAY('qwen/qwen3-32b', 'openai/gpt-oss-20b'),
       'Extract tasks, dates, responsible people and suggested follow-up schedule from the email. Return JSON only.',
       JSON_OBJECT('default', true)
WHERE NOT EXISTS (
    SELECT 1 FROM ai_feature_configs WHERE feature_key = 'task_extraction'
);

INSERT INTO ai_feature_configs (feature_key, provider, is_enabled, primary_model, fallback_models, prompt_template, metadata)
SELECT 'smart_labels', 'groq', 0, 'meta-llama/llama-4-scout-17b-16e-instruct', JSON_ARRAY('groq/compound-mini', 'llama-3.1-8b-instant'),
       'Generate smart labels for the email inbox such as billing, legal, sales, support or urgent. Return JSON array only.',
       JSON_OBJECT('default', true)
WHERE NOT EXISTS (
    SELECT 1 FROM ai_feature_configs WHERE feature_key = 'smart_labels'
);

INSERT INTO scheduler_integrations (provider, is_enabled, base_url, timezone, metadata)
SELECT 'cron-job.org', 0, 'https://api.cron-job.org', 'UTC', JSON_OBJECT('optional', true, 'activation', 'requires_api_key_in_db')
WHERE NOT EXISTS (
    SELECT 1 FROM scheduler_integrations WHERE provider = 'cron-job.org'
);
