/* ════════════════════════════════════════════════════
   PLANES.PHP — Carga todos los planes desde la BD
════════════════════════════════════════════════════ */

const IMG_DEFAULT = [
  'https://lh3.googleusercontent.com/aida-public/AB6AXuAqrYiszyVO9b5FJvsR6QPJYsqdT1GRR_mYpjMlPFWjxafulqCduopzqvrPvj3cGmkgQeW_gjY7KmGaR5QZF2cJ5nICZ31UU4QvbFe3hQvpbR3fcNkkiv4PtLHKxPWQaj-m5KvRDvg0BuZM5v2Yx1AK6kk9MFOE-pR83n69Fo6EBiS5FK3SaeIGMpyP68nUPiP33pURX9LjDtg5JKbLoWzeTPEuwFkFJaSET8JWQ8qGwPGcqgJeNRD9fPPvAF0pxhu2cdEaD2v2fV72',
  'https://lh3.googleusercontent.com/aida-public/AB6AXuALZtJ2omilYGHRKrG9x1nwXdgbTd-CLY_tL4_aKJOyNqaE4VqDD1vM-0EgYDbk1b_zQ_Bzwjaj2EL6Jug0TSFK9Q-JoDVcC2zHmYPXmPc1zpUCs9D1NvLosOig4fhqukT6z_XkcA1TJTQ3z1izAZLfA9vpyDhxsvALNQAMbO2erryl9DKcG8sCxPDIU7jo1FK55zo9d97q8LeZ2rLMly0ow4RZmUu4fjbaSEys11NOkjGrGZcKesu4mXcv8VQI1xP2rcQ5i7H1vsOt',
  'https://lh3.googleusercontent.com/aida-public/AB6AXuCGZj183QMPNxArCGjvbpRWb8nIGbfTxzu7KKzsUUwoup4opovL_2ax7638xLfXWsTVJ1v0jzegSzjCRDmhpBuss3YW7lxJc7zEbj7DB-JGTHSRu24vIj4BFSU3WGWajncHqKD4tDTAe35nhNLhcqzJlbtmsBL8xsEyZpj1x8ZGtQeKs0da94l9vSQaoV_fGaAYWq2uPje0-b20Ubur2D8KewvzKCXDnLgnFTPtyV1wnH9ZvVnLXKAA8g6BO7Ol7BbeE0q0uXo3eBwO',
];

function renderPlan(plan, idx) {
  const img       = plan.imagen_hero_url || IMG_DEFAULT[idx % IMG_DEFAULT.length];
  const dias      = plan.duracion_dias > 1 ? `${plan.duracion_dias} Días` : `${plan.duracion_dias} Día`;
  const estrellas = parseFloat(plan.puntuacion).toFixed(1);
  const resenas   = plan.total_resenas;
  const precio    = plan.precio_formateado;
  const moneda    = plan.moneda === 'COP' ? 'COP' : '';

  return `
  <div class="tarjeta-plan panel-vidrio borde-vidrio">
    <div class="tarjeta-plan__imagen-envoltorio">
      <img class="tarjeta-plan__imagen" src="${img}" alt="${plan.titulo}" loading="lazy"/>
    </div>
    <div class="tarjeta-plan__cuerpo">
      <div>
        <div class="tarjeta-plan__fila-superior">
          <h3 class="tarjeta-plan__titulo">${plan.titulo}</h3>
          <span class="tarjeta-plan__etiqueta">${plan.etiqueta || 'Tour'}</span>
        </div>
        <div class="tarjeta-plan__meta">
          <span class="tarjeta-plan__meta-item">
            <span class="material-symbols-outlined">schedule</span> ${dias}
          </span>
          <span class="tarjeta-plan__meta-item">
            <span class="material-symbols-outlined">star</span> ${estrellas} (${resenas} reseñas)
          </span>
        </div>
      </div>
      <div class="tarjeta-plan__pie">
        <div>
          <span class="tarjeta-plan__precio-desde">Desde</span>
          <span class="tarjeta-plan__precio">
            $${precio} <span class="tarjeta-plan__precio-unidad">${moneda} / pers</span>
          </span>
        </div>
        <button class="tarjeta-plan__boton" onclick="window.location.href='detalles-turisticos.php?id=${plan.id}'">
          Ver detalles <span class="material-symbols-outlined">visibility</span>
        </button>
      </div>
    </div>
  </div>`;
}

async function cargarTodosLosPlanes() {
  try {
    const res   = await fetch('/ajax/planes_turisticos.php?sin_limite=1');
    const data  = await res.json();
    const lista = document.getElementById('lista-planes');

    if (data.ok && data.planes.length) {
      lista.innerHTML = data.planes.map((p, i) => renderPlan(p, i)).join('');
      const contador = document.getElementById('contador-planes');
      if (contador) {
        contador.textContent = `${data.planes.length} ${data.planes.length === 1 ? 'plan disponible' : 'planes disponibles'}`;
      }
    } else {
      lista.innerHTML = '<p class="planes-main__vacio">No hay planes disponibles en este momento.</p>';
    }
  } catch (err) {
    console.error('Error cargando planes:', err);
  }
}

document.addEventListener('DOMContentLoaded', cargarTodosLosPlanes);