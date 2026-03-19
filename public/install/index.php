<?php

declare(strict_types=1);

use App\Services\Installer;
use App\Services\Validator;

require_once __DIR__ . '/../../app/services/Encryption.php';
require_once __DIR__ . '/../../app/services/Installer.php';
require_once __DIR__ . '/../../app/services/Validator.php';

session_start();

$installer = new Installer();
$envPath = __DIR__ . '/../../config/env.php';
$steps = [
    1 => '01_welcome.php',
    2 => '02_database.php',
    3 => '03_admin.php',
    4 => '04_resend_prep.php',
    5 => '05_resend_config.php',
    6 => '06_finish.php',
];

$_SESSION['install'] ??= [
    'step' => 1,
    'database' => [],
    'admin' => [],
    'resend' => [],
    'migrations' => [],
];
$_SESSION['install_csrf'] ??= bin2hex(random_bytes(32));

$maxCompletedStep = (int) ($_SESSION['install']['step'] ?? 1);
$requestedStep = max(1, min(6, (int) ($_GET['step'] ?? $maxCompletedStep)));
$step = min($requestedStep, $maxCompletedStep);
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['install_csrf'], (string) ($_POST['csrf_token'] ?? ''))) {
        $errors[] = 'Invalid installer token. Please reload the page.';
    } else {
        $action = (string) ($_POST['action'] ?? '');

        try {
            if ($action === 'save_database') {
                $databaseInput = [
                    'host' => trim((string) ($_POST['host'] ?? '')),
                    'port' => (int) ($_POST['port'] ?? 3306),
                    'database' => trim((string) ($_POST['database'] ?? '')),
                    'username' => trim((string) ($_POST['username'] ?? '')),
                    'password' => (string) ($_POST['password'] ?? ''),
                    'charset' => 'utf8mb4',
                ];

                $errors = validateDatabaseInput($databaseInput);
                if ($errors === []) {
                    $envConfig = $installer->buildEnvConfig($databaseInput);
                    $installer->runMigrations($envConfig['database'], __DIR__ . '/../../install/migrations');
                    $installer->writeEnvFile($envPath, $envConfig);

                    $_SESSION['install']['database'] = $databaseInput;
                    $_SESSION['install']['migrations'] = ['status' => 'completed'];
                    $_SESSION['install']['step'] = 3;

                    redirectToStep(3);
                }
            }

            if ($action === 'save_admin') {
                ensureEnvFileExists($envPath);

                $adminInput = [
                    'name' => trim((string) ($_POST['name'] ?? '')),
                    'email' => trim((string) ($_POST['email'] ?? '')),
                    'password' => (string) ($_POST['password'] ?? ''),
                ];

                $errors = validateAdminInput($adminInput);
                if ($errors === []) {
                    $envConfig = require $envPath;
                    $adminId = $installer->createAdmin($envConfig['database'], $adminInput['name'], $adminInput['email'], $adminInput['password']);

                    $_SESSION['install']['admin'] = [
                        'id' => $adminId,
                        'name' => $adminInput['name'],
                        'email' => $adminInput['email'],
                    ];
                    $_SESSION['install']['step'] = 4;

                    redirectToStep(4);
                }
            }

            if ($action === 'continue_to_resend') {
                $_SESSION['install']['step'] = 5;
                redirectToStep(5);
            }

            if ($action === 'save_resend') {
                ensureEnvFileExists($envPath);

                $resendInput = [
                    'domain' => trim((string) ($_POST['domain'] ?? '')),
                    'api_key' => trim((string) ($_POST['api_key'] ?? '')),
                ];

                $errors = validateResendInput($resendInput);
                if ($errors === []) {
                    $envConfig = require $envPath;
                    $adminId = (int) ($_SESSION['install']['admin']['id'] ?? 0);
                    if ($adminId <= 0) {
                        throw new RuntimeException('Admin account step must be completed before configuring Resend.');
                    }

                    $installer->saveResendConfig($envConfig['database'], $envConfig, $adminId, $resendInput['domain'], $resendInput['api_key']);
                    $_SESSION['install']['resend'] = [
                        'domain' => $resendInput['domain'],
                        'api_key_last_digits' => substr($resendInput['api_key'], -4),
                    ];
                    $_SESSION['install']['step'] = 6;

                    redirectToStep(6);
                }
            }
        } catch (Throwable $exception) {
            $errors[] = $exception->getMessage();
        }
    }
}

