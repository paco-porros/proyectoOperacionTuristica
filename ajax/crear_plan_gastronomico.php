<?php
/**
 * ═══════════════════════════════════════════════════════════════════════════
 * ENDPOINT: crear_plan_gastronomico.php
 * ═══════════════════════════════════════════════════════════════════════════
 *
 * PROPÓSITO:
 * Endpoint AJAX que recibe formulario (POST) con datos de una new experiencia
 * gastronómica y maneja la subida de imagen a la carpeta /img/.
 *
 * DIFERENCIA CON TURÍSTICO:
 * • Requiere restaurante_id (debe existir en BD)
 * • Campos específicos: categoría, duración_horas, max_personas
 * • Inserta en tabla "planes_gastronomicos" (no "planes_turisticos")
 *
 * FLUJO:
 * 1. Verifica autenticación del usuario
 * 2. Verifica rol (solo admin/editor pueden crear)
 * 3. Valida método HTTP (solo POST)
 * 4. Extrae y valida campos del formulario
 * 5. Valida que el restaurante existe en BD
 * 6. Procesa imagen (si existe)
 * 7. Inserta en BD tabla "planes_gastronomicos"
 * 8. Devuelve JSON con plan creado o error
 *
 * SEGURIDAD: Mismo nivel que crear_plan_turistico.php
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/session.php';

/* BLOQUE 1: Autenticación básica (igual que en turístico)
   Ver comentarios en crear_plan_turistico.php para detalles
*/
if (!estaLogueado()) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'msg' => 'No autenticado.']);
    exit;
}

/* BLOQUE 2: Verificar permisos de rol (igual que turístico) */
$usuario = usuarioActual();
if (!in_array($usuario['rol'], ['admin', 'editor'], true)) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'msg' => 'Acceso denegado.']);
    exit;
}

/* BLOQUE 3: Validar método POST (igual que turístico) */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'msg' => 'Método no permitido.']);
    exit;
}

// ────────────────────────────────────────────────────────────────────────────
// Validar campos requeridos (ESPECÍFICO PARA GASTRONÓMICO)
// ────────────────────────────────────────────────────────────────────────────

/* BLOQUE 4: Extraer datos del formulario gastronómico
   
   PARÁMETROS ESPERADOS (específicos de gastronomía):
   • titulo (string, requerido): Nombre del plato/experiencia
   • descripcion (string, opcional): Qué incluye
   • restaurante_id (int, requerido): A qué restaurante pertenece
   • categoria (string, opcional): Tipo de comida (Parrilla, Degustación, etc.)
   • duracion_horas (float, opcional): Cuántas horas toma
   • max_personas (int, opcional): Capacidad máxima
   • precio_desde (float, requerido): Precio por persona
   • estado (string, opcional): activo/inactivo
   • etiqueta (string, opcional): Categoría general
   
   DIFERENCIAS con turístico:
   • Agrega: restaurante_id, categoria, duracion_horas, max_personas
   • Remueve: ubicacion, duracion_dias
   
   CONVERSIONES DE TIPO:
   • (int): Convierte restaurante_id a entero
   • (float): Convierte horas a decimal (ej: 1.5 horas)
   • ?? : Proporciona valores por defecto sensatos
*/
$titulo          = trim($_POST['titulo'] ?? '');
$desc            = trim($_POST['descripcion'] ?? '');
$restaurante_id  = (int)($_POST['restaurante_id'] ?? 0);
$categoria       = trim($_POST['categoria'] ?? 'General');
$horas           = (float)($_POST['duracion_horas'] ?? 0);
$max_personas    = (int)($_POST['max_personas'] ?? 10);
$precio          = (float)($_POST['precio_desde'] ?? 0);
$estado          = in_array($_POST['estado'] ?? 'activo', ['activo', 'inactivo']) ? $_POST['estado'] : 'activo';
$etiqueta        = trim($_POST['etiqueta'] ?? 'Gastronomía');

/* BLOQUE 5: Validar TÍTULO (requerido y no vacío) */
if (!$titulo) {
    echo json_encode(['ok' => false, 'msg' => 'El título es requerido.']);
    exit;
} (IDÉNTICO A TURÍSTICO, ver ese archivo para detalles)
// ────────────────────────────────────────────────────────────────────────────

