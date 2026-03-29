<?php
/**
 * login.php — FORMULARIO DE INICIO DE SESIÓN
 * Autenticación vía AJAX POST a ajax/login.php
 * Redirige a dashboard si ya está logueado
 */

// BLOQUE 1 - Detectar si ya está logueado
// Si sí: redirigir a dashboard (admin/editor) o home (cliente)
require_once __DIR__ . '/includes/session.php';

// Si ya está logueado, redirigir
if (estaLogueado()) {
    $u = usuarioActual();
    header('Location: ' . (in_array($u['rol'], ['admin','editor']) ? '/dashboard-administrador.php' : '/home.php'));
    exit;
}
?>
<!DOCTYPE html>
<html class="claro" lang="es">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>Iniciar Sesión</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
  <script src="tailwind.config.js"></script>
  <link rel="stylesheet" href="./estilos/style-login.css">
  <style>
    .contenedor-input { position: relative; }
    .icono-ojo { position: absolute; right: 10px; cursor: pointer; color: #aaa; }
  </style>
</head>
<body class="fondo-principal fuente-cuerpo texto-superficie seleccion-primaria sin-desbordamiento-horizontal">

  <nav class="barra-navegacion">
    <div class="logo-marca">
      <a href="index.php">Operador Turístico y Gastronomico | Santa Rosa de Cabal</a></div>
  </nav>

  <main class="contenedor-principal">
    <div class="capa-fondo">
      <img alt="Cadena de montañas neblinosas" class="imagen-fondo" src="./img/fondo_login.png"/>
      <div class="superposicion-radial"></div>
    </div>

    <section class="seccion-tarjeta">
      <div class="tarjeta-cristal">

        <header class="encabezado-tarjeta">
          <div class="contenedor-icono-marca">
            <span class="material-symbols-outlined icono-explorar">travel_explore</span>
          </div>
          <h1 class="titulo-bienvenida">Bienvenido de nuevo</h1>
          <p class="subtitulo-bienvenida">Inicia sesión para planear tu próxima aventura</p>
        </header>

        <!-- Mensaje de estado AJAX -->
        <div id="alerta-login" style="display:none; margin-bottom:1rem; padding:.75rem 1rem; border-radius:.5rem; font-size:.875rem; font-weight:600; text-align:center;"></div>

        <form class="formulario-login" id="formulario-login" novalidate>
          <div class="grupo-campo">
            <label class="etiqueta-campo" for="correo">Correo electrónico</label>
            <div class="contenedor-input">
              <span class="material-symbols-outlined icono-input">mail</span>
              <input class="campo-input" id="correo" name="email" placeholder="viajero@ejemplo.com" type="email" autocomplete="email"/>
            </div>
          </div>

          <div class="grupo-campo">
            <div class="fila-etiqueta-contrasena">
              <label class="etiqueta-campo" for="contrasena">Contraseña</label>
              <a class="enlace-olvide-contrasena" href="#">¿Olvidaste tu contraseña?</a>
            </div>
            <div class="contenedor-input">
              <span class="material-symbols-outlined icono-input">lock</span>
              <input class="campo-input" id="contrasena" name="password" placeholder="••••••••" type="password" autocomplete="current-password"/>
              <span class="material-symbols-outlined icono-ojo" id="togglePassword">visibility</span>
            </div>
          </div>

          <button class="boton-iniciar-sesion" type="submit" id="btn-login">Iniciar Sesión</button>
        </form>

        <footer class="pie-tarjeta">
          <p class="texto-registro">
            ¿No tienes una cuenta?
            <a class="enlace-registro" href="registro.php">Regístrate aquí</a>
          </p>
        </footer>

      </div>
    </section>
  </main>

  <footer class="pie-pagina">
    <div class="logo-pie">Operador Turístico y Gastronomico | Santa Rosa de Cabal</div>
    <div class="enlaces-pie">
      <a class="enlace-pie" href="#">Privacy Policy</a>
      <a class="enlace-pie" href="#">Terms of Service</a>
      <a class="enlace-pie" href="#">Support</a>
      <a class="enlace-pie" href="#">Contact</a>
    </div>
    <div class="copyright-pie">© 2024 Operador Turístico y Gastronomico | Santa Rosa de Cabal.</div>
  </footer>

  <script src="/scripts/script-login.js"></script>
</body>
</html>