<?php
/**
 * ═══════════════════════════════════════════════════════════════════════════
 * ENDPOINT: crear_plan_turistico.php
 * ═══════════════════════════════════════════════════════════════════════════
 *
 * PROPÓSITO:
 * Endpoint AJAX que recibe formulario (POST) con datos de un nuevo plan
 * turístico y maneja la subida de imagen a la carpeta /img/.
 *
 * FLUJO:
 * 1. Verifica autenticación del usuario
 * 2. Verifica rol (solo admin/editor pueden crear planes)
 * 3. Valida método HTTP (solo POST)
 * 4. Extrae y valida campos del formulario
 * 5. Procesa imagen (si existe)
 * 6. Inserta en BD tabla "planes_turisticos"
 * 7. Devuelve JSON con plan creado o error
 *
 * SEGURIDAD:
 * - Prepared statements (protección SQL injection)
 * - Validación de tipo de archivo
 * - Validación de tamaño
 * - Generación de nombres únicos
 * - Limpieza de imagen si BD falla
 *
 * RESPUESTA JSON:
 * Éxito: { ok: true, msg: "...", plan: {...} }
 * Error:  { ok: false, msg: "..." }
 */

/* ──────────────────────────────────────────────────────────────────────────
   CONFIGURACIÓN INICIAL Y AUTENTICACIÓN
   ────────────────────────────────────────────────────────────────────────── */

// Asegurar que la respuesta sea JSON válido con UTF-8
header('Content-Type: application/json; charset=utf-8');

// Incluir módulos de BD y sesión
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/session.php';

/* BLOQUE 1: Verificar que el usuario está logueado
   • Si no hay sesión activa, responde 401 Unauthorized
   • Detiene ejecución con exit después de respuesta JSON
*/
if (!estaLogueado()) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'msg' => 'No autenticado.']);
    exit;
}

/* BLOQUE 2: Verificar que el usuario tiene permisos (rol admin o editor)
   • Obtiene datos del usuario actual
   • Comprueba si su rol está en array de roles permitidos (admin/editor)
   • Si no tiene permisos, responde 403 Forbidden
   • Detiene ejecución
*/
$usuario = usuarioActual();
if (!in_array($usuario['rol'], ['admin', 'editor'], true)) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'msg' => 'Acceso denegado.']);
    exit;
}

/* BLOQUE 3: Validar que el método HTTP es POST
   • Solo acepta POST (no GET, PUT, DELETE, etc.)
   • Si es otro método, responde 405 Method Not Allowed
   • Esto previene que se creen planes por GET accidental
*/
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'msg' => 'Método no permitido.']);
    exit;
}

// ────────────────────────────────────────────────────────────────────────────
// Validar campos requeridos
// ────────────────────────────────────────────────────────────────────────────

/* BLOQUE 4: Extraer y validar datos del formulario
   
   PARÁMETROS ESPERADOS ($_POST):
   • titulo (string, requerido): Nombre del plan
   • descripcion (string, opcional): Texto descriptivo
   • ubicacion (string, requerido): Dónde se realiza el plan
   • duracion_dias (int, opcional): Cuántos días dura (mínimo 1)
   • precio_desde (float, requerido): Precio base del plan
   • estado (string, opcional): 'activo' o 'inactivo'
   • etiqueta (string, opcional): Categoría del plan
   
   VALIDACIONES AQUÍ:
   • trim(): elimina espacios en blanco
   • max(1, ...): asegura que duración sea mínimo 1 día
   • (float)(...): convierte a número decimal
   • in_array(...): valida que estado sea activo o inactivo
   • ?? : operador null coalesce (valor por defecto si no existe)
   
   ⚠️ NOTA: Las validaciones CRÍTICAS (titulo, precio) se hacen después.
           Las opcionales se establecen con defaults.
*/
$titulo   = trim($_POST['titulo'] ?? '');
$desc     = trim($_POST['descripcion'] ?? '');
$ubic     = trim($_POST['ubicacion'] ?? '');
$dias     = max(1, (int)($_POST['duracion_dias'] ?? 1));
$precio   = (float)($_POST['precio_desde'] ?? 0);
$estado   = in_array($_POST['estado'] ?? 'activo', ['activo', 'inactivo']) ? $_POST['estado'] : 'activo';
$etiqueta = trim($_POST['etiqueta'] ?? 'Turismo');

