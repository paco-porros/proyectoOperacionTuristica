<?php
/**
 * ajax/admin_usuarios.php — GESTIÓN DE USUARIOS
 * GET    → Lista usuarios paginados con filtros (búsqueda, rol, estado)
 * POST   → Crea un nuevo usuario
 * PUT    → Edita un usuario existente (nombre, email, rol, estado, password)
 * DELETE → Elimina un usuario (no puede eliminarse a sí mismo)
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
   BLOQUE 2 — GET: Listar Usuarios
   - Paginación: page, limit=10
   - Filtros: q (búsqueda por nombre/email), rol, estado
   - Devuelve { ok, usuarios[], total, pagina, paginas, stats }
────────────────────────────────────────────────────── */
if ($method === 'GET') {
    $page  = max(1, (int)($_GET['page']   ?? 1));
    $limit = 10;
    $off   = ($page - 1) * $limit;
    $busca = '%' . trim($_GET['q']        ?? '') . '%';
    $filtroRol    = trim($_GET['rol']     ?? '');
    $filtroEstado = trim($_GET['estado']  ?? '');

    // Condiciones dinámicas
    $where  = ['(nombre LIKE :b1 OR email LIKE :b2)'];
    $params = [':b1' => $busca, ':b2' => $busca];

    $roles_validos = ['admin', 'editor', 'viewer', 'cliente'];
    if ($filtroRol && in_array($filtroRol, $roles_validos, true)) {
        $where[]          = 'rol = :rol';
        $params[':rol']   = $filtroRol;
    }

    $estados_validos = ['activo', 'inactivo'];
    if ($filtroEstado && in_array($filtroEstado, $estados_validos, true)) {
        $where[]            = 'estado = :estado';
        $params[':estado']  = $filtroEstado;
    }

    $whereSQL = 'WHERE ' . implode(' AND ', $where);

    // Contar total para paginación
    $stmtCount = $pdo->prepare("SELECT COUNT(*) FROM usuarios $whereSQL");
    $stmtCount->execute($params);
    $totalRows = (int)$stmtCount->fetchColumn();

    // Consulta principal
    $sql = "SELECT id, nombre, email, rol, estado, avatar_url, created_at
            FROM usuarios
            $whereSQL
            ORDER BY id DESC
            LIMIT :lim OFFSET :off";

    $stmt = $pdo->prepare($sql);
    foreach ($params as $k => $v) {
        $stmt->bindValue($k, $v);
    }
    $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':off', $off,   PDO::PARAM_INT);
    $stmt->execute();
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Estadísticas generales
    $stats = $pdo->query(
        "SELECT
            COUNT(*)                        AS total,
            SUM(estado = 'activo')          AS activos,
            SUM(estado = 'inactivo')        AS inactivos,
            SUM(rol = 'admin')              AS admins,
            SUM(rol = 'editor')             AS editores,
            SUM(rol = 'cliente')            AS clientes
         FROM usuarios"
    )->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'ok'       => true,
        'usuarios' => $usuarios,
        'total'    => $totalRows,
        'pagina'   => $page,
        'paginas'  => (int) ceil($totalRows / $limit),
        'stats'    => $stats,
    ]);
    exit;
}

/* ──────────────────────────────────────────────────────
   BLOQUE 3 — POST: Crear Usuario
   - Valida nombre, email, password, rol, estado
   - Verifica que el email no esté ya en uso
   - Hashea la contraseña con password_hash
   - Devuelve { ok, msg, id }
────────────────────────────────────────────────────── */
if ($method === 'POST') {
    $d      = json_decode(file_get_contents('php://input'), true) ?? [];
    $nombre = trim($d['nombre']   ?? '');
    $email  = trim($d['email']    ?? '');
    $pass   = trim($d['password'] ?? '');
    $rol    = trim($d['rol']      ?? 'cliente');
    $estado = trim($d['estado']   ?? 'activo');

    $roles_validos   = ['admin', 'editor', 'viewer', 'cliente'];
    $estados_validos = ['activo', 'inactivo'];

    // Validaciones
    if (!$nombre || !$email || !$pass) {
        echo json_encode(['ok' => false, 'msg' => 'Nombre, email y contraseña son requeridos.']);
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['ok' => false, 'msg' => 'El email no tiene un formato válido.']);
        exit;
    }
    if (!in_array($rol, $roles_validos, true)) {
        echo json_encode(['ok' => false, 'msg' => 'Rol no válido.']);
        exit;
    }
    if (!in_array($estado, $estados_validos, true)) {
        echo json_encode(['ok' => false, 'msg' => 'Estado no válido.']);
        exit;
    }
    if (strlen($pass) < 6) {
        echo json_encode(['ok' => false, 'msg' => 'La contraseña debe tener al menos 6 caracteres.']);
        exit;
    }

    // Verificar email único
    $chk = $pdo->prepare('SELECT id FROM usuarios WHERE email = ? LIMIT 1');
    $chk->execute([$email]);
    if ($chk->fetch()) {
        echo json_encode(['ok' => false, 'msg' => 'Ya existe un usuario con ese email.']);
        exit;
    }

    $hash = password_hash($pass, PASSWORD_DEFAULT);

    $ins = $pdo->prepare(
        'INSERT INTO usuarios (nombre, email, password, rol, estado) VALUES (?, ?, ?, ?, ?)'
    );
    $ins->execute([$nombre, $email, $hash, $rol, $estado]);

    echo json_encode([
        'ok'  => true,
        'msg' => 'Usuario creado correctamente.',
        'id'  => (int)$pdo->lastInsertId(),
    ]);
    exit;
}