/* BLOQUE 9: Procesar imagen
   Lógica idéntica a crear_plan_turistico.php
   Único cambio: prefijo en nombre de archivo es 'plan_gastronomico_' en lugar de 'plan_turistico_'
   Ver crear_plan_turistico.php BLOQUE 7-13 para explicación detallada
*/
$imagen_ruta = null;

if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $archivo = $_FILES['imagen'];
    
    // Validar tipo de archivo
    $tipos_permitidos = ['image/jpeg', 'image/png', 'image/webp'];
    if (!in_array($archivo['type'], $tipos_permitidos, true)) {
        echo json_encode(['ok' => false, 'msg' => 'Tipo de archivo no permitido. Use JPG, PNG o WebP.']);
        exit;
    }

    // Validar tamaño (máximo 5MB)
    $max_size = 5 * 1024 * 1024; // 5MB
    if ($archivo['size'] > $max_size) {
        echo json_encode(['ok' => false, 'msg' => 'La imagen es demasiado grande (máximo 5MB).']);
        exit;
    }

    // Crear directorio si no existe
    $dir_img = __DIR__ . '/../img';
    if (!is_dir($dir_img)) {
        mkdir($dir_img, 0755, true);
    }

    // Generar nombre único (con prefijo gastronomico)
   • SELECT id FROM restaurantes WHERE id = ? LIMIT 1
   • Solo verifica existencia (no trae todos los datos, por eficiencia)
   • LIMIT 1: Aunque ID es PK (único), lo limitamos por buena práctica
   
   Si restaurante NO existe:
   • fetch() retorna false
   • Devuelve error antes de procesar imagen
   • Evita archivos huérfanos sin restaurante ligado
*/
$pdo = getDB();

