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
<<<<<<< HEAD
  <title>Detalle Plan Turístico | Operador Santa Rosa de Cabal</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet"/>
=======
  <title>Escapada A Termales | Santa Rosa de Cabal</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&;family=Manrope:wght@400;500;600;700&;display=swap" rel="stylesheet"/>
>>>>>>> 16e14b6b1b8412fbec3ff2aacff89f9ad261fedf
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="/estilos/style-detalles-turisticos.css"/>
</head>
<body class="cuerpo-principal">

  <!-- NAVEGACIÓN -->
  <nav class="barra-nav">
    <div class="nav-logo">Santa Rosa de Cabal</div>
    <div class="nav-enlaces">
<<<<<<< HEAD
      <a class="nav-enlace" href="index.php">Inicio</a>
=======
>>>>>>> 16e14b6b1b8412fbec3ff2aacff89f9ad261fedf
      <a class="nav-enlace nav-enlace--activo" href="#">Servicios</a>
      <a class="nav-enlace" href="#">Planes</a>
      <a class="nav-enlace" href="#">Gastronomía</a>
    </div>
    <?php if ($logueado): ?>
      <button class="nav-boton-reservar" id="btn-logout-det">
        <?= htmlspecialchars($usuario['nombre']) ?> · Salir
      </button>
    <?php else: ?>
      <button class="nav-boton-reservar" onclick="window.location.href='/login.php'">Iniciar Sesión</button>
    <?php endif; ?>
  </nav>

  <!-- CONTENIDO PRINCIPAL — se rellena vía AJAX -->
  <main class="contenido-principal" id="contenido-plan">

<<<<<<< HEAD
    <!-- Skeleton de carga -->
    <section class="seccion-hero" style="background:#e5feff;">
      <div style="position:absolute;inset:0;background:linear-gradient(135deg,#cbfdff,#b5f3f6);"></div>
      <div class="hero-contenido">
        <div class="hero-texto">
          <span class="hero-etiqueta" style="opacity:.4;">Cargando…</span>
          <h1 class="hero-titulo" style="opacity:.3; background:#c3c6d4; color:transparent; border-radius:.5rem;">Cargando plan turístico</h1>
=======
    <!-- Hero -->
    <section class="seccion-hero">
      <div class="hero-fondo">
        <img
          class="hero-imagen"
          alt="Termales de Santa Rosa de Cabal"
          src="img/termales2.jpg"
        />
        <div class="hero-degradado"></div>
      </div>
      <div class="hero-contenido">
        <div class="hero-texto">
          <span class="hero-etiqueta">Experiencia Exclusiva</span>
          <h1 class="hero-titulo">Escapada a Termales</h1>
          <div class="hero-meta">
            <div class="hero-meta__item">
              <span class="material-symbols-outlined">location_on</span>
              <span>Santa Rosa de Cabal, Colombia</span>
            </div>
            <div class="hero-meta__item hero-meta__item--precio">
              <span class="material-symbols-outlined">payments</span>
              <span>Desde $2,499</span>
            </div>
          </div>
