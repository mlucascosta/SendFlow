<?php declare(strict_types=1); require __DIR__ . '/head.php'; ?>
<div class="min-h-screen flex">
    <aside class="w-72 border-r border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-4 hidden md:block">
        <div class="flex items-center gap-3 mb-6">
            <div class="h-10 w-10 rounded-xl bg-slate-900 text-white dark:bg-slate-200 dark:text-slate-900 grid place-items-center">
                <ion-icon name="paper-plane"></ion-icon>
            </div>
            <div>
                <p class="font-semibold">SendFlow</p>
                <p class="text-xs text-slate-500">Elegant Mail</p>
            </div>
        </div>

        <nav class="space-y-1">
            <a class="sidebar-link hover:bg-slate-100 dark:hover:bg-slate-800 <?= $active === 'dashboard' ? 'bg-slate-100 dark:bg-slate-800' : '' ?>" href="/dashboard"><ion-icon name="grid-outline"></ion-icon>Dashboard</a>
            <a class="sidebar-link hover:bg-slate-100 dark:hover:bg-slate-800 <?= $active === 'inbox' ? 'bg-slate-100 dark:bg-slate-800' : '' ?>" href="/emails/inbox"><ion-icon name="mail-outline"></ion-icon>Inbox</a>
            <a class="sidebar-link hover:bg-slate-100 dark:hover:bg-slate-800 <?= $active === 'compose' ? 'bg-slate-100 dark:bg-slate-800' : '' ?>" href="/email/compose"><ion-icon name="create-outline"></ion-icon>Compose</a>
            <a class="sidebar-link hover:bg-slate-100 dark:hover:bg-slate-800 <?= $active === 'settings' ? 'bg-slate-100 dark:bg-slate-800' : '' ?>" href="/settings"><ion-icon name="settings-outline"></ion-icon>Settings</a>
        </nav>

        <div class="mt-8 p-4 rounded-2xl bg-slate-100 dark:bg-slate-800">
            <div id="lottie-mail" class="h-24"></div>
            <p class="text-xs text-slate-600 dark:text-slate-300">Syncing webhooks and delivery statuses.</p>
        </div>
    </aside>

    <main class="flex-1">
        <header class="h-16 border-b border-slate-200 dark:border-slate-800 bg-white/95 dark:bg-slate-900/95 backdrop-blur px-4 md:px-8 flex items-center justify-between">
            <h1 class="font-semibold text-lg"><?= htmlspecialchars($pageTitle ?? 'SendFlow', ENT_QUOTES, 'UTF-8') ?></h1>
            <div class="flex items-center gap-2">
                <button @click="toggleTheme()" class="h-10 w-10 rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800">
                    <ion-icon :name="dark ? 'sunny-outline' : 'moon-outline'"></ion-icon>
                </button>
                <button class="px-3 h-10 rounded-xl border border-slate-200 dark:border-slate-700 text-sm">Caio Admin</button>
            </div>
        </header>

        <section class="p-4 md:p-8 animate__animated animate__fadeIn">
            <?php require $contentView; ?>
        </section>
    </main>
</div>
<?php require __DIR__ . '/footer.php'; ?>
