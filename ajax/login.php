<?php
/**
 * ajax/login.php — AUTENTICACIÓN USUARIO
 * POST { email, password } 
 * Valida credenciales, inicia sesión, devuelve redirect según rol
 * Responde: { ok, msg, redirect? }
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/session.php';

/*
   BLOQUE 1 - Validar método HTTP
   - Solo acepta POST (405 si otro método)
*/
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'msg' => 'Método no permitido.']);
    exit;
}

/*
   BLOQUE 2 - Extraer y validar parámetros
   - email, password (ambos requeridos)
   - Valida formato email con FILTER_VALIDATE_EMAIL
*/
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

/*
   BLOQUE 3 - Buscar usuario por email
   - SELECT FROM usuarios WHERE email = ?
   - Si no existe: "Credenciales incorrectas" (no revela si existe o no)
*/
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

/*
   BLOQUE 4 - Verificar estado de cuenta
   - Si estado es 'inactivo': rechaza login
   - Mensaje indica contactar administrador
*/
if ($usuario['estado'] === 'inactivo') {
    echo json_encode(['ok' => false, 'msg' => 'Tu cuenta está inactiva. Contacta al administrador.']);
    exit;
}

/*
   BLOQUE 5 - Verificar contraseña (compatibilidad legacy + moderna)
   - MD5 (32 chars): hash antiguo (usuario Victor)
   - Bcrypt: hash moderno password_verify()
   - Si no coincide: "Credenciales incorrectas"
*/
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

/*
   BLOQUE 6 - Iniciar sesión ($_SESSION)
   - Guarda usuario_id, nombre, email, rol en sesión
*/
$_SESSION['usuario_id']     = $usuario['id'];
$_SESSION['usuario_nombre'] = $usuario['nombre'];
$_SESSION['usuario_email']  = $usuario['email'];
$_SESSION['usuario_rol']    = $usuario['rol'];

/*
   BLOQUE 7 - Determinar redirección según rol
   - admin/editor → /dashboard-administrador.php
   - otro → /home.php
*/
$redirect = match($usuario['rol']) {
    'admin'  => '/dashboard-administrador.php',
    'editor' => '/dashboard-administrador.php',
    default  => '/home.php',
};

/*
   BLOQUE 8 - Devolver éxito con redirección
   - { ok: true, msg: bienvenida, redirect: URL }
*/
echo json_encode(['ok' => true, 'msg' => '¡Bienvenido, ' . $usuario['nombre'] . '!', 'redirect' => $redirect]);
