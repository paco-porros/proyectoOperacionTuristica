/* ============================================================
   script-home.js — Ethereal Voyager · Página principal
   ============================================================ */

document.addEventListener("DOMContentLoaded", () => {

  /* ----------------------------------------------------------
     Referencias a elementos del DOM
     ---------------------------------------------------------- */
  const inputBusqueda       = document.getElementById("input-busqueda");
  const botonExplorar       = document.getElementById("boton-explorar");
  const botonVerTodos       = document.getElementById("boton-ver-todos-itinerarios");
  const botonNotificaciones = document.getElementById("boton-notificaciones");
  const avatarUsuario       = document.getElementById("avatar-usuario");
  const inputBoletin        = document.getElementById("input-boletin");
  const botonBoletin        = document.getElementById("boton-boletin");
  const listaSocios         = document.getElementById("lista-socios");

  /* ----------------------------------------------------------
     Barra de búsqueda hero
     ---------------------------------------------------------- */
  botonExplorar.addEventListener("click", () => {
    const destino = inputBusqueda.value.trim();
    if (!destino) {
      inputBusqueda.focus();
      return;
    }
    // Aquí se conectaría con el motor de búsqueda de destinos
    console.log("Explorando destino:", destino);
    mostrarNotificacion(`Buscando: "${destino}"`, "info");
  });

  // También permite buscar presionando Enter
  inputBusqueda.addEventListener("keydown", (evento) => {
    if (evento.key === "Enter") botonExplorar.click();
  });

  /* ----------------------------------------------------------
     Botón "Ver todos" en itinerarios
     ---------------------------------------------------------- */
  botonVerTodos.addEventListener("click", () => {
    console.log("Navegar a todos los itinerarios.");
    // Aquí se navegaría a la página de itinerarios
  });

  /* ----------------------------------------------------------
     Botones de tarjetas de itinerarios (delegación de eventos)
     ---------------------------------------------------------- */
  document.querySelectorAll(".boton-tarjeta--blanco").forEach((boton) => {
    boton.addEventListener("click", () => {
      console.log("Ver detalles del destino.");
    });
  });

  document.querySelectorAll(".boton-tarjeta--cristal").forEach((boton) => {
    boton.addEventListener("click", () => {
      const nombreDestino = boton
        .closest(".info-tarjeta")
        ?.querySelector(".nombre-destino, .nombre-destino-principal")
        ?.textContent?.trim();
      console.log("Agregando a lista:", nombreDestino);
      mostrarNotificacion(`"${nombreDestino}" agregado a tu lista.`, "exito");
    });
  });

  /* ----------------------------------------------------------
     Notificaciones del nav
     ---------------------------------------------------------- */
  botonNotificaciones.addEventListener("click", () => {
    console.log("Abrir panel de notificaciones.");
  });

  /* ----------------------------------------------------------
     Avatar de usuario
     ---------------------------------------------------------- */
  avatarUsuario.addEventListener("click", () => {
    console.log("Abrir perfil de usuario.");
  });

  /* ----------------------------------------------------------
     Boletín informativo (pie de página)
     ---------------------------------------------------------- */
  botonBoletin.addEventListener("click", () => {
    const correo = inputBoletin.value.trim();

    if (!correo || !validarCorreo(correo)) {
      mostrarNotificacion("Por favor ingresa un correo válido.", "error");
      inputBoletin.focus();
      return;
    }

    // Aquí se conectaría con el servicio de boletín
    console.log("Suscripción al boletín:", correo);
    mostrarNotificacion("¡Suscripción exitosa! Bienvenido al boletín.", "exito");
    inputBoletin.value = "";
  });

  inputBoletin.addEventListener("keydown", (evento) => {
    if (evento.key === "Enter") botonBoletin.click();
  });

  /* ----------------------------------------------------------
     Íconos de redes sociales en el pie
     ---------------------------------------------------------- */
  document.getElementById("icono-web").addEventListener("click", () => {
    console.log("Abrir sitio web principal.");
  });

  document.getElementById("icono-foro").addEventListener("click", () => {
    console.log("Abrir comunidad / foro.");
  });

  /* ----------------------------------------------------------
     Efecto de socios: hover con grayscale (ya en CSS,
     esto es por si se quiere extender con lógica futura)
     ---------------------------------------------------------- */
  if (listaSocios) {
    // Placeholder para futuras animaciones de socios
  }

  /* ----------------------------------------------------------
     Utilidades
     ---------------------------------------------------------- */

  /**
   * Valida el formato de un correo electrónico.
   * @param {string} correo
   * @returns {boolean}
   */
  function validarCorreo(correo) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correo);
  }

  /**
   * Muestra una notificación flotante temporal en pantalla.
   * @param {string} texto  - Mensaje a mostrar.
   * @param {string} tipo   - "exito" | "error" | "info"
   */
  function mostrarNotificacion(texto, tipo) {
    const previa = document.getElementById("notificacion-flotante");
    if (previa) previa.remove();

    const colores = {
      exito: { fondo: "#d1fae5", texto: "#065f46", borde: "#6ee7b7" },
      error: { fondo: "#fee2e2", texto: "#7f1d1d", borde: "#fca5a5" },
      info:  { fondo: "#dbeafe", texto: "#1e3a8a", borde: "#93c5fd" },
    };

    const paleta = colores[tipo] || colores.info;

    const notificacion = document.createElement("div");
    notificacion.id = "notificacion-flotante";
    notificacion.textContent = texto;

    Object.assign(notificacion.style, {
      position:     "fixed",
      bottom:       "1.5rem",
      right:        "1.5rem",
      zIndex:       "9999",
      padding:      "0.875rem 1.5rem",
      borderRadius: "0.75rem",
      fontSize:     "0.875rem",
      fontWeight:   "600",
      fontFamily:   "Manrope, sans-serif",
      backgroundColor: paleta.fondo,
      color:        paleta.texto,
      border:       `1px solid ${paleta.borde}`,
      boxShadow:    "0 4px 16px rgba(0,0,0,0.1)",
      transition:   "opacity 0.3s ease",
      opacity:      "0",
    });

    document.body.appendChild(notificacion);

    // Aparece con fadeIn
    requestAnimationFrame(() => {
      notificacion.style.opacity = "1";
    });

    // Desaparece después de 3.5 segundos
    setTimeout(() => {
      notificacion.style.opacity = "0";
      setTimeout(() => notificacion.remove(), 300);
    }, 3500);
  }

});