<?php
/**
 * ajax/agregar_gastronomico.php
 * Endpoint AJAX: POST { plan_id, fecha_inicio, num_adultos }
 * Reserva un plan gastronómico. Solo usuarios logueados.
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/session.php';

if (!estaLogueado()) {
    echo json_encode(['ok' => false, 'msg' => 'Debes iniciar sesión para reservar.', 'login' => true]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'msg' => 'Método no permitido.']);
    exit;
}

$data        = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$plan_id     = (int)($data['plan_id']     ?? 0);
$fecha       = trim($data['fecha_inicio'] ?? date('Y-m-d'));
$num_adultos = max(1, (int)($data['num_adultos'] ?? 1));

if ($plan_id <= 0) {
    echo json_encode(['ok' => false, 'msg' => 'Plan no válido.']);
    exit;
}

$pdo = getDB();

// Obtener precio del plan gastronómico
$stmt = $pdo->prepare(
    'SELECT id, titulo, precio_desde, moneda FROM planes_gastronomicos WHERE id = ? AND estado = \'activo\' LIMIT 1'
);
$stmt->execute([$plan_id]);
$plan = $stmt->fetch();

if (!$plan) {
    echo json_encode(['ok' => false, 'msg' => 'Plan gastronómico no encontrado.']);
    exit;
}

// Verificar duplicado pendiente/confirmada
$dup = $pdo->prepare(
    'SELECT id FROM reservas WHERE usuario_id = ? AND plan_gastronomico_id = ? AND estado IN (\'pendiente\',\'confirmada\') LIMIT 1'
);
$dup->execute([$_SESSION['usuario_id'], $plan_id]);
if ($dup->fetch()) {
    echo json_encode(['ok' => false, 'msg' => 'Ya tienes este plan gastronómico en tu lista de reservas.']);
    exit;
}

$precio_total = $plan['precio_desde'] * $num_adultos;

$ins = $pdo->prepare(
    'INSERT INTO reservas (usuario_id, tipo_plan, plan_gastronomico_id, fecha_inicio, num_adultos, precio_total, moneda, estado)
     VALUES (?, \'gastronomico\', ?, ?, ?, ?, ?, \'pendiente\')'
);
$ins->execute([
    $_SESSION['usuario_id'],
    $plan_id,
    $fecha,
    $num_adultos,
    $precio_total,
    $plan['moneda'],
]);

echo json_encode([
    'ok'  => true,
    'msg' => '✓ "' . $plan['titulo'] . '" agregado a tu cuenta correctamente.',
]);