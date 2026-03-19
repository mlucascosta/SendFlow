<?php $currentPage='dashboard'; ?>
<section class="grid gap-6">
  <div class="panel animate__animated animate__fadeIn rounded-[1.75rem] p-6 lg:p-8">
    <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
      <div class="max-w-3xl">
        <span class="metric-badge bg-brand-primary/10 text-brand-primary dark:bg-brand-primaryDark/15 dark:text-brand-primaryDark">
          <ion-icon name="sparkles-outline"></ion-icon>
          IBM Carbon redesign
        </span>
        <h1 class="mt-4 text-4xl font-semibold tracking-tight text-carbon-text dark:text-carbon-textDark">Centralize operações inbound, IA e segurança com uma experiência visual mais clara.</h1>
        <p class="mt-4 max-w-2xl text-base leading-7 text-carbon-textSubtle dark:text-carbon-textSubtleDark">O novo dashboard prioriza contraste, estados semânticos e leitura rápida para webhooks Resend, saúde da fila e onboarding guiado em seis etapas.</p>
      </div>
      <div class="grid gap-3 sm:grid-cols-2">
        <div class="rounded-3xl border border-carbon-border bg-carbon-layer p-4 dark:border-carbon-borderDark dark:bg-carbon-layerDark">
          <p class="text-xs uppercase tracking-[0.2em] text-carbon-textSubtle dark:text-carbon-textSubtleDark">Disponibilidade</p>
          <p class="mt-2 text-3xl font-semibold">99.98%</p>
          <p class="mt-1 text-sm text-carbon-success dark:text-carbon-successDark">Eventos processados sem falhas críticas</p>
        </div>
        <div class="rounded-3xl border border-carbon-border bg-carbon-layer p-4 dark:border-carbon-borderDark dark:bg-carbon-layerDark">
          <p class="text-xs uppercase tracking-[0.2em] text-carbon-textSubtle dark:text-carbon-textSubtleDark">IA opcional</p>
          <p class="mt-2 text-3xl font-semibold">312</p>
          <p class="mt-1 text-sm text-carbon-info dark:text-carbon-infoDark">Resumos e classificações nas últimas 24h</p>
        </div>
      </div>
    </div>
  </div>

  <div class="grid gap-4 xl:grid-cols-4 md:grid-cols-2">
    <article class="stat-card p-5">
      <p class="text-sm text-carbon-textSubtle dark:text-carbon-textSubtleDark">Emails hoje</p>
      <div class="mt-4 flex items-end justify-between">
        <p class="text-4xl font-semibold">124</p>
        <span class="metric-badge bg-carbon-success/10 text-carbon-success dark:bg-carbon-successDark/10 dark:text-carbon-successDark">+12%</span>
      </div>
    </article>
    <article class="stat-card p-5">
      <p class="text-sm text-carbon-textSubtle dark:text-carbon-textSubtleDark">Taxa de entrega</p>
      <div class="mt-4 flex items-end justify-between">
        <p class="text-4xl font-semibold">98.3%</p>
        <span class="metric-badge bg-brand-primary/10 text-brand-primary dark:bg-brand-primaryDark/10 dark:text-brand-primaryDark">Estável</span>
      </div>
    </article>
    <article class="stat-card p-5">
      <p class="text-sm text-carbon-textSubtle dark:text-carbon-textSubtleDark">Sinais de spam</p>
      <div class="mt-4 flex items-end justify-between">
        <p class="text-4xl font-semibold">7</p>
        <span class="metric-badge bg-carbon-warning/20 text-[#8a6a00] dark:bg-carbon-warningDark/15 dark:text-carbon-warningDark">Revisar</span>
      </div>
    </article>
    <article class="stat-card p-5">
      <p class="text-sm text-carbon-textSubtle dark:text-carbon-textSubtleDark">Onboarding</p>
      <div class="mt-4 flex items-end justify-between">
        <p class="text-4xl font-semibold">5/6</p>
        <span class="metric-badge bg-carbon-info/10 text-carbon-info dark:bg-carbon-infoDark/10 dark:text-carbon-infoDark">Última etapa</span>
      </div>
    </article>
  </div>

  <div class="grid gap-6 xl:grid-cols-[1.5fr_1fr]">
    <section class="surface-card p-6">
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-2xl font-semibold">Atividade recente</h2>
          <p class="mt-1 text-sm text-carbon-textSubtle dark:text-carbon-textSubtleDark">Eventos recentes da caixa de entrada, classificações de IA e filtros de segurança.</p>
        </div>
        <button class="btn-secondary text-sm">Ver timeline</button>
      </div>
      <div class="mt-6 space-y-4">
        <?php foreach ([
          ['title' => 'Webhook Resend validado', 'meta' => 'Há 3 min · assinatura verificada', 'tone' => 'success'],
          ['title' => 'Resumo IA gerado para lead enterprise', 'meta' => 'Há 12 min · Groq opcional ativo', 'tone' => 'info'],
          ['title' => 'Política default-deny bloqueou remetente não listado', 'meta' => 'Há 27 min · ação preventiva', 'tone' => 'warning'],
        ] as $item): ?>
          <div class="rounded-3xl border border-carbon-border bg-carbon-layer p-4 dark:border-carbon-borderDark dark:bg-carbon-layerDark">
            <div class="flex items-start gap-4">
              <span class="mt-1 inline-flex h-10 w-10 items-center justify-center rounded-2xl <?= $item['tone'] === 'success' ? 'bg-carbon-success/10 text-carbon-success dark:bg-carbon-successDark/10 dark:text-carbon-successDark' : ($item['tone'] === 'warning' ? 'bg-carbon-warning/20 text-[#8a6a00] dark:bg-carbon-warningDark/10 dark:text-carbon-warningDark' : 'bg-carbon-info/10 text-carbon-info dark:bg-carbon-infoDark/10 dark:text-carbon-infoDark') ?>">
                <ion-icon name="<?= $item['tone'] === 'success' ? 'checkmark-circle-outline' : ($item['tone'] === 'warning' ? 'warning-outline' : 'sparkles-outline') ?>"></ion-icon>
              </span>
              <div>
                <h3 class="font-semibold"><?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                <p class="mt-1 text-sm text-carbon-textSubtle dark:text-carbon-textSubtleDark"><?= htmlspecialchars($item['meta'], ENT_QUOTES, 'UTF-8') ?></p>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </section>

    <section class="surface-card p-6">
      <h2 class="text-2xl font-semibold">Fila de processamento</h2>
      <p class="mt-1 text-sm text-carbon-textSubtle dark:text-carbon-textSubtleDark">Distribuição dos jobs assíncronos após o redesign.</p>
      <div class="mt-6 space-y-4">
        <?php foreach ([['Webhook parsing', 92], ['Resumo IA', 61], ['Auditoria', 84], ['Sessões seguras', 76]] as [$label, $progress]): ?>
          <div>
            <div class="mb-2 flex items-center justify-between text-sm">
              <span><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></span>
              <span class="font-mono text-carbon-textSubtle dark:text-carbon-textSubtleDark"><?= $progress ?>%</span>
            </div>
            <div class="h-3 rounded-full bg-carbon-surface dark:bg-carbon-surfaceDark">
              <div class="h-3 rounded-full bg-brand-primary dark:bg-brand-primaryDark" style="width: <?= $progress ?>%"></div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </section>
  </div>
</section>
