/* =========================================================
   script.js — Voyager Admin · Dashboard de Usuarios
   ========================================================= */

document.addEventListener('DOMContentLoaded', () => {

  /* ----- Búsqueda en tiempo real -------------------------- */
  const campoBusqueda = document.querySelector('.campo-busqueda');
  if (campoBusqueda) {
    campoBusqueda.addEventListener('input', (e) => {
      const termino = e.target.value.toLowerCase().trim();
      document.querySelectorAll('.fila-usuario').forEach((fila) => {
        const nombre = fila.querySelector('.nombre-usuario')?.textContent.toLowerCase() ?? '';
        const correo = fila.querySelector('.correo-usuario')?.textContent.toLowerCase() ?? '';
        const rol    = fila.querySelector('.insignia-rol')?.textContent.toLowerCase() ?? '';
        fila.style.display = (nombre + correo + rol).includes(termino) ? '' : 'none';
      });
    });
  }

  /* ----- Paginación activa -------------------------------- */
  document.querySelectorAll('.boton-paginacion').forEach((boton) => {
    if (!boton.querySelector('.material-symbols-outlined')) {
      boton.addEventListener('click', () => {
        document.querySelectorAll('.boton-paginacion')
          .forEach((b) => b.classList.remove('boton-paginacion--activo'));
        boton.classList.add('boton-paginacion--activo');
      });
    }
  });

  /* ----- Nuevo usuario ------------------------------------ */
  const botonNuevo = document.querySelector('.boton-nuevo-usuario');
  if (botonNuevo) {
    botonNuevo.addEventListener('click', () => {
      console.log('Acción: Crear nuevo usuario');
    });
  }

  /* ----- Editar usuario ----------------------------------- */
  document.querySelectorAll('.boton-accion--editar').forEach((boton) => {
    boton.addEventListener('click', (e) => {
      const nombre = e.currentTarget.closest('.fila-usuario')
        ?.querySelector('.nombre-usuario')?.textContent ?? 'desconocido';
      console.log(`Acción: Editar → ${nombre}`);
    });
  });

  /* ----- Eliminar usuario --------------------------------- */
  document.querySelectorAll('.boton-accion--eliminar').forEach((boton) => {
    boton.addEventListener('click', (e) => {
      const fila   = e.currentTarget.closest('.fila-usuario');
      const nombre = fila?.querySelector('.nombre-usuario')?.textContent ?? 'desconocido';
      if (window.confirm(`¿Eliminar a ${nombre}?`)) {
        fila?.remove();
      }
    });
  });

});