/* ============================================================
   script.js — Ethereal Voyager
   Configuración de Tailwind CSS y comportamientos interactivos
   ============================================================ */

/* ---------- Configuración de Tailwind ---------- */
tailwind.config = {
  darkMode: "class",
  theme: {
    extend: {
      colors: {
        "surface-container-high":       "#b5f3f6",
        "outline":                      "#737783",
        "error":                        "#ba1a1a",
        "on-secondary-fixed-variant":   "#104b6e",
        "surface":                      "#e5feff",
        "primary":                      "#054da4",
        "on-tertiary":                  "#ffffff",
        "inverse-primary":              "#adc6ff",
        "background":                   "#e5feff",
        "primary-container":            "#3066be",
        "on-primary-fixed":             "#001a41",
        "surface-variant":              "#afedf0",
        "on-background":                "#002021",
        "primary-fixed":                "#d8e2ff",
        "on-tertiary-fixed-variant":    "#004f53",
        "surface-tint":                 "#225cb3",
        "tertiary-container":           "#007479",
        "surface-dim":                  "#a7e5e8",
        "surface-container-low":        "#cbfdff",
        "surface-container-highest":    "#afedf0",
        "on-tertiary-container":        "#93f8ff",
        "on-surface-variant":           "#424752",
        "secondary-fixed":              "#cce5ff",
        "secondary-container":          "#a4d4ff",
        "tertiary-fixed-dim":           "#65d7de",
        "on-primary-container":         "#e0e8ff",
        "inverse-surface":              "#003739",
        "on-primary":                   "#ffffff",
        "surface-container-lowest":     "#ffffff",
        "tertiary":                     "#00595e",
        "on-error-container":           "#93000a",
        "on-primary-fixed-variant":     "#004494",
        "on-tertiary-fixed":            "#002022",
        "outline-variant":              "#c3c6d4",
        "primary-fixed-dim":            "#adc6ff",
        "tertiary-fixed":               "#84f4fb",
        "surface-bright":               "#e5feff",
        "on-error":                     "#ffffff",
        "surface-container":            "#bbf9fc",
        "on-surface":                   "#002021",
        "inverse-on-surface":           "#bdfcff",
        "error-container":              "#ffdad6",
        "on-secondary-fixed":           "#001e31",
        "secondary-fixed-dim":          "#9bccf6",
        "on-secondary-container":       "#285c81",
        "secondary":                    "#306388",
        "on-secondary":                 "#ffffff"
      },
      fontFamily: {
        "headline": ["Plus Jakarta Sans"],
        "body":     ["Manrope"],
        "label":    ["Manrope"]
      },
      borderRadius: {
        "DEFAULT": "1rem",
        "lg":      "2rem",
        "xl":      "3rem",
        "full":    "9999px"
      }
    }
  }
};

/* ---------- Interacciones ---------- */
document.addEventListener("DOMContentLoaded", () => {

  /* Botón "Iniciar Sesión" */
  const botonSesion = document.querySelector(".navegacion__boton");
  if (botonSesion) {
    botonSesion.addEventListener("click", () => {
      alert("Funcionalidad de inicio de sesión próximamente.");
    });
  }

  /* Botones "Ver detalles" de cada plan */
  const botonesPlan = document.querySelectorAll(".tarjeta-plan__boton");
  botonesPlan.forEach((boton) => {
    boton.addEventListener("click", () => {
      const titulo = boton.closest(".tarjeta-plan")
                          .querySelector(".tarjeta-plan__titulo").textContent;
      alert(`Próximamente: detalle del plan "${titulo}"`);
    });
  });

  /* Botones "Ver más" de cada servicio */
  const botonesServicio = document.querySelectorAll(".tarjeta-servicio__enlace");
  botonesServicio.forEach((boton) => {
    boton.addEventListener("click", () => {
      const titulo = boton.closest(".tarjeta-servicio")
                          .querySelector(".tarjeta-servicio__titulo").textContent;
      alert(`Próximamente: más información sobre "${titulo}"`);
    });
  });

  /* Botón "Ver todos los planes" */
  const botonTodos = document.querySelector(".planes__boton-todos");
  if (botonTodos) {
    botonTodos.addEventListener("click", () => {
      alert("Próximamente: catálogo completo de planes.");
    });
  }

});

/* ============================================================
   script.js — Ethereal Voyager · Página de inicio de sesión
   ============================================================ */

