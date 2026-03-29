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
  /* ════════════════════════════════════════════════════
     DETALLES GASTRONÓMICOS — Página informativa
     Carga datos del restaurante y plan desde la BD vía AJAX
  ════════════════════════════════════════════════════ */

  const PLAN_ID = <?= $planId ?>;

  /* ── Toast ── */
  function mostrarToast(msg, tipo) {
    const t = document.getElementById('toast-plan');
    t.textContent = msg;
    t.style.display = 'block';
    t.className = 'toast-plan toast-plan--' + (tipo === 'ok' ? 'ok' : 'error');
    requestAnimationFrame(() => { t.style.opacity = '1'; });
    setTimeout(() => {
      t.style.opacity = '0';
      setTimeout(() => { t.style.display = 'none'; }, 300);
    }, 3500);
  }

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
      html += `<span class="material-symbols-outlined estrella-resena ${i <= Math.round(n) ? 'estrella-resena--activa' : ''}">star</span>`;
    }
    return html;
  }

  /* ── Renderizar lista de restaurantes (vista sin id) ── */
  function renderLista(restaurantes) {
    if (!restaurantes || !restaurantes.length) {
      document.getElementById('contenido-gastro').innerHTML =
        '<p class="mensaje-estado">No hay restaurantes disponibles.</p>';
      return;
    }

    const cards = restaurantes.map(r => {
      const planesHTML = (r.planes || []).map(p => `
        <li class="incluye-item">
          <span class="material-symbols-outlined icono-filled icono-check">restaurant_menu</span>
          <span>${p.titulo}</span>
          <span style="margin-left:auto;font-weight:700;color:var(--color-primario);">$${Number(p.precio_desde).toLocaleString('es-CO')} ${p.moneda}</span>
        </li>`
      ).join('');

      return `
      <div class="tarjeta-cristal" style="margin-bottom:2rem;">
        <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:1rem;">
          <span class="material-symbols-outlined icono-filled">${r.icono || 'restaurant'}</span>
          <div>
            <h3 class="titulo-card" style="margin:0;">${r.nombre}</h3>
            <span class="hero-etiqueta" style="position:static;font-size:.75rem;">${r.tipo || 'Restaurante'}</span>
          </div>
        </div>
        ${r.descripcion ? `<p class="descripcion-card">${r.descripcion}</p>` : ''}
        <div class="cuadricula-destacados">
          <div class="destacado-item">
            <span class="material-symbols-outlined icono-filled">location_on</span>
            <span class="destacado-item__etiqueta">${r.direccion || 'Santa Rosa de Cabal'}</span>
          </div>
        </div>
        ${planesHTML ? `
          <div class="itinerario" style="margin-top:1rem;">
            <h3 class="itinerario__titulo">Planes disponibles</h3>
            <ul class="lista-incluye">${planesHTML}</ul>
          </div>` : ''}
      </div>`;
    }).join('');

    document.getElementById('contenido-gastro').innerHTML = `
      <section class="seccion-hero">
        <div class="hero-fondo">
          <img class="hero-imagen" alt="Gastronomía Santa Rosa de Cabal" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCVyCZ2FExmqXaMFwH23NJmftIQOA29hIqHlw4QVhAyepxIP0qPmqft0EUBSzlc28rmNRLQeWkcsbAthowjVD7Ov8tO8TmnGOd6kMpXYHMGvzcTt7nBtEijjwUxIA7Xqtab9OFW9f4D2DOdhkVPEI9EtdcOs20Tu3k2INSFHQ0fHSgtHFqFjhWArit9OSR3uw9dshnJU4AClXYlhUcWLwO76ZZ4emwujwsnGxR4H2dYMakb5BLUruCP_FnBlwjPvO-R5Vyvf59CpI0B"/>
          <div class="hero-degradado"></div>
        </div>
        <div class="hero-contenido">
          <div class="hero-texto">
            <span class="hero-etiqueta">Gastronomía Local</span>
            <h1 class="hero-titulo">Restaurantes & Sabores</h1>
            <div class="hero-meta">
              <div class="hero-meta__item">
                <span class="material-symbols-outlined">location_on</span>
                <span>Santa Rosa de Cabal, Risaralda</span>
              </div>
            </div>
          </div>
        </div>
      </section>
      <section class="seccion-cuerpo">
        <div class="columna-contenido" style="max-width:60rem;margin:0 auto;">
          ${cards}
        </div>
      </section>`;
  }

  /* ── Renderizar detalle de un plan gastronómico ── */
  function renderDetalle(plan) {
    const imgHero = plan.imagen_hero_url || 'https://lh3.googleusercontent.com/aida-public/AB6AXuCVyCZ2FExmqXaMFwH23NJmftIQOA29hIqHlw4QVhAyepxIP0qPmqft0EUBSzlc28rmNRLQeWkcsbAthowjVD7Ov8tO8TmnGOd6kMpXYHMGvzcTt7nBtEijjwUxIA7Xqtab9OFW9f4D2DOdhkVPEI9EtdcOs20Tu3k2INSFHQ0fHSgtHFqFjhWArit9OSR3uw9dshnJU4AClXYlhUcWLwO76ZZ4emwujwsnGxR4H2dYMakb5BLUruCP_FnBlwjPvO-R5Vyvf59CpI0B';

    // Tarjetas de información del restaurante como dia-items
    const infoItems = [
      { numero: '01', titulo: plan.restaurante_nombre, descripcion: (plan.restaurante_tipo || 'Restaurante') + (plan.restaurante_direccion ? ' · ' + plan.restaurante_direccion : ''), primario: true },
      { numero: '02', titulo: 'Categoría: ' + (plan.categoria || plan.etiqueta || 'Gastronomía'), descripcion: plan.idiomas ? 'Idiomas disponibles: ' + plan.idiomas : 'Experiencia gastronómica local', primario: false },
      ...(plan.duracion_horas ? [{ numero: '03', titulo: 'Duración de la experiencia', descripcion: plan.duracion_horas + ' horas · Máx. ' + (plan.max_personas || '—') + ' personas', primario: true }] : []),
    ];

    const infoHTML = infoItems.map(d => `
      <div class="dia-item ${d.primario ? 'dia-item--cristal' : 'dia-item--contenedor'}">
        <div class="dia-item__numero ${d.primario ? 'dia-item__numero--primario' : 'dia-item__numero--secundario'}">${d.numero}</div>
        <div class="dia-item__contenido">
          <h4 class="dia-item__titulo">${d.titulo}</h4>
          <p class="dia-item__descripcion">${d.descripcion}</p>
        </div>
      </div>`).join('');

    // Qué incluye
    const incluyeHTML = (plan.incluye || []).map(item => `
      <li class="incluye-item">
        <span class="material-symbols-outlined icono-filled icono-check">check_circle</span>
        ${item.descripcion}
      </li>`).join('');

    // Reseñas
    const resenasHTML = (plan.resenas || []).map(r => `
      <div class="resena-item">
        <div class="resena-estrellas">${estrellas(r.puntuacion)}</div>
        <p class="resena-comentario">"${r.comentario}"</p>
        <span class="resena-autor">— ${r.autor_nombre}${r.autor_cargo ? ', ' + r.autor_cargo : ''}</span>
      </div>`).join('');

    const precioFormateado = plan.precio_formateado || Number(plan.precio_desde).toLocaleString('es-CO');

    document.getElementById('contenido-gastro').innerHTML = `
      <!-- Hero -->
      <section class="seccion-hero">
        <div class="hero-fondo">
          <img class="hero-imagen" alt="${plan.titulo}" src="${imgHero}"/>
          <div class="hero-degradado"></div>
        </div>
        <div class="hero-contenido">
          <div class="hero-texto">
            <span class="hero-etiqueta">${plan.etiqueta || 'Gastronomía'}</span>
            <h1 class="hero-titulo">${plan.titulo}</h1>
            <div class="hero-meta">
              <div class="hero-meta__item">
                <span class="material-symbols-outlined">storefront</span>
                <span>${plan.restaurante_nombre}</span>
              </div>
              <div class="hero-meta__item hero-meta__item--precio">
                <span class="material-symbols-outlined">payments</span>
                <span>Desde $${precioFormateado} ${plan.moneda}</span>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Cuerpo -->
      <section class="seccion-cuerpo">
        <div class="cuadricula-principal">

          <!-- Columna izquierda -->
          <div class="columna-contenido">
            <div class="tarjeta-cristal">
              <h2 class="titulo-card">${plan.titulo}</h2>
              <p class="descripcion-card">${plan.descripcion}</p>
              <div class="cuadricula-destacados">
                <div class="destacado-item">
                  <span class="material-symbols-outlined icono-filled">restaurant_menu</span>
                  <span class="destacado-item__etiqueta">${plan.categoria || plan.etiqueta || 'Gastronomía'}</span>
                </div>
                <div class="destacado-item">
                  <span class="material-symbols-outlined icono-filled">star</span>
                  <span class="destacado-item__etiqueta">${parseFloat(plan.puntuacion).toFixed(1)} (${plan.total_resenas})</span>
                </div>
                ${plan.duracion_horas ? `
                <div class="destacado-item">
                  <span class="material-symbols-outlined icono-filled">schedule</span>
                  <span class="destacado-item__etiqueta">${plan.duracion_horas} horas</span>
                </div>` : ''}
                ${plan.max_personas ? `
                <div class="destacado-item">
                  <span class="material-symbols-outlined icono-filled">group</span>
                  <span class="destacado-item__etiqueta">Máx. ${plan.max_personas} personas</span>
                </div>` : ''}
              </div>
            </div>

            <div class="itinerario">
              <h3 class="itinerario__titulo">Información del Restaurante</h3>
              <div class="itinerario__lista">${infoHTML}</div>
            </div>
          </div>

          <!-- Columna lateral — SOLO informativa -->
          <aside class="columna-lateral">
            <div class="lateral-sticky">

              <div class="tarjeta-reserva">
                <h3 class="tarjeta-reserva__titulo">Información del Plan</h3>
                <p class="tarjeta-reserva__subtitulo">Contacta directamente al restaurante para disponibilidad.</p>
                <div class="reserva-campos">
                  <div class="reserva-campo">
                    <span class="reserva-campo__etiqueta">Precio referencial</span>
                    <span class="reserva-campo__valor">$${precioFormateado} ${plan.moneda}</span>
                  </div>
                  ${plan.duracion_horas ? `
                  <div class="reserva-campo">
                    <span class="reserva-campo__etiqueta">Duración</span>
                    <span class="reserva-campo__valor">${plan.duracion_horas} horas</span>
                  </div>` : ''}
                </div>
                <a href="index.php#planes" class="boton-reservar" style="display:block;text-align:center;text-decoration:none;">Ver Planes Turísticos</a>
                <p class="reserva-nota">Esta página es solo informativa. Para reservar visita la sección de Planes Turísticos.</p>
              </div>

              ${incluyeHTML ? `
              <div class="tarjeta-incluye">
                <h3 class="tarjeta-incluye__titulo">Qué incluye</h3>
                <ul class="lista-incluye">${incluyeHTML}</ul>
              </div>` : ''}

              ${resenasHTML ? `
              <div class="tarjeta-cristal tarjeta-opiniones">
                <h3 class="opiniones-titulo">Opiniones</h3>
                ${resenasHTML}
              </div>` : ''}

            </div>
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
          `<p class="mensaje-estado mensaje-estado--error">${data.msg}</p>`;
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

</body>
</html>
