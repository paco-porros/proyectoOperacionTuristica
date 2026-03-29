-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 29-03-2026 a las 19:19:29
-- Versión del servidor: 8.4.3
-- Versión de PHP: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `operador_turistico_src`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `galeria_gastronomicos`
--

CREATE TABLE `galeria_gastronomicos` (
  `id` int UNSIGNED NOT NULL,
  `plan_gastronomico_id` int UNSIGNED NOT NULL,
  `url_imagen` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alt_texto` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `orden` tinyint UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `galeria_turisticos`
--

CREATE TABLE `galeria_turisticos` (
  `id` int UNSIGNED NOT NULL,
  `plan_turistico_id` int UNSIGNED NOT NULL,
  `url_imagen` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alt_texto` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `orden` tinyint UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `itinerario_dias`
--

CREATE TABLE `itinerario_dias` (
  `id` int UNSIGNED NOT NULL,
  `plan_turistico_id` int UNSIGNED NOT NULL,
  `numero_dia` tinyint UNSIGNED NOT NULL,
  `titulo` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `itinerario_dias`
--

INSERT INTO `itinerario_dias` (`id`, `plan_turistico_id`, `numero_dia`, `titulo`, `descripcion`) VALUES
(1, 1, 1, 'Llegada y bienvenida termal', 'Traslado desde Santa Rosa de Cabal en jeep Willys por la vía escénica a la Vereda San Ramón. Activación del pasaporte de 4 horas, descenso por el sendero ecológico hasta la cascada Santa Helena y primera inmersión en las piscinas termales a 40 °C. Hidroterapia de contraste con los chorros de agua fría de la quebrada.'),
(2, 2, 1, 'Ascenso al bosque de niebla y aguas termales', 'Salida desde la plaza de mercado de Santa Rosa en jeep Willys hacia el km 17. Recorrido por los senderos del bosque húmedo de niebla hasta las 8 piscinas termales. Tarde libre para explorar el río termal, la piscina de burbujas naturales y los baños turcos con vapor termal.'),
(3, 2, 2, 'Senderismo a cascadas y géiseres', 'Mañana de senderismo por los senderos ecológicos de la reserva hasta las cascadas de agua fría y los géiseres naturales. Avistamiento de aves endémicas del Eje Cafetero. Última sesión de termales antes del descenso al municipio.'),
(4, 3, 1, 'Santa Rosa → Estación Potosí, aclimatación', 'Traslado desde Santa Rosa hasta la Estación Potosí (3.600 m.s.n.m.), principal acceso al PNN Los Nevados por Risaralda. Caminata de aclimatación por páramo y frailejones. Alojamiento en finca de borde de parque.'),
(5, 3, 2, 'Ascenso al Nevado Santa Isabel (5.100 m.s.n.m.)', 'Inicio del ascenso al amanecer con guía certificado por el Parque Nacional. Cruce de páramo húmedo, lagunas glaciares y zona periglaciar hasta el glaciar del Nevado Santa Isabel, el más bajo de Colombia. Descenso en la tarde.'),
(6, 3, 3, 'Laguna del Otún y regreso', 'Excursión opcional a la Laguna del Otún, uno de los espejos de agua más impresionantes de los Andes colombianos. Regreso a Santa Rosa de Cabal con almuerzo campesino en ruta.'),
(7, 4, 1, 'De la mata a la taza — finca cafetera en la cordillera', 'Recogida en el centro de Santa Rosa y traslado a la finca cafetera en la vereda. Recorrido por los cafetales con el caficultor, recolección manual de granos maduros, proceso de beneficio húmedo, secado solar y tueste artesanal. Cata de varietales con catador certificado. Almuerzo campesino con bandeja paisa de finca: frijoles de mata, chicharrón, aguacate, arroz con cilantro y arepa de chócolo.'),
(8, 5, 1, 'Senderismo por bosque nativo hasta la cascada', 'Encuentro en el parque principal de Santa Rosa. Traslado a la entrada del sendero. Caminata de ascenso de 1h 30 min por bosque nativo con avistamiento de tangaras, tucanes y otras aves endémicas del Eje Cafetero. Llegada a la Cascada La Leona (80 m), descanso y refrigerio. Descenso y traslado de regreso al municipio.'),
(9, 6, 1, 'Casco histórico y degustación gastronómica', 'Recorrido guiado a pie por el Parque Las Araucarias, la Basílica Menor Nuestra Señora de las Victorias (joya neogótica en madera), el Parque del Machete con su monumento a la cultura cafetera y el mercado campesino. Degustación de chorizo santarrosano artesanal con papa criolla, ají casero y agua de panela con queso en los mejores establecimientos del municipio. Historia y tradición del embutido más famoso de Colombia.'),
(10, 7, 1, 'Termales San Vicente — bosque de niebla', 'Llegada a Santa Rosa de Cabal y traslado en jeep Willys hasta la Reserva Termal San Vicente (km 17). Tarde de inmersión en las 8 piscinas termales, río termal y piscina de burbujas. Cena y alojamiento en la reserva.'),
(11, 7, 2, 'Finca cafetera y ruta del chorizo santarrosano', 'Mañana en finca cafetera de la vereda con recorrido y cata de café de origen. Regreso al casco urbano para la ruta gastronómica del chorizo santarrosano, visita a la Basílica y el Parque Las Araucarias.'),
(12, 7, 3, 'Senderismo Los Nevados — estación Potosí', 'Excursión de día completo hacia la estación Potosí del PNN Los Nevados. Caminata por páramo con frailejones gigantes, avistamiento de fauna de alta montaña y vistas al glaciar del Nevado Santa Isabel. Regreso a Santa Rosa al atardecer.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `planes_gastronomicos`
--

CREATE TABLE `planes_gastronomicos` (
  `id` int UNSIGNED NOT NULL,
  `restaurante_id` int UNSIGNED NOT NULL,
  `titulo` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `etiqueta` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `categoria` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `precio_desde` decimal(10,2) NOT NULL,
  `moneda` char(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'COP',
  `duracion_horas` decimal(4,1) DEFAULT NULL,
  `max_personas` tinyint UNSIGNED DEFAULT NULL,
  `idiomas` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `puntuacion` decimal(3,1) NOT NULL DEFAULT '5.0',
  `total_resenas` int UNSIGNED NOT NULL DEFAULT '0',
  `imagen_hero_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` enum('activo','inactivo','borrador') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'activo',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `planes_gastronomicos`
--

INSERT INTO `planes_gastronomicos` (`id`, `restaurante_id`, `titulo`, `descripcion`, `etiqueta`, `categoria`, `precio_desde`, `moneda`, `duracion_horas`, `max_personas`, `idiomas`, `puntuacion`, `total_resenas`, `imagen_hero_url`, `estado`, `created_at`, `updated_at`) VALUES
(1, 1, 'Chorizo Santarrosano a la Parrilla', 'El emblema gastronómico de Santa Rosa de Cabal. Chorizo artesanal de cerdo preparado con carne magra picada a mano, aliñada con especias tradicionales y cocida lentamente a la parrilla de leña. Se sirve acompañado de papa criolla chorreada, ají casero de tomate y aguacate hass de la región. Una tradición que ha convertido a Santa Rosa en la capital del chorizo en Colombia.', 'Típico', 'Plato fuerte', 18000.00, 'COP', NULL, NULL, 'Español', 4.9, 198, '/img/Chorizo-Santarrosano.jpg', 'activo', '2026-03-26 23:59:04', '2026-03-29 18:32:02'),
(2, 2, 'Bandeja Paisa Completa', 'La reina de la gastronomía antioqueña en su versión más auténtica. Frijoles rojos de mata, chicharrón crujiente, carne molida sofrita con hogao, huevo frito, chorizo santarrosano, morcilla artesanal, arroz blanco, aguacate de la región, tajadas de plátano maduro y arepa de maíz pelao. Una experiencia cultural y gastronómica que refleja la identidad de todo el Eje Cafetero.', 'Insignia', 'Tradición', 28000.00, 'COP', NULL, NULL, 'Español', 4.8, 276, '/img/Bandeja-Paisa.jpg', 'activo', '2026-03-26 23:59:04', '2026-03-29 18:32:02'),
(3, 3, 'Tabla de Chorizos Artesanales Santarrosanos', 'La mejor selección de chorizos artesanales del municipio en una sola tabla: chorizo tradicional de cerdo, chorizo con queso campesino, longaniza especiada y morcilla de arroz. Todos elaborados con receta familiar transmitida por tres generaciones. Acompañados de pan de bono recién horneado, papa chorreada, hogao casero y un surtido de ajíes de la huerta propia.', 'Especial', 'Tabla / Degustación', 32000.00, 'COP', NULL, 6, 'Español', 5.0, 154, '/img/tabla-chorizo.webp', 'activo', '2026-03-26 23:59:04', '2026-03-29 18:32:02'),
(4, 4, 'Trucha del Río San Ramón al Ajillo', 'Trucha arcoíris de los ríos fríos que bajan del Parque Nacional Los Nevados, preparada al ajillo con mantequilla de campo, ajo soasado, hierbas aromáticas frescas y chorrito de limón de Castilla. Acompañada de patacones artesanales, ensalada de repollo morado con zanahoria rallada y arroz con cilantro. El plato más fotografiado de Santa Rosa de Cabal entre viajeros internacionales.', 'Típico', 'Plato fuerte', 35000.00, 'COP', NULL, NULL, 'Español, Inglés', 4.9, 312, '/img/Trucha-Ajillo.jpg', 'activo', '2026-03-26 23:59:04', '2026-03-29 18:32:02'),
(5, 5, 'Mazamorra Chiquita con Panela y Café de Altura', 'El postre más tradicional de la región cafetera: mazamorra chiquita de maíz peto servida en porrón de barro, acompañada de melado de panela artesanal, queso campesino fresco y un vaso de leche fría de vaca pastoreada. Para culminar, taza de café de altura 100 % arábica de fincas de la Vereda El Cairo, preparado en chorreado artesanal con filtro de tela. Tradición pura del Paisaje Cultural Cafetero.', 'Tradición', 'Postre / Bebida', 12000.00, 'COP', NULL, NULL, 'Español', 4.8, 187, '/img/mazamorra-chiquita.jpg', 'activo', '2026-03-26 23:59:04', '2026-03-29 18:32:02'),
(6, 8, 'Tour Cafetero & Almuerzo Campesino de Finca', 'Vive el proceso completo del café de origen en una finca cafetera de la cordillera Central: recolección manual en los cafetales, beneficio húmedo, secado en zaranda y tueste artesanal en paila. La experiencia incluye cata de variedades caturra, castillo y geisha con catador local, y culmina con almuerzo campesino: sopa de verduras de huerta, bandeja con trucha del río, frijoles de mata, arepa de chócolo horneada en hoja y jugo de mora de castilla.', 'Experiencia', 'Experiencia', 85000.00, 'COP', 4.0, 12, 'Español, Inglés', 5.0, 89, '/img/Tour-Cafetero11.png', 'activo', '2026-03-26 23:59:04', '2026-03-29 18:32:02'),
(7, 9, 'Trucha a la Plancha en la Reserva San Vicente', 'Un almuerzo único rodeado de bosque de niebla a 2.300 metros de altura. Trucha arcoíris a la plancha con hierbas del bosque, servida con arroz de la casa, ensalada fresca de la huerta propia de la reserva y sopa del día. El restaurante del Hotel San Vicente tiene capacidad para 900 personas y vista directa a los senderos termales. La combinación perfecta entre bienestar y gastronomía en medio de la naturaleza.', 'Naturaleza', 'Plato fuerte', 38000.00, 'COP', NULL, NULL, 'Español', 4.7, 143, '/img/Trucha-Vicente.png', 'activo', '2026-03-26 23:59:04', '2026-03-29 18:32:02'),
(8, 10, 'Sancocho de Gallina Criolla a la Usanza Cafetera', 'Sancocho elaborado con gallina criolla de campo cocida a fuego lento durante más de tres horas con plátano verde de la región, yuca recién pelada, papa pastusa, mazorca tierna y un sofrito de hogao con cilantro cimarrón. Se sirve con arroz blanco, aguacate y ají de maní. Una receta transmitida de generación en generación en las cocinas de las fincas cafeteras de Risaralda.', 'Típico', 'Plato fuerte', 30000.00, 'COP', NULL, NULL, 'Español', 4.8, 221, '/img/sancocho-de-gallina.gif', 'activo', '2026-03-26 23:59:04', '2026-03-29 18:32:02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `planes_turisticos`
--

CREATE TABLE `planes_turisticos` (
  `id` int UNSIGNED NOT NULL,
  `titulo` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ubicacion` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `duracion_dias` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `precio_desde` decimal(12,2) NOT NULL,
  `moneda` char(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'COP',
  `etiqueta` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `puntuacion` decimal(3,1) NOT NULL DEFAULT '5.0',
  `total_resenas` int UNSIGNED NOT NULL DEFAULT '0',
  `imagen_hero_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` enum('activo','inactivo','borrador') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'activo',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `planes_turisticos`
--

INSERT INTO `planes_turisticos` (`id`, `titulo`, `descripcion`, `ubicacion`, `duracion_dias`, `precio_desde`, `moneda`, `etiqueta`, `puntuacion`, `total_resenas`, `imagen_hero_url`, `estado`, `created_at`, `updated_at`) VALUES
(1, 'Día de Termales en Santa Rosa de Cabal', 'Sumérgete en las aguas volcánicas del complejo Termales Santa Rosa de Cabal, ubicado a 9 km del casco urbano en la Vereda San Ramón. Las aguas brotan a 70 °C del sistema volcánico del Parque Nacional Natural Los Nevados y descienden por la cascada Santa Helena hasta piscinas termales a 40 °C. Incluye pasaporte de 4 horas, acceso a la cascada de agua caliente y fría para contraste térmico, y sendero ecológico por la cordillera Central. Opción de masajes relajantes y spa adicional.', 'Km 9 Vereda San Ramón, Santa Rosa de Cabal, Risaralda', 1, 53000.00, 'COP', 'Bienestar', 4.8, 312, '/img/termales-santa-rosa.jpg', 'activo', '2026-03-26 23:59:04', '2026-03-29 18:08:10'),
(2, 'Reserva Termal San Vicente — Experiencia Natural', 'La experiencia termal más natural de Colombia, a 2.300 metros de altura en un bosque húmedo de niebla de más de 400 hectáreas. La Reserva San Vicente, ubicada en el km 17 vía Los Nevados, cuenta con 8 piscinas termales de distinta composición mineral, 3 baños turcos con vapor termal, una piscina de burbujas naturales y un río termal único en el Eje Cafetero. Senderos ecológicos conducen a cascadas de agua fría y géiseres naturales. El silencio, las aves endémicas y el bosque de niebla hacen de este lugar un santuario de bienestar incomparable.', 'Km 17 Vereda Potreros, Santa Rosa de Cabal, Risaralda', 2, 90000.00, 'COP', 'Ecoturismo', 4.9, 478, '/img/termales-san-vicente.webp', 'activo', '2026-03-26 23:59:04', '2026-03-29 18:08:10'),
(3, 'Expedición al Nevado Santa Isabel — Los Nevados', 'Santa Rosa de Cabal es la puerta de entrada al Parque Nacional Natural Los Nevados por la estación Potosí, el mejor punto de partida para la travesía al Nevado Santa Isabel y su glaciar de montaña, el de menor altitud del país. La ruta asciende por páramo, frailejones y lagunas de ensueño hasta los 5.100 m.s.n.m. Un recorrido de alta exigencia para amantes del senderismo y la montaña, con guía certificado por el PNN y todo el equipo necesario.', 'Estación Potosí — Parque Nacional Natural Los Nevados, Risaralda', 3, 320000.00, 'COP', 'Aventura', 4.9, 187, '/img/nevados.webp', 'activo', '2026-03-26 23:59:04', '2026-03-29 18:08:10'),
(4, 'Tour Cafetero en Finca de la Cordillera Central', 'Recorre una auténtica finca cafetera en las laderas de la cordillera Central de Risaralda. Aprende todo el proceso del café de origen: siembra, recolección a mano, beneficio, secado y tueste artesanal. Incluye cata guiada de varietales de altura con perfiles de sabor diferenciados, recorrido por los cafetales con vista panorámica del Eje Cafetero y almuerzo campesino con productos de la finca. Una experiencia inscrita en el Paisaje Cultural Cafetero, Patrimonio de la Humanidad.', 'Vereda El Cairo, Santa Rosa de Cabal, Risaralda', 1, 85000.00, 'COP', 'Cultura', 5.0, 264, '/img/cordellera_central.webp', 'activo', '2026-03-26 23:59:04', '2026-03-29 18:08:20'),
(5, 'Senderismo a la Cascada La Leona', 'Caminata ecológica de dificultad media-alta por los bosques nativos que rodean Santa Rosa de Cabal hasta la Cascada La Leona, un salto de agua de más de 80 metros en medio de selva nublada. El sendero atraviesa zonas de avistamiento de aves endémicas del Eje Cafetero y riachuelos cristalinos. Duración aproximada: 1h 30 min de ascenso. Guía local especializado, hidratación y seguro incluidos.', 'Zona rural, Santa Rosa de Cabal, Risaralda', 1, 55000.00, 'COP', 'Naturaleza', 4.7, 143, '/img/senderismo.webp', 'activo', '2026-03-26 23:59:04', '2026-03-29 18:08:20'),
(6, 'Ruta del Chorizo Santarrosano y Casco Histórico', 'Un recorrido a pie por el corazón del municipio visitando los lugares más emblemáticos: el Parque Las Araucarias, la Basílica Menor de Nuestra Señora de las Victorias (arquitectura neogótica en madera), el Parque del Machete y la Plaza de Mercado. La experiencia culmina con una degustación guiada del chorizo santarrosano, el embutido más famoso de Colombia, en los mejores establecimientos tradicionales del municipio.', 'Casco urbano, Santa Rosa de Cabal, Risaralda', 1, 35000.00, 'COP', 'Gastronómico', 4.8, 219, '/img/ruta_chorizo.jpg', 'activo', '2026-03-26 23:59:04', '2026-03-29 18:08:20'),
(7, 'Santa Rosa Completa — Termales, Nevados y Café', 'El plan definitivo para conocer todo lo que Santa Rosa de Cabal tiene para ofrecer. Tres días que combinan el bienestar de las aguas termales de San Vicente, la aventura en el Parque Nacional Los Nevados por la ruta Potosí, el recorrido por fincas cafeteras del Paisaje Cultural Cafetero y la gastronomía típica del municipio. Incluye alojamiento en hotel de la zona, transporte en jeep Willys por las rutas veredales y guías locales certificados.', 'Santa Rosa de Cabal y alrededores, Risaralda', 3, 480000.00, 'COP', 'Premium', 5.0, 96, '/img/sct.png', 'activo', '2026-03-26 23:59:04', '2026-03-29 18:08:20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plan_incluye`
--

CREATE TABLE `plan_incluye` (
  `id` int UNSIGNED NOT NULL,
  `plan_turistico_id` int UNSIGNED NOT NULL,
  `descripcion` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `icono` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `plan_incluye`
--

INSERT INTO `plan_incluye` (`id`, `plan_turistico_id`, `descripcion`, `icono`) VALUES
(1, 1, 'Pasaporte termal 4 horas (acceso a todas las piscinas y cascada)', 'water'),
(2, 1, 'Transporte ida y vuelta desde el municipio en jeep Willys', 'directions_car'),
(3, 1, 'Guía local en el recorrido del sendero ecológico', 'person'),
(4, 2, 'Pasaporte Bienestar (8 termas, 2 cabinas de vapor, río termal)', 'spa'),
(5, 2, 'Alojamiento 1 noche en cabaña de la reserva', 'hotel'),
(6, 2, 'Transporte ida y vuelta en jeep Willys desde plaza de mercado', 'directions_car'),
(7, 2, 'Senderismo guiado a cascadas y géiseres', 'hiking'),
(8, 3, 'Guía certificado por el Parque Nacional Natural Los Nevados', 'verified'),
(9, 3, 'Equipo de alta montaña (bastones, crampones básicos)', 'backpack'),
(10, 3, 'Alojamiento 2 noches en finca de borde de parque', 'cabin'),
(11, 3, 'Traslados terrestres desde Santa Rosa de Cabal', 'directions_car'),
(12, 3, 'Alimentación completa durante el recorrido', 'restaurant'),
(13, 4, 'Recorrido guiado por cafetales con caficultor local', 'local_cafe'),
(14, 4, 'Cata de café de origen con catador certificado', 'coffee'),
(15, 4, 'Almuerzo campesino tradicional en la finca', 'restaurant'),
(16, 4, 'Transporte ida y vuelta desde el municipio', 'directions_car'),
(17, 5, 'Guía local especializado en flora y fauna nativa', 'forest'),
(18, 5, 'Kit de hidratación durante el senderismo', 'water_bottle'),
(19, 5, 'Seguro de asistencia médica básica en montaña', 'health_and_safety'),
(20, 6, 'Guía gastronómico local con historia del chorizo santarrosano', 'person'),
(21, 6, 'Degustación en 3 establecimientos tradicionales del municipio', 'restaurant_menu'),
(22, 6, 'Recorrido por los 4 monumentos principales del casco histórico', 'place'),
(23, 7, 'Alojamiento 2 noches (1 en Termales San Vicente, 1 en hotel)', 'hotel'),
(24, 7, 'Todos los traslados en jeep Willys por rutas veredales', 'directions_car'),
(25, 7, 'Guías locales certificados para cada actividad', 'group'),
(26, 7, 'Alimentación: desayunos, 2 almuerzos y 1 cena incluidos', 'restaurant'),
(27, 7, 'Pasaporte Bienestar San Vicente + cata de café', 'star');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `platos`
--

CREATE TABLE `platos` (
  `id` int UNSIGNED NOT NULL,
  `plan_gastronomico_id` int UNSIGNED NOT NULL,
  `titulo` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `imagen_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `orden` tinyint UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `platos`
--

INSERT INTO `platos` (`id`, `plan_gastronomico_id`, `titulo`, `descripcion`, `imagen_url`, `orden`) VALUES
(1, 3, 'Chorizo Tradicional de Cerdo', 'Chorizo artesanal elaborado con carne de cerdo magra picada a mano, poco aliño y mucho amor. Cocido a la parrilla de leña hasta dorar la tripa natural. Jugoso y con sabor característico de Santa Rosa de Cabal.', NULL, 1),
(2, 3, 'Chorizo con Queso Campesino', 'Variante especial del chorizo santarrosano relleno con queso campesino fresco de la región. Al calor de la parrilla el queso se derrite creando una combinación irresistible.', NULL, 2),
(3, 3, 'Longaniza Especiada de la Casa', 'Longaniza de cerdo con mezcla secreta de especias locales: comino, pimienta negra, clavo y nuez moscada. Más larga y delgada que el chorizo tradicional, con textura firme y aroma intenso.', NULL, 3),
(4, 3, 'Morcilla de Arroz Artesanal', 'Morcilla elaborada con sangre de cerdo, arroz y especias naturales, embutida en tripa natural. Acompañada de papa criolla chorreada con hogao de la casa.', NULL, 4),
(5, 6, 'Cata de Café Caturra de Altura', 'Varietal caturra cultivado a 1.800 m.s.n.m. en la Vereda El Cairo. Perfil de taza: acidez cítrica de naranja, cuerpo medio y notas dulces de caramelo en el retrogusto. Preparado en chorreado artesanal.', NULL, 1),
(6, 6, 'Cata de Café Castillo Especial', 'Castillo colombiano de la misma finca, con proceso de fermentación controlada. Perfil de taza: equilibrado, acidez suave, notas de chocolate oscuro y frutos secos.', NULL, 2),
(7, 6, 'Sopa de Verduras de Huerta', 'Caldo de verduras frescas de la huerta propia de la finca: cilantro, cebolla cabezona, papa criolla, zanahoria y mazorca. Primer tiempo del almuerzo campesino.', NULL, 3),
(8, 6, 'Bandeja Campesina de Finca', 'Plato fuerte del almuerzo: trucha del río al ajillo, frijoles de mata recién cocinados, arroz de la finca, aguacate de cosecha propia, tajadas de plátano maduro y arepa de chócolo horneada en hoja de mazorca.', NULL, 4),
(9, 6, 'Jugo de Mora de Castilla', 'Jugo natural de mora de castilla cultivada en las laderas de la finca, sin conservantes ni azúcar añadida. Servido frío en vaso de vidrio con panela molida para endulzar al gusto.', NULL, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resenas`
--

CREATE TABLE `resenas` (
  `id` int UNSIGNED NOT NULL,
  `usuario_id` int UNSIGNED NOT NULL,
  `tipo_plan` enum('turistico','gastronomico') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `plan_turistico_id` int UNSIGNED DEFAULT NULL,
  `plan_gastronomico_id` int UNSIGNED DEFAULT NULL,
  `puntuacion` tinyint UNSIGNED NOT NULL,
  `comentario` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `autor_nombre` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `autor_cargo` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` enum('publicado','pendiente','rechazado') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendiente',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `resenas`
--

INSERT INTO `resenas` (`id`, `usuario_id`, `tipo_plan`, `plan_turistico_id`, `plan_gastronomico_id`, `puntuacion`, `comentario`, `autor_nombre`, `autor_cargo`, `estado`, `created_at`) VALUES
(1, 3, 'turistico', 2, NULL, 5, 'Los Termales San Vicente están a otro nivel. Las piscinas son más prolijas, el agua más calentita y limpia, y el ambiente es mucho más tranquilo. Sentirse a 2.300 metros en ese bosque de niebla es una experiencia que no tiene precio.', 'Valentina O.', 'Viajera colombiana', 'publicado', '2026-03-26 23:59:05'),
(2, 4, 'turistico', 1, NULL, 5, 'Todo está rodeado de naturaleza. La cascada Santa Helena es simplemente impresionante. La caminata de 10 minutos por el sendero hasta las aguas termales ya vale el viaje. Llevar ropa extra porque el frío sorprende.', 'Andrés M.', 'Turista de Bogotá', 'publicado', '2026-03-26 23:59:05'),
(3, 2, 'turistico', 3, NULL, 5, 'Subir al Nevado Santa Isabel desde Potosí es una experiencia de vida. El guía conocía cada centímetro del páramo. Los frailejones gigantes y la laguna glaciar antes del glaciar son alucinantes. Totalmente recomendado.', 'Carlos A.', 'Senderista certificado', 'publicado', '2026-03-26 23:59:05'),
(4, 5, 'turistico', 4, NULL, 5, 'La finca cafetera superó todas mis expectativas. El caficultor explicó cada paso con una pasión increíble. La cata de variedades fue reveladora y el almuerzo campesino con frijoles de mata y arepa de chócolo fue lo mejor que he comido en años.', 'Luisa R.', 'Viajera gastronómica', 'publicado', '2026-03-26 23:59:05'),
(5, 3, 'turistico', 6, NULL, 5, 'La ruta del chorizo es imperdible si visitas Santa Rosa. El guía contó toda la historia del embutido y la degustaciónón en tres sitios diferentes mostró que cada lugar tiene su estilo propio. El del Chorizo de Oro es el mejor que he probado en Colombia.', 'Juliana V.', 'Crítica gastronómica', 'publicado', '2026-03-26 23:59:05'),
(6, 2, 'gastronomico', NULL, 4, 5, 'La trucha del Río San Ramón al ajillo en Raíces Santarrosanas es extraordinaria. Se nota que el pescado es fresco, recién llegado del río. Los patacones artesanales y el ají casero la complementan de manera perfecta.', 'Carlos A.', 'Editor del operador', 'publicado', '2026-03-26 23:59:05'),
(7, 5, 'gastronomico', NULL, 1, 5, 'El chorizo santarrosano de El Ciervo Rojo es el mejor que he probado en toda Colombia. Jugoso, con un sabor único que no encuentras en ningún otro lugar. La papa criolla chorreada y el aguacate de la región lo hacen un plato completo.', 'Luisa F. R.', 'Viajera gastronómica', 'publicado', '2026-03-26 23:59:05'),
(8, 4, 'gastronomico', NULL, 3, 5, 'La tabla de chorizos artesanales del Chorizo de Oro merece cinco estrellas por donde se le mire. La morcilla de arroz es una revelación. Llevé a mis amigos internacionales y quedaron fascinados.', 'Alejandro G.', 'Guía turístico local', 'publicado', '2026-03-26 23:59:05'),
(9, 3, 'gastronomico', NULL, 6, 5, 'El tour cafetero en la finca El Descanso fue la experiencia más completa de mi vida cafetera. Recolectar el grano, ver el proceso y luego catarlo con el mismo caficultor que lo sembró... eso no tiene precio. El almuerzo campesino de cierre fue perfecto.', 'Valentina O.', 'Aficionada al café', 'publicado', '2026-03-26 23:59:05'),
(10, 2, 'gastronomico', NULL, 8, 5, 'El sancocho de gallina criolla en Termales Santa Rosa es de otro mundo. Después de una tarde en las aguas termales, ese caldo reconstituyente con gallina de campo, yuca y plátano te devuelve la vida. Receta auténtica que no ha cambiado en décadas.', 'Carlos A.', 'Editor turístico', 'publicado', '2026-03-26 23:59:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id` int UNSIGNED NOT NULL,
  `usuario_id` int UNSIGNED NOT NULL,
  `tipo_plan` enum('turistico','gastronomico') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `plan_turistico_id` int UNSIGNED DEFAULT NULL,
  `plan_gastronomico_id` int UNSIGNED DEFAULT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date DEFAULT NULL,
  `num_adultos` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `precio_total` decimal(12,2) NOT NULL,
  `moneda` char(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'COP',
  `estado` enum('pendiente','confirmada','cancelada','completada') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendiente',
  `notas` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`id`, `usuario_id`, `tipo_plan`, `plan_turistico_id`, `plan_gastronomico_id`, `fecha_inicio`, `fecha_fin`, `num_adultos`, `precio_total`, `moneda`, `estado`, `notas`, `created_at`, `updated_at`) VALUES
(1, 3, 'turistico', 2, NULL, '2026-04-12', '2026-04-13', 2, 180000.00, 'COP', 'confirmada', NULL, '2026-03-26 23:59:05', '2026-03-26 23:59:05'),
(2, 5, 'turistico', 4, NULL, '2026-04-20', '2026-04-20', 4, 340000.00, 'COP', 'pendiente', NULL, '2026-03-26 23:59:05', '2026-03-26 23:59:05'),
(3, 4, 'turistico', 3, NULL, '2026-05-03', '2026-05-05', 2, 640000.00, 'COP', 'confirmada', NULL, '2026-03-26 23:59:05', '2026-03-26 23:59:05'),
(4, 2, 'gastronomico', NULL, 6, '2026-04-15', '2026-04-15', 2, 170000.00, 'COP', 'confirmada', NULL, '2026-03-26 23:59:05', '2026-03-26 23:59:05'),
(5, 5, 'gastronomico', NULL, 4, '2026-04-18', '2026-04-18', 3, 105000.00, 'COP', 'pendiente', NULL, '2026-03-26 23:59:05', '2026-03-26 23:59:05'),
(6, 3, 'turistico', 7, NULL, '2026-06-01', '2026-06-03', 2, 960000.00, 'COP', 'pendiente', NULL, '2026-03-26 23:59:05', '2026-03-26 23:59:05'),
(7, 7, 'turistico', 2, NULL, '2026-03-29', NULL, 2, 180000.00, 'COP', 'pendiente', NULL, '2026-03-29 02:20:37', '2026-03-29 02:20:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `restaurantes`
--

CREATE TABLE `restaurantes` (
  `id` int UNSIGNED NOT NULL,
  `nombre` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `direccion` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icono` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` enum('activo','inactivo') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'activo',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `restaurantes`
--

INSERT INTO `restaurantes` (`id`, `nombre`, `descripcion`, `direccion`, `icono`, `logo_url`, `tipo`, `estado`, `created_at`) VALUES
(1, 'El Ciervo Rojo', 'Restaurante emblemático del centro de Santa Rosa de Cabal, reconocido por su sancocho de gallina criolla, trucha al ajillo y los auténticos chorizos santarrosanos a la parrilla. Ambiente familiar y servicio cálido.', 'Centro histórico, Santa Rosa de Cabal, Risaralda', 'restaurant', NULL, 'Restaurante', 'activo', '2026-03-26 23:59:04'),
(2, 'Restaurante La Fogata', 'Conocido por su ambiente rústico y acogedor. Especialista en chorizo santarrosano, bandeja paisa completa y carnes a la parrilla con leña. Uno de los más concurridos del municipio.', 'Santa Rosa de Cabal, Risaralda', 'local_fire_department', NULL, 'Restaurante', 'activo', '2026-03-26 23:59:04'),
(3, 'Taberna El Chorizo de Oro', 'Considerada por los lugareños como la mejor chorizería de la región. Sus chorizos artesanales de cerdo con especias secretas, acompañados de papa criolla y ají, son la insignia gastronómica del Eje Cafetero.', 'Santa Rosa de Cabal, Risaralda', 'restaurant_menu', NULL, 'Chorizería / Taberna', 'activo', '2026-03-26 23:59:04'),
(4, 'Raíces Santarrosanas', 'Propuesta de cocina local de autor con ingredientes de finca. Especialidad en trucha del río San Ramón, morcilla casera y mazamorra con panela. Muy recomendado por visitantes internacionales.', 'Cl. 12 #14-21, Santa Rosa de Cabal, Risaralda', 'eco', NULL, 'Restaurante', 'activo', '2026-03-26 23:59:04'),
(5, 'La Pastelería César Restrepo', 'Pastelería y restaurante de referencia en el municipio. Reconocida por sus pasteles artesanales, postres tradicionales, almuerzo corriente con sopa, plato fuerte y postre. Precios accesibles.', 'Cra. 14 #13-55, Santa Rosa de Cabal, Risaralda', 'cake', NULL, 'Pastelería / Restaurante', 'activo', '2026-03-26 23:59:04'),
(6, 'Choripaco', 'Puesto gastronómico de tradición familiar, citado en guías especializadas como uno de los mejores para probar chorizos santarrosanos frescos directamente de la parrilla.', 'Plaza de mercado, Santa Rosa de Cabal, Risaralda', 'outdoor_grill', NULL, 'Puesto / Chorizo', 'activo', '2026-03-26 23:59:04'),
(7, 'Parador Don Julio', 'Especializado en carnes a la parrilla, costillas de cerdo y lechona tolimense. Ambiente de finca, vista a la cordillera y porciones generosas. Ideal para grupos y familias.', 'Vía a los Termales, Santa Rosa de Cabal, Risaralda', 'grill_dining', NULL, 'Parador / Carnes', 'activo', '2026-03-26 23:59:04'),
(8, 'Finca Cafetera El Descanso', 'Finca productora de café de origen en las laderas de la cordillera Central. Ofrece recorridos guiados por el proceso del café, catas de varietales y almuerzo campesino tradicional.', 'Vereda El Cairo, Santa Rosa de Cabal, Risaralda', 'local_cafe', NULL, 'Finca / Tour Cafetero', 'activo', '2026-03-26 23:59:04'),
(9, 'Restaurante Termales San Vicente', 'Restaurante dentro de la Reserva Termal San Vicente, km 17 vía Los Nevados. Capacidad para 900 personas por día. Especialidad en trucha a la plancha, sancocho y platos típicos de la región cafetera.', 'Km 17 Vereda Potreros, Santa Rosa de Cabal, Risaralda', 'spa', NULL, 'Restaurante / Resort', 'activo', '2026-03-26 23:59:04'),
(10, 'Restaurante Termales Santa Rosa', 'Restaurante del complejo Termales Santa Rosa de Cabal (Km 9 Vereda San Ramón). Buffet y carta con platos regionales: trucha, bandeja paisa y chorizos. Rodeado de montañas y cascadas del Paisaje Cultural Cafetero.', 'Km 9 Vereda San Ramón, Santa Rosa de Cabal, Risaralda', 'water', NULL, 'Restaurante / Resort', 'activo', '2026-03-26 23:59:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int UNSIGNED NOT NULL,
  `nombre` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contrasena` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rol` enum('admin','editor','viewer','cliente') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cliente',
  `estado` enum('activo','inactivo') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'activo',
  `avatar_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `contrasena`, `rol`, `estado`, `avatar_url`, `created_at`, `updated_at`) VALUES
(2, 'Carlos Arenas', 'carlos@srcabal.com', '$2y$12$placeholderHashEditor01', 'editor', 'activo', NULL, '2026-03-26 23:59:04', '2026-03-26 23:59:04'),
(3, 'Valentina Ospina', 'valen@srcabal.com', '$2y$12$placeholderHashEditor02', 'editor', 'activo', NULL, '2026-03-26 23:59:04', '2026-03-26 23:59:04'),
(4, 'Alejandro Gómez', 'ale@srcabal.com', '$2y$12$placeholderHashViewer01', 'viewer', 'inactivo', NULL, '2026-03-26 23:59:04', '2026-03-26 23:59:04'),
(5, 'Luisa Fernanda Restrepo', 'luisa@srcabal.com', '$2y$12$placeholderHashViewer02', 'viewer', 'activo', NULL, '2026-03-26 23:59:04', '2026-03-26 23:59:04'),
(6, 'Victor', 'victor@gei.com', '81dc9bdb52d04dc20036dbd8313ed055', 'cliente', 'activo', NULL, '2026-03-27 00:03:33', '2026-03-27 00:03:33'),
(7, 'jesus garcia', 'jesuu150@gmail.com', '$2y$10$3q0zaQk3dGwuil.zgMjHl.OzbuP2Daxj/YM4cKkSyfMeG5cFtsRG.', 'cliente', 'activo', NULL, '2026-03-29 02:20:12', '2026-03-29 02:20:12');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `galeria_gastronomicos`
--
ALTER TABLE `galeria_gastronomicos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_galg_pgast` (`plan_gastronomico_id`);

--
-- Indices de la tabla `galeria_turisticos`
--
ALTER TABLE `galeria_turisticos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_galt_plan` (`plan_turistico_id`);

--
-- Indices de la tabla `itinerario_dias`
--
ALTER TABLE `itinerario_dias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_itin_plan` (`plan_turistico_id`);

--
-- Indices de la tabla `planes_gastronomicos`
--
ALTER TABLE `planes_gastronomicos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pg_estado` (`estado`),
  ADD KEY `idx_pg_restaurante` (`restaurante_id`);

--
-- Indices de la tabla `planes_turisticos`
--
ALTER TABLE `planes_turisticos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pt_estado` (`estado`);

--
-- Indices de la tabla `plan_incluye`
--
ALTER TABLE `plan_incluye`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pinc_plan` (`plan_turistico_id`);

--
-- Indices de la tabla `platos`
--
ALTER TABLE `platos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_plat_pgast` (`plan_gastronomico_id`);

--
-- Indices de la tabla `resenas`
--
ALTER TABLE `resenas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_res_usr` (`usuario_id`),
  ADD KEY `fk_res_tur` (`plan_turistico_id`),
  ADD KEY `fk_res_gas` (`plan_gastronomico_id`),
  ADD KEY `idx_res_tipo` (`tipo_plan`,`estado`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_rsv_tur` (`plan_turistico_id`),
  ADD KEY `fk_rsv_gas` (`plan_gastronomico_id`),
  ADD KEY `idx_rsv_usuario` (`usuario_id`,`estado`),
  ADD KEY `idx_rsv_fecha` (`fecha_inicio`);

--
-- Indices de la tabla `restaurantes`
--
ALTER TABLE `restaurantes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_email` (`email`),
  ADD KEY `idx_usr_rol` (`rol`,`estado`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `galeria_gastronomicos`
--
ALTER TABLE `galeria_gastronomicos`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `galeria_turisticos`
--
ALTER TABLE `galeria_turisticos`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `itinerario_dias`
--
ALTER TABLE `itinerario_dias`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `planes_gastronomicos`
--
ALTER TABLE `planes_gastronomicos`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `planes_turisticos`
--
ALTER TABLE `planes_turisticos`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `plan_incluye`
--
ALTER TABLE `plan_incluye`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `platos`
--
ALTER TABLE `platos`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `resenas`
--
ALTER TABLE `resenas`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `restaurantes`
--
ALTER TABLE `restaurantes`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `galeria_gastronomicos`
--
ALTER TABLE `galeria_gastronomicos`
  ADD CONSTRAINT `fk_galg_pgast` FOREIGN KEY (`plan_gastronomico_id`) REFERENCES `planes_gastronomicos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `galeria_turisticos`
--
ALTER TABLE `galeria_turisticos`
  ADD CONSTRAINT `fk_galt_plan` FOREIGN KEY (`plan_turistico_id`) REFERENCES `planes_turisticos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `itinerario_dias`
--
ALTER TABLE `itinerario_dias`
  ADD CONSTRAINT `fk_itin_plan` FOREIGN KEY (`plan_turistico_id`) REFERENCES `planes_turisticos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `planes_gastronomicos`
--
ALTER TABLE `planes_gastronomicos`
  ADD CONSTRAINT `fk_pgast_rest` FOREIGN KEY (`restaurante_id`) REFERENCES `restaurantes` (`id`) ON DELETE RESTRICT;

--
-- Filtros para la tabla `plan_incluye`
--
ALTER TABLE `plan_incluye`
  ADD CONSTRAINT `fk_pinc_plan` FOREIGN KEY (`plan_turistico_id`) REFERENCES `planes_turisticos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `platos`
--
ALTER TABLE `platos`
  ADD CONSTRAINT `fk_plat_pgast` FOREIGN KEY (`plan_gastronomico_id`) REFERENCES `planes_gastronomicos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `resenas`
--
ALTER TABLE `resenas`
  ADD CONSTRAINT `fk_res_gas` FOREIGN KEY (`plan_gastronomico_id`) REFERENCES `planes_gastronomicos` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_res_tur` FOREIGN KEY (`plan_turistico_id`) REFERENCES `planes_turisticos` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_res_usr` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE RESTRICT;

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `fk_rsv_gas` FOREIGN KEY (`plan_gastronomico_id`) REFERENCES `planes_gastronomicos` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_rsv_tur` FOREIGN KEY (`plan_turistico_id`) REFERENCES `planes_turisticos` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_rsv_usr` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
