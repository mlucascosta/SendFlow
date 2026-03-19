<?php

declare(strict_types=1);

/**
 * Entrada legada do instalador (fora do public root).
 * Legacy installer entrypoint (outside public root).
 */
header('Location: /install/index.php');
exit;