document.addEventListener("DOMContentLoaded", () => {

  /* ----------------------------------------------------------
     Referencias a elementos del DOM
     ---------------------------------------------------------- */
  const formulario        = document.getElementById("formulario-login");
  const campoCorrAeo      = document.getElementById("correo");
  const campoContrasena   = document.getElementById("contrasena");
  const botonGoogle       = document.getElementById("boton-google");
  const botonFacebook     = document.getElementById("boton-facebook");

  /* ----------------------------------------------------------
     Envío del formulario principal
     ---------------------------------------------------------- */
  if (formulario) {
    formulario.addEventListener("submit", (evento) => {
      evento.preventDefault();

      const correo     = campoCorrAeo?.value.trim() || "";
      const contrasena = campoContrasena?.value.trim() || "";

      if (!correo || !contrasena) {
        mostrarMensaje("Por favor completa todos los campos.", "error");
        return;
      }

      if (!validarCorreo(correo)) {
        mostrarMensaje("Ingresa un correo electrónico válido.", "error");
        return;
      }

      console.log("Iniciando sesión con:", { correo, contrasena });
      mostrarMensaje("Iniciando sesión…", "exito");
    });
  }

  if (botonGoogle) {
    botonGoogle.addEventListener("click", () => {
      console.log("Inicio de sesión con Google solicitado.");
    });
  }

  if (botonFacebook) {
    botonFacebook.addEventListener("click", () => {
      console.log("Inicio de sesión con Facebook solicitado.");
    });
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
    const expresionRegular = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return expresionRegular.test(correo);
  }

  /**
   * Muestra un mensaje temporal al usuario.
   * @param {string} mensaje  - Texto a mostrar.
   * @param {string} tipo     - "exito" | "error"
   */
  function mostrarMensaje(mensaje, tipo) {
    // Eliminar mensaje previo si existe
    const mensajePrevio = document.getElementById("mensaje-estado");
    if (mensajePrevio) mensajePrevio.remove();

    const contenedor = document.createElement("p");
    contenedor.id = "mensaje-estado";
    contenedor.textContent = mensaje;

    Object.assign(contenedor.style, {
      marginTop:    "0.75rem",
      fontSize:     "0.825rem",
      fontWeight:   "600",
      textAlign:    "center",
      color:        tipo === "exito" ? "#054da4" : "#ba1a1a",
      fontFamily:   "Manrope, sans-serif",
    });

    formulario.appendChild(contenedor);

    // Eliminar el mensaje después de 4 segundos
    setTimeout(() => contenedor.remove(), 4000);
  }

});

/* ============================================================
   script-registro.js — Ethereal Voyager · Página de registro
   ============================================================ */

document.addEventListener("DOMContentLoaded", () => {

  /* ----------------------------------------------------------
     Referencias a elementos del DOM
     ---------------------------------------------------------- */
  const formulario           = document.getElementById("formulario-registro");
  const campoNombre          = document.getElementById("nombre-completo");
  const campoCorrEo          = document.getElementById("correo");
  const campoContrasena      = document.getElementById("contrasena");
  const campoConfirmar       = document.getElementById("confirmar-contrasena");
  const checkboxTerminos     = document.getElementById("terminos");
  const botonGoogle          = document.getElementById("boton-google-registro");
  const botonFacebook        = document.getElementById("boton-facebook-registro");

  /* ----------------------------------------------------------
     Envío del formulario de registro
     ---------------------------------------------------------- */
  formulario.addEventListener("submit", (evento) => {
    evento.preventDefault();

    const nombre     = campoNombre.value.trim();
    const correo     = campoCorrEo.value.trim();
    const contrasena = campoContrasena.value.trim();
    const confirmar  = campoConfirmar.value.trim();
    const terminos   = checkboxTerminos.checked;

    // Validaciones en orden
    if (!nombre) {
      mostrarMensaje("Por favor ingresa tu nombre completo.", "error");
      campoNombre.focus();
      return;
    }

    if (!correo || !validarCorreo(correo)) {
      mostrarMensaje("Ingresa un correo electrónico válido.", "error");
      campoCorrEo.focus();
      return;
    }

    if (!contrasena || contrasena.length < 8) {
      mostrarMensaje("La contraseña debe tener al menos 8 caracteres.", "error");
      campoContrasena.focus();
      return;
    }

    if (contrasena !== confirmar) {
      mostrarMensaje("Las contraseñas no coinciden.", "error");
      campoConfirmar.focus();
      return;
    }

    if (!terminos) {
      mostrarMensaje("Debes aceptar los Términos de Servicio para continuar.", "error");
      return;
    }

    // Aquí se conectaría con el backend de registro
    console.log("Registrando usuario:", { nombre, correo });
    mostrarMensaje("¡Cuenta creada con éxito! Bienvenido a Ethereal Voyager.", "exito");
    formulario.reset();
  });

  /* ----------------------------------------------------------
     Registro con Google
     ---------------------------------------------------------- */
  botonGoogle.addEventListener("click", () => {
    console.log("Registro con Google solicitado.");
    // Integrar con Google OAuth
  });

  /* ----------------------------------------------------------
     Registro con Facebook
     ---------------------------------------------------------- */
  botonFacebook.addEventListener("click", () => {
    console.log("Registro con Facebook solicitado.");
    // Integrar con Facebook OAuth
  });

  /* ----------------------------------------------------------
     Utilidades
     ---------------------------------------------------------- */

  /**
   * Valida el formato de un correo electrónico.
   * @param {string} correo
   * @returns {boolean}
   */
  function validarCorreo(correo) {
    const expresion = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return expresion.test(correo);
  }

  /**
   * Muestra un mensaje de estado temporal debajo del formulario.
   * @param {string} texto - Mensaje a mostrar.
   * @param {string} tipo  - "exito" | "error"
   */
  function mostrarMensaje(texto, tipo) {
    const previo = document.getElementById("mensaje-estado");
    if (previo) previo.remove();

    const mensaje = document.createElement("p");
    mensaje.id = "mensaje-estado";
    mensaje.textContent = texto;

    Object.assign(mensaje.style, {
      marginTop:  "0.75rem",
      fontSize:   "0.825rem",
      fontWeight: "600",
      textAlign:  "center",
      color:      tipo === "exito" ? "#054da4" : "#ba1a1a",
      fontFamily: "Manrope, sans-serif",
    });

    formulario.appendChild(mensaje);

    setTimeout(() => mensaje.remove(), 4000);
  }

});




