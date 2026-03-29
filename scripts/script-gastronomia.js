/* ════════════════════════════════════════════════════
   GASTRONOMIA.PHP — Carga todas las ofertas desde la BD
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

document.addEventListener('DOMContentLoaded', cargarTodasLasOfertas);