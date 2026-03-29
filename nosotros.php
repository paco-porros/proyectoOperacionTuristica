<?php
/**
 * nosotros.php — PÁGINA SOBRE NOSOTROS
 * Información de la empresa, misión, visión, equipo
 * Públicamente accesible
 */

// BLOQUE 1 - Detectar estado de autenticación
// estaLogueado() — mostrar navbar usuario o login button
require_once __DIR__ . '/includes/session.php';
$logueado  = estaLogueado();
$usuario   = $logueado ? usuarioActual() : null;
$avatarSrc = null;
if ($logueado) {
  $avatarUrl = $usuario['avatar_url'] ?? null;
  $avatarSrc = $avatarUrl ?: 'https://ui-avatars.com/api/?name=' . urlencode($usuario['nombre']) . '&background=afedf0&color=054da4&size=40';
}
$inicio = $logueado ? 'home.php' : 'index.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sobre Nosotros | Operador Turístico y Gastronómico – Santa Rosa de Cabal</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&family=DM+Sans:wght@300;400;500&display=swap" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,300,0,0" />
  <link rel="stylesheet" href="/estilos/style-nosotros.css" />
  <style>
    /* ── Zona usuario logueado ── */
    .navegacion__zona-usuario{display:flex;align-items:center;gap:.75rem}
    .navegacion__saludo{font-size:.8rem;font-weight:600;color:#582c4d}
    .navegacion__boton-notificaciones{padding:.4rem;border-radius:9999px;background:transparent;border:none;cursor:pointer;display:flex;align-items:center;color:#582c4d;transition:background .2s}
    .navegacion__boton-notificaciones:hover{background:rgba(162,103,105,.15)}
    .navegacion__avatar-envoltorio{position:relative}
    .navegacion__avatar{width:2.5rem;height:2.5rem;border-radius:9999px;overflow:hidden;border:2px solid #b8787a;cursor:pointer;transition:transform .2s}
    .navegacion__avatar:hover{transform:scale(1.05)}
    .navegacion__avatar-imagen{width:100%;height:100%;object-fit:cover}
    .navegacion__dropdown{display:none;position:absolute;right:0;top:calc(100% + .5rem);background:#fff;border:1px solid rgba(162,103,105,.2);border-radius:.75rem;box-shadow:0 10px 25px rgba(88,44,77,.12);padding:.5rem;min-width:180px;z-index:100}
    .navegacion__dropdown--visible{display:block}
    .navegacion__dropdown-encabezado{padding:.5rem .75rem;font-size:.75rem;font-weight:700;color:#582c4d;text-transform:uppercase;letter-spacing:.1em;border-bottom:1px solid rgba(162,103,105,.1);margin-bottom:.25rem}
    .navegacion__dropdown-item{display:flex;align-items:center;gap:.5rem;padding:.5rem .75rem;font-size:.875rem;color:#582c4d;font-weight:600;text-decoration:none;border-radius:.5rem;transition:background .2s}
    .navegacion__dropdown-item:hover{background:#e8d0d5}
    .navegacion__dropdown-salir{display:flex;align-items:center;gap:.5rem;padding:.5rem .75rem;font-size:.875rem;color:#ba1a1a;font-weight:600;background:transparent;border:none;cursor:pointer;border-radius:.5rem;width:100%;transition:background .2s}
    .navegacion__dropdown-salir:hover{background:rgba(186,26,26,.08)}
  </style>
</head>

<body>

    <!-- ═══════════════════════════════════════════
       NAVEGACIÓN — condicional logueado / anónimo
  ═══════════════════════════════════════════ -->
  <nav class="navegacion">
    <div class="navegacion__logo">
      <a href="<?= $inicio ?>">Operador Turístico y Gastronómico | Santa Rosa de Cabal</a>
    </div>

    <div class="navegacion__enlaces">
      <?php if ($logueado): ?>
        <a class="navegacion__enlace" href="home.php">Inicio</a>
        <a class="navegacion__enlace" href="planes.php">Planes Turísticos</a>
        <a class="navegacion__enlace" href="gastronomia.php">Ofertas Gastronómicas</a>
        <?php if (in_array($usuario['rol'], ['admin', 'editor'])): ?>
          <a class="navegacion__enlace" href="dashboard-administrador.php">Dashboard</a>
        <?php endif; ?>
      <?php else: ?>
        <a class="navegacion__enlace" href="index.php#servicios">Servicios</a>
        <a class="navegacion__enlace" href="planes.php">Planes Turísticos</a>
        <a class="navegacion__enlace" href="gastronomia.php">Ofertas Gastronómicas</a>
      <?php endif; ?>
    </div>

    <?php if ($logueado): ?>
      <div class="navegacion__zona-usuario">
        <span class="navegacion__saludo">
          Hola, <?= htmlspecialchars(explode(' ', $usuario['nombre'])[0]) ?>
        </span>
        <button class="navegacion__boton-notificaciones" title="Notificaciones">
          <span class="material-symbols-outlined">notifications</span>
        </button>
        <div class="navegacion__avatar-envoltorio">
          <div class="navegacion__avatar" id="avatar-usuario" title="Mi cuenta">
            <img alt="Perfil" class="navegacion__avatar-imagen" src="<?= htmlspecialchars($avatarSrc) ?>" />
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
      <button class="navegacion__boton">
        <a href="index.php">← Inicio</a>
      </button>
    <?php endif; ?>
  </nav>

  <!-- ═══════════════════════════════════════════
       HÉROE
  ═══════════════════════════════════════════ -->
  <header class="heroe">
    <div class="heroe__fondo">
      <img
        class="heroe__imagen"
        src="/img/parque-principal.jpg"
        alt="Parque Principal de Santa Rosa de Cabal"
      />
      <div class="heroe__gradiente"></div>
      <div class="heroe__patron"></div>
    </div>
    <div class="heroe__contenido">
      <span class="heroe__supratitulo">Quiénes somos · Nuestra historia</span>
      <h1 class="heroe__titulo">Una empresa <em>nacida</em><br>en el corazón cafetero</h1>
      <p class="heroe__bajada">
        Somos un operador turístico y gastronómico con raíces en Santa Rosa de Cabal,
        comprometidos con mostrar lo mejor del Paisaje Cultural Cafetero al mundo.
      </p>
      <div class="heroe__anclas">
        <a class="heroe__ancla" href="#mision-vision">
          <span class="simbolo-material">favorite</span> Misión y Visión
        </a>
        <a class="heroe__ancla" href="#equipo">
          <span class="simbolo-material">groups</span> Equipo Ejecutivo
        </a>
        <a class="heroe__ancla" href="#carreras">
          <span class="simbolo-material">work</span> Carreras
        </a>
      </div>
    </div>
  </header>

  <!-- Franja decorativa -->
  <div class="franja-decorativa">
    <span class="franja-decorativa__linea"></span>
    <p class="franja-decorativa__texto">
      "Más de una década conectando viajeros con la esencia de Risaralda"
    </p>
    <span class="franja-decorativa__linea"></span>
  </div>

  <!-- ═══════════════════════════════════════════
       MISIÓN Y VISIÓN
  ═══════════════════════════════════════════ -->
  <section id="mision-vision" class="seccion seccion--suave">
    <div class="seccion__cabecera">
      <div class="seccion__etiqueta">
        <span class="simbolo-material">favorite</span> Nuestra razón de ser
      </div>
      <h2 class="seccion__titulo">Misión <em>&amp;</em> Visión</h2>
      <p class="seccion__descripcion">
        Fundados en 2013, nacimos con la convicción de que el turismo responsable puede ser
        el motor de desarrollo más poderoso para los municipios del Eje Cafetero.
      </p>
    </div>

    <!-- Historia con imagen lateral -->
    <div class="historia__rejilla">
      <div class="historia__imagen-envoltorio">
        <img
          class="historia__imagen"
          src="/img/paisaje-cafetero.jpg"
          alt="Paisaje Cafetero de Risaralda"
        />
        <div class="historia__imagen-pie">
          <span class="historia__imagen-anio">2013</span>
          <span class="historia__imagen-leyenda">Año de fundación</span>
        </div>
      </div>

      <div class="historia__texto">
        <p class="historia__parrafo">
          Todo comenzó cuando un grupo de guías locales y emprendedores santarrosanos decidió
          organizar la oferta turística del municipio, que hasta entonces era informal y dispersa.
          La riqueza de las Termales, el café de origen, el río San Eugenio y la cultura paisa
          merecían una vitrina profesional que llegara a turistas nacionales e internacionales.
        </p>
        <p class="historia__parrafo">
          En nuestros primeros años nos enfocamos en la construcción de relaciones con los
          prestadores locales: fincas cafeteras, restaurantes familiares, artesanos y guías de
          montaña. Hoy somos el principal puente entre el viajero exigente y la autenticidad de
          Santa Rosa de Cabal, ciudad reconocida por la CNN Travel entre los 20 destinos
          emergentes más interesantes de América Latina.
        </p>
        <p class="historia__parrafo">
          Operamos bajo los principios del turismo sostenible del Ministerio de Comercio,
          Industria y Turismo de Colombia, y somos miembros activos de la Red de Turismo
          Sostenible del Paisaje Cultural Cafetero, declarado Patrimonio de la Humanidad
          por la UNESCO en 2011.
        </p>
      </div>
    </div>

    <!-- Tarjetas Misión y Visión -->
    <div class="mision-vision__rejilla">
      <div class="tarjeta-mv tarjeta-mv--mision">
        <div class="tarjeta-mv__icono">
          <span class="simbolo-material">rocket_launch</span>
        </div>
        <h3 class="tarjeta-mv__titulo">Nuestra Misión</h3>
        <p class="tarjeta-mv__texto">
          Diseñar y operar experiencias turísticas y gastronómicas auténticas en Santa Rosa de Cabal
          y el Eje Cafetero, que generen valor económico para las comunidades locales, conserven
          el patrimonio natural y cultural del Paisaje Cultural Cafetero, y superen las expectativas
          de cada viajero a través de un servicio cálido, profesional y personalizado.
        </p>
      </div>

      <div class="tarjeta-mv tarjeta-mv--vision">
        <div class="tarjeta-mv__icono">
          <span class="simbolo-material">visibility</span>
        </div>
        <h3 class="tarjeta-mv__titulo">Nuestra Visión</h3>
        <p class="tarjeta-mv__texto">
          Para 2030, ser el operador turístico de referencia en el noroccidente colombiano,
          reconocido por transformar la manera en que el mundo descubre el Paisaje Cultural
          Cafetero. Queremos liderar un modelo de turismo regenerativo donde cada visitante
          contribuya activamente a la preservación del ecosistema, la economía campesina
          y la identidad cultural de nuestro territorio.
        </p>
      </div>
    </div>

    <!-- Valores -->
    <div class="valores__rejilla">
      <div class="tarjeta-valor">
        <div class="tarjeta-valor__icono">
          <span class="simbolo-material">eco</span>
        </div>
        <div class="tarjeta-valor__nombre">Sostenibilidad</div>
        <p class="tarjeta-valor__descripcion">
          Cada decisión operativa mide su impacto ambiental. Usamos proveedores locales,
          reducimos plásticos y apoyamos la reforestación del río San Eugenio.
        </p>
      </div>

      <div class="tarjeta-valor">
        <div class="tarjeta-valor__icono">
          <span class="simbolo-material">handshake</span>
        </div>
        <div class="tarjeta-valor__nombre">Comunidad</div>
        <p class="tarjeta-valor__descripcion">
          El 85 % de nuestros proveedores son microempresarios santarrosanos. Reinvertimos
          parte de nuestras utilidades en formación turística local.
        </p>
      </div>

      <div class="tarjeta-valor">
        <div class="tarjeta-valor__icono">
          <span class="simbolo-material">verified</span>
        </div>
        <div class="tarjeta-valor__nombre">Excelencia</div>
        <p class="tarjeta-valor__descripcion">
          Somos Prestadores de Servicios Turísticos certificados por el Ministerio de Comercio.
          Formamos continuamente a nuestro equipo con estándares internacionales.
        </p>
      </div>

      <div class="tarjeta-valor">
        <div class="tarjeta-valor__icono">
          <span class="simbolo-material">diversity_3</span>
        </div>
        <div class="tarjeta-valor__nombre">Inclusión</div>
        <p class="tarjeta-valor__descripcion">
          Diseñamos experiencias accesibles para todos: turismo familiar, turismo de tercera
          edad, turismo accesible y opciones para viajeros con presupuesto limitado.
        </p>
      </div>
    </div>
  </section>

  <!-- ═══════════════════════════════════════════
       EQUIPO EJECUTIVO
  ═══════════════════════════════════════════ -->
  <section id="equipo" class="seccion seccion--oscura">
    <div class="seccion__cabecera">
      <div class="seccion__etiqueta">
        <span class="simbolo-material">groups</span> Las personas detrás del viaje
      </div>
      <h2 class="seccion__titulo">Equipo <em>Ejecutivo</em></h2>
      <p class="seccion__descripcion">
        Un equipo multidisciplinar con pasión por Santa Rosa de Cabal, formado entre
        administradores de turismo, chefs, guías certificados y expertos en hospitalidad.
      </p>
    </div>

    <div class="equipo__rejilla">

      <div class="tarjeta-miembro">
        <div class="tarjeta-miembro__imagen-envoltorio">
          <div class="tarjeta-miembro__avatar">VALRI</div>
        </div>
        <div class="tarjeta-miembro__cuerpo">
          <div class="tarjeta-miembro__nombre">Victor Lopez Rios</div>
          <div class="tarjeta-miembro__cargo">Director General &amp; Fundador</div>
          <p class="tarjeta-miembro__bio">
            Víctor es barbero y estudiante en Unisarc (Santa Rosa de Cabal), apasionado por el estilo,
  la estética y el emprendimiento. Combina su formación académica con la práctica diaria en
  barbería, desarrollando técnicas modernas de corte y cuidado personal. Ha participado en
  eventos locales de cultura urbana y busca posicionarse como referente en el mundo del
  grooming masculino en la región.
          </p>
          <div class="tarjeta-miembro__especialidades">
            <span class="etiqueta-especialidad">Gestión Turística</span>
            <span class="etiqueta-especialidad">Patrimonio UNESCO</span>
            <span class="etiqueta-especialidad">Estrategia</span>
          </div>
        </div>
      </div>

      <div class="tarjeta-miembro">
        <div class="tarjeta-miembro__imagen-envoltorio">
          <div class="tarjeta-miembro__avatar">CHRISS</div>
        </div>
        <div class="tarjeta-miembro__cuerpo">
          <div class="tarjeta-miembro__nombre">Crhristian David Henao Sanchez</div>
          <div class="tarjeta-miembro__cargo">Director de Operaciones Turísticas</div>
          <p class="tarjeta-miembro__bio">
            Guía de turismo certificado por COTELCO con 12 años de experiencia en rutas
            del Paisaje Cultural Cafetero. Especialista en ecoturismo y senderismo de alto
            rendimiento. Formador de guías locales para el programa departamental
            Guías Risaralda 2025.
          </p>
          <div class="tarjeta-miembro__especialidades">
            <span class="etiqueta-especialidad">Ecoturismo</span>
            <span class="etiqueta-especialidad">Senderismo</span>
            <span class="etiqueta-especialidad">Logística</span>
          </div>
        </div>
      </div>

      <div class="tarjeta-miembro">
        <div class="tarjeta-miembro__imagen-envoltorio">
          <div class="tarjeta-miembro__avatar">AT</div>
        </div>
        <div class="tarjeta-miembro__cuerpo">
          <div class="tarjeta-miembro__nombre">Alejo Tabares</div>
          <div class="tarjeta-miembro__cargo">Directora Gastronómica</div>
          <p class="tarjeta-miembro__bio">
            Chef profesional graduada del Politécnico Colombiano Jaime Isaza Cadavid.
            Especialista en cocina cafetera y fusión andina. Asesora de restaurantes en
            Santa Rosa, Pereira y Manizales. Jurado en el Festival Gastronómico del
            Paisaje Cultural Cafetero 2021–2023.
          </p>
          <div class="tarjeta-miembro__especialidades">
            <span class="etiqueta-especialidad">Cocina Cafetera</span>
            <span class="etiqueta-especialidad">Trucha</span>
            <span class="etiqueta-especialidad">Café de Especialidad</span>
          </div>
        </div>
      </div>

      <div class="tarjeta-miembro">
        <div class="tarjeta-miembro__imagen-envoltorio">
          <div class="tarjeta-miembro__avatar">JEGM</div>
        </div>
        <div class="tarjeta-miembro__cuerpo">
          <div class="tarjeta-miembro__nombre">Jesus Enrique Garcia Marbello</div>
          <div class="tarjeta-miembro__cargo">Director Comercial y Marketing</div>
          <p class="tarjeta-miembro__bio">
            Comunicador Social (Universidad de Manizales) con especialización en marketing
            digital turístico. Responsable del crecimiento digital de la empresa: más de
            45.000 seguidores en redes sociales y posicionamiento SEO en turismo cafetero
            a nivel nacional.
          </p>
          <div class="tarjeta-miembro__especialidades">
            <span class="etiqueta-especialidad">Marketing Digital</span>
            <span class="etiqueta-especialidad">SEO Turístico</span>
            <span class="etiqueta-especialidad">Ventas</span>
          </div>
        </div>
      </div>

      

     
    </div>

    <!-- Cifras del equipo -->
    <div class="equipo__cifras">
      <div class="cifra-equipo">
        <div class="cifra-equipo__numero">28</div>
        <div class="cifra-equipo__descripcion">Colaboradores directos en planta</div>
      </div>
      <div class="cifra-equipo">
        <div class="cifra-equipo__numero">60+</div>
        <div class="cifra-equipo__descripcion">Guías y aliados locales certificados</div>
      </div>
      <div class="cifra-equipo">
        <div class="cifra-equipo__numero">12</div>
        <div class="cifra-equipo__descripcion">Años de experiencia en el sector</div>
      </div>
      <div class="cifra-equipo">
        <div class="cifra-equipo__numero">4.9</div>
        <div class="cifra-equipo__descripcion">Calificación promedio en Google</div>
      </div>
    </div>

  </section>

  <!-- ═══════════════════════════════════════════
       CARRERAS
  ═══════════════════════════════════════════ -->
  <section id="carreras" class="seccion seccion--clara">
    <div class="seccion__cabecera">
      <div class="seccion__etiqueta">
        <span class="simbolo-material">work</span> Únete al equipo
      </div>
      <h2 class="seccion__titulo">Haz Carrera con<br><em>Nosotros</em></h2>
      <p class="seccion__descripcion">
        Buscamos personas apasionadas por el turismo, la gastronomía y el territorio cafetero.
        Si amas Santa Rosa de Cabal y quieres construir experiencias que transformen vidas,
        este es tu lugar.
      </p>
    </div>

    <!-- Beneficios de trabajar aquí -->
    <div class="carreras__beneficios">
      <div class="tarjeta-beneficio">
        <div class="tarjeta-beneficio__icono">
          <span class="simbolo-material">school</span>
        </div>
        <div class="tarjeta-beneficio__titulo">Formación continua</div>
        <p class="tarjeta-beneficio__descripcion">
          Acceso a cursos de guianza turística, COTELCO, SENA y plataformas internacionales
          de hospitalidad. Cubrimos el 100 % del costo de las certificaciones relacionadas
          con tu cargo.
        </p>
      </div>

      <div class="tarjeta-beneficio">
        <div class="tarjeta-beneficio__icono">
          <span class="simbolo-material">landscape</span>
        </div>
        <div class="tarjeta-beneficio__titulo">Trabajo en el territorio</div>
        <p class="tarjeta-beneficio__descripcion">
          Operamos en plena naturaleza: termales, cañones, cafetales y mercados campesinos.
          Cada jornada laboral es una experiencia distinta en uno de los paisajes más
          hermosos de Colombia.
        </p>
      </div>

      <div class="tarjeta-beneficio">
        <div class="tarjeta-beneficio__icono">
          <span class="simbolo-material">savings</span>
        </div>
        <div class="tarjeta-beneficio__titulo">Beneficios económicos</div>
        <p class="tarjeta-beneficio__descripcion">
          Salario competitivo, comisiones por ventas, prima extralegal de turismo en
          temporada alta, auxilio de transporte y acceso gratuito a nuestros planes
          para ti y tu familia una vez al año.
        </p>
      </div>

      <div class="tarjeta-beneficio">
        <div class="tarjeta-beneficio__icono">
          <span class="simbolo-material">balance</span>
        </div>
        <div class="tarjeta-beneficio__titulo">Bienestar y equilibrio</div>
        <p class="tarjeta-beneficio__descripcion">
          Turnos flexibles en temporada baja, trabajo híbrido para cargos administrativos,
          día libre en tu cumpleaños y jornadas de bienestar en las Termales de Santa Rosa
          dos veces al año.
        </p>
      </div>

      <div class="tarjeta-beneficio">
        <div class="tarjeta-beneficio__icono">
          <span class="simbolo-material">groups_3</span>
        </div>
        <div class="tarjeta-beneficio__titulo">Cultura colaborativa</div>
        <p class="tarjeta-beneficio__descripcion">
          Equipo pequeño y cohesionado donde cada persona tiene voz real. Reuniones de
          retroalimentación mensual, cultura horizontal y espacio para proponer nuevas
          experiencias y productos turísticos.
        </p>
      </div>

      <div class="tarjeta-beneficio">
        <div class="tarjeta-beneficio__icono">
          <span class="simbolo-material">travel_explore</span>
        </div>
        <div class="tarjeta-beneficio__titulo">Proyección internacional</div>
        <p class="tarjeta-beneficio__descripcion">
          Contamos con alianzas con operadores de España, Estados Unidos y México.
          Participamos en la Feria Internacional de Turismo (FIT) y Anato cada año.
          Hay oportunidades de intercambio con socios internacionales.
        </p>
      </div>
    </div>

    <!-- Vacantes actuales -->
    <h3 class="carreras__titulo-vacantes">Vacantes <em>abiertas</em></h3>

    <div class="vacantes__lista">

      <div class="tarjeta-vacante">
        <div>
          <div class="tarjeta-vacante__cargo">Guía Turístico Bilingüe (Español – Inglés)</div>
          <div class="tarjeta-vacante__meta">
            <span class="tarjeta-vacante__dato">
              <span class="simbolo-material">place</span> Santa Rosa de Cabal, Risaralda
            </span>
            <span class="tarjeta-vacante__dato">
              <span class="simbolo-material">school</span> Guía de turismo certificado COTELCO
            </span>
            <span class="tarjeta-vacante__dato">
              <span class="simbolo-material">payments</span> $2.200.000 – $2.800.000 COP + comisiones
            </span>
          </div>
        </div>
        <span class="tarjeta-vacante__tipo tarjeta-vacante__tipo--tiempo-completo">Tiempo Completo</span>
      </div>

      <div class="tarjeta-vacante">
        <div>
          <div class="tarjeta-vacante__cargo">Coordinador/a de Reservas y Logística</div>
          <div class="tarjeta-vacante__meta">
            <span class="tarjeta-vacante__dato">
              <span class="simbolo-material">place</span> Santa Rosa de Cabal (híbrido)
            </span>
            <span class="tarjeta-vacante__dato">
              <span class="simbolo-material">school</span> Tecnología en Turismo o afines · 2 años exp.
            </span>
            <span class="tarjeta-vacante__dato">
              <span class="simbolo-material">payments</span> $1.800.000 – $2.200.000 COP
            </span>
          </div>
        </div>
        <span class="tarjeta-vacante__tipo tarjeta-vacante__tipo--tiempo-completo">Tiempo Completo</span>
      </div>

      <div class="tarjeta-vacante">
        <div>
          <div class="tarjeta-vacante__cargo">Chef Auxiliar – Especialidad Trucha y Cocina Cafetera</div>
          <div class="tarjeta-vacante__meta">
            <span class="tarjeta-vacante__dato">
              <span class="simbolo-material">place</span> Vía Termales, Santa Rosa de Cabal
            </span>
            <span class="tarjeta-vacante__dato">
              <span class="simbolo-material">school</span> Técnico en Cocina · Experiencia en parrilla
            </span>
            <span class="tarjeta-vacante__dato">
              <span class="simbolo-material">payments</span> $1.600.000 COP + prestaciones
            </span>
          </div>
        </div>
        <span class="tarjeta-vacante__tipo tarjeta-vacante__tipo--tiempo-completo">Tiempo Completo</span>
      </div>

      <div class="tarjeta-vacante">
        <div>
          <div class="tarjeta-vacante__cargo">Asistente de Marketing Digital y Redes Sociales</div>
          <div class="tarjeta-vacante__meta">
            <span class="tarjeta-vacante__dato">
              <span class="simbolo-material">place</span> Remoto / Santa Rosa de Cabal
            </span>
            <span class="tarjeta-vacante__dato">
              <span class="simbolo-material">school</span> Comunicación Social, Diseño o afines
            </span>
            <span class="tarjeta-vacante__dato">
              <span class="simbolo-material">payments</span> $1.400.000 – $1.700.000 COP
            </span>
          </div>
        </div>
        <span class="tarjeta-vacante__tipo tarjeta-vacante__tipo--medio-tiempo">Medio Tiempo</span>
      </div>

      <div class="tarjeta-vacante">
        <div>
          <div class="tarjeta-vacante__cargo">Conductor Turístico – Vehículo 4×4</div>
          <div class="tarjeta-vacante__meta">
            <span class="tarjeta-vacante__dato">
              <span class="simbolo-material">place</span> Santa Rosa de Cabal y municipios aledaños
            </span>
            <span class="tarjeta-vacante__dato">
              <span class="simbolo-material">school</span> Licencia C2 vigente · Pase de turismo deseable
            </span>
            <span class="tarjeta-vacante__dato">
              <span class="simbolo-material">payments</span> $1.500.000 COP + rodamiento
            </span>
          </div>
        </div>
        <span class="tarjeta-vacante__tipo tarjeta-vacante__tipo--temporal">Temporal</span>
      </div>

    </div>

    <!-- CTA postulación espontánea -->
    <div class="carreras__cta">
      <div>
        <h3 class="carreras__cta-titulo">¿No ves tu perfil<br><em>entre las vacantes?</em></h3>
        <p class="carreras__cta-texto">
          Siempre estamos abiertos a conocer personas talentosas con pasión por el turismo
          y el territorio cafetero. Envíanos tu hoja de vida con una carta contándonos por qué
          quieres hacer parte de nuestro equipo. Guardamos todas las postulaciones para
          futuras oportunidades.
        </p>
      </div>
      <button class="carreras__cta-boton">
        <a href="mailto:talentohumano@srcabal.com">Postulación Espontánea</a>
      </button>
    </div>

  </section>

  <!-- ═══════════════════════════════════════════
       PIE DE PÁGINA
  ═══════════════════════════════════════════ -->
  <footer class="pie">
    <div class="pie__rejilla">
      <div>
        <span class="pie__logo">Operador Turístico y Gastronómico | Santa Rosa de Cabal</span>
        <p class="pie__eslogan">Redefiniendo el lujo en cada travesía. Tu horizonte, nuestra pasión.</p>
        <div class="pie__redes">
          <button class="pie__boton-red"><span class="simbolo-material">public</span></button>
          <button class="pie__boton-red"><span class="simbolo-material">share</span></button>
        </div>
      </div>

      <div>
        <h4 class="pie__columna-titulo">Enlaces rápidos</h4>
        <ul class="pie__lista">
          <li><a href="<?= $inicio ?>">Inicio</a></li>
          <li><a href="#mision-vision">Mision y vision</a></li>
          <li><a href="#equipo">Equipo ejecutivo</a></li>
          <li><a href="#carreras">Carreras</a></li>
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
            <span class="simbolo-material">mail</span>
            <a href="mailto:info@srcabal.com">info@srcabal.com</a>
          </li>
          <li class="pie__contacto-item">
            <span class="simbolo-material">work</span>
            <a href="mailto:talentohumano@srcabal.com">talentohumano@srcabal.com</a>
          </li>
          <li class="pie__contacto-item">
            <span class="simbolo-material">call</span>
            +57 (606) 364-0000
          </li>
          <li class="pie__contacto-item">
            <span class="simbolo-material">place</span>
            Santa Rosa de Cabal, Risaralda
          </li>
        </ul>
      </div>
    </div>
    <div class="pie__inferior">
      <p>© 2026 Operador Turístico y Gastronómico | Santa Rosa de Cabal
        <span class="pie__separador">|</span>
        <a href="#">Política de Privacidad</a>
        <span class="pie__separador">|</span>
        <a href="#">Términos de Servicio</a>
      </p>
    </div>
  </footer>

  <?php if ($logueado): ?>
  <script src="/scripts/avatar-menu.js"></script>
  <?php endif; ?>
</body>
</html>