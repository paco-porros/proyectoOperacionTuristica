<?php
/**
 * planes.php — Catálogo completo de planes turísticos.
 * Funciona tanto para visitantes anónimos como para usuarios autenticados.
 */
require_once __DIR__ . '/includes/session.php';

$logueado  = estaLogueado();
$usuario   = $logueado ? usuarioActual() : null;
$avatarSrc = null;
if ($logueado) {
  $avatarUrl = $usuario['avatar_url'] ?? null;
  $avatarSrc = $avatarUrl ?: 'https://ui-avatars.com/api/?name=' . urlencode($usuario['nombre']) . '&background=afedf0&color=054da4&size=40';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Planes Turísticos | Operador Turístico y Gastronomico</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Manrope:wght@300;400;500;600;700&display=swap" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,300,0,0" />
  <link rel="stylesheet" href="/estilos/style-planes.css" />
</head>

<body class="altura-minima columna-flexible sin-desbordamiento-horizontal">

  <!-- ═══════════════════════════════════════════
       NAVEGACIÓN — condicional logueado / anónimo
  ═══════════════════════════════════════════ -->
  <nav class="navegacion">
    <div class="navegacion__logo">
      <?php if ($logueado): ?>
        <a href="home.php">Operador Turístico y Gastronomico | Santa Rosa de Cabal</a>
      <?php else: ?>
        <a href="index.php">Operador Turístico y Gastronomico | Santa Rosa de Cabal</a>
      <?php endif; ?>
    </div>

    <div class="navegacion__enlaces">
      <?php if ($logueado): ?>
        <a class="navegacion__enlace" href="home.php">Inicio</a>
      <?php else: ?>
        <a class="navegacion__enlace" href="index.php#servicios">Servicios</a>
      <?php endif; ?>
      <a class="navegacion__enlace navegacion__enlace--activo" href="planes.php">Planes Turísticos</a>
      <a class="navegacion__enlace" href="index.php#gastronomia">Ofertas Gastronómicas</a>
      <?php if ($logueado && in_array($usuario['rol'], ['admin', 'editor'])): ?>
        <a class="navegacion__enlace" href="dashboard-administrador.php">Dashboard</a>
      <?php endif; ?>
    </div>

    <?php if ($logueado): ?>
      <!-- Usuario autenticado: saludo + avatar -->
      <div class="navegacion__zona-usuario">
        <span class="navegacion__saludo">
          Hola, <?= htmlspecialchars(explode(' ', $usuario['nombre'])[0]) ?>
        </span>
        <button class="navegacion__boton-notificaciones" id="boton-notificaciones" title="Notificaciones">
          <span class="material-symbols-outlined">notifications</span>
        </button>
        <div class="navegacion__avatar-envoltorio">
          <div class="navegacion__avatar" id="avatar-usuario" title="Mi cuenta">
            <img alt="Perfil de usuario" class="navegacion__avatar-imagen" src="<?= htmlspecialchars($avatarSrc) ?>" />
          </div>
          <div class="navegacion__dropdown" id="menu-avatar">
            <div class="navegacion__dropdown-encabezado">
              <?= htmlspecialchars($usuario['nombre']) ?>
            </div>
            <?php if (in_array($usuario['rol'], ['admin', 'editor'])): ?>
              <a href="dashboard-administrador.php" class="navegacion__dropdown-item">
                <span class="material-symbols-outlined">admin_panel_settings</span> Dashboard
              </a>
            <?php endif; ?>
            <button id="btn-logout" class="navegacion__dropdown-salir">
              <span class="material-symbols-outlined">logout</span> Cerrar Sesión
            </button>
          </div>
        </div>
      </div>
    <?php else: ?>
      <!-- Visitante anónimo: botón iniciar sesión -->
      <button class="navegacion__boton"><a href="login.php">Iniciar Sesión</a></button>
    <?php endif; ?>
  </nav>

  <!-- ═══════════════════════════════════════════
       CABECERA DE PÁGINA
  ═══════════════════════════════════════════ -->
  <header class="cabecera-planes">
    <div class="cabecera-planes__fondo">
      <img class="cabecera-planes__imagen" src="img/fondoPortada.jpg" alt="Paisaje Santa Rosa de Cabal" />
      <div class="cabecera-planes__veladura"></div>
    </div>
    <div class="cabecera-planes__contenido">
      <span class="seccion__supratitulo">Catálogo completo</span>
      <h1 class="cabecera-planes__titulo">Planes Turísticos</h1>
      <p class="cabecera-planes__subtitulo">Descubre todas las experiencias disponibles en Santa Rosa de Cabal</p>
    </div>
  </header>

  <!-- ═══════════════════════════════════════════
       SECCIÓN PRINCIPAL — lista de planes
  ═══════════════════════════════════════════ -->
  <main class="planes-main">
    <div class="planes-main__interior">

      <!-- Encabezado con contador -->
      <div class="planes-main__encabezado">
        <div>
          <span class="seccion__supratitulo">Selección premium</span>
          <h2 class="seccion__titulo">Todos los Planes</h2>
        </div>
        <span class="planes-main__contador" id="contador-planes"></span>
      </div>

      <!-- Skeleton inicial -->
      <div class="planes-main__lista" id="lista-planes">
        <div class="tarjeta-plan panel-vidrio borde-vidrio" style="opacity:.4; pointer-events:none;">
          <div class="tarjeta-plan__imagen-envoltorio" style="background:#d5b9b2;"></div>
          <div class="tarjeta-plan__cuerpo">
            <div style="height:1rem; background:#d5b9b2; border-radius:.5rem; margin-bottom:1rem;"></div>
            <div style="height:.75rem; background:#d5b9b2; border-radius:.5rem; width:60%;"></div>
          </div>
        </div>
        <div class="tarjeta-plan panel-vidrio borde-vidrio" style="opacity:.25; pointer-events:none;">
          <div class="tarjeta-plan__imagen-envoltorio" style="background:#d5b9b2;"></div>
          <div class="tarjeta-plan__cuerpo">
            <div style="height:1rem; background:#d5b9b2; border-radius:.5rem; margin-bottom:1rem;"></div>
            <div style="height:.75rem; background:#d5b9b2; border-radius:.5rem; width:60%;"></div>
          </div>
        </div>
        <div class="tarjeta-plan panel-vidrio borde-vidrio" style="opacity:.15; pointer-events:none;">
          <div class="tarjeta-plan__imagen-envoltorio" style="background:#d5b9b2;"></div>
          <div class="tarjeta-plan__cuerpo">
            <div style="height:1rem; background:#d5b9b2; border-radius:.5rem; margin-bottom:1rem;"></div>
            <div style="height:.75rem; background:#d5b9b2; border-radius:.5rem; width:60%;"></div>
          </div>
        </div>
      </div>

    </div>
  </main>

  <!-- ═══════════════════════════════════════════
       PIE DE PÁGINA — idéntico al index.php
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
          <?php if ($logueado): ?>
            <li><a href="home.php">Inicio</a></li>
          <?php else: ?>
            <li><a href="index.php">Inicio</a></li>
          <?php endif; ?>
          <li><a href="index.php#servicios">Nuestros Servicios</a></li>
          <li><a href="planes.php">Planes Turísticos</a></li>
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
          <li class="pie__contacto-item">
            <a href="mailto:info@srcabal.com">info@srcabal.com</a>
          </li>
          <li class="pie__contacto-item">
            <a href="mailto:talentohumano@srcabal.com">talentohumano@srcabal.com</a>
          </li>
          <li class="pie__contacto-item">
            +57 (606) 364-0000
          </li>
          <li class="pie__contacto-item">
            Santa Rosa de Cabal, Risaralda
          </li>
        </ul>
      </div>
    </div>
    <div class="pie__inferior">
      <p>© 2026 Operador Turístico y Gastronomico | Santa Rosa de Cabal
        <span class="pie__separador">|</span>
        <a href="legales.php#privacidad">Política de Privacidad</a>
        <span class="pie__separador">|</span>
        <a href="legales.php#terminos">Términos de Servicio</a>
      </p>
    </div>
  </footer>

  <script>
  /* ════════════════════════════════════════════════════
     PLANES.PHP — carga todos los planes desde la BD
  ════════════════════════════════════════════════════ */

  const IMG_DEFAULT = [
    'https://lh3.googleusercontent.com/aida-public/AB6AXuAqrYiszyVO9b5FJvsR6QPJYsqdT1GRR_mYpjMlPFWjxafulqCduopzqvrPvj3cGmkgQeW_gjY7KmGaR5QZF2cJ5nICZ31UU4QvbFe3hQvpbR3fcNkkiv4PtLHKxPWQaj-m5KvRDvg0BuZM5v2Yx1AK6kk9MFOE-pR83n69Fo6EBiS5FK3SaeIGMpyP68nUPiP33pURX9LjDtg5JKbLoWzeTPEuwFkFJaSET8JWQ8qGwPGcqgJeNRD9fPPvAF0pxhu2cdEaD2v2fV72',
    'https://lh3.googleusercontent.com/aida-public/AB6AXuALZtJ2omilYGHRKrG9x1nwXdgbTd-CLY_tL4_aKJOyNqaE4VqDD1vM-0EgYDbk1b_zQ_Bzwjaj2EL6Jug0TSFK9Q-JoDVcC2zHmYPXmPc1zpUCs9D1NvLosOig4fhqukT6z_XkcA1TJTQ3z1izAZLfA9vpyDhxsvALNQAMbO2erryl9DKcG8sCxPDIU7jo1FK55zo9d97q8LeZ2rLMly0ow4RZmUu4fjbaSEys11NOkjGrGZcKesu4mXcv8VQI1xP2rcQ5i7H1vsOt',
    'https://lh3.googleusercontent.com/aida-public/AB6AXuCGZj183QMPNxArCGjvbpRWb8nIGbfTxzu7KKzsUUwoup4opovL_2ax7638xLfXWsTVJ1v0jzegSzjCRDmhpBuss3YW7lxJc7zEbj7DB-JGTHSRu24vIj4BFSU3WGWajncHqKD4tDTAe35nhNLhcqzJlbtmsBL8xsEyZpj1x8ZGtQeKs0da94l9vSQaoV_fGaAYWq2uPje0-b20Ubur2D8KewvzKCXDnLgnFTPtyV1wnH9ZvVnLXKAA8g6BO7Ol7BbeE0q0uXo3eBwO',
  ];

  /* ── Misma función renderPlan del index ── */
  function renderPlan(plan, idx) {
    const img      = plan.imagen_hero_url || IMG_DEFAULT[idx % IMG_DEFAULT.length];
    const dias     = plan.duracion_dias > 1 ? `${plan.duracion_dias} Días` : `${plan.duracion_dias} Día`;
    const estrellas = parseFloat(plan.puntuacion).toFixed(1);
    const resenas  = plan.total_resenas;
    const precio   = plan.precio_formateado;
    const moneda   = plan.moneda === 'COP' ? 'COP' : '';

    return `
    <div class="tarjeta-plan panel-vidrio borde-vidrio">
      <div class="tarjeta-plan__imagen-envoltorio">
        <img class="tarjeta-plan__imagen" src="${img}" alt="${plan.titulo}" loading="lazy"/>
      </div>
      <div class="tarjeta-plan__cuerpo">
        <div>
          <div class="tarjeta-plan__fila-superior">
            <h3 class="tarjeta-plan__titulo">${plan.titulo}</h3>
            <span class="tarjeta-plan__etiqueta">${plan.etiqueta || 'Tour'}</span>
          </div>
          <div class="tarjeta-plan__meta">
            <span class="tarjeta-plan__meta-item">
              <span class="material-symbols-outlined">schedule</span> ${dias}
            </span>
            <span class="tarjeta-plan__meta-item">
              <span class="material-symbols-outlined">star</span> ${estrellas} (${resenas} reseñas)
            </span>
          </div>
        </div>
        <div class="tarjeta-plan__pie">
          <div>
            <span class="tarjeta-plan__precio-desde">Desde</span>
            <span class="tarjeta-plan__precio">
              $${precio} <span class="tarjeta-plan__precio-unidad">${moneda} / pers</span>
            </span>
          </div>
          <button class="tarjeta-plan__boton" onclick="window.location.href='detalles-turisticos.php?id=${plan.id}'">
            Ver detalles <span class="material-symbols-outlined">visibility</span>
          </button>
        </div>
      </div>
    </div>`;
  }

  /* ── Carga SIN límite para mostrar todos ── */
  async function cargarTodosLosPlanes() {
    try {
      const res  = await fetch('/ajax/planes_turisticos.php');
      const data = await res.json();
      const lista = document.getElementById('lista-planes');

      if (data.ok && data.planes.length) {
        lista.innerHTML = data.planes.map((p, i) => renderPlan(p, i)).join('');
        const contador = document.getElementById('contador-planes');
        if (contador) {
          contador.textContent = `${data.planes.length} ${data.planes.length === 1 ? 'plan disponible' : 'planes disponibles'}`;
        }
      } else {
        lista.innerHTML = '<p class="planes-main__vacio">No hay planes disponibles en este momento.</p>';
      }
    } catch (err) {
      console.error('Error cargando planes:', err);
    }
  }

  <?php if ($logueado): ?>
  /* ── Menú avatar toggle ── */
  document.getElementById('avatar-usuario').addEventListener('click', (e) => {
    e.stopPropagation();
    const menu = document.getElementById('menu-avatar');
    menu.classList.toggle('navegacion__dropdown--visible');
  });
  document.addEventListener('click', () => {
    const menu = document.getElementById('menu-avatar');
    if (menu) menu.classList.remove('navegacion__dropdown--visible');
  });

  /* ── Logout AJAX ── */
  document.getElementById('btn-logout').addEventListener('click', async () => {
    await fetch('/ajax/logout.php', { method: 'POST' });
    window.location.href = '/index.php';
  });
  <?php endif; ?>

  document.addEventListener('DOMContentLoaded', cargarTodosLosPlanes);
  </script>

</body>
</html>