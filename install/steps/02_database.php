<?php

declare(strict_types=1);

$db = $old['database'] ?? [];
$databaseMode = (string) ($old['database_mode'] ?? 'mysql');
?>
<div class="space-y-6">
  <div>
    <p class="text-sm uppercase tracking-[0.18em] text-red-600 font-semibold">Step 2</p>
    <h2 class="text-3xl font-bold mt-2">Connect your database or continue with SQLite</h2>
    <p class="text-neutral-500 mt-3">If you leave the MySQL/MariaDB fields empty, SendFlow will create and use a protected SQLite database automatically for local tests and quick trials.</p>
  </div>

  <?php if ($databaseMode === 'sqlite'): ?>
    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">
      <p class="font-semibold">SQLite mode selected</p>
      <p class="mt-2">The installer will store the database file inside <code>storage/database/sendflow.db</code> with restrictive permissions and outside the public web root.</p>
    </div>
  <?php endif; ?>

  <form method="post" class="space-y-4">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" name="action" value="save_database">

    <div class="grid md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium mb-2">Host</label>
        <input class="w-full h-11 rounded-xl border border-neutral-300 px-3" name="host" value="<?= htmlspecialchars((string) ($db['host'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="127.0.0.1">
      </div>
      <div>
        <label class="block text-sm font-medium mb-2">Port</label>
        <input class="w-full h-11 rounded-xl border border-neutral-300 px-3" name="port" type="number" min="1" max="65535" value="<?= htmlspecialchars((string) (($db['port'] ?? '') === 0 ? '' : ($db['port'] ?? '')), ENT_QUOTES, 'UTF-8') ?>" placeholder="3306">
      </div>
      <div>
        <label class="block text-sm font-medium mb-2">Database name</label>
        <input class="w-full h-11 rounded-xl border border-neutral-300 px-3" name="database" value="<?= htmlspecialchars((string) ($db['database'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="sendflow_db">
      </div>
      <div>
        <label class="block text-sm font-medium mb-2">Username</label>
        <input class="w-full h-11 rounded-xl border border-neutral-300 px-3" name="username" value="<?= htmlspecialchars((string) ($db['username'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="sendflow_user">
      </div>
    </div>

    <div>
      <label class="block text-sm font-medium mb-2">Password</label>
      <input class="w-full h-11 rounded-xl border border-neutral-300 px-3" name="password" type="password" placeholder="Optional for SQLite fallback">
    </div>

    <div class="rounded-2xl border border-neutral-200 p-4 text-sm text-neutral-500">
      <p class="font-semibold text-neutral-700 mb-2">Installer notes</p>
      <ul class="list-disc ml-5 space-y-1">
        <li>The MySQL/MariaDB database should already exist if you choose that route.</li>
        <li>Leaving every field empty makes the wizard continue naturally with SQLite.</li>
        <li>The SQLite <code>.db</code> file is created under <code>storage/database</code>, not inside <code>public/</code>.</li>
      </ul>
    </div>

    <div class="flex justify-between">
      <a href="/install/index.php?step=1" class="inline-flex items-center gap-2 rounded-xl border border-neutral-300 px-4 py-3 font-semibold">Back</a>
      <button class="inline-flex items-center gap-2 rounded-xl bg-red-600 px-5 py-3 text-white font-semibold" type="submit">
        Continue installation
        <ion-icon name="server-outline"></ion-icon>
      </button>
    </div>
  </form>
</div>
