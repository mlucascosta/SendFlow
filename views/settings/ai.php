<?php $currentPage='ai-settings'; ?>
<div class="space-y-6">
  <div>
    <h1 class="text-2xl font-bold">AI & Scheduler Settings</h1>
    <p class="text-neutral-500">Groq and cron-job.org are optional and stay disabled until an API key is saved in the database.</p>
  </div>

  <div class="grid lg:grid-cols-2 gap-4">
    <div class="card space-y-4">
      <div>
        <h2 class="font-semibold">Groq Provider</h2>
        <p class="text-sm text-neutral-500">Email summaries, smart labels, spam checks, task extraction and send-time suggestions.</p>
      </div>

      <div class="space-y-2">
        <label class="text-sm font-medium">Groq API key</label>
        <input class="input-field" placeholder="gsk_..." />
      </div>

      <div class="space-y-2">
        <label class="text-sm font-medium">Default model</label>
        <select class="input-field">
          <option>openai/gpt-oss-20b</option>
          <option>groq/compound-mini</option>
          <option>groq/compound</option>
          <option>meta-llama/llama-guard-4-12b</option>
        </select>
      </div>

      <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-3 text-sm text-neutral-500">
        <p>Fallback chain defaults:</p>
        <ul class="list-disc ml-5 mt-2 space-y-1">
          <li>Summaries: compound-mini → gpt-oss-20b → llama-3.1-8b-instant</li>
          <li>Spam guard: llama-guard-4-12b → gpt-oss-safeguard-20b → prompt-guard-2-86m</li>
          <li>Tasks: compound → qwen3-32b → gpt-oss-20b</li>
        </ul>
      </div>

      <button class="btn-primary">Save Groq settings</button>
    </div>

    <div class="card space-y-4">
      <div>
        <h2 class="font-semibold">cron-job.org Scheduler</h2>
        <p class="text-sm text-neutral-500">All recurring jobs should be provisioned through cron-job.org after the API key is stored in the database.</p>
      </div>

      <div class="space-y-2">
        <label class="text-sm font-medium">cron-job.org API key</label>
        <input class="input-field" placeholder="Bearer token" />
      </div>

      <div class="space-y-2">
        <label class="text-sm font-medium">Managed jobs</label>
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-3 space-y-2 text-sm text-neutral-500">
          <div>• webhook reconciliation</div>
          <div>• inbound policy cleanup</div>
          <div>• AI summaries backfill</div>
          <div>• scheduled send dispatcher</div>
        </div>
      </div>

      <button class="btn-primary">Save scheduler settings</button>
    </div>
  </div>
</div>
