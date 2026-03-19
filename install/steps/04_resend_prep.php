<?php

declare(strict_types=1);
?>
<div class="space-y-6">
  <div>
    <p class="text-sm uppercase tracking-[0.18em] text-red-600 font-semibold">Step 4</p>
    <h2 class="text-3xl font-bold mt-2">How SendFlow works</h2>
    <p class="text-neutral-500 mt-3">Before connecting Resend, here is the onboarding tour for how the project is structured and why each credential matters.</p>
  </div>

  <div class="grid md:grid-cols-2 gap-4">
    <div class="rounded-2xl border border-neutral-200 p-4">
      <h3 class="font-semibold">Mail security first</h3>
      <p class="text-sm text-neutral-500 mt-2">Inbound mail is protected by webhook verification, explicit mailbox rules, `noreply` blocking and reply-only controls.</p>
    </div>
    <div class="rounded-2xl border border-neutral-200 p-4">
      <h3 class="font-semibold">Your database stays in control</h3>
      <p class="text-sm text-neutral-500 mt-2">Database credentials, admin account metadata and Resend credentials stay in your own MySQL/MariaDB environment.</p>
    </div>
    <div class="rounded-2xl border border-neutral-200 p-4">
      <h3 class="font-semibold">AI is optional</h3>
      <p class="text-sm text-neutral-500 mt-2">Groq stays disabled until the user explicitly saves a key in the database and enables the desired features.</p>
    </div>
    <div class="rounded-2xl border border-neutral-200 p-4">
      <h3 class="font-semibold">Scheduler is optional</h3>
      <p class="text-sm text-neutral-500 mt-2">cron-job.org is only activated when the user adds its API key, so the app never blocks if the scheduler is not configured yet.</p>
    </div>
  </div>

  <form method="post" class="flex justify-between">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" name="action" value="continue_to_resend">
    <a href="/install/index.php?step=3" class="inline-flex items-center gap-2 rounded-xl border border-neutral-300 px-4 py-3 font-semibold">Back</a>
    <button class="inline-flex items-center gap-2 rounded-xl bg-red-600 px-5 py-3 text-white font-semibold" type="submit">
      Continue to Resend setup
      <ion-icon name="mail-open-outline"></ion-icon>
    </button>
  </form>
</div>
