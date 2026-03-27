<!DOCTYPE html>
<html class="light" lang="es">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>Escapada A Termales | Santa Rosa de Cabal</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&;family=Manrope:wght@400;500;600;700&;display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="/estilos/style-detalles-turisticos.css"/>
</head>
<body class="cuerpo-principal">

  <!-- ============================================================
       NAVEGACIÓN
  ============================================================ -->
  <nav class="barra-nav">
    <div class="nav-logo">Santa Rosa de Cabal</div>
    <div class="nav-enlaces">
      <a class="nav-enlace nav-enlace--activo" href="#">Servicios</a>
      <a class="nav-enlace" href="#">Planes</a>
      <a class="nav-enlace" href="#">Gastronomía</a>
    </div>
    <button class="nav-boton-reservar">Reservar</button>
  </nav>

  <!-- ============================================================
       CONTENIDO PRINCIPAL
  ============================================================ -->
  <main class="contenido-principal">

    <!-- Hero -->
    <section class="seccion-hero">
      <div class="hero-fondo">
        <img
          class="hero-imagen"
          alt="Termales de Santa Rosa de Cabal"
          src="img/termales2.jpg"
        />
        <div class="hero-degradado"></div>
      </div>
      <div class="hero-contenido">
        <div class="hero-texto">
          <span class="hero-etiqueta">Experiencia Exclusiva</span>
          <h1 class="hero-titulo">Escapada a Termales</h1>
          <div class="hero-meta">
            <div class="hero-meta__item">
              <span class="material-symbols-outlined">location_on</span>
              <span>Santa Rosa de Cabal, Colombia</span>
            </div>
            <div class="hero-meta__item hero-meta__item--precio">
              <span class="material-symbols-outlined">payments</span>
              <span>Desde $2,499</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Cuerpo principal -->
    <section class="seccion-cuerpo">
      <div class="cuadricula-principal">

        <!-- Columna izquierda -->
        <div class="columna-contenido">

          <!-- Descripción -->
          <div class="tarjeta-cristal">
            <h2 class="titulo-card">Los Termales de Santa Rosa de Cabal</h2>
            <p class="descripcion-card">
              Sumérgete en un oasis de bienestar en los Termales de Santa Rosa de Cabal,
              un paraíso natural donde las aguas termales se entrelazan con la exuberante vegetación.
              Este destino emblemático ofrece una experiencia única de relajación y rejuvenecimiento,
              rodeado de paisajes impresionantes y una atmósfera serena. Disfruta de baños termales al aire libre,
              masajes revitalizantes y senderos naturales que te conectarán con la belleza pura de la naturaleza colombiana.  
            </p>
            <div class="cuadricula-destacados">
              <div class="destacado-item">
                <span class="material-symbols-outlined icono-filled">hotel</span>
                <span class="destacado-item__etiqueta">3 Noches</span>
              </div>
              <div class="destacado-item">
                <span class="material-symbols-outlined icono-filled">restaurant</span>
                <span class="destacado-item__etiqueta">Desayuno Incluido</span>
              </div>
              <div class="destacado-item">
                <span class="material-symbols-outlined icono-filled">spa</span>
                <span class="destacado-item__etiqueta">Sesión Spa</span>
              </div>
            </div>
          </div>

          <!-- Itinerario -->
          <div class="itinerario">
            <h3 class="itinerario__titulo">Tu Itinerario</h3>
            <div class="itinerario__lista">

              <div class="dia-item dia-item--cristal">
                <div class="dia-item__numero dia-item__numero--primario">01</div>
                <div class="dia-item__contenido">
                  <h4 class="dia-item__titulo">Llegada y Atardecer en Termales de Santa Rosa de Cabal</h4>
                  <p class="dia-item__descripcion">Bienvenida VIP y traslado privado a cabaña en Termales de Santa Rosa de Cabal</p>
                </div>
              </div>

              <div class="dia-item dia-item--contenedor">
                <div class="dia-item__numero dia-item__numero--secundario">02</div>
                <div class="dia-item__contenido">
                  <h4 class="dia-item__titulo">Exploración de senderos en reserva natural</h4>
                  <p class="dia-item__descripcion">Tour privado por los senderos de la reserva natural, con guía especializado.</p>
                </div>
              </div>

              <div class="dia-item dia-item--cristal">
                <div class="dia-item__numero dia-item__numero--primario">03</div>
                <div class="dia-item__contenido">
                  <h4 class="dia-item__titulo">Día de Relajación</h4>
                  <p class="dia-item__descripcion">Día completo en los Termales de Santa Rosa de Cabal, disfrutando de los baños termales y masajes revitalizantes en piscina de lodo.</p>
                </div>
              </div>

            </div>
          </div>

        </div>

        <!-- Columna derecha -->
        <aside class="columna-lateral">
          <div class="lateral-sticky">

            <!-- Tarjeta de reserva -->
            <div class="tarjeta-reserva">
              <h3 class="tarjeta-reserva__titulo">Reserva tu Escapada</h3>
              <p class="tarjeta-reserva__subtitulo">Disponibilidad limitada para la temporada.</p>
              <div class="reserva-campos">
                <div class="reserva-campo">
                  <span class="reserva-campo__etiqueta">Fecha</span>
                  <span class="reserva-campo__valor">15 Sep - 20 Sep</span>
                </div>
                <div class="reserva-campo">
                  <span class="reserva-campo__etiqueta">Personas</span>
                  <span class="reserva-campo__valor">2 Adultos</span>
                </div>
              </div>
              <button class="boton-reservar">Reservar Ahora</button>
              <p class="reserva-nota">Cancelación gratuita hasta 15 días antes.</p>
            </div>

            <!-- Galería -->
            <div class="tarjeta-cristal tarjeta-galeria">
              <h3 class="galeria__titulo">Galería Visual</h3>
              <div class="galeria__cuadricula">
                <div class="galeria__item">
                  <img
                    class="galeria__imagen"
                    alt="Iglesia con cúpula azul en Santorini"
                    src="img/termales-santa-rosa.jpg"
                  />
                </div>
                <div class="galeria__item">
                  <img
                    class="galeria__imagen"
                    alt="Piscina infinita con vistas al Mediterráneo"
                    src="img/termales.jpg"
                  />
                </div>
                <div class="galeria__item galeria__item--blur">
                  <img
                    class="galeria__imagen"
                    alt="Casas coloridas en pueblo costero mediterráneo"
                    src="img/santarosa.webp"
                  />
                </div>
                <div class="galeria__item galeria__item--mas">
                  <span class="galeria__mas-texto">+2</span>
                  <div class="galeria__mas-overlay"></div>
                </div>
              </div>
            </div>

            <!-- Qué incluye -->
            <div class="tarjeta-incluye">
              <h3 class="tarjeta-incluye__titulo">Qué incluye</h3>
              <ul class="lista-incluye">
                <li class="incluye-item">
                  <span class="material-symbols-outlined icono-filled icono-check">check_circle</span>
                  Vuelos ida y vuelta (Clase Business)
                </li>
                <li class="incluye-item">
                  <span class="material-symbols-outlined icono-filled icono-check">check_circle</span>
                  Alojamiento en Cabañas de Lujo con vistas panorámicas
                </li>
                <li class="incluye-item">
                  <span class="material-symbols-outlined icono-filled icono-check">check_circle</span>
                  Guía privado políglota
                </li>
                <li class="incluye-item">
                  <span class="material-symbols-outlined icono-filled icono-check">check_circle</span>
                  Seguro de viaje Premium
                </li>
              </ul>
            </div>

          </div>
        </aside>

      </div>
    </section>

  </main>

  <!-- ============================================================
       PIE DE PÁGINA
  ============================================================ -->
  <footer class="pie-pagina">
    <div class="pie-pagina__interior">
      <div class="pie-pagina__marca">
        <span class="pie-pagina__logo">Operador Turístico y Gastronomico | Santa Rosa de Cabal</span>
        <p class="pie-pagina__copyright">© 2026 Operador Turístico y Gastronomico | Santa Rosa de Cabal.</p>
      </div>
      <div class="pie-pagina__enlaces">
        <a class="pie-pagina__enlace" href="#">Privacidad</a>
        <a class="pie-pagina__enlace" href="#">Términos</a>
        <a class="pie-pagina__enlace" href="#">Instagram</a>
        <a class="pie-pagina__enlace" href="#">Facebook</a>
      </div>
    </div>
  </footer>

  <script src="/scripts/script-detalles-turisticos.js"></script>
</body>
</html>