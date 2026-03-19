<?php

declare(strict_types=1);
?>
<div class="space-y-6">
  <div>
    <p class="text-sm uppercase tracking-[0.18em] text-red-600 font-semibold">Step 1</p>
    <h2 class="text-3xl font-bold mt-2">Welcome to SendFlow</h2>
    <p class="text-neutral-500 mt-3">This setup wizard will walk you through database access, automatic migration execution, administrator creation, and secure Resend onboarding.</p>
  </div>

  <div class="grid md:grid-cols-2 gap-4">
    <div class="rounded-2xl border border-neutral-200 p-4">
      <h3 class="font-semibold">What happens in this wizard</h3>
      <ul class="list-disc ml-5 mt-3 text-sm text-neutral-500 space-y-1">
        <li>Connect to a MySQL/MariaDB database or leave the fields empty to use SQLite.</li>
        <li>Run the correct migrations automatically for the chosen driver.</li>
        <li>Create the first SendFlow admin account.</li>
        <li>Store the Resend domain and API key in your own database.</li>
      </ul>
    </div>
    <div class="rounded-2xl border border-neutral-200 p-4">
      <h3 class="font-semibold">Security defaults</h3>
      <ul class="list-disc ml-5 mt-3 text-sm text-neutral-500 space-y-1">
        <li>CSRF-protected installation forms.</li>
        <li>Password hashing for the first admin user.</li>
        <li>Encrypted storage for the Resend API key.</li>
        <li>SQLite fallback stored under <code>storage/database</code> with restrictive permissions.</li>
      </ul>
    </div>
  </div>

  <div class="flex justify-end">
    <a href="/install/index.php?step=2" class="inline-flex items-center gap-2 rounded-xl bg-red-600 px-5 py-3 text-white font-semibold">
      Start setup
      <ion-icon name="arrow-forward-outline"></ion-icon>
    </a>
  </div>
</div>
