<?php
/**
 * ajax/admin_usuarios.php — GESTIÓN DE USUARIOS
 * GET  → Lista usuarios (paginada) + búsqueda + stats
 * POST → Crea nuevo usuario (con password hash)
 * PUT  → Edita usuario (nombre, email, rol, estado, password)
 * DELETE → Elimina usuario (no puede auto-eliminarse)
 * Auth: admin/editor
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/session.php';

// BLOQUE 1 - Autenticación y Autorización
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
   BLOQUE 2 - GET: Listar Usuarios
   - Obtiene page y query de búsqueda
   - LIMIT 10 usuarios por página
   - Busca por nombre O email (LIKE)
   - Calcula total de páginas
   - Devuelve { usuarios, total, pagina, paginas, stats }
*/
if ($method === 'GET') {
    $page  = max(1, (int)($_GET['page']  ?? 1));
    $limit = 10;
    $off   = ($page - 1) * $limit;
    $busca = '%' . trim($_GET['q'] ?? '') . '%';

    $total = $pdo->prepare(
        'SELECT COUNT(*) FROM usuarios WHERE nombre LIKE ? OR email LIKE ?'
    );
    $total->execute([$busca, $busca]);
    $totalRows = (int)$total->fetchColumn();

    $stmt = $pdo->prepare(
        'SELECT id, nombre, email, rol, estado, avatar_url, created_at
         FROM usuarios WHERE nombre LIKE :b1 OR email LIKE :b2
         ORDER BY id DESC LIMIT :lim OFFSET :off'
    );
    $stmt->bindValue(':b1',  $busca);
    $stmt->bindValue(':b2',  $busca);
    $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':off', $off,   PDO::PARAM_INT);
    $stmt->execute();
    $usuarios = $stmt->fetchAll();

    // Estadísticas rápidas
    $stats = $pdo->query(
        'SELECT
            COUNT(*) AS total,
            SUM(estado = \'activo\') AS activos
         FROM usuarios'
    )->fetch();

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

/*
   BLOQUE 3 - POST: Crear Usuario
   - Extrae nombre, email, password, rol, estado
   - Valida campos requeridos
   - Verifica que email no esté en uso
   - Hash password con PASSWORD_BCRYPT
   - INSERT nuevo usuario
   - Devuelve { ok: true, id }
*/
if ($method === 'POST') {
    $d       = json_decode(file_get_contents('php://input'), true) ?? $_POST;
    $nombre  = trim($d['nombre']  ?? '');
    $email   = trim($d['email']   ?? '');
    $pass    = trim($d['password'] ?? '');
    $rol     = $d['rol']    ?? 'cliente';
    $estado  = $d['estado'] ?? 'activo';

    if (!$nombre || !$email || !$pass) {
        echo json_encode(['ok' => false, 'msg' => 'Nombre, email y contraseña son requeridos.']);
        exit;
    }

    $check = $pdo->prepare('SELECT id FROM usuarios WHERE email = ? LIMIT 1');
    $check->execute([$email]);
    if ($check->fetch()) {
        echo json_encode(['ok' => false, 'msg' => 'El email ya está en uso.']);
        exit;
    }

    $hash = password_hash($pass, PASSWORD_BCRYPT);
    $ins  = $pdo->prepare(
        'INSERT INTO usuarios (nombre, email, contrasena, rol, estado) VALUES (?,?,?,?,?)'
    );
    $ins->execute([$nombre, $email, $hash, $rol, $estado]);

    echo json_encode(['ok' => true, 'msg' => 'Usuario creado correctamente.', 'id' => (int)$pdo->lastInsertId()]);
    exit;
}

/*
   BLOQUE 4 - PUT: Editar Usuario
   - Extrae id, nombre, email, rol, estado, password
   - Valida datos requeridos
   - Verifica email no duplicado en otro usuario
   - UPDATE dinámico (solo actualiza campos proporcionados)
   - Si password se proporciona: hash con PASSWORD_BCRYPT
   - Devuelve { ok: true, msg }
*/
if ($method === 'PUT') {
    $d      = json_decode(file_get_contents('php://input'), true) ?? [];
    $id     = (int)($d['id']     ?? 0);
    $nombre = trim($d['nombre']  ?? '');
    $email  = trim($d['email']   ?? '');
    $rol    = $d['rol']    ?? null;
    $estado = $d['estado'] ?? null;

    if (!$id || !$nombre || !$email) {
        echo json_encode(['ok' => false, 'msg' => 'Datos incompletos.']);
        exit;
    }

    // Verificar email duplicado en otro usuario
    $dup = $pdo->prepare('SELECT id FROM usuarios WHERE email = ? AND id <> ? LIMIT 1');
    $dup->execute([$email, $id]);
    if ($dup->fetch()) {
        echo json_encode(['ok' => false, 'msg' => 'El email ya está en uso por otro usuario.']);
        exit;
    }

    $fields = ['nombre = ?', 'email = ?'];
    $vals   = [$nombre, $email];

    if ($rol)    { $fields[] = 'rol = ?';    $vals[] = $rol; }
    if ($estado) { $fields[] = 'estado = ?'; $vals[] = $estado; }
    if (!empty($d['password'])) {
        $fields[] = 'contrasena = ?';
        $vals[]   = password_hash($d['password'], PASSWORD_BCRYPT);
    }

    $vals[] = $id;
    $pdo->prepare('UPDATE usuarios SET ' . implode(', ', $fields) . ' WHERE id = ?')->execute($vals);

    echo json_encode(['ok' => true, 'msg' => 'Usuario actualizado.']);
    exit;
}

/*
   BLOQUE 5 - DELETE: Eliminar Usuario
   - Obtiene id del body o GET
   - No permite auto-eliminarse (usuario no puede borrar su propia cuenta)
   - DELETE donde id = ?
   - Devuelve { ok: true, msg }
*/
if ($method === 'DELETE') {
    $d  = json_decode(file_get_contents('php://input'), true) ?? [];
    $id = (int)($d['id'] ?? $_GET['id'] ?? 0);

    if (!$id) {
        echo json_encode(['ok' => false, 'msg' => 'ID requerido.']);
        exit;
    }

    // No puede eliminarse a sí mismo
    if ($id === $usuario['id']) {
        echo json_encode(['ok' => false, 'msg' => 'No puedes eliminarte a ti mismo.']);
        exit;
    }

    $pdo->prepare('DELETE FROM usuarios WHERE id = ?')->execute([$id]);
    echo json_encode(['ok' => true, 'msg' => 'Usuario eliminado.']);
    exit;
}

// BLOQUE 6 - Método no soportado
echo json_encode(['ok' => false, 'msg' => 'Método no soportado.']);

    if (!$nombre || !$email || !$pass) {
        echo json_encode(['ok' => false, 'msg' => 'Nombre, email y contraseña son requeridos.']);
        exit;
    }

    $check = $pdo->prepare('SELECT id FROM usuarios WHERE email = ? LIMIT 1');
    $check->execute([$email]);
    if ($check->fetch()) {
        echo json_encode(['ok' => false, 'msg' => 'El email ya está en uso.']);
        exit;
    }

    $hash = password_hash($pass, PASSWORD_BCRYPT);
    $ins  = $pdo->prepare(
        'INSERT INTO usuarios (nombre, email, contrasena, rol, estado) VALUES (?,?,?,?,?)'
    );
    $ins->execute([$nombre, $email, $hash, $rol, $estado]);

    echo json_encode(['ok' => true, 'msg' => 'Usuario creado correctamente.', 'id' => (int)$pdo->lastInsertId()]);
    exit;
}

