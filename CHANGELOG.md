# Changelog do SendFlow

> Registro histórico completo por **data real** (sem `Unreleased`).
> Formato: **Summary + Details** com **accordion** e blocos de **spoiler** em todas as entradas.

---

## 2026-03-09

### Summary
- Inicialização do repositório com README base.
- Scaffold completo do SendFlow (frontend, instalador, migrations SQL, services PHP, webhook, cron, estrutura de storage).

### Details

<details>
  <summary><strong>📦 Commit 9fa7966 — Initial commit</strong></summary>

- **Data:** `2026-03-09`
- **Mensagem:** `Initial commit`

#### Mudanças
- >!Criação do arquivo inicial de documentação do projeto.!<

#### Arquivos
- >!`README.md`!<

</details>

<details>
  <summary><strong>🏗️ Commit 32917bd — Initial scaffold: UI, installer, DB migrations, services, and webhook endpoint</strong></summary>

- **Data:** `2026-03-09`
- **Mensagem:** `Initial scaffold: UI, installer, DB migrations, services, and webhook endpoint`

#### Mudanças (alto nível)
- >!Estruturação do frontend com layout, páginas, partials, assets e componentes visuais.!<
- >!Implementação de base de instalador web com passos e tela pública de instalação.!<
- >!Criação de migrations SQL `001` a `010` com tabelas, views, procedures, triggers e seed inicial.!<
- >!Adição de serviços e helpers em PHP para conexão de banco, criptografia e validação.!<
- >!Inclusão de scripts de cron placeholders e endpoint de webhook.!<
- >!Configuração de `.htaccess` e scaffolding de diretórios de storage/uploads.!<

#### Mudanças (detalhes por domínio)

##### Frontend e UI
- >!Roteador principal em `public/index.php` e páginas em `views/*` para auth/dashboard/emails/settings.!<
- >!Partials reutilizáveis em `public/partials/*` (header, sidebar, footer, toast e skeletons).!<
- >!Assets customizados em `public/assets/css/custom.css`, `public/assets/js/app.js`, logos, ilustrações e lotties placeholder.!<

##### Instalador
- >!Entrada `public/install/index.php` com progresso por etapa.!<
- >!Passos do instalador em `install/steps/01...06` para fluxo guiado inicial.!<

##### Banco de dados (MySQL/MariaDB)
- >!Migrations SQL em `install/migrations/001...010` cobrindo schema, views, procedures, triggers e seed/configuração inicial.!<

##### Backend PHP
- >!Helpers: `app/helpers/constants.php`, `app/helpers/functions.php`.!<
- >!Services: `app/services/Database.php`, `app/services/Encryption.php`, `app/services/Validator.php`.!<
- >!Config: `config/constants.php`, `config/env.example.php`.!<

##### Operação e integrações
- >!Cron placeholders em `cron/garbage_collector.php`, `cron/process_webhooks.php`, `cron/sync_resend.php`.!<
- >!Webhook placeholder em `webhook/resend.php`.!<
- >!Pastas de runtime com `.gitkeep` para `storage/*` e `public/uploads/temp`.!<

