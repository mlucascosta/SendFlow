<?php $currentPage='inbox'; ?>
<section class="grid gap-6" x-data="{selectedEmails:[]}">
  <div class="panel rounded-[1.75rem] p-6">
    <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
      <div>
        <span class="metric-badge bg-carbon-info/10 text-carbon-info dark:bg-carbon-infoDark/10 dark:text-carbon-infoDark">
          <ion-icon name="mail-outline"></ion-icon>
          Inbox redesenhada
        </span>
        <h1 class="mt-4 text-3xl font-semibold tracking-tight">Caixa de entrada com leitura mais rápida, estados claros e filtros acionáveis.</h1>
        <p class="mt-2 max-w-3xl text-sm leading-6 text-carbon-textSubtle dark:text-carbon-textSubtleDark">Busca, classificação por prioridade e indicadores de IA agora usam a mesma semântica visual do restante da plataforma.</p>
      </div>
      <div class="flex flex-wrap gap-2">
        <span class="metric-badge bg-carbon-success/10 text-carbon-success dark:bg-carbon-successDark/10 dark:text-carbon-successDark">86 processados</span>
        <span class="metric-badge bg-carbon-warning/20 text-[#8a6a00] dark:bg-carbon-warningDark/10 dark:text-carbon-warningDark">4 pendentes</span>
        <span class="metric-badge bg-brand-primary/10 text-brand-primary dark:bg-brand-primaryDark/10 dark:text-brand-primaryDark">12 com IA</span>
      </div>
    </div>
  </div>

  <div class="surface-card overflow-hidden">
    <div class="border-b border-carbon-border p-4 dark:border-carbon-borderDark lg:p-5">
      <div class="grid gap-3 lg:grid-cols-[auto_1.5fr_repeat(3,minmax(0,1fr))_auto]">
        <label class="inline-flex items-center justify-center rounded-2xl border border-carbon-border bg-carbon-layer px-4 dark:border-carbon-borderDark dark:bg-carbon-layerDark">
          <input type="checkbox" aria-label="Selecionar todos" class="h-4 w-4 rounded border-carbon-border text-brand-primary focus:ring-brand-primary dark:border-carbon-borderDark dark:bg-carbon-layerDark">
        </label>
        <label class="relative block">
          <ion-icon class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-carbon-textSubtle dark:text-carbon-textSubtleDark" name="search-outline"></ion-icon>
          <input class="input-field pl-12" placeholder="Buscar remetente, assunto ou domínio...">
        </label>
        <select class="input-field"><option>Prioridade</option><option>Alta</option><option>Média</option><option>Baixa</option></select>
        <select class="input-field"><option>Status IA</option><option>Com resumo</option><option>Spam suspeito</option><option>Sem IA</option></select>
        <select class="input-field"><option>Período</option><option>Hoje</option><option>Últimos 7 dias</option><option>Últimos 30 dias</option></select>
        <button class="btn-primary">Filtrar</button>
      </div>
    </div>

    <div class="divide-y divide-carbon-border dark:divide-carbon-borderDark">
      <?php foreach ([
        ['from' => 'Cliente Enterprise', 'subject' => 'Aprovação do rollout SendFlow', 'preview' => 'Precisamos alinhar o webhook inbound com regras default-deny antes da virada do ambiente.', 'time' => '09:18', 'state' => 'Alta prioridade', 'tone' => 'info'],
        ['from' => 'Resend', 'subject' => 'Webhook delivery recebido', 'preview' => 'Evento entregue com assinatura válida e payload dentro da política definida.', 'time' => '08:42', 'state' => 'Verificado', 'tone' => 'success'],
        ['from' => 'Groq Pipeline', 'subject' => 'Resumo IA indisponível temporariamente', 'preview' => 'A fila manteve o fallback operacional e sinalizou o incidente para auditoria.', 'time' => '08:10', 'state' => 'Atenção', 'tone' => 'warning'],
        ['from' => 'Financeiro', 'subject' => 'Confirmação de renovação VPS', 'preview' => 'Cobrança confirmada com sucesso; nenhuma ação adicional necessária.', 'time' => 'Ontem', 'state' => 'Rotina', 'tone' => 'neutral'],
      ] as $index => $email): ?>
        <article class="animate__animated animate__fadeInUp px-4 py-5 lg:px-6" style="--i: <?= $index ?>; animation-delay: calc(var(--i) * 50ms);">
          <div class="flex flex-col gap-4 xl:flex-row xl:items-start xl:justify-between">
            <div class="flex gap-4">
              <input type="checkbox" x-model="selectedEmails" value="<?= $index ?>" class="mt-3 h-4 w-4 rounded border-carbon-border text-brand-primary focus:ring-brand-primary dark:border-carbon-borderDark dark:bg-carbon-layerDark">
              <span class="mt-1 inline-flex h-11 w-11 items-center justify-center rounded-2xl <?= $email['tone'] === 'success' ? 'bg-carbon-success/10 text-carbon-success dark:bg-carbon-successDark/10 dark:text-carbon-successDark' : ($email['tone'] === 'warning' ? 'bg-carbon-warning/20 text-[#8a6a00] dark:bg-carbon-warningDark/10 dark:text-carbon-warningDark' : ($email['tone'] === 'info' ? 'bg-brand-primary/10 text-brand-primary dark:bg-brand-primaryDark/10 dark:text-brand-primaryDark' : 'bg-carbon-surface text-carbon-textSubtle dark:bg-carbon-surfaceDark dark:text-carbon-textSubtleDark')) ?>">
                <ion-icon name="<?= $email['tone'] === 'success' ? 'checkmark-outline' : ($email['tone'] === 'warning' ? 'warning-outline' : 'mail-outline') ?>"></ion-icon>
              </span>
              <div>
                <div class="flex flex-wrap items-center gap-2">
                  <strong class="text-base"><?= htmlspecialchars($email['from'], ENT_QUOTES, 'UTF-8') ?></strong>
                  <span class="metric-badge <?= $email['tone'] === 'success' ? 'bg-carbon-success/10 text-carbon-success dark:bg-carbon-successDark/10 dark:text-carbon-successDark' : ($email['tone'] === 'warning' ? 'bg-carbon-warning/20 text-[#8a6a00] dark:bg-carbon-warningDark/10 dark:text-carbon-warningDark' : ($email['tone'] === 'info' ? 'bg-carbon-info/10 text-carbon-info dark:bg-carbon-infoDark/10 dark:text-carbon-infoDark' : 'bg-carbon-surface text-carbon-textSubtle dark:bg-carbon-surfaceDark dark:text-carbon-textSubtleDark')) ?>"><?= htmlspecialchars($email['state'], ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <h2 class="mt-2 text-lg font-semibold"><?= htmlspecialchars($email['subject'], ENT_QUOTES, 'UTF-8') ?></h2>
                <p class="mt-2 max-w-3xl text-sm leading-6 text-carbon-textSubtle dark:text-carbon-textSubtleDark"><?= htmlspecialchars($email['preview'], ENT_QUOTES, 'UTF-8') ?></p>
              </div>
            </div>
            <div class="flex items-center gap-3 xl:flex-col xl:items-end">
              <span class="text-sm font-mono text-carbon-textSubtle dark:text-carbon-textSubtleDark"><?= htmlspecialchars($email['time'], ENT_QUOTES, 'UTF-8') ?></span>
              <button class="btn-secondary text-sm">Abrir</button>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="grid gap-4 md:grid-cols-3">
    <div class="surface-card p-5"><p class="text-sm text-carbon-textSubtle dark:text-carbon-textSubtleDark">Resumo automático</p><div class="mt-3 h-16 rounded-2xl skeleton"></div></div>
    <div class="surface-card p-5"><p class="text-sm text-carbon-textSubtle dark:text-carbon-textSubtleDark">Classificação</p><div class="mt-3 h-16 rounded-2xl skeleton"></div></div>
    <div class="surface-card p-5"><p class="text-sm text-carbon-textSubtle dark:text-carbon-textSubtleDark">Detecção de spam</p><div class="mt-3 h-16 rounded-2xl skeleton"></div></div>
  </div>
</section>
