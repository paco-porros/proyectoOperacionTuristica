<?php
/**
 * ajax/gastronomicos.php — LISTAR PLANES GASTRONÓMICOS
 * GET ?id=N — detalle de un plan con platos y reseñas
 * GET ?limite=N | ?sin_limite=1 — lista plana de planes
 * GET (sin parámetros) — restaurantes agrupados con sus planes
 * Responde: { ok, plan|planes|restaurantes }
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes/db.php';

$pdo = getDB();
$id  = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    /*
       BLOQUE 1 - Detalle de plan gastronómico (GET ?id=N)
       - JOIN restaurantes para obtener ubicación, tipo, dirección
       - Solo estado='activo'
    */
    $stmt = $pdo->prepare(
        "SELECT pg.id, pg.titulo, pg.descripcion, pg.etiqueta, pg.categoria,
                pg.precio_desde, pg.moneda, pg.duracion_horas, pg.max_personas,
                pg.idiomas, pg.puntuacion, pg.total_resenas, pg.imagen_hero_url,
                r.nombre    AS restaurante_nombre,
                r.direccion AS restaurante_direccion,
                r.tipo      AS restaurante_tipo
         FROM planes_gastronomicos pg
         JOIN restaurantes r ON r.id = pg.restaurante_id
         WHERE pg.id = ? AND pg.estado = 'activo' LIMIT 1"
    );
    $stmt->execute([$id]);
    $plan = $stmt->fetch();

    if (!$plan) {
        echo json_encode(['ok' => false, 'msg' => 'Plan no encontrado.']);
        exit;
    }

    /*
       BLOQUE 2 - Cargar platos del plan
       - SELECT FROM platos ORDER BY orden
    */
    $platos = $pdo->prepare(
        'SELECT titulo, descripcion, imagen_url FROM platos
         WHERE plan_gastronomico_id = ? ORDER BY orden ASC'
    );
    $platos->execute([$id]);
    $plan['platos'] = $platos->fetchAll();

    /*
       BLOQUE 3 - Cargar reseñas publicadas
       - SELECT FROM resenas limit 3, más recientes
    */
    $res = $pdo->prepare(
        "SELECT puntuacion, comentario, autor_nombre, autor_cargo
         FROM resenas WHERE plan_gastronomico_id = ? AND estado = 'publicado'
         ORDER BY created_at DESC LIMIT 3"
    );
    $res->execute([$id]);
    $plan['resenas'] = $res->fetchAll();

    /*
       BLOQUE 4 - Formatear precio e imagen
       - precio: number_format con separador miles
       - imagen: fallback a /img/fondoPortada.jpg si vacío
    */
    $plan['precio_formateado'] = number_format((float)$plan['precio_desde'], 0, ',', '.');
    $plan['imagen_hero_url']   = !empty($plan['imagen_hero_url'])
        ? $plan['imagen_hero_url'] : '/img/fondoPortada.jpg';

    echo json_encode(['ok' => true, 'plan' => $plan]);

} else {

    $sinLimite = isset($_GET['sin_limite']) && $_GET['sin_limite'] === '1';
    $limite    = $sinLimite ? null : (int)($_GET['limite'] ?? 0);

    if ($sinLimite || $limite > 0) {
        /*
           BLOQUE 5 - Lista plana de planes (GET ?limite=N o ?sin_limite=1)
           - Si sin_limite: todos los activos
           - Si limite: SELECT LIMIT N
           - Incluye nombre restaurante
        */
        $sql = "SELECT pg.id, pg.titulo, pg.descripcion, pg.etiqueta, pg.categoria,
                       pg.precio_desde, pg.moneda, pg.puntuacion, pg.total_resenas,
                       pg.imagen_hero_url,
                       r.nombre AS restaurante_nombre
                FROM planes_gastronomicos pg
                JOIN restaurantes r ON r.id = pg.restaurante_id
                WHERE pg.estado = 'activo'
                ORDER BY pg.puntuacion DESC";

        if ($sinLimite) {
            $stmt = $pdo->query($sql);
        } else {
            $stmt = $pdo->prepare($sql . ' LIMIT ?');
            $stmt->execute([$limite]);
        }

        $planes = $stmt->fetchAll();

        /*
           BLOQUE 6 - Formatear respuesta
           - precio_formateado, imagen fallback
        */
        foreach ($planes as &$p) {
            $p['precio_formateado'] = number_format((float)$p['precio_desde'], 0, ',', '.');
            $p['imagen_hero_url']   = !empty($p['imagen_hero_url'])
                ? $p['imagen_hero_url'] : '/img/fondoPortada.jpg';
        }

        echo json_encode(['ok' => true, 'planes' => $planes]);

    } else {
        /*
           BLOQUE 7 - Lista agrupada: restaurantes → planes (GET sin parámetros)
           - SELECT restaurantes activos
           - Para cada: COUNT planes by restaurante_id
        */
        $restaurantes = $pdo->query(
            "SELECT r.id, r.nombre, r.descripcion, r.direccion, r.tipo, r.icono
             FROM restaurantes r WHERE r.estado = 'activo' ORDER BY r.nombre ASC"
        )->fetchAll();

        /*
           BLOQUE 8 - Anidar planes en cada restaurante
           - Para cada restaurante: SELECT planes ORDER BY puntuación
        */
        foreach ($restaurantes as &$rest) {
            $pla = $pdo->prepare(
                "SELECT id, titulo, categoria, precio_desde, moneda, puntuacion, etiqueta
                 FROM planes_gastronomicos
                 WHERE restaurante_id = ? AND estado = 'activo'
                 ORDER BY puntuacion DESC"
            );
            $pla->execute([$rest['id']]);
            $rest['planes'] = $pla->fetchAll();
        }

        echo json_encode(['ok' => true, 'restaurantes' => $restaurantes]);
    }
}