<?php

declare(strict_types=1);

$title = 'Login | SendFlow';
require __DIR__ . '/partials/head.php';
?>
<div class="min-h-screen grid lg:grid-cols-2">
    <section class="hidden lg:flex bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 p-12 flex-col justify-between">
        <div>
            <div class="h-11 w-11 rounded-xl bg-slate-900 text-white dark:bg-slate-200 dark:text-slate-900 grid place-items-center mb-6">
                <ion-icon name="paper-plane"></ion-icon>
            </div>
            <h1 class="text-4xl font-semibold leading-tight">Webmail elegante, rápido e minimalista.</h1>
            <p class="mt-4 text-slate-600 dark:text-slate-300 max-w-md">Inspirado no visual limpo do Resend e iCloud, com foco em produtividade e simplicidade.</p>
        </div>
        <div id="lottie-mail" class="h-56"></div>
    </section>

    <section class="flex items-center justify-center p-6">
        <div class="w-full max-w-md bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-8 shadow-sm">
            <h2 class="text-2xl font-semibold">Entrar no SendFlow</h2>
            <p class="text-sm text-slate-500 mt-1 mb-6">Acesse sua central de emails.</p>

            <form class="space-y-4" hx-post="/login" hx-target="#login-feedback" hx-swap="innerHTML">
                <div>
                    <label class="text-sm font-medium">Email</label>
                    <input class="mt-1 w-full h-11 rounded-xl border border-slate-300 dark:border-slate-700 bg-transparent px-3" type="email" placeholder="voce@dominio.com">
                </div>
                <div>
                    <label class="text-sm font-medium">Senha</label>
                    <input class="mt-1 w-full h-11 rounded-xl border border-slate-300 dark:border-slate-700 bg-transparent px-3" type="password" placeholder="••••••••">
                </div>
                <button class="w-full h-11 rounded-xl bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-900 font-medium">Entrar</button>
            </form>
            <div id="login-feedback" class="mt-4 text-sm text-slate-500">Demo visual do layout.</div>
        </div>
    </section>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
