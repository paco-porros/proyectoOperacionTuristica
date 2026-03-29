<?php
/**
 * gastronomia.php — Catálogo completo de ofertas gastronómicas.
 * Funciona tanto para visitantes anónimos como para usuarios autenticados.
 */
require_once __DIR__ . '/includes/session.php';

$logueado  = estaLogueado();
$usuario   = $logueado ? usuarioActual() : null;
$avatarSrc = null;
if ($logueado) {
  $avatarUrl = $usuario['avatar_url'] ?? null;
  $avatarSrc = $avatarUrl ?: 'https://ui-avatars.com/api/?name=' . urlencode($usuario['nombre']) . '&background=afedf0&color=054da4&size=40';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ofertas Gastronómicas | Operador Turístico y Gastronomico</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Manrope:wght@300;400;500;600;700&display=swap" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,300,0,0" />
  <link rel="stylesheet" href="/estilos/style-gastronomia.css" />
</head>

<body class="altura-minima columna-flexible sin-desbordamiento-horizontal">

  <!-- ═══════════════════════════════════════════
       NAVEGACIÓN — condicional logueado / anónimo
  ═══════════════════════════════════════════ -->
  <nav class="navegacion">
    <div class="navegacion__logo">
      <?php if ($logueado): ?>
        <a href="home.php">Operador Turístico y Gastronomico | Santa Rosa de Cabal</a>
      <?php else: ?>
        <a href="index.php">Operador Turístico y Gastronomico | Santa Rosa de Cabal</a>
      <?php endif; ?>
    </div>

    <div class="navegacion__enlaces">
      <?php if ($logueado): ?>
        <a class="navegacion__enlace" href="home.php">Inicio</a>
      <?php else: ?>
        <a class="navegacion__enlace" href="index.php#servicios">Servicios</a>
      <?php endif; ?>
      <a class="navegacion__enlace" href="planes.php">Planes Turísticos</a>
      <a class="navegacion__enlace navegacion__enlace--activo" href="gastronomia.php">Ofertas Gastronómicas</a>
      <?php if ($logueado && in_array($usuario['rol'], ['admin', 'editor'])): ?>
        <a class="navegacion__enlace" href="dashboard-administrador.php">Dashboard</a>
      <?php endif; ?>
    </div>

    <?php if ($logueado): ?>
      <!-- Usuario autenticado: saludo + avatar -->
      <div class="navegacion__zona-usuario">
        <span class="navegacion__saludo">
          Hola, <?= htmlspecialchars(explode(' ', $usuario['nombre'])[0]) ?>
        </span>
        <button class="navegacion__boton-notificaciones" id="boton-notificaciones" title="Notificaciones">
          <span class="material-symbols-outlined">notifications</span>
        </button>
        <div class="navegacion__avatar-envoltorio">
          <div class="navegacion__avatar" id="avatar-usuario" title="Mi cuenta">
            <img alt="Perfil de usuario" class="navegacion__avatar-imagen" src="<?= htmlspecialchars($avatarSrc) ?>" />
          </div>
          <div class="navegacion__dropdown" id="menu-avatar">
            <div class="navegacion__dropdown-encabezado">
              <?= htmlspecialchars($usuario['nombre']) ?>
            </div>
            <?php if (in_array($usuario['rol'], ['admin', 'editor'])): ?>
              <a href="dashboard-administrador.php" class="navegacion__dropdown-item">
                <span class="material-symbols-outlined">admin_panel_settings</span> Dashboard
              </a>
            <?php endif; ?>
            <button id="btn-logout" class="navegacion__dropdown-salir">
              <span class="material-symbols-outlined">logout</span> Cerrar Sesión
            </button>
          </div>
        </div>
      </div>
    <?php else: ?>
      <!-- Visitante anónimo: botón iniciar sesión -->
      <button class="navegacion__boton"><a href="login.php">Iniciar Sesión</a></button>
    <?php endif; ?>
  </nav>

  <!-- ═══════════════════════════════════════════
       CABECERA DE PÁGINA
  ═══════════════════════════════════════════ -->
  <header class="cabecera-gastro">
    <div class="cabecera-gastro__fondo">
      <img class="cabecera-gastro__imagen" src="img/fondoPortada.jpg" alt="Gastronomía Santa Rosa de Cabal" />
      <div class="cabecera-gastro__veladura"></div>
    </div>
    <div class="cabecera-gastro__contenido">
      <span class="seccion__supratitulo">Sabores de Santa Rosa</span>
      <h1 class="cabecera-gastro__titulo">Ofertas Gastronómicas</h1>
      <p class="cabecera-gastro__subtitulo">Explora los sabores auténticos y las experiencias culinarias de Santa Rosa de Cabal</p>
    </div>
  </header>

  <!-- ═══════════════════════════════════════════
       SECCIÓN PRINCIPAL — lista de ofertas
  ═══════════════════════════════════════════ -->
  <main class="gastro-main">
    <div class="gastro-main__interior">

      <!-- Encabezado con contador -->
      <div class="gastro-main__encabezado">
        <div>
          <span class="seccion__supratitulo">Selección culinaria</span>
          <h2 class="seccion__titulo">Todas las Ofertas</h2>
        </div>
        <span class="gastro-main__contador" id="contador-gastro">5 ofertas disponibles</span>
      </div>

      <!-- Cuadrícula de ofertas gastronómicas -->
      <div class="gastro-main__cuadricula">

        <!-- ── Oferta 1 ── -->
        <div class="tarjeta-gastro grupo-imagen">
          <div class="tarjeta-gastro__imagen-envoltorio">
            <img alt="Trucha al Ajillo" class="tarjeta-gastro__imagen"
              src="https://lh3.googleusercontent.com/aida-public/AB6AXuB711Yx-xQsszazQUBDqN1OqQYl4K8czjoq1Nka6XzAwDkrWX0IejB6EnX6bjk1X_vvcGNWiJIcqWzq4t5qYN1Opwg2Y8nMBxhqhzu_1R1ae4Q_8NhuJzyYxiUDDV8sQfFCgDo6LycuHewxR61A3BS_3oGw2yzY4JkuQeTM1brAg3oPmgaFMooN2Xcm4k2EjJs23RaG9pS7IWuUb7sc7WqOiWOGVE1VoaznK9VAT2w7bsR3Ycem5FSWtpZLVI9C8fuu1sUFmuMbahA"
              loading="lazy" />
            <span class="tarjeta-gastro__badge">Típico</span>
          </div>
          <div class="tarjeta-gastro__cuerpo panel-cristal borde-superior-cristal">
            <span class="tarjeta-gastro__restaurante">Restaurante El Ciervo Rojo</span>
            <h3 class="tarjeta-gastro__titulo">Trucha al Ajillo con Patacones</h3>
            <p class="tarjeta-gastro__descripcion">Trucha fresca de río preparada con mantequilla de ajo, hierbas finas y acompañada de patacones crujientes típicos de la región cafetera.</p>
            <div class="tarjeta-gastro__meta">
              <span class="tarjeta-gastro__precio">$35,000 COP</span>
              <span class="tarjeta-gastro__calificacion">
                <span class="material-symbols-outlined">star</span> 4.9
              </span>
            </div>
            <div class="acciones-tarjeta">
              <button class="boton-tarjeta boton-tarjeta--principal" onclick="window.location.href='detalles-gastronomicos.php?id=4'">
                Ver detalles <span class="material-symbols-outlined">arrow_forward</span>
              </button>
            </div>
          </div>
        </div>

        <!-- ── Oferta 2 ── -->
        <div class="tarjeta-gastro grupo-imagen">
          <div class="tarjeta-gastro__imagen-envoltorio">
            <img alt="Bandeja Paisa" class="tarjeta-gastro__imagen"
              src="https://lh3.googleusercontent.com/aida-public/AB6AXuAjFlGpXujdfQF-8b9gRFHXxsmyP1MibGWZWjioRuKfNBI60Jq9HU_is02Emlcpmhz96F3sT4SZ6csyzaqJjVATkr96Vcy6-hQNGPmllHVL8NdTGVvqyGi0aXpa8iXv71B--3uE6f3aGwcmkiOmI8KfjXtOhUr1D01QKnfxdjnggBPIpZXa0f_V0daOaDcp_9GSF5JMimVgcZJr7yp3zUhhbbuSpmtsKKVQzCIEbxJb5X9pmZGIrVH6-nBXTHG44GxabvfF-7AGN-Y"
              loading="lazy" />
            <span class="tarjeta-gastro__badge">Insignia</span>
          </div>
          <div class="tarjeta-gastro__cuerpo panel-cristal borde-superior-cristal">
            <span class="tarjeta-gastro__restaurante">La Fogata</span>
            <h3 class="tarjeta-gastro__titulo">Bandeja Paisa Premium</h3>
            <p class="tarjeta-gastro__descripcion">La bandeja más completa del Eje Cafetero: fríjoles, chicharrón, chorizo, morcilla, huevo, arroz, hogao y arepa. Una festividad en el plato.</p>
            <div class="tarjeta-gastro__meta">
              <span class="tarjeta-gastro__precio">$28,000 COP</span>
              <span class="tarjeta-gastro__calificacion">
                <span class="material-symbols-outlined">star</span> 4.8
              </span>
            </div>
            <div class="acciones-tarjeta">
              <button class="boton-tarjeta boton-tarjeta--principal" onclick="window.location.href='detalles-gastronomicos.php?id=2'">
                Ver detalles <span class="material-symbols-outlined">arrow_forward</span>
              </button>
            </div>
          </div>
        </div>

        <!-- ── Oferta 3 ── -->
        <div class="tarjeta-gastro grupo-imagen">
          <div class="tarjeta-gastro__imagen-envoltorio">
            <img alt="Tour Cafetero" class="tarjeta-gastro__imagen"
              src="https://lh3.googleusercontent.com/aida-public/AB6AXuBclMJTecXPdKK2ee7Nqm1hnNKeODuSBdQO7f22h0Ai2Pn_xKD50h1tBxHMeNHqf32ZSFp3rDm2oNfUDSOL2Hgc4wFFSfbxLq9CIuFvyY-xeM4rSf6U0NZLKeJRI1NPZ-kFbrdUd4EL8cxVpJNdSC5VxkG77TP8RygkTo83YL2HQvLN-40KKatTvjzX8hhCz49Wagw59CxqICS09LnrUdEU-pScwieXY9yQFcGOuhJY1ziFpj0UKuwv40lUL0u79wGO4IJtttXzodM"
              loading="lazy" />
            <span class="tarjeta-gastro__badge">Experiencia</span>
          </div>
          <div class="tarjeta-gastro__cuerpo panel-cristal borde-superior-cristal">
            <span class="tarjeta-gastro__restaurante">Finca El Descanso</span>
            <h3 class="tarjeta-gastro__titulo">Tour Cafetero & Cata Gourmet</h3>
            <p class="tarjeta-gastro__descripcion">Recorre los cafetales de Santa Rosa, aprende el proceso del café de origen y finaliza con una cata gourmet guiada por expertos baristas certificados.</p>
            <div class="tarjeta-gastro__meta">
              <span class="tarjeta-gastro__precio">$65,000 COP</span>
              <span class="tarjeta-gastro__calificacion">
                <span class="material-symbols-outlined">star</span> 5.0
              </span>
            </div>
            <div class="acciones-tarjeta">
              <button class="boton-tarjeta boton-tarjeta--principal" onclick="window.location.href='detalles-gastronomicos.php?id=6'">
                Ver detalles <span class="material-symbols-outlined">arrow_forward</span>
              </button>
            </div>
          </div>
        </div>

        <!-- ── Oferta 4 (nueva) ── -->
        <div class="tarjeta-gastro grupo-imagen">
          <div class="tarjeta-gastro__imagen-envoltorio">
            <img alt="Arepas de Choclo" class="tarjeta-gastro__imagen"
              src="https://lh3.googleusercontent.com/aida-public/AB6AXuAqrYiszyVO9b5FJvsR6QPJYsqdT1GRR_mYpjMlPFWjxafulqCduopzqvrPvj3cGmkgQeW_gjY7KmGaR5QZF2cJ5nICZ31UU4QvbFe3hQvpbR3fcNkkiv4PtLHKxPWQaj-m5KvRDvg0BuZM5v2Yx1AK6kk9MFOE-pR83n69Fo6EBiS5FK3SaeIGMpyP68nUPiP33pURX9LjDtg5JKbLoWzeTPEuwFkFJaSET8JWQ8qGwPGcqgJeNRD9fPPvAF0pxhu2cdEaD2v2fV72"
              loading="lazy" />
            <span class="tarjeta-gastro__badge">Tradicional</span>
          </div>
          <div class="tarjeta-gastro__cuerpo panel-cristal borde-superior-cristal">
            <span class="tarjeta-gastro__restaurante">Restaurante La Abuela Rosa</span>
            <h3 class="tarjeta-gastro__titulo">Arepas de Choclo con Quesillo</h3>
            <p class="tarjeta-gastro__descripcion">Arepas de maíz tierno artesanales, rellenas con quesillo fresco derretido y bañadas en mantequilla casera. El desayuno más emblemático del Eje Cafetero.</p>
            <div class="tarjeta-gastro__meta">
              <span class="tarjeta-gastro__precio">$12,000 COP</span>
              <span class="tarjeta-gastro__calificacion">
                <span class="material-symbols-outlined">star</span> 4.7
              </span>
            </div>
            <div class="acciones-tarjeta">
              <button class="boton-tarjeta boton-tarjeta--principal" onclick="window.location.href='detalles-gastronomicos.php?id=7'">
                Ver detalles <span class="material-symbols-outlined">arrow_forward</span>
              </button>
            </div>
          </div>
        </div>

        <!-- ── Oferta 5 (nueva) ── -->
        <div class="tarjeta-gastro grupo-imagen">
          <div class="tarjeta-gastro__imagen-envoltorio">
            <img alt="Lechona Risaraldense" class="tarjeta-gastro__imagen"
              src="https://lh3.googleusercontent.com/aida-public/AB6AXuALZtJ2omilYGHRKrG9x1nwXdgbTd-CLY_tL4_aKJOyNqaE4VqDD1vM-0EgYDbk1b_zQ_Bzwjaj2EL6Jug0TSFK9Q-JoDVcC2zHmYPXmPc1zpUCs9D1NvLosOig4fhqukT6z_XkcA1TJTQ3z1izAZLfA9vpyDhxsvALNQAMbO2erryl9DKcG8sCxPDIU7jo1FK55zo9d97q8LeZ2rLMly0ow4RZmUu4fjbaSEys11NOkjGrGZcKesu4mXcv8VQI1xP2rcQ5i7H1vsOt"
              loading="lazy" />
            <span class="tarjeta-gastro__badge">Regional</span>
          </div>
          <div class="tarjeta-gastro__cuerpo panel-cristal borde-superior-cristal">
            <span class="tarjeta-gastro__restaurante">El Rancho Típico</span>
            <h3 class="tarjeta-gastro__titulo">Lechona Risaraldense Festiva</h3>
            <p class="tarjeta-gastro__descripcion">Cerdo entero horneado por 12 horas relleno de arroz, arveja y especias regionales. Servido con chicharrón crocante y arepa de maíz pelado, ideal para celebraciones.</p>
            <div class="tarjeta-gastro__meta">
              <span class="tarjeta-gastro__precio">$42,000 COP</span>
              <span class="tarjeta-gastro__calificacion">
                <span class="material-symbols-outlined">star</span> 4.8
              </span>
            </div>
            <div class="acciones-tarjeta">
              <button class="boton-tarjeta boton-tarjeta--principal" onclick="window.location.href='detalles-gastronomicos.php?id=8'">
                Ver detalles <span class="material-symbols-outlined">arrow_forward</span>
              </button>
            </div>
          </div>
        </div>

      </div><!-- /gastro-main__cuadricula -->
    </div>
  </main>

  <!-- ═══════════════════════════════════════════
       PIE DE PÁGINA
  ═══════════════════════════════════════════ -->
  <footer class="pie">
    <div class="pie__cuadricula">
      <div>
        <span class="pie__logo">Operador Turístico y Gastronomico | Santa Rosa de Cabal</span>
        <p class="pie__eslogan">Redefiniendo el lujo en cada travesía. Tu horizonte, nuestra pasión.</p>
        <div class="pie__redes">
          <button class="pie__boton-red"><span class="material-symbols-outlined">public</span></button>
          <button class="pie__boton-red"><span class="material-symbols-outlined">share</span></button>
        </div>
      </div>

      <div>
        <h4 class="pie__columna-titulo">Enlaces rápidos</h4>
        <ul class="pie__lista">
          <?php if ($logueado): ?>
            <li><a href="home.php">Inicio</a></li>
          <?php else: ?>
            <li><a href="index.php">Inicio</a></li>
          <?php endif; ?>
          <li><a href="index.php#servicios">Nuestros Servicios</a></li>
          <li><a href="planes.php">Planes Turísticos</a></li>
          <li><a href="gastronomia.php">Ofertas Gastronómicas</a></li>
        </ul>
      </div>

      <div>
        <h4 class="pie__columna-titulo"><a href="nosotros.php">Sobre nosotros</a></h4>
        <ul class="pie__lista">
          <li><a href="nosotros.php#mision-vision">Misión y Visión</a></li>
          <li><a href="nosotros.php#equipo">Equipo Ejecutivo</a></li>
          <li><a href="nosotros.php#carreras">Carreras</a></li>
        </ul>
      </div>

      <div>
        <h4 class="pie__columna-titulo"><a href="servicios.php">Servicios</a></h4>
        <ul class="pie__lista">
          <li><a href="servicios.php#alojamiento">Alojamiento</a></li>
          <li><a href="servicios.php#transporte">Transporte</a></li>
          <li><a href="servicios.php#alimentacion">Alimentación</a></li>
          <li><a href="servicios.php#entretenimiento">Entretenimiento</a></li>
        </ul>
      </div>

      <div>
        <h4 class="pie__columna-titulo"><a href="legales.php">Legales y Ayuda</a></h4>
        <ul class="pie__lista">
          <li><a href="legales.php#terminos">Términos y Condiciones</a></li>
          <li><a href="legales.php#privacidad">Privacidad</a></li>
          <li><a href="legales.php#faqs">FAQs</a></li>
        </ul>
      </div>

      <div>
        <h4 class="pie__columna-titulo">Contacto</h4>
        <ul class="pie__lista-contacto">
          <li class="pie__contacto-item">
            <a href="mailto:info@srcabal.com">info@srcabal.com</a>
          </li>
          <li class="pie__contacto-item">
            <a href="mailto:talentohumano@srcabal.com">talentohumano@srcabal.com</a>
          </li>
          <li class="pie__contacto-item">+57 (606) 364-0000</li>
          <li class="pie__contacto-item">Santa Rosa de Cabal, Risaralda</li>
        </ul>
      </div>
    </div>
    <div class="pie__inferior">
      <p>© 2026 Operador Turístico y Gastronomico | Santa Rosa de Cabal
        <span class="pie__separador">|</span>
        <a href="legales.php#privacidad">Política de Privacidad</a>
        <span class="pie__separador">|</span>
        <a href="legales.php#terminos">Términos de Servicio</a>
      </p>
    </div>
  </footer>

  <script>
  /* ════════════════════════════════════════════════════
     GASTRONOMIA.PHP — menú avatar y logout (logueado)
  ════════════════════════════════════════════════════ */
  <?php if ($logueado): ?>
  document.getElementById('avatar-usuario').addEventListener('click', (e) => {
    e.stopPropagation();
    document.getElementById('menu-avatar').classList.toggle('navegacion__dropdown--visible');
  });
  document.addEventListener('click', () => {
    const menu = document.getElementById('menu-avatar');
    if (menu) menu.classList.remove('navegacion__dropdown--visible');
  });
  document.getElementById('btn-logout').addEventListener('click', async () => {
    await fetch('/ajax/logout.php', { method: 'POST' });
    window.location.href = '/index.php';
  });
  <?php endif; ?>
  </script>

</body>
</html>