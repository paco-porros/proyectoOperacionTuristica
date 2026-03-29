<?php
/**
 * ajax/admin_planes_gastronomicos.php
 * ════════════════════════════════════════════════════════════════════════════
 * 
 * Endpoint AJAX para gestión de planes gastronómicos en el dashboard admin.
 * 
 * MÉTODOS HTTP SOPORTADOS:
 * ├─ GET  → Lista todos los planes (activos + inactivos) + estadísticas + RESTAURANTES
 * ├─ PUT  → Editar campos de un plan gastronómico existente
 * └─ POST → Toggle (activar/inactivar) un plan por ID
 * 
 * AUTENTICACIÓN:
 * - Solo usuarios logueados pueden acceder
 * - Solo admin y editor tienen permiso (403 para otros roles)
 * 
 * RESPUESTAS JSON:
 * ├─ GET (éxito): { ok: true, planes: [...], stats: {...}, restaurantes: [...] }
 * ├─ PUT (éxito): { ok: true, msg: "Plan actualizado..." }
 * ├─ POST (éxito): { ok: true, msg: "...", estado: "activo|inactivo" }
 * └─ Error: { ok: false, msg: "Descripción del error" }
 * 
 * NOTA IMPORTANTE SOBRE RESTAURANTES:
 * El endpoint devuelve un array de restaurantes en la respuesta GET.
 * Esto es NECESARIO porque el frontend (dashboard) necesita poblar el
 * <select id="crear-plan-restaurante"> cuando el usuario abre el modal
 * para crear un nuevo plan gastronómico.
 * 
 * SIN esto: Los restaurantes no aparecerían en el select (no se sabe de dónde sacarlos)
 * CON esto: JavaScript carga la lista y llena el select dinámicamente
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/session.php';

/* ════════════════════════════════════════════════════════════════════════════
   BLOQUE 1 - Autenticación y Autorización
════════════════════════════════════════════════════════════════════════════

   Propósito: Verificar que el usuario está logueado y tiene permisos de admin
   
   ¿Por qué?
   - Proteger datos sensibles (rutas AJAX publicas son vulnerables)
   - Solo admin/editor pueden ver y modificar planes gastronómicos
   - Cliente intenta acceder: devolver 401 (no autenticado)
   - Usuario logueado pero sin permisos: devolver 403 (prohibido)
   
   HTTP Status Codes:
   - 401 Unauthorized: Usuario NO está logueado
   - 403 Forbidden: Usuario está logueado pero sin permiso de rol
   - 200 OK: Éxito (si el método GET/PUT/POST es válido)
*/

if (!estaLogueado()) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'msg' => 'No autenticado.']);
    exit;
}

$usuario = usuarioActual();
if (!in_array($usuario['rol'], ['admin', 'editor'], true)) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'msg' => 'Acceso denegado.']);
    exit;
}

$pdo    = getDB();
$method = $_SERVER['REQUEST_METHOD'];

/* ════════════════════════════════════════════════════════════════════════════
   BLOQUE 2 - GET: Listar Planes Gastronómicos + Restaurantes
════════════════════════════════════════════════════════════════════════════

   Propósito: Devolver todos los planes gastronómicos con información completa
   + lista de restaurantes disponibles para el dropdown del modal.
   
   ¿Cuándo se llama?
   - Cuando dashboard se carga inicialmente (página abierta)
   - Cuando usuario navega a sección "Planes Gastronómicos"
   - Cuando usuario crea/edita un plan (recarga tabla)
   - Cuando usuario abre modal para crear plan (carga restaurantes)
*/

