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