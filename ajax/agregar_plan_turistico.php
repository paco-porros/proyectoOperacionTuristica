<?php
/**
 * ajax/agregar_plan_turistico.php
 * POST multipart/form-data — crea un nuevo plan turístico.
 * Sube la imagen a /img/ y guarda la ruta en la BD.
 * Solo admin y editor.
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/session.php';

if (!estaLogueado()) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'msg' => 'No autenticado.']);
    exit;
}

$usuario = usuarioActual();
if (!in_array($usuario['rol'], ['admin', 'editor'], true)) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'msg' => 'Acceso denegado.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'msg' => 'Método no permitido.']);
    exit;
}

// ── Leer campos de texto ─────────────────────────────────────────────────────
$titulo   = trim($_POST['titulo']      ?? '');
$desc     = trim($_POST['descripcion'] ?? '');
$ubic     = trim($_POST['ubicacion']   ?? '');
$dias     = max(1, (int)($_POST['duracion_dias'] ?? 1));
$precio   = (float)($_POST['precio_desde'] ?? 0);
$moneda   = trim($_POST['moneda']   ?? 'COP');
$etiqueta = trim($_POST['etiqueta'] ?? '');
$estado   = in_array($_POST['estado'] ?? '', ['activo', 'inactivo']) ? $_POST['estado'] : 'activo';

if (!$titulo) {
    echo json_encode(['ok' => false, 'msg' => 'El título es requerido.']);
    exit;
}

// ── Subida de imagen ─────────────────────────────────────────────────────────
$imagen_url = '';

if (!empty($_FILES['imagen']['name'])) {
    $file    = $_FILES['imagen'];
    $ext     = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($ext, $allowed)) {
        echo json_encode(['ok' => false, 'msg' => 'Formato de imagen no válido. Use JPG, PNG o WebP.']);
        exit;
    }

    if ($file['size'] > 5 * 1024 * 1024) {
        echo json_encode(['ok' => false, 'msg' => 'La imagen supera el límite de 5 MB.']);
        exit;
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['ok' => false, 'msg' => 'Error al subir la imagen (código ' . $file['error'] . ').']);
        exit;
    }

    // Nombre único para evitar colisiones
    $nombre_archivo = 'plan-tur-' . time() . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
    $destino        = __DIR__ . '/../img/' . $nombre_archivo;

    if (!move_uploaded_file($file['tmp_name'], $destino)) {
        echo json_encode(['ok' => false, 'msg' => 'No se pudo guardar la imagen en el servidor.']);
        exit;
    }

    $imagen_url = 'img/' . $nombre_archivo;
}

// ── Insertar en BD ───────────────────────────────────────────────────────────
$pdo  = getDB();
$stmt = $pdo->prepare(
    "INSERT INTO planes_turisticos
        (titulo, descripcion, ubicacion, duracion_dias, precio_desde, moneda, etiqueta,
         imagen_hero_url, estado, puntuacion, total_resenas)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0, 0)"
);
$stmt->execute([$titulo, $desc, $ubic, $dias, $precio, $moneda, $etiqueta, $imagen_url, $estado]);
$nuevo_id = (int)$pdo->lastInsertId();

echo json_encode([
    'ok'  => true,
    'msg' => 'Plan turístico "' . $titulo . '" creado correctamente.',
    'id'  => $nuevo_id,
]);
