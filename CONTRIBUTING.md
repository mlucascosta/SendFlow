# Contributing to SendFlow

Thanks for contributing to SendFlow.

## Development principles

- Prefer small, reviewable pull requests.
- Keep integrations optional by default.
- Do not require vendor lock-in for core mail flows.
- Treat inbound email handling as security-sensitive code.
- Store third-party API credentials encrypted when they must live in the database.

## Pull requests

- Include a short summary of the change.
- Describe any schema or migration impact.
- List manual or automated checks that were run.
- Update `README.md` and `CHANGELOG.md` when the public behavior changes.
- Run `php scripts/audit_mysql_compat.php` when changing SQL migrations or stored routines.

## AI integrations

- Groq support must remain disabled until the user stores an API key in the database.
- Add new AI features behind explicit feature flags.
- Define fallback models so the product can degrade gracefully on rate limits or capacity issues.
- Log enough metadata for auditing without storing unnecessary sensitive prompt data.

## Scheduler integrations

- cron-job.org is the default managed scheduler target for recurring background jobs.
- New recurring jobs should be represented in `managed_cron_jobs`.
- Jobs must fail safely when the scheduler integration is disabled or unconfigured.

## Security

- Validate webhook signatures before processing payloads.
- Keep `noreply` addresses blocked for inbound mail.
- Prefer allowlists and explicit routing over broad permissive rules.


## Adapted AI workflow for SendFlow

Before medium/large changes, prefer the repository workflow scaffold:

```bash
./scripts/workflow-start.sh <change-slug>
./scripts/workflow-status.sh
```

Expected flow:

1. brainstorm scope and constraints;
2. produce a phased plan;
3. apply quality gates when the change touches installer/schema/auth/integrations;
4. execute one phase at a time;
5. review and capture reusable learnings in docs.

Keep `docs/workflow/foundation/` up to date when changing architecture, integrations, environments or glossary-level project concepts.
