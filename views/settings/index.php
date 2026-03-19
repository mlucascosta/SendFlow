<?php $currentPage='settings'; ?>
<section class="grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">
  <div class="grid gap-6">
    <div class="panel rounded-[1.75rem] p-6">
      <span class="metric-badge bg-brand-primary/10 text-brand-primary dark:bg-brand-primaryDark/10 dark:text-brand-primaryDark">
        <ion-icon name="settings-outline"></ion-icon>
        Configuração central
      </span>
      <h1 class="mt-4 text-3xl font-semibold tracking-tight">Configurações operacionais alinhadas ao novo design system.</h1>
      <p class="mt-2 max-w-2xl text-sm leading-6 text-carbon-textSubtle dark:text-carbon-textSubtleDark">Entradas, ações e estados agora compartilham tokens visuais consistentes para Resend, IA, sessões e auditoria.</p>
      <div class="mt-5 flex flex-wrap gap-3">
        <a href="/settings/resend" class="btn-secondary text-sm">Abrir Resend</a>
        <a href="/settings/ai" class="btn-secondary text-sm">Abrir AI settings</a>
        <a href="/dashboard/ai" class="btn-secondary text-sm">Ver AI dashboard</a>
      </div>
    </div>

    <section class="surface-card p-6">
      <div class="flex items-center justify-between gap-3">
        <div>
          <h2 class="text-2xl font-semibold">Resend API</h2>
          <p class="mt-1 text-sm text-carbon-textSubtle dark:text-carbon-textSubtleDark">Gerencie domínio, webhook e token de forma mais legível.</p>
        </div>
        <span class="metric-badge bg-carbon-success/10 text-carbon-success dark:bg-carbon-successDark/10 dark:text-carbon-successDark">Conectado</span>
      </div>
      <div class="mt-6 grid gap-4 md:grid-cols-2">
        <label class="block md:col-span-2">
          <span class="mb-2 block text-sm font-medium">Token API</span>
          <input class="input-field font-mono" value="re_demo_sendflow_secure_key" placeholder="re_...">
        </label>
        <label class="block">
          <span class="mb-2 block text-sm font-medium">Domínio inbound</span>
          <input class="input-field" value="mail.seudominio.com" placeholder="mail.seudominio.com">
        </label>
        <label class="block">
          <span class="mb-2 block text-sm font-medium">Ambiente</span>
          <select class="input-field"><option>Produção</option><option>Staging</option></select>
        </label>
      </div>
      <div class="mt-5 flex flex-wrap gap-3">
        <button class="btn-primary">Salvar configuração</button>
        <button class="btn-secondary">Testar webhook</button>
      </div>
    </section>
  </div>

  <div class="grid gap-6">
    <section class="surface-card p-6">
      <h2 class="text-2xl font-semibold">Sessões ativas</h2>
      <p class="mt-1 text-sm text-carbon-textSubtle dark:text-carbon-textSubtleDark">Acompanhe dispositivos, localização aproximada e validade.</p>
      <div class="mt-6 space-y-4">
        <?php foreach ([['Chrome · Linux', 'Atual', 'Expira em 14 dias'], ['Safari · iPhone', 'Recente', 'Expira em 7 dias'], ['Firefox · Windows', 'Verificar', 'Última atividade há 2 dias']] as [$device, $state, $meta]): ?>
          <div class="rounded-3xl border border-carbon-border bg-carbon-layer p-4 dark:border-carbon-borderDark dark:bg-carbon-layerDark">
            <div class="flex items-start justify-between gap-3">
              <div>
                <p class="font-semibold"><?= htmlspecialchars($device, ENT_QUOTES, 'UTF-8') ?></p>
                <p class="mt-1 text-sm text-carbon-textSubtle dark:text-carbon-textSubtleDark"><?= htmlspecialchars($meta, ENT_QUOTES, 'UTF-8') ?></p>
              </div>
              <span class="metric-badge <?= $state === 'Verificar' ? 'bg-carbon-warning/20 text-[#8a6a00] dark:bg-carbon-warningDark/10 dark:text-carbon-warningDark' : 'bg-carbon-success/10 text-carbon-success dark:bg-carbon-successDark/10 dark:text-carbon-successDark' ?>"><?= htmlspecialchars($state, ENT_QUOTES, 'UTF-8') ?></span>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </section>

    <section class="surface-card p-6">
      <h2 class="text-2xl font-semibold">Segurança inbound</h2>
      <ul class="mt-5 space-y-3 text-sm leading-6 text-carbon-textSubtle dark:text-carbon-textSubtleDark">
        <li class="rounded-2xl bg-carbon-surface p-4 dark:bg-carbon-surfaceDark">Default-deny ativo para remetentes externos não aprovados.</li>
        <li class="rounded-2xl bg-carbon-surface p-4 dark:bg-carbon-surfaceDark">Assinatura de webhook verificada em tempo de recepção.</li>
        <li class="rounded-2xl bg-carbon-surface p-4 dark:bg-carbon-surfaceDark">Logs de auditoria com retenção e trilha por sessão.</li>
      </ul>
    </section>
  </div>
</section>
