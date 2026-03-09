<header class="bg-white dark:bg-neutral-800 border-b border-neutral-200 dark:border-neutral-700 sticky top-0 z-20">
  <div class="flex items-center justify-between h-16 px-4 md:px-6">
    <div class="flex items-center gap-3">
      <?php if (!empty($showSidebar)): ?>
      <button class="md:hidden p-2 rounded-lg hover:bg-neutral-100 dark:hover:bg-neutral-700" @click="sidebarOpen=!sidebarOpen" aria-label="Abrir menu">
        <ion-icon name="menu"></ion-icon>
      </button>
      <?php endif; ?>
      <a href="/dashboard" class="flex items-center gap-2">
        <span class="w-8 h-8 rounded-lg bg-primary-600 text-white grid place-items-center"><ion-icon name="send"></ion-icon></span>
        <span class="font-semibold hidden sm:block">SendFlow</span>
      </a>
    </div>
    <div class="flex items-center gap-2">
      <?php if (!empty($showCompose)): ?><a href="/emails/compose" class="btn-primary hidden md:inline-flex"> <ion-icon name="create-outline"></ion-icon> Novo Email</a><?php endif; ?>
      <button @click="toggleTheme()" class="p-2 rounded-lg hover:bg-neutral-100 dark:hover:bg-neutral-700" aria-label="Alternar tema">
        <ion-icon :name="currentTheme === 'dark' ? 'sunny-outline' : 'moon-outline'"></ion-icon>
      </button>
      <div x-data="{open:false}" class="relative">
        <button @click="open=!open" class="p-1.5 rounded-lg hover:bg-neutral-100 dark:hover:bg-neutral-700 flex items-center gap-2">
          <span class="w-8 h-8 rounded-full bg-primary-100 dark:bg-primary-900/30 text-primary-600 grid place-items-center text-sm font-semibold"><?= htmlspecialchars(substr($userName ?? 'U',0,1),ENT_QUOTES,'UTF-8') ?></span>
        </button>
        <div x-show="open" @click.away="open=false" class="absolute right-0 mt-2 w-56 bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-xl shadow-lg py-2">
          <a href="/settings" class="sidebar-link"><ion-icon name="settings-outline"></ion-icon>Configurações</a>
          <a href="/settings/sessions" class="sidebar-link"><ion-icon name="phone-portrait-outline"></ion-icon>Sessões</a>
          <a href="/logout" class="sidebar-link text-red-500"><ion-icon name="log-out-outline"></ion-icon>Sair</a>
        </div>
      </div>
    </div>
  </div>
</header>
