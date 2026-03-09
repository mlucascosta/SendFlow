<?php

declare(strict_types=1);

/**
 * Endpoint básico de webhook da Resend.
 * Basic Resend webhook endpoint.
 *
 * Observação: este arquivo é um placeholder inicial.
 * Note: this file is an initial placeholder.
 */
http_response_code(200);
header('Content-Type: application/json; charset=utf-8');
echo json_encode(['status' => 'ok']);