if ($method === 'GET') {
    
    /* ─────────────────────────────────────────────────────────────────────────
       BLOQUE 2a - Obtener Planes Gastronómicos con Información del Restaurante
    ─────────────────────────────────────────────────────────────────────────
    
       Query SQL:
       SELECT pg.id, pg.titulo, pg.descripcion, pg.etiqueta, pg.categoria,
              pg.precio_desde, pg.moneda, pg.duracion_horas, pg.max_personas,
              pg.puntuacion, pg.total_resenas, pg.imagen_hero_url, pg.estado,
              r.nombre AS restaurante_nombre
       FROM planes_gastronomicos pg
       JOIN restaurantes r ON r.id = pg.restaurante_id
       ORDER BY pg.id DESC
       
       ¿Por qué JOIN?
       - Cada plan_gastronomico tiene restaurante_id (FK)
       - Necesitamos el NOMBRE del restaurante para mostrar en tabla
       - JOIN trae el nombre en una sola query (eficiente)
       
       ¿Por qué ORDER BY pg.id DESC?
       - Mostrar planes más nuevos primero
       - DESC = descendente (id mayor = más nuevo)
       
       Resultado: Array de planes donde cada uno tiene su restaurante_nombre
       Ejemplo:
       {
           id: 5,
           titulo: "Asado Tradicional",
           restaurante_nombre: "La Churrascería",
           estado: "activo",
           ...
       }
    */
    
    $stmt = $pdo->query(
        "SELECT pg.id, pg.titulo, pg.descripcion, pg.etiqueta, pg.categoria,
                pg.precio_desde, pg.moneda, pg.duracion_horas, pg.max_personas,
                pg.puntuacion, pg.total_resenas, pg.imagen_hero_url, pg.estado,
                r.nombre AS restaurante_nombre
         FROM planes_gastronomicos pg
         JOIN restaurantes r ON r.id = pg.restaurante_id
         ORDER BY pg.id DESC"
    );
    $planes = $stmt->fetchAll();

    /* ─────────────────────────────────────────────────────────────────────────
       BLOQUE 2b - Formatear Datos de Cada Plan
    ─────────────────────────────────────────────────────────────────────────
    
       Para cada plan, agregamos:
       1. precio_formateado: Convierte precio numérico a formato legible
          Ejemplo: 49500 → "49.500" (formato latino con punto como miles)
          
       2. imagen_hero_url: Si está vacía, usar imagen por defecto
          Fallback: /img/fondoPortada.jpg (evita <img> rota)
       
       Esto es importante para:
       - Usuarios: ven precios en formato familiar
       - Frontend: siempre tiene URL válida para <img>
    */
    
    foreach ($planes as &$p) {
        $p['precio_formateado'] = number_format((float)$p['precio_desde'], 0, ',', '.');
        $p['imagen_hero_url']   = !empty($p['imagen_hero_url'])
            ? $p['imagen_hero_url'] : '/img/fondoPortada.jpg';
    }

    /* ─────────────────────────────────────────────────────────────────────────
       BLOQUE 2c - Obtener Estadísticas de Planes Gastronómicos
    ─────────────────────────────────────────────────────────────────────────
    
       Query:
       SELECT COUNT(*) AS total,
              SUM(estado = 'activo') AS activos
       FROM planes_gastronomicos
       
       Explicación:
       - COUNT(*): Cuántos planes en total existen
       - SUM(estado = 'activo'): Cuántos están activos
         * suma 1 si estado es 'activo'
         * suma 0 si estado es otro valor
         * Total da cantidad de activos
       
       Ejemplo resultado:
       { total: 15, activos: 12 }
       Significado: 15 planes en total, 12 activos (3 inactivos)
       
       Uso frontend:
       - stat-total: Muestra "15"
       - stat-activos: Muestra "12"
    */
    
    $stats = $pdo->query(
        "SELECT COUNT(*) AS total,
                SUM(estado = 'activo') AS activos
         FROM planes_gastronomicos"
    )->fetch();

    /* ─────────────────────────────────────────────────────────────────────────
       BLOQUE 2d - Obtener Lista de Restaurantes Disponibles [IMPORTANTE]
    ─────────────────────────────────────────────────────────────────────────
    
       Query:
       SELECT id, nombre FROM restaurantes ORDER BY nombre ASC
       
       ¿Por qué esto?
       
       PROBLEMA:
       - Usuario abre modal "Crear Plan Gastronómico"
       - Ve un <select> vacío: "Selecciona un restaurante"
       - ¿De dónde saca los restaurantes para llenar el select?
       
       SOLUCIÓN:
       - Este endpoint devuelve array de restaurantes
       - JavaScript recibe restaurantes en la respuesta
       - JavaScript llena el <select> dinámicamente
       - Usuario ve lista de restaurantes disponibles
       
       Flow:
       1. Dashboard hace fetch a admin_planes_gastronomicos.php (GET)
       2. Servidor devuelve: { planes: [...], restaurantes: [...] }
       3. JavaScript recibe restaurantes en data.restaurantes
       4. cargarRestaurantes() llena el select con estos datos
       5. Usuario selecciona restaurante → puede crear plan
       
       Ordenado por nombre (ASC):
       - "La Churrascería" antes de "Restaurant Le Petit"
       - UX: Más fácil de buscar/seleccionar
       
       Devuelve: [
           { id: 3, nombre: "La Churrascería" },
           { id: 7, nombre: "Restaurant Le Petit" },
           ...
       ]
    */
    
    $stmtRest = $pdo->query("SELECT id, nombre FROM restaurantes ORDER BY nombre ASC");
    $restaurantes = $stmtRest->fetchAll();

    /* ─────────────────────────────────────────────────────────────────────────
       BLOQUE 2e - Devolver Respuesta JSON Completa
    ─────────────────────────────────────────────────────────────────────────
    
       Estructura:
       {
           "ok": true,
           "planes": [ ... array de planes ... ],
           "stats": { total: X, activos: Y },
           "restaurantes": [ ... array para dropdown ... ]
       }
       
       JavaScript recibe esto y:
       - data.planes: llena la tabla de planes
       - data.stats: actualiza estadísticos arriba
       - data.restaurantes: llena el <select> del modal
       
       Esto permite que TODO suceda en una sola petición AJAX
       (más eficiente que hacer 2-3 requests)
    */
    
    echo json_encode(['ok' => true, 'planes' => $planes, 'stats' => $stats, 'restaurantes' => $restaurantes]);
    exit;
}

