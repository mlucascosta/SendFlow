<?php declare(strict_types=1); ?>
<!doctype html>
<html lang="pt-BR" x-data="themeApp()" x-init="init()" :class="dark ? 'dark' : ''">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title ?? 'SendFlow', ENT_QUOTES, 'UTF-8') ?></title>

    <script>
        (function () {
            const storedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const resolvedTheme = storedTheme ?? (prefersDark ? 'dark' : 'light');

            localStorage.setItem('theme', resolvedTheme);
            if (resolvedTheme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class' };
    </script>
    <link rel="stylesheet" href="/assets/css/custom.css">

    <script src="https://unpkg.com/htmx.org@1.9.10"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js"></script>
</head>
<body class="bg-slate-100 text-slate-900 dark:bg-slate-950 dark:text-slate-100">
