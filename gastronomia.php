<?php
/**
 * gastronomia.php — Catálogo completo de ofertas gastronómicas.
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
  <title>Ofertas Gastronómicas | Operador Turístico y Gastronomico</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Manrope:wght@300;400;500;600;700&display=swap" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,300,0,0" />
  <link rel="stylesheet" href="/estilos/style-gastronomia.css" />
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
      <a class="navegacion__enlace" href="planes.php">Planes Turísticos</a>
      <a class="navegacion__enlace navegacion__enlace--activo" href="gastronomia.php">Ofertas Gastronómicas</a>
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
  <header class="cabecera-gastro">
    <div class="cabecera-gastro__fondo">
      <img class="cabecera-gastro__imagen" src="img/fondoPortada.jpg" alt="Gastronomía Santa Rosa de Cabal" />
      <div class="cabecera-gastro__veladura"></div>
    </div>
    <div class="cabecera-gastro__contenido">
      <span class="seccion__supratitulo">Sabores de Santa Rosa</span>
      <h1 class="cabecera-gastro__titulo">Ofertas Gastronómicas</h1>
      <p class="cabecera-gastro__subtitulo">Explora los sabores auténticos y las experiencias culinarias de Santa Rosa de Cabal</p>
    </div>
  </header>

  <!-- ═══════════════════════════════════════════
       SECCIÓN PRINCIPAL — lista de ofertas
  ═══════════════════════════════════════════ -->
  <main class="gastro-main">
    <div class="gastro-main__interior">

      <!-- Encabezado con contador -->
      <div class="gastro-main__encabezado">
        <div>
          <span class="seccion__supratitulo">Selección culinaria</span>
          <h2 class="seccion__titulo">Todas las Ofertas</h2>
        </div>
        <span class="gastro-main__contador" id="contador-gastro"></span>
      </div>

      <!-- Cuadrícula dinámica — se rellena con AJAX -->
      <div class="gastro-main__cuadricula" id="cuadricula-gastro">
        <!-- Skeletons mientras carga -->
        <div class="tarjeta-gastro" style="opacity:.4;pointer-events:none;">
          <div class="tarjeta-gastro__imagen-envoltorio" style="background:#d5b9b2;height:13rem;"></div>
          <div class="tarjeta-gastro__cuerpo"><div style="height:.8rem;background:#d5b9b2;border-radius:.5rem;margin-bottom:.75rem;"></div><div style="height:.6rem;background:#d5b9b2;border-radius:.5rem;width:60%;"></div></div>
        </div>
        <div class="tarjeta-gastro" style="opacity:.25;pointer-events:none;">
          <div class="tarjeta-gastro__imagen-envoltorio" style="background:#d5b9b2;height:13rem;"></div>
          <div class="tarjeta-gastro__cuerpo"><div style="height:.8rem;background:#d5b9b2;border-radius:.5rem;margin-bottom:.75rem;"></div><div style="height:.6rem;background:#d5b9b2;border-radius:.5rem;width:60%;"></div></div>
        </div>
        <div class="tarjeta-gastro" style="opacity:.15;pointer-events:none;">
          <div class="tarjeta-gastro__imagen-envoltorio" style="background:#d5b9b2;height:13rem;"></div>
          <div class="tarjeta-gastro__cuerpo"><div style="height:.8rem;background:#d5b9b2;border-radius:.5rem;margin-bottom:.75rem;"></div><div style="height:.6rem;background:#d5b9b2;border-radius:.5rem;width:60%;"></div></div>
        </div>
      </div>

    </div>
  </main>

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
          <?php if ($logueado): ?>
            <li><a href="home.php">Inicio</a></li>
          <?php else: ?>
            <li><a href="index.php">Inicio</a></li>
          <?php endif; ?>
          <li><a href="index.php#servicios">Nuestros Servicios</a></li>
          <li><a href="planes.php">Planes Turísticos</a></li>
          <li><a href="gastronomia.php">Ofertas Gastronómicas</a></li>
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
          <li class="pie__contacto-item">+57 (606) 364-0000</li>
          <li class="pie__contacto-item">Santa Rosa de Cabal, Risaralda</li>
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
     GASTRONOMIA.PHP — carga todas las ofertas desde la BD
  ════════════════════════════════════════════════════ */

  function renderTarjetaGastro(plan) {
    const precio    = plan.precio_formateado;
    const moneda    = plan.moneda === 'COP' ? 'COP' : plan.moneda;
    const estrellas = parseFloat(plan.puntuacion).toFixed(1);
    const img       = plan.imagen_hero_url || '/img/fondoPortada.jpg';
    const desc      = (plan.descripcion || '').substring(0, 130);

    return `
    <div class="tarjeta-gastro grupo-imagen">
      <div class="tarjeta-gastro__imagen-envoltorio">
        <img class="tarjeta-gastro__imagen" src="${img}" alt="${plan.titulo}" loading="lazy"/>
        <span class="tarjeta-gastro__badge">${plan.etiqueta || ''}</span>
      </div>
      <div class="tarjeta-gastro__cuerpo panel-cristal borde-superior-cristal">
        <span class="tarjeta-gastro__restaurante">${plan.restaurante_nombre}</span>
        <h3 class="tarjeta-gastro__titulo">${plan.titulo}</h3>
        <p class="tarjeta-gastro__descripcion">${desc}${plan.descripcion && plan.descripcion.length > 130 ? '…' : ''}</p>
        <div class="tarjeta-gastro__meta">
          <span class="tarjeta-gastro__precio">$${precio} ${moneda}</span>
          <span class="tarjeta-gastro__calificacion">
            <span class="material-symbols-outlined">star</span> ${estrellas}
          </span>
        </div>
        <div class="acciones-tarjeta">
          <button class="boton-tarjeta boton-tarjeta--principal"
                  onclick="window.location.href='detalles-gastronomicos.php?id=${plan.id}'">
            Ver detalles <span class="material-symbols-outlined">arrow_forward</span>
          </button>
        </div>
      </div>
    </div>`;
  }

  /* ── Carga SIN límite — devuelve todas las ofertas activas ── */
  async function cargarTodasLasOfertas() {
    try {
      const res  = await fetch('/ajax/gastronomicos.php?sin_limite=1');
      const data = await res.json();
      const grid = document.getElementById('cuadricula-gastro');

      if (data.ok && data.planes.length) {
        grid.innerHTML = data.planes.map(p => renderTarjetaGastro(p)).join('');
        const contador = document.getElementById('contador-gastro');
        if (contador) {
          const n = data.planes.length;
          contador.textContent = `${n} ${n === 1 ? 'oferta disponible' : 'ofertas disponibles'}`;
        }
      } else {
        grid.innerHTML = '<p class="gastro-main__vacio">No hay ofertas disponibles en este momento.</p>';
      }
    } catch (err) {
      console.error('Error cargando gastronomía:', err);
    }
  }

  <?php if ($logueado): ?>
  document.getElementById('avatar-usuario').addEventListener('click', (e) => {
    e.stopPropagation();
    document.getElementById('menu-avatar').classList.toggle('navegacion__dropdown--visible');
  });
  document.addEventListener('click', () => {
    const menu = document.getElementById('menu-avatar');
    if (menu) menu.classList.remove('navegacion__dropdown--visible');
  });
  document.getElementById('btn-logout').addEventListener('click', async () => {
    await fetch('/ajax/logout.php', { method: 'POST' });
    window.location.href = '/index.php';
  });
  <?php endif; ?>

  document.addEventListener('DOMContentLoaded', cargarTodasLasOfertas);
  </script>

</body>
</html>