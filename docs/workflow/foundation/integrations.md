# Foundation: Integrations

## Resend

**Uso principal**
- webhook inbound/tracking;
- credenciais de domínio e API salvas no banco.

**Pontos de código**
- `webhook/resend.php`
- `views/settings/resend.php`
- `Installer::saveResendConfig()`

**Riscos comuns**
- assinatura inválida;
- credenciais ausentes;
- regressão de navegação para telas de configuração.

## Groq

**Uso principal**
- resumos, classificação, detecção de spam/phishing, labels e extração de tarefas.

**Pontos de código**
- `app/services/GroqClient.php`
- `app/services/GroqModelCatalog.php`
- `views/settings/ai.php`
- `views/dashboard/ai.php`

**Riscos comuns**
- telas acessíveis apenas por URL profunda;
- fallback de modelos mal documentado;
- feature flags inconsistentes com schema.

## cron-job.org

**Uso principal**
- jobs recorrentes sem depender de cron do servidor.

**Pontos de código**
- `app/services/CronJobOrgClient.php`
- `views/settings/ai.php`
- migrations de AI/scheduler.

## SQLite local fallback

**Uso principal**
- instalações locais, demos e testes rápidos sem provisionar MySQL.

**Pontos de código**
- `Database::connection()`
- `Installer::resolveDatabaseConfig()`
- `install/migrations/sqlite/001_create_schema.sql`
- `storage/database/`

**Cuidados**
- manter o `.db` fora de `public/`;
- aplicar permissões restritivas;
- revisar queries específicas de MySQL antes de reutilizar em runtime cross-driver.
