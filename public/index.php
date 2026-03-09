<?php
declare(strict_types=1);

/**
 * Front controller e roteador simples do SendFlow.
 * SendFlow front controller and simple router.
 *
 * Mantém o ponto de entrada único e seleciona a view
 * com base na rota requisitada.
 * Keeps a single entry point and selects the target view
 * based on the requested route.
 */
$route = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

/** @var array<string,array{view:string,sidebar:bool,compose:bool,title:string}> $routes */
$routes = [
    '/' => ['view' => __DIR__ . '/../views/auth/login.php', 'sidebar' => false, 'compose' => false, 'title' => 'Login - SendFlow'],
    '/login' => ['view' => __DIR__ . '/../views/auth/login.php', 'sidebar' => false, 'compose' => false, 'title' => 'Login - SendFlow'],
    '/dashboard' => ['view' => __DIR__ . '/../views/dashboard/index.php', 'sidebar' => true, 'compose' => true, 'title' => 'Dashboard - SendFlow'],
    '/emails/inbox' => ['view' => __DIR__ . '/../views/emails/inbox.php', 'sidebar' => true, 'compose' => true, 'title' => 'Inbox - SendFlow'],
    '/emails/sent' => ['view' => __DIR__ . '/../views/emails/sent.php', 'sidebar' => true, 'compose' => true, 'title' => 'Enviados - SendFlow'],
    '/emails/drafts' => ['view' => __DIR__ . '/../views/emails/drafts.php', 'sidebar' => true, 'compose' => true, 'title' => 'Rascunhos - SendFlow'],
    '/emails/compose' => ['view' => __DIR__ . '/../views/emails/compose.php', 'sidebar' => true, 'compose' => true, 'title' => 'Compor - SendFlow'],
    '/settings' => ['view' => __DIR__ . '/../views/settings/index.php', 'sidebar' => true, 'compose' => true, 'title' => 'Configurações - SendFlow'],
    '/settings/resend' => ['view' => __DIR__ . '/../views/settings/resend.php', 'sidebar' => true, 'compose' => true, 'title' => 'Resend - SendFlow'],
    '/settings/sessions' => ['view' => __DIR__ . '/../views/settings/sessions.php', 'sidebar' => true, 'compose' => true, 'title' => 'Sessões - SendFlow'],
    '/settings/audit' => ['view' => __DIR__ . '/../views/settings/audit.php', 'sidebar' => true, 'compose' => true, 'title' => 'Auditoria - SendFlow'],
];

if (!isset($routes[$route])) {
    http_response_code(404);
    echo '<h1>404</h1>';
    exit;
}

$config = $routes[$route];
$showSidebar = $config['sidebar'];
$showCompose = $config['compose'];
$pageTitle = $config['title'];
$userName = 'Admin';
$userEmail = 'admin@sendflow.local';

ob_start();
require $config['view'];
$content = (string) ob_get_clean();
?>
<!DOCTYPE html>
<html lang="pt-BR" class="light" x-data="themeSwitcher()" x-init="initTheme()">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SendFlow - Webmail Self-Hosted com Resend">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={darkMode:'class'};</script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js"></script>
    <script src="https://unpkg.com/htmx.org@1.9.10"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="/assets/css/custom.css">
    <script>
        (function(){
            const savedTheme = localStorage.getItem('sendflow_theme');
            const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = savedTheme || (systemPrefersDark ? 'dark' : 'light');
            localStorage.setItem('sendflow_theme', theme);
            document.documentElement.classList.toggle('dark', theme === 'dark');
        })();
    </script>
</head>
<body class="bg-neutral-50 dark:bg-neutral-900 text-neutral-900 dark:text-neutral-100 transition-colors duration-300">
<div class="min-h-screen flex flex-col">
    <?php include __DIR__ . '/partials/header.php'; ?>
    <div class="flex flex-1 overflow-hidden">
        <?php if ($showSidebar): include __DIR__ . '/partials/sidebar.php'; endif; ?>
        <main class="flex-1 overflow-y-auto p-4 md:p-6 lg:p-8" id="main-content" hx-target="#main-content" hx-swap="innerHTML">
            <?= $content ?>
        </main>
    </div>
    <?php include __DIR__ . '/partials/footer.php'; ?>
    <?php include __DIR__ . '/partials/components/toast.php'; ?>
</div>
<script src="/assets/js/app.js"></script>
</body>
</html>
