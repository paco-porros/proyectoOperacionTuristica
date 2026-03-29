<?php
/**
 * ajax/admin_planes_turisticos.php — GESTIÓN DE PLANES TURÍSTICOS
 * GET  → Lista planes + stats
 * PUT  → Edita un plan existente
 * POST action=toggle → Activa/inactiva un plan
 * Auth: admin/editor
 */

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/session.php';

/*
   BLOQUE 1 - Autenticación y Autorización
   - Verifica usuario logueado (401 si no)
   - Verifica rol admin/editor (403 si no)
*/
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

$pdo    = getDB();
$method = $_SERVER['REQUEST_METHOD'];

/*
   BLOQUE 2 - GET: Listar Planes Turísticos
   - Query SELECT todos los planes (turísticos)
   - Formatea precios a formato legible
   - Calcula stats (total, activos)
   - Devuelve { planes, stats }
*/
if ($method === 'GET') {
    $stmt = $pdo->query(
        "SELECT id, titulo, descripcion, ubicacion, duracion_dias,
                precio_desde, moneda, etiqueta, puntuacion,
                total_resenas, imagen_hero_url, estado
         FROM planes_turisticos
         ORDER BY id DESC"
    );
    $planes = $stmt->fetchAll();

    foreach ($planes as &$p) {
        $p['precio_formateado'] = number_format((float)$p['precio_desde'], 0, ',', '.');
    }

    $stats = $pdo->query(
        "SELECT COUNT(*) AS total,
                SUM(estado = 'activo') AS activos
         FROM planes_turisticos"
    )->fetch();

    echo json_encode(['ok' => true, 'planes' => $planes, 'stats' => $stats]);
    exit;
}

/*
   BLOQUE 3 - PUT: Editar Plan Turístico
   - Parsea JSON del body
   - Extrae y valida parámetros (id, titulo, ubicacion, dias, precio)
   - Verifica que plan existe
   - UPDATE con prepared statement
   - Devuelve { ok: true, msg }
*/
if ($method === 'PUT') {
    $d      = json_decode(file_get_contents('php://input'), true) ?? [];
    $id     = (int)($d['id'] ?? 0);
    $titulo = trim($d['titulo'] ?? '');
    $desc   = trim($d['descripcion'] ?? '');
    $ubic   = trim($d['ubicacion'] ?? '');
    $dias   = max(1, (int)($d['duracion_dias'] ?? 1));
    $precio = (float)($d['precio_desde'] ?? 0);
    $imagen = trim($d['imagen_hero_url'] ?? '');
    $estado = in_array($d['estado'] ?? '', ['activo', 'inactivo']) ? $d['estado'] : 'activo';

    if (!$id || !$titulo) {
        echo json_encode(['ok' => false, 'msg' => 'ID y título son requeridos.']);
        exit;
    }

    // Verificar que el plan existe
    $check = $pdo->prepare('SELECT id FROM planes_turisticos WHERE id = ? LIMIT 1');
    $check->execute([$id]);
    if (!$check->fetch()) {
        echo json_encode(['ok' => false, 'msg' => 'Plan no encontrado.']);
        exit;
    }

    $stmt = $pdo->prepare(
        "UPDATE planes_turisticos
         SET titulo = ?, descripcion = ?, ubicacion = ?, duracion_dias = ?,
             precio_desde = ?, imagen_hero_url = ?, estado = ?
         WHERE id = ?"
    );
    $stmt->execute([$titulo, $desc, $ubic, $dias, $precio, $imagen, $estado, $id]);

    echo json_encode(['ok' => true, 'msg' => 'Plan actualizado correctamente.']);
    exit;
}

/*
   BLOQUE 4 - POST action=toggle: Activar/Inactivar
   - Parsea JSON/POST
   - Valida action="toggle" e id
   - Obtiene estado actual
   - Calcula nuevo estado (inverso)
   - UPDATE plan, devuelve { ok, msg, estado }
*/
if ($method === 'POST') {
    $d      = json_decode(file_get_contents('php://input'), true) ?? $_POST;
    $action = trim($d['action'] ?? '');
    $id     = (int)($d['id'] ?? 0);

    if ($action !== 'toggle' || !$id) {
        echo json_encode(['ok' => false, 'msg' => 'Parámetros inválidos.']);
        exit;
    }

    // Obtener estado actual
    $sel = $pdo->prepare('SELECT estado FROM planes_turisticos WHERE id = ? LIMIT 1');
    $sel->execute([$id]);
    $row = $sel->fetch();

    if (!$row) {
        echo json_encode(['ok' => false, 'msg' => 'Plan no encontrado.']);
        exit;
    }

    $nuevo = ($row['estado'] === 'activo') ? 'inactivo' : 'activo';
    $pdo->prepare('UPDATE planes_turisticos SET estado = ? WHERE id = ?')->execute([$nuevo, $id]);

    echo json_encode([
        'ok'     => true,
        'msg'    => 'Plan ' . ($nuevo === 'activo' ? 'activado' : 'inactivado') . ' correctamente.',
        'estado' => $nuevo,
    ]);
    exit;
}

// BLOQUE 5 - Método no soportado
echo json_encode(['ok' => false, 'msg' => 'Método no soportado.']);