/* ──────────────────────────────────────────────────────
   BLOQUE 4 — PUT: Editar Usuario
   - Valida id, nombre, email, rol, estado
   - La contraseña solo se actualiza si se envía
   - Verifica que el email no esté en uso por otro usuario
   - Devuelve { ok, msg }
────────────────────────────────────────────────────── */
if ($method === 'PUT') {
    $d      = json_decode(file_get_contents('php://input'), true) ?? [];
    $id     = (int)($d['id']       ?? 0);
    $nombre = trim($d['nombre']    ?? '');
    $email  = trim($d['email']     ?? '');
    $pass   = trim($d['password']  ?? '');
    $rol    = trim($d['rol']       ?? '');
    $estado = trim($d['estado']    ?? '');

    $roles_validos   = ['admin', 'editor', 'viewer', 'cliente'];
    $estados_validos = ['activo', 'inactivo'];

    if (!$id) {
        echo json_encode(['ok' => false, 'msg' => 'ID requerido.']);
        exit;
    }
    if (!$nombre || !$email) {
        echo json_encode(['ok' => false, 'msg' => 'Nombre y email son requeridos.']);
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['ok' => false, 'msg' => 'El email no tiene un formato válido.']);
        exit;
    }
    if ($rol && !in_array($rol, $roles_validos, true)) {
        echo json_encode(['ok' => false, 'msg' => 'Rol no válido.']);
        exit;
    }
    if ($estado && !in_array($estado, $estados_validos, true)) {
        echo json_encode(['ok' => false, 'msg' => 'Estado no válido.']);
        exit;
    }
    if ($pass && strlen($pass) < 6) {
        echo json_encode(['ok' => false, 'msg' => 'La contraseña debe tener al menos 6 caracteres.']);
        exit;
    }

    // Verificar que el usuario existe
    $chk = $pdo->prepare('SELECT id, rol, estado FROM usuarios WHERE id = ? LIMIT 1');
    $chk->execute([$id]);
    $row = $chk->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        echo json_encode(['ok' => false, 'msg' => 'Usuario no encontrado.']);
        exit;
    }

    // Verificar email único (excluir el propio usuario)
    $chkEmail = $pdo->prepare('SELECT id FROM usuarios WHERE email = ? AND id != ? LIMIT 1');
    $chkEmail->execute([$email, $id]);
    if ($chkEmail->fetch()) {
        echo json_encode(['ok' => false, 'msg' => 'Ese email ya está en uso por otro usuario.']);
        exit;
    }

    $nuevoRol    = $rol    ?: $row['rol'];
    $nuevoEstado = $estado ?: $row['estado'];

    if ($pass) {
        // Actualizar con nueva contraseña
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $pdo->prepare(
            'UPDATE usuarios SET nombre = ?, email = ?, password = ?, rol = ?, estado = ? WHERE id = ?'
        )->execute([$nombre, $email, $hash, $nuevoRol, $nuevoEstado, $id]);
    } else {
        // Actualizar sin tocar la contraseña
        $pdo->prepare(
            'UPDATE usuarios SET nombre = ?, email = ?, rol = ?, estado = ? WHERE id = ?'
        )->execute([$nombre, $email, $nuevoRol, $nuevoEstado, $id]);
    }

    echo json_encode(['ok' => true, 'msg' => 'Usuario actualizado correctamente.']);
    exit;
}

/* ──────────────────────────────────────────────────────
   BLOQUE 5 — DELETE: Eliminar Usuario
   - No permite que un admin se elimine a sí mismo
   - No permite eliminar al último admin del sistema
   - Devuelve { ok, msg }
────────────────────────────────────────────────────── */
if ($method === 'DELETE') {
    $d  = json_decode(file_get_contents('php://input'), true) ?? [];
    $id = (int)($d['id'] ?? $_GET['id'] ?? 0);

    if (!$id) {
        echo json_encode(['ok' => false, 'msg' => 'ID requerido.']);
        exit;
    }

    // Evitar que el usuario se elimine a sí mismo
    if ($id === (int)$usuario['id']) {
        echo json_encode(['ok' => false, 'msg' => 'No puedes eliminar tu propia cuenta.']);
        exit;
    }

    $chk = $pdo->prepare('SELECT id, rol FROM usuarios WHERE id = ? LIMIT 1');
    $chk->execute([$id]);
    $row = $chk->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        echo json_encode(['ok' => false, 'msg' => 'Usuario no encontrado.']);
        exit;
    }

    // Proteger al último admin del sistema
    if ($row['rol'] === 'admin') {
        $countAdmins = (int)$pdo->query("SELECT COUNT(*) FROM usuarios WHERE rol = 'admin'")->fetchColumn();
        if ($countAdmins <= 1) {
            echo json_encode(['ok' => false, 'msg' => 'No puedes eliminar al único administrador del sistema.']);
            exit;
        }
    }

    $pdo->prepare('DELETE FROM usuarios WHERE id = ?')->execute([$id]);
    echo json_encode(['ok' => true, 'msg' => 'Usuario eliminado correctamente.']);
    exit;
}

// BLOQUE 6 — Método no soportado
http_response_code(405);
echo json_encode(['ok' => false, 'msg' => 'Método no soportado.']);