/* BLOQUE 5: Validación del TÍTULO
   • El título es obligatorio (no puede estar vacío)
   • Si está vacío, devuelve error JSON y termina
   • Esto previene crear planes sin nombre
*/
if (!$titulo) {
    echo json_encode(['ok' => false, 'msg' => 'El título es requerido.']);
    exit;
}

/* BLOQUE 6: Validación del PRECIO
   • El precio no puede ser negativo
   • Si es negativo, devuelve error y termina
   • Nota: permite 0 (planes gratuitos son válidos)
*/
if ($precio < 0) {
    echo json_encode(['ok' => false, 'msg' => 'El precio no puede ser negativo.']);
    exit;
}

// ────────────────────────────────────────────────────────────────────────────
// Manejo de la imagen
// ────────────────────────────────────────────────────────────────────────────

/* BLOQUE 7: Preparar variable para ruta de imagen
   • $imagen_ruta será null si no se sube imagen (imagen es opcional)
   • O será la ruta relativa si se sube exitosamente (ej: "img/plan_turistico_123.jpg")
   • La imagen se guardará en BD conectada a este plan
*/
$imagen_ruta = null;

/* BLOQUE 8: Verificar que la imagen existe y se subió sin errores
   
   isset($_FILES['imagen']): Valida que el campo 'imagen' existe en $_FILES
   $_FILES['imagen']['error'] === UPLOAD_ERR_OK: Valida que NO hubo error
   
   CÓDIGOS DE ERROR PHP UPLOAD_ERR_*:
   • UPLOAD_ERR_OK (0): Sin error, archivo subido correctamente
   • UPLOAD_ERR_NO_FILE (4): No se envió archivo (es normal si es opcional)
   • Otros: errores varios (tamaño servidor, permisos, etc.)
   
   Este IF solo procesa si AMBAS condiciones son true.
   Si solo se cumple una, $imagen_ruta permanece NULL (no error, solo sin imagen)
*/
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $archivo = $_FILES['imagen'];
    
    /* BLOQUE 9: Validación de TIPO de archivo
       
       ¿Por qué validar el tipo?
       • Prevenir que suban archivos maliciosos (PHP, Exe, etc.)
       • Solo permitir imágenes de formatos especificados
       
       MIME TYPES permitidos:
       • 'image/jpeg': Archivos .jpg, .jpeg
       • 'image/png': Archivos .png
       • 'image/webp': Archivos .webp (formato moderno)
       
       ⚠️ IMPORTANTE: $_FILES['image']['type'] se basa en el MIME enviado
                      por el cliente, NO es 100% seguro.
                      Para producción, validar con getimagesize() o Imageick
       
       Si tipo NO está permitido, devuelve error y termina igual.
    */
    $tipos_permitidos = ['image/jpeg', 'image/png', 'image/webp'];
    if (!in_array($archivo['type'], $tipos_permitidos, true)) {
        echo json_encode(['ok' => false, 'msg' => 'Tipo de archivo no permitido. Use JPG, PNG o WebP.']);
        exit;
    }

    /* BLOQUE 10 e insertar datos
// ────────────────────────────────────────────────────────────────────────────

/* BLOQUE 14: Obtener conexión a BD
   
   getDB(): Función del archivo /includes/db.php
   • Retorna objeto PDO (conexión a MySQL)
   • Usa conexión singleton (misma instancia siempre)
   • La conexión ya está configurada (host, user, pass, charset UTF-8)
*/
$pdo = getDB();