// Verificar que el restaurante existe
$check_rest = $pdo->prepare('SELECT id FROM restaurantes WHERE id = ? LIMIT 1');
$check_rest->execute([$restaurante_id]);
if (!$check_rest->fetch()) {
    echo json_en (TABLA: planes_gastronomicos)
// ────────────────────────────────────────────────────────────────────────────

/* BLOQUE 10: Envolver BD en try-catch (igual que turístico) */
try {
    /* BLOQUE 11: Preparar INSERT en tabla planes_gastronomicos
       
       TABLA: planes_gastronomicos (no planes_turisticos)
       
       CAMPOS EN ESTE INSERT (11 placeholders):
       1. titulo           - Nombre del plato/experiencia
       2. descripcion      - Descripción
       3. restaurante_id   - FK a tabla restaurantes (ya validado)
       4. categoria        - Tipo de comida (Parrilla, Degustación, etc.)
       5. duracion_horas   - Duración en horas (float: 1.5, 2.0, etc.)
       6. max_personas     - Máximo de personas para esta experiencia
       7. precio_desde     - Precio por persona
       8. moneda           - Siempre 'USD'
       9. etiqueta         - Categoría general
       10. imagen_hero_url - Ruta de imagen (null si no se subió)
       11. estado          - activo/inactivo
       
       Inicializados a 0 (no placeholders):
       • puntuacion: Comienza en 0 (se actualiza con reseñas)
       • total_resenas: Cuenta de reseñas, comienza en 0
    */
    $stmt = $pdo->prepare(
        "INSERT INTO planes_gastronomicos 
         (titulo, descripcion, restaurante_id, categoria, duracion_horas, 
          max_personas, precio_desde, moneda, etiqueta, imagen_hero_url, 
          estado, puntuacion, total_resenas)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, 0)"
    );
    
    /* BLOQUE 12: Ejecutar con valores en orden */
    $stmt->execute([
        $titulo,
        $desc,
        $restaurante_id,      // FK verificado en BLOQUE 8
        $categoria,
        $horas,
        $max_personas,
        $precio,
        'USD',
        $etiqueta,
        $imagen_ruta,
        $estado,
    ]);

    /* BLOQUE 13: Obtener ID del plan recién creado */
    $plan_id = $pdo->lastInsertId();

    /* BLOQUE 14: Obtener nombre del restaurante para respuesta
       
       ¿Por qué hacer esta query?
       • El frontend necesita mostrar nombre del restaurante
       • No queremos resolver joins del lado cliente
       • Esta query trae solo lo necesario
       
       SELECT nombre: Solo trae el nombre (eficiente)
       WHERE id = ?: Usa prepared statement (seguro)
       ->fetch(): Retorna 1 fila como array asociativo
    */
    $stmt_rest = $pdo->prepare('SELECT nombre FROM restaurantes WHERE id = ? LIMIT 1');
    $stmt_rest->execute([$restaurante_id]);
    $rest_data = $stmt_rest->fetch();
    $restaurante_nombre = $rest_data['nombre'] ?? '—';  // Fallback: si falla consulta, usa '—'

    /* BLOQUE 15: Armar objeto con datos del plan GASTRONÓMICO para respuesta
       
       DIFERENCIAS con turístico:
       • Agrega: restaurante_id, restaurante_nombre, categoria, duracion_horas, max_personas
       • Remueve: ubicacion, duracion_dias
       
       Se devuelve completo para que frontend pueda:
       • Mostrar nueva fila sin query extra
       • Mostrar nombre del restaurante sin query extra
    */
    $nuevo_plan = [
        'id'                  => $plan_id,
        'titulo'              => $titulo,
        'descripcion'         => $desc,
        'restaurante_id'      => $restaurante_id,
        'restaurante_nombre'  => $restaurante_nombre,
        'categoria'           => $categoria,
        'duracion_horas'      => $horas,
        'max_personas'        => $max_personas,
        'precio_desde'        => $precio,
        'precio_formateado'   => number_format($precio, 0, ',', '.'),  // 1500.50 → 1.500,50
        'moneda'              => 'USD',
        'etiqueta'            => $etiqueta,
        'imagen_hero_url'     => $imagen_ruta ?: '/img/fondoPortada.jpg',
        'estado'              => $estado,
    ];

    /* BLOQUE 16: Respuesta de éxito */
    echo json_encode([
        'ok'    => true,
        'msg'   => '✓ Plan gastronómico "' . $titulo . '" creado correctamente.',
        'plan'  => $nuevo_plan,
    ]);

/* BLOQUE 17: Manejo de excepciones (igual que turístico) */
} catch (PDOException $e) {
    // Si algo falla en BD, limpiar la imagen que subimos
    if ($imagen_ruta && file_exists($dir_img . '/' . basename($imagen_ruta))) {
        unlink($dir_img . '/' . basename($imagen_ruta));
    }
    
    // Responder con error genérico     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, 0)"
    );
    
    $stmt->execute([
        $titulo,
        $desc,
        $restaurante_id,
        $categoria,
        $horas,
        $max_personas,
        $precio,
        'USD',
        $etiqueta,
        $imagen_ruta,
        $estado,
    ]);

    $plan_id = $pdo->lastInsertId();

    // Obtener nombre del restaurante para respuesta
    $stmt_rest = $pdo->prepare('SELECT nombre FROM restaurantes WHERE id = ? LIMIT 1');
    $stmt_rest->execute([$restaurante_id]);
    $rest_data = $stmt_rest->fetch();
    $restaurante_nombre = $rest_data['nombre'] ?? '—';

    // Preparar objeto del plan para respuesta
    $nuevo_plan = [
        'id'                  => $plan_id,
        'titulo'              => $titulo,
        'descripcion'         => $desc,
        'restaurante_id'      => $restaurante_id,
        'restaurante_nombre'  => $restaurante_nombre,
        'categoria'           => $categoria,
        'duracion_horas'      => $horas,
        'max_personas'        => $max_personas,
        'precio_desde'        => $precio,
        'precio_formateado'   => number_format($precio, 0, ',', '.'),
        'moneda'              => 'USD',
        'etiqueta'            => $etiqueta,
        'imagen_hero_url'     => $imagen_ruta ?: '/img/fondoPortada.jpg',
        'estado'              => $estado,
    ];

    echo json_encode([
        'ok'    => true,
        'msg'   => '✓ Plan gastronómico "' . $titulo . '" creado correctamente.',
        'plan'  => $nuevo_plan,
    ]);

} catch (PDOException $e) {
    // Eliminar imagen si hubo error en BD
    if ($imagen_ruta && file_exists($dir_img . '/' . basename($imagen_ruta))) {
        unlink($dir_img . '/' . basename($imagen_ruta));
    }
    
    echo json_encode(['ok' => false, 'msg' => 'Error al guardar el plan en la BD.']);
    exit;
}
