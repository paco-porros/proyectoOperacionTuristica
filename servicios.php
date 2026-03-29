<?php
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
  <title>Nuestros Servicios | Operador Turístico y Gastronómico – Santa Rosa de Cabal</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&family=DM+Sans:wght@300;400;500&display=swap" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,300,0,0" />
  <link rel="stylesheet" href="/estilos/style-servicios.css" />
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
          class="alojamiento__imagen"
          src="/img/parque-principal.jpg"
          alt="Termales de Santa Rosa de Cabal"
        />
      <div class="heroe__gradiente"></div>
    </div>
    <div class="heroe__contenido">
      <span class="heroe__supratitulo">Santa Rosa de Cabal · Risaralda · Colombia</span>
      <h1 class="heroe__titulo">Nuestros <em>Servicios</em><br>para Tu Viaje Perfecto</h1>
      <p class="heroe__bajada">
        Diseñamos cada detalle de tu experiencia en el corazón del Eje Cafetero.
        Desde el primer descanso hasta la última aventura, estamos contigo.
      </p>
      <div class="heroe__anclas">
        <a class="heroe__ancla" href="#alojamiento">
          <span class="simbolo-material">hotel</span> Alojamiento
        </a>
        <a class="heroe__ancla" href="#transporte">
          <span class="simbolo-material">directions_car</span> Transporte
        </a>
        <a class="heroe__ancla" href="#alimentacion">
          <span class="simbolo-material">restaurant</span> Alimentación
        </a>
        <a class="heroe__ancla" href="#entretenimiento">
          <span class="simbolo-material">local_activity</span> Entretenimiento
        </a>
      </div>
    </div>
  </header>

  <!-- Franja decorativa -->
  <div class="franja-decorativa">
    <span class="franja-decorativa__linea"></span>
    <p class="franja-decorativa__texto">
      "Donde el café, las aguas termales y la montaña se convierten en memoria"
    </p>
    <span class="franja-decorativa__linea"></span>
  </div>

  <!-- ═══════════════════════════════════════════
       ALOJAMIENTO
  ═══════════════════════════════════════════ -->
  <section id="alojamiento" class="seccion seccion--suave">
    <div class="seccion__cabecera">
      <div class="seccion__etiqueta">
        <span class="simbolo-material">hotel</span> Dónde descansar
      </div>
      <h2 class="seccion__titulo">Alojamiento en<br><em>Santa Rosa de Cabal</em></h2>
      <p class="seccion__descripcion">
        El municipio ofrece una variada oferta de hospedaje: desde acogedoras fincas cafeteras
        con arquitectura de bahareque hasta modernos hoteles con acceso a aguas termales.
        La mayoría se ubica a pocos minutos del centro y las Termales de Santa Rosa.
      </p>
    </div>

    <div class="alojamiento__rejilla">

      <!-- Lista de opciones -->
      <ul class="alojamiento__lista">

        <li class="tarjeta-alojamiento">
          <div class="tarjeta-alojamiento__icono">
            <span class="simbolo-material">spa</span>
          </div>
          <div>
            <div class="tarjeta-alojamiento__nombre">Hotel Termales de Santa Rosa</div>
            <div class="tarjeta-alojamiento__detalle">
              Ubicado a 9 km del casco urbano, sobre la vía al Nevado del Ruiz. Ofrece piscinas termales
              a 38 °C, cabañas en medio de la selva nublada y restaurante con vista al cañón del río San
              Eugenio. Capacidad para grupos y parejas.
            </div>
            <span class="tarjeta-alojamiento__precio">Desde $220.000 COP / noche</span>
          </div>
        </li>

        <li class="tarjeta-alojamiento">
          <div class="tarjeta-alojamiento__icono">
            <span class="simbolo-material">villa</span>
          </div>
          <div>
            <div class="tarjeta-alojamiento__nombre">Finca Cafetera La Esmeralda</div>
            <div class="tarjeta-alojamiento__detalle">
              Hacienda cafetera de 1920 en vereda La Hermosa. Habitaciones en bahareque auténtico,
              desayuno típico incluido, recorridos por el beneficiadero y vista a 360° sobre el paisaje
              cafetero. Declarado Patrimonio Cultural de la Humanidad por la UNESCO.
            </div>
            <span class="tarjeta-alojamiento__precio">Desde $180.000 COP / noche</span>
          </div>
        </li>

        <li class="tarjeta-alojamiento">
          <div class="tarjeta-alojamiento__icono">
            <span class="simbolo-material">bed</span>
          </div>
          <div>
            <div class="tarjeta-alojamiento__nombre">Hotel Boutique Casa Vásquez</div>
            <div class="tarjeta-alojamiento__detalle">
              En pleno centro histórico, frente al Parque Principal. Edificio republicano restaurado
              con 14 habitaciones decoradas con artesanías locales, Wi-Fi, desayuno buffet y salón
              de eventos. Ideal para viajeros de negocio y turismo cultural.
            </div>
            <span class="tarjeta-alojamiento__precio">Desde $150.000 COP / noche</span>
          </div>
        </li>

        <li class="tarjeta-alojamiento">
          <div class="tarjeta-alojamiento__icono">
            <span class="simbolo-material">cottage</span>
          </div>
          <div>
            <div class="tarjeta-alojamiento__nombre">Cabañas El Arrayán</div>
            <div class="tarjeta-alojamiento__detalle">
              Seis cabañas ecológicas inmersas en guadual a 1.800 m s. n. m., a 4 km del municipio.
              Cada cabaña cuenta con chimenea, hamaca, jacuzzi privado y jardín. Pet-friendly.
              Perfectas para desconectarse en familia.
            </div>
            <span class="tarjeta-alojamiento__precio">Desde $280.000 COP / noche</span>
          </div>
        </li>

        <li class="tarjeta-alojamiento">
          <div class="tarjeta-alojamiento__icono">
            <span class="simbolo-material">home</span>
          </div>
          <div>
            <div class="tarjeta-alojamiento__nombre">Hostal El Sombrerero</div>
            <div class="tarjeta-alojamiento__detalle">
              Opción económica y social en el casco urbano, con dormitorios compartidos y habitaciones
              privadas. Salón comunal, cocina equipada y terraza con vista a la cordillera. Muy bien
              valorado por viajeros jóvenes y mochileros.
            </div>
            <span class="tarjeta-alojamiento__precio">Desde $45.000 COP / persona</span>
          </div>
        </li>

      </ul>

      <!-- Imagen destacada -->
      <div class="alojamiento__imagen-envoltorio">
        <img
          class="alojamiento__imagen"
          src="/img/termales-santa-rosa.jpg"
          alt="Termales de Santa Rosa de Cabal"
        />
        <div class="alojamiento__imagen-etiqueta">
          <em class="alojamiento__imagen-titulo">Aguas termales</em>
          <span class="alojamiento__imagen-subtitulo">A 9 km del centro · Temperatura: 38 °C</span>
        </div>
      </div>

    </div>
  </section>

  <!-- ═══════════════════════════════════════════
       TRANSPORTE
  ═══════════════════════════════════════════ -->
  <section id="transporte" class="seccion seccion--oscura">
    <div class="seccion__cabecera">
      <div class="seccion__etiqueta">
        <span class="simbolo-material">directions_car</span> Cómo movilizarte
      </div>
      <h2 class="seccion__titulo">Transporte y<br><em>Conectividad</em></h2>
      <p class="seccion__descripcion">
        Santa Rosa de Cabal se conecta fácilmente con Pereira, Manizales y el resto del Eje Cafetero.
        Dentro del municipio, las opciones son variadas para todos los presupuestos.
      </p>
    </div>

    <div class="transporte__rejilla">

      <div class="tarjeta-transporte">
        <div class="tarjeta-transporte__cabecera">
          <div class="tarjeta-transporte__icono">
            <span class="simbolo-material">directions_bus</span>
          </div>
          <div>
            <div class="tarjeta-transporte__tipo">Bus Intermunicipal</div>
            <div class="tarjeta-transporte__subtipo">Pereira ↔ Santa Rosa de Cabal</div>
          </div>
        </div>
        <div class="tarjeta-transporte__cuerpo">
          <p class="tarjeta-transporte__texto">
            La empresa Expreso Palmira y Transportes Florida ofrecen salidas cada 15–20 minutos
            desde el Terminal de Transportes de Pereira (carrera 19). El recorrido dura aprox.
            35–45 minutos por la vía La Virginia. Económico y frecuente.
          </p>
          <div class="tarjeta-transporte__info">
            <span class="tarjeta-transporte__dato">
              <span class="simbolo-material">schedule</span> Cada 15–20 min · 5 AM – 9 PM
            </span>
            <span class="tarjeta-transporte__dato">
              <span class="simbolo-material">payments</span> Desde $5.500 COP
            </span>
          </div>
        </div>
      </div>

      <div class="tarjeta-transporte">
        <div class="tarjeta-transporte__cabecera">
          <div class="tarjeta-transporte__icono">
            <span class="simbolo-material">local_taxi</span>
          </div>
          <div>
            <div class="tarjeta-transporte__tipo">Taxi y Mototaxi</div>
            <div class="tarjeta-transporte__subtipo">Movilidad local</div>
          </div>
        </div>
        <div class="tarjeta-transporte__cuerpo">
          <p class="tarjeta-transporte__texto">
            En el casco urbano operan taxis blancos tarifados por el municipio y mototaxis
            (chivos) que cubren barrios y veredas cercanas. Son el medio más rápido para
            llegar a las Termales y a los restaurantes del río San Eugenio.
          </p>
          <div class="tarjeta-transporte__info">
            <span class="tarjeta-transporte__dato">
              <span class="simbolo-material">straighten</span> Carrera mínima desde $4.000 COP
            </span>
            <span class="tarjeta-transporte__dato">
              <span class="simbolo-material">schedule</span> Disponible 24 horas
            </span>
          </div>
        </div>
      </div>

      <div class="tarjeta-transporte">
        <div class="tarjeta-transporte__cabecera">
          <div class="tarjeta-transporte__icono">
            <span class="simbolo-material">directions_car</span>
          </div>
          <div>
            <div class="tarjeta-transporte__tipo">Vehículo Privado</div>
            <div class="tarjeta-transporte__subtipo">Ruta desde Pereira · 35 km</div>
          </div>
        </div>
        <div class="tarjeta-transporte__cuerpo">
          <p class="tarjeta-transporte__texto">
            Desde Pereira se toma la carretera 25 (Pereira–La Virginia) hasta el desvío
            a Santa Rosa. La vía está en buen estado. Para llegar a las Termales se continúa
            9 km adicionales por vía destapada en buen estado, con señalización clara.
            Hay parqueadero amplio en todos los atractivos principales.
          </p>
          <div class="tarjeta-transporte__info">
            <span class="tarjeta-transporte__dato">
              <span class="simbolo-material">timer</span> 35–45 min desde Pereira
            </span>
            <span class="tarjeta-transporte__dato">
              <span class="simbolo-material">local_parking</span> Parqueadero gratuito en atractivos
            </span>
          </div>
        </div>
      </div>

      <div class="tarjeta-transporte">
        <div class="tarjeta-transporte__cabecera">
          <div class="tarjeta-transporte__icono">
            <span class="simbolo-material">tour</span>
          </div>
          <div>
            <div class="tarjeta-transporte__tipo">Transfer Turístico</div>
            <div class="tarjeta-transporte__subtipo">Servicio personalizado</div>
          </div>
        </div>
        <div class="tarjeta-transporte__cuerpo">
          <p class="tarjeta-transporte__texto">
            Contratamos traslados privados desde el Aeropuerto Matecaña de Pereira (PEI)
            o desde cualquier punto del Eje Cafetero. Vehículos tipo van para grupos,
            camionetas 4×4 para excursiones por trochas y minibús para grupos corporativos
            o familiares. Con conductor bilingüe opcional.
          </p>
          <div class="tarjeta-transporte__info">
            <span class="tarjeta-transporte__dato">
              <span class="simbolo-material">flight</span> Aeropuerto Matecaña · 45 min
            </span>
            <span class="tarjeta-transporte__dato">
              <span class="simbolo-material">groups</span> Grupos de 1 a 30 personas
            </span>
          </div>
        </div>
      </div>

      <div class="tarjeta-transporte">
        <div class="tarjeta-transporte__cabecera">
          <div class="tarjeta-transporte__icono">
            <span class="simbolo-material">pedal_bike</span>
          </div>
          <div>
            <div class="tarjeta-transporte__tipo">Cicloturismo</div>
            <div class="tarjeta-transporte__subtipo">Rutas cafeteras</div>
          </div>
        </div>
        <div class="tarjeta-transporte__cuerpo">
          <p class="tarjeta-transporte__texto">
            Santa Rosa hace parte del circuito de cicloturismo del Paisaje Cultural Cafetero.
            Alquiler de bicicletas de montaña disponible en el centro urbano con guías
            certificados. Ruta recomendada: centro → vereda Cedralito → Termales
            (18 km, nivel moderado).
          </p>
          <div class="tarjeta-transporte__info">
            <span class="tarjeta-transporte__dato">
              <span class="simbolo-material">route</span> Rutas de 8 a 45 km
            </span>
            <span class="tarjeta-transporte__dato">
              <span class="simbolo-material">payments</span> Alquiler desde $25.000 COP / día
            </span>
          </div>
        </div>
      </div>

      <div class="tarjeta-transporte">
        <div class="tarjeta-transporte__cabecera">
          <div class="tarjeta-transporte__icono">
            <span class="simbolo-material">two_wheeler</span>
          </div>
          <div>
            <div class="tarjeta-transporte__tipo">Jeep Willys</div>
            <div class="tarjeta-transporte__subtipo">Transporte campesino icónico</div>
          </div>
        </div>
        <div class="tarjeta-transporte__cuerpo">
          <p class="tarjeta-transporte__texto">
            El Jeep Willys es el transporte tradicional del Eje Cafetero, declarado Patrimonio
            Cultural. Conecta el centro urbano de Santa Rosa con veredas como Cedralito,
            Guacas y El Español. Además de su utilidad, el recorrido en chiva es en sí mismo
            una experiencia cultural única e imperdible.
          </p>
          <div class="tarjeta-transporte__info">
            <span class="tarjeta-transporte__dato">
              <span class="simbolo-material">place</span> Sale desde Parque Principal
            </span>
            <span class="tarjeta-transporte__dato">
              <span class="simbolo-material">payments</span> Desde $2.500 COP por tramo
            </span>
          </div>
        </div>
      </div>

    </div>
  </section>

  <!-- ═══════════════════════════════════════════
       ALIMENTACIÓN
  ═══════════════════════════════════════════ -->
  <section id="alimentacion" class="seccion seccion--clara">
    <div class="seccion__cabecera">
      <div class="seccion__etiqueta">
        <span class="simbolo-material">restaurant</span> Sabores locales
      </div>
      <h2 class="seccion__titulo">Alimentación y<br><em>Gastronomía</em></h2>
      <p class="seccion__descripcion">
        La cocina santarrosana combina la herencia antioqueña con los sabores frescos del trópico
        andino. La trucha, el café de origen, el tamal tolimense y la mazamorra son protagonistas
        de una mesa llena de identidad y sabor.
      </p>
    </div>

    <!-- Datos destacados -->
    <div class="alimentacion__intro-datos">
      <div class="dato-gastronomia">
        <div class="dato-gastronomia__numero">40+</div>
        <div class="dato-gastronomia__label">Restaurantes registrados</div>
      </div>
      <div class="dato-gastronomia">
        <div class="dato-gastronomia__numero">4</div>
        <div class="dato-gastronomia__label">Cocinas del río San Eugenio</div>
      </div>
      <div class="dato-gastronomia">
        <div class="dato-gastronomia__numero">1</div>
        <div class="dato-gastronomia__label">Denominación de Origen: Café de Risaralda</div>
      </div>
    </div>

    <!-- Lista de restaurantes -->
    <div class="alimentacion__lista">

      <div class="tarjeta-restaurante">
        <div class="tarjeta-restaurante__numero">01</div>
        <div>
          <div class="tarjeta-restaurante__encabezado">
            <div class="tarjeta-restaurante__nombre">Restaurante El Manantial – Termales</div>
            <span class="tarjeta-restaurante__tipo">Trucha · Parrilla</span>
          </div>
          <p class="tarjeta-restaurante__descripcion">
            Situado a orillas del río San Eugenio, a 9 km del centro, este es el restaurante
            más famoso de Santa Rosa. Especialidad: trucha al ajillo con patacones y mazamorra
            chiquita. Las mesas están dispuestas sobre el río con vista al bosque nublado.
            Imprescindible reservar los fines de semana. Capacidad para 200 personas.
          </p>
          <div class="tarjeta-restaurante__meta">
            <span class="tarjeta-restaurante__meta-item">
              <span class="simbolo-material">place</span> Vía Termales km 9
            </span>
            <span class="tarjeta-restaurante__meta-item">
              <span class="simbolo-material">schedule</span> Lunes a domingo 8 AM – 6 PM
            </span>
            <span class="tarjeta-restaurante__meta-item">
              <span class="simbolo-material">payments</span> Menú desde $28.000 COP
            </span>
          </div>
        </div>
      </div>

      <div class="tarjeta-restaurante">
        <div class="tarjeta-restaurante__numero">02</div>
        <div>
          <div class="tarjeta-restaurante__encabezado">
            <div class="tarjeta-restaurante__nombre">Fogón Paisa – Centro Histórico</div>
            <span class="tarjeta-restaurante__tipo">Cocina Paisa</span>
          </div>
          <p class="tarjeta-restaurante__descripcion">
            En el costado norte del Parque Principal, frente a la iglesia. Bandeja paisa completa,
            fríjoles con garra, chicharrón crujiente y aguardiente Tapa Roja. Menú del día a buen
            precio, muy frecuentado por locales. Las paredes están decoradas con fotografías
            históricas de Santa Rosa de principios del siglo XX.
          </p>
          <div class="tarjeta-restaurante__meta">
            <span class="tarjeta-restaurante__meta-item">
              <span class="simbolo-material">place</span> Carrera 14 # 13-42, Parque Principal
            </span>
            <span class="tarjeta-restaurante__meta-item">
              <span class="simbolo-material">schedule</span> Lunes a sábado 7 AM – 8 PM
            </span>
            <span class="tarjeta-restaurante__meta-item">
              <span class="simbolo-material">payments</span> Menú del día desde $14.000 COP
            </span>
          </div>
        </div>
      </div>

      <div class="tarjeta-restaurante">
        <div class="tarjeta-restaurante__numero">03</div>
        <div>
          <div class="tarjeta-restaurante__encabezado">
            <div class="tarjeta-restaurante__nombre">Café Origen – Hacienda La Cabaña</div>
            <span class="tarjeta-restaurante__tipo">Café de Especialidad</span>
          </div>
          <p class="tarjeta-restaurante__descripcion">
            La mejor opción para los amantes del café de especialidad. Ubicado en la hacienda
            cafetera a 3 km del centro, ofrece cataciones guiadas, métodos de preparación
            alternativos (Chemex, V60, Aeropress) y maridaje con postres locales como el
            bizcochuelo y el quesillo. Puntuación SCA en sus varietales: 85–88 puntos.
          </p>
          <div class="tarjeta-restaurante__meta">
            <span class="tarjeta-restaurante__meta-item">
              <span class="simbolo-material">place</span> Vereda Cedralito · 3 km centro
            </span>
            <span class="tarjeta-restaurante__meta-item">
              <span class="simbolo-material">schedule</span> Miércoles a domingo 9 AM – 5 PM
            </span>
            <span class="tarjeta-restaurante__meta-item">
              <span class="simbolo-material">payments</span> Catación desde $35.000 COP
            </span>
          </div>
        </div>
      </div>

      <div class="tarjeta-restaurante">
        <div class="tarjeta-restaurante__numero">04</div>
        <div>
          <div class="tarjeta-restaurante__encabezado">
            <div class="tarjeta-restaurante__nombre">Cocinas del Río – Las Margaritas</div>
            <span class="tarjeta-restaurante__tipo">Trucha · Típico</span>
          </div>
          <p class="tarjeta-restaurante__descripcion">
            Un conjunto de cuatro cocinas familiares ubicadas sobre el río San Eugenio antes
            de llegar a las Termales. Cada una tiene su especialidad: trucha frita, trucha en
            salsa de maracuyá, trucha al vapor con hierbas y trucha ahumada. Los precios son
            muy asequibles y el ambiente es auténtico y festivo los fines de semana.
          </p>
          <div class="tarjeta-restaurante__meta">
            <span class="tarjeta-restaurante__meta-item">
              <span class="simbolo-material">place</span> Vía Termales km 5–7
            </span>
            <span class="tarjeta-restaurante__meta-item">
              <span class="simbolo-material">schedule</span> Viernes a domingo 10 AM – 5 PM
            </span>
            <span class="tarjeta-restaurante__meta-item">
              <span class="simbolo-material">payments</span> Platos desde $18.000 COP
            </span>
          </div>
        </div>
      </div>

      <div class="tarjeta-restaurante">
        <div class="tarjeta-restaurante__numero">05</div>
        <div>
          <div class="tarjeta-restaurante__encabezado">
            <div class="tarjeta-restaurante__nombre">Galería Gastronómica – Mercado Central</div>
            <span class="tarjeta-restaurante__tipo">Mercado · Productos Locales</span>
          </div>
          <p class="tarjeta-restaurante__descripcion">
            El Mercado Central de Santa Rosa alberga una galería con puestos de productos frescos:
            chontaduros, guanábanas, maracuyás, tomates de árbol y hortalizas de la región.
            Los sábados hay feria de productores con quesos campesinos, panelas orgánicas,
            dulces de guayaba y artesanías en guadua. El lugar perfecto para llevarse sabores a casa.
          </p>
          <div class="tarjeta-restaurante__meta">
            <span class="tarjeta-restaurante__meta-item">
              <span class="simbolo-material">place</span> Carrera 12, Centro, Santa Rosa de Cabal
            </span>
            <span class="tarjeta-restaurante__meta-item">
              <span class="simbolo-material">schedule</span> Diario 6 AM – 1 PM · Feria sábados
            </span>
            <span class="tarjeta-restaurante__meta-item">
              <span class="simbolo-material">eco</span> Productos agroecológicos certificados
            </span>
          </div>
        </div>
      </div>

    </div>
  </section>

  <!-- ═══════════════════════════════════════════
       ENTRETENIMIENTO
  ═══════════════════════════════════════════ -->
  <section id="entretenimiento" class="seccion seccion--media">
    <div class="seccion__cabecera">
      <div class="seccion__etiqueta">
        <span class="simbolo-material">local_activity</span> Qué hacer
      </div>
      <h2 class="seccion__titulo">Entretenimiento y<br><em>Aventura</em></h2>
      <p class="seccion__descripcion">
        De la naturaleza exuberante a la cultura cafetera: Santa Rosa de Cabal tiene actividades
        para todos los ritmos. Termales, senderismo, parapente y festivales hacen de este municipio
        un destino de clase mundial en el corazón del Eje Cafetero.
      </p>
    </div>

    <div class="entretenimiento__mosaico">

      <!-- Tarjeta destacada grande -->
      <div class="tarjeta-actividad tarjeta-actividad--destacada">
        <div class="tarjeta-actividad__icono">
          <span class="simbolo-material">hot_tub</span>
        </div>
        <h3 class="tarjeta-actividad__titulo">Termales de Santa Rosa de Cabal</h3>
        <p class="tarjeta-actividad__descripcion">
          Las aguas termales son el atractivo estrella del municipio y uno de los más visitados
          de Colombia. Ubicadas a 9 km del centro en el cañón del río San Eugenio, a 1.850 m s. n. m.
          Las piscinas alcanzan los 42 °C y están rodeadas de selva nublada con cascadas naturales.
          Existen dos complejos: Las Termales (más amplio y familiar) y Balnearios El Otoño (más íntimo).
          Ambos cuentan con restaurante, zona de masajes, bar y áreas de descanso. Ideal todo el año
          gracias al microclima. Se recomienda visitar entre semana para disfrutar sin aglomeraciones.
        </p>
        <div class="tarjeta-actividad__etiquetas">
          <span class="etiqueta-actividad">42 °C</span>
          <span class="etiqueta-actividad">Selva nublada</span>
          <span class="etiqueta-actividad">Cascadas</span>
          <span class="etiqueta-actividad">Masajes</span>
          <span class="etiqueta-actividad">Abierto todo el año</span>
        </div>
      </div>

      <!-- Tarjeta normal -->
      <div class="tarjeta-actividad tarjeta-actividad--normal">
        <div class="tarjeta-actividad__icono">
          <span class="simbolo-material">paragliding</span>
        </div>
        <h3 class="tarjeta-actividad__titulo">Parapente sobre el Eje Cafetero</h3>
        <p class="tarjeta-actividad__descripcion">
          Desde la cima del cerro El Nudo (2.300 m s. n. m.) se realizan vuelos biplaza con
          instructores certificados. Vista aérea del municipio, el río Campoalegre y las montañas
          del Parque Nacional Natural Los Nevados. Duración: 15–25 minutos.
        </p>
        <div class="tarjeta-actividad__etiquetas">
          <span class="etiqueta-actividad">+12 años</span>
          <span class="etiqueta-actividad">Biplaza</span>
          <span class="etiqueta-actividad">Desde $80.000 COP</span>
        </div>
      </div>

      <!-- Tarjeta acento -->
      <div class="tarjeta-actividad tarjeta-actividad--acento">
        <div class="tarjeta-actividad__icono">
          <span class="simbolo-material">hiking</span>
        </div>
        <h3 class="tarjeta-actividad__titulo">Senderismo al Cerro Morro Plancho</h3>
        <p class="tarjeta-actividad__descripcion">
          Ruta de 14 km de ida y vuelta, nivel moderado-alto. Atraviesa bosques de niebla,
          páramo y humedales. En la cima (3.200 m s. n. m.) se avista el Nevado Santa Isabel
          en días despejados. Guía local obligatorio.
        </p>
        <div class="tarjeta-actividad__etiquetas">
          <span class="etiqueta-actividad">14 km</span>
          <span class="etiqueta-actividad">6–7 horas</span>
          <span class="etiqueta-actividad">Nivel alto</span>
        </div>
      </div>

      <!-- Tarjeta suave -->
      <div class="tarjeta-actividad tarjeta-actividad--suave">
        <div class="tarjeta-actividad__icono">
          <span class="simbolo-material">coffee</span>
        </div>
        <h3 class="tarjeta-actividad__titulo">Tour de Café en Finca Cafetera</h3>
        <p class="tarjeta-actividad__descripcion">
          Recorrido guiado por fincas cafeteras de la vereda La Hermosa y Cedralito. Aprende
          el proceso completo: siembra, recolección, despulpado, fermentación, secado y tostión.
          Incluye cata de café especial y almuerzo típico.
        </p>
        <div class="tarjeta-actividad__etiquetas">
          <span class="etiqueta-actividad">3 horas</span>
          <span class="etiqueta-actividad">Almuerzo incluido</span>
          <span class="etiqueta-actividad">Todas las edades</span>
        </div>
      </div>

      <!-- Tarjeta normal -->
      <div class="tarjeta-actividad tarjeta-actividad--normal">
        <div class="tarjeta-actividad__icono">
          <span class="simbolo-material">kayaking</span>
        </div>
        <h3 class="tarjeta-actividad__titulo">Rafting y Kayak – Río Campoalegre</h3>
        <p class="tarjeta-actividad__descripcion">
          El río Campoalegre ofrece rápidos de grado II y III, ideales para rafting en familia
          y kayak individual. Empresas locales de aventura ofrecen recorridos de 2 horas con
          todo el equipo incluido. Temporada óptima: abril-mayo y octubre-noviembre.
        </p>
        <div class="tarjeta-actividad__etiquetas">
          <span class="etiqueta-actividad">Grado II–III</span>
          <span class="etiqueta-actividad">2 horas</span>
          <span class="etiqueta-actividad">Desde $65.000 COP</span>
        </div>
      </div>

      <!-- Tarjeta normal -->
      <div class="tarjeta-actividad tarjeta-actividad--normal">
        <div class="tarjeta-actividad__icono">
          <span class="simbolo-material">festival</span>
        </div>
        <h3 class="tarjeta-actividad__titulo">Festival del Folclor y el Anisado</h3>
        <p class="tarjeta-actividad__descripcion">
          Celebrado cada año en agosto, el Festival del Anisado es la fiesta cultural más importante
          del municipio. Incluye desfiles de silleteros, concursos de chirimías, demostraciones de
          destilación artesanal de anisado y muestras gastronómicas en el Parque Principal.
        </p>
        <div class="tarjeta-actividad__etiquetas">
          <span class="etiqueta-actividad">Agosto</span>
          <span class="etiqueta-actividad">Gratuito</span>
          <span class="etiqueta-actividad">Cultural</span>
        </div>
      </div>

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
          <li><a href="#alojamiento">Alojamientos</a></li>
          <li><a href="#transporte">Transporte</a></li>
          <li><a href="#alimentacion">Alimentación</a></li>
          <li><a href="#entretenimiento">Entretenimiento</a></li>
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