/* BLOQUE 15: Envolver en try-catch para manejo de errores
   
   ¿Por qué try-catch?
   • Si algo falla en BD (tables no existe, error SQL, permisos, etc.)
     PDO lanzará PDOException
   • Sin try-catch, la excepción rompe el script
   • Con try-catch, capturamos el error y respondemos gracefully
   
   Si hay error en la BD:
   • Catch captura la excepción
   • Elimina la imagen que subimos (no queremos huérfana)
   • Devuelve JSON con error específico
*/
try {
    /* BLOQUE 16: Preparar statement SQL parameterizado
       
       ¿Por qué prepared statement?
       • Previene SQL Injection (ataque de seguridad)
       • Los ? son placeholders (se reemplazan con execute())
       • Las comillas y caracteres especiales se escapan automáticamente
       
       TABLA: planes_turisticos
       CAMPOS A INSERT:
       • titulo, descripcion, ubicacion, duracion_dias
       • precio_desde, moneda (siempre 'USD')
       • etiqueta, imagen_hero_url, estado
       • puntuacion, total_resenas (inicializan en 0)
       
       Orden de los ?: Los 9 primeros ? corresponden a los 9 campos
                       Los dos 0 son literales (no placeholders)
    */
    $stmt = $pdo->prepare(
        "INSERT INTO planes_turisticos 
         (titulo, descripcion, ubicacion, duracion_dias, precio_desde, 
          moneda, etiqueta, imagen_hero_url, estado, puntuacion, total_resenas)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0, 0)"
    );
    
    /* BLOQUE 17: Ejecutar statement con valores
       
       execute(array): Reemplaza los ? con los valores del array en orden
       
       Orden del array:
       1. $titulo        - Nombre del plan
       2. $desc          - Descripción
       3. $ubic          - Ubicación
       4. $dias          - Duración en días
       5. $precio        - Precio base
       6. 'USD'          - Moneda fija
       7. $etiqueta      - Categoría
       8. $imagen_ruta   - Ruta de archivo (null si no subió)
       9. $estado        - activo/inactivo
       
       Si execute() retorna false, se va al catch
       Si falla, PDOException se lanza
    */
    $stmt->execute([
        $titulo,
        $desc,
        $ubic,
        $dias,
        $precio,
        'USD',
        $etiqueta,
        $imagen_ruta,
        $estado,
    ]);

    /* BLOQUE 18: Obtener ID del plan recién creado
       
       lastInsertId(): Retorna el AUTO_INCREMENT del último INSERT
       • Útil para devolver al frontend con qué ID se guardó
       • El frontend puede localizar la nueva fila mostrada
       
       Ejemplo: Si es el plan #5 creado, retorna 5
    */
    $plan_id = $pdo->lastInsertId();

    /* BLOQUE 19: Armar objeto con datos del plan para respuesta
       
       ¿Por qué un array con los datos?
       • El frontend necesita mostrar el nuevo plan en la tabla
       • Sin hacer otra query GET
       • Esto evita consulta extra a BD
       
       'precio_formateado': Versión bonita del precio
       • number_format($precio, 0, ',', '.')
       • Agrega separadores de miles (1500.50 → 1.500,50)
       • El frontend lo puede mostrar así en UI
       
       'imagen_hero_url': Usa fallback si no hay imagen
       • Si $imagen_ruta es null → '/img/fondoPortada.jpg' (imagen por defecto)
       • El ?: es operador ternario ($a ?: $b significa: $a si es truthy, sino $b)
    */
    $nuevo_plan = [
        'id'               => $plan_id,
        'titulo'           => $titulo,
        'descripcion'      => $desc,
        'ubicacion'        => $ubic,
        'duracion_dias'    => $dias,
        'precio_desde'     => $precio,
        'precio_formateado' => number_format($precio, 0, ',', '.'),
        'moneda'           => 'USD',
        'etiqueta'         => $etiqueta,
        'imagen_hero_url'  => $imagen_ruta ?: '/img/fondoPortada.jpg',
        'estado'           => $estado,
    ];

    /* BLOQUE 20: Respuesta de éxito
       
       Se devuelve JSON con:
       • ok: true (indica que todo salió bien)
       • msg: Mensaje amigable confirmando creación
       • plan: Objeto con datos del plan (para mostrar en tabla)
       
       json_encode(): Convierte array PHP a string JSON válido
       • Automáticamente hace escape del mensaje
       • Respeta encoding UTF-8 (porque header lo especificarló)
    */
    echo json_encode([
        'ok'    => true,
        'msg'   => '✓ Plan turístico "' . $titulo . '" creado correctamente.',
        'plan'  => $nuevo_plan,
    ]);

