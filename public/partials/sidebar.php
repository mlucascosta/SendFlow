<?php
$primaryNavItems = [
    ['href' => '/dashboard', 'icon' => 'grid-outline', 'label' => 'Dashboard', 'section' => 'dashboard'],
    ['href' => '/emails/inbox', 'icon' => 'mail-outline', 'label' => 'Inbox', 'section' => 'inbox'],
    ['href' => '/emails/sent', 'icon' => 'paper-plane-outline', 'label' => 'Enviados', 'section' => 'sent'],
    ['href' => '/emails/drafts', 'icon' => 'create-outline', 'label' => 'Rascunhos', 'section' => 'drafts'],
    ['href' => '/emails/compose', 'icon' => 'sparkles-outline', 'label' => 'Compor', 'section' => 'compose'],
    ['href' => '/settings', 'icon' => 'settings-outline', 'label' => 'Configurações', 'section' => 'settings'],
];

$featureNavItems = [
    ['href' => '/settings/resend', 'icon' => 'paper-plane-outline', 'label' => 'Resend setup'],
    ['href' => '/settings/ai', 'icon' => 'hardware-chip-outline', 'label' => 'AI settings'],
    ['href' => '/dashboard/ai', 'icon' => 'sparkles-outline', 'label' => 'AI dashboard'],
];
?>
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-[4.5rem] left-0 z-30 flex w-[18rem] flex-col border-r border-carbon-border/70 bg-carbon-surface/95 px-4 py-5 backdrop-blur transition-transform duration-300 dark:border-carbon-borderDark/60 dark:bg-carbon-surfaceDark/95 md:static md:translate-x-0">
  <div class="mb-6 rounded-3xl border border-brand-primary/15 bg-brand-primary/5 p-4 dark:border-brand-primaryDark/20 dark:bg-brand-primaryDark/10">
    <div class="flex items-start justify-between gap-3">
      <div>
        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-brand-primary dark:text-brand-primaryDark">Mission control</p>
        <h2 class="mt-2 text-lg font-semibold text-carbon-text dark:text-carbon-textDark">Operação inbound segura</h2>
      </div>
      <span class="rounded-full bg-carbon-layer px-2.5 py-1 text-xs font-medium text-carbon-textSubtle dark:bg-carbon-layerDark dark:text-carbon-textSubtleDark">6 etapas</span>
    </div>
    <p class="mt-3 text-sm leading-6 text-carbon-textSubtle dark:text-carbon-textSubtleDark">Monitore webhooks Resend, sinais de IA e políticas default-deny em uma única navegação lateral.</p>
  </div>

  <nav class="space-y-1.5">
    <?php foreach ($primaryNavItems as $item): ?>
      <a href="<?= htmlspecialchars($item['href'], ENT_QUOTES, 'UTF-8') ?>" class="sidebar-link <?= ($currentSection ?? '') === $item['section'] ? 'active' : '' ?>">
        <ion-icon name="<?= htmlspecialchars($item['icon'], ENT_QUOTES, 'UTF-8') ?>"></ion-icon>
        <span><?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?></span>
      </a>
    <?php endforeach; ?>
  </nav>

  <div class="mt-6 rounded-3xl border border-carbon-border bg-carbon-layer p-4 dark:border-carbon-borderDark dark:bg-carbon-layerDark">
    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-carbon-textSubtle dark:text-carbon-textSubtleDark">Acessos rápidos</p>
    <div class="mt-3 space-y-1.5">
      <?php foreach ($featureNavItems as $item): ?>
        <a href="<?= htmlspecialchars($item['href'], ENT_QUOTES, 'UTF-8') ?>" class="sidebar-link !min-h-[2.75rem] !px-3 !py-2 text-sm">
          <ion-icon name="<?= htmlspecialchars($item['icon'], ENT_QUOTES, 'UTF-8') ?>"></ion-icon>
          <span><?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="mt-6 grid gap-3">
    <div class="rounded-3xl border border-carbon-border bg-carbon-layer p-4 dark:border-carbon-borderDark dark:bg-carbon-layerDark">
      <div class="flex items-center gap-3">
        <span class="grid h-10 w-10 place-items-center rounded-2xl bg-carbon-info/10 text-carbon-info dark:bg-carbon-infoDark/10 dark:text-carbon-infoDark">
          <ion-icon name="sparkles-outline"></ion-icon>
        </span>
        <div>
          <p class="text-sm font-semibold">Assistente IA</p>
          <p class="text-xs text-carbon-textSubtle dark:text-carbon-textSubtleDark">Resumo, classificação e spam</p>
        </div>
      </div>
    </div>
    <div class="rounded-3xl border border-carbon-border bg-carbon-layer p-4 dark:border-carbon-borderDark dark:bg-carbon-layerDark">
      <div id="lottie-mail" class="h-24"></div>
      <p class="mt-3 text-sm text-carbon-textSubtle dark:text-carbon-textSubtleDark">Fila de processamento sincronizando eventos inbound, sessões e auditoria.</p>
    </div>
  </div>
</aside>
