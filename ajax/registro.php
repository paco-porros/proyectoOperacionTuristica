<?php
/**
 * ajax/registro.php
 * Endpoint AJAX: POST { nombre, email, password, confirmar }
 * Responde JSON { ok, msg, redirect? }
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/session.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'msg' => 'Método no permitido.']);
    exit;
}

$data      = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$nombre    = trim($data['nombre']    ?? '');
$email     = trim($data['email']     ?? '');
$password  = trim($data['password']  ?? '');
$confirmar = trim($data['confirmar'] ?? '');

// Validaciones
if (!$nombre || !$email || !$password || !$confirmar) {
    echo json_encode(['ok' => false, 'msg' => 'Completa todos los campos.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['ok' => false, 'msg' => 'Correo electrónico no válido.']);
    exit;
}

if (strlen($password) < 8) {
    echo json_encode(['ok' => false, 'msg' => 'La contraseña debe tener al menos 8 caracteres.']);
    exit;
}

if ($password !== $confirmar) {
    echo json_encode(['ok' => false, 'msg' => 'Las contraseñas no coinciden.']);
    exit;
}

$pdo = getDB();

// Verificar email duplicado
$check = $pdo->prepare('SELECT id FROM usuarios WHERE email = ? LIMIT 1');
$check->execute([$email]);
if ($check->fetch()) {
    echo json_encode(['ok' => false, 'msg' => 'Este correo ya está registrado.']);
    exit;
}

// Insertar usuario
$hash = password_hash($password, PASSWORD_BCRYPT);
$ins  = $pdo->prepare(
    'INSERT INTO usuarios (nombre, email, contrasena, rol, estado) VALUES (?, ?, ?, \'cliente\', \'activo\')'
);
$ins->execute([$nombre, $email, $hash]);
$nuevoId = (int) $pdo->lastInsertId();

// Auto-login tras registro
$_SESSION['usuario_id']     = $nuevoId;
$_SESSION['usuario_nombre'] = $nombre;
$_SESSION['usuario_email']  = $email;
$_SESSION['usuario_rol']    = 'cliente';

echo json_encode([
    'ok'       => true,
    'msg'      => '¡Cuenta creada! Bienvenido, ' . $nombre . '.',
    'redirect' => '/home.php',
]);
