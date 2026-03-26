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
  <link rel="stylesheet" href="/estilos/style.css"/>

  <!-- ✅ Estilo para el ojo -->
  <style>
    .contenedor-input {
      position: relative;
    }
    .icono-ojo {
      position: absolute;
      right: 10px;
      cursor: pointer;
      color: #aaa;
    }
  </style>
</head>
<body class="fondo-principal fuente-cuerpo texto-superficie seleccion-primaria sin-desbordamiento-horizontal">

  <!-- Barra de navegación superior -->
  <nav class="barra-navegacion">
    <div class="logo-marca">Operador Turístico y Gastronomico | Santa Rosa de Cabal</div>
  </nav>

  <!-- Contenedor principal hero -->
  <main class="contenedor-principal">

    <!-- Imagen de fondo -->
    <div class="capa-fondo">
      <img
        alt="Cadena de montañas neblinosas"
        class="imagen-fondo"
        src="https://lh3.googleusercontent.com/aida-public/AB6AXuCNZTu32FuYq9VDjnhGiQ4SARHSjYLvMic_dSZ8aEpaZTP-19L-_2MkRFFAfT8fnbVbodd9t0JsK1ki0PqTWp9v0_-U0qw5QvXMA-rmuF2MmEZoFBIvw-rGqExaVr2jo4VZM8ZNtpmNB65teSoGwAM4Aw7cl6gaVh38iKMTSdGpJ_doLBOFdNmJPaAjilD-14FUlh5NeevqTAUGv2PKRal1Woxr3PAVKrUdobdAa2G_BpF1qSfVotnKEbSB6Oq8XBsBOy-rkdpqF4QK"
      />
      <div class="superposicion-radial"></div>
    </div>

    <!-- Tarjeta -->
    <section class="seccion-tarjeta">
      <div class="tarjeta-cristal">

        <header class="encabezado-tarjeta">
          <div class="contenedor-icono-marca">
            <span class="material-symbols-outlined icono-explorar">travel_explore</span>
          </div>
          <h1 class="titulo-bienvenida">Bienvenido de nuevo</h1>
          <p class="subtitulo-bienvenida">Inicia sesión para planear tu próxima aventura</p>
        </header>

        <form class="formulario-login" id="formulario-login">

          <!-- Correo -->
          <div class="grupo-campo">
            <label class="etiqueta-campo" for="correo">Correo electrónico</label>
            <div class="contenedor-input">
              <span class="material-symbols-outlined icono-input">mail</span>
              <input
                class="campo-input"
                id="correo"
                placeholder="viajero@ejemplo.com"
                type="email"
              />
            </div>
          </div>

          <!-- Contraseña -->
          <div class="grupo-campo">
            <div class="fila-etiqueta-contrasena">
              <label class="etiqueta-campo" for="contrasena">Contraseña</label>
              <a class="enlace-olvide-contrasena" href="#">¿Olvidaste tu contraseña?</a>
            </div>
            <div class="contenedor-input">
              <span class="material-symbols-outlined icono-input">lock</span>
              <input
                class="campo-input"
                id="contrasena"
                placeholder="••••••••"
                type="password"
              />

              <!-- 👁️ BOTÓN VER CONTRASEÑA -->
              <span 
                class="material-symbols-outlined icono-ojo" 
                id="togglePassword"
              >visibility</span>
            </div>
          </div>

          <button class="boton-iniciar-sesion" type="submit">Iniciar Sesión</button>
        </form>

        <footer class="pie-tarjeta">
          <p class="texto-registro">
            ¿No tienes una cuenta?
            <a class="enlace-registro" href="registro.php">Regístrate aquí</a>
          </p>
        </footer>

          

      </div>
    </section>
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

    
<script src="/scripts/script.js"></script>
</body>
</html>