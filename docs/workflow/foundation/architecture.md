# Foundation: Architecture

## Resumo

SendFlow é uma aplicação webmail self-hosted em PHP puro, com front controller em `public/index.php`, views PHP renderizadas no servidor e serviços pequenos em `app/services/`.

## Camadas principais

- **Routing/UI shell**
  - `public/index.php` resolve rota -> view;
  - `public/partials/` compõem header, sidebar, footer e componentes compartilhados;
  - `views/` contém as telas roteadas.
- **Services**
  - `app/services/Database.php`: conexão PDO por driver;
  - `Installer.php`: onboarding, migrations, admin inicial e persistência de env;
  - serviços especializados para webhook, inbound policy, Groq e cron-job.org.
- **Install flow**
  - `public/install/index.php` controla wizard de 6 etapas;
  - `install/steps/` contém conteúdo de cada etapa;
  - `install/migrations/` contém schema MySQL/MariaDB;
  - `install/migrations/sqlite/` contém schema SQLite adaptado para onboarding/local use.
- **Runtime integrations**
  - `webhook/resend.php` processa eventos da Resend;
  - `cron/` contém jobs operacionais.

## Decisões arquiteturais atuais

- sem framework full-stack;
- dependências visuais via CDN para simplificar deploy;
- `config/env.php` é gerado pelo instalador;
- o sistema aceita MySQL/MariaDB e agora também SQLite para setup local;
- a postura inbound é default-deny.

## Áreas sensíveis a regressão

- wizard de instalação;
- navegação entre dashboard/settings/features opcionais;
- schema e compatibilidade de banco;
- persistência segura de segredos (Resend/Groq/etc.);
- endpoint de webhook inbound.
