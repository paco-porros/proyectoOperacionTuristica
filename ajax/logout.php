<?php
/**
 * ajax/logout.php — CERRAR SESIÓN
 * POST → $_SESSION = [] → session_destroy() → redirect /login.php
 * Responde: { ok: true, redirect }
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes/session.php';

// BLOQUE 1 - Borrar sesión
$_SESSION = [];
session_destroy();

// BLOQUE 2 - Devolver redirect a login
echo json_encode(['ok' => true, 'redirect' => '/login.php']);