/* ════════════════════════════════════════════════════════════════════════════
   BLOQUE 3 - PUT: Editar un Plan Gastronómico
════════════════════════════════════════════════════════════════════════════

   Propósito: Actualizar campos de un plan gastronómico existente
   
   ¿Cuándo se llama?
   - Usuario abre modal "Editar Plan"
   - Modifica campos (título, precio, categoría, etc)
   - Hace click en "Guardar cambios"
   - Se envía PUT con los nuevos valores
   
   Parámetros esperados en request body (JSON):
   {
       "id": 5,                      // ID del plan a editar (requerido)
       "titulo": "Asado Nuevo",      // Nuevo título (requerido)
       "descripcion": "Degustación...",
       "categoria": "Parrilla",
       "duracion_horas": 2.5,
       "precio_desde": 55000,
       "imagen_hero_url": "https://...",
       "estado": "activo"            // activo | inactivo
   }
*/

if ($method === 'PUT') {
    
    /* ────────────────────────────────────────────────────────────────────────
       BLOQUE 3a - Parsear Request Body y Extraer Valores
    ────────────────────────────────────────────────────────────────────────
    
       PUT no usa $_POST, envía JSON en el body (raw data)
       Necesitamos:
       1. Leer con file_get_contents('php://input')
       2. Decodificar con json_decode(..., true)
       3. ?? [] es fallback si no hay body → array vacío
    */
    
    $d       = json_decode(file_get_contents('php://input'), true) ?? [];
    
    /* ────────────────────────────────────────────────────────────────────────
       BLOQUE 3b - Extraer y Tipo-Castear Parámetros
    ────────────────────────────────────────────────────────────────────────
    
       (int)($d['id'] ?? 0): 
       - Si existe $d['id']: convertir a entero
       - Si no existe: usar 0 (defecto)
       
       Valores posibles:
       - $id: entero > 0 (ID del plan)
       - $titulo: string sin espacios (trim())
       - $desc: string descripción
       - $cat: string categoría (ej: "Parrilla", "Degustación")
       - $horas: float duración en horas
       - $precio: float precio en USD
       - $imagen: string URL de imagen
       - $estado: 'activo' | 'inactivo' (validado con in_array)
    */
    
    $id      = (int)($d['id'] ?? 0);
    $titulo  = trim($d['titulo'] ?? '');
    $desc    = trim($d['descripcion'] ?? '');
    $cat     = trim($d['categoria'] ?? '');
    $horas   = (float)($d['duracion_horas'] ?? 0);
    $precio  = (float)($d['precio_desde'] ?? 0);
    $imagen  = trim($d['imagen_hero_url'] ?? '');
    $estado  = in_array($d['estado'] ?? '', ['activo', 'inactivo']) ? $d['estado'] : 'activo';

    /* ────────────────────────────────────────────────────────────────────────
       BLOQUE 3c - Validar Parámetros Obligatorios
    ────────────────────────────────────────────────────────────────────────
    
       Validaciones:
       - $id > 0: Debe ser un ID válido (entero positivo)
       - $titulo no vacío: El plan DEBE tener título
       
       Si falla, retornar error inmediatamente (exit)
    */
    
    if (!$id || !$titulo) {
        echo json_encode(['ok' => false, 'msg' => 'ID y título son requeridos.']);
        exit;
    }

    /* ────────────────────────────────────────────────────────────────────────
       BLOQUE 3d - Verificar que el Plan Existe en BD
    ────────────────────────────────────────────────────────────────────────
    
       IMPORTANTE PARA SEGURIDAD:
       - Usuario intenta editar plan_id = 999 (NO existe)
       - Sin esta verificación: UPDATE afectaría 0 filas (silencioso)
       - Con verificación: Detectamos estado inválido
       
       Query preparada:
       SELECT id FROM planes_gastronomicos WHERE id = ? LIMIT 1
       
       Si no hay resultado (fetch() devuelve false/null):
       - El plan no existe → devolver error
    */
    
    $check = $pdo->prepare('SELECT id FROM planes_gastronomicos WHERE id = ? LIMIT 1');
    $check->execute([$id]);
    if (!$check->fetch()) {
        echo json_encode(['ok' => false, 'msg' => 'Plan no encontrado.']);
        exit;
    }

    /* ────────────────────────────────────────────────────────────────────────
       BLOQUE 3e - Ejecutar UPDATE con Prepared Statement
    ────────────────────────────────────────────────────────────────────────
    
       Query parametrizada:
       UPDATE planes_gastronomicos
       SET titulo = ?, descripcion = ?, categoria = ?, 
           duracion_horas = ?, precio_desde = ?, 
           imagen_hero_url = ?, estado = ?
       WHERE id = ?
       
       ¿Por qué prepared statement?
       - Previene SQL injection (parámetros escapados)
       - Los wildcards (?) previenen inyecciones maliciosas
       
       Orden de parámetros:
       [$titulo, $desc, $cat, $horas, $precio, $imagen, $estado, $id]
    */
    
    $stmt = $pdo->prepare(
        "UPDATE planes_gastronomicos
         SET titulo = ?, descripcion = ?, categoria = ?, duracion_horas = ?,
             precio_desde = ?, imagen_hero_url = ?, estado = ?
         WHERE id = ?"
    );
    $stmt->execute([$titulo, $desc, $cat, $horas, $precio, $imagen, $estado, $id]);

    /* ────────────────────────────────────────────────────────────────────────
       BLOQUE 3f - Devolver Éxito
    ────────────────────────────────────────────────────────────────────────
    
       Si llegamos aquí: UPDATE se ejecutó sin errores
       Devolver respuesta exitosa al frontend
       
       Frontend recibirá:
       { ok: true, msg: 'Plan gastronómico actualizado correctamente.' }
       
       Luego JavaScript:
       1. Cerrará el modal
       2. Recargará la tabla de planes
       3. Mostrará toast de confirmación
    */
    
    echo json_encode(['ok' => true, 'msg' => 'Plan gastronómico actualizado correctamente.']);
    exit;
}

