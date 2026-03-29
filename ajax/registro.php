<?php
/**
 * ajax/registro.php — CREAR NUEVA CUENTA
 * POST { nombre, email, password, confirmar }
 * Valida datos, crea usuario (rol: cliente), auto-login
 * Responde: { ok, msg, redirect }
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/session.php';

// BLOQUE 1 - Validar método HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'msg' => 'Método no permitido.']);
    exit;
}

/*
   BLOQUE 2 - Extraer parámetros
   - nombre, email, password, confirmar
*/
$data      = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$nombre    = trim($data['nombre']    ?? '');
$email     = trim($data['email']     ?? '');
$password  = trim($data['password']  ?? '');
$confirmar = trim($data['confirmar'] ?? '');

/*
   BLOQUE 3 - Validaciones
   - Campos requeridos (todos)
   - Email válido (FILTER_VALIDATE_EMAIL)
   - Password >= 8 caracteres
   - password === confirmar
*/
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

/*
   BLOQUE 4 - Verificar email no duplicado
   - SELECT FROM usuarios WHERE email = ?
   - Si existe: error "ya registrado"
*/
$pdo = getDB();
$check = $pdo->prepare('SELECT id FROM usuarios WHERE email = ? LIMIT 1');
$check->execute([$email]);
if ($check->fetch()) {
    echo json_encode(['ok' => false, 'msg' => 'Este correo ya está registrado.']);
    exit;
}

/*
   BLOQUE 5 - Crear usuario nuevo
   - Hash password con PASSWORD_BCRYPT
   - INSERT usuario con rol='cliente' estado='activo'
   - Obtener lastInsertId()
*/
$hash = password_hash($password, PASSWORD_BCRYPT);
$ins  = $pdo->prepare(
    'INSERT INTO usuarios (nombre, email, contrasena, rol, estado) VALUES (?, ?, ?, \'cliente\', \'activo\')'
);
$ins->execute([$nombre, $email, $hash]);
$nuevoId = (int) $pdo->lastInsertId();

/*
   BLOQUE 6 - Auto-login
   - Sesión se inicia automáticamente sin login manual
   - usuario_rol = 'cliente'
*/
$_SESSION['usuario_id']     = $nuevoId;
$_SESSION['usuario_nombre'] = $nombre;
$_SESSION['usuario_email']  = $email;
$_SESSION['usuario_rol']    = 'cliente';

/*
   BLOQUE 7 - Devolver éxito con redirect a home
   - { ok: true, msg: bienvenida, redirect: /home.php }
*/
echo json_encode([
    'ok'       => true,
    'msg'      => '¡Cuenta creada! Bienvenido, ' . $nombre . '.',
    'redirect' => '/home.php',
]);
