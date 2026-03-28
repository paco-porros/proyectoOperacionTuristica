<?php
/**
 * ajax/gastronomicos.php
 * Endpoint AJAX: GET ?id=1 (detalle) o GET (lista)
 * Solo informativo: restaurantes, dirección, planes gastronómicos
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes/db.php';

$pdo = getDB();
$id  = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    // ── Detalle de un plan gastronómico ──────────────────────────────────
    $stmt = $pdo->prepare(
        'SELECT pg.id, pg.titulo, pg.descripcion, pg.etiqueta, pg.categoria,
                pg.precio_desde, pg.moneda, pg.duracion_horas, pg.max_personas,
                pg.idiomas, pg.puntuacion, pg.total_resenas, pg.imagen_hero_url,
                r.nombre  AS restaurante_nombre,
                r.direccion AS restaurante_direccion,
                r.tipo    AS restaurante_tipo
         FROM planes_gastronomicos pg
         JOIN restaurantes r ON r.id = pg.restaurante_id
         WHERE pg.id = ? AND pg.estado = \'activo\' LIMIT 1'
    );
    $stmt->execute([$id]);
    $plan = $stmt->fetch();

    if (!$plan) {
        echo json_encode(['ok' => false, 'msg' => 'Plan no encontrado.']);
        exit;
    }

    // Platos
    $platos = $pdo->prepare(
        'SELECT titulo, descripcion, imagen_url FROM platos WHERE plan_gastronomico_id = ? ORDER BY orden ASC'
    );
    $platos->execute([$id]);
    $plan['platos'] = $platos->fetchAll();

    // Reseñas
    $res = $pdo->prepare(
        'SELECT puntuacion, comentario, autor_nombre, autor_cargo
         FROM resenas WHERE plan_gastronomico_id = ? AND estado = \'publicado\'
         ORDER BY created_at DESC LIMIT 3'
    );
    $res->execute([$id]);
    $plan['resenas'] = $res->fetchAll();

    $plan['precio_formateado'] = number_format((float)$plan['precio_desde'], 0, ',', '.');

    echo json_encode(['ok' => true, 'plan' => $plan]);

} else {
    // ── Lista de restaurantes y sus planes gastronómicos ─────────────────
    $restaurantes = $pdo->query(
        'SELECT r.id, r.nombre, r.descripcion, r.direccion, r.tipo, r.icono
         FROM restaurantes r WHERE r.estado = \'activo\' ORDER BY r.nombre ASC'
    )->fetchAll();

    foreach ($restaurantes as &$rest) {
        $pla = $pdo->prepare(
            'SELECT id, titulo, categoria, precio_desde, moneda, puntuacion, etiqueta
             FROM planes_gastronomicos
             WHERE restaurante_id = ? AND estado = \'activo\'
             ORDER BY puntuacion DESC'
        );
        $pla->execute([$rest['id']]);
        $rest['planes'] = $pla->fetchAll();
    }

    echo json_encode(['ok' => true, 'restaurantes' => $restaurantes]);
}
