<?php
/**
 * ajax/planes_turisticos.php
 * Endpoint AJAX: GET ?limite=3&offset=0
 * Devuelve planes turísticos activos para mostrar en index.php
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes/db.php';

$limite = max(1, min(12, (int)($_GET['limite'] ?? 3)));
$offset = max(0, (int)($_GET['offset'] ?? 0));

$pdo  = getDB();
$stmt = $pdo->prepare(
    'SELECT id, titulo, descripcion, ubicacion, duracion_dias, precio_desde, moneda,
            etiqueta, puntuacion, total_resenas, imagen_hero_url
     FROM planes_turisticos
     WHERE estado = \'activo\'
     ORDER BY total_resenas DESC
     LIMIT :lim OFFSET :off'
);
$stmt->bindValue(':lim', $limite, PDO::PARAM_INT);
$stmt->bindValue(':off', $offset, PDO::PARAM_INT);
$stmt->execute();
$planes = $stmt->fetchAll();

// Formatear precio
foreach ($planes as &$p) {
    $p['precio_formateado'] = number_format((float)$p['precio_desde'], 0, ',', '.');
}

echo json_encode(['ok' => true, 'planes' => $planes]);