const togglePassword = document.getElementById("togglePassword");
    const password = document.getElementById("contrasena");

    togglePassword.addEventListener("click", () => {
      const type = password.type === "password" ? "text" : "password";
      password.type = type;

      togglePassword.textContent = type === "password" ? "visibility" : "visibility_off";
    });

    /* ============================================================
   script-registro.js — Ethereal Voyager · Página de registro
   ============================================================ */

document.addEventListener("DOMContentLoaded", () => {

  /* ----------------------------------------------------------
     Referencias a elementos del DOM
     ---------------------------------------------------------- */
  const formulario           = document.getElementById("formulario-registro");
  const campoNombre          = document.getElementById("nombre-completo");
  const campoCorrEo          = document.getElementById("correo");
  const campoContrasena      = document.getElementById("contrasena");
  const campoConfirmar       = document.getElementById("confirmar-contrasena");
  const checkboxTerminos     = document.getElementById("terminos");
  const botonGoogle          = document.getElementById("boton-google-registro");
  const botonFacebook        = document.getElementById("boton-facebook-registro");

  /* ----------------------------------------------------------
     Envío del formulario de registro
     ---------------------------------------------------------- */
  formulario.addEventListener("submit", (evento) => {
    evento.preventDefault();

    const nombre     = campoNombre.value.trim();
    const correo     = campoCorrEo.value.trim();
    const contrasena = campoContrasena.value.trim();
    const confirmar  = campoConfirmar.value.trim();
    const terminos   = checkboxTerminos.checked;

    // Validaciones en orden
    if (!nombre) {
      mostrarMensaje("Por favor ingresa tu nombre completo.", "error");
      campoNombre.focus();
      return;
    }

    if (!correo || !validarCorreo(correo)) {
      mostrarMensaje("Ingresa un correo electrónico válido.", "error");
      campoCorrEo.focus();
      return;
    }

    if (!contrasena || contrasena.length < 8) {
      mostrarMensaje("La contraseña debe tener al menos 8 caracteres.", "error");
      campoContrasena.focus();
      return;
    }

    if (contrasena !== confirmar) {
      mostrarMensaje("Las contraseñas no coinciden.", "error");
      campoConfirmar.focus();
      return;
    }

    if (!terminos) {
      mostrarMensaje("Debes aceptar los Términos de Servicio para continuar.", "error");
      return;
    }

    // Aquí se conectaría con el backend de registro
    console.log("Registrando usuario:", { nombre, correo });
    mostrarMensaje("¡Cuenta creada con éxito! Bienvenido a Ethereal Voyager.", "exito");
    formulario.reset();
  });

  /* ----------------------------------------------------------
     Registro con Google
     ---------------------------------------------------------- */
  botonGoogle.addEventListener("click", () => {
    console.log("Registro con Google solicitado.");
    // Integrar con Google OAuth
  });

  /* ----------------------------------------------------------
     Registro con Facebook
     ---------------------------------------------------------- */
  botonFacebook.addEventListener("click", () => {
    console.log("Registro con Facebook solicitado.");
    // Integrar con Facebook OAuth
  });

  /* ----------------------------------------------------------
     Utilidades
     ---------------------------------------------------------- */

  /**
   * Valida el formato de un correo electrónico.
   * @param {string} correo
   * @returns {boolean}
   */
  function validarCorreo(correo) {
    const expresion = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return expresion.test(correo);
  }

  /**
   * Muestra un mensaje de estado temporal debajo del formulario.
   * @param {string} texto - Mensaje a mostrar.
   * @param {string} tipo  - "exito" | "error"
   */
  function mostrarMensaje(texto, tipo) {
    const previo = document.getElementById("mensaje-estado");
    if (previo) previo.remove();

    const mensaje = document.createElement("p");
    mensaje.id = "mensaje-estado";
    mensaje.textContent = texto;

    Object.assign(mensaje.style, {
      marginTop:  "0.75rem",
      fontSize:   "0.825rem",
      fontWeight: "600",
      textAlign:  "center",
      color:      tipo === "exito" ? "#054da4" : "#ba1a1a",
      fontFamily: "Manrope, sans-serif",
    });

    formulario.appendChild(mensaje);

    setTimeout(() => mensaje.remove(), 4000);
  }

});

console.log("Hola mundo")