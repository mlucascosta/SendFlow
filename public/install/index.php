<?php
declare(strict_types=1);
session_start();
$step = (int) ($_GET['step'] ?? 1);
$step = max(1, min(6, $step));
$totalSteps = 6;
$progress = (int) (($step / $totalSteps) * 100);
$map = [1 => '01_welcome.php', 2 => '02_database.php', 3 => '03_admin.php', 4 => '04_resend_prep.php', 5 => '05_resend_config.php', 6 => '06_finish.php'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Instalação - SendFlow</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
</head>
<body class="min-h-screen bg-neutral-50 flex items-center justify-center p-4">
<div class="w-full max-w-2xl">
  <div class="text-center mb-6"><h1 class="text-2xl font-bold">SendFlow</h1><p class="text-neutral-500">Instalação do Sistema</p></div>
  <div class="mb-5"><div class="w-full h-2 bg-neutral-200 rounded-full overflow-hidden"><div class="h-full bg-red-600" style="width: <?= $progress ?>%"></div></div></div>
  <div class="bg-white rounded-2xl border border-neutral-200 p-6">
    <?php
      $file = __DIR__ . '/../../install/steps/' . $map[$step];
      if (file_exists($file)) {
          include $file;
      } else {
          echo 'Passo não encontrado.';
      }
    ?>
  </div>
</div>
</body>
</html>
