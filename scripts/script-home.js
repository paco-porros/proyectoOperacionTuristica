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
  menu.classList.toggle('navegacion__dropdown--visible');
});
document.addEventListener('click', () => {
  document.getElementById('menu-avatar').classList.remove('navegacion__dropdown--visible');
});

/* ── Logout AJAX ── */
document.getElementById('btn-logout-home').addEventListener('click', async () => {
  await fetch('/ajax/logout.php', { method: 'POST' });
  window.location.href = '/index.php';
});

/* ── Botones de navegación ── */
document.getElementById('boton-ver-todos-itinerarios').addEventListener('click', () => {
  window.location.href = 'planes.php';
});
document.getElementById('boton-ver-todos-gastronomia').addEventListener('click', () => {
  window.location.href = 'gastronomia.php';
});

/* ── Render tarjeta gastronómica (formato home) ── */
function renderTarjetaGastroHome(plan) {
  const precio    = plan.precio_formateado;
  const moneda    = plan.moneda === 'COP' ? 'COP' : plan.moneda;
  const estrellas = parseFloat(plan.puntuacion).toFixed(1);
  const img       = plan.imagen_hero_url || '/img/fondoPortada.jpg';
  return `
  <div class="tarjeta-gastro grupo-imagen">
    <div class="tarjeta-gastro__imagen-envoltorio">
      <img class="tarjeta-gastro__imagen" src="${img}" alt="${plan.titulo}" loading="lazy"/>
      <span class="tarjeta-gastro__badge">${plan.etiqueta || ''}</span>
    </div>
    <div class="tarjeta-gastro__cuerpo panel-cristal borde-superior-cristal">
      <span class="tarjeta-gastro__restaurante">${plan.restaurante_nombre}</span>
      <h3 class="tarjeta-gastro__titulo">${plan.titulo}</h3>
      <div class="tarjeta-gastro__meta">
        <span class="tarjeta-gastro__precio">$${precio} ${moneda}</span>
        <span class="tarjeta-gastro__calificacion">
          <span class="material-symbols-outlined">star</span> ${estrellas}
        </span>
      </div>
      <div class="acciones-tarjeta">
        <button class="boton-tarjeta boton-tarjeta--blanco boton-tarjeta--pequeno"
                onclick="window.location.href='detalles-gastronomicos.php?id=${plan.id}'">
          Ver info <span class="material-symbols-outlined">arrow_forward</span>
        </button>
      </div>
    </div>
  </div>`;
}

/* ── Carga 3 ofertas gastronómicas desde la BD ── */
async function cargarGastronomiaHome() {
  try {
    const res  = await fetch('/ajax/gastronomicos.php?limite=3');
    const data = await res.json();
    const grid = document.getElementById('cuadricula-gastronomia');
    if (data.ok && data.planes.length) {
      grid.innerHTML = data.planes.map(p => renderTarjetaGastroHome(p)).join('');
    } else {
      grid.innerHTML = '<p style="padding:2rem;color:#6b4558;">No hay ofertas disponibles.</p>';
    }
  } catch (err) {
    console.error('Error cargando gastronomía home:', err);
  }
}

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

document.addEventListener('DOMContentLoaded', () => { cargarBento(); cargarGastronomiaHome(); });