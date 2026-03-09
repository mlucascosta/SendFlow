<?php $currentPage='settings'; ?>
<div class="card"><h1 class="text-2xl font-bold mb-3">Sessões Ativas</h1><?php for($i=0;$i<3;$i++): ?><div class="p-3 border-b border-neutral-200 dark:border-neutral-700">Dispositivo <?= $i+1 ?></div><?php endfor; ?></div>
