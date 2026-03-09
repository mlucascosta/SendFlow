<?php $currentPage='inbox'; ?>
<div class="space-y-4" x-data="{selectedEmails:[]}">
  <div class="flex items-center justify-between"><h1 class="text-2xl font-bold">Caixa de Entrada</h1><input class="input-field max-w-xs" placeholder="Buscar emails..."></div>
  <div class="card p-0 overflow-hidden">
    <?php for($i=1;$i<=8;$i++): ?>
      <div class="email-row group flex items-center gap-3 p-4 border-b border-neutral-200 dark:border-neutral-700 <?= $i<3?'unread':'' ?>">
        <input type="checkbox" x-model="selectedEmails" value="<?= $i ?>">
        <div class="flex-1 min-w-0"><p class="font-medium truncate">Remetente <?= $i ?></p><p class="text-sm text-neutral-500 truncate">Assunto de exemplo <?= $i ?></p></div>
        <span class="text-xs text-neutral-400">Hoje</span>
      </div>
    <?php endfor; ?>
  </div>
</div>
