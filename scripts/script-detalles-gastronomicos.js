/**
 * detalles-gastronomicos.js
 * Scripts para la página de detalles gastronómicos
 */

// Esperar a que el DOM esté listo
document.addEventListener('DOMContentLoaded', function () {

  // ============================================================
  // Navegación activa al hacer scroll
  // ============================================================
  const barra = document.querySelector('.barra-nav');

  window.addEventListener('scroll', function () {
    if (window.scrollY > 20) {
      barra.style.background = 'rgba(236, 254, 255, 0.6)';
    } else {
      barra.style.background = 'rgba(236, 254, 255, 0.15)';
    }
  });

  // ============================================================
  // Botón reservar (placeholder para integración futura)
  // ============================================================
  const botonReservar = document.querySelector('.boton-reservar');
  if (botonReservar) {
    botonReservar.addEventListener('click', function () {
      // TODO: integrar con sistema de reservas
      alert('Redirigiendo al sistema de reservas...');
    });
  }

});