# SendFlow

SendFlow is an open-source self-hosted webmail platform built with plain PHP, focused on secure inbound handling, modern UX, and compatibility with shared hosting or VPS deployments.

## Current scope

- PHP front controller with lightweight page routing.
- SQL-first installation flow for MySQL/MariaDB.
- Secure inbound webhook foundation for Resend.
- Optional Groq-powered AI features stored and configured from the database.
- Optional cron-job.org-based managed scheduler for every recurring background job.

## Core routes

- `/login`
- `/dashboard`
- `/dashboard/ai`
- `/emails/inbox`
- `/emails/sent`
- `/emails/drafts`
- `/emails/compose`
- `/settings`
- `/settings/resend`
- `/settings/ai`
- `/settings/sessions`
- `/settings/audit`
- `/install/index.php`

## Local development

```bash
php -S 127.0.0.1:8000 -t public
```

## SQL compatibility audit

Run the SQL audit before shipping changes to the installer or schema:

```bash
php scripts/audit_mysql_compat.php
```

The audit checks the migration set for PostgreSQL/PLpgSQL-specific constructs and keeps the project aligned with MySQL/MariaDB.

## Guided onboarding wizard

The installer now provides a personalized six-step onboarding flow:

1. welcome and security overview,
2. MySQL/MariaDB credentials,
3. first administrator creation,
4. product tour,
5. Resend domain + API key storage,
6. final handoff to login.

During step 2, the installer validates the MySQL/MariaDB connection, runs all migrations automatically, creates `schema_migrations`, and writes `config/env.php`.
During step 3, the first administrator email/password is stored for SendFlow login.
During step 5, the Resend domain and API key are saved into the user's own database, with the API key encrypted before storage.

## Secure inbound email policy

SendFlow now treats inbound email as default-deny.

- Webhook requests must pass signature verification before they are processed.
- Only explicitly configured records in `inbound_mailboxes` can receive inbound messages.
- `mailbox_type = 'noreply'` always rejects replies.
- `sender_policy = 'allow_senders'` uses `inbound_allowed_senders` for explicit allowlists.
- `sender_policy = 'allow_domains'` uses mailbox-level domain allowlists.
- `sender_policy = 'reply_only'` accepts inbound mail only when it matches a known outbound thread.

## Optional Groq AI integration

Groq is disabled by default.

- The integration only becomes active after the user saves a Groq API key in the database.
- API keys are intended to be stored encrypted via the application encryption service.
- Default AI features:
  - pre-open email summaries,
  - spam and phishing validation,
  - smart labels,
  - task extraction from emails,
  - send-time recommendations for scheduled messaging.
- Fallback chains are configured per feature so the system can switch models when the preferred model is rate-limited or unavailable.

### Default Groq feature routing

- `email_summary`: `groq/compound-mini` → `openai/gpt-oss-20b` → `llama-3.1-8b-instant`
- `spam_guard`: `meta-llama/llama-guard-4-12b` → `openai/gpt-oss-safeguard-20b` → `meta-llama/llama-prompt-guard-2-86m`
- `task_extraction`: `groq/compound` → `qwen/qwen3-32b` → `openai/gpt-oss-20b`
- `smart_labels`: `meta-llama/llama-4-scout-17b-16e-instruct` → `groq/compound-mini` → `llama-3.1-8b-instant`

### Groq activation flow

1. Apply migrations up to `012_create_ai_and_scheduler_tables.sql`.
2. Save the Groq API key into `ai_provider_configs.encrypted_api_key`.
3. Enable the provider and the desired feature flags in `ai_provider_configs` and `ai_feature_configs`.
4. Review the AI dashboard for usage, fallback activity, and feature metrics.

### Reference docs

- Groq overview: <https://console.groq.com/docs/overview>
- Groq models: <https://console.groq.com/docs/models>
- Groq rate limits: <https://console.groq.com/docs/rate-limits>

## Optional cron-job.org scheduler integration

cron-job.org is also disabled by default.

- The project standard is to manage recurring jobs through cron-job.org instead of relying on server cron access.
- The integration only becomes active after the user saves a cron-job.org API key in the database.
- Managed jobs are persisted in `managed_cron_jobs`.
- Scheduler credentials are stored in `scheduler_integrations`.
- This keeps the application non-blocking: features can stay inactive until credentials are present.

### Suggested managed jobs

- webhook reconciliation
- inbound policy cleanup
- AI summary backfill
- scheduled send dispatch

### Reference docs

- cron-job.org REST API: <https://docs.cron-job.org/rest-api.html>

## Database additions

### Inbound security

- `install/migrations/011_create_inbound_rules.sql`
  - `inbound_mailboxes`
  - `inbound_allowed_senders`
  - `emails` metadata for thread tracing and rejected inbound logging

### AI and scheduler

- `install/migrations/012_create_ai_and_scheduler_tables.sql`
  - `ai_provider_configs`
  - `ai_feature_configs`
  - `ai_usage_logs`
  - `ai_email_insights`
  - `scheduler_integrations`
  - `managed_cron_jobs`

## Installation security notes

- The installer only supports MySQL/MariaDB.
- Installation forms are CSRF-protected.
- Database hosts, domains, emails and passwords are validated before persistence.
- Admin passwords are hashed with PHP `password_hash()`.
- Resend and optional integration API keys are stored encrypted in the database.

## Open source

This project is distributed under the MIT License. See `LICENSE`.

## Contributing

See `CONTRIBUTING.md` for development workflow, pull request expectations, and integration guidelines.
