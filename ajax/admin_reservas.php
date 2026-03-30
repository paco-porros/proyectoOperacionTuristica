<?php
/**
 * ajax/admin_reservas.php — GESTIÓN DE RESERVAS
 * GET  → Lista reservas paginadas con filtros (estado, tipo, búsqueda)
 * PUT  → Edita una reserva (fecha, num_adultos, estado)
 * POST action=cambiar_estado → Cambia el estado de una reserva
 * DELETE → Elimina una reserva
 * Auth: admin/editor
 */

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/session.php';

/* ──────────────────────────────────────────────────────
   BLOQUE 1 — Autenticación y Autorización
   Verifica usuario logueado (401) y rol admin/editor (403)
────────────────────────────────────────────────────── */
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

/* ──────────────────────────────────────────────────────
   BLOQUE 2 — GET: Listar Reservas
   - Paginación: page, limit=10
   - Filtros: q (búsqueda por usuario/plan), estado, tipo
   - JOIN con usuarios, planes_turisticos y planes_gastronomicos
   - Devuelve { ok, reservas[], total, pagina, paginas, stats }
────────────────────────────────────────────────────── */
if ($method === 'GET') {
    $page   = max(1, (int)($_GET['page']  ?? 1));
    $limit  = 10;
    $off    = ($page - 1) * $limit;
    $busca  = '%' . trim($_GET['q']      ?? '') . '%';
    $filtroEstado = trim($_GET['estado'] ?? '');
    $filtroTipo   = trim($_GET['tipo']   ?? '');

    // Condiciones dinámicas
    $where  = ['(u.nombre LIKE :b1 OR u.email LIKE :b2 OR pt.titulo LIKE :b3 OR pg.titulo LIKE :b4)'];
    $params = [':b1' => $busca, ':b2' => $busca, ':b3' => $busca, ':b4' => $busca];

    $estados_validos = ['pendiente', 'confirmada', 'cancelada', 'completada'];
    if ($filtroEstado && in_array($filtroEstado, $estados_validos, true)) {
        $where[]            = 'r.estado = :estado';
        $params[':estado']  = $filtroEstado;
    }

    $tipos_validos = ['turistico', 'gastronomico'];
    if ($filtroTipo && in_array($filtroTipo, $tipos_validos, true)) {
        $where[]          = 'r.tipo_plan = :tipo';
        $params[':tipo']  = $filtroTipo;
    }

    $whereSQL = 'WHERE ' . implode(' AND ', $where);

    // Contar total para paginación
    $sqlCount = "SELECT COUNT(*) FROM reservas r
                 LEFT JOIN usuarios u             ON u.id  = r.usuario_id
                 LEFT JOIN planes_turisticos pt   ON pt.id = r.plan_turistico_id
                 LEFT JOIN planes_gastronomicos pg ON pg.id = r.plan_gastronomico_id
                 $whereSQL";
    $stmtCount = $pdo->prepare($sqlCount);
    $stmtCount->execute($params);
    $totalRows = (int)$stmtCount->fetchColumn();

    // Consulta principal
    $sql = "SELECT
                r.id,
                r.tipo_plan,
                r.estado,
                r.fecha_inicio,
                r.num_adultos,
                r.precio_total,
                r.moneda,
                r.created_at,
                u.nombre      AS usuario_nombre,
                u.email       AS usuario_email,
                COALESCE(pt.titulo, pg.titulo) AS plan_titulo
            FROM reservas r
            LEFT JOIN usuarios u              ON u.id  = r.usuario_id
            LEFT JOIN planes_turisticos pt    ON pt.id = r.plan_turistico_id
            LEFT JOIN planes_gastronomicos pg ON pg.id = r.plan_gastronomico_id
            $whereSQL
            ORDER BY r.id DESC
            LIMIT :lim OFFSET :off";

    $stmt = $pdo->prepare($sql);
    foreach ($params as $k => $v) {
        $stmt->bindValue($k, $v);
    }
    $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':off', $off,   PDO::PARAM_INT);
    $stmt->execute();
    $reservas = $stmt->fetchAll();

    // Formatear precio
    foreach ($reservas as &$r) {
        $r['precio_formateado'] = number_format((float)$r['precio_total'], 0, ',', '.');
    }

    // Estadísticas generales
    $stats = $pdo->query(
        "SELECT
            COUNT(*)                             AS total,
            SUM(estado = 'pendiente')            AS pendientes,
            SUM(estado = 'confirmada')           AS confirmadas,
            SUM(estado = 'cancelada')            AS canceladas,
            SUM(estado = 'completada')           AS completadas,
            SUM(tipo_plan = 'turistico')         AS turisticas,
            SUM(tipo_plan = 'gastronomico')      AS gastronomicas,
            COALESCE(SUM(precio_total), 0)       AS ingresos_totales
         FROM reservas"
    )->fetch();

    $stats['ingresos_formateados'] = number_format((float)$stats['ingresos_totales'], 0, ',', '.');

    echo json_encode([
        'ok'       => true,
        'reservas' => $reservas,
        'total'    => $totalRows,
        'pagina'   => $page,
        'paginas'  => (int) ceil($totalRows / $limit),
        'stats'    => $stats,
    ]);
    exit;
}