// ── PUT: editar usuario ───────────────────────────────────────────────────
if ($method === 'PUT') {
    $d      = json_decode(file_get_contents('php://input'), true) ?? [];
    $id     = (int)($d['id']     ?? 0);
    $nombre = trim($d['nombre']  ?? '');
    $email  = trim($d['email']   ?? '');
    $rol    = $d['rol']    ?? null;
    $estado = $d['estado'] ?? null;

    if (!$id || !$nombre || !$email) {
        echo json_encode(['ok' => false, 'msg' => 'Datos incompletos.']);
        exit;
    }

    // Verificar email duplicado en otro usuario
    $dup = $pdo->prepare('SELECT id FROM usuarios WHERE email = ? AND id <> ? LIMIT 1');
    $dup->execute([$email, $id]);
    if ($dup->fetch()) {
        echo json_encode(['ok' => false, 'msg' => 'El email ya está en uso por otro usuario.']);
        exit;
    }

    $fields = ['nombre = ?', 'email = ?'];
    $vals   = [$nombre, $email];

    if ($rol)    { $fields[] = 'rol = ?';    $vals[] = $rol; }
    if ($estado) { $fields[] = 'estado = ?'; $vals[] = $estado; }
    if (!empty($d['password'])) {
        $fields[] = 'contrasena = ?';
        $vals[]   = password_hash($d['password'], PASSWORD_BCRYPT);
    }

    $vals[] = $id;
    $pdo->prepare('UPDATE usuarios SET ' . implode(', ', $fields) . ' WHERE id = ?')->execute($vals);

    echo json_encode(['ok' => true, 'msg' => 'Usuario actualizado.']);
    exit;
}

// ── DELETE: eliminar usuario ──────────────────────────────────────────────
if ($method === 'DELETE') {
    $d  = json_decode(file_get_contents('php://input'), true) ?? [];
    $id = (int)($d['id'] ?? $_GET['id'] ?? 0);

    if (!$id) {
        echo json_encode(['ok' => false, 'msg' => 'ID requerido.']);
        exit;
    }

    // No puede eliminarse a sí mismo
    if ($id === $usuario['id']) {
        echo json_encode(['ok' => false, 'msg' => 'No puedes eliminarte a ti mismo.']);
        exit;
    }

    $pdo->prepare('DELETE FROM usuarios WHERE id = ?')->execute([$id]);
    echo json_encode(['ok' => true, 'msg' => 'Usuario eliminado.']);
    exit;
}

echo json_encode(['ok' => false, 'msg' => 'Método no soportado.']);
