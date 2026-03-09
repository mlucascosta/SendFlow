# SendFlow

SendFlow é um webmail self-hosted em PHP puro (sem Composer), com foco em segurança, UX moderna e compatibilidade com shared hosting/VPS.

## Frontend (Resend + iCloud Inspired)

Estrutura implementada:

- `public/index.php`: layout base com header, sidebar, footer, toasts e roteamento de páginas.
- `public/assets/css/custom.css`: design tokens, componentes visuais e skeleton states.
- `public/assets/js/app.js`: theme switcher com `localStorage`, toasts, utilitários HTMX e Lottie loader.
- `public/partials/*`: componentes reutilizáveis de layout.
- `views/*`: páginas separadas por domínio (`auth`, `dashboard`, `emails`, `settings`).
- `public/install/index.php`: wizard visual de instalação (6 passos).

## Stack de UI (CDN)

- Normalize.css
- Tailwind CSS
- Animate.css
- HTMX
- Alpine.js
- Ionicons
- Lottie

## Rotas principais

- `/login`
- `/dashboard`
- `/emails/inbox`
- `/emails/sent`
- `/emails/drafts`
- `/emails/compose`
- `/settings`
- `/settings/resend`
- `/settings/sessions`
- `/settings/audit`
- `/install/index.php` (wizard)

## Executar localmente

```bash
php -S 127.0.0.1:8000 -t public
```
