<div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden">
    <div class="p-4 border-b border-slate-200 dark:border-slate-800 flex items-center gap-2">
        <input class="h-10 flex-1 rounded-xl border border-slate-300 dark:border-slate-700 bg-transparent px-3" placeholder="Buscar emails...">
        <button class="h-10 px-4 rounded-xl bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-900">Filtrar</button>
    </div>
    <ul>
        <?php for ($i = 0; $i < 7; $i++): ?>
            <li class="px-4 py-3 border-b last:border-b-0 border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800/70">
                <div class="flex justify-between text-sm">
                    <strong>Cliente <?= $i + 1 ?></strong>
                    <span class="text-slate-500">09:<?= 10 + $i ?></span>
                </div>
                <p class="text-sm text-slate-600 dark:text-slate-300">Atualização do projeto SendFlow e status de entrega.</p>
            </li>
        <?php endfor; ?>
    </ul>
</div>

<div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-3">
    <div class="h-16 skeleton bg-slate-200 dark:bg-slate-800"></div>
    <div class="h-16 skeleton bg-slate-200 dark:bg-slate-800"></div>
    <div class="h-16 skeleton bg-slate-200 dark:bg-slate-800"></div>
    <div class="h-16 skeleton bg-slate-200 dark:bg-slate-800"></div>
</div>
