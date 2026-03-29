/* ════════════════════════════════════════════════════
   LEGALES.PHP — Acordeón de FAQs
════════════════════════════════════════════════════ */

document.querySelectorAll('.pregunta-frecuente__cabecera').forEach(function (boton) {
  boton.addEventListener('click', function () {
    var expandido = this.getAttribute('aria-expanded') === 'true';
    /* Cierra todos los demás */
    document.querySelectorAll('.pregunta-frecuente__cabecera').forEach(function (b) {
      b.setAttribute('aria-expanded', 'false');
      b.closest('.pregunta-frecuente').classList.remove('pregunta-frecuente--abierta');
    });
    /* Abre el actual si estaba cerrado */
    if (!expandido) {
      this.setAttribute('aria-expanded', 'true');
      this.closest('.pregunta-frecuente').classList.add('pregunta-frecuente--abierta');
    }
  });
});