/* ════════════════════════════════════════════════════
   DETALLES GASTRONÓMICOS — Página informativa
   Carga datos del restaurante y plan desde la BD vía AJAX
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

        <!-- Columna lateral -->
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
              <button class="boton-reservar" id="btn-reservar-ahora">Reservar Ahora</button>
              ${LOGUEADO
                ? `<button class="boton-reservar boton-agregar" id="btn-agregar-gastro">
                     <span class="material-symbols-outlined">add_circle</span>
                     Agregar a mi cuenta
                   </button>`
                : `<a href="/login.php" class="enlace-login-reservar">
                     Inicia sesión para reservar
                   </a>`
              }
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
  document.getElementById('btn-agregar-gastro')?.addEventListener('click', abrirModal);
}

/* ── Modal reserva ── */
function abrirModal() {
  if (!LOGUEADO) { window.location.href = '/login.php'; return; }
  const hoy = new Date().toISOString().split('T')[0];
  document.getElementById('modal-fecha').min   = hoy;
  document.getElementById('modal-fecha').value = hoy;
  document.getElementById('modal-alerta').style.display = 'none';
  document.getElementById('modal-agregar').style.display = 'flex';
}

document.getElementById('modal-cancelar').addEventListener('click', () => {
  document.getElementById('modal-agregar').style.display = 'none';
});

document.getElementById('modal-agregar').addEventListener('click', function (e) {
  if (e.target === this) this.style.display = 'none';
});

document.getElementById('modal-confirmar').addEventListener('click', async () => {
  const fecha    = document.getElementById('modal-fecha').value;
  const adultos  = parseInt(document.getElementById('modal-adultos').value) || 1;
  const alertaEl = document.getElementById('modal-alerta');
  const btnConf  = document.getElementById('modal-confirmar');

  if (!fecha) {
    alertaEl.textContent = 'Selecciona una fecha.';
    alertaEl.className = 'modal-alerta modal-alerta--error';
    alertaEl.style.display = 'block';
    return;
  }

  btnConf.disabled    = true;
  btnConf.textContent = 'Procesando…';

  try {
    const res  = await fetch('/ajax/agregar_gastronomico.php', {
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
      alertaEl.className = 'modal-alerta modal-alerta--error';
      alertaEl.style.display = 'block';
    }
  } catch (err) {
    alertaEl.textContent = 'Error de conexión. Intenta de nuevo.';
    alertaEl.className = 'modal-alerta modal-alerta--error';
    alertaEl.style.display = 'block';
  }

  btnConf.disabled    = false;
  btnConf.textContent = 'Confirmar reserva';
});

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