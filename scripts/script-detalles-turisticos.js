/* ════════════════════════════════════════════════════
   DETALLES TURÍSTICOS — Carga dinámica AJAX
   NOTA: PLAN_ID y LOGUEADO se definen en un <script> inline
         en el PHP antes de cargar este archivo.
════════════════════════════════════════════════════ */


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

/* ── Renderizar estrellas ── */
function estrellas(n) {
  let html = '';
  for (let i = 1; i <= 5; i++) {
    html += `<span class="material-symbols-outlined estrella-resena ${i <= Math.round(n) ? 'estrella-resena--activa' : ''}">star</span>`;
  }
  return html;
}

/* ── Renderizar plan completo ── */
function renderPlan(plan) {
  const imgHero = plan.imagen_hero_url || 'https://lh3.googleusercontent.com/aida-public/AB6AXueBw6cpP_2_9duN8rV_LaehJ3L3gvss-p648PtuXqpEyGz86c_LYLXCKXRSG1D0ui3RPWhHH4n_4579-eXZtdKurLR86TevI0HvdE_Qf0ub2uyBqQljLD6Pamw1yKzgCIRC-cnD81gVv2wnO1Ra8l2C9YE4CkEbWQLH9cs4FTe_VHMVCdy5K7Kb5fKr4E6N_PnaYCgBD7sRmvmiIxHJcXyOTOhMP1pKucGA6Q3P7jyYWLmIyGIi0OD4VY1ELQ2h6lkrZp1iTXusIX8';

  const itineHTML = (plan.itinerario || []).map((d, i) => `
    <div class="dia-item ${i % 2 === 0 ? 'dia-item--cristal' : 'dia-item--contenedor'}">
      <div class="dia-item__numero ${i % 2 === 0 ? 'dia-item__numero--primario' : 'dia-item__numero--secundario'}">
        ${String(d.numero_dia).padStart(2, '0')}
      </div>
      <div class="dia-item__contenido">
        <h4 class="dia-item__titulo">${d.titulo}</h4>
        <p class="dia-item__descripcion">${d.descripcion}</p>
      </div>
    </div>`).join('');

  const incluyeHTML = (plan.incluye || []).map(item => `
    <li class="incluye-item">
      <span class="material-symbols-outlined icono-filled icono-check">check_circle</span>
      ${item.descripcion}
    </li>`).join('');

  const resenasHTML = (plan.resenas || []).map(r => `
    <div class="resena-item">
      <div class="resena-estrellas">${estrellas(r.puntuacion)}</div>
      <p class="resena-comentario">"${r.comentario}"</p>
      <span class="resena-autor">— ${r.autor_nombre}${r.autor_cargo ? ', ' + r.autor_cargo : ''}</span>
    </div>`).join('');

  const btnAgregar = LOGUEADO
    ? `<button class="boton-reservar boton-agregar" id="btn-agregar-plan">
         <span class="material-symbols-outlined">add_circle</span>
         Agregar a mi cuenta
       </button>`
    : `<a href="/login.php" class="enlace-login-reservar">
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
            <div class="tarjeta-cristal tarjeta-opiniones">
              <h3 class="opiniones-titulo">Opiniones</h3>
              ${resenasHTML}
            </div>` : ''}
          </div>
        </aside>
      </div>
    </section>`;

  document.getElementById('btn-reservar-ahora')?.addEventListener('click', abrirModal);
  document.getElementById('btn-agregar-plan')?.addEventListener('click', abrirModal);
}

/* ── Modal agregar plan ── */
function abrirModal() {
  if (!LOGUEADO) { window.location.href = '/login.php'; return; }
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
  const fecha    = document.getElementById('modal-fecha').value;
  const adultos  = parseInt(document.getElementById('modal-adultos').value) || 1;
  const alertaEl = document.getElementById('modal-alerta');
  const btnConf  = document.getElementById('modal-confirmar');

  if (!fecha) {
    alertaEl.textContent = 'Selecciona una fecha.';
    alertaEl.className = 'modal-alerta modal-alerta--error'; alertaEl.style.display = 'block';
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
      alertaEl.className = 'modal-alerta modal-alerta--error'; alertaEl.style.display = 'block';
    }
  } catch (err) {
    alertaEl.textContent = 'Error de conexión. Intenta de nuevo.';
    alertaEl.className = 'modal-alerta modal-alerta--error'; alertaEl.style.display = 'block';
  }

  btnConf.disabled    = false;
  btnConf.textContent = 'Confirmar reserva';
});

/* ── Cierra modal clickando fuera ── */
document.getElementById('modal-agregar').addEventListener('click', function (e) {
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
      '<p class="mensaje-estado">Plan no especificado.</p>';
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
        `<p class="mensaje-estado mensaje-estado--error">${data.msg}</p>`;
    }
  } catch (err) {
    console.error('Error cargando plan:', err);
  }
}

document.addEventListener('DOMContentLoaded', cargarPlan);