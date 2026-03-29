<?php
/**
 * detalles-gastronomicos.php — DETALLE DE PLAN GASTRONÓMICO
 * Página de detalle con platos, restaurante y reserva AJAX
 * GET ?id=N requerido, carga datos vía AJAX de gastronomicos.php
 */

// BLOQUE 1 - Validar ID del plan y estado de autenticación
// $planId de GET para cargar vía AJAX
// $logueado detecta si muestra botón "Reservar"
require_once __DIR__ . '/includes/session.php';

$planId  = (int)($_GET['id'] ?? 0);
$logueado = estaLogueado();
$usuario  = usuarioActual();
?>
<!DOCTYPE html>
<html class="light" lang="es">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>Detalle Gastronómico | Operador Turístico y Gastronomico</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="/estilos/style-detalles-turisticos.css"/>
</head>
<body class="cuerpo-principal">

  <!-- ═══════════════════════════════════════════
       NAVEGACIÓN
  ═══════════════════════════════════════════ -->
  <nav class="navegacion">
    <div class="navegacion__logo">
      <a href="index.php">Operador Turístico y Gastronomico | Santa Rosa de Cabal</a>
    </div>
    <div class="navegacion__enlaces">
      <a class="navegacion__enlace" href="index.php#servicios">Servicios</a>
      <a class="navegacion__enlace" href="index.php#planes">Planes Turísticos</a>
      <a class="navegacion__enlace navegacion__enlace--activo" href="index.php#gastronomia">Gastronomía</a>
    </div>
    <?php if ($logueado): ?>
      <button class="navegacion__boton" id="btn-cerrar-sesion-gastro">
        Hola, <?= htmlspecialchars($usuario['nombre']) ?> · Salir
      </button>
    <?php else: ?>
      <button class="navegacion__boton"><a href="/login.php">Iniciar Sesión</a></button>
    <?php endif; ?>
  </nav>

  <!-- CONTENIDO PRINCIPAL — rellenado por AJAX -->
  <main class="contenido-principal" id="contenido-gastro">

    <!-- Skeleton de carga -->
    <section class="seccion-hero seccion-hero--skeleton">
      <div class="hero-skeleton__fondo"></div>
      <div class="hero-contenido">
        <div class="hero-texto">
          <span class="hero-etiqueta hero-etiqueta--skeleton">Cargando…</span>
          <h1 class="hero-titulo hero-titulo--skeleton">Cargando información gastronómica</h1>
        </div>
      </div>
    </section>

    <section class="seccion-cuerpo">
      <div class="cuadricula-principal">
        <div class="columna-contenido">
          <div class="tarjeta-cristal" style="opacity:.3;min-height:12rem;"></div>
        </div>
        <aside class="columna-lateral">
          <div class="tarjeta-cristal" style="opacity:.3;min-height:10rem;"></div>
        </aside>
      </div>
    </section>
  </main>

  <!-- Toast de notificación -->
  <div id="toast-plan" class="toast-plan"></div>

  <!-- Modal: seleccionar fecha y personas -->
  <div id="modal-agregar" class="modal-overlay">
    <div class="modal-caja">
      <h3 class="modal-titulo">Reservar plan gastronómico</h3>
      <div id="modal-alerta" class="modal-alerta"></div>
      <div class="modal-campo">
        <label class="modal-etiqueta">Fecha de visita</label>
        <input id="modal-fecha" type="date" class="modal-input"/>
      </div>
      <div class="modal-campo modal-campo--ultimo">
        <label class="modal-etiqueta">Número de personas</label>
        <input id="modal-adultos" type="number" min="1" max="20" value="2" class="modal-input"/>
      </div>
      <div class="modal-acciones">
        <button id="modal-cancelar" class="modal-btn modal-btn--cancelar">Cancelar</button>
        <button id="modal-confirmar" class="modal-btn modal-btn--confirmar">Confirmar reserva</button>
      </div>
    </div>
  </div>

  <!-- ═══════════════════════════════════════════
       PIE DE PÁGINA
  ═══════════════════════════════════════════ -->
  <footer class="pie">
    <div class="pie__cuadricula">
      <div>
        <span class="pie__logo">Operador Turístico y Gastronomico | Santa Rosa de Cabal</span>
        <p class="pie__eslogan">Redefiniendo el lujo en cada travesía. Tu horizonte, nuestra pasión.</p>
        <div class="pie__redes">
          <button class="pie__boton-red"><span class="material-symbols-outlined">public</span></button>
          <button class="pie__boton-red"><span class="material-symbols-outlined">share</span></button>
        </div>
      </div>
      <div>
        <h4 class="pie__columna-titulo">Enlaces rápidos</h4>
        <ul class="pie__lista">
          <li><a href="index.php">Inicio</a></li>
          <li><a href="index.php#servicios">Nuestros Servicios</a></li>
          <li><a href="index.php#planes">Planes Turísticos</a></li>
          <li><a href="index.php#gastronomia">Ofertas Gastronómicas</a></li>
        </ul>
      </div>
      <div>
        <h4 class="pie__columna-titulo"><a href="nosotros.php">Sobre nosotros</a></h4>
        <ul class="pie__lista">
          <li><a href="nosotros.php#mision-vision">Misión y Visión</a></li>
          <li><a href="nosotros.php#equipo">Equipo Ejecutivo</a></li>
          <li><a href="nosotros.php#carreras">Carreras</a></li>
        </ul>
      </div>
      <div>
        <h4 class="pie__columna-titulo"><a href="servicios.php">Servicios</a></h4>
        <ul class="pie__lista">
          <li><a href="servicios.php#alojamiento">Alojamiento</a></li>
          <li><a href="servicios.php#transporte">Transporte</a></li>
          <li><a href="servicios.php#alimentacion">Alimentación</a></li>
          <li><a href="servicios.php#entretenimiento">Entretenimiento</a></li>
        </ul>
      </div>
      <div>
        <h4 class="pie__columna-titulo"><a href="legales.php">Legales y Ayuda</a></h4>
        <ul class="pie__lista">
          <li><a href="legales.php#terminos">Términos y Condiciones</a></li>
          <li><a href="legales.php#privacidad">Privacidad</a></li>
          <li><a href="legales.php#faqs">FAQs</a></li>
        </ul>
      </div>
      <div>
        <h4 class="pie__columna-titulo">Contacto</h4>
        <ul class="pie__lista-contacto">
          <li class="pie__contacto-item"><a href="mailto:info@srcabal.com">info@srcabal.com</a></li>
          <li class="pie__contacto-item"><a href="mailto:talentohumano@srcabal.com">talentohumano@srcabal.com</a></li>
          <li class="pie__contacto-item">+57 (606) 364-0000</li>
          <li class="pie__contacto-item">Santa Rosa de Cabal, Risaralda</li>
        </ul>
      </div>
    </div>
    <div class="pie__inferior">
      <p>© 2026 Operador Turístico y Gastronomico | Santa Rosa de Cabal
        <span class="pie__separador">|</span>
        <a href="#">Política de Privacidad</a>
        <span class="pie__separador">|</span>
        <a href="#">Términos de Servicio</a>
      </p>
    </div>
  </footer>

  <script>
  const PLAN_ID  = <?= $planId ?>;
  const LOGUEADO = <?= $logueado ? 'true' : 'false' ?>;
</script>
<script src="/scripts/script-detalles-gastronomicos.js"></script>

</body>
</html>