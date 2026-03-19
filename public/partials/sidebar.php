<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="sidebar fixed md:static inset-y-0 left-0 z-30 bg-white dark:bg-neutral-800 border-r border-neutral-200 dark:border-neutral-700 transform transition-transform duration-300 md:translate-x-0">
  <div class="p-3 space-y-1 mt-2">
    <a href="/emails/inbox" class="sidebar-link <?= ($currentPage ?? '')==='inbox' ? 'active' : '' ?>"><ion-icon name="mail-outline"></ion-icon>Caixa de Entrada</a>
    <a href="/emails/sent" class="sidebar-link <?= ($currentPage ?? '')==='sent' ? 'active' : '' ?>"><ion-icon name="send-outline"></ion-icon>Enviados</a>
    <a href="/emails/drafts" class="sidebar-link <?= ($currentPage ?? '')==='drafts' ? 'active' : '' ?>"><ion-icon name="document-text-outline"></ion-icon>Rascunhos</a>
    <a href="/dashboard" class="sidebar-link <?= ($currentPage ?? '')==='dashboard' ? 'active' : '' ?>"><ion-icon name="stats-chart-outline"></ion-icon>Dashboard</a>
    <a href="/dashboard/ai" class="sidebar-link <?= ($currentPage ?? '')==='ai-dashboard' ? 'active' : '' ?>"><ion-icon name="sparkles-outline"></ion-icon>AI Dashboard</a>
    <a href="/settings" class="sidebar-link <?= ($currentPage ?? '')==='settings' ? 'active' : '' ?>"><ion-icon name="settings-outline"></ion-icon>Configurações</a>
    <a href="/settings/ai" class="sidebar-link <?= ($currentPage ?? '')==='ai-settings' ? 'active' : '' ?>"><ion-icon name="hardware-chip-outline"></ion-icon>AI & Cron</a>
  </div>
</aside>
