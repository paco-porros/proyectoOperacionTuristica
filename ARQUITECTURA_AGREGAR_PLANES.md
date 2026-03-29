# Arquitectura: Sistema de Agregar Planes (Turísticos y Gastronómicos)

**Documento Educativo para Comprender el Flujo Completo**

---

## 📋 Tabla de Contenidos
1. [Visión General](#visión-general)
2. [Flujo de Datos](#flujo-de-datos)
3. [Componentes Implementados](#componentes-implementados)
4. [Ciclos de Vida (Lifecycle)](#ciclos-de-vida)
5. [Seguridad](#seguridad)
6. [Puntos Clave de Aprendizaje](#puntos-clave-de-aprendizaje)

---

## 🎯 Visión General

### ¿Qué hace este sistema?

Permite a administradores crear nuevos planes turísticos y gastronómicos desde el dashboard:
- **Planes Turísticos**: Viajes, excursiones (ubicación + días de duración)
- **Planes Gastronómicos**: Experiencias en restaurantes (categoría + horas + aforo)

### ¿Cómo lo logra?

```
Usuario (Dashboard) 
    ↓ (click en "Agregar Plan")
Modal Crear Plan (HTML)
    ↓ (selecciona imagen, llena form)
FormData (JavaScript)
    ↓ (POST con archivo + datos)
Servidor PHP
    ↓ (valida, procesa imagen, inserta BD)
Respuesta JSON
    ↓ (ok:true + nuevo plan)
Tabla Actualizada (sin F5)
```

---

## 🔄 Flujo de Datos

### Fase 1: Usuario Abre Modal

```javascript
Usuario hace click en "Agregar Plan Gastronómico"
    ↓
abrirCrearPlan('gastronomico')
    ├─ Limpia formulario (reset)
    ├─ Muestra campos gastronómicos (oculta turísticos)
    └─ cargarRestaurantes()
        ├─ fetch GET /ajax/admin_planes_gastronomicos.php
        ├─ Recibe: { planes, stats, restaurantes }
        └─ JavaScript llena <select> con restaurantes
    
Modal visible al usuario
```

**Archivos Involucrados:**
- `dashboard-administrador.php` (BLOQUE 1: abrirCrearPlan)
- `admin_planes_gastronomicos.php` (BLOQUE 2d: devuelve restaurantes)

---

### Fase 2: Usuario Selecciona Imagen

```javascript
Usuario selecciona archivo en input[type="file"]
    ↓
event listener: change
    ├─ FileReader.readAsDataURL(archivo)
    ├─ Convierte a Base64
    └─ Muestra preview en <img> (BLOQUE 5)

Usuario ve foto antes de enviar ✓
```

**Concepto Clave: FileReader API**
- Lee archivo LOCAL (seguridad navegador)
- NO accede a sistema de archivos completo
- DataURL = "data:image/jpeg;base64,/9j/4AAQSkZJRgA..."
- Perfecto para previewear

---

### Fase 3: Usuario Envía Formulario

```
Usuario hace click "Crear Plan"
    ↓
form submit event (BLOQUE 8)
    ├─ Extrae valores de inputs
    ├─ Validaciones frontend:
    │   ├─ Título no vacío
    │   ├─ Precio >= 0
    │   ├─ Restaurante seleccionado (si gastro)
    │   ├─ Ubicación (si turístico)
    │   └─ etc...
    │
    ├─ SI validación FALLA → alertaModalCrearPlan('error')
    │   └─ Usuario sigue en modal, puede corregir
    │
    └─ SI validación OK:
        ├─ Crea FormData
        ├─ formData.append('titulo', valor)
        ├─ formData.append('imagen', archivo) ← ARCHIVO BINARIO
        ├─ formData.append('restaurante_id', id) ← si gastro
        └─ fetch POST (BLOQUE 8m)
```

**Por qué FormData?**
```javascript
// ❌ NO FUNCIONA: JSON no soporta archivos
JSON.stringify({ titulo: '...', imagen: File })

// ✓ FUNCIONA: FormData maneja archivo + texto
const fd = new FormData();
fd.append('titel', 'Asado Tradicional');
fd.append('imagen', archivoFile); // Binario OK
fetch(endpoint, { method: 'POST', body: fd })
```

---

### Fase 4: Servidor Procesa Solicitud

#### 4a. PHP Recibe FormData

```php
// FormData llega como $_POST + $_FILES

$_POST['titulo']        // "Asado Tradicional"
$_POST['restaurante_id'] // "3"
$_FILES['imagen']       // Array con archivo

// $_FILES['imagen'] contiene:
[
    'name'     => 'photo.jpg',
    'type'     => 'image/jpeg',
    'tmp_name' => '/tmp/phpXXXXXX',
    'error'    => 0,
    'size'     => 245782
]
```

#### 4b. Endpoint: crear_plan_gastronomico.php

**Flujo de Validación y Procesamiento:**

```php
BLOQUE 1-3: Autenticación
├─ ¿Usuario está logueado?
└─ ¿Tiene rol admin/editor?
    └─ Si NO → 403 Forbidden

BLOQUE 4-8: Validación Frontend
├─ Título no vacío
├─ Precio números válidos
├─ Restaurante_id existe (FK check) ← IMPORTANTE
└─ Si FALLA → JSON error

BLOQUE 9: Procesamiento de Imagen
├─ Verificar MIME type (imagen/jpeg | imagen/png | imagen/webp)
├─ Verificar tamaño < 5MB
├─ Crear directorio si no existe
├─ Generar nombre único: turismo_gastro_[timestamp]_[uniqid].jpg
├─ Validar permisos de escritura
└─ move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta)

BLOQUE 10: Inserción en BD
├─ Preparar INSERT:
│   INSERT INTO planes_gastronomicos 
│   (restaurante_id, titulo, descripcion, categoria, duracion_horas,
│    precio_desde, imagen_hero_url, estado, created_at)
│   VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
│
├─ Ejecutar con parámetros (prepared statement)
├─ Si BD FALLA:
│   ├─ unlink($ruta) ← Borrar imagen que subió
│   └─ JSON error
│
└─ Si BD OK → JSON { ok: true, id: 15, msg: "Plan creado" }
```

**Pseudo-código de manejo de imagen:**

```php
// VALIDACIÓN
if (empty($_FILES['imagen']['tmp_name'])) {
    throw new Exception('No se subió imagen');
}

$mime = mime_content_type($_FILES['imagen']['tmp_name']);
if (!in_array($mime, ['image/jpeg', 'image/png', 'image/webp'])) {
    throw new Exception('Tipo de imagen no permitido'); // 403
}

if ($_FILES['imagen']['size'] > 5 * 1024 * 1024) {
    throw new Exception('Imagen muy pesada (max 5MB)'); // 413
}

// ALMACENAMIENTO
$ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
$nombreUnico = 'turismo_gastro_' . time() . '_' . uniqid() . '.' . strtolower($ext);
// Ejemplo: turismo_gastro_1711788000_65a4d3f2a1.jpg

$rutaDestino = __DIR__ . '/../uploads/' . $nombreUnico;
if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
    throw new Exception('Error al guardar imagen');
}

// BD
try {
    $stmt = $pdo->prepare("INSERT INTO ... VALUES (?, ?, ...)");
    $stmt->execute([..., '/uploads/' . $nombreUnico, ...]);
    echo json_encode(['ok' => true, 'id' => $pdo->lastInsertId()]);
} catch (PDOException $e) {
    unlink($rutaDestino); // Cleanup: borrar la imagen
    throw $e;
}
```

---

### Fase 5: Servidor Responde a Frontend

```json
✓ ÉXITO:
{
    "ok": true,
    "msg": "Plan gastronómico creado correctamente",
    "data": {
        "id": 15,
        "titulo": "Asado Tradicional"
    }
}

✗ ERROR:
{
    "ok": false,
    "msg": "La imagen debe ser JPEG, PNG o WebP"
}
```

---

### Fase 6: JavaScript Procesa Respuesta

```javascript
const data = await res.json();

if (data.ok) {
    // ✓ ÉXITO
    cerrarCrearPlan();                    // Cierra modal
    toast(data.msg, 'ok');               // Toast verde abajo
    cargarPlanesGastronomicos();          // AJAX reload tabla
    // Usuario ve tabla actualizada SIN F5 ✓
} else {
    // ✗ ERROR
    alertaModalCrearPlan(data.msg, 'error');
    // Modal permanece abierto, usuario ve error
}
```

---

## 🏗️ Componentes Implementados

### Archivos Creados (Nueva Funcionalidad)

| Archivo | Tipo | Propósito |
|---------|------|----------|
| `ajax/crear_plan_turistico.php` | PHP Endpoint | POST: crear plan turístico |
| `ajax/crear_plan_gastronomico.php` | PHP Endpoint | POST: crear plan gastronómico |

### Archivos Modificados (Existentes)

| Archivo | Cambios |
|---------|---------|
| `dashboard-administrador.php` | + Modal HTML para crear planes + JavaScript (8 bloques) |
| `ajax/admin_planes_gastronomicos.php` | Ahora devuelve `restaurantes[]` en GET |

### Estructura HTML en Dashboard

```html
<!-- Modal para crear planes (turístico O gastronómico) -->
<div id="modal-crear-plan" style="z-index:1003; ...">
    <!-- Campos turísticos -->
    <div id="campos-crear-turistico" style="display:none;">
        <input id="crear-plan-ubicacion" />
        <input id="crear-plan-duracion-dias" type="number" />
    </div>
    
    <!-- Campos gastronómicos -->
    <div id="campos-crear-gastronomico" style="display:none;">
        <select id="crear-plan-restaurante">
            <!-- Llenado dinámicamente por JavaScript -->
        </select>
        <input id="crear-plan-categoria" />
        <input id="crear-plan-duracion-horas" type="number" />
        <input id="crear-plan-max-personas" type="number" />
    </div>
    
    <!-- Común -->
    <input id="crear-plan-titulo" />
    <input id="crear-plan-descripcion" />
    <input id="crear-plan-precio" type="number" />
    <input id="crear-plan-imagen" type="file" accept="image/*" />
    <div id="preview-contenedor">
        <img id="preview-imagen" /> <!-- Preview FileReader -->
    </div>
</div>
```

---

## 🔄 Ciclos de Vida (Lifecycle)

### Ciclo Crear Plan Turístico

```
1. cargarSeccion('planes-turisticos')
   └─ GET /ajax/admin_planes_turisticos.php
      └─ Tabla se llena

2. Usuario click "Agregar Plan"
   └─ abrirCrearPlan('turistico')
      ├─ Muestra campos turísticos
      └─ Modal visible

3. Usuario llena form + selecciona imagen
   └─ FileReader preview visible

4. Usuario submit form
   └─ Validaciones frontend OK
   └─ FormData created
   └─ POST /ajax/crear_plan_turistico.php

5. Servidor procesa y devuelve { ok: true }

6. cerrarCrearPlan()

7. cargarPlanesTuristicos() → tabla se recarga sin F5

8. Usuario ve nuevo plan en tabla ✓
```

### Ciclo Crear Plan Gastronómico

```
1. cargarSeccion('planes-gastronomicos')
   └─ GET /ajax/admin_planes_gastronomicos.php
      ├─ Tabla se llena
      ├─ restaurantes[] se carga (pero no visible aún)

2. Usuario click "Agregar Plan"
   └─ abrirCrearPlan('gastronomico')
      ├─ cargarRestaurantes()
      │  └─ Llena <select id="crear-plan-restaurante">
      └─ Modal visible con restaurantes disponibles

3. Usuario selecciona restaurante + llena form + imagen

4. FormData includes restaurante_id

5. POST /ajax/crear_plan_gastronomico.php
   └─ Servidor verifica restaurante_id existe (FK)
   └─ Si NO existe → 403 Forbidden

6. Si OK → cargarPlanesGastronomicos()
   └─ Recarga tabla con nuevo plan ✓
```

---

## 🔐 Seguridad

### Frontend (Protección UX/Performance)

| Tipo | Implementación |
|------|-----------------|
| **Validación de Campo** | `required`, `type="number"`, `type="email"` |
| **Validación JS** | if statements antes de FormData |
| **Confirmaciones** | `confirm()` antes de operaciones críticas |
| **Visual Feedback** | Botón se deshabilita, texto cambia a "Creando..." |

### Backend (Protección Real)

| Tipo | Implementación |
|------|-----------------|
| **Autenticación** | `estaLogueado()` → 401 si no |
| **Autorización** | Rol check `['admin', 'editor']` → 403 si no |
| **SQL Injection** | Prepared statements con `?` placeholders |
| **Tamaño Archivo** | `$_FILES['size'] < 5MB` |
| **Tipo MIME** | Whitelist: `image/jpeg`, `image/png`, `image/webp` |
| **Nombre Único** | Timestamp + uniqid para evitar colisiones |
| **FK Validation** | Verifica `restaurante_id` existe en BD |
| **Cleanup** | `unlink()` si BD falla → no deja archivos huérfanos |

### Flujo de Seguridad

```
Solicitud POST /crear_plan_gastronomico.php
    ↓
¿Sesión activa? → NO: 401 → Stop
    ↓ YES
¿Es admin/editor? → NO: 403 → Stop
    ↓ YES
¿Archivo en $_FILES? → NO: Error → Stop
    ↓ YES
¿MIME valido? → NO: Error → Stop
    ↓ YES
¿Tamaño < 5MB? → NO: Error → Stop
    ↓ YES
¿Restaurante_id existe? → NO: Error → Stop
    ↓ YES
Insertar en BD (prepared statement)
    ↓
¿BD OK? → Guardar imagen → Devolver éxito
    ↓ NO
Tipo error? → Si imagen subió, borrar (unlink)
    ↓
Devolver error
```

---

## 📚 Puntos Clave de Aprendizaje

### 1. FormData vs JSON

```javascript
// Para enviar SOLO datos:
fetch(url, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ titulo: 'Asado' })
})

// Para enviar archivo + datos:
const fd = new FormData();
fd.append('titulo', 'Asado');
fd.append('imagen', fileInput.files[0]); // ← Archivo binario
fetch(url, {
    method: 'POST',
    body: fd // ← FormData automáticamente setea multipart/form-data
})
```

### 2. FileReader API

```javascript
const file = document.getElementById('input').files[0];
const reader = new FileReader();

reader.onload = (event) => {
    // event.target.result = "data:image/jpeg;base64,/9j/4A..."
    document.getElementById('preview').src = event.target.result;
};

reader.readAsDataURL(file);
```

### 3. Prepared Statements

```php
// ❌ Vulnerable (SQL injection):
$stmt = $pdo->query("SELECT * FROM users WHERE id = " . $_GET['id']);

// ✓ Seguro (parámetros escapados):
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_GET['id']]);
```

### 4. Modal Dinámico

```javascript
// Mostrar campos según tipo
if (tipo === 'turistico') {
    document.getElementById('campos-turistico').style.display = '';
    document.getElementById('campos-gastronomico').style.display = 'none';
} else {
    document.getElementById('campos-turistico').style.display = 'none';
    document.getElementById('campos-gastronomico').style.display = '';
}
```

### 5. SPA (Single Page Application)

```javascript
// Sin recargar página (F5):
await fetch(endpoint, { method, body });
const data = await response.json();

if (data.ok) {
    // Recargar tabla via AJAX
    await cargarPlanesTuristicos(); // Fetch + DOM update
    
    // Usuario ve tabla actualizada SIN interrupciones
}
```

---

## 📊 Diagrama de Interacción

```
┌─────────────────┐
│ Dashboard Admin │
└────────┬────────┘
         │ Click "Agregar Plan"
         ↓
┌──────────────────────────┐
│ abrirCrearPlan(tipo)     │
│ - Limpiar formulario     │
│ - Mostrar campos según   │
│   tipo (turístico/gastro)│
│ - Si gastro: cargar      │
│   restaurantes via AJAX  │
└────────┬─────────────────┘
         │ GET /ajax/admin_planes_gastronomicos.php
         ↓
┌──────────────────────────┐
│ PHP: admin_planes...     │
│ - Devuelve planes[]      │
│ - Devuelve restaurantes[]│ ← KEY POINT
└────────┬─────────────────┘
         │ JSON response
         ↓
┌──────────────────────────┐
│ JavaScript: cargar       │
│ Restaurantes()           │
│ - Llena <select>         │
└──────────────────────────┘
         │
         ↓ Usuario selecciona restaurante + llena form
         │ + selecciona imagen
         │
         ↓ Usuario click "Crear Plan"
         │
┌──────────────────────────┐
│ Form Submit              │
│ - Validaciones frontend  │
│ - Crea FormData          │
│ - Append archivo a FD    │
└────────┬─────────────────┘
         │ POST + FormData
         ↓
┌──────────────────────────────────┐
│ PHP: crear_plan_gastronomico.php │
│ - Auth check (401/403)           │
│ - Validar parámetros             │
│ - Validar archivo (MIME, size)   │
│ - FK check (restaurante existe)  │
│ - Procesar imagen                │
│ - INSERT en BD                   │
│ - Si BD OK: devolver éxito        │
│ - Si BD FAIL: unlink() + error    │
└────────┬─────────────────────────┘
         │ JSON { ok, msg, data }
         ↓
┌──────────────────────────┐
│ JavaScript: procesa      │
│ respuesta                │
│ - Cierra modal           │
│ - Toast éxito            │
│ - Recarga tabla via AJAX │
└──────────────────────────┘
         │
         ↓ Usuario ve tabla actualizada SIN F5 ✓
```

---

## 🚀 Resumen

El sistema **"Agregar Planes"** demuestra:

1. **Frontend Dinámico**: Modal que se adapta según tipo (turístico/gastro)
2. **File Upload**: FormData + FileReader para manejo de imágenes
3. **AJAX**: Cargar datos sin recargar página (SPA pattern)
4. **Backend Robusto**: Validaciones dobles (frontend + backend)
5. **Seguridad**: Autenticación, autorización, SQL injection prevention
6. **UX**: Feedback visual (preview, toasts, spinners)
7. **Database**: Prepared statements, FK constraints, transactions

**Archivos de Documentación:**
- `crear_plan_turistico.php`: 21 bloques de documentación
- `crear_plan_gastronomico.php`: 17 bloques de documentación
- `dashboard-administrador.php`: 8 bloques en sección JavaScript
- `admin_planes_gastronomicos.php`: 5 bloques (GET/PUT/POST)
- Este archivo: Arquitectura y flujo completo

**Total: ~100+ bloques de documentación profesional para aprender cómo funciona todo.** 📚
