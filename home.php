<?php
/**
 * home.php — Página principal para usuarios autenticados
 * Redirige al index si no hay sesión.
 */
require_once __DIR__ . '/includes/session.php';
requiereLogin('/index.php');

$usuario = usuarioActual();
$avatarUrl = $usuario['avatar_url'] ?? null;
$avatarSrc = $avatarUrl ?: 'https://ui-avatars.com/api/?name=' . urlencode($usuario['nombre']) . '&background=afedf0&color=054da4&size=40';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>Operador Turístico y Gastronomico | Santa Rosa de Cabal</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Manrope:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
  <script src="tailwind.config.js"></script>
  <link rel="stylesheet" href="/estilos/style-home.css"/>
</head>
<body class="altura-minima columna-flexible sin-desbordamiento-horizontal">

  <!-- =========================================================
       BARRA DE NAVEGACIÓN SUPERIOR
       ========================================================= -->
  <nav class="barra-navegacion">
    <div class="contenedor-navegacion">

      <!-- Logo + enlaces -->
      <div class="grupo-izquierdo-nav">
        <span class="logo-nav">Operador Turístico y Gastronomico | Santa Rosa de Cabal</span>
        <div class="menu-enlaces-nav">
          <a class="enlace-nav enlace-nav--activo" href="home.php">Destinos</a>
          <a class="enlace-nav" href="index.php">Planes</a>
          <a class="enlace-nav" href="detalles-gastronomicos.php">Gastronomía</a>
          <?php if (in_array($usuario['rol'], ['admin','editor'])): ?>
          <a class="enlace-nav" href="dashboard-administrador.php">Dashboard</a>
          <?php endif; ?>
        </div>
      </div>

      <!-- Acciones de usuario -->
      <div class="grupo-derecho-nav">
        <!-- Saludo visible -->
        <span style="font-size:.8rem;font-weight:600;color:var(--color-primario);font-family:var(--fuente-cuerpo);">
          Hola, <?= htmlspecialchars(explode(' ', $usuario['nombre'])[0]) ?>
        </span>
        <button class="boton-notificaciones" id="boton-notificaciones" title="Notificaciones">
          <span class="material-symbols-outlined icono-notificacion">notifications</span>
        </button>
        <!-- Avatar con menú dropdown -->
        <div style="position:relative;">
          <div class="avatar-usuario" id="avatar-usuario" title="Mi cuenta" style="cursor:pointer;">
            <img alt="Perfil de usuario" class="imagen-avatar" src="<?= htmlspecialchars($avatarSrc) ?>"/>
          </div>
          <!-- Dropdown menú -->
          <div id="menu-avatar" style="
            display:none; position:absolute; right:0; top:calc(100% + .5rem);
            background:#fff; border:1px solid #afedf0; border-radius:.75rem;
            box-shadow:0 10px 25px rgba(0,32,33,.12); padding:.5rem; min-width:180px; z-index:100;
          ">
            <div style="padding:.5rem .75rem;font-size:.75rem;font-weight:700;color:#306388;text-transform:uppercase;letter-spacing:.1em;border-bottom:1px solid #e5feff;margin-bottom:.25rem;">
              <?= htmlspecialchars($usuario['nombre']) ?>
            </div>
            <?php if (in_array($usuario['rol'], ['admin','editor'])): ?>
            <a href="dashboard-administrador.php" style="display:flex;align-items:center;gap:.5rem;padding:.5rem .75rem;font-size:.875rem;color:#054da4;font-weight:600;text-decoration:none;border-radius:.5rem;transition:background .2s;" onmouseover="this.style.background='#e5feff'" onmouseout="this.style.background='transparent'">
              <span class="material-symbols-outlined" style="font-size:1.125rem;">admin_panel_settings</span> Dashboard
            </a>
            <?php endif; ?>
            <button id="btn-logout-home" style="display:flex;align-items:center;gap:.5rem;padding:.5rem .75rem;font-size:.875rem;color:#ba1a1a;font-weight:600;background:transparent;border:none;cursor:pointer;border-radius:.5rem;width:100%;transition:background .2s;" onmouseover="this.style.background='rgba(186,26,26,.08)'" onmouseout="this.style.background='transparent'">
              <span class="material-symbols-outlined" style="font-size:1.125rem;">logout</span> Cerrar Sesión
            </button>
          </div>
        </div>
      </div>

    </div>
  </nav>

  <!-- =========================================================
       SECCIÓN HERO
       ========================================================= -->
  <section class="seccion-hero">
    <div class="capa-fondo-hero">
      <img alt="Paisaje de montaña" class="imagen-hero" src="img/fondoPortada.jpg"/>
      <div class="degradado-hero"></div>
    </div>
    <div class="contenido-hero">
      <h1 class="titulo-hero">Vive Esta Experiencia</h1>
      <p class="subtitulo-hero">Descubre a Santa Rosa donde la realidad y los sueños conectan.</p>
    </div>
  </section>

  <!-- =========================================================
       ITINERARIOS RECOMENDADOS (BENTO) — cargados desde BD
       ========================================================= -->
  <section class="seccion-itinerarios">
    <div class="encabezado-seccion-itinerarios">
      <div>
        <span class="etiqueta-seccion">Curado para ti</span>
        <h2 class="titulo-seccion">Itinerarios Recomendados</h2>
      </div>
      <button class="boton-ver-todos" id="boton-ver-todos-itinerarios">
        Ver todos
        <span class="material-symbols-outlined icono-flecha-ver-todos">arrow_forward</span>
      </button>
    </div>

    <!-- Cuadrícula bento — se rellena con AJAX -->
    <div class="cuadricula-bento" id="bento-planes">
      <!-- Skeleton principal -->
      <div class="tarjeta-bento tarjeta-bento--principal" style="background:#d5b9b2;opacity:.3;min-height:300px;"></div>
      <div class="columna-bento-lateral">
        <div class="tarjeta-bento" style="background:#d5b9b2;opacity:.2;"></div>
        <div class="tarjeta-bento" style="background:#d5b9b2;opacity:.15;"></div>
      </div>
    </div>
  </section>

  <!-- =========================================================
       OFERTAS GASTRONÓMICAS
       ========================================================= -->
  <section class="seccion-gastronomia">
    <div class="contenedor-gastronomia">
      <div class="encabezado-seccion-gastronomia">
        <div>
          <span class="etiqueta-seccion">Sabores Locales</span>
          <h2 class="titulo-seccion">Ofertas Gastronómicas</h2>
        </div>
        <button class="boton-ver-todos" id="boton-ver-todos-gastronomia">
          Ver todas
          <span class="material-symbols-outlined icono-flecha-ver-todos">arrow_forward</span>
        </button>
      </div>

      <div class="cuadricula-gastronomia">
        <div class="tarjeta-gastro grupo-imagen">
          <div class="tarjeta-gastro__imagen-envoltorio">
            <img alt="Trucha al Ajillo" class="tarjeta-gastro__imagen" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB711Yx-xQsszazQUBDqN1OqQYl4K8czjoq1Nka6XzAwDkrWX0IejB6EnX6bjk1X_vvcGNWiJIcqWzq4t5qYN1Opwg2Y8nMBxhqhzu_1R1ae4Q_8NhuJzyYxiUDDV8sQfFCgDo6LycuHewxR61A3BS_3oGw2yzY4JkuQeTM1brAg3oPmgaFMooN2Xcm4k2EjJs23RaG9pS7IWuUb7sc7WqOiWOGVE1VoaznK9VAT2w7bsR3Ycem5FSWtpZLVI9C8fuu1sUFmuMbahA"/>
            <span class="tarjeta-gastro__badge">Típico</span>
          </div>
          <div class="tarjeta-gastro__cuerpo panel-cristal borde-superior-cristal">
            <span class="tarjeta-gastro__restaurante">Restaurante El Ciervo Rojo</span>
            <h3 class="tarjeta-gastro__titulo">Trucha al Ajillo con Patacones</h3>
            <div class="tarjeta-gastro__meta">
              <span class="tarjeta-gastro__precio">$35,000 COP</span>
              <span class="tarjeta-gastro__calificacion"><span class="material-symbols-outlined">star</span> 4.9</span>
            </div>
            <div class="acciones-tarjeta">
              <button class="boton-tarjeta boton-tarjeta--blanco boton-tarjeta--pequeno" onclick="window.location.href='detalles-gastronomicos.php?id=4'">
                Ver info <span class="material-symbols-outlined">arrow_forward</span>
              </button>
            </div>
          </div>
        </div>

        <div class="tarjeta-gastro grupo-imagen">
          <div class="tarjeta-gastro__imagen-envoltorio">
            <img alt="Bandeja Paisa" class="tarjeta-gastro__imagen" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAjFlGpXujdfQF-8b9gRFHXxsmyP1MibGWZWjioRuKfNBI60Jq9HU_is02Emlcpmhz96F3sT4SZ6csyzaqJjVATkr96Vcy6-hQNGPmllHVL8NdTGVvqyGi0aXpa8iXv71B--3uE6f3aGwcmkiOmI8KfjXtOhUr1D01QKnfxdjnggBPIpZXa0f_V0daOaDcp_9GSF5JMimVgcZJr7yp3zUhhbbuSpmtsKKVQzCIEbxJb5X9pmZGIrVH6-nBXTHG44GxabvfF-7AGN-Y"/>
            <span class="tarjeta-gastro__badge">Insignia</span>
          </div>
          <div class="tarjeta-gastro__cuerpo panel-cristal borde-superior-cristal">
            <span class="tarjeta-gastro__restaurante">La Fogata</span>
            <h3 class="tarjeta-gastro__titulo">Bandeja Paisa Premium</h3>
            <div class="tarjeta-gastro__meta">
              <span class="tarjeta-gastro__precio">$28,000 COP</span>
              <span class="tarjeta-gastro__calificacion"><span class="material-symbols-outlined">star</span> 4.8</span>
            </div>
            <div class="acciones-tarjeta">
              <button class="boton-tarjeta boton-tarjeta--blanco boton-tarjeta--pequeno" onclick="window.location.href='detalles-gastronomicos.php?id=2'">
                Ver info <span class="material-symbols-outlined">arrow_forward</span>
              </button>
            </div>
          </div>
        </div>

        <div class="tarjeta-gastro grupo-imagen">
          <div class="tarjeta-gastro__imagen-envoltorio">
            <img alt="Tour Cafetero" class="tarjeta-gastro__imagen" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBclMJTecXPdKK2ee7Nqm1hnNKeODuSBdQO7f22h0Ai2Pn_xKD50h1tBxHMeNHqf32ZSFp3rDm2oNfUDSOL2Hgc4wFFSfbxLq9CIuFvyY-xeM4rSf6U0NZLKeJRI1NPZ-kFbrdUd4EL8cxVpJNdSC5VxkG77TP8RygkTo83YL2HQvLN-40KKatTvjzX8hhCz49Wagw59CxqICS09LnrUdEU-pScwieXY9yQFcGOuhJY1ziFpj0UKuwv40lUL0u79wGO4IJtttXzodM"/>
            <span class="tarjeta-gastro__badge">Experiencia</span>
          </div>
          <div class="tarjeta-gastro__cuerpo panel-cristal borde-superior-cristal">
            <span class="tarjeta-gastro__restaurante">Finca El Descanso</span>
            <h3 class="tarjeta-gastro__titulo">Tour Cafetero & Cata Gourmet</h3>
            <div class="tarjeta-gastro__meta">
              <span class="tarjeta-gastro__precio">$65,000 COP</span>
              <span class="tarjeta-gastro__calificacion"><span class="material-symbols-outlined">star</span> 5.0</span>
            </div>
            <div class="acciones-tarjeta">
              <button class="boton-tarjeta boton-tarjeta--blanco boton-tarjeta--pequeno" onclick="window.location.href='detalles-gastronomicos.php?id=6'">
                Ver info <span class="material-symbols-outlined">arrow_forward</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- =========================================================
       NUESTROS SERVICIOS PREMIUM
       ========================================================= -->
  <section class="seccion-servicios">
    <div class="contenedor-servicios">
      <div class="encabezado-centrado">
        <span class="etiqueta-seccion etiqueta-seccion--primario">Posibilidades Infinitas</span>
        <h2 class="titulo-seccion-oscuro">Nuestros Servicios Premium</h2>
      </div>
      <div class="cuadricula-servicios">
        <div class="tarjeta-servicio panel-cristal grupo-servicio">
          <div class="icono-servicio-contenedor"><span class="material-symbols-outlined icono-servicio">flight_takeoff</span></div>
          <h3 class="titulo-servicio">Vuelos Privados</h3>
          <p class="descripcion-servicio">Viaja en absoluta privacidad con nuestra flota curada de jets de lujo y vuelos chárter privados.</p>
        </div>
        <div class="tarjeta-servicio panel-cristal grupo-servicio">
          <div class="icono-servicio-contenedor"><span class="material-symbols-outlined icono-servicio">hotel</span></div>
          <h3 class="titulo-servicio">Estancias de Élite</h3>
          <p class="descripcion-servicio">Acceso exclusivo a más de 500 villas boutique y hoteles de ultra-lujo en todo el mundo.</p>
        </div>
        <div class="tarjeta-servicio panel-cristal grupo-servicio">
          <div class="icono-servicio-contenedor"><span class="material-symbols-outlined icono-servicio">restaurant</span></div>
          <h3 class="titulo-servicio">Gastronomía Curada</h3>
          <p class="descripcion-servicio">Reservas aseguradas en restaurantes con estrellas Michelin y joyas locales ocultas.</p>
        </div>
        <div class="tarjeta-servicio panel-cristal grupo-servicio">
          <div class="icono-servicio-contenedor"><span class="material-symbols-outlined icono-servicio">concierge</span></div>
          <h3 class="titulo-servicio">Conserje 24/7</h3>
          <p class="descripcion-servicio">Un guardián de viajes dedicado disponible las 24 horas para perfeccionar su viaje.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- =========================================================
       SOCIOS DESTACADOS
       ========================================================= -->
  <section class="seccion-socios">
    <div class="contenedor-socios">
      <div class="encabezado-socios">
        <h2 class="titulo-seccion-oscuro">Socios Destacados</h2>
        <p class="subtitulo-socios">Las mentes detrás de nuestras experiencias de clase mundial.</p>
      </div>
      <div class="lista-socios" id="lista-socios">
        <div class="item-socio"><span class="material-symbols-outlined icono-socio">diamond</span><span class="nombre-socio">AURORA LUX</span></div>
        <div class="item-socio"><span class="material-symbols-outlined icono-socio">landscape</span><span class="nombre-socio">TERRA EXPLORE</span></div>
        <div class="item-socio"><span class="material-symbols-outlined icono-socio">anchor</span><span class="nombre-socio">OCEANIC CO.</span></div>
        <div class="item-socio"><span class="material-symbols-outlined icono-socio">castle</span><span class="nombre-socio">HERITAGE STAYS</span></div>
      </div>
    </div>
  </section>

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
        <h4 class="pie__columna-titulo">Sobre nosotros</h4>
        <ul class="pie__lista">
          <li><a href="#">Misión y Visión</a></li>
          <li><a href="#">Equipo Ejecutivo</a></li>
          <li><a href="#">Carreras</a></li>
        </ul>
      </div>
      <div>
        <h4 class="pie__columna-titulo">Enlaces rápidos</h4>
        <ul class="pie__lista">
          <li><a href="#">Términos y Condiciones</a></li>
          <li><a href="#">Privacidad</a></li>
          <li><a href="#">FAQs</a></li>
        </ul>
      </div>
      <div>
        <h4 class="pie__columna-titulo">Contacto</h4>
        <ul class="pie__lista-contacto">
          <li class="pie__contacto-item">
            <span class="material-symbols-outlined">mail</span>
            <a href="mailto:info@srcabal.com">info@srcabal.com</a>
          </li>
          <li class="pie__contacto-item">
            <span class="material-symbols-outlined">call</span>
            +57 (606) 364-0000
          </li>
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

  <script src="/scripts/script-home.js"></script>

  <script>
  /* ════════════════════════════════════════════════════
     HOME.PHP — Bento de planes AJAX + menú avatar
  ════════════════════════════════════════════════════ */

  const IMGS_DEFAULT = [
    'https://lh3.googleusercontent.com/aida-public/AB6AXuB711Yx-xQsszazQUBDqN1OqQYl4K8czjoq1Nka6XzAwDkrWX0IejB6EnX6bjk1X_vvcGNWiJIcqWzq4t5qYN1Opwg2Y8nMBxhqhzu_1R1ae4Q_8NhuJzyYxiUDDV8sQfFCgDo6LycuHewxR61A3BS_3oGw2yzY4JkuQeTM1brAg3oPmgaFMooN2Xcm4k2EjJs23RaG9pS7IWuUb7sc7WqOiWOGVE1VoaznK9VAT2w7bsR3Ycem5FSWtpZLVI9C8fuu1sUFmuMbahA',
    'https://lh3.googleusercontent.com/aida-public/AB6AXuAjFlGpXujdfQF-8b9gRFHXxsmyP1MibGWZWjioRuKfNBI60Jq9HU_is02Emlcpmhz96F3sT4SZ6csyzaqJjVATkr96Vcy6-hQNGPmllHVL8NdTGVvqyGi0aXpa8iXv71B--3uE6f3aGwcmkiOmI8KfjXtOhUr1D01QKnfxdjnggBPIpZXa0f_V0daOaDcp_9GSF5JMimVgcZJr7yp3zUhhbbuSpmtsKKVQzCIEbxJb5X9pmZGIrVH6-nBXTHG44GxabvfF-7AGN-Y',
    'https://lh3.googleusercontent.com/aida-public/AB6AXuBclMJTecXPdKK2ee7Nqm1hnNKeODuSBdQO7f22h0Ai2Pn_xKD50h1tBxHMeNHqf32ZSFp3rDm2oNfUDSOL2Hgc4wFFSfbxLq9CIuFvyY-xeM4rSf6U0NZLKeJRI1NPZ-kFbrdUd4EL8cxVpJNdSC5VxkG77TP8RygkTo83YL2HQvLN-40KKatTvjzX8hhCz49Wagw59CxqICS09LnrUdEU-pScwieXY9yQFcGOuhJY1ziFpj0UKuwv40lUL0u79wGO4IJtttXzodM',
  ];

  /* ── Menú avatar toggle ── */
  document.getElementById('avatar-usuario').addEventListener('click', (e) => {
    e.stopPropagation();
    const menu = document.getElementById('menu-avatar');
    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
  });
  document.addEventListener('click', () => {
    document.getElementById('menu-avatar').style.display = 'none';
  });

  /* ── Logout AJAX ── */
  document.getElementById('btn-logout-home').addEventListener('click', async () => {
    await fetch('/ajax/logout.php', { method: 'POST' });
    window.location.href = '/index.php';
  });

  /* ── Botones de navegación ── */
  document.getElementById('boton-ver-todos-itinerarios').addEventListener('click', () => {
    window.location.href = 'index.php';
  });
  document.getElementById('boton-ver-todos-gastronomia').addEventListener('click', () => {
    window.location.href = 'detalles-gastronomicos.php';
  });

  /* ── Render bento de planes ── */
  function renderBento(planes) {
    if (!planes || planes.length === 0) return;

    const principal = planes[0];
    const laterales = planes.slice(1, 3);

    const imgP = principal.imagen_hero_url || IMGS_DEFAULT[0];

    let html = `
    <div class="tarjeta-bento tarjeta-bento--principal grupo-imagen">
      <img alt="${principal.titulo}" class="imagen-tarjeta" src="${imgP}" loading="lazy"/>
      <div class="sombra-gradiente-tarjeta"></div>
      <div class="info-tarjeta panel-cristal borde-superior-cristal">
        <div class="fila-info-tarjeta">
          <div>
            <span class="etiqueta-categoria">${principal.duracion_dias} ${principal.duracion_dias > 1 ? 'Días' : 'Día'} · ${principal.etiqueta || 'Premium'}</span>
            <h3 class="nombre-destino-principal">${principal.titulo}</h3>
          </div>
          <span class="precio-destino">$${principal.precio_formateado} ${principal.moneda === 'COP' ? 'COP' : ''}</span>
        </div>
        <div class="acciones-tarjeta">
          <button class="boton-tarjeta boton-tarjeta--blanco" onclick="window.location.href='detalles-turisticos.php?id=${principal.id}'">
            Ver detalles <span class="material-symbols-outlined">arrow_forward</span>
          </button>
          <button class="boton-tarjeta boton-tarjeta--cristal" onclick="window.location.href='detalles-turisticos.php?id=${principal.id}'">Explorar</button>
        </div>
      </div>
    </div>

    <div class="columna-bento-lateral">`;

    laterales.forEach((p, i) => {
      const img = p.imagen_hero_url || IMGS_DEFAULT[(i + 1) % IMGS_DEFAULT.length];
      html += `
      <div class="tarjeta-bento grupo-imagen">
        <img alt="${p.titulo}" class="imagen-tarjeta" src="${img}" loading="lazy"/>
        <div class="sombra-gradiente-tarjeta"></div>
        <div class="info-tarjeta panel-cristal borde-superior-cristal">
          <h3 class="nombre-destino">${p.titulo}</h3>
          <div class="acciones-tarjeta">
            <button class="boton-tarjeta boton-tarjeta--blanco boton-tarjeta--pequeno" onclick="window.location.href='detalles-turisticos.php?id=${p.id}'">
              Ver detalles <span class="material-symbols-outlined">arrow_forward</span>
            </button>
            <button class="boton-tarjeta boton-tarjeta--cristal boton-tarjeta--pequeno" onclick="window.location.href='detalles-turisticos.php?id=${p.id}'">Explorar</button>
          </div>
        </div>
      </div>`;
    });

    html += '</div>';
    document.getElementById('bento-planes').innerHTML = html;
  }

  /* ── Cargar planes ── */
  async function cargarBento() {
    try {
      const res  = await fetch('/ajax/planes_turisticos.php?limite=3');
      const data = await res.json();
      if (data.ok && data.planes.length) {
        renderBento(data.planes);
      }
    } catch (err) {
      console.error('Error cargando planes bento:', err);
    }
  }

  document.addEventListener('DOMContentLoaded', cargarBento);
  </script>

</body>
</html>
