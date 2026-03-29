<?php
/**
 * includes/session.php — GESTIÓN DE SESIÓN Y AUTENTICACIÓN
 * Manejo centralizado de login/logout y helpers de permisos
 */

// BLOQUE 1 - Iniciar sesión PHP
// Si no existe sesión activa: session_start()
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
   BLOQUE 2 - estaLogueado()
   - Devuelve true si $_SESSION['usuario_id'] existe
   - rápida: solo verifica presencia de ID en sesión
*/
function estaLogueado(): bool {
    return isset($_SESSION['usuario_id']);
}

/*
   BLOQUE 3 - usuarioActual()
   - Retorna array con dato del usuario en sesión
   - campos: id, nombre, email, rol (default 'cliente')
   - Retorna null si no está logueado
*/
function usuarioActual(): ?array {
    if (!estaLogueado()) return null;
    return [
        'id'     => $_SESSION['usuario_id'],
        'nombre' => $_SESSION['usuario_nombre'] ?? '',
        'email'  => $_SESSION['usuario_email']  ?? '',
        'rol'    => $_SESSION['usuario_rol']     ?? 'cliente',
    ];
}

/*
   BLOQUE 4 - requiereLogin()
   - Fuerza redirección si NO hay sesión
   - Usado al inicio de páginas protegidas (home, dashboard, etc)
   - Parámetro: ruta redireccion (default /login.php)
*/
function requiereLogin(string $redireccion = '/login.php'): void {
    if (!estaLogueado()) {
        header('Location: ' . $redireccion);
        exit;
    }
}

/*
   BLOQUE 5 - requiereRol()
   - Verifica que usuario esté logueado Y su rol esté en lista permitida
   - Array de roles requeridos ej: ['admin', 'editor']
   - Redirige si NO cumple (default /home.php)
*/
function requiereRol(array $roles, string $redireccion = '/home.php'): void {
    $u = usuarioActual();
    if (!$u || !in_array($u['rol'], $roles, true)) {
        header('Location: ' . $redireccion);
        exit;
    }
}

/*
   BLOQUE 6 - cerrarSesion()
   - Limpia $_SESSION array
   - Destruye sesión con session_destroy()
   - Redirige a /login.php (o ruta especificada)
   - Usado por botón logout
*/
function cerrarSesion(string $redireccion = '/login.php'): void {
    $_SESSION = [];
    session_destroy();
    header('Location: ' . $redireccion);
    exit;
}
