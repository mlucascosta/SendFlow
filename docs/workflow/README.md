# SendFlow AI Workflow

Este repositório adota um workflow docs-first inspirado na metodologia do **Pster's AI Workflow**, mas adaptado à realidade do SendFlow: PHP puro, instalador web, suporte a MySQL/SQLite, webhooks inbound e integrações opcionais com IA/scheduler.

## Objetivos

- reduzir alucinação em mudanças assistidas por IA;
- manter previsibilidade em features, refactors e correções;
- transformar documentação em memória operacional reutilizável;
- garantir que mudanças de produto, schema e integrações sigam uma trilha clara.

## Fluxo padrão adaptado ao SendFlow

1. **Brainstorm**
   - esclarecer escopo, restrições, impacto em UX/instalador/schema/integrations;
   - registrar decisões iniciais em `docs/workflow/changes/<change>/01-brainstorm.md`.
2. **Plan**
   - quebrar a entrega em fases pequenas e verificáveis;
   - registrar em `02-plan.md` com riscos, testes e docs afetadas.
3. **Quality gates (quando necessário)**
   - checklist de requisitos;
   - clarificação de ambiguidades;
   - análise read-only antes de mexer em migrations, webhook ou auth.
4. **Execution**
   - implementar uma fase por vez;
   - atualizar docs do domínio tocado durante a execução.
5. **Review**
   - revisar impacto técnico, acessos de navegação, compatibilidade de banco e fluxo de instalação;
   - registrar achados em `03-review.md`.
6. **Doc capture**
   - extrair aprendizados reutilizáveis para runbooks/foundation docs.

## Estrutura recomendada

- `docs/workflow/foundation/`
  - visão estável do sistema (arquitetura, integrações, ambientes, glossário).
- `docs/workflow/runbooks/`
  - procedimentos operacionais repetíveis.
- `docs/workflow/templates/change/`
  - modelos base para brainstorm, plano, review e captura.
- `docs/workflow/changes/`
  - trilha documental de cada mudança ativa/concluída.

## Quando atualizar docs fundacionais

Atualize `foundation/` sempre que a mudança alterar qualquer um destes pontos:

- shell público, rotas principais ou onboarding;
- estratégia de banco, migrations, compatibilidade MySQL/SQLite;
- integrações Resend, Groq ou cron-job.org;
- políticas inbound e posture de segurança;
- convenções operacionais relevantes para agentes/colaboradores.

## Qualidade mínima por tipo de mudança

### Schema / installer

- validar `php -l` nos arquivos alterados;
- validar a rota `/install/index.php` quando o fluxo mudar;
- revisar compatibilidade MySQL e fallback SQLite;
- atualizar runbook de instalação se necessário.

### UI / navegação

- garantir navegabilidade para telas existentes;
- revisar acessos principais e links profundos;
- evitar regressões em `/dashboard`, `/emails/*`, `/settings/*`, `/install/*`.

### Integrações externas

- documentar pré-requisitos, segredo/token, fallback e failure modes;
- atualizar docs de integração e runbooks.

## Scripts de apoio

- `./scripts/workflow-start.sh <slug>` cria a trilha documental inicial de uma mudança.
- `./scripts/workflow-status.sh` verifica a presença da estrutura essencial do workflow e lista mudanças registradas.

## Convenção de nomenclatura para mudanças

Use nomes curtos e orientados ao resultado, por exemplo:

- `sqlite-fallback-installer`
- `restore-ai-navigation`
- `resend-webhook-hardening`
- `dashboard-ai-insights`
