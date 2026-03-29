<?php
/**
 * ajax/planes_turisticos.php
 * Endpoint AJAX: GET ?limite=3&offset=0 | GET ?sin_limite=1
 *
 * ?limite=N     → devuelve hasta N planes (máx. 12, por defecto 3)
 * ?sin_limite=1 → devuelve TODOS los planes activos (usado por planes.php)
 * ?offset=N     → paginación (solo aplica con limite)
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes/db.php';

$sinLimite = isset($_GET['sin_limite']) && $_GET['sin_limite'] === '1';
$limite    = $sinLimite ? null : max(1, min(12, (int)($_GET['limite'] ?? 3)));
$offset    = max(0, (int)($_GET['offset'] ?? 0));

$pdo = getDB();

if ($sinLimite) {
    // Devuelve TODOS los planes activos sin restriccion de cantidad
    $stmt = $pdo->query(
        "SELECT id, titulo, descripcion, ubicacion, duracion_dias, precio_desde, moneda,
                etiqueta, puntuacion, total_resenas, imagen_hero_url
         FROM planes_turisticos
         WHERE estado = 'activo'
         ORDER BY total_resenas DESC"
    );
} else {
    $stmt = $pdo->prepare(
        "SELECT id, titulo, descripcion, ubicacion, duracion_dias, precio_desde, moneda,
                etiqueta, puntuacion, total_resenas, imagen_hero_url
         FROM planes_turisticos
         WHERE estado = 'activo'
         ORDER BY total_resenas DESC
         LIMIT :lim OFFSET :off"
    );
    $stmt->bindValue(':lim', $limite, PDO::PARAM_INT);
    $stmt->bindValue(':off', $offset, PDO::PARAM_INT);
    $stmt->execute();
}

$planes = $stmt->fetchAll();

// Formatear precio
foreach ($planes as &$p) {
    $p['precio_formateado'] = number_format((float)$p['precio_desde'], 0, ',', '.');
}

echo json_encode(['ok' => true, 'planes' => $planes]);