<?php
/**
 * ajax/crear_plan_turistico.php
 * Endpoint AJAX: POST para crear nuevo plan turístico
 * Maneja subida de imagen a /img/
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
    http_response_code(405);
    echo json_encode(['ok' => false, 'msg' => 'Método no permitido.']);
    exit;
}

// ────────────────────────────────────────────────────────────────────────────
// Validar campos requeridos
// ────────────────────────────────────────────────────────────────────────────

$titulo   = trim($_POST['titulo'] ?? '');
$desc     = trim($_POST['descripcion'] ?? '');
$ubic     = trim($_POST['ubicacion'] ?? '');
$dias     = max(1, (int)($_POST['duracion_dias'] ?? 1));
$precio   = (float)($_POST['precio_desde'] ?? 0);
$estado   = in_array($_POST['estado'] ?? 'activo', ['activo', 'inactivo']) ? $_POST['estado'] : 'activo';
$etiqueta = trim($_POST['etiqueta'] ?? 'Turismo');

if (!$titulo) {
    echo json_encode(['ok' => false, 'msg' => 'El título es requerido.']);
    exit;
}

if ($precio < 0) {
    echo json_encode(['ok' => false, 'msg' => 'El precio no puede ser negativo.']);
    exit;
}

// ────────────────────────────────────────────────────────────────────────────
// Manejo de la imagen
// ────────────────────────────────────────────────────────────────────────────

$imagen_ruta = null;

if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $archivo = $_FILES['imagen'];
    
    // Validar tipo de archivo
    $tipos_permitidos = ['image/jpeg', 'image/png', 'image/webp'];
    if (!in_array($archivo['type'], $tipos_permitidos, true)) {
        echo json_encode(['ok' => false, 'msg' => 'Tipo de archivo no permitido. Use JPG, PNG o WebP.']);
        exit;
    }

    // Validar tamaño (máximo 5MB)
    $max_size = 5 * 1024 * 1024; // 5MB
    if ($archivo['size'] > $max_size) {
        echo json_encode(['ok' => false, 'msg' => 'La imagen es demasiado grande (máximo 5MB).']);
        exit;
    }

    // Crear directorio si no existe
    $dir_img = __DIR__ . '/../img';
    if (!is_dir($dir_img)) {
        mkdir($dir_img, 0755, true);
    }

    // Generar nombre único
    $ext = match($archivo['type']) {
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
    };
    
    $nombre_archivo = 'plan_turistico_' . time() . '_' . uniqid() . '.' . $ext;
    $ruta_completa  = $dir_img . '/' . $nombre_archivo;
    $ruta_relativa  = 'img/' . $nombre_archivo;

    // Mover archivo
    if (!move_uploaded_file($archivo['tmp_name'], $ruta_completa)) {
        echo json_encode(['ok' => false, 'msg' => 'Error al subir la imagen.']);
        exit;
    }

    $imagen_ruta = $ruta_relativa;
}

// ────────────────────────────────────────────────────────────────────────────
// Guardar en BD
// ────────────────────────────────────────────────────────────────────────────

$pdo = getDB();

try {
    $stmt = $pdo->prepare(
        "INSERT INTO planes_turisticos 
         (titulo, descripcion, ubicacion, duracion_dias, precio_desde, 
          moneda, etiqueta, imagen_hero_url, estado, puntuacion, total_resenas)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0, 0)"
    );
    
    $stmt->execute([
        $titulo,
        $desc,
        $ubic,
        $dias,
        $precio,
        'USD',
        $etiqueta,
        $imagen_ruta,
        $estado,
    ]);

    $plan_id = $pdo->lastInsertId();

    // Preparar objeto del plan para respuesta
    $nuevo_plan = [
        'id'               => $plan_id,
        'titulo'           => $titulo,
        'descripcion'      => $desc,
        'ubicacion'        => $ubic,
        'duracion_dias'    => $dias,
        'precio_desde'     => $precio,
        'precio_formateado' => number_format($precio, 0, ',', '.'),
        'moneda'           => 'USD',
        'etiqueta'         => $etiqueta,
        'imagen_hero_url'  => $imagen_ruta ?: '/img/fondoPortada.jpg',
        'estado'           => $estado,
    ];

    echo json_encode([
        'ok'    => true,
        'msg'   => '✓ Plan turístico "' . $titulo . '" creado correctamente.',
        'plan'  => $nuevo_plan,
    ]);

} catch (PDOException $e) {
    // Eliminar imagen si hubo error en BD
    if ($imagen_ruta && file_exists($dir_img . '/' . basename($imagen_ruta))) {
        unlink($dir_img . '/' . basename($imagen_ruta));
    }
    
    echo json_encode(['ok' => false, 'msg' => 'Error al guardar el plan en la BD.']);
    exit;
}
