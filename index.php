<?php
/**
 * index.php — Página principal con planes turísticos desde la BD
 */
require_once __DIR__ . '/includes/session.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Operador Turístico y Gastronomico</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&family=DM+Sans:wght@300;400;500&display=swap" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,300,0,0" />
  <link rel="stylesheet" href="/estilos/style.css" />
</head>

<body>

  <!-- ═══════════════════════════════════════════
       NAVEGACIÓN
  ═══════════════════════════════════════════ -->
  <nav class="navegacion">
    <div class="navegacion__logo">
      <a href="#">Operador Turístico y Gastronomico | Santa Rosa de Cabal</a>
    </div>
    <div class="navegacion__enlaces">
      <a class="navegacion__enlace" href="#servicios">Servicios</a>
      <a class="navegacion__enlace" href="#planes">Planes Turísticos</a>
      <a class="navegacion__enlace" href="#gastronomia">Ofertas Gastronómicas</a>
    </div>
    <?php if (estaLogueado()): $u = usuarioActual(); ?>
      <button class="navegacion__boton" id="btn-cerrar-sesion">
        Hola, <?= htmlspecialchars($u['nombre']) ?> · Salir
      </button>
    <?php else: ?>
      <button class="navegacion__boton"><a href="login.php">Iniciar Sesión</a></button>
    <?php endif; ?>
  </nav>

  <!-- ═══════════════════════════════════════════
       PORTADA (HERO)
  ═══════════════════════════════════════════ -->
  <header class="portada">
    <div class="portada__fondo">
      <img class="portada__imagen" src="img/fondoPortada.jpg" alt="Paisaje montañoso impresionante" />
      <div class="portada__veladura portada-gradiente"></div>
    </div>
    <div class="portada__contenido">
      <h1 class="portada__titulo">Vive Esta Maravillosa Experiencia</h1>
      <p class="portada__subtitulo">Planes Turísticos y Gastronómicos Personalizados Para Ti</p>
    </div>
  </header>

  <!-- ═══════════════════════════════════════════
       SERVICIOS DESTACADOS
  ═══════════════════════════════════════════ -->
  <section id="servicios" class="servicios">
    <div class="servicios__encabezado">
      <div>
        <span class="seccion__supratitulo">Mas de Santa Rosa</span>
        <h2 class="seccion__titulo"><a href="servicios.php">Nuestros Servicios <span class="material-symbols-outlined">arrow_forward</span></a></h2>
      </div>
    </div>
    <div class="servicios__cuadricula">
      <div class="tarjeta-servicio panel-vidrio borde-vidrio">
        <div class="tarjeta-servicio__icono-envoltorio">
          <span class="material-symbols-outlined tarjeta-servicio__icono">hotel</span>
        </div>
        <h3 class="tarjeta-servicio__titulo">Alojamiento</h3>
        <p class="tarjeta-servicio__descripcion">Desde hoteles boutique hasta villas privadas de ensueño.</p>
        <button class="tarjeta-servicio__enlace"><a href="servicios.php#alojamiento">Ver más </a><span class="material-symbols-outlined">arrow_forward</span></button>
      </div>
      <div class="tarjeta-servicio panel-vidrio borde-vidrio">
        <div class="tarjeta-servicio__icono-envoltorio">
          <span class="material-symbols-outlined tarjeta-servicio__icono">directions_car</span>
        </div>
        <h3 class="tarjeta-servicio__titulo">Transporte</h3>
        <p class="tarjeta-servicio__descripcion">Traslados ejecutivos y alquiler de vehículos de alta gama.</p>
        <button class="tarjeta-servicio__enlace"><a href="servicios.php#transporte">Ver más </a><span class="material-symbols-outlined">arrow_forward</span></button>
      </div>
      <div class="tarjeta-servicio panel-vidrio borde-vidrio">
        <div class="tarjeta-servicio__icono-envoltorio">
          <span class="material-symbols-outlined tarjeta-servicio__icono">restaurant</span>
        </div>
        <h3 class="tarjeta-servicio__titulo">Alimentación</h3>
        <p class="tarjeta-servicio__descripcion">Experiencias gastronómicas locales y cenas gourmet privadas.</p>
        <button class="tarjeta-servicio__enlace"><a href="servicios.php#alimentacion">Ver más</a><span class="material-symbols-outlined">arrow_forward</span></button>
      </div>
      <div class="tarjeta-servicio panel-vidrio borde-vidrio">
        <div class="tarjeta-servicio__icono-envoltorio">
          <span class="material-symbols-outlined tarjeta-servicio__icono">local_activity</span>
        </div>
        <h3 class="tarjeta-servicio__titulo">Entretenimiento</h3>
        <p class="tarjeta-servicio__descripcion">Tours exclusivos, deportes de aventura y eventos culturales.</p>
        <button class="tarjeta-servicio__enlace"><a href="servicios.php#entretenimiento">Ver más </a><span class="material-symbols-outlined">arrow_forward</span></button>
      </div>
    </div>
  </section>

  <!-- ═══════════════════════════════════════════
       PLANES RECOMENDADOS — cargados vía AJAX desde la BD
  ═══════════════════════════════════════════ -->
  <section id="planes" class="planes">
    <div class="planes__interior">
      <div class="planes__encabezado">
        <div>
          <span class="seccion__supratitulo">Selección premium</span>
          <h2 class="seccion__titulo">Planes Recomendados</h2>
        </div>
        <button class="planes__boton-todos" id="btn-ver-todos-planes">Ver todos los planes</button>
      </div>
      <!-- Contenedor dinámico -->
      <div class="planes__lista" id="lista-planes">
        <!-- Skeleton mientras carga -->
        <div class="tarjeta-plan panel-vidrio borde-vidrio" style="opacity:.4; pointer-events:none;">
          <div class="tarjeta-plan__imagen-envoltorio" style="background:#d5b9b2;"></div>
          <div class="tarjeta-plan__cuerpo">
            <div style="height:1rem; background:#d5b9b2; border-radius:.5rem; margin-bottom:1rem;"></div>
            <div style="height:.75rem; background:#d5b9b2; border-radius:.5rem; width:60%;"></div>
          </div>
        </div>
        <div class="tarjeta-plan panel-vidrio borde-vidrio" style="opacity:.25; pointer-events:none;">
          <div class="tarjeta-plan__imagen-envoltorio" style="background:#d5b9b2;"></div>
          <div class="tarjeta-plan__cuerpo">
            <div style="height:1rem; background:#d5b9b2; border-radius:.5rem; margin-bottom:1rem;"></div>
            <div style="height:.75rem; background:#d5b9b2; border-radius:.5rem; width:60%;"></div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ═══════════════════════════════════════════
       OFERTAS GASTRONÓMICAS
  ═══════════════════════════════════════════ -->
  <section id="gastronomia" class="gastronomia">
    <div class="gastronomia__interior">
      <div class="gastronomia__encabezado">
        <div>
          <span class="seccion__supratitulo">Sabores de Santa Rosa</span>
          <h2 class="seccion__titulo">Ofertas Gastronómicas</h2>
        </div>
        <button class="gastronomia__boton-todos" id="boton-ver-todos-gastronomia">Ver todas las ofertas</button>
      </div>
      <div class="gastronomia__lista">
        <div class="tarjeta-gastronomia panel-vidrio borde-vidrio">
          <div class="tarjeta-gastronomia__imagen-envoltorio">
            <img class="tarjeta-gastronomia__imagen" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAqrYiszyVO9b5FJvsR6QPJYsqdT1GRR_mYpjMlPFWjxafulqCduopzqvrPvj3cGmkgQeW_gjY7KmGaR5QZF2cJ5nICZ31UU4QvbFe3hQvpbR3fcNkkiv4PtLHKxPWQaj-m5KvRDvg0BuZM5v2Yx1AK6kk9MFOE-pR83n69Fo6EBiS5FK3SaeIGMpyP68nUPiP33pURX9LjDtg5JKbLoWzeTPEuwFkFJaSET8JWQ8qGwPGcqgJeNRD9fPPvAF0pxhu2cdEaD2v2fV72" alt="Trucha al ajillo"/>
            <span class="tarjeta-gastronomia__badge">Típico</span>
          </div>
          <div class="tarjeta-gastronomia__cuerpo">
            <div>
              <div class="tarjeta-gastronomia__fila-superior">
                <h3 class="tarjeta-gastronomia__titulo">Trucha al Ajillo con Patacones</h3>
                <span class="tarjeta-gastronomia__etiqueta">Plato fuerte</span>
              </div>
              <p class="tarjeta-gastronomia__descripcion">Trucha fresca de los ríos de Santa Rosa, preparada con mantequilla de ajo, hierbas aromáticas locales y acompañada de patacones artesanales.</p>
              <div class="tarjeta-gastronomia__meta">
                <span class="tarjeta-gastronomia__meta-item"><span class="material-symbols-outlined">restaurant</span> Restaurante El Manantial</span>
                <span class="tarjeta-gastronomia__meta-item"><span class="material-symbols-outlined">star</span> 4.9</span>
              </div>
            </div>
            <div class="tarjeta-gastronomia__pie">
              <div>
                <span class="tarjeta-gastronomia__precio-desde">Desde</span>
                <span class="tarjeta-gastronomia__precio">$35,000 <span class="tarjeta-gastronomia__precio-unidad">COP / pers</span></span>
              </div>
              <button class="tarjeta-gastronomia__boton" onclick="window.location.href='detalles-gastronomicos.php?id=4'">
                Ver detalles <span class="material-symbols-outlined">visibility</span>
              </button>
            </div>
          </div>
        </div>
        <div class="tarjeta-gastronomia panel-vidrio borde-vidrio">
          <div class="tarjeta-gastronomia__imagen-envoltorio">
            <img class="tarjeta-gastronomia__imagen" src="https://lh3.googleusercontent.com/aida-public/AB6AXuALZtJ2omilYGHRKrG9x1nwXdgbTd-CLY_tL4_aKJOyNqaE4VqDD1vM-0EgYDbk1b_zQ_Bzwjaj2EL6Jug0TSFK9Q-JoDVcC2zHmYPXmPc1zpUCs9D1NvLosOig4fhqukT6z_XkcA1TJTQ3z1izAZLfA9vpyDhxsvALNQAMbO2erryl9DKcG8sCxPDIU7jo1FK55zo9d97q8LeZ2rLMly0ow4RZmUu4fjbaSEys11NOkjGrGZcKesu4mXcv8VQI1xP2rcQ5i7H1vsOt" alt="Bandeja Paisa"/>
            <span class="tarjeta-gastronomia__badge">Insignia</span>
          </div>
          <div class="tarjeta-gastronomia__cuerpo">
            <div>
              <div class="tarjeta-gastronomia__fila-superior">
                <h3 class="tarjeta-gastronomia__titulo">Bandeja Paisa Premium</h3>
                <span class="tarjeta-gastronomia__etiqueta">Tradición</span>
              </div>
              <p class="tarjeta-gastronomia__descripcion">La reina de la gastronomía antioqueña en su versión más completa: frijoles, chicharrón, chorizo artesanal, huevo, arroz, aguacate y arepa de maíz.</p>
              <div class="tarjeta-gastronomia__meta">
                <span class="tarjeta-gastronomia__meta-item"><span class="material-symbols-outlined">restaurant</span> La Fonda Paisa</span>
                <span class="tarjeta-gastronomia__meta-item"><span class="material-symbols-outlined">star</span> 4.8</span>
              </div>
            </div>
            <div class="tarjeta-gastronomia__pie">
              <div>
                <span class="tarjeta-gastronomia__precio-desde">Desde</span>
                <span class="tarjeta-gastronomia__precio">$28,000 <span class="tarjeta-gastronomia__precio-unidad">COP / pers</span></span>
              </div>
              <button class="tarjeta-gastronomia__boton" onclick="window.location.href='detalles-gastronomicos.php?id=2'">
                Ver detalles <span class="material-symbols-outlined">visibility</span>
              </button>
            </div>
          </div>
        </div>
        <div class="tarjeta-gastronomia panel-vidrio borde-vidrio">
          <div class="tarjeta-gastronomia__imagen-envoltorio">
            <img class="tarjeta-gastronomia__imagen" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCGZj183QMPNxArCGjvbpRWb8nIGbfTxzu7KKzsUUwoup4opovL_2ax7638xLfXWsTVJ1v0jzegSzjCRDmhpBuss3YW7lxJc7zEbj7DB-JGTHSRu24vIj4BFSU3WGWajncHqKD4tDTAe35nhNLhcqzJlbtmsBL8xsEyZpj1x8ZGtQeKs0da94l9vSQaoV_fGaAYWq2uPje0-b20Ubur2D8KewvzKCXDnLgnFTPtyV1wnH9ZvVnLXKAA8g6BO7Ol7BbeE0q0uXo3eBwO" alt="Tour Cafetero"/>
            <span class="tarjeta-gastronomia__badge">Experiencia</span>
          </div>
          <div class="tarjeta-gastronomia__cuerpo">
            <div>
              <div class="tarjeta-gastronomia__fila-superior">
                <h3 class="tarjeta-gastronomia__titulo">Tour Cafetero & Cata Gourmet</h3>
                <span class="tarjeta-gastronomia__etiqueta">Experiencia</span>
              </div>
              <p class="tarjeta-gastronomia__descripcion">Recorre los cafetales de la región, conoce el proceso del café de origen y disfruta una cata guiada con maridaje de postres artesanales locales.</p>
              <div class="tarjeta-gastronomia__meta">
                <span class="tarjeta-gastronomia__meta-item"><span class="material-symbols-outlined">schedule</span> 3 horas</span>
                <span class="tarjeta-gastronomia__meta-item"><span class="material-symbols-outlined">star</span> 5.0</span>
              </div>
            </div>
            <div class="tarjeta-gastronomia__pie">
              <div>
                <span class="tarjeta-gastronomia__precio-desde">Desde</span>
                <span class="tarjeta-gastronomia__precio">$65,000 <span class="tarjeta-gastronomia__precio-unidad">COP / pers</span></span>
              </div>
              <button class="tarjeta-gastronomia__boton" onclick="window.location.href='detalles-gastronomicos.php?id=6'">
                Ver detalles <span class="material-symbols-outlined">visibility</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  
  <!-- PIE DE PÁGINA -->
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
        <h4 class="pie__columna-titulo"><a href="nosotros.php">Sobre nosotros</a></h4>
        <ul class="pie__lista">
          <li><a href="nosotros.php#mision-vision">Misión y Visión</a></li>
          <li><a href="nosotros.php#equipo">Equipo Ejecutivo</a></li>
          <li><a href="nosotros.php#carreras">Carreras</a></li>
        </ul>
      </div>

      <div>
        <h4 class="pie__columna-titulo">Enlaces rápidos</h4>
        <ul class="pie__lista">
          <li><a href="index.php">Inicio</a></li>
          <li><a href="#servicios">Nuestros Servicios</a></li>
          <li><a href="#planes">Planes Turísticos</a></li>
          <li><a href="#gastronomia">Ofertas Gastronómicas</a></li>
        </ul>
      </div>

      <div>
        <h4 class="pie__columna-titulo">Legales y ayuda</h4>
        <ul class="pie__lista">
          <li><a href="#">Términos y Condiciones</a></li>
          <li><a href="#">Privacidad</a></li>
          <li><a href="#">FAQs</a></li>
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
          <li class="pie__contacto-item">
            +57 (606) 364-0000
          </li>
          <li class="pie__contacto-item">
            Santa Rosa de Cabal, Risaralda
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

  <script src="/scripts/script.js"></script>

  <script>
  /* ════════════════════════════════════════════════════
     INDEX.PHP — Carga dinámica de planes turísticos
  ════════════════════════════════════════════════════ */

  // Imagen placeholder por defecto cuando no hay imagen en la BD
  const IMG_DEFAULT = [
    'https://lh3.googleusercontent.com/aida-public/AB6AXuAqrYiszyVO9b5FJvsR6QPJYsqdT1GRR_mYpjMlPFWjxafulqCduopzqvrPvj3cGmkgQeW_gjY7KmGaR5QZF2cJ5nICZ31UU4QvbFe3hQvpbR3fcNkkiv4PtLHKxPWQaj-m5KvRDvg0BuZM5v2Yx1AK6kk9MFOE-pR83n69Fo6EBiS5FK3SaeIGMpyP68nUPiP33pURX9LjDtg5JKbLoWzeTPEuwFkFJaSET8JWQ8qGwPGcqgJeNRD9fPPvAF0pxhu2cdEaD2v2fV72',
    'https://lh3.googleusercontent.com/aida-public/AB6AXuALZtJ2omilYGHRKrG9x1nwXdgbTd-CLY_tL4_aKJOyNqaE4VqDD1vM-0EgYDbk1b_zQ_Bzwjaj2EL6Jug0TSFK9Q-JoDVcC2zHmYPXmPc1zpUCs9D1NvLosOig4fhqukT6z_XkcA1TJTQ3z1izAZLfA9vpyDhxsvALNQAMbO2erryl9DKcG8sCxPDIU7jo1FK55zo9d97q8LeZ2rLMly0ow4RZmUu4fjbaSEys11NOkjGrGZcKesu4mXcv8VQI1xP2rcQ5i7H1vsOt',
    'https://lh3.googleusercontent.com/aida-public/AB6AXuCGZj183QMPNxArCGjvbpRWb8nIGbfTxzu7KKzsUUwoup4opovL_2ax7638xLfXWsTVJ1v0jzegSzjCRDmhpBuss3YW7lxJc7zEbj7DB-JGTHSRu24vIj4BFSU3WGWajncHqKD4tDTAe35nhNLhcqzJlbtmsBL8xsEyZpj1x8ZGtQeKs0da94l9vSQaoV_fGaAYWq2uPje0-b20Ubur2D8KewvzKCXDnLgnFTPtyV1wnH9ZvVnLXKAA8g6BO7Ol7BbeE0q0uXo3eBwO',
  ];

  function renderPlan(plan, idx) {
    const img = plan.imagen_hero_url || IMG_DEFAULT[idx % IMG_DEFAULT.length];
    const dias = plan.duracion_dias > 1 ? `${plan.duracion_dias} Días` : `${plan.duracion_dias} Día`;
    const estrellas = parseFloat(plan.puntuacion).toFixed(1);
    const resenas   = plan.total_resenas;
    const precio    = plan.precio_formateado;
    const moneda    = plan.moneda === 'COP' ? 'COP' : '';

    return `
    <div class="tarjeta-plan panel-vidrio borde-vidrio">
      <div class="tarjeta-plan__imagen-envoltorio">
        <img class="tarjeta-plan__imagen" src="${img}" alt="${plan.titulo}" loading="lazy"/>
      </div>
      <div class="tarjeta-plan__cuerpo">
        <div>
          <div class="tarjeta-plan__fila-superior">
            <h3 class="tarjeta-plan__titulo">${plan.titulo}</h3>
            <span class="tarjeta-plan__etiqueta">${plan.etiqueta || 'Tour'}</span>
          </div>
          <div class="tarjeta-plan__meta">
            <span class="tarjeta-plan__meta-item">
              <span class="material-symbols-outlined">schedule</span> ${dias}
            </span>
            <span class="tarjeta-plan__meta-item">
              <span class="material-symbols-outlined">star</span> ${estrellas} (${resenas} reseñas)
            </span>
          </div>
        </div>
        <div class="tarjeta-plan__pie">
          <div>
            <span class="tarjeta-plan__precio-desde">Desde</span>
            <span class="tarjeta-plan__precio">
              $${precio} <span class="tarjeta-plan__precio-unidad">${moneda} / pers</span>
            </span>
          </div>
          <button class="tarjeta-plan__boton" onclick="window.location.href='detalles-turisticos.php?id=${plan.id}'">
            Ver detalles <span class="material-symbols-outlined">visibility</span>
          </button>
        </div>
      </div>
    </div>`;
  }

  async function cargarPlanes() {
    try {
      const res   = await fetch('/ajax/planes_turisticos.php?limite=3');
      const data  = await res.json();
      const lista = document.getElementById('lista-planes');

      if (data.ok && data.planes.length) {
        lista.innerHTML = data.planes.map((p, i) => renderPlan(p, i)).join('');
      } else {
        lista.innerHTML = '<p style="padding:2rem;color:#6b4558;">No hay planes disponibles en este momento.</p>';
      }
    } catch (err) {
      console.error('Error cargando planes:', err);
    }
  }

  /* ── Cerrar sesión AJAX ── */
  const btnSalir = document.getElementById('btn-cerrar-sesion');
  if (btnSalir) {
    btnSalir.addEventListener('click', async () => {
      await fetch('/ajax/logout.php', { method: 'POST' });
      window.location.href = '/login.php';
    });
  }

  // Carga inmediata al DOMContentLoaded
  document.addEventListener('DOMContentLoaded', cargarPlanes);
  </script>

</body>
</html>