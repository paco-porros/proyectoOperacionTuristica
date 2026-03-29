<?php
/**
 * ajax/planes_turisticos.php — LISTAR PLANES TURÍSTICOS
 * GET ?limite=3&offset=0 — lista paginada
 * GET ?sin_limite=1 — devuelve todos los planes
 * Responde: { ok, planes[] }
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes/db.php';

// BLOQUE 1 - Parsear parámetros
// ?limite=N (máx 12, default 3) o ?sin_limite=1 para todos
// ?offset=N para paginación
$sinLimite = isset($_GET['sin_limite']) && $_GET['sin_limite'] === '1';
$limite    = $sinLimite ? null : max(1, min(12, (int)($_GET['limite'] ?? 3)));
$offset    = max(0, (int)($_GET['offset'] ?? 0));

$pdo = getDB();

/*
   BLOQUE 2 - Consultar planes
   - Si sin_limite=1: SELECT * ORDER BY resenas DESC (todos)
   - Si no: idem pero LIMIT N OFFSET M (paginado)
   - Solo estado='activo'
*/
if ($sinLimite) {
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

/*
   BLOQUE 3 - Formatear precios
   - Cada plan: precio_desde → number_format con separador de miles
   - Resultado incluido en array planes[]
*/
foreach ($planes as &$p) {
    $p['precio_formateado'] = number_format((float)$p['precio_desde'], 0, ',', '.');
}

echo json_encode(['ok' => true, 'planes' => $planes]);