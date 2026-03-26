<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>Operador Turístico y Gastronomico | Santa Rosa de Cabal</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Manrope:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
  <script src="tailwind.config.js"></script>
  <link rel="stylesheet" href="/estilos/style-home.css"/>
</head>
<body class="altura-minima columna-flexible sin-desbordamiento-horizontal">

  <!-- =========================================================
       BARRA DE NAVEGACIÓN SUPERIOR
       ========================================================= -->
  <nav class="barra-navegacion">
    <div class="contenedor-navegacion">

      <!-- Logo + enlaces -->
      <div class="grupo-izquierdo-nav">
        <span class="logo-nav">Operador Turístico y Gastronomico | Santa Rosa de Cabal</span>
        <div class="menu-enlaces-nav">
          <a class="enlace-nav enlace-nav--activo" href="#">Destinos</a>
          <a class="enlace-nav" href="#">Itinerarios</a>
          <a class="enlace-nav" href="#">Diario</a>
        </div>
      </div>

      <!-- Acciones de usuario -->
      <div class="grupo-derecho-nav">
        <button class="boton-notificaciones" id="boton-notificaciones">
          <span class="material-symbols-outlined icono-notificacion" data-icon="notifications">notifications</span>
        </button>
        <div class="avatar-usuario" id="avatar-usuario">
          <img
            alt="Perfil de usuario"
            class="imagen-avatar"
            src="https://lh3.googleusercontent.com/aida-public/AB6AXuDwYuCzf81MFx9gh0Maxm7gtLCXzDr-3dmHFO9I0CZFsg2llwHfrQa5veXfRJlDq7hvWnqFhBTesTtDEgdgP4wUp-wP1SsVvkiss6sHkg1o9AnZiGESeUaSTmBlc_CbH9E5gbGao0lYXwkHwVVl0ZGt2rHoxX4CkGqTO7XGD8R2g6Ozto1HVnGTNA4Ywq1BB9_TH9dyEThhtFL84YBIXHhcRFzfzJvCEgR1n8d9bCnCdSHfS7g5t26YbQtXgXgZGq0J56uG76DtEzk"
          />
        </div>
      </div>

    </div>
  </nav>

  <!-- =========================================================
       SECCIÓN HERO
       ========================================================= -->
  <section class="seccion-hero">

    <!-- Imagen de fondo + degradado -->
    <div class="capa-fondo-hero">
      <img
        alt="Paisaje de montaña"
        class="imagen-hero"
        src="img/fondoPortada.jpg"
      />
      <div class="degradado-hero"></div>
    </div>

    <!-- Contenido del hero -->
    <div class="contenido-hero">
      <h1 class="titulo-hero">Vive Esta Experiencia </h1>
      <p class="subtitulo-hero">
      Descubre a Santa Rosa donde la realidad y los sueños conectan.
      </p>

    </div>

  </section>

  <!-- =========================================================
       ITINERARIOS RECOMENDADOS (CUADRÍCULA BENTO)
       ========================================================= -->
  <section class="seccion-itinerarios">

    <!-- Encabezado de sección -->
    <div class="encabezado-seccion-itinerarios">
      <div>
        <span class="etiqueta-seccion">Curado para ti</span>
        <h2 class="titulo-seccion">Itinerarios Recomendados</h2>
      </div>
      <button class="boton-ver-todos" id="boton-ver-todos-itinerarios">
        Ver todos
        <span class="material-symbols-outlined icono-flecha-ver-todos" data-icon="arrow_forward">arrow_forward</span>
      </button>
    </div>

    <!-- Cuadrícula bento -->
    <div class="cuadricula-bento">

      <!-- Tarjeta principal destacada -->
      <div class="tarjeta-bento tarjeta-bento--principal grupo-imagen">
        <img
          alt="Escape Tropical"
          class="imagen-tarjeta"
          src="https://lh3.googleusercontent.com/aida-public/AB6AXuB711Yx-xQsszazQUBDqN1OqQYl4K8czjoq1Nka6XzAwDkrWX0IejB6EnX6bjk1X_vvcGNWiJIcqWzq4t5qYN1Opwg2Y8nMBxhqhzu_1R1ae4Q_8NhuJzyYxiUDDV8sQfFCgDo6LycuHewxR61A3BS_3oGw2yzY4JkuQeTM1brAg3oPmgaFMooN2Xcm4k2EjJs23RaG9pS7IWuUb7sc7WqOiWOGVE1VoaznK9VAT2w7bsR3Ycem5FSWtpZLVI9C8fuu1sUFmuMbahA"
        />
        <div class="sombra-gradiente-tarjeta"></div>
        <div class="info-tarjeta panel-cristal borde-superior-cristal">
          <div class="fila-info-tarjeta">
            <div>
              <span class="etiqueta-categoria">7 Días • Lujo</span>
              <h3 class="nombre-destino-principal">Retiro Azul en Maldivas</h3>
            </div>
            <span class="precio-destino">$4,200</span>
          </div>
          <div class="acciones-tarjeta">
            <button class="boton-tarjeta boton-tarjeta--blanco">
              Ver todos <span class="material-symbols-outlined">arrow_forward</span>
            </button>
            <button class="boton-tarjeta boton-tarjeta--cristal">Explorar</button>
          </div>
        </div>
      </div>

      <!-- Columna lateral bento -->
      <div class="columna-bento-lateral">

        <!-- Tarjeta superior lateral -->
        <div class="tarjeta-bento grupo-imagen">
          <img
            alt="Toscana"
            class="imagen-tarjeta"
            src="https://lh3.googleusercontent.com/aida-public/AB6AXuAjFlGpXujdfQF-8b9gRFHXxsmyP1MibGWZWjioRuKfNBI60Jq9HU_is02Emlcpmhz96F3sT4SZ6csyzaqJjVATkr96Vcy6-hQNGPmllHVL8NdTGVvqyGi0aXpa8iXv71B--3uE6f3aGwcmkiOmI8KfjXtOhUr1D01QKnfxdjnggBPIpZXa0f_V0daOaDcp_9GSF5JMimVgcZJr7yp3zUhhbbuSpmtsKKVQzCIEbxJb5X9pmZGIrVH6-nBXTHG44GxabvfF-7AGN-Y"
          />
          <div class="sombra-gradiente-tarjeta"></div>
          <div class="info-tarjeta panel-cristal borde-superior-cristal">
            <h3 class="nombre-destino">Escape a Viñedos de la Toscana</h3>
            <div class="acciones-tarjeta">
              <button class="boton-tarjeta boton-tarjeta--blanco boton-tarjeta--pequeno">
                Ver todos <span class="material-symbols-outlined">arrow_forward</span>
              </button>
              <button class="boton-tarjeta boton-tarjeta--cristal boton-tarjeta--pequeno">Agregar</button>
            </div>
          </div>
        </div>

        <!-- Tarjeta inferior lateral -->
        <div class="tarjeta-bento grupo-imagen">
          <img
            alt="Kioto"
            class="imagen-tarjeta"
            src="https://lh3.googleusercontent.com/aida-public/AB6AXuBclMJTecXPdKK2ee7Nqm1hnNKeODuSBdQO7f22h0Ai2Pn_xKD50h1tBxHMeNHqf32ZSFp3rDm2oNfUDSOL2Hgc4wFFSfbxLq9CIuFvyY-xeM4rSf6U0NZLKeJRI1NPZ-kFbrdUd4EL8cxVpJNdSC5VxkG77TP8RygkTo83YL2HQvLN-40KKatTvjzX8hhCz49Wagw59CxqICS09LnrUdEU-pScwieXY9yQFcGOuhJY1ziFpj0UKuwv40lUL0u79wGO4IJtttXzodM"
          />
          <div class="sombra-gradiente-tarjeta"></div>
          <div class="info-tarjeta panel-cristal borde-superior-cristal">
            <h3 class="nombre-destino">Tradiciones Zen de Kioto</h3>
            <div class="acciones-tarjeta">
              <button class="boton-tarjeta boton-tarjeta--blanco boton-tarjeta--pequeno">
                Ver todos <span class="material-symbols-outlined">arrow_forward</span>
              </button>
              <button class="boton-tarjeta boton-tarjeta--cristal boton-tarjeta--pequeno">Agregar</button>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- =========================================================
       OFERTAS GASTRONÓMICAS
       ========================================================= -->
  <section class="seccion-gastronomia">
    <div class="contenedor-gastronomia">

      <!-- Encabezado -->
      <div class="encabezado-seccion-gastronomia">
        <div>
          <span class="etiqueta-seccion">Sabores Locales</span>
          <h2 class="titulo-seccion">Ofertas Gastronómicas</h2>
        </div>
        <button class="boton-ver-todos" id="boton-ver-todos-gastronomia">
          Ver todas
          <span class="material-symbols-outlined icono-flecha-ver-todos" data-icon="arrow_forward">arrow_forward</span>
        </button>
      </div>

      <!-- Cuadrícula de tarjetas gastronómicas -->
      <div class="cuadricula-gastronomia">

        <!-- Tarjeta 1 — Trucha -->
        <div class="tarjeta-gastro grupo-imagen">
          <div class="tarjeta-gastro__imagen-envoltorio">
            <img
              alt="Trucha al Ajillo"
              class="tarjeta-gastro__imagen"
              src="https://lh3.googleusercontent.com/aida-public/AB6AXuB711Yx-xQsszazQUBDqN1OqQYl4K8czjoq1Nka6XzAwDkrWX0IejB6EnX6bjk1X_vvcGNWiJIcqWzq4t5qYN1Opwg2Y8nMBxhqhzu_1R1ae4Q_8NhuJzyYxiUDDV8sQfFCgDo6LycuHewxR61A3BS_3oGw2yzY4JkuQeTM1brAg3oPmgaFMooN2Xcm4k2EjJs23RaG9pS7IWuUb7sc7WqOiWOGVE1VoaznK9VAT2w7bsR3Ycem5FSWtpZLVI9C8fuu1sUFmuMbahA"
            />
            <span class="tarjeta-gastro__badge">Típico</span>
          </div>
          <div class="tarjeta-gastro__cuerpo panel-cristal borde-superior-cristal">
            <span class="tarjeta-gastro__restaurante">Restaurante El Manantial</span>
            <h3 class="tarjeta-gastro__titulo">Trucha al Ajillo con Patacones</h3>
            <div class="tarjeta-gastro__meta">
              <span class="tarjeta-gastro__precio">$35,000 COP</span>
              <span class="tarjeta-gastro__calificacion">
                <span class="material-symbols-outlined">star</span> 4.9
              </span>
            </div>
            <div class="acciones-tarjeta">
              <button class="boton-tarjeta boton-tarjeta--blanco boton-tarjeta--pequeno">
                Ver menú <span class="material-symbols-outlined">arrow_forward</span>
              </button>
              <button class="boton-tarjeta boton-tarjeta--cristal boton-tarjeta--pequeno">Reservar</button>
            </div>
          </div>
        </div>

        <!-- Tarjeta 2 — Bandeja Paisa -->
        <div class="tarjeta-gastro grupo-imagen">
          <div class="tarjeta-gastro__imagen-envoltorio">
            <img
              alt="Bandeja Paisa"
              class="tarjeta-gastro__imagen"
              src="https://lh3.googleusercontent.com/aida-public/AB6AXuAjFlGpXujdfQF-8b9gRFHXxsmyP1MibGWZWjioRuKfNBI60Jq9HU_is02Emlcpmhz96F3sT4SZ6csyzaqJjVATkr96Vcy6-hQNGPmllHVL8NdTGVvqyGi0aXpa8iXv71B--3uE6f3aGwcmkiOmI8KfjXtOhUr1D01QKnfxdjnggBPIpZXa0f_V0daOaDcp_9GSF5JMimVgcZJr7yp3zUhhbbuSpmtsKKVQzCIEbxJb5X9pmZGIrVH6-nBXTHG44GxabvfF-7AGN-Y"
            />
            <span class="tarjeta-gastro__badge">Insignia</span>
          </div>
          <div class="tarjeta-gastro__cuerpo panel-cristal borde-superior-cristal">
            <span class="tarjeta-gastro__restaurante">La Fonda Paisa</span>
            <h3 class="tarjeta-gastro__titulo">Bandeja Paisa Premium</h3>
            <div class="tarjeta-gastro__meta">
              <span class="tarjeta-gastro__precio">$28,000 COP</span>
              <span class="tarjeta-gastro__calificacion">
                <span class="material-symbols-outlined">star</span> 4.8
              </span>
            </div>
            <div class="acciones-tarjeta">
              <button class="boton-tarjeta boton-tarjeta--blanco boton-tarjeta--pequeno">
                Ver menú <span class="material-symbols-outlined">arrow_forward</span>
              </button>
              <button class="boton-tarjeta boton-tarjeta--cristal boton-tarjeta--pequeno">Reservar</button>
            </div>
          </div>
        </div>

        <!-- Tarjeta 3 — Tour Cafetero -->
        <div class="tarjeta-gastro grupo-imagen">
          <div class="tarjeta-gastro__imagen-envoltorio">
            <img
              alt="Tour Cafetero"
              class="tarjeta-gastro__imagen"
              src="https://lh3.googleusercontent.com/aida-public/AB6AXuBclMJTecXPdKK2ee7Nqm1hnNKeODuSBdQO7f22h0Ai2Pn_xKD50h1tBxHMeNHqf32ZSFp3rDm2oNfUDSOL2Hgc4wFFSfbxLq9CIuFvyY-xeM4rSf6U0NZLKeJRI1NPZ-kFbrdUd4EL8cxVpJNdSC5VxkG77TP8RygkTo83YL2HQvLN-40KKatTvjzX8hhCz49Wagw59CxqICS09LnrUdEU-pScwieXY9yQFcGOuhJY1ziFpj0UKuwv40lUL0u79wGO4IJtttXzodM"
            />
            <span class="tarjeta-gastro__badge">Experiencia</span>
          </div>
          <div class="tarjeta-gastro__cuerpo panel-cristal borde-superior-cristal">
            <span class="tarjeta-gastro__restaurante">Finca La Esperanza</span>
            <h3 class="tarjeta-gastro__titulo">Tour Cafetero & Cata Gourmet</h3>
            <div class="tarjeta-gastro__meta">
              <span class="tarjeta-gastro__precio">$65,000 COP</span>
              <span class="tarjeta-gastro__calificacion">
                <span class="material-symbols-outlined">star</span> 5.0
              </span>
            </div>
            <div class="acciones-tarjeta">
              <button class="boton-tarjeta boton-tarjeta--blanco boton-tarjeta--pequeno">
                Ver menú <span class="material-symbols-outlined">arrow_forward</span>
              </button>
              <button class="boton-tarjeta boton-tarjeta--cristal boton-tarjeta--pequeno">Reservar</button>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- =========================================================
       NUESTROS SERVICIOS PREMIUM
       ========================================================= -->
  <section class="seccion-servicios">
    <div class="contenedor-servicios">

      <!-- Encabezado -->
      <div class="encabezado-centrado">
        <span class="etiqueta-seccion etiqueta-seccion--primario">Posibilidades Infinitas</span>
        <h2 class="titulo-seccion-oscuro">Nuestros Servicios Premium</h2>
      </div>

      <!-- Cuadrícula de servicios -->
      <div class="cuadricula-servicios">

        <!-- Servicio 1: Vuelos Privados -->
        <div class="tarjeta-servicio panel-cristal grupo-servicio">
          <div class="icono-servicio-contenedor">
            <span class="material-symbols-outlined icono-servicio" data-icon="flight_takeoff">flight_takeoff</span>
          </div>
          <h3 class="titulo-servicio">Vuelos Privados</h3>
          <p class="descripcion-servicio">Viaja en absoluta privacidad con nuestra flota curada de jets de lujo y vuelos chárter privados.</p>
        </div>

        <!-- Servicio 2: Estancias de Élite -->
        <div class="tarjeta-servicio panel-cristal grupo-servicio">
          <div class="icono-servicio-contenedor">
            <span class="material-symbols-outlined icono-servicio" data-icon="hotel">hotel</span>
          </div>
          <h3 class="titulo-servicio">Estancias de Élite</h3>
          <p class="descripcion-servicio">Acceso exclusivo a más de 500 villas boutique y hoteles de ultra-lujo en todo el mundo.</p>
        </div>

        <!-- Servicio 3: Gastronomía Curada -->
        <div class="tarjeta-servicio panel-cristal grupo-servicio">
          <div class="icono-servicio-contenedor">
            <span class="material-symbols-outlined icono-servicio" data-icon="restaurant">restaurant</span>
          </div>
          <h3 class="titulo-servicio">Gastronomía Curada</h3>
          <p class="descripcion-servicio">Reservas aseguradas en restaurantes con estrellas Michelin y joyas locales ocultas.</p>
        </div>

        <!-- Servicio 4: Conserje 24/7 -->
        <div class="tarjeta-servicio panel-cristal grupo-servicio">
          <div class="icono-servicio-contenedor">
            <span class="material-symbols-outlined icono-servicio" data-icon="concierge">concierge</span>
          </div>
          <h3 class="titulo-servicio">Conserje 24/7</h3>
          <p class="descripcion-servicio">Un guardián de viajes dedicado disponible las 24 horas para perfeccionar su viaje.</p>
        </div>

      </div>
    </div>
  </section>

  <!-- =========================================================
       SOCIOS DESTACADOS
       ========================================================= -->
  <section class="seccion-socios">
    <div class="contenedor-socios">
      <div class="encabezado-socios">
        <h2 class="titulo-seccion-oscuro">Socios Destacados</h2>
        <p class="subtitulo-socios">Las mentes detrás de nuestras experiencias de clase mundial.</p>
      </div>
      <div class="lista-socios" id="lista-socios">

        <div class="item-socio">
          <span class="material-symbols-outlined icono-socio" data-icon="diamond">diamond</span>
          <span class="nombre-socio">AURORA LUX</span>
        </div>

        <div class="item-socio">
          <span class="material-symbols-outlined icono-socio" data-icon="landscape">landscape</span>
          <span class="nombre-socio">TERRA EXPLORE</span>
        </div>

        <div class="item-socio">
          <span class="material-symbols-outlined icono-socio" data-icon="anchor">anchor</span>
          <span class="nombre-socio">OCEANIC CO.</span>
        </div>

        <div class="item-socio">
          <span class="material-symbols-outlined icono-socio" data-icon="castle">castle</span>
          <span class="nombre-socio">HERITAGE STAYS</span>
        </div>

      </div>
    </div>
  </section>

  <!-- ═══════════════════════════════════════════
       PIE DE PÁGINA
  ═══════════════════════════════════════════ -->
  <footer class="pie">

    <div class="pie__cuadricula">

      <!-- Marca -->
      <div>
        <span class="pie__logo">Operador Turístico y Gastronomico | Santa Rosa de Cabal</span>
        <p class="pie__eslogan">Redefiniendo el lujo en cada travesía. Tu horizonte, nuestra pasión.</p>
        <div class="pie__redes">
          <button class="pie__boton-red">
            <span class="material-symbols-outlined">public</span>
          </button>
          <button class="pie__boton-red">
            <span class="material-symbols-outlined">share</span>
          </button>
        </div>
      </div>

      <!-- Sobre nosotros -->
      <div>
        <h4 class="pie__columna-titulo">Sobre nosotros</h4>
        <ul class="pie__lista">
          <li><a href="#">Misión y Visión</a></li>
          <li><a href="#">Equipo Ejecutivo</a></li>
          <li><a href="#">Carreras</a></li>
        </ul>
      </div>

      <!-- Enlaces rápidos -->
      <div>
        <h4 class="pie__columna-titulo">Enlaces rápidos</h4>
        <ul class="pie__lista">
          <li><a href="#">Términos y Condiciones</a></li>
          <li><a href="#">Privacidad</a></li>
          <li><a href="#">FAQs</a></li>
        </ul>
      </div>

      <!-- Contacto -->
      <div>
        <h4 class="pie__columna-titulo">Contacto</h4>
        <ul class="pie__lista-contacto">
          <li class="pie__contacto-item">
            <span class="material-symbols-outlined">mail</span>
            <a href="/cdn-cgi/l/email-protection#d3bca3b6a1b2b7bca1a7a6a1baa0a7bab0bcaab4b2a0a7a1bcbdbcbebab0bc93b0bca1a1b6bcfdb0bcbe"><span class="__cf_email__" data-cfemail="e28d92879083868d909697908b91968b818d9b85839196908d8c8d8f8b818da2818d9090878dcc818d8f">[email&#160;protected]</span></a>
          </li>
          <li class="pie__contacto-item">
            <span class="material-symbols-outlined">call</span>
            +1 (555) 123-4567
          </li>
        </ul>
      </div>

    </div>

    <div class="pie__inferior">
      <p>© 2026 Operador Turístico y Gastronomico | Santa Rosa de Cabal
        <span class="pie__separador">|</span>
        <a href="#">Política de Privacidad</a>
        <span class="pie__separador">|</span>
        <a href="#">Términos de Servicio</a>
      </p>
    </div>

  </footer>
  <script src="/scripts/script-home.js"></script>
</body>
</html>