<?php
/**
 * includes/session.php
 * Gestión de sesión y helpers de autenticación
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/** Devuelve true si hay un usuario autenticado */
function estaLogueado(): bool {
    return isset($_SESSION['usuario_id']);
}

/** Devuelve el array del usuario en sesión o null */
function usuarioActual(): ?array {
    if (!estaLogueado()) return null;
    return [
        'id'     => $_SESSION['usuario_id'],
        'nombre' => $_SESSION['usuario_nombre'] ?? '',
        'email'  => $_SESSION['usuario_email']  ?? '',
        'rol'    => $_SESSION['usuario_rol']     ?? 'cliente',
    ];
}

/** Fuerza redirección si no hay sesión */
function requiereLogin(string $redireccion = '/login.php'): void {
    if (!estaLogueado()) {
        header('Location: ' . $redireccion);
        exit;
    }
}

/** Fuerza redirección si el rol no coincide */
function requiereRol(array $roles, string $redireccion = '/home.php'): void {
    $u = usuarioActual();
    if (!$u || !in_array($u['rol'], $roles, true)) {
        header('Location: ' . $redireccion);
        exit;
    }
}

/** Cierra la sesión y redirige */
function cerrarSesion(string $redireccion = '/login.php'): void {
    $_SESSION = [];
    session_destroy();
    header('Location: ' . $redireccion);
    exit;
}
