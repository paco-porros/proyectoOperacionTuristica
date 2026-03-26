/**
 * detalles-turisticos.js
 * Scripts para la página de detalles turísticos
 */

document.addEventListener('DOMContentLoaded', function () {

  // ============================================================
  // Navegación — efecto scroll
  // ============================================================
  const barraNav = document.querySelector('.barra-nav');

  window.addEventListener('scroll', function () {
    if (window.scrollY > 20) {
      barraNav.style.background = 'rgba(236, 254, 255, 0.6)';
    } else {
      barraNav.style.background = 'rgba(236, 254, 255, 0.15)';
    }
  });

  // ============================================================
  // Botones de reserva (placeholder para integración futura)
  // ============================================================
  const botonesReservar = document.querySelectorAll('.boton-reservar, .nav-boton-reservar');

  botonesReservar.forEach(function (boton) {
    boton.addEventListener('click', function () {
      // TODO: integrar con sistema de reservas
      alert('Redirigiendo al sistema de reservas...');
    });
  });

  // ============================================================
  // Galería — ítem "+12" abre galería completa (placeholder)
  // ============================================================
  const itemMas = document.querySelector('.galeria__item--mas');

  if (itemMas) {
    itemMas.addEventListener('click', function () {
      // TODO: integrar con lightbox o página de galería completa
      alert('Abriendo galería completa...');
    });
  }

});