/* BLOQUE 21: Manejo de excepciones de BD
   
   PDOException: Se lanza si:
   • Conexión falla
   • Tabla/campo no existe
   • Constraint violation (ej: email duplicado)
   • Syntax error en SQL
   
   ACCIONES:
   • Si hay imagen, LA ELIMINA del servidor
   • Devuelve error genérico JSON (no exponer detalle de BD a client)
   • Termina ejecución
   
   ⚠️ IMPORTANTE: Esto es como un "rollback" manual
                  Si BD falla, no dejamos archivos huérfanos.
*/
} catch (PDOException $e) {
    // Limpiar: eliminar imagen si fue salvada pero BD falló
    if ($imagen_ruta && file_exists($dir_img . '/' . basename($imagen_ruta))) {
        unlink($dir_img . '/' . basename($imagen_ruta));
    }
    
    // Responder con error (sin detallar interno de BD)   
       Un match() EQUIVALENTE a switch para obtener extensión:
       • Si type es 'image/jpeg' → $ext = 'jpg'
       • Si type es 'image/png' → $ext = 'png'
       • Si type es 'image/webp' → $ext = 'webp'
       
       RESULTADO EJEMPLO: 'plan_turistico_1711800000_65e4a2b1f3c8a.jpg'
    */
    $ext = match($archivo['type']) {
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
    };
    
    $nombre_archivo = 'plan_turistico_' . time() . '_' . uniqid() . '.' . $ext;
    $ruta_completa  = $dir_img . '/' . $nombre_archivo;      // Ruta absoluta del servidor
    $ruta_relativa  = 'img/' . $nombre_archivo;              // Ruta relativa para HTML

    /* BLOQUE 13: Mover archivo a /img/
       
       move_uploaded_file($origen, $destino):
       • Función PHP que mueve archivo temporal a ubicación permanente
       • Solo funciona con archivos subidos por HTTP (no copia locales)
       • $_FILES['imagen']['tmp_name']: Ubicación temporal del servidor
       • $ruta_completa: Donde queremos guardarlo definitivamente
       
       SI FALLA:
       • Devuelve false (no tira excepción)
       • Devuelve error JSON
       • Termina ejecución (no continúa a BD)
       
       SI ÉXITO:
       • Archivo está en /img/
       • $imagen_ruta se asigna y sale del IF
    */
    if (!move_uploaded_file($archivo['tmp_name'], $ruta_completa)) {
        echo json_encode(['ok' => false, 'msg' => 'Error al subir la imagen.']);
        exit;
    }

    // Asignar ruta relativa (esto es lo que se guardará en BD)
    $imagen_ruta = $ruta_relativa;
}

// ────────────────────────────────────────────────────────────────────────────
// Guardar en BD
// ────────────────────────────────────────────────────────────────────────────

$pdo = getDB();

try {
    $stmt = $pdo->prepare(
        "INSERT INTO planes_turisticos 
         (titulo, descripcion, ubicacion, duracion_dias, precio_desde, 
          moneda, etiqueta, imagen_hero_url, estado, puntuacion, total_resenas)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0, 0)"
    );
    
    $stmt->execute([
        $titulo,
        $desc,
        $ubic,
        $dias,
        $precio,
        'USD',
        $etiqueta,
        $imagen_ruta,
        $estado,
    ]);

    $plan_id = $pdo->lastInsertId();

    // Preparar objeto del plan para respuesta
    $nuevo_plan = [
        'id'               => $plan_id,
        'titulo'           => $titulo,
        'descripcion'      => $desc,
        'ubicacion'        => $ubic,
        'duracion_dias'    => $dias,
        'precio_desde'     => $precio,
        'precio_formateado' => number_format($precio, 0, ',', '.'),
        'moneda'           => 'USD',
        'etiqueta'         => $etiqueta,
        'imagen_hero_url'  => $imagen_ruta ?: '/img/fondoPortada.jpg',
        'estado'           => $estado,
    ];

    echo json_encode([
        'ok'    => true,
        'msg'   => '✓ Plan turístico "' . $titulo . '" creado correctamente.',
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
