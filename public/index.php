<?php
declare(strict_types=1);

$route = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

/** @var array<string,array{view:string,sidebar:bool,compose:bool,title:string,section:string}> $routes */
$routes = [
    '/' => ['view' => __DIR__ . '/../views/auth/login.php', 'sidebar' => false, 'compose' => false, 'title' => 'Login - SendFlow', 'section' => 'login'],
    '/login' => ['view' => __DIR__ . '/../views/auth/login.php', 'sidebar' => false, 'compose' => false, 'title' => 'Login - SendFlow', 'section' => 'login'],
    '/dashboard' => ['view' => __DIR__ . '/../views/dashboard/index.php', 'sidebar' => true, 'compose' => true, 'title' => 'Dashboard - SendFlow', 'section' => 'dashboard'],
    '/emails/inbox' => ['view' => __DIR__ . '/../views/emails/inbox.php', 'sidebar' => true, 'compose' => true, 'title' => 'Inbox - SendFlow', 'section' => 'inbox'],
    '/emails/sent' => ['view' => __DIR__ . '/../views/emails/sent.php', 'sidebar' => true, 'compose' => true, 'title' => 'Enviados - SendFlow', 'section' => 'sent'],
    '/emails/drafts' => ['view' => __DIR__ . '/../views/emails/drafts.php', 'sidebar' => true, 'compose' => true, 'title' => 'Rascunhos - SendFlow', 'section' => 'drafts'],
    '/emails/compose' => ['view' => __DIR__ . '/../views/emails/compose.php', 'sidebar' => true, 'compose' => true, 'title' => 'Compor - SendFlow', 'section' => 'compose'],
    '/settings' => ['view' => __DIR__ . '/../views/settings/index.php', 'sidebar' => true, 'compose' => true, 'title' => 'Configurações - SendFlow', 'section' => 'settings'],
    '/settings/resend' => ['view' => __DIR__ . '/../views/settings/resend.php', 'sidebar' => true, 'compose' => true, 'title' => 'Resend - SendFlow', 'section' => 'settings'],
    '/settings/ai' => ['view' => __DIR__ . '/../views/settings/ai.php', 'sidebar' => true, 'compose' => true, 'title' => 'AI Settings - SendFlow', 'section' => 'settings'],
    '/settings/sessions' => ['view' => __DIR__ . '/../views/settings/sessions.php', 'sidebar' => true, 'compose' => true, 'title' => 'Sessões - SendFlow', 'section' => 'settings'],
    '/settings/audit' => ['view' => __DIR__ . '/../views/settings/audit.php', 'sidebar' => true, 'compose' => true, 'title' => 'Auditoria - SendFlow', 'section' => 'settings'],
    '/dashboard/ai' => ['view' => __DIR__ . '/../views/dashboard/ai.php', 'sidebar' => true, 'compose' => true, 'title' => 'AI Dashboard - SendFlow', 'section' => 'dashboard'],
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
$currentSection = $config['section'];
$userName = 'Admin';
$userEmail = 'admin@sendflow.local';

ob_start();
require $config['view'];
$content = (string) ob_get_clean();
?>
<!DOCTYPE html>
<html lang="pt-BR" class="light" x-data="themeSwitcher()" x-init="initTheme()" :data-theme="currentTheme">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SendFlow - Webmail self-hosted com inbound seguro, IA opcional e onboarding guiado.">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['IBM Plex Sans', 'sans-serif'],
                        mono: ['IBM Plex Mono', 'monospace'],
                    },
                    colors: {
                        brand: {
                            primary: '#0f62fe',
                            secondary: '#002d9c',
                            primaryDark: '#4589ff',
                            secondaryDark: '#a6c8ff',
                        },
                        carbon: {
                            bg: '#ffffff',
                            surface: '#f4f4f4',
                            layer: '#ffffff',
                            text: '#161616',
                            textSubtle: '#525252',
                            border: '#d9d9d9',
                            bgDark: '#161616',
                            surfaceDark: '#262626',
                            layerDark: '#393939',
                            textDark: '#f4f4f4',
                            textSubtleDark: '#c6c6c6',
                            borderDark: '#525252',
                            success: '#24a148',
                            successDark: '#42be65',
                            warning: '#f1c21b',
                            warningDark: '#ffe97b',
                            error: '#da1e28',
                            errorDark: '#ff8389',
                            info: '#0043ce',
                            infoDark: '#78a9ff'
                        }
                    },
                    boxShadow: {
                        panel: '0 24px 48px -24px rgba(22, 22, 22, 0.28)',
                    }
                }
            }
        };
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script type="module" src="https://unpkg.com/ionicons@7.4.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.4.0/dist/ionicons/ionicons.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            document.documentElement.dataset.theme = theme;
        })();
    </script>
</head>
<body class="sendflow-body bg-carbon-bg text-carbon-text dark:bg-carbon-bgDark dark:text-carbon-textDark transition-colors duration-200">
<div class="min-h-screen overflow-hidden bg-[radial-gradient(circle_at_top_left,_rgba(15,98,254,0.14),_transparent_28%),radial-gradient(circle_at_bottom_right,_rgba(0,67,206,0.12),_transparent_24%)] dark:bg-[radial-gradient(circle_at_top_left,_rgba(69,137,255,0.18),_transparent_24%),radial-gradient(circle_at_bottom_right,_rgba(120,169,255,0.16),_transparent_24%)]">
    <?php include __DIR__ . '/partials/header.php'; ?>
    <div class="flex min-h-[calc(100vh-4.5rem)]">
        <?php if ($showSidebar): include __DIR__ . '/partials/sidebar.php'; endif; ?>
        <main class="flex-1 overflow-y-auto" id="main-content" hx-target="#main-content" hx-swap="innerHTML">
            <div class="mx-auto max-w-7xl px-4 py-6 md:px-6 lg:px-8 lg:py-8">
                <?= $content ?>
            </div>
        </main>
    </div>
    <?php include __DIR__ . '/partials/footer.php'; ?>
    <?php include __DIR__ . '/partials/components/toast.php'; ?>
</div>
<script src="/assets/js/app.js"></script>
</body>
</html>
