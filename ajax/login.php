<?php
/**
 * ajax/login.php
 * Endpoint AJAX: POST { email, password }
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

$data     = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$email    = trim($data['email']    ?? '');
$password = trim($data['password'] ?? '');

if (!$email || !$password) {
    echo json_encode(['ok' => false, 'msg' => 'Completa todos los campos.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['ok' => false, 'msg' => 'Correo no válido.']);
    exit;
}

$pdo  = getDB();
$stmt = $pdo->prepare(
    'SELECT id, nombre, email, contrasena, rol, estado FROM usuarios WHERE email = ? LIMIT 1'
);
$stmt->execute([$email]);
$usuario = $stmt->fetch();

if (!$usuario) {
    echo json_encode(['ok' => false, 'msg' => 'Credenciales incorrectas.']);
    exit;
}

if ($usuario['estado'] === 'inactivo') {
    echo json_encode(['ok' => false, 'msg' => 'Tu cuenta está inactiva. Contacta al administrador.']);
    exit;
}

// Compatibilidad: MD5 legacy (usuario Victor) y bcrypt
$passwordOk = false;
if (strlen($usuario['contrasena']) === 32) {
    // Hash MD5 legacy
    $passwordOk = (md5($password) === $usuario['contrasena']);
} else {
    $passwordOk = password_verify($password, $usuario['contrasena']);
}

if (!$passwordOk) {
    echo json_encode(['ok' => false, 'msg' => 'Credenciales incorrectas.']);
    exit;
}

// Iniciar sesión
$_SESSION['usuario_id']     = $usuario['id'];
$_SESSION['usuario_nombre'] = $usuario['nombre'];
$_SESSION['usuario_email']  = $usuario['email'];
$_SESSION['usuario_rol']    = $usuario['rol'];

// Redirigir según rol
$redirect = match($usuario['rol']) {
    'admin'  => '/dashboard-administrador.php',
    'editor' => '/dashboard-administrador.php',
    default  => '/home.php',
};

echo json_encode(['ok' => true, 'msg' => '¡Bienvenido, ' . $usuario['nombre'] . '!', 'redirect' => $redirect]);