$progress = (int) (($step / count($steps)) * 100);
$file = __DIR__ . '/../../install/steps/' . $steps[$step];
$old = $_SESSION['install'];
$csrfToken = $_SESSION['install_csrf'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SendFlow Setup Wizard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
</head>
<body class="min-h-screen bg-neutral-50 text-neutral-900 p-4 md:p-6">
<div class="max-w-5xl mx-auto">
  <div class="grid lg:grid-cols-[280px,1fr] gap-6">
    <aside class="bg-white rounded-3xl border border-neutral-200 p-6 space-y-5">
      <div>
        <p class="text-sm uppercase tracking-[0.2em] text-red-600 font-semibold">SendFlow</p>
        <h1 class="text-2xl font-bold mt-2">Personalized onboarding</h1>
        <p class="text-neutral-500 mt-2">A guided setup for a secure MySQL/MariaDB-based SendFlow deployment.</p>
      </div>

      <div class="space-y-3">
        <?php foreach ($steps as $index => $filename): ?>
          <?php
            $labels = [
                1 => 'Welcome',
                2 => 'Database',
                3 => 'Admin account',
                4 => 'Product tour',
                5 => 'Resend setup',
                6 => 'Finish',
            ];
            $isActive = $index === $step;
            $isDone = $index < $maxCompletedStep;
          ?>
          <div class="rounded-2xl border <?= $isActive ? 'border-red-500 bg-red-50' : 'border-neutral-200' ?> px-4 py-3">
            <div class="flex items-center justify-between gap-3">
              <div>
                <p class="text-xs uppercase tracking-[0.16em] text-neutral-400">Step <?= $index ?></p>
                <p class="font-semibold"><?= htmlspecialchars($labels[$index], ENT_QUOTES, 'UTF-8') ?></p>
              </div>
              <span class="text-xs font-semibold <?= $isDone ? 'text-emerald-600' : 'text-neutral-400' ?>">
                <?= $isDone ? 'Done' : ($isActive ? 'Now' : 'Pending') ?>
              </span>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <div>
        <div class="w-full h-2 bg-neutral-200 rounded-full overflow-hidden">
          <div class="h-full bg-red-600" style="width: <?= $progress ?>%"></div>
        </div>
        <p class="text-sm text-neutral-500 mt-3">Progress: <?= $progress ?>%</p>
      </div>
    </aside>

    <main class="bg-white rounded-3xl border border-neutral-200 p-6 md:p-8">
      <?php if ($errors !== []): ?>
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-red-700">
          <p class="font-semibold">Please review the following issues:</p>
          <ul class="list-disc ml-5 mt-2 space-y-1">
            <?php foreach ($errors as $error): ?>
              <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <?php
      if (file_exists($file)) {
          include $file;
      } else {
          echo '<h2 class="text-xl font-semibold">Step not found.</h2>';
      }
      ?>
    </main>
  </div>
</div>
</body>
</html>
<?php

/**
 * @param array<string,mixed> $databaseInput
 * @return array<int,string>
 */
function validateDatabaseInput(array $databaseInput): array
{
    $errors = [];

    if (!Validator::host((string) $databaseInput['host'])) {
        $errors[] = 'Provide a valid MySQL/MariaDB hostname or IP address.';
    }

    if ((int) $databaseInput['port'] < 1 || (int) $databaseInput['port'] > 65535) {
        $errors[] = 'Database port must be between 1 and 65535.';
    }

    if (!Validator::required((string) $databaseInput['database'])) {
        $errors[] = 'Database name is required.';
    }

    if (!Validator::required((string) $databaseInput['username'])) {
        $errors[] = 'Database username is required.';
    }

    return $errors;
}

/**
 * @param array<string,string> $adminInput
 * @return array<int,string>
 */
function validateAdminInput(array $adminInput): array
{
    $errors = [];

    if (!Validator::required($adminInput['name'])) {
        $errors[] = 'Administrator name is required.';
    }

    if (!Validator::email($adminInput['email'])) {
        $errors[] = 'Provide a valid administrator email address.';
    }

    if (!Validator::password($adminInput['password'])) {
        $errors[] = 'Password must contain at least 12 characters, including upper/lowercase letters and a number.';
    }

    return $errors;
}

/**
 * @param array<string,string> $resendInput
 * @return array<int,string>
 */
function validateResendInput(array $resendInput): array
{
    $errors = [];

    if (!Validator::domain($resendInput['domain'])) {
        $errors[] = 'Provide a valid Resend sending domain such as mail.example.com.';
    }

    if (!preg_match('/^re_[A-Za-z0-9]+$/', $resendInput['api_key'])) {
        $errors[] = 'Provide a valid Resend API key.';
    }

    return $errors;
}

function redirectToStep(int $step): void
{
    header('Location: /install/index.php?step=' . $step);
    exit;
}

function ensureEnvFileExists(string $path): void
{
    if (!file_exists($path)) {
        throw new RuntimeException('Database setup must be completed before continuing.');
    }
}
