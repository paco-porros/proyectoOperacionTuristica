```php
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
  <link rel="stylesheet" href="./estilos/style-registro.css"/>

  <style>
    .contenedor-input-icono {
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

<body class="fuente-cuerpo texto-superficie fondo-principal altura-minima columna-flexible relativo sin-desbordamiento-horizontal">

  <div class="capa-fondo-fija">
    <img
      class="imagen-fondo"
      alt="Vista panorámica"
      src="./img/santarosa.webp"
    />
    <div class="superposicion-fondo"></div>
  </div>

  <main class="contenedor-principal">
    <div class="tarjeta-registro">

      <div class="encabezado-registro">
        <div class="contenedor-marca">
          <span class="nombre-marca">Operador Turístico y Gastronomico | Santa Rosa de Cabal</span>
        </div>
        <h1 class="titulo-registro">Crea tu cuenta</h1>
        <p class="subtitulo-registro">Únete a la comunidad de viajeros más exclusiva</p>
      </div>

      <form class="formulario-registro" id="formulario-registro">

        <!-- Nombre -->
        <div class="grupo-campo">
          <label class="etiqueta-campo" for="nombre-completo">Nombre Completo</label>
          <div class="contenedor-input-icono">
            <span class="material-symbols-outlined icono-campo">person</span>
            <input class="campo-cristal" id="nombre-completo" placeholder="Juan Pérez" type="text"/>
          </div>
        </div>

        <!-- Correo -->
        <div class="grupo-campo">
          <label class="etiqueta-campo" for="correo">Correo Electrónico</label>
          <div class="contenedor-input-icono">
            <span class="material-symbols-outlined icono-campo">mail</span>
            <input class="campo-cristal" id="correo" placeholder="tu@email.com" type="email"/>
          </div>
        </div>

        <!-- Contraseñas -->
        <div class="cuadricula-contrasenas">

          <!-- Contraseña -->
          <div class="grupo-campo">
            <label class="etiqueta-campo" for="contrasena">Contraseña</label>
            <div class="contenedor-input-icono">
              <span class="material-symbols-outlined icono-campo">lock</span>
              <input class="campo-cristal" id="contrasena" placeholder="••••••••" type="password"/>

              <!-- 👁️ -->
              <span class="material-symbols-outlined icono-ojo" id="togglePassword">
                visibility
              </span>
            </div>
          </div>

          <!-- Confirmar -->
          <div class="grupo-campo">
            <label class="etiqueta-campo" for="confirmar-contrasena">Confirmar Contraseña</label>
            <div class="contenedor-input-icono">
              <span class="material-symbols-outlined icono-campo">verified_user</span>
              <input class="campo-cristal" id="confirmar-contrasena" placeholder="••••••••" type="password"/>

              <!-- 👁️ -->
              <span class="material-symbols-outlined icono-ojo" id="togglePassword2">
                visibility
              </span>
            </div>
          </div>

        </div>

        <div class="fila-terminos">
          <input class="checkbox-terminos" id="terminos" type="checkbox"/>
          <label class="etiqueta-terminos" for="terminos">
            Acepto los
            <a class="enlace-terminos" href="#">Términos de Servicio</a>
            y la
            <a class="enlace-terminos" href="#">Política de Privacidad</a>.
          </label>
        </div>

        <button class="boton-registrarse" id="boton-registrarse" type="submit">
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

  <footer class="pie-pagina-registro">
    <span class="copyright-registro">© 2024 Operador Turístico y Gastronomico</span>
  </footer>

  <!-- SCRIPT -->
  <script src="./scripts/script.js">
   
  </script>

</body>
</html>
```
