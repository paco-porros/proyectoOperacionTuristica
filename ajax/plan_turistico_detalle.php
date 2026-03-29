<?php
/**
 * ajax/plan_turistico_detalle.php — DETALLE DE PLAN TURÍSTICO
 * GET ?id=N — devuelve plan con itinerario, incluye y reseñas
 * Responde: { ok, plan } | { ok: false, msg }
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes/db.php';

/*
   BLOQUE 1 - Validar y parsear ID
   - GET ?id=N requerido
   - Si no: JSON error
*/
$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    echo json_encode(['ok' => false, 'msg' => 'ID inválido.']);
    exit;
}

$pdo = getDB();

/*
   BLOQUE 2 - Consultar plan principal
   - SELECT plan_turistico by id, solo activos
   - Si no existe: error 404
*/
$stmt = $pdo->prepare(
    'SELECT id, titulo, descripcion, ubicacion, duracion_dias, precio_desde, moneda,
            etiqueta, puntuacion, total_resenas, imagen_hero_url
     FROM planes_turisticos WHERE id = ? AND estado = \'activo\' LIMIT 1'
);
$stmt->execute([$id]);
$plan = $stmt->fetch();

if (!$plan) {
    echo json_encode(['ok' => false, 'msg' => 'Plan no encontrado.']);
    exit;
}

/*
   BLOQUE 3 - Cargar itinerario
   - SELECT itinerario_dias ORDER BY número_día
   - Incluye: numero_dia, titulo, descripción
*/
$dias = $pdo->prepare(
    'SELECT numero_dia, titulo, descripcion FROM itinerario_dias
     WHERE plan_turistico_id = ? ORDER BY numero_dia ASC'
);
$dias->execute([$id]);
$plan['itinerario'] = $dias->fetchAll();

/*
   BLOQUE 4 - Cargar qué incluye
   - SELECT plan_incluye con icono y descripción
*/
$inc = $pdo->prepare(
    'SELECT descripcion, icono FROM plan_incluye WHERE plan_turistico_id = ? ORDER BY id ASC'
);
$inc->execute([$id]);
$plan['incluye'] = $inc->fetchAll();

/*
   BLOQUE 5 - Cargar reseñas publicadas
   - SELECT resenas limit 5, más recientes
*/
$res = $pdo->prepare(
    'SELECT puntuacion, comentario, autor_nombre, autor_cargo
     FROM resenas
     WHERE plan_turistico_id = ? AND estado = \'publicado\'
     ORDER BY created_at DESC LIMIT 5'
);
$res->execute([$id]);
$plan['resenas'] = $res->fetchAll();

/*
   BLOQUE 6 - Formatear respuesta
   - precio_formateado: separador miles
   - imagen_hero_url: fallback si vacío
*/
$plan['precio_formateado'] = number_format((float)$plan['precio_desde'], 0, ',', '.');
$plan['imagen_hero_url'] = !empty($plan['imagen_hero_url'])
    ? $plan['imagen_hero_url']
    : '/img/fondoPortada.jpg';

/*
   BLOQUE 7 - Devolver JSON
   - { ok: true, plan: {id, titulo, descripción, itinerario[], incluye[], resenas[]} }
*/
echo json_encode(['ok' => true, 'plan' => $plan]);
