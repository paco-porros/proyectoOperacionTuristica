<?php
/**
 * detalles-gastronomicos.php
 * Página INFORMATIVA: restaurante, dirección, contacto, platos.
 * No permite agregar a planes turísticos.
 * Datos cargados vía AJAX desde la BD.
 */
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
  <title>Detalle Gastronómico | Operador Santa Rosa de Cabal</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="/estilos/style-detalles-gastronomicos.css"/>
</head>
<body class="cuerpo-principal">

  <!-- NAVEGACIÓN -->
  <nav class="barra-nav">
    <div class="nav-logo">Azure Voyager</div>
    <div class="nav-enlaces">
      <a class="nav-enlace" href="index.php">Inicio</a>
      <a class="nav-enlace" href="#">Servicios</a>
      <a class="nav-enlace" href="#">Planes</a>
      <a class="nav-enlace" href="#">Destinos</a>
      <a class="nav-enlace nav-enlace--activo" href="#">Gastronomía</a>
    </div>
    <div class="nav-acciones">
      <div class="nav-buscador">
        <span class="material-symbols-outlined nav-buscador__icono">search</span>
        <input class="nav-buscador__input" placeholder="Buscar..." type="text"/>
      </div>
      <?php if ($logueado): ?>
        <button class="nav-boton-reservar" id="btn-logout-gastro"><?= htmlspecialchars($usuario['nombre']) ?> · Salir</button>
      <?php else: ?>
        <button class="nav-boton-reservar" onclick="window.location.href='/login.php'">Iniciar Sesión</button>
      <?php endif; ?>
    </div>
  </nav>

  <!-- CONTENIDO PRINCIPAL — rellenado por AJAX -->
  <main class="contenido-principal" id="contenido-gastro">

    <!-- Skeleton -->
    <section class="seccion-hero" style="min-height:400px;background:linear-gradient(135deg,#cbfdff,#afedf0);">
      <div class="hero-degradado"></div>
      <div class="hero-contenido">
        <span class="hero-etiqueta" style="opacity:.3;">Cargando…</span>
        <h1 class="hero-titulo" style="opacity:.2;background:#c3c6d4;color:transparent;border-radius:.5rem;">Cargando información gastronómica</h1>
      </div>
    </section>

    <section class="seccion-cuerpo">
      <div class="cuadricula-principal">
        <div class="columna-contenido">
          <div class="tarjeta-cristal" style="opacity:.3;min-height:12rem;"></div>
        </div>
        <aside class="columna-lateral">
          <div class="tarjeta-cristal tarjeta-cristal--sticky" style="opacity:.3;min-height:10rem;"></div>
        </aside>
      </div>
    </section>
  </main>

  <!-- PIE DE PÁGINA -->
  <footer class="pie-pagina">
    <div class="pie-pagina__interior">
      <div class="pie-pagina__marca">
        <div class="pie-pagina__logo">Azure Ethereal Voyager</div>
        <p class="pie-pagina__copyright">© 2024 Azure Ethereal Voyager. Translucent Horizons Travel Group.</p>
      </div>
      <div class="pie-pagina__enlaces">
        <a class="pie-pagina__enlace" href="#">Privacidad</a>
        <a class="pie-pagina__enlace" href="#">Términos</a>
        <a class="pie-pagina__enlace" href="#">Soporte</a>
        <a class="pie-pagina__enlace" href="#">Instagram</a>
      </div>
    </div>
  </footer>

  <script>
  /* ════════════════════════════════════════════════════
     DETALLES GASTRONÓMICOS — Página puramente informativa
     Carga datos del restaurante y plan desde la BD vía AJAX
  ════════════════════════════════════════════════════ */

  const PLAN_ID = <?= $planId ?>;

  /* ── Renderizar estrellas ── */
  function estrellas(n) {
    let html = '';
    for (let i = 1; i <= 5; i++) {
      const llena = i <= Math.round(n);
      html += `<span class="material-symbols-outlined ${llena ? 'estrella-llena' : ''}" style="font-size:1.125rem;color:#054da4;">${llena ? 'star' : 'star'}</span>`;
    }
    return html;
  }

  /* ── Renderizar lista de restaurantes (vista sin id) ── */
  function renderLista(restaurantes) {
    if (!restaurantes || !restaurantes.length) {
      document.getElementById('contenido-gastro').innerHTML =
        '<p style="padding:6rem 2rem;text-align:center;color:#306388;">No hay restaurantes disponibles.</p>';
      return;
    }

    const cards = restaurantes.map(r => {
      const planesHTML = r.planes.map(p =>
        `<li style="display:flex;justify-content:space-between;align-items:center;padding:.5rem 0;border-bottom:1px solid rgba(195,198,212,.2);">
          <span style="font-size:.875rem;color:#002021;font-weight:500;">${p.titulo}</span>
          <span style="font-size:.75rem;font-weight:700;color:#00595e;">$${Number(p.precio_desde).toLocaleString('es-CO')} ${p.moneda}</span>
        </li>`
      ).join('');

      return `
      <div class="tarjeta-contenedor" style="margin-bottom:2rem;">
        <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:1rem;">
          <span class="material-symbols-outlined icono-primario">${r.icono || 'restaurant'}</span>
          <div>
            <h3 style="font-family:'Plus Jakarta Sans',sans-serif;font-size:1.25rem;font-weight:700;color:#054da4;margin:0;">${r.nombre}</h3>
            <span style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#306388;">${r.tipo || 'Restaurante'}</span>
          </div>
        </div>
        ${r.descripcion ? `<p style="font-size:.875rem;color:#424752;line-height:1.625;margin-bottom:1rem;">${r.descripcion}</p>` : ''}
        <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1rem;">
          <span class="material-symbols-outlined" style="color:#306388;font-size:1.25rem;">location_on</span>
          <span style="font-size:.875rem;color:#306388;font-weight:500;">${r.direccion || 'Santa Rosa de Cabal, Risaralda'}</span>
        </div>
        ${planesHTML ? `
          <h4 style="font-family:'Plus Jakarta Sans',sans-serif;font-size:.875rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#306388;margin:1rem 0 .5rem;">Planes disponibles</h4>
          <ul style="list-style:none;padding:0;margin:0;">${planesHTML}</ul>
        ` : ''}
      </div>`;
    }).join('');

    document.getElementById('contenido-gastro').innerHTML = `
      <section class="seccion-hero" style="min-height:320px;">
        <div class="hero-fondo" style="background-image:url('https://lh3.googleusercontent.com/aida-public/AB6AXuCVyCZ2FExmqXaMFwH23NJmftIQOA29hIqHlw4QVhAyepxIP0qPmqft0EUBSzlc28rmNRLQeWkcsbAthowjVD7Ov8tO8TmnGOd6kMpXYHMGvzcTt7nBtEijjwUxIA7Xqtab9OFW9f4D2DOdhkVPEI9EtdcOs20Tu3k2INSFHQ0fHSgtHFqFjhWArit9OSR3uw9dshnJU4AClXYlhUcWLwO76ZZ4emwujwsnGxR4H2dYMakb5BLUruCP_FnBlwjPvO-R5Vyvf59CpI0B');"></div>
        <div class="hero-degradado"></div>
        <div class="hero-contenido">
          <span class="hero-etiqueta">Gastronomía Local</span>
          <h1 class="hero-titulo">Restaurantes & Sabores</h1>
          <p class="hero-subtitulo">Descubre los mejores lugares gastronómicos de Santa Rosa de Cabal, sus direcciones y especialidades.</p>
        </div>
      </section>
      <section class="seccion-cuerpo">
        <div style="max-width:80rem;margin:0 auto;">
          ${cards}
        </div>
      </section>`;
  }

  /* ── Renderizar detalle de un plan gastronómico ── */
  function renderDetalle(plan) {
    const imgHero = plan.imagen_hero_url || 'https://lh3.googleusercontent.com/aida-public/AB6AXuCVyCZ2FExmqXaMFwH23NJmftIQOA29hIqHlw4QVhAyepxIP0qPmqft0EUBSzlc28rmNRLQeWkcsbAthowjVD7Ov8tO8TmnGOd6kMpXYHMGvzcTt7nBtEijjwUxIA7Xqtab9OFW9f4D2DOdhkVPEI9EtdcOs20Tu3k2INSFHQ0fHSgtHFqFjhWArit9OSR3uw9dshnJU4AClXYlhUcWLwO76ZZ4emwujwsnGxR4H2dYMakb5BLUruCP_FnBlwjPvO-R5Vyvf59CpI0B';

    const platosHTML = (plan.platos || []).map(p => `
      <div class="plato">
        <div class="plato__imagen-envoltorio">
          <img class="plato__imagen" src="${p.imagen_url || 'https://lh3.googleusercontent.com/aida-public/AB6AXuAERIc4zxlLattoIydkblU9vwFfzgioXPnhg6QVJEjbWnsq1M-CQBhoBmTIkvPqJ9DBqd-OHgphca5EgyPLSe4rAUCptAXc5sDhTNRrwZQTz7UICm3qsmUZXVNhqPk7Tvt7c0-0Do0m4p3I9AJ2z9HZxo7U5OfI7yCqThYZAMSM3JVsXPH1Fy7M5yOTrizSMZH-kkdfh4KPYUCvz9Lw7yLHoLe-RPVGA8ZHZfaB57vODJFL_ZyJOBqT05UgJoGqQvSWaoBWBHH_B7wS'}" alt="${p.titulo}" loading="lazy"/>
        </div>
        <div class="plato__info">
          <h3 class="plato__titulo">${p.titulo}</h3>
          <p class="plato__descripcion">${p.descripcion}</p>
        </div>
      </div>`).join('');

    const resenasHTML = (plan.resenas || []).map(r => `
      <div class="opinion opinion--con-borde">
        <div class="opinion__estrellas">${estrellas(r.puntuacion)}</div>
        <p class="opinion__texto">"${r.comentario}"</p>
        <span class="opinion__autor">— ${r.autor_nombre}${r.autor_cargo ? ', ' + r.autor_cargo : ''}</span>
      </div>`).join('');

    document.getElementById('contenido-gastro').innerHTML = `
      <!-- Hero -->
      <section class="seccion-hero">
        <div class="hero-fondo" style="background-image:url('${imgHero}');"></div>
        <div class="hero-degradado"></div>
        <div class="hero-contenido">
          <span class="hero-etiqueta">${plan.etiqueta || 'Gastronomía'}</span>
          <h1 class="hero-titulo">${plan.titulo}</h1>
          <p class="hero-subtitulo">${plan.descripcion.substring(0, 160)}…</p>
        </div>
      </section>

      <!-- Cuerpo -->
      <section class="seccion-cuerpo">
        <div class="cuadricula-principal">

          <!-- Columna izquierda -->
          <div class="columna-contenido">

            ${platosHTML ? `
            <div class="tarjeta-cristal">
              <div class="tarjeta-cristal__encabezado">
                <span class="material-symbols-outlined icono-primario">restaurant_menu</span>
                <h2 class="titulo-seccion">Descripción del Plato</h2>
              </div>
              <div class="lista-platos">${platosHTML}</div>
            </div>` : ''}

            <!-- Información del restaurante -->
            <div class="cuadricula-dos-columnas">
              <div class="tarjeta-contenedor">
                <h3 class="subtitulo-seccion">
                  <span class="material-symbols-outlined icono-secundario">storefront</span>
                  Restaurante
                </h3>
                <p style="font-size:1rem;font-weight:700;color:#002021;margin:0 0 .5rem;">${plan.restaurante_nombre}</p>
                <p style="font-size:.875rem;color:#424752;margin:0 0 .75rem;">${plan.restaurante_tipo || 'Restaurante'}</p>
                <div style="display:flex;align-items:center;gap:.5rem;">
                  <span class="material-symbols-outlined icono-secundario">location_on</span>
                  <span style="font-size:.875rem;color:#306388;font-weight:500;">${plan.restaurante_direccion || 'Santa Rosa de Cabal'}</span>
                </div>
              </div>

              <div class="tarjeta-contenedor">
                <h3 class="subtitulo-seccion">
                  <span class="material-symbols-outlined icono-secundario">info</span>
                  Información
                </h3>
                <div class="lista-maridaje">
                  ${plan.duracion_horas ? `<p><strong class="texto-destacado">Duración:</strong> ${plan.duracion_horas} horas</p>` : ''}
                  ${plan.max_personas   ? `<p><strong class="texto-destacado">Máx. personas:</strong> ${plan.max_personas}</p>` : ''}
                  ${plan.idiomas        ? `<p><strong class="texto-destacado">Idiomas:</strong> ${plan.idiomas}</p>` : ''}
                  <p><strong class="texto-destacado">Categoría:</strong> ${plan.categoria || plan.etiqueta || 'Gastronomía'}</p>
                  <p><strong class="texto-destacado">Puntuación:</strong> ${parseFloat(plan.puntuacion).toFixed(1)} ⭐ (${plan.total_resenas} reseñas)</p>
                </div>
              </div>
            </div>

          </div>

          <!-- Columna lateral — SOLO informativa, sin reserva de planes turísticos -->
          <aside class="columna-lateral">

            <div class="tarjeta-cristal tarjeta-cristal--sticky">
              <!-- Precio referencial -->
              <div class="reserva-precio">
                <span class="reserva-precio__etiqueta">Precio referencial</span>
                <div class="reserva-precio__valor">
                  <span class="reserva-precio__numero">$${Number(plan.precio_desde).toLocaleString('es-CO')}</span>
                  <span class="reserva-precio__unidad">/ ${plan.moneda === 'COP' ? 'persona COP' : 'persona'}</span>
                </div>
              </div>

              <!-- Detalles del local -->
              <div class="reserva-detalles">
                <div class="reserva-detalle-item">
                  <span class="material-symbols-outlined icono-secundario">storefront</span>
                  <span>${plan.restaurante_nombre}</span>
                </div>
                <div class="reserva-detalle-item">
                  <span class="material-symbols-outlined icono-secundario">location_on</span>
                  <span>${plan.restaurante_direccion || 'Santa Rosa de Cabal, Risaralda'}</span>
                </div>
                ${plan.duracion_horas ? `
                <div class="reserva-detalle-item">
                  <span class="material-symbols-outlined icono-secundario">schedule</span>
                  <span>Duración: ${plan.duracion_horas} horas</span>
                </div>` : ''}
                ${plan.idiomas ? `
                <div class="reserva-detalle-item">
                  <span class="material-symbols-outlined icono-secundario">language</span>
                  <span>${plan.idiomas}</span>
                </div>` : ''}
              </div>

              <!-- Nota informativa — no hay acción de reserva de plan turístico -->
              <div style="background:rgba(0,116,121,.08);padding:1rem;border-radius:.5rem;font-size:.875rem;color:#00595e;font-weight:500;line-height:1.5;">
                <span class="material-symbols-outlined" style="vertical-align:middle;margin-right:.25rem;">info</span>
                Esta página es informativa. Para reservar una experiencia turística completa, visita la sección de
                <a href="index.php" style="color:#054da4;font-weight:700;text-decoration:underline;">Planes Turísticos</a>.
              </div>

              <p class="reserva-nota" style="margin-top:1rem;">Contacta directamente al restaurante para disponibilidad.</p>
            </div>

            <!-- Opiniones -->
            ${resenasHTML ? `
            <div class="tarjeta-opiniones">
              <h3 class="tarjeta-opiniones__titulo">Opiniones</h3>
              <div class="lista-opiniones">${resenasHTML}</div>
            </div>` : ''}

          </aside>
        </div>
      </section>`;
  }

  /* ── Logout AJAX ── */
  document.getElementById('btn-logout-gastro')?.addEventListener('click', async () => {
    await fetch('/ajax/logout.php', { method: 'POST' });
    window.location.href = '/index.php';
  });

  /* ── Carga de datos ── */
  async function cargarGastronomico() {
    try {
      const url  = PLAN_ID ? `/ajax/gastronomicos.php?id=${PLAN_ID}` : '/ajax/gastronomicos.php';
      const res  = await fetch(url);
      const data = await res.json();

      if (!data.ok) {
        document.getElementById('contenido-gastro').innerHTML =
          `<p style="padding:6rem 2rem;text-align:center;color:#ba1a1a;">${data.msg}</p>`;
        return;
      }

      if (PLAN_ID) {
        renderDetalle(data.plan);
      } else {
        renderLista(data.restaurantes);
      }
    } catch (err) {
      console.error('Error cargando gastronomía:', err);
    }
  }

  document.addEventListener('DOMContentLoaded', cargarGastronomico);
  </script>

  <script src="/scripts/script-detalles-gastronomicos.js"></script>
</body>
</html>
