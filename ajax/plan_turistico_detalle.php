<?php
/**
 * ajax/plan_turistico_detalle.php
 * Endpoint AJAX: GET ?id=1
 * Devuelve detalle completo de un plan turístico con itinerario e incluye
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes/db.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    echo json_encode(['ok' => false, 'msg' => 'ID inválido.']);
    exit;
}

$pdo = getDB();

// Plan principal
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

// Itinerario
$dias = $pdo->prepare(
    'SELECT numero_dia, titulo, descripcion FROM itinerario_dias
     WHERE plan_turistico_id = ? ORDER BY numero_dia ASC'
);
$dias->execute([$id]);
$plan['itinerario'] = $dias->fetchAll();

// Qué incluye
$inc = $pdo->prepare(
    'SELECT descripcion, icono FROM plan_incluye WHERE plan_turistico_id = ? ORDER BY id ASC'
);
$inc->execute([$id]);
$plan['incluye'] = $inc->fetchAll();

// Reseñas publicadas
$res = $pdo->prepare(
    'SELECT puntuacion, comentario, autor_nombre, autor_cargo
     FROM resenas
     WHERE plan_turistico_id = ? AND estado = \'publicado\'
     ORDER BY created_at DESC LIMIT 5'
);
$res->execute([$id]);
$plan['resenas'] = $res->fetchAll();

$plan['precio_formateado'] = number_format((float)$plan['precio_desde'], 0, ',', '.');

echo json_encode(['ok' => true, 'plan' => $plan]);
