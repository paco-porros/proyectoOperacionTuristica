<!DOCTYPE html>
<html class="claro" lang="es">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>Registro | Ethereal Voyager</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <script src="tailwind.config.js"></script>
  <link rel="stylesheet" href="style.css"/>
</head>
<body class="fuente-cuerpo texto-superficie fondo-principal altura-minima columna-flexible relativo sin-desbordamiento-horizontal">

  <!-- Capa de imagen de fondo -->
  <div class="capa-fondo-fija">
    <img
      class="imagen-fondo"
      alt="Vista panorámica de picos nevados bajo cielo azul con luz matutina"
      src="https://lh3.googleusercontent.com/aida-public/AB6AXuAq7mzBIISCjyO3Vav6Kdwb3xt7hARVkrgORLSpWeFGTIQscGSrDjrkJZmsXal_5Pe54PiSVTzRyAByQDNxRxl5-2izuEkX8BDJh8r-_u064Vnam-POGa7FzOKXO8dx489vVe7Rgxk-gIpYZyV-qXwxFsLrQHX4mjw60j-0S4cN9Q5RxaTG8EEX4KsZDr0SOEN5Dd6CuyPrCn_mCnl5IKFjEFyrW6Ceo_KpB7pqvacI7lPtEoGlpGzygdZMNnBUSRiFCgS2BB9FGRw"
    />
    <div class="superposicion-fondo"></div>
  </div>

  <!-- Contenedor principal -->
  <main class="contenedor-principal">

    <!-- Tarjeta de registro -->
    <div class="tarjeta-registro">

      <!-- Marca y título -->
      <div class="encabezado-registro">
        <div class="contenedor-marca">
          <span class="nombre-marca">Ethereal Voyager</span>
        </div>
        <h1 class="titulo-registro">Crea tu cuenta</h1>
        <p class="subtitulo-registro">Únete a la comunidad de viajeros más exclusiva</p>
      </div>

      <!-- Formulario de registro -->
      <form class="formulario-registro" id="formulario-registro">

        <!-- Nombre completo -->
        <div class="grupo-campo">
          <label class="etiqueta-campo" for="nombre-completo">Nombre Completo</label>
          <div class="contenedor-input-icono">
            <span class="material-symbols-outlined icono-campo" data-icon="person">person</span>
            <input
              class="campo-cristal"
              id="nombre-completo"
              placeholder="Juan Pérez"
              type="text"
            />
          </div>
        </div>

        <!-- Correo electrónico -->
        <div class="grupo-campo">
          <label class="etiqueta-campo" for="correo">Correo Electrónico</label>
          <div class="contenedor-input-icono">
            <span class="material-symbols-outlined icono-campo" data-icon="mail">mail</span>
            <input
              class="campo-cristal"
              id="correo"
              placeholder="tu@email.com"
              type="email"
            />
          </div>
        </div>

        <!-- Cuadrícula de contraseñas -->
        <div class="cuadricula-contrasenas">

          <!-- Contraseña -->
          <div class="grupo-campo">
            <label class="etiqueta-campo" for="contrasena">Contraseña</label>
            <div class="contenedor-input-icono">
              <span class="material-symbols-outlined icono-campo" data-icon="lock">lock</span>
              <input
                class="campo-cristal"
                id="contrasena"
                placeholder="••••••••"
                type="password"
              />
            </div>
          </div>

          <!-- Confirmar contraseña -->
          <div class="grupo-campo">
            <label class="etiqueta-campo" for="confirmar-contrasena">Confirmar Contraseña</label>
            <div class="contenedor-input-icono">
              <span class="material-symbols-outlined icono-campo" data-icon="verified_user">verified_user</span>
              <input
                class="campo-cristal"
                id="confirmar-contrasena"
                placeholder="••••••••"
                type="password"
              />
            </div>
          </div>

        </div>

        <!-- Términos y condiciones -->
        <div class="fila-terminos">
          <input
            class="checkbox-terminos"
            id="terminos"
            type="checkbox"
          />
          <label class="etiqueta-terminos" for="terminos">
            Acepto los
            <a class="enlace-terminos" href="#">Términos de Servicio</a>
            y la
            <a class="enlace-terminos" href="#">Política de Privacidad</a>.
          </label>
        </div>

        <!-- Botón de registro -->
        <button class="boton-registrarse" id="boton-registrarse" type="submit">
          <span>Registrarse</span>
          <span class="material-symbols-outlined icono-flecha" data-icon="arrow_forward">arrow_forward</span>
        </button>

      </form>

      <!-- Divisor de autenticación social -->
      <div class="divisor-social">
        <div class="linea-divisor"></div>
        <span class="texto-divisor">O regístrate con</span>
        <div class="linea-divisor"></div>
      </div>

      <!-- Botones sociales -->
      <div class="cuadricula-botones-sociales">

        <button class="boton-social" id="boton-google-registro">
          <svg class="icono-social" viewBox="0 0 24 24">
            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="currentColor"/>
            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="currentColor"/>
            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="currentColor"/>
            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.66l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="currentColor"/>
          </svg>
          Google
        </button>

        <button class="boton-social" id="boton-facebook-registro">
          <svg class="icono-social" viewBox="0 0 24 24">
            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" fill="currentColor"/>
          </svg>
          Facebook
        </button>

      </div>

      <!-- Enlace a inicio de sesión -->
      <div class="pie-tarjeta-registro">
        <p class="texto-ya-cuenta">
          ¿Ya tienes una cuenta?
          <a class="enlace-iniciar-sesion" href="login.html">Inicia sesión aquí</a>
        </p>
      </div>

    </div>
  </main>

  <!-- Pie de página -->
  <footer class="pie-pagina-registro">
    <span class="copyright-registro">© 2024 Ethereal Voyager. El Horizonte Translúcido.</span>
    <div class="enlaces-pie-registro">
      <a class="enlace-pie-registro" href="#">Privacidad</a>
      <a class="enlace-pie-registro" href="#">Términos</a>
      <a class="enlace-pie-registro" href="#">Soporte</a>
    </div>
  </footer>

  <script src="script-registro.js"></script>
</body>
</html>