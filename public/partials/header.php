<header class="sticky top-0 z-40 border-b border-carbon-border/70 bg-carbon-bg/90 backdrop-blur dark:border-carbon-borderDark/60 dark:bg-carbon-bgDark/85">
  <div class="mx-auto flex h-[4.5rem] max-w-7xl items-center justify-between gap-4 px-4 md:px-6 lg:px-8">
    <div class="flex items-center gap-3">
      <?php if (!empty($showSidebar)): ?>
      <button class="inline-flex h-11 w-11 items-center justify-center rounded-xl border border-carbon-border bg-carbon-layer text-carbon-text hover:border-brand-primary hover:text-brand-primary dark:border-carbon-borderDark dark:bg-carbon-layerDark dark:text-carbon-textDark dark:hover:border-brand-primaryDark dark:hover:text-brand-primaryDark md:hidden" @click="sidebarOpen=!sidebarOpen" aria-label="Abrir menu">
        <ion-icon name="menu"></ion-icon>
      </button>
      <?php endif; ?>
      <a href="/dashboard" class="flex items-center gap-3 rounded-2xl px-1 py-1">
        <span class="grid h-11 w-11 place-items-center rounded-2xl bg-brand-primary text-white shadow-panel dark:bg-brand-primaryDark dark:text-carbon-bgDark">
          <ion-icon name="paper-plane"></ion-icon>
        </span>
        <span>
          <strong class="block text-base font-semibold tracking-tight">SendFlow</strong>
          <span class="block text-xs text-carbon-textSubtle dark:text-carbon-textSubtleDark">Inbox intelligence for self-hosted email</span>
        </span>
      </a>
    </div>

    <div class="hidden items-center gap-3 lg:flex">
      <div class="rounded-2xl border border-carbon-border bg-carbon-surface px-4 py-2 text-sm text-carbon-textSubtle shadow-sm dark:border-carbon-borderDark dark:bg-carbon-surfaceDark dark:text-carbon-textSubtleDark">
        <span class="font-medium text-carbon-text dark:text-carbon-textDark">Default-deny inbound</span>
        · Webhooks verificados · IA opcional
      </div>
    </div>

    <div class="flex items-center gap-2">
      <?php if (!empty($showCompose)): ?>
        <a href="/emails/compose" class="hidden h-11 items-center gap-2 rounded-xl bg-brand-primary px-4 text-sm font-medium text-white transition hover:bg-brand-secondary dark:bg-brand-primaryDark dark:text-carbon-bgDark dark:hover:bg-brand-secondaryDark md:inline-flex">
          <ion-icon name="create"></ion-icon>
          Novo email
        </a>
      <?php endif; ?>
      <button @click="toggleTheme()" class="inline-flex h-11 w-11 items-center justify-center rounded-xl border border-carbon-border bg-carbon-layer text-carbon-text hover:border-brand-primary hover:text-brand-primary dark:border-carbon-borderDark dark:bg-carbon-layerDark dark:text-carbon-textDark dark:hover:border-brand-primaryDark dark:hover:text-brand-primaryDark" aria-label="Alternar tema">
        <ion-icon :name="currentTheme === 'dark' ? 'sunny' : 'moon'"></ion-icon>
      </button>
      <div x-data="{open:false}" class="relative">
        <button @click="open=!open" class="flex items-center gap-3 rounded-2xl border border-carbon-border bg-carbon-layer px-3 py-2 text-left hover:border-brand-primary dark:border-carbon-borderDark dark:bg-carbon-layerDark dark:hover:border-brand-primaryDark">
          <span class="grid h-9 w-9 place-items-center rounded-full bg-brand-primary/10 font-semibold text-brand-primary dark:bg-brand-primaryDark/15 dark:text-brand-primaryDark"><?= htmlspecialchars(substr($userName ?? 'U',0,1),ENT_QUOTES,'UTF-8') ?></span>
          <span class="hidden sm:block">
            <strong class="block text-sm font-semibold leading-none"><?= htmlspecialchars($userName ?? 'Admin', ENT_QUOTES, 'UTF-8') ?></strong>
            <span class="text-xs text-carbon-textSubtle dark:text-carbon-textSubtleDark"><?= htmlspecialchars($userEmail ?? 'admin@sendflow.local', ENT_QUOTES, 'UTF-8') ?></span>
          </span>
          <ion-icon class="text-carbon-textSubtle dark:text-carbon-textSubtleDark" name="chevron-down"></ion-icon>
        </button>
        <div x-show="open" x-transition.origin.top.right @click.away="open=false" class="absolute right-0 mt-2 w-72 overflow-hidden rounded-2xl border border-carbon-border bg-carbon-layer shadow-panel dark:border-carbon-borderDark dark:bg-carbon-layerDark">
          <div class="border-b border-carbon-border px-4 py-4 dark:border-carbon-borderDark">
            <p class="text-sm font-semibold text-carbon-text dark:text-carbon-textDark"><?= htmlspecialchars($userName ?? 'Admin', ENT_QUOTES, 'UTF-8') ?></p>
            <p class="text-xs text-carbon-textSubtle dark:text-carbon-textSubtleDark"><?= htmlspecialchars($userEmail ?? 'admin@sendflow.local', ENT_QUOTES, 'UTF-8') ?></p>
          </div>
          <div class="p-2">
            <a href="/settings" class="sidebar-link"><ion-icon name="settings-outline"></ion-icon>Configurações</a>
            <a href="/settings/sessions" class="sidebar-link"><ion-icon name="phone-portrait-outline"></ion-icon>Sessões</a>
            <a href="/settings/audit" class="sidebar-link"><ion-icon name="shield-checkmark-outline"></ion-icon>Auditoria</a>
            <a href="/logout" class="sidebar-link !text-carbon-error dark:!text-carbon-errorDark"><ion-icon name="log-out-outline"></ion-icon>Sair</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>
