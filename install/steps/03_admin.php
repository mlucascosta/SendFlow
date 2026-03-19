<?php

declare(strict_types=1);

$admin = $old['admin'] ?? [];
?>
<div class="space-y-6">
  <div>
    <p class="text-sm uppercase tracking-[0.18em] text-red-600 font-semibold">Step 3</p>
    <h2 class="text-3xl font-bold mt-2">Create the first administrator</h2>
    <p class="text-neutral-500 mt-3">These credentials will be used to log into the SendFlow webmail after installation.</p>
  </div>

  <form method="post" class="space-y-4">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" name="action" value="save_admin">

    <div>
      <label class="block text-sm font-medium mb-2">Full name</label>
      <input class="w-full h-11 rounded-xl border border-neutral-300 px-3" name="name" value="<?= htmlspecialchars((string) ($admin['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required>
    </div>

    <div>
      <label class="block text-sm font-medium mb-2">Login email</label>
      <input class="w-full h-11 rounded-xl border border-neutral-300 px-3" name="email" type="email" value="<?= htmlspecialchars((string) ($admin['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required>
    </div>

    <div>
      <label class="block text-sm font-medium mb-2">Password</label>
      <input class="w-full h-11 rounded-xl border border-neutral-300 px-3" name="password" type="password" required>
      <p class="text-xs text-neutral-500 mt-2">Use at least 12 characters with uppercase, lowercase and a number.</p>
    </div>

    <div class="rounded-2xl border border-neutral-200 p-4 text-sm text-neutral-500">
      <p class="font-semibold text-neutral-700 mb-2">Security guidance</p>
      <ul class="list-disc ml-5 space-y-1">
        <li>The password is hashed with PHP `password_hash()` before storage.</li>
        <li>The installer creates the first admin only once.</li>
        <li>The email will become the initial SendFlow login identity.</li>
      </ul>
    </div>

    <div class="flex justify-between">
      <a href="/install/index.php?step=2" class="inline-flex items-center gap-2 rounded-xl border border-neutral-300 px-4 py-3 font-semibold">Back</a>
      <button class="inline-flex items-center gap-2 rounded-xl bg-red-600 px-5 py-3 text-white font-semibold" type="submit">
        Save administrator
        <ion-icon name="person-circle-outline"></ion-icon>
      </button>
    </div>
  </form>
</div>
