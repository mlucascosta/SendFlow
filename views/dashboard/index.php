<?php $currentPage='dashboard'; ?>
<div class="space-y-6">
  <div><h1 class="text-2xl font-bold">Dashboard</h1><p class="text-neutral-500">Visão geral da sua conta</p></div>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <?php for($i=0;$i<4;$i++): ?><div class="card"><p class="text-sm text-neutral-500">Métrica <?= $i+1 ?></p><p class="text-3xl font-bold mt-2"><?= [1240,980,312,67][$i] ?></p></div><?php endfor; ?>
  </div>
  <div class="grid lg:grid-cols-3 gap-4">
    <div class="card lg:col-span-2">
      <h2 class="font-semibold mb-3">Emails Recentes</h2>
      <?php for($i=0;$i<5;$i++): ?><div class="email-row flex items-center justify-between p-3 border-b border-neutral-200 dark:border-neutral-700"><span>Cliente <?= $i+1 ?></span><span class="text-xs text-neutral-500">09:<?= 10+$i ?></span></div><?php endfor; ?>
    </div>
    <div class="card"><h2 class="font-semibold mb-3">Ações Rápidas</h2><div class="space-y-2"><a class="btn-ghost w-full" href="/emails/compose">Novo Email</a><a class="btn-ghost w-full" href="/settings/resend">Configurar Resend</a></div></div>
  </div>
</div>
