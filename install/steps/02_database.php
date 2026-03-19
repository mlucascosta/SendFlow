<?php

declare(strict_types=1);

$db = $old['database'] ?? [];
?>
<div class="space-y-6">
  <div>
    <p class="text-sm uppercase tracking-[0.18em] text-red-600 font-semibold">Step 2</p>
    <h2 class="text-3xl font-bold mt-2">Connect your MySQL / MariaDB database</h2>
    <p class="text-neutral-500 mt-3">As soon as this connection is validated, SendFlow will run every SQL migration automatically and write `config/env.php` for the current installation.</p>
  </div>

  <form method="post" class="space-y-4">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" name="action" value="save_database">

    <div class="grid md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium mb-2">Host</label>
        <input class="w-full h-11 rounded-xl border border-neutral-300 px-3" name="host" value="<?= htmlspecialchars((string) ($db['host'] ?? '127.0.0.1'), ENT_QUOTES, 'UTF-8') ?>" required>
      </div>
      <div>
        <label class="block text-sm font-medium mb-2">Port</label>
        <input class="w-full h-11 rounded-xl border border-neutral-300 px-3" name="port" type="number" min="1" max="65535" value="<?= htmlspecialchars((string) ($db['port'] ?? 3306), ENT_QUOTES, 'UTF-8') ?>" required>
      </div>
      <div>
        <label class="block text-sm font-medium mb-2">Database name</label>
        <input class="w-full h-11 rounded-xl border border-neutral-300 px-3" name="database" value="<?= htmlspecialchars((string) ($db['database'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required>
      </div>
      <div>
        <label class="block text-sm font-medium mb-2">Username</label>
        <input class="w-full h-11 rounded-xl border border-neutral-300 px-3" name="username" value="<?= htmlspecialchars((string) ($db['username'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required>
      </div>
    </div>

    <div>
      <label class="block text-sm font-medium mb-2">Password</label>
      <input class="w-full h-11 rounded-xl border border-neutral-300 px-3" name="password" type="password">
    </div>

    <div class="rounded-2xl border border-neutral-200 p-4 text-sm text-neutral-500">
      <p class="font-semibold text-neutral-700 mb-2">Installer notes</p>
      <ul class="list-disc ml-5 space-y-1">
        <li>The database should already exist.</li>
        <li>SendFlow uses `mysql:` PDO connections for MySQL/MariaDB only.</li>
        <li>Migrations are tracked in `schema_migrations` to avoid duplicate execution.</li>
      </ul>
    </div>

    <div class="flex justify-between">
      <a href="/install/index.php?step=1" class="inline-flex items-center gap-2 rounded-xl border border-neutral-300 px-4 py-3 font-semibold">Back</a>
      <button class="inline-flex items-center gap-2 rounded-xl bg-red-600 px-5 py-3 text-white font-semibold" type="submit">
        Validate connection & run migrations
        <ion-icon name="server-outline"></ion-icon>
      </button>
    </div>
  </form>
</div>
