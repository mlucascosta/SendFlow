<?php $currentPage='ai-dashboard'; ?>
<div class="space-y-6">
  <div>
    <h1 class="text-2xl font-bold">AI Dashboard</h1>
    <p class="text-neutral-500">Centralized metrics for Groq usage, fallback health, spam checks, summaries and scheduler automation.</p>
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
    <?php
      $cards = [
        ['label' => 'AI Requests (24h)', 'value' => '0', 'hint' => 'Inactive until Groq API key is saved'],
        ['label' => 'Fallback Activations', 'value' => '0', 'hint' => 'Model switching after limit/capacity'],
        ['label' => 'Summaries Generated', 'value' => '0', 'hint' => 'Pre-open email summaries'],
        ['label' => 'Scheduled Jobs', 'value' => '0', 'hint' => 'Managed by cron-job.org'],
      ];
    ?>
    <?php foreach ($cards as $card): ?>
      <div class="card">
        <p class="text-sm text-neutral-500"><?= htmlspecialchars($card['label'], ENT_QUOTES, 'UTF-8') ?></p>
        <p class="text-3xl font-bold mt-2"><?= htmlspecialchars($card['value'], ENT_QUOTES, 'UTF-8') ?></p>
        <p class="text-xs text-neutral-500 mt-2"><?= htmlspecialchars($card['hint'], ENT_QUOTES, 'UTF-8') ?></p>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="grid lg:grid-cols-3 gap-4">
    <div class="card lg:col-span-2 space-y-3">
      <h2 class="font-semibold">Feature Coverage</h2>
      <div class="space-y-2 text-sm">
        <div class="flex items-center justify-between"><span>Email summaries</span><span class="text-neutral-500">Groq optional</span></div>
        <div class="flex items-center justify-between"><span>Spam validation</span><span class="text-neutral-500">Guard models + fallback</span></div>
        <div class="flex items-center justify-between"><span>Task extraction</span><span class="text-neutral-500">Compound / Qwen fallback</span></div>
        <div class="flex items-center justify-between"><span>Smart labels</span><span class="text-neutral-500">Scout / mini fallback</span></div>
        <div class="flex items-center justify-between"><span>Send-time recommendation</span><span class="text-neutral-500">Optional scheduling intelligence</span></div>
      </div>
    </div>

    <div class="card space-y-3">
      <h2 class="font-semibold">Operational Status</h2>
      <div class="rounded-xl bg-neutral-100 dark:bg-neutral-800 p-3 text-sm text-neutral-500">
        <p>Provider state: disabled by default</p>
        <p class="mt-2">Enablement requires:</p>
        <ul class="list-disc ml-5 mt-2 space-y-1">
          <li>Groq API key saved in DB</li>
          <li>cron-job.org API key saved in DB</li>
          <li>feature flags enabled per workflow</li>
        </ul>
      </div>
      <a class="btn-ghost w-full" href="/settings/ai">Open AI settings</a>
    </div>
  </div>
</div>