#### Arquivos adicionados/modificados
- >!`README.md`!<
- >!`app/controllers/.gitkeep`, `app/middleware/.gitkeep`!<
- >!`app/helpers/constants.php`, `app/helpers/functions.php`!<
- >!`app/services/Database.php`, `app/services/Encryption.php`, `app/services/Validator.php`!<
- >!`config/constants.php`, `config/env.example.php`!<
- >!`cron/garbage_collector.php`, `cron/process_webhooks.php`, `cron/sync_resend.php`!<
- >!`install/index.php`!<
- >!`install/migrations/001_create_tables.sql`!<
- >!`install/migrations/002_create_views.sql`!<
- >!`install/migrations/003_create_procedures_usuarios.sql`!<
- >!`install/migrations/004_create_procedures_sessoes.sql`!<
- >!`install/migrations/005_create_procedures_emails.sql`!<
- >!`install/migrations/006_create_procedures_seguranca.sql`!<
- >!`install/migrations/007_create_procedures_resend.sql`!<
- >!`install/migrations/008_create_procedures_auditoria.sql`!<
- >!`install/migrations/009_create_triggers.sql`!<
- >!`install/migrations/010_seed_admin_procedure.sql`!<
- >!`install/steps/01_welcome.php`!<
- >!`install/steps/02_database.php`!<
- >!`install/steps/03_admin.php`!<
- >!`install/steps/04_resend_prep.php`!<
- >!`install/steps/05_resend_config.php`!<
- >!`install/steps/06_finish.php`!<
- >!`public/.htaccess`!<
- >!`public/assets/css/custom.css`!<
- >!`public/assets/images/logo-light.svg`, `public/assets/images/logo-dark.svg`!<
- >!`public/assets/images/illustrations/welcome.svg`!<
- >!`public/assets/images/illustrations/empty-inbox.svg`!<
- >!`public/assets/images/illustrations/error.svg`!<
- >!`public/assets/js/app.js`!<
- >!`public/assets/lottie/loading.json`, `public/assets/lottie/success.json`, `public/assets/lottie/error.json`!<
- >!`public/install/index.php`!<
- >!`public/partials/header.php`, `public/partials/sidebar.php`, `public/partials/footer.php`!<
- >!`public/partials/components/toast.php`!<
- >!`public/partials/components/skeleton-card.php`!<
- >!`public/partials/components/skeleton-email-row.php`!<
- >!`public/uploads/temp/.gitkeep`!<
- >!`public/views/login.php`!<
- >!`public/views/pages/dashboard.php`, `public/views/pages/inbox.php`, `public/views/pages/compose.php`, `public/views/pages/settings.php`!<
- >!`public/views/partials/head.php`, `public/views/partials/footer.php`, `public/views/partials/app_shell.php`!<
- >!`storage/cache/.gitkeep`, `storage/logs/.gitkeep`, `storage/sessions/.gitkeep`, `storage/uploads/.gitkeep`!<
- >!`views/auth/login.php`!<
- >!`views/dashboard/index.php`!<
- >!`views/emails/inbox.php`, `views/emails/sent.php`, `views/emails/drafts.php`, `views/emails/compose.php`, `views/emails/view.php`!<
- >!`views/settings/index.php`, `views/settings/resend.php`, `views/settings/sessions.php`, `views/settings/audit.php`!<
- >!`webhook/resend.php`!<

</details>

---

## 2026-03-19

### Summary
- Hardened inbound email policy enforcement for Resend webhooks.
- Added optional Groq AI foundations with model fallback and usage tracking schema.
- Added optional cron-job.org scheduler foundations for managed recurring jobs.
- Replaced installer placeholders with a real onboarding wizard for MySQL/MariaDB, automatic migrations, admin creation and encrypted Resend setup.
- Added a MySQL/MariaDB compatibility audit to keep SQL migrations free of PostgreSQL/PLpgSQL-specific constructs.
- Open-sourced the project with MIT licensing, an English README, and contribution guidelines.

### Details

<details>
  <summary><strong>🤖 Commit 04c8929 — Add inbound email policy enforcement</strong></summary>

- **Date:** `2026-03-19`
- **Message:** `Add inbound email policy enforcement`

#### Changes
- >!Secure inbound webhook processing with mailbox allowlists, noreply blocking and reply-only controls.!<
- >!Added schema for inbound mailboxes and rejected inbound tracking.!<

</details>

<details>
  <summary><strong>🧠 Pending current branch work — Groq AI + cron-job.org foundations</strong></summary>

#### Planned/implemented scope in this branch
- >!Optional Groq integration stored in the database and disabled by default until an API key is saved.!<
- >!AI dashboard and AI settings pages for metrics, model routing and scheduler activation.!<
- >!Managed cron-job.org integration stored in the database and disabled by default until an API key is saved.!<
- >!Installer now validates MySQL/MariaDB credentials, runs migrations automatically, creates the first admin account and stores the Resend setup securely.!<
- >!Added a repository-level audit script to detect PostgreSQL/PLpgSQL leftovers in SQL migrations and kept stored routines on MySQL/MariaDB-style delimiters.!<
- >!MIT license, contribution guide and README rewrite in English.!<

</details>

---

## Convenções deste changelog

### Summary
- >!Visão executiva rápida do que mudou na data.!<

### Details
- >!Detalhamento técnico completo por commit e por domínio funcional.!<

### Accordion + Spoiler
- >!Cada bloco de commit está dentro de `<details>`/`<summary>` (accordion).!<
- >!Cada item informativo usa spoiler Markdown (`>! ... !<`) conforme solicitado.!<
