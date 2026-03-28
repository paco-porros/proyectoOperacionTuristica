<?php
/**
 * ajax/logout.php
 * Endpoint AJAX: POST → cierra sesión y responde JSON
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes/session.php';

$_SESSION = [];
session_destroy();

echo json_encode(['ok' => true, 'redirect' => '/login.php']);
