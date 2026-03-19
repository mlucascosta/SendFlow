# Runbook: AI and Automation Features

## Quando usar

- mudanças em `/dashboard/ai`;
- mudanças em `/settings/ai`;
- alterações de fallback/model catalog;
- mudanças em scheduler/cron-job.org.

## Passo a passo

1. revisar `docs/workflow/foundation/integrations.md`;
2. confirmar se a tela continua acessível por navegação principal ou atalhos claros;
3. mapear impacto em:
   - UX,
   - credenciais/API keys,
   - schema,
   - logs e métricas.

## Checklist de review

- [ ] `/dashboard/ai` continua descobrível;
- [ ] `/settings/ai` continua descobrível;
- [ ] mudanças em modelos/fallbacks estão refletidas em docs;
- [ ] integrações opcionais continuam desabilitadas por padrão quando sem credenciais.