/* ──────────────────────────────────────────────────────
   BLOQUE 3 — PUT: Editar Reserva
   - Parsea JSON del body
   - Valida id, fecha, num_adultos, estado
   - Recalcula precio_total si cambia num_adultos
   - UPDATE con prepared statement
   - Devuelve { ok, msg }
────────────────────────────────────────────────────── */
if ($method === 'PUT') {
    $d           = json_decode(file_get_contents('php://input'), true) ?? [];
    $id          = (int)($d['id']          ?? 0);
    $fecha       = trim($d['fecha_inicio'] ?? '');
    $num_adultos = max(1, (int)($d['num_adultos'] ?? 1));
    $estado      = $d['estado'] ?? '';

    $estados_validos = ['pendiente', 'confirmada', 'cancelada', 'completada'];
    if (!$id) {
        echo json_encode(['ok' => false, 'msg' => 'ID requerido.']);
        exit;
    }
    if ($estado && !in_array($estado, $estados_validos, true)) {
        echo json_encode(['ok' => false, 'msg' => 'Estado no válido.']);
        exit;
    }

    // Obtener reserva actual para recalcular precio
    $sel = $pdo->prepare(
        'SELECT r.*, 
                COALESCE(pt.precio_desde, pg.precio_desde) AS precio_unitario
         FROM reservas r
         LEFT JOIN planes_turisticos pt    ON pt.id = r.plan_turistico_id
         LEFT JOIN planes_gastronomicos pg ON pg.id = r.plan_gastronomico_id
         WHERE r.id = ? LIMIT 1'
    );
    $sel->execute([$id]);
    $row = $sel->fetch();

    if (!$row) {
        echo json_encode(['ok' => false, 'msg' => 'Reserva no encontrada.']);
        exit;
    }

    $precio_total = (float)$row['precio_unitario'] * $num_adultos;
    $nuevo_estado = $estado ?: $row['estado'];
    $nueva_fecha  = $fecha  ?: $row['fecha_inicio'];

    $pdo->prepare(
        'UPDATE reservas SET fecha_inicio = ?, num_adultos = ?, precio_total = ?, estado = ? WHERE id = ?'
    )->execute([$nueva_fecha, $num_adultos, $precio_total, $nuevo_estado, $id]);

    echo json_encode(['ok' => true, 'msg' => 'Reserva actualizada correctamente.']);
    exit;
}

/* ──────────────────────────────────────────────────────
   BLOQUE 4 — POST action=cambiar_estado: Cambiar estado
   - Valida id y nuevo estado
   - UPDATE directo
   - Devuelve { ok, msg, estado }
────────────────────────────────────────────────────── */
if ($method === 'POST') {
    $d      = json_decode(file_get_contents('php://input'), true) ?? $_POST;
    $action = trim($d['action'] ?? '');
    $id     = (int)($d['id']    ?? 0);
    $estado = trim($d['estado'] ?? '');

    $estados_validos = ['pendiente', 'confirmada', 'cancelada', 'completada'];

    if ($action !== 'cambiar_estado' || !$id || !in_array($estado, $estados_validos, true)) {
        echo json_encode(['ok' => false, 'msg' => 'Parámetros inválidos.']);
        exit;
    }

    $check = $pdo->prepare('SELECT id FROM reservas WHERE id = ? LIMIT 1');
    $check->execute([$id]);
    if (!$check->fetch()) {
        echo json_encode(['ok' => false, 'msg' => 'Reserva no encontrada.']);
        exit;
    }

    $pdo->prepare('UPDATE reservas SET estado = ? WHERE id = ?')->execute([$estado, $id]);

    $labels = [
        'pendiente'  => 'marcada como pendiente',
        'confirmada' => 'confirmada',
        'cancelada'  => 'cancelada',
        'completada' => 'marcada como completada',
    ];

    echo json_encode([
        'ok'     => true,
        'msg'    => 'Reserva ' . $labels[$estado] . ' correctamente.',
        'estado' => $estado,
    ]);
    exit;
}

/* ──────────────────────────────────────────────────────
   BLOQUE 5 — DELETE: Eliminar Reserva
   - Solo elimina reservas canceladas o pendientes
   - Devuelve { ok, msg }
────────────────────────────────────────────────────── */
if ($method === 'DELETE') {
    $d  = json_decode(file_get_contents('php://input'), true) ?? [];
    $id = (int)($d['id'] ?? $_GET['id'] ?? 0);

    if (!$id) {
        echo json_encode(['ok' => false, 'msg' => 'ID requerido.']);
        exit;
    }

    $check = $pdo->prepare('SELECT id, estado FROM reservas WHERE id = ? LIMIT 1');
    $check->execute([$id]);
    $row = $check->fetch();

    if (!$row) {
        echo json_encode(['ok' => false, 'msg' => 'Reserva no encontrada.']);
        exit;
    }

    // Seguridad: solo se pueden eliminar reservas canceladas o pendientes
    if (!in_array($row['estado'], ['cancelada', 'pendiente'], true)) {
        echo json_encode(['ok' => false, 'msg' => 'Solo se pueden eliminar reservas canceladas o pendientes.']);
        exit;
    }

    $pdo->prepare('DELETE FROM reservas WHERE id = ?')->execute([$id]);
    echo json_encode(['ok' => true, 'msg' => 'Reserva eliminada correctamente.']);
    exit;
}

// BLOQUE 6 — Método no soportado
echo json_encode(['ok' => false, 'msg' => 'Método no soportado.']);