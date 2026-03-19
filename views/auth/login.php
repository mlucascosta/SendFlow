<section class="relative isolate min-h-[calc(100vh-4.5rem)] overflow-hidden px-4 py-10 md:px-6 lg:px-8">
  <div class="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_top_left,_rgba(15,98,254,0.18),_transparent_30%),radial-gradient(circle_at_bottom_right,_rgba(0,67,206,0.12),_transparent_25%)] dark:bg-[radial-gradient(circle_at_top_left,_rgba(69,137,255,0.22),_transparent_24%),radial-gradient(circle_at_bottom_right,_rgba(120,169,255,0.18),_transparent_24%)]"></div>
  <div class="mx-auto grid max-w-7xl gap-8 lg:grid-cols-[1.15fr_0.85fr]">
    <section class="panel rounded-[2rem] p-8 lg:p-10">
      <span class="metric-badge bg-brand-primary/10 text-brand-primary dark:bg-brand-primaryDark/15 dark:text-brand-primaryDark">
        <ion-icon name="shield-checkmark-outline"></ion-icon>
        SendFlow redesign
      </span>
      <h1 class="mt-6 max-w-2xl text-4xl font-semibold leading-tight tracking-tight text-carbon-text dark:text-carbon-textDark">Webmail auto-hospedado com onboarding guiado, inbound seguro e linguagem visual Carbon-inspired.</h1>
      <p class="mt-5 max-w-2xl text-lg leading-8 text-carbon-textSubtle dark:text-carbon-textSubtleDark">A nova experiência reforça foco, acessibilidade e legibilidade para login, inbox, configurações e operações com IA opcional.</p>

      <div class="mt-8 grid gap-4 md:grid-cols-3">
        <article class="rounded-3xl border border-carbon-border bg-carbon-layer p-5 dark:border-carbon-borderDark dark:bg-carbon-layerDark">
          <ion-icon class="text-brand-primary dark:text-brand-primaryDark" name="mail-open-outline"></ion-icon>
          <h2 class="mt-3 font-semibold">Inbound seguro</h2>
          <p class="mt-2 text-sm leading-6 text-carbon-textSubtle dark:text-carbon-textSubtleDark">Políticas default-deny e verificação de webhook desde a entrada.</p>
        </article>
        <article class="rounded-3xl border border-carbon-border bg-carbon-layer p-5 dark:border-carbon-borderDark dark:bg-carbon-layerDark">
          <ion-icon class="text-carbon-info dark:text-carbon-infoDark" name="sparkles-outline"></ion-icon>
          <h2 class="mt-3 font-semibold">IA opcional</h2>
          <p class="mt-2 text-sm leading-6 text-carbon-textSubtle dark:text-carbon-textSubtleDark">Resumos, classificação e detecção de spam sem travar o fluxo principal.</p>
        </article>
        <article class="rounded-3xl border border-carbon-border bg-carbon-layer p-5 dark:border-carbon-borderDark dark:bg-carbon-layerDark">
          <ion-icon class="text-carbon-success dark:text-carbon-successDark" name="albums-outline"></ion-icon>
          <h2 class="mt-3 font-semibold">Zero build</h2>
          <p class="mt-2 text-sm leading-6 text-carbon-textSubtle dark:text-carbon-textSubtleDark">Stack CDN-first pronta para hospedagem compartilhada e VPS.</p>
        </article>
      </div>

      <div class="mt-8 rounded-[1.75rem] border border-carbon-border bg-carbon-layer p-6 dark:border-carbon-borderDark dark:bg-carbon-layerDark">
        <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
          <div>
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-carbon-textSubtle dark:text-carbon-textSubtleDark">Estado operacional</p>
            <p class="mt-2 text-2xl font-semibold">Plataforma pronta para receber e-mails inbound.</p>
          </div>
          <div id="lottie-mail" class="h-28 w-full max-w-[220px]"></div>
        </div>
      </div>
    </section>

    <section class="surface-card rounded-[2rem] p-8 lg:p-10">
      <div class="flex items-center justify-between gap-3">
        <div>
          <h2 class="text-3xl font-semibold tracking-tight">Entrar no SendFlow</h2>
          <p class="mt-2 text-sm text-carbon-textSubtle dark:text-carbon-textSubtleDark">Acesse sua central de e-mails, webhooks e automações com contraste elevado e foco visível.</p>
        </div>
        <span class="metric-badge bg-carbon-success/10 text-carbon-success dark:bg-carbon-successDark/10 dark:text-carbon-successDark">Online</span>
      </div>

      <form class="mt-8 space-y-5" hx-post="/login" hx-target="#login-feedback" hx-swap="innerHTML">
        <label class="block">
          <span class="mb-2 block text-sm font-medium">Email</span>
          <input class="input-field" type="email" placeholder="voce@dominio.com">
        </label>
        <label class="block">
          <span class="mb-2 block text-sm font-medium">Senha</span>
          <input class="input-field" type="password" placeholder="••••••••">
        </label>
        <div class="flex items-center justify-between rounded-2xl bg-carbon-surface px-4 py-3 text-sm dark:bg-carbon-surfaceDark">
          <span class="text-carbon-textSubtle dark:text-carbon-textSubtleDark">Tema e preferências são persistidos localmente.</span>
          <ion-icon class="text-brand-primary dark:text-brand-primaryDark" name="moon-outline"></ion-icon>
        </div>
        <button class="btn-primary w-full" type="submit">
          <ion-icon name="log-in-outline"></ion-icon>
          Entrar
        </button>
      </form>
      <div id="login-feedback" class="mt-4 rounded-2xl bg-carbon-surface px-4 py-3 text-sm text-carbon-textSubtle dark:bg-carbon-surfaceDark dark:text-carbon-textSubtleDark">Demo visual do novo layout de autenticação.</div>
    </section>
  </div>
</section>
