<?php

declare(strict_types=1);

$resend = $old['resend'] ?? [];
?>
<div class="space-y-6">
  <div>
    <p class="text-sm uppercase tracking-[0.18em] text-red-600 font-semibold">Step 5</p>
    <h2 class="text-3xl font-bold mt-2">Connect Resend</h2>
    <p class="text-neutral-500 mt-3">Save the sending domain and API key that SendFlow should use for mail delivery. The key is stored encrypted in your database.</p>
  </div>

  <form method="post" class="space-y-4">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" name="action" value="save_resend">

    <div>
      <label class="block text-sm font-medium mb-2">Resend domain</label>
      <input class="w-full h-11 rounded-xl border border-neutral-300 px-3" name="domain" value="<?= htmlspecialchars((string) ($resend['domain'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="mail.example.com" required>
    </div>

    <div>
      <label class="block text-sm font-medium mb-2">Resend API key</label>
      <input class="w-full h-11 rounded-xl border border-neutral-300 px-3" name="api_key" value="" placeholder="re_..." required>
      <p class="text-xs text-neutral-500 mt-2">The installer validates the key shape and stores it encrypted with the application key.</p>
    </div>

    <div class="rounded-2xl border border-neutral-200 p-4 text-sm text-neutral-500">
      <p class="font-semibold text-neutral-700 mb-2">What is saved</p>
      <ul class="list-disc ml-5 space-y-1">
        <li>The Resend domain is saved for the admin user.</li>
        <li>The API key is encrypted before it is stored.</li>
        <li>The last 4 characters are stored separately for UI display and auditing.</li>
      </ul>
    </div>

    <div class="flex justify-between">
      <a href="/install/index.php?step=4" class="inline-flex items-center gap-2 rounded-xl border border-neutral-300 px-4 py-3 font-semibold">Back</a>
      <button class="inline-flex items-center gap-2 rounded-xl bg-red-600 px-5 py-3 text-white font-semibold" type="submit">
        Save Resend settings
        <ion-icon name="paper-plane-outline"></ion-icon>
      </button>
    </div>
  </form>
</div>
