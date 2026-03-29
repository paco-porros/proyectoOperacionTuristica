<?php
/**
 * registro.php — con registro AJAX
 */
require_once __DIR__ . '/includes/session.php';

if (estaLogueado()) {
    header('Location: /home.php');
    exit;
}
?>
<!DOCTYPE html>
<html class="claro" lang="es">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>Registro | Operador Turístico</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <script src="tailwind.config.js"></script>
  <link rel="stylesheet" href="./estilos/style-registro.css"/>
  <style>
    .contenedor-input-icono { position: relative; }
    .icono-ojo { position: absolute; right: 10px; cursor: pointer; color: #aaa; }

    /* Nav y footer visibles sobre el fondo fijo */
    .barra-navegacion {
      position: fixed !important;
      top: 0 !important;
      left: 0 !important;
      width: 100% !important;
      z-index: 9999 !important;
      display: flex !important;
      justify-content: space-between;
      align-items: center;
      padding: 1rem 2rem;
      background-color: rgba(236, 226, 208, 0.15);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      box-shadow: 0 4px 20px rgba(236, 226, 208, 0.08);
    }
    .logo-marca {
      font-weight: 700;
      font-size: 1.5rem;
      color: #3066be;
    }
    .logo-marca a {
      color: inherit;
      text-decoration: none;
    }
    .pie-pagina {
      position: relative !important;
      z-index: 10 !important;
      width: 100%;
      padding: 3rem 2rem;
      display: flex !important;
      flex-direction: column;
      align-items: center;
      justify-content: space-between;
      gap: 1.5rem;
      background-color: rgba(46, 20, 40, 0.88);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      margin-top: auto;
    }
    @media (min-width: 768px) { .pie-pagina { flex-direction: row; gap: 0; } }
    .logo-pie  { font-weight: 700; color: #adc6ff; }
    .enlaces-pie { display: flex; flex-wrap: wrap; justify-content: center; gap: 2rem; }
    .enlace-pie  { font-size: .7rem; letter-spacing: .1em; text-transform: uppercase; color: rgba(232,208,213,.6); text-decoration: none; }
    .enlace-pie:hover { color: #adc6ff; }
    .copyright-pie { font-size: .7rem; letter-spacing: .1em; text-transform: uppercase; color: rgba(232,208,213,.5); }
  </style>
</head>
<body class="fuente-cuerpo texto-superficie fondo-principal altura-minima columna-flexible relativo sin-desbordamiento-horizontal">

  <div class="capa-fondo-fija">
    <img class="imagen-fondo" alt="Vista panorámica" src="./img/santarosa.webp"/>
    <div class="superposicion-fondo"></div>
  </div>

  <nav class="barra-navegacion">
    <div class="logo-marca">
      <a href="index.php">Operador Turístico y Gastronomico | Santa Rosa de Cabal</a>
    </div>
  </nav>

  <main class="contenedor-principal">
    <div class="tarjeta-registro">

      <div class="encabezado-registro">
        <div class="contenedor-marca">
          <span class="nombre-marca">Operador Turístico y Gastronomico | Santa Rosa de Cabal</span>
        </div>
        <h1 class="titulo-registro">Crea tu cuenta</h1>
        <p class="subtitulo-registro">Únete a la comunidad de viajeros más exclusiva</p>
      </div>

      <!-- Alerta AJAX -->
      <div id="alerta-registro" style="display:none; margin-bottom:1rem; padding:.75rem 1rem; border-radius:.5rem; font-size:.875rem; font-weight:600; text-align:center;"></div>

      <form class="formulario-registro" id="formulario-registro" novalidate>

        <div class="grupo-campo">
          <label class="etiqueta-campo" for="nombre-completo">Nombre Completo</label>
          <div class="contenedor-input-icono">
            <span class="material-symbols-outlined icono-campo">person</span>
            <input class="campo-cristal" id="nombre-completo" name="nombre" placeholder="Juan Pérez" type="text" autocomplete="name" style="color:#000;"/>
          </div>
        </div>

        <div class="grupo-campo">
          <label class="etiqueta-campo" for="correo">Correo Electrónico</label>
          <div class="contenedor-input-icono">
            <span class="material-symbols-outlined icono-campo">mail</span>
            <input class="campo-cristal" id="correo" name="email" placeholder="tu@email.com" type="email" autocomplete="email" style="color:#000;"/>
          </div>
        </div>

        <div class="cuadricula-contrasenas">
          <div class="grupo-campo">
            <label class="etiqueta-campo" for="contrasena">Contraseña</label>
            <div class="contenedor-input-icono">
              <span class="material-symbols-outlined icono-campo">lock</span>
              <input class="campo-cristal" id="contrasena" name="password" placeholder="••••••••" type="password" autocomplete="new-password" style="color:#000;"/>
              <span class="material-symbols-outlined icono-ojo" id="togglePassword">visibility</span>
            </div>
          </div>

          <div class="grupo-campo">
            <label class="etiqueta-campo" for="confirmar-contrasena">Confirmar Contraseña</label>
            <div class="contenedor-input-icono">
              <span class="material-symbols-outlined icono-campo">verified_user</span>
              <input class="campo-cristal" id="confirmar-contrasena" name="confirmar" placeholder="••••••••" type="password" autocomplete="new-password" style="color:#000;"/>
              <span class="material-symbols-outlined icono-ojo" id="togglePassword2">visibility</span>
            </div>
          </div>
        </div>

        <div class="fila-terminos">
          <input class="checkbox-terminos" id="terminos" type="checkbox"/>
          <label class="etiqueta-terminos" for="terminos">
            Acepto los <a class="enlace-terminos" href="#">Términos de Servicio</a> y la
            <a class="enlace-terminos" href="#">Política de Privacidad</a>.
          </label>
        </div>

        <button class="boton-registrarse" id="btn-registro" type="submit">
          <span>Registrarse</span>
          <span class="material-symbols-outlined">arrow_forward</span>
        </button>

      </form>

      <div class="pie-tarjeta-registro">
        <p class="texto-ya-cuenta">
          ¿Ya tienes una cuenta?
          <a class="enlace-iniciar-sesion" href="login.php">Inicia sesión aquí</a>
        </p>
      </div>

    </div>
  </main>

  <footer class="pie-pagina">
    <div class="logo-pie">Operador Turístico y Gastronomico | Santa Rosa de Cabal</div>
    <div class="enlaces-pie">
      <a class="enlace-pie" href="#">Privacy Policy</a>
      <a class="enlace-pie" href="#">Terms of Service</a>
      <a class="enlace-pie" href="#">Support</a>
      <a class="enlace-pie" href="#">Contact</a>
    </div>
    <div class="copyright-pie">© 2024 Operador Turístico y Gastronomico | Santa Rosa de Cabal.</div>
  </footer>

  <script>
  /* ── Toggle contraseñas ── */
  const camposContrasena = {
    togglePassword: document.getElementById('contrasena'),
    togglePassword2: document.getElementById('confirmar-contrasena'),
  };

  Object.entries(camposContrasena).forEach(([btnId, inp]) => {
    const btn = document.getElementById(btnId);
    if (!btn || !inp) return;

    inp.style.color = '#000';
    btn.addEventListener('click', function () {
      const show = inp.type === 'password';
      inp.type = show ? 'text' : 'password';
      this.textContent = show ? 'visibility_off' : 'visibility';
      inp.style.color = '#000';
      inp.focus();
    });
  });

  /* ── Alerta helper ── */
  function mostrarAlerta(msg, tipo) {
    const el = document.getElementById('alerta-registro');
    el.textContent = msg;
    el.style.display = 'block';
    if (tipo === 'ok') {
      el.style.background = 'rgba(16,185,129,.15)';
      el.style.color = '#065f46';
      el.style.border = '1px solid #6ee7b7';
    } else {
      el.style.background = 'rgba(239,68,68,.12)';
      el.style.color = '#7f1d1d';
      el.style.border = '1px solid #fca5a5';
    }
  }

  /* ── Envío AJAX ── */
  document.getElementById('formulario-registro').addEventListener('submit', async function (e) {
    e.preventDefault();

    const btn      = document.getElementById('btn-registro');
    const nombre   = document.getElementById('nombre-completo').value.trim();
    const email    = document.getElementById('correo').value.trim();
    const password = document.getElementById('contrasena').value.trim();
    const confirmar = document.getElementById('confirmar-contrasena').value.trim();
    const terminos = document.getElementById('terminos').checked;

    if (!nombre || !email || !password || !confirmar) {
      mostrarAlerta('Por favor completa todos los campos.', 'error'); return;
    }
    if (!terminos) {
      mostrarAlerta('Debes aceptar los Términos de Servicio.', 'error'); return;
    }

    btn.disabled = true;
    btn.querySelector('span').textContent = 'Creando cuenta…';

    try {
      const res  = await fetch('/ajax/registro.php', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json' },
        body:    JSON.stringify({ nombre, email, password, confirmar }),
      });
      const data = await res.json();

      if (data.ok) {
        mostrarAlerta(data.msg, 'ok');
        setTimeout(() => { window.location.href = data.redirect; }, 900);
      } else {
        mostrarAlerta(data.msg, 'error');
        btn.disabled = false;
        btn.querySelector('span').textContent = 'Registrarse';
      }
    } catch (err) {
      mostrarAlerta('Error de conexión. Intenta de nuevo.', 'error');
      btn.disabled = false;
      btn.querySelector('span').textContent = 'Registrarse';
    }
  });
  </script>
</body>
</html>
