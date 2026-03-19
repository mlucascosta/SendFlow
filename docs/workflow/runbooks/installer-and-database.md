# Runbook: Installer and Database Changes

## Quando usar

- mudanças em `public/install/index.php`;
- mudanças em `Installer.php`, `Database.php` ou `config/env.example.php`;
- novas migrations;
- alterações no fallback SQLite.

## Passo a passo

1. revisar `docs/workflow/foundation/architecture.md` e `integrations.md`;
2. identificar se a mudança afeta MySQL, SQLite ou ambos;
3. se alterar schema, revisar também o impacto em:
   - onboarding do admin,
   - persistência de Resend,
   - webhook/runtime;
4. rodar checks sintáticos mínimos:
   - `php -l app/services/Database.php`
   - `php -l app/services/Installer.php`
   - `php -l public/install/index.php`
5. se houver SQLite:
   - validar criação do arquivo `.db`;
   - validar execução do schema SQLite;
   - confirmar que o arquivo continua fora de `public/`.

## Checklist de review

- [ ] o wizard continua navegável do início ao fim;
- [ ] inputs vazios/parciais produzem comportamento esperado;
- [ ] o driver salvo em `config/env.php` está correto;
- [ ] migrations corretas são escolhidas por driver;
- [ ] segredos continuam protegidos;
- [ ] documentação foi atualizada.
