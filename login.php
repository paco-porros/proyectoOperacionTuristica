<!DOCTYPE html>
<html class="claro" lang="es">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>Ethereal Voyager - Iniciar Sesión</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
  <script src="tailwind.config.js"></script>
  <link rel="stylesheet" href="style.css"/>
</head>
<body class="fondo-principal fuente-cuerpo texto-superficie seleccion-primaria sin-desbordamiento-horizontal">

  <!-- Barra de navegación superior -->
  <nav class="barra-navegacion">
    <div class="logo-marca">Ethereal Voyager</div>
    <div class="menu-navegacion">
      <a class="enlace-navegacion" href="#">Destinations</a>
      <a class="enlace-navegacion" href="#">Itineraries</a>
      <a class="enlace-navegacion" href="#">Journal</a>
      <a class="enlace-navegacion" href="#">About</a>
    </div>
    <button class="boton-iniciar-sesion-nav">Sign In</button>
  </nav>

  <!-- Contenedor principal hero -->
  <main class="contenedor-principal">

    <!-- Imagen de fondo con superposición radial -->
    <div class="capa-fondo">
      <img
        alt="Cadena de montañas neblinosas"
        class="imagen-fondo"
        src="https://lh3.googleusercontent.com/aida-public/AB6AXuCNZTu32FuYq9VDjnhGiQ4SARHSjYLvMic_dSZ8aEpaZTP-19L-_2MkRFFAfT8fnbVbodd9t0JsK1ki0PqTWp9v0_-U0qw5QvXMA-rmuF2MmEZoFBIvw-rGqExaVr2jo4VZM8ZNtpmNB65teSoGwAM4Aw7cl6gaVh38iKMTSdGpJ_doLBOFdNmJPaAjilD-14FUlh5NeevqTAUGv2PKRal1Woxr3PAVKrUdobdAa2G_BpF1qSfVotnKEbSB6Oq8XBsBOy-rkdpqF4QK"
      />
      <div class="superposicion-radial"></div>
    </div>

    <!-- Tarjeta de inicio de sesión centrada -->
    <section class="seccion-tarjeta">
      <div class="tarjeta-cristal">

        <!-- Encabezado de la tarjeta -->
        <header class="encabezado-tarjeta">
          <div class="contenedor-icono-marca">
            <span class="material-symbols-outlined icono-explorar" data-icon="travel_explore">travel_explore</span>
          </div>
          <h1 class="titulo-bienvenida">Bienvenido de nuevo</h1>
          <p class="subtitulo-bienvenida">Inicia sesión para planear tu próxima aventura</p>
        </header>

        <!-- Formulario de inicio de sesión -->
        <form class="formulario-login" id="formulario-login">

          <!-- Campo de correo electrónico -->
          <div class="grupo-campo">
            <label class="etiqueta-campo" for="correo">Correo electrónico</label>
            <div class="contenedor-input">
              <span class="material-symbols-outlined icono-input" data-icon="mail">mail</span>
              <input
                class="campo-input"
                id="correo"
                placeholder="viajero@ejemplo.com"
                type="email"
              />
            </div>
          </div>

          <!-- Campo de contraseña -->
          <div class="grupo-campo">
            <div class="fila-etiqueta-contrasena">
              <label class="etiqueta-campo" for="contrasena">Contraseña</label>
              <a class="enlace-olvide-contrasena" href="#">¿Olvidaste tu contraseña?</a>
            </div>
            <div class="contenedor-input">
              <span class="material-symbols-outlined icono-input" data-icon="lock">lock</span>
              <input
                class="campo-input"
                id="contrasena"
                placeholder="••••••••"
                type="password"
              />
            </div>
          </div>

          <!-- Botón principal de acción -->
          <button class="boton-iniciar-sesion" type="submit">Iniciar Sesión</button>
        </form>

        <!-- Inicio de sesión con redes sociales -->
        <div class="seccion-social">
          <div class="divisor-social">
            <div class="linea-divisor"></div>
            <span class="texto-divisor">O inicia sesión con</span>
            <div class="linea-divisor"></div>
          </div>
          <div class="botones-sociales">
            <button class="boton-social" id="boton-google">
              <img
                alt="Google"
                class="icono-social"
                src="https://lh3.googleusercontent.com/aida-public/AB6AXuAYhhe3IedKSHIHdlLecHbe9hebdgM4SAkcPAKBuuZQMhC8q_0xbZstlA6MPkelRyMAIlll6g2npoQ2P6g3qDll7-ybNlUgw4fNccPZ6KarXXzhfbPppSe2wph3n8dSsVWrNin1UtJlsAaIcFPwvZrq7CaOfZRxXxQ_mTbqKhnfYVtHY1uCspcdhhGTAfH5JyQFmxGpg7EbjoV8jxyonlHvvxcCLBcXARNeSCWh99I2QNDNENwKFmvVP2NpXm8vjx5QBsA9zEPNNJQI"
              />
            </button>
            <button class="boton-social boton-facebook" id="boton-facebook">
              <svg class="icono-facebook" viewBox="0 0 24 24">
                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
              </svg>
            </button>
          </div>
        </div>

        <!-- Pie de la tarjeta -->
        <footer class="pie-tarjeta">
          <p class="texto-registro">
            ¿No tienes una cuenta?
            <a class="enlace-registro" href="#">Regístrate aquí</a>
          </p>
        </footer>

      </div>
    </section>
  </main>

  <!-- Pie de página -->
  <footer class="pie-pagina">
    <div class="logo-pie">Ethereal Voyager</div>
    <div class="enlaces-pie">
      <a class="enlace-pie" href="#">Privacy Policy</a>
      <a class="enlace-pie" href="#">Terms of Service</a>
      <a class="enlace-pie" href="#">Support</a>
      <a class="enlace-pie" href="#">Contact</a>
    </div>
    <div class="copyright-pie">© 2024 Ethereal Voyager. Beyond Boundaries.</div>
  </footer>

  <script src="script.js"></script>
</body>
</html>