/* ════════════════════════════════════════════════════════════════════════════
   BLOQUE 4 - POST action=toggle: Activar / Inactivar un Plan
════════════════════════════════════════════════════════════════════════════

   Propósito: Cambiar rápidamente el estado de un plan sin abrir modal
   
   ¿Cuándo se llama?
   - Usuario ve plan en tabla con botón "toggle_on" (activo)
   - Hace click en botón
   - Plan se inactiva inmediatamente
   - Botón cambia a "toggle_off" (inactivo)
   - Tabla se actualiza sin recargar
   
   ¿Por qué es diferente a PUT?
   - PUT: editar múltiples campos (abre modal)
   - POST toggle: cambiar solo estado (un clic, sin modal)
   - UX: Más rápido para tareas simples
   
   Request body esperado (JSON):
   {
       "action": "toggle",    // Validador de acción
       "id": 5                // ID del plan
   }
*/

if ($method === 'POST') {
    
    /* ────────────────────────────────────────────────────────────────────────
       BLOQUE 4a - Parsear Request y Extraer Parámetros
    ────────────────────────────────────────────────────────────────────────
    
       POST puede venir como:
       1. JSON: file_get_contents('php://input') y descodificar
       2. Form-encoded: en $_POST
       
       ?? $_POST es fallback (POST tradicional con form-urlencoded)
       ?? [] es fallback si no hay nada (array vacío)
    */
    
    $d      = json_decode(file_get_contents('php://input'), true) ?? $_POST;
    $action = trim($d['action'] ?? '');
    $id     = (int)($d['id'] ?? 0);

    /* ────────────────────────────────────────────────────────────────────────
       BLOQUE 4b - Validar Parámetros
    ────────────────────────────────────────────────────────────────────────
    
       Validaciones:
       - $action DEBE ser 'toggle' exactamente
       - $id DEBE ser > 0 (entero válido)
       
       Si falla: error y exit
    */
    
    if ($action !== 'toggle' || !$id) {
        echo json_encode(['ok' => false, 'msg' => 'Parámetros inválidos.']);
        exit;
    }

    /* ────────────────────────────────────────────────────────────────────────
       BLOQUE 4c - Obtener Estado Actual del Plan
    ────────────────────────────────────────────────────────────────────────
    
       Query:
       SELECT estado FROM planes_gastronomicos WHERE id = ? LIMIT 1
       
       Importante:
       - Necesitamos saber el estado ACTUAL para toglea
       - Si estado es 'activo' → cambiar a 'inactivo'
       - Si estado es otro (inactivo) → cambiar a 'activo'
       
       fetch() devuelve array o false si no existe
    */
    
    $sel = $pdo->prepare('SELECT estado FROM planes_gastronomicos WHERE id = ? LIMIT 1');
    $sel->execute([$id]);
    $row = $sel->fetch();

    /* ────────────────────────────────────────────────────────────────────────
       BLOQUE 4d - Verificar que el Plan Existe
    ────────────────────────────────────────────────────────────────────────
    
       Si fetch() devuelve falsy (null, false):
       - Plan con ese ID no existe en BD
       - No se puede toglea algo que no existe
       - Devolver error
    */
    
    if (!$row) {
        echo json_encode(['ok' => false, 'msg' => 'Plan no encontrado.']);
        exit;
    }

    /* ────────────────────────────────────────────────────────────────────────
       BLOQUE 4e - Calcular Nuevo Estado
    ────────────────────────────────────────────────────────────────────────
    
       Lógica de toggle:
       - Si estado actual === 'activo' → nuevo estado = 'inactivo'
       - Si estado actual === anything else → nuevo estado = 'activo'
       
       Ejemplo:
       $row['estado'] = 'activo'   → $nuevo = 'inactivo'
       $row['estado'] = 'inactivo' → $nuevo = 'activo'
    */
    
    $nuevo = ($row['estado'] === 'activo') ? 'inactivo' : 'activo';
    
    /* ────────────────────────────────────────────────────────────────────────
       BLOQUE 4f - Actualizar Estado en BD
    ────────────────────────────────────────────────────────────────────────
    
       Query preparada:
       UPDATE planes_gastronomicos SET estado = ? WHERE id = ?
       
       Parámetros: [$nuevo, $id]
       - Cambia el estado al nuevo valor
       - Solo del plan con ID especificado
    */
    
    $pdo->prepare('UPDATE planes_gastronomicos SET estado = ? WHERE id = ?')->execute([$nuevo, $id]);

    /* ────────────────────────────────────────────────────────────────────────
       BLOQUE 4g - Devolver Respuesta con Nuevo Estado
    ────────────────────────────────────────────────────────────────────────
    
       Respuesta:
       {
           "ok": true,
           "msg": "Plan activado correctamente." o "Plan inactivado correctamente.",
           "estado": "activo" o "inactivo"
       }
       
       Frontend recibe:
       1. ok: true → la operación fue exitosa
       2. msg: mensaje de confirmación (para toast)
       3. estado: el nuevo estado
       
       JavaScript usa estado para:
       - Actualizar badge de estado (verde/rojo) SIN recargar tabla
       - Cambiar icono del botón (toggle_on ↔ toggle_off)
       - Actualizar estadísticas de planes activos
       
       ¡TODO ESTO OCURRE SIN F5! → Mejor UX
    */
    
    echo json_encode([
        'ok'     => true,
        'msg'    => 'Plan ' . ($nuevo === 'activo' ? 'activado' : 'inactivado') . ' correctamente.',
        'estado' => $nuevo,
    ]);
    exit;
}

/* ════════════════════════════════════════════════════════════════════════════
   BLOQUE 5 - Método No Soportado
════════════════════════════════════════════════════════════════════════════

   Si llegan aquí: método HTTP no es GET, PUT ni POST
   (por ej: DELETE, PATCH, HEAD, OPTIONS, etc)
   
   Responder con error 405 (Method Not Allowed) sería más correcto,
   pero por ahora devolvemos 200 + JSON con ok:false
*/

echo json_encode(['ok' => false, 'msg' => 'Método no soportado.']);
