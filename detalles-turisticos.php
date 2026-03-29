<?php
/**
 * detalles-turisticos.php
 * Carga el detalle del plan vía AJAX (evita conflictos de recarga).
 * El botón "Agregar plan" solo aparece si el usuario está logueado.
 */
require_once __DIR__ . '/includes/session.php';

$planId   = (int)($_GET['id'] ?? 0);
$logueado = estaLogueado();
$usuario  = usuarioActual();
?>
<!DOCTYPE html>
<html class="light" lang="es">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>Detalle Plan Turístico | Operador Santa Rosa de Cabal</title>
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
      <a class="navegacion__enlace navegacion__enlace--activo" href="index.php#planes">Planes Turísticos</a>
      <a class="navegacion__enlace" href="index.php#gastronomia">Ofertas Gastronómicas</a>
    </div>
    <?php if ($logueado): ?>
      <button class="navegacion__boton" id="btn-logout-det">
        Hola, <?= htmlspecialchars($usuario['nombre']) ?> · Salir
      </button>
    <?php else: ?>
      <button class="navegacion__boton"><a href="login.php">Iniciar Sesión</a></button>
    <?php endif; ?>
  </nav>

  <!-- CONTENIDO PRINCIPAL — se rellena vía AJAX -->
  <main class="contenido-principal" id="contenido-plan">

    <!-- Skeleton de carga -->
    <section class="seccion-hero seccion-hero--skeleton">
      <div class="hero-skeleton__fondo"></div>
      <div class="hero-contenido">
        <div class="hero-texto">
          <span class="hero-etiqueta hero-etiqueta--skeleton">Cargando…</span>
          <h1 class="hero-titulo hero-titulo--skeleton">Cargando plan turístico</h1>
        </div>
      </div>
    </section>

    <section class="seccion-cuerpo">
      <div class="cuadricula-principal">
        <div class="columna-contenido">

          <!-- Descripción -->
          <div class="tarjeta-cristal">
            <h2 class="titulo-card">Los Termales de Santa Rosa de Cabal</h2>
            <p class="descripcion-card">
              Sumérgete en un oasis de bienestar en los Termales de Santa Rosa de Cabal,
              un paraíso natural donde las aguas termales se entrelazan con la exuberante vegetación.
              Este destino emblemático ofrece una experiencia única de relajación y rejuvenecimiento,
              rodeado de paisajes impresionantes y una atmósfera serena. Disfruta de baños termales al aire libre,
              masajes revitalizantes y senderos naturales que te conectarán con la belleza pura de la naturaleza colombiana.  
            </p>
            <div class="cuadricula-destacados">
              <div class="destacado-item">
                <span class="material-symbols-outlined icono-filled">hotel</span>
                <span class="destacado-item__etiqueta">3 Noches</span>
              </div>
              <div class="destacado-item">
                <span class="material-symbols-outlined icono-filled">restaurant</span>
                <span class="destacado-item__etiqueta">Desayuno Incluido</span>
              </div>
              <div class="destacado-item">
                <span class="material-symbols-outlined icono-filled">spa</span>
                <span class="destacado-item__etiqueta">Sesión Spa</span>
              </div>
            </div>
          </div>

          <!-- Itinerario -->
          <div class="itinerario">
            <h3 class="itinerario__titulo">Tu Itinerario</h3>
            <div class="itinerario__lista">

              <div class="dia-item dia-item--cristal">
                <div class="dia-item__numero dia-item__numero--primario">01</div>
                <div class="dia-item__contenido">
                  <h4 class="dia-item__titulo">Llegada y Atardecer en Termales de Santa Rosa de Cabal</h4>
                  <p class="dia-item__descripcion">Bienvenida VIP y traslado privado a cabaña en Termales de Santa Rosa de Cabal</p>
                </div>
              </div>

              <div class="dia-item dia-item--contenedor">
                <div class="dia-item__numero dia-item__numero--secundario">02</div>
                <div class="dia-item__contenido">
                  <h4 class="dia-item__titulo">Exploración de senderos en reserva natural</h4>
                  <p class="dia-item__descripcion">Tour privado por los senderos de la reserva natural, con guía especializado.</p>
                </div>
              </div>

              <div class="dia-item dia-item--cristal">
                <div class="dia-item__numero dia-item__numero--primario">03</div>
                <div class="dia-item__contenido">
                  <h4 class="dia-item__titulo">Día de Relajación</h4>
                  <p class="dia-item__descripcion">Día completo en los Termales de Santa Rosa de Cabal, disfrutando de los baños termales y masajes revitalizantes en piscina de lodo.</p>
                </div>
              </div>

            </div>
          </div>

        </div>
        <aside class="columna-lateral">
          <div class="lateral-sticky">

            <!-- Tarjeta de reserva -->
            <div class="tarjeta-reserva">
              <h3 class="tarjeta-reserva__titulo">Reserva tu Escapada</h3>
              <p class="tarjeta-reserva__subtitulo">Disponibilidad limitada para la temporada.</p>
              <div class="reserva-campos">
                <div class="reserva-campo">
                  <span class="reserva-campo__etiqueta">Fecha</span>
                  <span class="reserva-campo__valor">15 Sep - 20 Sep</span>
                </div>
                <div class="reserva-campo">
                  <span class="reserva-campo__etiqueta">Personas</span>
                  <span class="reserva-campo__valor">2 Adultos</span>
                </div>
              </div>
              <button class="boton-reservar">Reservar Ahora</button>
              <p class="reserva-nota">Cancelación gratuita hasta 15 días antes.</p>
            </div>

            <!-- Galería -->
            <div class="tarjeta-cristal tarjeta-galeria">
              <h3 class="galeria__titulo">Galería Visual</h3>
              <div class="galeria__cuadricula">
                <div class="galeria__item">
                  <img
                    class="galeria__imagen"
                    alt="Iglesia con cúpula azul en Santorini"
                    src="img/termales-santa-rosa.jpg"
                  />
                </div>
                <div class="galeria__item">
                  <img
                    class="galeria__imagen"
                    alt="Piscina infinita con vistas al Mediterráneo"
                    src="img/termales.jpg"
                  />
                </div>
                <div class="galeria__item galeria__item--blur">
                  <img
                    class="galeria__imagen"
                    alt="Casas coloridas en pueblo costero mediterráneo"
                    src="img/santarosa.webp"
                  />
                </div>
                <div class="galeria__item galeria__item--mas">
                  <span class="galeria__mas-texto">+2</span>
                  <div class="galeria__mas-overlay"></div>
                </div>
              </div>
            </div>

            <!-- Qué incluye -->
            <div class="tarjeta-incluye">
              <h3 class="tarjeta-incluye__titulo">Qué incluye</h3>
              <ul class="lista-incluye">
                <li class="incluye-item">
                  <span class="material-symbols-outlined icono-filled icono-check">check_circle</span>
                  Vuelos ida y vuelta (Clase Business)
                </li>
                <li class="incluye-item">
                  <span class="material-symbols-outlined icono-filled icono-check">check_circle</span>
                  Alojamiento en Cabañas de Lujo con vistas panorámicas
                </li>
                <li class="incluye-item">
                  <span class="material-symbols-outlined icono-filled icono-check">check_circle</span>
                  Guía privado políglota
                </li>
                <li class="incluye-item">
                  <span class="material-symbols-outlined icono-filled icono-check">check_circle</span>
                  Seguro de viaje Premium
                </li>
              </ul>
            </div>

          </div>
        </aside>
      </div>
    </section>
  </main>

  <!-- Toast de notificación AJAX -->
  <div id="toast-plan" class="toast-plan"></div>

  <!-- Modal: seleccionar fecha y personas -->
  <div id="modal-agregar" class="modal-overlay">
    <div class="modal-caja">
      <h3 class="modal-titulo">Agregar a mi cuenta</h3>
      <div id="modal-alerta" class="modal-alerta"></div>
      <div class="modal-campo">
        <label class="modal-etiqueta">Fecha de inicio</label>
        <input id="modal-fecha" type="date" class="modal-input"/>
      </div>
      <div class="modal-campo modal-campo--ultimo">
        <label class="modal-etiqueta">Número de adultos</label>
        <input id="modal-adultos" type="number" min="1" max="20" value="2" class="modal-input"/>
      </div>
      <div class="modal-acciones">
        <button id="modal-cancelar" class="modal-btn modal-btn--cancelar">Cancelar</button>
        <button id="modal-confirmar" class="modal-btn modal-btn--confirmar">Confirmar reserva</button>
      </div>
    </div>
  </div>

  <!-- PIE DE PÁGINA -->
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
<script src="/scripts/script-detalles-turisticos.js"></script>
</body>
</html>