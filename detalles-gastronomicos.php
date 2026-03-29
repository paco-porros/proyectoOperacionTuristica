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
<html lang="es">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>Detalle Gastronómico | Operador Turístico y Gastronomico</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&family=DM+Sans:wght@300;400;500&display=swap" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,300,0,0" />
  <link rel="stylesheet" href="/estilos/style.css" />
  <link rel="stylesheet" href="/estilos/style-detalles-gastronomicos.css"/>
</head>
<body>

  <!-- ═══════════════════════════════════════════
       NAVEGACIÓN (igual que index.php)
  ═══════════════════════════════════════════ -->
  <nav class="navegacion">
    <div class="navegacion__logo">
      <a href="/index.php">Operador Turístico y Gastronomico | Santa Rosa de Cabal</a>
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

    <!-- Skeleton -->
    <section class="seccion-hero" style="min-height:400px;background:linear-gradient(135deg,var(--color-superficie-contenedor-bajo),var(--color-primario-fijo-tenue));">
      <div class="hero-degradado"></div>
      <div class="hero-contenido">
        <span class="hero-etiqueta" style="opacity:.3;">Cargando…</span>
        <h1 class="hero-titulo" style="opacity:.2;background:var(--color-superficie-contenedor-bajo);color:transparent;border-radius:.5rem;">Cargando información gastronómica</h1>
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

  <!-- ═══════════════════════════════════════════
       PIE DE PÁGINA (igual que index.php)
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
        <a href="#">Política de Privacidad</a>
        <span class="pie__separador">|</span>
        <a href="#">Términos de Servicio</a>
      </p>
    </div>
  </footer>

  <script>
  /* ════════════════════════════════════════════════════
     DETALLES GASTRONÓMICOS — Página puramente informativa
     Carga datos del restaurante y plan desde la BD vía AJAX
  ════════════════════════════════════════════════════ */

  const PLAN_ID = <?= $planId ?>;

  /* ── Cerrar sesión AJAX ── */
  const btnSalirGastro = document.getElementById('btn-cerrar-sesion-gastro');
  if (btnSalirGastro) {
    btnSalirGastro.addEventListener('click', async () => {
      await fetch('/ajax/logout.php', { method: 'POST' });
      window.location.href = '/index.php';
    });
  }

  /* ── Renderizar estrellas ── */
  function estrellas(n) {
    let html = '';
    for (let i = 1; i <= 5; i++) {
      const llena = i <= Math.round(n);
      html += `<span class="material-symbols-outlined ${llena ? 'estrella-llena' : ''}" style="font-size:1.125rem;color:var(--color-terciario);">${llena ? 'star' : 'star'}</span>`;
    }
    return html;
  }

  /* ── Renderizar lista de restaurantes (vista sin id) ── */
  function renderLista(restaurantes) {
    if (!restaurantes || !restaurantes.length) {
      document.getElementById('contenido-gastro').innerHTML =
        '<p style="padding:6rem 2rem;text-align:center;color:var(--color-sobre-superficie-variante);">No hay restaurantes disponibles.</p>';
      return;
    }

    const cards = restaurantes.map(r => {
      const planesHTML = r.planes.map(p =>
        `<li style="display:flex;justify-content:space-between;align-items:center;padding:.5rem 0;border-bottom:1px solid rgba(162,103,105,.2);">
          <span style="font-size:.875rem;color:var(--color-sobre-superficie);font-weight:500;">${p.titulo}</span>
          <span style="font-size:.75rem;font-weight:700;color:var(--color-primario);">$${Number(p.precio_desde).toLocaleString('es-CO')} ${p.moneda}</span>
        </li>`
      ).join('');

      return `
      <div class="tarjeta-contenedor" style="margin-bottom:2rem;">
        <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:1rem;">
          <span class="material-symbols-outlined icono-primario">${r.icono || 'restaurant'}</span>
          <div>
            <h3 style="font-family:var(--fuente-titular);font-size:1.25rem;font-weight:700;color:var(--color-primario);margin:0;">${r.nombre}</h3>
            <span style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--color-sobre-superficie-variante);">${r.tipo || 'Restaurante'}</span>
          </div>
        </div>
        ${r.descripcion ? `<p style="font-size:.875rem;color:var(--color-sobre-superficie-variante);line-height:1.625;margin-bottom:1rem;">${r.descripcion}</p>` : ''}
        <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1rem;">
          <span class="material-symbols-outlined" style="color:var(--color-terciario);font-size:1.25rem;">location_on</span>
          <span style="font-size:.875rem;color:var(--color-sobre-superficie-variante);font-weight:500;">${r.direccion || 'Santa Rosa de Cabal, Risaralda'}</span>
        </div>
        ${planesHTML ? `
          <h4 style="font-family:var(--fuente-titular);font-size:.875rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--color-sobre-superficie-variante);margin:1rem 0 .5rem;">Planes disponibles</h4>
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

            <!-- Información del restaurante -->
            <div class="cuadricula-dos-columnas">
              <div class="tarjeta-contenedor">
                <h3 class="subtitulo-seccion">
                  <span class="material-symbols-outlined icono-secundario">storefront</span>
                  Restaurante
                </h3>
                <p style="font-size:1rem;font-weight:700;color:var(--color-sobre-superficie);margin:0 0 .5rem;">${plan.restaurante_nombre}</p>
                <p style="font-size:.875rem;color:var(--color-sobre-superficie-variante);margin:0 0 .75rem;">${plan.restaurante_tipo || 'Restaurante'}</p>
                <div style="display:flex;align-items:center;gap:.5rem;">
                  <span class="material-symbols-outlined icono-secundario">location_on</span>
                  <span style="font-size:.875rem;color:var(--color-sobre-superficie-variante);font-weight:500;">${plan.restaurante_direccion || 'Santa Rosa de Cabal'}</span>
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

              <!-- Nota informativa -->
              <div style="background:rgba(162,103,105,.1);padding:1rem;border-radius:.5rem;font-size:.875rem;color:var(--color-sobre-superficie-variante);font-weight:500;line-height:1.5;">
                <span class="material-symbols-outlined" style="vertical-align:middle;margin-right:.25rem;">info</span>
                Esta página es informativa. Para reservar una experiencia turística completa, visita la sección de
                <a href="index.php#planes" style="color:var(--color-primario);font-weight:700;text-decoration:underline;">Planes Turísticos</a>.
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

  /* ── Carga de datos ── */
  async function cargarGastronomico() {
    try {
      const url  = PLAN_ID ? `/ajax/gastronomicos.php?id=${PLAN_ID}` : '/ajax/gastronomicos.php';
      const res  = await fetch(url);
      const data = await res.json();

      if (!data.ok) {
        document.getElementById('contenido-gastro').innerHTML =
          `<p style="padding:6rem 2rem;text-align:center;color:var(--color-terciario);">${data.msg}</p>`;
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