>>>>>>> 16e14b6b1b8412fbec3ff2aacff89f9ad261fedf
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
  <div id="toast-plan" style="
    display:none; position:fixed; bottom:1.5rem; right:1.5rem; z-index:9999;
    padding:.875rem 1.5rem; border-radius:.75rem; font-size:.875rem; font-weight:600;
    font-family:'Manrope',sans-serif; box-shadow:0 4px 16px rgba(0,0,0,.1);
    transition:opacity .3s; opacity:0;
  "></div>

  <!-- Modal: seleccionar fecha y personas -->
  <div id="modal-agregar" style="
    display:none; position:fixed; inset:0; z-index:9998; background:rgba(0,32,33,.5);
    backdrop-filter:blur(4px); align-items:center; justify-content:center;
  ">
    <div style="
      background:#e5feff; border-radius:1rem; padding:2.5rem; max-width:480px; width:90%;
      box-shadow:0 20px 40px rgba(0,32,33,.15);
    ">
      <h3 style="font-family:'Plus Jakarta Sans',sans-serif; font-size:1.5rem; font-weight:700; color:#054da4; margin:0 0 1.5rem;">
        Agregar a mi cuenta
      </h3>
      <div id="modal-alerta" style="display:none; margin-bottom:1rem; padding:.75rem; border-radius:.5rem; font-size:.875rem; font-weight:600;"></div>
      <div style="margin-bottom:1rem;">
        <label style="display:block; font-size:.75rem; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:#306388; margin-bottom:.5rem;">
          Fecha de inicio
        </label>
        <input id="modal-fecha" type="date" style="
          width:100%; padding:.75rem 1rem; border:1px solid #afedf0; border-radius:.5rem;
          font-family:'Manrope',sans-serif; color:#002021; background:#fff; outline:none;
          box-sizing:border-box;
        "/>
      </div>
      <div style="margin-bottom:2rem;">
        <label style="display:block; font-size:.75rem; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:#306388; margin-bottom:.5rem;">
          Número de adultos
        </label>
        <input id="modal-adultos" type="number" min="1" max="20" value="2" style="
          width:100%; padding:.75rem 1rem; border:1px solid #afedf0; border-radius:.5rem;
          font-family:'Manrope',sans-serif; color:#002021; background:#fff; outline:none;
          box-sizing:border-box;
        "/>
      </div>
      <div style="display:flex; gap:1rem;">
        <button id="modal-cancelar" style="
          flex:1; padding:1rem; border-radius:9999px; border:2px solid #afedf0;
          background:transparent; color:#306388; font-weight:700; cursor:pointer; font-family:'Plus Jakarta Sans',sans-serif;
        ">Cancelar</button>
        <button id="modal-confirmar" style="
          flex:2; padding:1rem; border-radius:9999px; background:#054da4; color:#fff;
          font-weight:700; border:none; cursor:pointer; font-family:'Plus Jakarta Sans',sans-serif;
          font-size:1rem;
        ">Confirmar reserva</button>
      </div>
    </div>
  </div>

  <!-- PIE DE PÁGINA -->
  <footer class="pie-pagina">
    <div class="pie-pagina__interior">
      <div class="pie-pagina__marca">
        <span class="pie-pagina__logo">Operador Turístico y Gastronomico | Santa Rosa de Cabal</span>
        <p class="pie-pagina__copyright">© 2026 Operador Turístico y Gastronomico | Santa Rosa de Cabal.</p>
      </div>
      <div class="pie-pagina__enlaces">
        <a class="pie-pagina__enlace" href="#">Privacidad</a>
        <a class="pie-pagina__enlace" href="#">Términos</a>
        <a class="pie-pagina__enlace" href="#">Instagram</a>
        <a class="pie-pagina__enlace" href="#">Facebook</a>
      </div>
    </div>
  </footer>

  <script>
  /* ════════════════════════════════════════════════════
     DETALLES TURÍSTICOS — Carga dinámica AJAX
  ════════════════════════════════════════════════════ */

  const PLAN_ID   = <?= $planId ?>;
  const LOGUEADO  = <?= $logueado ? 'true' : 'false' ?>;
  let   planActual = null;

  /* ── Toast ── */
  function mostrarToast(msg, tipo) {
    const t = document.getElementById('toast-plan');
    t.textContent = msg;
    t.style.display = 'block';
    t.style.background = tipo === 'ok' ? '#d1fae5' : '#fee2e2';
    t.style.color      = tipo === 'ok' ? '#065f46' : '#7f1d1d';
    t.style.border     = `1px solid ${tipo === 'ok' ? '#6ee7b7' : '#fca5a5'}`;
    requestAnimationFrame(() => { t.style.opacity = '1'; });
    setTimeout(() => {
      t.style.opacity = '0';
      setTimeout(() => { t.style.display = 'none'; }, 300);
    }, 3500);
  }

  /* ── Renderizar estrellas ── */
  function estrellas(n) {
    let html = '';
    for (let i = 1; i <= 5; i++) {
      html += `<span class="material-symbols-outlined" style="font-size:1.125rem;color:#054da4;${i <= Math.round(n) ? "font-variation-settings:'FILL' 1,'wght' 400,'GRAD' 0,'opsz' 24;" : ''}">${i <= Math.round(n) ? 'star' : 'star'}</span>`;
    }
    return html;
  }

  /* ── Renderizar plan completo ── */
  function renderPlan(plan) {
    const imgHero = plan.imagen_hero_url || 'https://lh3.googleusercontent.com/aida-public/AB6AXuCeBw6cpP_2_9duN8rV_LaehJ3L3gvss-p648PtuXqpEyGz86c_LYLXCKXRSG1D0ui3RPWhHH4n_4579-eXZtdKurLR86TevI0HvdE_Qf0ub2uyBqQljLD6Pamw1yKzgCIRC-cnD81gVv2wnO1Ra8l2C9YE4CkEbWQLH9cs4FTe_VHMVCdy5K7Kb5fKr4E6N_PnaYCgBD7sRmvmiIxHJcXyOTOhMP1pKucGA6Q3P7jyYWLmIyGIi0OD4VY1ELQ2h6lkrZp1iTXusIX8';

    // Itinerario HTML
    const itineHTML = (plan.itinerario || []).map((d, i) => `
      <div class="dia-item ${i % 2 === 0 ? 'dia-item--cristal' : 'dia-item--contenedor'}">
        <div class="dia-item__numero ${i % 2 === 0 ? 'dia-item__numero--primario' : 'dia-item__numero--secundario'}">
          ${String(d.numero_dia).padStart(2,'0')}
        </div>
        <div class="dia-item__contenido">
          <h4 class="dia-item__titulo">${d.titulo}</h4>
          <p class="dia-item__descripcion">${d.descripcion}</p>
        </div>
      </div>`).join('');

    // Qué incluye HTML
    const incluyeHTML = (plan.incluye || []).map(item => `
      <li class="incluye-item">
        <span class="material-symbols-outlined icono-filled icono-check">check_circle</span>
        ${item.descripcion}
      </li>`).join('');

    // Reseñas HTML
    const resenasHTML = (plan.resenas || []).map(r => `
      <div style="padding:1rem 0; border-bottom:1px solid rgba(195,198,212,.3);">
        <div style="display:flex;gap:.25rem;margin-bottom:.5rem;">${estrellas(r.puntuacion)}</div>
        <p style="font-size:.875rem;font-style:italic;color:#424752;margin:0 0 .5rem;">"${r.comentario}"</p>
        <span style="font-size:.75rem;font-weight:700;color:#002021;">— ${r.autor_nombre}${r.autor_cargo ? ', ' + r.autor_cargo : ''}</span>
      </div>`).join('');

    // Botón agregar (solo logueados con rol cliente)
    const btnAgregar = LOGUEADO
      ? `<button class="boton-reservar" id="btn-agregar-plan" style="margin-top:1rem;background:#00595e;">
           <span class="material-symbols-outlined" style="margin-right:.5rem;vertical-align:middle;">add_circle</span>
           Agregar a mi cuenta
         </button>`
      : `<a href="/login.php" style="display:block;margin-top:1rem;text-align:center;font-size:.875rem;color:#306388;font-weight:600;">
           Inicia sesión para reservar
         </a>`;

    document.getElementById('contenido-plan').innerHTML = `
      <!-- Hero -->
      <section class="seccion-hero">
        <div class="hero-fondo">
          <img class="hero-imagen" alt="${plan.titulo}" src="${imgHero}"/>
          <div class="hero-degradado"></div>
        </div>
        <div class="hero-contenido">
          <div class="hero-texto">
            <span class="hero-etiqueta">${plan.etiqueta || 'Experiencia'}</span>
            <h1 class="hero-titulo">${plan.titulo}</h1>
            <div class="hero-meta">
              <div class="hero-meta__item">
                <span class="material-symbols-outlined">location_on</span>
                <span>${plan.ubicacion}</span>
              </div>
              <div class="hero-meta__item hero-meta__item--precio">
                <span class="material-symbols-outlined">payments</span>
                <span>Desde $${plan.precio_formateado} ${plan.moneda}</span>
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
                  <span class="material-symbols-outlined icono-filled">hotel</span>
                  <span class="destacado-item__etiqueta">${plan.duracion_dias} ${plan.duracion_dias > 1 ? 'Noches' : 'Noche'}</span>
                </div>
                <div class="destacado-item">
                  <span class="material-symbols-outlined icono-filled">star</span>
                  <span class="destacado-item__etiqueta">${parseFloat(plan.puntuacion).toFixed(1)} (${plan.total_resenas})</span>
                </div>
                <div class="destacado-item">
                  <span class="material-symbols-outlined icono-filled">location_on</span>
                  <span class="destacado-item__etiqueta">Risaralda</span>
                </div>
                <div class="destacado-item">
                  <span class="material-symbols-outlined icono-filled">verified</span>
                  <span class="destacado-item__etiqueta">${plan.etiqueta || 'Premium'}</span>
                </div>
              </div>
            </div>

            ${itineHTML ? `
            <div class="itinerario">
              <h3 class="itinerario__titulo">Tu Itinerario</h3>
              <div class="itinerario__lista">${itineHTML}</div>
            </div>` : ''}
          </div>

          <!-- Columna lateral -->
          <aside class="columna-lateral">
            <div class="lateral-sticky">

              <div class="tarjeta-reserva">
                <h3 class="tarjeta-reserva__titulo">Reserva tu Experiencia</h3>
                <p class="tarjeta-reserva__subtitulo">Disponibilidad limitada. ¡No pierdas tu lugar!</p>
                <div class="reserva-campos">
                  <div class="reserva-campo">
                    <span class="reserva-campo__etiqueta">Duración</span>
                    <span class="reserva-campo__valor">${plan.duracion_dias} ${plan.duracion_dias > 1 ? 'días' : 'día'}</span>
                  </div>
                  <div class="reserva-campo">
                    <span class="reserva-campo__etiqueta">Desde</span>
                    <span class="reserva-campo__valor">$${plan.precio_formateado} ${plan.moneda}</span>
                  </div>
                </div>
                <button class="boton-reservar" id="btn-reservar-ahora">Reservar Ahora</button>
                ${btnAgregar}
                <p class="reserva-nota">Cancelación gratuita hasta 15 días antes.</p>
              </div>

              ${incluyeHTML ? `
              <div class="tarjeta-incluye">
                <h3 class="tarjeta-incluye__titulo">Qué incluye</h3>
                <ul class="lista-incluye">${incluyeHTML}</ul>
              </div>` : ''}

              ${resenasHTML ? `
              <div class="tarjeta-cristal" style="padding:1.5rem;">
                <h3 style="font-family:'Plus Jakarta Sans',sans-serif;font-size:1.125rem;font-weight:700;color:#054da4;margin:0 0 1rem;">Opiniones</h3>
                ${resenasHTML}
              </div>` : ''}
            </div>
          </aside>

        </div>
      </section>`;

    // Eventos botones
    document.getElementById('btn-reservar-ahora')?.addEventListener('click', abrirModal);
    document.getElementById('btn-agregar-plan')?.addEventListener('click', abrirModal);
  }

  /* ── Modal agregar plan ── */
  function abrirModal() {
    if (!LOGUEADO) { window.location.href = '/login.php'; return; }
    // Fecha mínima = hoy
    const hoy = new Date().toISOString().split('T')[0];
    document.getElementById('modal-fecha').min   = hoy;
    document.getElementById('modal-fecha').value = hoy;
    document.getElementById('modal-alerta').style.display = 'none';
    const modal = document.getElementById('modal-agregar');
    modal.style.display = 'flex';
  }

  document.getElementById('modal-cancelar').addEventListener('click', () => {
    document.getElementById('modal-agregar').style.display = 'none';
  });

  document.getElementById('modal-confirmar').addEventListener('click', async () => {
    const fecha     = document.getElementById('modal-fecha').value;
    const adultos   = parseInt(document.getElementById('modal-adultos').value) || 1;
    const alertaEl  = document.getElementById('modal-alerta');
    const btnConf   = document.getElementById('modal-confirmar');

    if (!fecha) {
      alertaEl.textContent = 'Selecciona una fecha.';
      alertaEl.style.cssText = 'display:block;background:rgba(239,68,68,.12);color:#7f1d1d;border:1px solid #fca5a5;padding:.75rem;border-radius:.5rem;font-size:.875rem;font-weight:600;';
      return;
    }

    btnConf.disabled    = true;
    btnConf.textContent = 'Procesando…';

    try {
      const res  = await fetch('/ajax/agregar_plan.php', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json' },
        body:    JSON.stringify({ plan_id: PLAN_ID, fecha_inicio: fecha, num_adultos: adultos }),
      });
      const data = await res.json();

      if (data.ok) {
        document.getElementById('modal-agregar').style.display = 'none';
        mostrarToast(data.msg, 'ok');
      } else if (data.login) {
        window.location.href = '/login.php';
      } else {
        alertaEl.textContent = data.msg;
        alertaEl.style.cssText = 'display:block;background:rgba(239,68,68,.12);color:#7f1d1d;border:1px solid #fca5a5;padding:.75rem;border-radius:.5rem;font-size:.875rem;font-weight:600;';
      }
    } catch (err) {
      alertaEl.textContent = 'Error de conexión. Intenta de nuevo.';
      alertaEl.style.cssText = 'display:block;background:rgba(239,68,68,.12);color:#7f1d1d;border:1px solid #fca5a5;padding:.75rem;border-radius:.5rem;font-size:.875rem;font-weight:600;';
    }

    btnConf.disabled    = false;
    btnConf.textContent = 'Confirmar reserva';
  });

  /* ── Cierra modal clickando fuera ── */
  document.getElementById('modal-agregar').addEventListener('click', function(e) {
    if (e.target === this) this.style.display = 'none';
  });

  /* ── Logout AJAX ── */
  document.getElementById('btn-logout-det')?.addEventListener('click', async () => {
    await fetch('/ajax/logout.php', { method: 'POST' });
    window.location.href = '/index.php';
  });

  /* ── Carga del plan ── */
  async function cargarPlan() {
    if (!PLAN_ID) {
      document.getElementById('contenido-plan').innerHTML =
        '<p style="padding:4rem;text-align:center;color:#306388;">Plan no especificado.</p>';
      return;
    }
    try {
      const res  = await fetch(`/ajax/plan_turistico_detalle.php?id=${PLAN_ID}`);
      const data = await res.json();

      if (data.ok) {
        planActual = data.plan;
        renderPlan(data.plan);
      } else {
        document.getElementById('contenido-plan').innerHTML =
          `<p style="padding:4rem;text-align:center;color:#ba1a1a;">${data.msg}</p>`;
      }
    } catch (err) {
      console.error('Error cargando plan:', err);
    }
  }

  document.addEventListener('DOMContentLoaded', cargarPlan);
  </script>

  <script src="/scripts/script-detalles-turisticos.js"></script>
</body>
</html>
