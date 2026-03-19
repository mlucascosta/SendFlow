<?php

declare(strict_types=1);

$admin = $old['admin'] ?? [];
$resend = $old['resend'] ?? [];
?>
<div class="space-y-6">
  <div>
    <p class="text-sm uppercase tracking-[0.18em] text-emerald-600 font-semibold">Step 6</p>
    <h2 class="text-3xl font-bold mt-2">SendFlow is ready</h2>
    <p class="text-neutral-500 mt-3">Your database migrations were executed, the admin account was created and Resend credentials were stored in your database.</p>
  </div>

  <div class="grid md:grid-cols-2 gap-4">
    <div class="rounded-2xl border border-neutral-200 p-4">
      <h3 class="font-semibold">Admin login</h3>
      <p class="text-sm text-neutral-500 mt-2"><?= htmlspecialchars((string) ($admin['email'] ?? 'Not available'), ENT_QUOTES, 'UTF-8') ?></p>
    </div>
    <div class="rounded-2xl border border-neutral-200 p-4">
      <h3 class="font-semibold">Resend domain</h3>
      <p class="text-sm text-neutral-500 mt-2"><?= htmlspecialchars((string) ($resend['domain'] ?? 'Not available'), ENT_QUOTES, 'UTF-8') ?></p>
    </div>
  </div>

  <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">
    <p class="font-semibold">Next steps</p>
    <ul class="list-disc ml-5 mt-2 space-y-1">
      <li>Log in with the admin email and password you just created.</li>
      <li>Verify your Resend domain and inbound rules before opening the app to external traffic.</li>
      <li>Optionally configure Groq AI and cron-job.org from the new AI settings area.</li>
    </ul>
  </div>

  <div class="flex justify-end">
    <a href="/login" class="inline-flex items-center gap-2 rounded-xl bg-red-600 px-5 py-3 text-white font-semibold">
      Go to SendFlow login
      <ion-icon name="log-in-outline"></ion-icon>
    </a>
  </div>
</div>
