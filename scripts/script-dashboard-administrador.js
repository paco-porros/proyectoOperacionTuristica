/* =============================================
   CONFIGURACIÓN DE TAILWIND CSS
   Define los tokens de diseño del sistema:
   colores del Material Design personalizado,
   tipografías y radios de borde.
   ============================================= */
tailwind.config = {
    darkMode: "class",
    theme: {
        extend: {
            colors: {
                "inverse-surface": "#003739",
                "on-primary-container": "#e0e8ff",
                "on-secondary-fixed-variant": "#104b6e",
                "surface-container-low": "#cbfdff",
                "on-tertiary-container": "#93f8ff",
                "on-secondary-fixed": "#001e31",
                "tertiary-container": "#007479",
                "secondary-fixed": "#cce5ff",
                "outline-variant": "#c3c6d4",
                "on-secondary-container": "#285c81",
                "on-tertiary-fixed": "#002022",
                "surface-container": "#bbf9fc",
                "on-primary-fixed": "#001a41",
                "inverse-on-surface": "#bdfcff",
                "tertiary": "#00595e",
                "surface-bright": "#e5feff",
                "background": "#e5feff",
                "on-tertiary-fixed-variant": "#004f53",
                "on-primary-fixed-variant": "#004494",
                "on-tertiary": "#ffffff",
                "on-background": "#002021",
                "on-surface-variant": "#424752",
                "on-error": "#ffffff",
                "surface-container-lowest": "#ffffff",
                "tertiary-fixed-dim": "#65d7de",
                "primary-fixed-dim": "#adc6ff",
                "error-container": "#ffdad6",
                "tertiary-fixed": "#84f4fb",
                "on-surface": "#002021",
                "on-primary": "#ffffff",
                "outline": "#737783",
                "on-secondary": "#ffffff",
                "secondary": "#306388",
                "surface-dim": "#a7e5e8",
                "on-error-container": "#93000a",
                "surface-container-highest": "#afedf0",
                "secondary-container": "#a4d4ff",
                "primary-container": "#3066be",
                "primary": "#054da4",
                "surface-tint": "#225cb3",
                "inverse-primary": "#adc6ff",
                "surface-container-high": "#b5f3f6",
                "surface": "#e5feff",
                "error": "#ba1a1a",
                "surface-variant": "#afedf0",
                "secondary-fixed-dim": "#9bccf6",
                "primary-fixed": "#d8e2ff"
            },
            fontFamily: {
                "headline": ["Plus Jakarta Sans"],
                "body": ["Manrope"],
                "label": ["Manrope"]
            },
            borderRadius: {
                "DEFAULT": "1rem",
                "lg": "2rem",
                "xl": "3rem",
                "full": "9999px"
            },
        },
    },
};

/* =============================================
   VARIABLES DE ESTADO GLOBAL
   paginaActual    → página visible en la tabla de usuarios
   busquedaTimer   → referencia del timeout para el debounce del buscador
   idEliminarPendiente → id del usuario a eliminar (se guarda hasta confirmar)
   ============================================= */
let paginaActual        = 1;
let busquedaTimer       = null;
let idEliminarPendiente = null;

/* =============================================
   HELPER — TOAST (notificación flotante)
   Muestra un mensaje temporal en la esquina
   inferior derecha durante 3.5 segundos.
   tipo: 'ok' → verde | cualquier otro → rojo
   ============================================= */
function toast(msg, tipo) {
    const t = document.getElementById('toast-admin');
    t.textContent      = msg;
    t.style.display    = 'block';
    t.style.background = tipo === 'ok' ? '#d1fae5' : '#fee2e2';
    t.style.color      = tipo === 'ok' ? '#065f46' : '#7f1d1d';
    t.style.border     = `1px solid ${tipo === 'ok' ? '#6ee7b7' : '#fca5a5'}`;

    /* requestAnimationFrame garantiza que el navegador aplique display:block
       antes de la transición de opacidad, evitando que el fade-in se salte */
    requestAnimationFrame(() => { t.style.opacity = '1'; });

    setTimeout(() => {
        t.style.opacity = '0';
        setTimeout(() => { t.style.display = 'none'; }, 300);
    }, 3500);
}

/* =============================================
   HELPER — ALERTA DENTRO DEL MODAL DE USUARIO
   Muestra un banner de éxito o error dentro
   del formulario de crear/editar usuario.
   ============================================= */
function alertaModal(msg, tipo) {
    const el = document.getElementById('modal-alerta');
    el.textContent      = msg;
    el.style.display    = 'block';
    el.style.background = tipo === 'ok' ? 'rgba(16,185,129,.12)' : 'rgba(239,68,68,.12)';
    el.style.color      = tipo === 'ok' ? '#065f46' : '#7f1d1d';
    el.style.border     = `1px solid ${tipo === 'ok' ? '#6ee7b7' : '#fca5a5'}`;
}

/* =============================================
   HELPER — BADGE DE ROL
   Devuelve el HTML del chip de rol del usuario,
   eligiendo la variante de color correcta.
   ============================================= */
function rolBadge(rol) {
    const mapa = {
        admin:   'etiqueta-rol--admin',
        editor:  'etiqueta-rol--editor',
        viewer:  'etiqueta-rol--viewer',
        cliente: 'etiqueta-rol--viewer',
    };
    return `<span class="etiqueta-rol ${mapa[rol] || 'etiqueta-rol--viewer'}">${rol}</span>`;
}

/* =============================================
   HELPER — AVATAR DE USUARIO
   Si el usuario tiene avatar propio lo usa;
   si no, genera una imagen inicial con
   ui-avatars.com usando su nombre.
   ============================================= */
function avatarSrc(u) {
    return u.avatar_url ||
        `https://ui-avatars.com/api/?name=${encodeURIComponent(u.nombre)}&background=afedf0&color=054da4&size=40`;
}

/* =============================================
   CARGAR USUARIOS (AJAX)
   Consulta /ajax/admin_usuarios.php con la
   página actual y el término de búsqueda.
   Actualiza stats, filas de la tabla y paginación.
   ============================================= */
async function cargarUsuarios(pagina = 1) {
    paginaActual = pagina;
    const q   = document.getElementById('buscador-usuarios').value.trim();
    const url = `/ajax/admin_usuarios.php?page=${pagina}&q=${encodeURIComponent(q)}`;

    try {
        const res  = await fetch(url);
        const text = await res.text();
        let data;

        /* Intentamos parsear JSON; si falla, significa que el servidor devolvió
           HTML de error (PHP fatal, 404, etc.) — lo mostramos en consola */
        try {
            data = JSON.parse(text);
        } catch (_) {
            console.error('Respuesta no-JSON del servidor:', text);
            toast(`Error del servidor (${res.status}). Revisa la consola.`, 'error');
            return;
        }

        if (!data.ok) { toast(data.msg || 'Error al cargar usuarios.', 'error'); return; }

        /* — Tarjetas de estadísticas — */
        document.getElementById('stat-total').textContent   = data.stats.total   ?? '—';
        document.getElementById('stat-activos').textContent = data.stats.activos  ?? '—';

        /* — Texto de paginación: "Mostrando X–Y de Z usuarios" — */
        const desde = (pagina - 1) * 10 + 1;
        const hasta = Math.min(pagina * 10, data.total);
        document.getElementById('info-paginacion').textContent =
            `Mostrando ${desde}–${hasta} de ${data.total} usuarios`;

        /* — Renderizar filas de la tabla — */
        const tbody = document.getElementById('tabla-cuerpo');
        if (!data.usuarios.length) {
            tbody.innerHTML = `<tr><td colspan="6" style="padding:2rem;text-align:center;color:#306388;">No se encontraron usuarios.</td></tr>`;
        } else {
            tbody.innerHTML = data.usuarios.map(u => `
            <tr class="fila-usuario" data-id="${u.id}">
                <td class="tabla-usuarios__celda">
                    <div class="avatar-usuario">
                        <img alt="${u.nombre}" class="avatar-usuario__imagen" src="${avatarSrc(u)}"/>
                    </div>
                </td>
                <td class="tabla-usuarios__celda">
                    <p class="usuario__nombre">${u.nombre}</p>
                </td>
                <td class="tabla-usuarios__celda">
                    <p class="usuario__email">${u.email}</p>
                </td>
                <td class="tabla-usuarios__celda">${rolBadge(u.rol)}</td>
                <td class="tabla-usuarios__celda">
                    <div class="estado-usuario">
                        <div class="estado-usuario__punto estado-usuario__punto--${u.estado === 'activo' ? 'activo' : 'inactivo'}"></div>
                        <span class="estado-usuario__texto--${u.estado === 'activo' ? 'activo' : 'inactivo'}">${u.estado === 'activo' ? 'Activo' : 'Inactivo'}</span>
                    </div>
                </td>
                <td class="tabla-usuarios__celda--derecha">
                    <div class="acciones-fila">
                        <button class="boton-accion boton-accion--editar" onclick="abrirEditar(${u.id},'${u.nombre.replace(/'/g,"\\'")}','${u.email}','${u.rol}','${u.estado}')">
                            <span class="material-symbols-outlined">edit</span>
                        </button>
                        <button class="boton-accion boton-accion--eliminar" onclick="pedirEliminar(${u.id},'${u.nombre.replace(/'/g,"\\'")}')">
                            <span class="material-symbols-outlined">delete</span>
                        </button>
                    </div>
                </td>
            </tr>`).join('');
        }

        /* — Controles de paginación — */
        renderPaginacion(data.pagina, data.paginas);

    } catch (err) {
        toast('Error de conexión al cargar usuarios.', 'error');
        console.error(err);
    }
}

/* =============================================
   RENDERIZAR PAGINACIÓN
   Genera botones anterior/siguiente + números
   de página. El botón de la página actual usa
   la clase --activo (resaltado azul).
   ============================================= */
function renderPaginacion(actual, total) {
    const cont = document.getElementById('paginacion');
    let html = `<button class="paginacion__boton" ${actual <= 1 ? 'disabled' : ''} onclick="cargarUsuarios(${actual - 1})">
        <span class="material-symbols-outlined">chevron_left</span>
    </button>`;

    for (let i = 1; i <= total; i++) {
        html += `<button class="${i === actual ? 'paginacion__boton--activo' : 'paginacion__boton--inactivo'}" onclick="cargarUsuarios(${i})">${i}</button>`;
    }

    html += `<button class="paginacion__boton" ${actual >= total ? 'disabled' : ''} onclick="cargarUsuarios(${actual + 1})">
        <span class="material-symbols-outlined">chevron_right</span>
    </button>`;

    cont.innerHTML = html;
}

/* =============================================
   MODAL — CREAR / EDITAR USUARIO
   abrirModal()  → función base que rellena todos
                   los campos y muestra el modal.
   abrirNuevo()  → llama a abrirModal sin datos.
   abrirEditar() → llama a abrirModal con datos del usuario.
   cerrarModal() → oculta el modal.
   ============================================= */
function abrirModal(titulo, id = '', nombre = '', email = '', rol = 'cliente', estado = 'activo') {
    document.getElementById('modal-titulo').textContent        = titulo;
    document.getElementById('modal-user-id').value            = id;
    document.getElementById('modal-nombre').value             = nombre;
    document.getElementById('modal-email').value              = email;
    document.getElementById('modal-rol').value                = rol;
    document.getElementById('modal-estado').value             = estado;
    document.getElementById('modal-password').value           = '';
    document.getElementById('modal-alerta').style.display     = 'none';

    /* El hint "(dejar vacío para no cambiar)" solo es relevante al editar */
    document.getElementById('pass-hint').style.display = id ? 'inline' : 'none';

    document.getElementById('modal-usuario').style.display = 'flex';
}

function abrirNuevo()                              { abrirModal('Nuevo Usuario'); }
function abrirEditar(id, nombre, email, rol, estado) { abrirModal('Editar Usuario', id, nombre, email, rol, estado); }
function cerrarModal()                             { document.getElementById('modal-usuario').style.display = 'none'; }

/* — Listeners de apertura/cierre del modal de usuario — */
document.getElementById('modal-cerrar').addEventListener('click', cerrarModal);
document.getElementById('modal-btn-cancelar').addEventListener('click', cerrarModal);
document.getElementById('btn-nuevo-usuario').addEventListener('click', abrirNuevo);

/* Cerrar el modal al hacer clic sobre el fondo oscuro */
document.getElementById('modal-usuario').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});

/* =============================================
   FORMULARIO DE USUARIO — ENVÍO (AJAX)
   POST → crear usuario nuevo
   PUT  → actualizar usuario existente
   La contraseña es opcional al editar;
   obligatoria al crear.
   ============================================= */
document.getElementById('form-usuario').addEventListener('submit', async function(e) {
    e.preventDefault();

    const id       = document.getElementById('modal-user-id').value;
    const nombre   = document.getElementById('modal-nombre').value.trim();
    const email    = document.getElementById('modal-email').value.trim();
    const password = document.getElementById('modal-password').value.trim();
    const rol      = document.getElementById('modal-rol').value;
    const estado   = document.getElementById('modal-estado').value;
    const btn      = document.getElementById('modal-btn-guardar');

    /* Validaciones básicas antes de enviar */
    if (!nombre || !email) {
        alertaModal('Nombre y email son requeridos.', 'error'); return;
    }
    if (!id && !password) {
        alertaModal('La contraseña es requerida para nuevos usuarios.', 'error'); return;
    }

    btn.disabled    = true;
    btn.textContent = 'Guardando…';

    try {
        const method = id ? 'PUT' : 'POST';

        /* Al editar, la contraseña solo se incluye si el campo no está vacío
           (spread condicional para no sobrescribir la contraseña existente) */
        const body = id
            ? { id: parseInt(id), nombre, email, rol, estado, ...(password ? { password } : {}) }
            : { nombre, email, password, rol, estado };

        const res  = await fetch('/ajax/admin_usuarios.php', {
            method,
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify(body),
        });
        const data = await res.json();

        if (data.ok) {
            cerrarModal();
            toast(data.msg, 'ok');
            cargarUsuarios(paginaActual);
        } else {
            alertaModal(data.msg, 'error');
        }
    } catch (err) {
        alertaModal('Error de conexión.', 'error');
    }

    btn.disabled    = false;
    btn.textContent = 'Guardar';
});

/* =============================================
   MODAL — CONFIRMAR ELIMINACIÓN DE USUARIO
   Guarda el id pendiente y espera confirmación.
   Solo hace el DELETE si el usuario confirma.
   ============================================= */
function pedirEliminar(id, nombre) {
    idEliminarPendiente = id;
    document.getElementById('eliminar-nombre-txt').textContent =
        `Esta acción eliminará permanentemente a "${nombre}".`;
    document.getElementById('modal-eliminar').style.display = 'flex';
}

document.getElementById('eliminar-cancelar').addEventListener('click', () => {
    document.getElementById('modal-eliminar').style.display = 'none';
    idEliminarPendiente = null;
});

document.getElementById('eliminar-confirmar').addEventListener('click', async () => {
    if (!idEliminarPendiente) return;

    const btn = document.getElementById('eliminar-confirmar');
    btn.disabled    = true;
    btn.textContent = 'Eliminando…';

    try {
        const res  = await fetch('/ajax/admin_usuarios.php', {
            method:  'DELETE',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify({ id: idEliminarPendiente }),
        });
        const data = await res.json();

        document.getElementById('modal-eliminar').style.display = 'none';
        idEliminarPendiente = null;

        if (data.ok) {
            toast(data.msg, 'ok');
            cargarUsuarios(paginaActual);
        } else {
            toast(data.msg, 'error');
        }
    } catch (err) {
        toast('Error de conexión.', 'error');
    }

    btn.disabled    = false;
    btn.textContent = 'Eliminar';
});

/* Cerrar el modal de eliminar al hacer clic en el fondo oscuro */
document.getElementById('modal-eliminar').addEventListener('click', function(e) {
    if (e.target === this) {
        this.style.display  = 'none';
        idEliminarPendiente = null;
    }
});

/* =============================================
   BUSCADOR CON DEBOUNCE
   Espera 400 ms tras la última tecla antes de
   lanzar la petición, evitando peticiones por
   cada carácter escrito.
   ============================================= */
document.getElementById('buscador-usuarios').addEventListener('input', () => {
    clearTimeout(busquedaTimer);
    busquedaTimer = setTimeout(() => cargarUsuarios(1), 400);
});

/* =============================================
   LOGOUT AJAX
   Llama al endpoint de cierre de sesión y
   redirige al login al terminar.
   ============================================= */
document.getElementById('btn-logout-admin').addEventListener('click', async () => {
    await fetch('/ajax/logout.php', { method: 'POST' });
    window.location.href = '/login.php';
});

/* Carga inicial de usuarios al terminar de parsear el DOM */
document.addEventListener('DOMContentLoaded', () => cargarUsuarios(1));

/* =============================================
   SPA — NAVEGACIÓN POR SECCIONES SIN RECARGA
   CONFIG_SECCIONES define el texto del encabezado
   y las etiquetas de estadísticas para cada sección.
   seccionActiva guarda cuál está visible ahora.
   ============================================= */
const CONFIG_SECCIONES = {
    'usuarios': {
        titulo:      'Gestión de Usuarios',
        descripcion: 'Controla el acceso, roles y estados de los miembros de la plataforma.',
        stat1:       'Total Usuarios',
        stat2:       'Usuarios Activos',
    },
    'planes-turisticos': {
        titulo:      'Planes Turísticos',
        descripcion: 'Administra los planes y experiencias turísticas disponibles.',
        stat1:       'Total Planes',
        stat2:       'Planes Activos',
    },
    'planes-gastronomicos': {
        titulo:      'Planes Gastronómicos',
        descripcion: 'Gestiona las experiencias y planes gastronómicos por restaurante.',
        stat1:       'Total Experiencias',
        stat2:       'Activas',
    },
    'reservas': {
        titulo:      'Gestión de Reservas',
        descripcion: 'Administra todas las reservas turísticas y gastronómicas de la plataforma.',
        stat1:       'Total Reservas',
        stat2:       'Confirmadas',
    },
};

let seccionActiva = 'usuarios';

/* ─── Marcar el enlace activo en la barra lateral ─── */
function activarNavItem(seccion) {
    document.querySelectorAll('[data-seccion]').forEach(el => {
        const esActivo = el.getAttribute('data-seccion') === seccion;
        el.className   = esActivo ? 'nav__enlace--activo' : 'nav__enlace';
        const icon     = el.querySelector('.material-symbols-outlined');
        if (icon) icon.className = esActivo
            ? 'material-symbols-outlined nav__icono--activo'
            : 'material-symbols-outlined';
    });
}

/* ─── Cambiar de sección ───
   Si el usuario hace clic en la sección ya activa,
   solo hace scroll suave al inicio del contenido.
   Si cambia de sección: actualiza encabezado, stats,
   visibilidad de paneles, buscador y botón "Nuevo",
   luego carga el contenido vía AJAX.
   ─────────────────────────── */
async function cargarSeccion(seccion) {
    if (seccion === seccionActiva) {
        document.getElementById('grilla-stats').scrollIntoView({ behavior: 'smooth', block: 'start' });
        return;
    }

    seccionActiva = seccion;
    const cfg = CONFIG_SECCIONES[seccion];

    document.getElementById('seccion-titulo').textContent      = cfg.titulo;
    document.getElementById('seccion-descripcion').textContent = cfg.descripcion;
    document.getElementById('stat-etiqueta-1').textContent     = cfg.stat1;
    document.getElementById('stat-etiqueta-2').textContent     = cfg.stat2;
    document.getElementById('stat-total').textContent          = '—';
    document.getElementById('stat-activos').textContent        = '—';

    /* Mostrar solo el panel de la sección seleccionada */
    document.getElementById('seccion-usuarios').style.display             = seccion === 'usuarios'             ? '' : 'none';
    document.getElementById('seccion-planes-turisticos').style.display    = seccion === 'planes-turisticos'    ? '' : 'none';
    document.getElementById('seccion-planes-gastronomicos').style.display = seccion === 'planes-gastronomicos' ? '' : 'none';
    document.getElementById('seccion-reservas').style.display             = seccion === 'reservas'             ? '' : 'none';

    /* Mostrar tarjetas extra solo en la sección reservas */
    const esReservas = seccion === 'reservas';
    document.getElementById('stat-extra-1').style.display = esReservas ? '' : 'none';
    document.getElementById('stat-extra-2').style.display = esReservas ? '' : 'none';

    /* El botón "Nuevo Usuario" y el buscador solo aparecen en la sección de usuarios */
    document.getElementById('btn-nuevo-usuario').style.display = seccion === 'usuarios' ? '' : 'none';
    document.querySelector('.buscador__contenedor').style.visibility = seccion === 'usuarios' ? 'visible' : 'hidden';

    activarNavItem(seccion);
    document.getElementById('grilla-stats').scrollIntoView({ behavior: 'smooth', block: 'start' });

    if (seccion === 'usuarios') {
        cargarUsuarios(1);
    } else if (seccion === 'planes-turisticos') {
        await cargarPlanesTuristicos();
    } else if (seccion === 'planes-gastronomicos') {
        await cargarPlanesGastronomicos();
    } else if (seccion === 'reservas') {
        paginaReservas = 1;
        await cargarReservas(1);
    }
}

/* Conectar los enlaces del sidebar con cargarSeccion() */
document.querySelectorAll('[data-seccion]').forEach(el => {
    el.addEventListener('click', e => {
        e.preventDefault();
        cargarSeccion(el.getAttribute('data-seccion'));
    });
});

/* =============================================
   HELPERS COMUNES PARA PLANES
   estadoBadgePlan() → HTML del indicador de estado
   accionesPlanHTML() → HTML de los botones editar/toggle
   ============================================= */

/* Genera el badge de estado (punto + texto) para una fila de plan */
function estadoBadgePlan(estado) {
    const activo = estado === 'activo';
    return `<div class="estado-usuario">
        <div class="estado-usuario__punto estado-usuario__punto--${activo ? 'activo' : 'inactivo'}"></div>
        <span class="estado-usuario__texto--${activo ? 'activo' : 'inactivo'}">${activo ? 'Activo' : 'Inactivo'}</span>
    </div>`;
}

/* Genera los botones de acción (editar + activar/inactivar) para una fila de plan.
   El botón de toggle cambia de color e icono según el estado actual del plan. */
function accionesPlanHTML(id, tipo, esActivo) {
    const iconToggle  = esActivo ? 'toggle_on'  : 'toggle_off';
    const titleToggle = esActivo ? 'Inactivar'  : 'Activar';
    return `<div class="acciones-fila">
        <button class="boton-accion boton-accion--editar"
                title="Editar"
                onclick="abrirEditarPlan(${id},'${tipo}')">
            <span class="material-symbols-outlined">edit</span>
        </button>
        <button class="boton-accion ${esActivo ? 'boton-accion--eliminar' : 'boton-accion--editar'}"
                title="${titleToggle}"
                id="btn-toggle-${tipo}-${id}"
                onclick="togglePlan(${id},'${tipo}')">
            <span class="material-symbols-outlined">${iconToggle}</span>
        </button>
    </div>`;
}

/* =============================================
   CARGAR PLANES TURÍSTICOS (AJAX)
   Obtiene la lista de planes del endpoint
   correspondiente y renderiza la tabla.
   ============================================= */
async function cargarPlanesTuristicos() {
    const tbody = document.getElementById('tbody-planes-turisticos');
    tbody.innerHTML = `<tr><td colspan="6" class="celda-cargando">Cargando planes…</td></tr>`;

    try {
        const res  = await fetch('/ajax/admin_planes_turisticos.php');
        const data = await res.json();

        if (!data.ok) { toast('Error al cargar planes turísticos.', 'error'); return; }

        document.getElementById('stat-total').textContent   = data.stats.total   ?? '—';
        document.getElementById('stat-activos').textContent = data.stats.activos ?? '—';
        document.getElementById('planes-turisticos-count').textContent =
            data.planes.length + ' planes (' + (data.stats.activos ?? 0) + ' activos)';

        if (!data.planes.length) {
            tbody.innerHTML = `<tr><td colspan="6" class="celda-cargando">No hay planes disponibles.</td></tr>`;
            return;
        }

        tbody.innerHTML = data.planes.map(p => `
        <tr class="fila-usuario" id="fila-turistico-${p.id}">
            <td class="tabla-usuarios__celda">
                <div class="avatar-plan">
                    <img src="${p.imagen_hero_url || '/img/fondoPortada.jpg'}"
                         alt="${p.titulo}"
                         class="avatar-plan__img"
                         onerror="this.src='/img/fondoPortada.jpg'"/>
                </div>
            </td>
            <td class="tabla-usuarios__celda">
                <p class="usuario__nombre">${p.titulo}</p>
                <p class="usuario__email" style="margin-top:.2rem;">
                    <span class="material-symbols-outlined" style="font-size:.9rem;vertical-align:-.15em;">schedule</span>
                    ${p.duracion_dias} día${p.duracion_dias != 1 ? 's' : ''} &nbsp;·&nbsp;
                    <span class="material-symbols-outlined" style="font-size:.9rem;vertical-align:-.15em;">location_on</span>
                    ${p.ubicacion || '—'}
                </p>
            </td>
            <td class="tabla-usuarios__celda">
                <span class="etiqueta-rol etiqueta-rol--editor">${p.etiqueta || '—'}</span>
            </td>
            <td class="tabla-usuarios__celda">
                <p class="usuario__nombre" style="color:#054da4;">$${p.precio_formateado}</p>
                <p class="usuario__email" style="font-size:.7rem;">${p.moneda}</p>
            </td>
            <td class="tabla-usuarios__celda" id="estado-turistico-${p.id}">
                ${estadoBadgePlan(p.estado)}
            </td>
            <td class="tabla-usuarios__celda--derecha">
                ${accionesPlanHTML(p.id, 'turistico', p.estado === 'activo')}
            </td>
        </tr>`).join('');

    } catch (err) {
        toast('Error de conexión al cargar planes turísticos.', 'error');
        console.error(err);
    }
}

/* =============================================
   CARGAR PLANES GASTRONÓMICOS (AJAX)
   Ídem al bloque anterior pero para planes
   gastronómicos, con columna de restaurante.
   ============================================= */
async function cargarPlanesGastronomicos() {
    const tbody = document.getElementById('tbody-planes-gastronomicos');
    tbody.innerHTML = `<tr><td colspan="6" class="celda-cargando">Cargando planes…</td></tr>`;

    try {
        const res  = await fetch('/ajax/admin_planes_gastronomicos.php');
        const data = await res.json();

        if (!data.ok) { toast('Error al cargar planes gastronómicos.', 'error'); return; }

        document.getElementById('stat-total').textContent   = data.stats.total   ?? '—';
        document.getElementById('stat-activos').textContent = data.stats.activos ?? '—';
        document.getElementById('planes-gastronomicos-count').textContent =
            data.planes.length + ' experiencias (' + (data.stats.activos ?? 0) + ' activas)';

        if (!data.planes.length) {
            tbody.innerHTML = `<tr><td colspan="6" class="celda-cargando">No hay planes disponibles.</td></tr>`;
            return;
        }

        tbody.innerHTML = data.planes.map(p => `
        <tr class="fila-usuario" id="fila-gastronomico-${p.id}">
            <td class="tabla-usuarios__celda">
                <div class="avatar-plan">
                    <img src="${p.imagen_hero_url || '/img/fondoPortada.jpg'}"
                         alt="${p.titulo}"
                         class="avatar-plan__img"
                         onerror="this.src='/img/fondoPortada.jpg'"/>
                </div>
            </td>
            <td class="tabla-usuarios__celda">
                <p class="usuario__nombre">${p.titulo}</p>
                <p class="usuario__email" style="margin-top:.2rem;">
                    <span class="material-symbols-outlined" style="font-size:.9rem;vertical-align:-.15em;">category</span>
                    ${p.categoria || p.etiqueta || '—'}
                    ${p.duracion_horas ? '&nbsp;·&nbsp;<span class="material-symbols-outlined" style="font-size:.9rem;vertical-align:-.15em;">schedule</span> ' + p.duracion_horas + ' hr' : ''}
                </p>
            </td>
            <td class="tabla-usuarios__celda">
                <p class="usuario__email">${p.restaurante_nombre || '—'}</p>
            </td>
            <td class="tabla-usuarios__celda">
                <p class="usuario__nombre" style="color:#054da4;">$${p.precio_formateado}</p>
                <p class="usuario__email" style="font-size:.7rem;">${p.moneda}</p>
            </td>
            <td class="tabla-usuarios__celda" id="estado-gastronomico-${p.id}">
                ${estadoBadgePlan(p.estado)}
            </td>
            <td class="tabla-usuarios__celda--derecha">
                ${accionesPlanHTML(p.id, 'gastronomico', p.estado === 'activo')}
            </td>
        </tr>`).join('');

    } catch (err) {
        toast('Error de conexión al cargar planes gastronómicos.', 'error');
        console.error(err);
    }
}

/* =============================================
   MODAL — EDITAR PLAN (turístico / gastronómico)
   Carga los datos frescos del plan desde el
   servidor para rellenar el formulario.
   Muestra/oculta los campos específicos según el tipo.
   _cachePlanes almacena temporalmente los datos
   del plan para evitar peticiones repetidas.
   ============================================= */
const _cachePlanes = {};

async function abrirEditarPlan(id, tipo) {
    const endpoint = tipo === 'turistico'
        ? '/ajax/admin_planes_turisticos.php'
        : '/ajax/admin_planes_gastronomicos.php';

    /* Mostrar el modal de inmediato con estado de carga */
    const modal = document.getElementById('modal-plan');
    document.getElementById('modal-plan-titulo').textContent  = 'Cargando…';
    document.getElementById('modal-plan-alerta').style.display = 'none';
    modal.style.display = 'flex';

    /* Obtener datos actualizados del servidor (GET) y buscar el plan por id */
    let plan = null;
    try {
        const r = await fetch(endpoint);
        const d = await r.json();
        if (d.ok) plan = d.planes.find(p => p.id == id);
    } catch (_) {}

    if (!plan) {
        document.getElementById('modal-plan-titulo').textContent = 'Error';
        alertaModalPlan('No se pudo cargar el plan.', 'error');
        return;
    }

    _cachePlanes[`${tipo}-${id}`] = plan;

    document.getElementById('modal-plan-titulo').textContent =
        tipo === 'turistico' ? 'Editar Plan Turístico' : 'Editar Plan Gastronómico';

    /* Rellenar campos comunes */
    document.getElementById('plan-id').value           = id;
    document.getElementById('plan-tipo').value         = tipo;
    document.getElementById('plan-titulo-input').value = plan.titulo       || '';
    document.getElementById('plan-descripcion').value  = plan.descripcion  || '';
    document.getElementById('plan-precio').value       = plan.precio_desde || '';
    document.getElementById('plan-imagen').value       = plan.imagen_hero_url || '';
    document.getElementById('plan-estado').value       = plan.estado       || 'activo';

    /* Mostrar los campos específicos del tipo y ocultar los del otro */
    const campTur = document.getElementById('campos-turistico');
    const campGas = document.getElementById('campos-gastronomico');

    if (tipo === 'turistico') {
        campTur.style.display = '';
        campGas.style.display = 'none';
        document.getElementById('plan-ubicacion').value     = plan.ubicacion     || '';
        document.getElementById('plan-duracion-dias').value = plan.duracion_dias || '';
    } else {
        campTur.style.display = 'none';
        campGas.style.display = '';
        document.getElementById('plan-categoria').value      = plan.categoria      || '';
        document.getElementById('plan-duracion-horas').value = plan.duracion_horas || '';
    }
}

function cerrarModalPlan() {
    document.getElementById('modal-plan').style.display = 'none';
}

/* Alerta interna del modal de editar plan */
function alertaModalPlan(msg, tipo) {
    const el = document.getElementById('modal-plan-alerta');
    el.textContent      = msg;
    el.style.display    = 'block';
    el.style.background = tipo === 'ok' ? 'rgba(16,185,129,.12)' : 'rgba(239,68,68,.12)';
    el.style.color      = tipo === 'ok' ? '#065f46' : '#7f1d1d';
    el.style.border     = `1px solid ${tipo === 'ok' ? '#6ee7b7' : '#fca5a5'}`;
}

/* — Listeners del modal de editar plan — */
document.getElementById('modal-plan-cerrar').addEventListener('click', cerrarModalPlan);
document.getElementById('modal-plan-btn-cancelar').addEventListener('click', cerrarModalPlan);
document.getElementById('modal-plan').addEventListener('click', function(e) {
    if (e.target === this) cerrarModalPlan();
});

/* ─── Envío del formulario de editar plan (PUT) ─── */
document.getElementById('form-plan').addEventListener('submit', async function(e) {
    e.preventDefault();

    const id    = document.getElementById('plan-id').value;
    const tipo  = document.getElementById('plan-tipo').value;
    const titulo = document.getElementById('plan-titulo-input').value.trim();

    if (!titulo) { alertaModalPlan('El título es requerido.', 'error'); return; }

    const body = {
        id:              parseInt(id),
        titulo,
        descripcion:     document.getElementById('plan-descripcion').value.trim(),
        precio_desde:    parseFloat(document.getElementById('plan-precio').value) || 0,
        imagen_hero_url: document.getElementById('plan-imagen').value.trim(),
        estado:          document.getElementById('plan-estado').value,
    };

    /* Añadir campos específicos según el tipo de plan */
    if (tipo === 'turistico') {
        body.ubicacion     = document.getElementById('plan-ubicacion').value.trim();
        body.duracion_dias = parseInt(document.getElementById('plan-duracion-dias').value) || 1;
    } else {
        body.categoria      = document.getElementById('plan-categoria').value.trim();
        body.duracion_horas = parseFloat(document.getElementById('plan-duracion-horas').value) || 0;
    }

    const btn = document.getElementById('modal-plan-btn-guardar');
    btn.disabled    = true;
    btn.textContent = 'Guardando…';

    const endpoint = tipo === 'turistico'
        ? '/ajax/admin_planes_turisticos.php'
        : '/ajax/admin_planes_gastronomicos.php';

    try {
        const res  = await fetch(endpoint, {
            method:  'PUT',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify(body),
        });
        const data = await res.json();

        if (data.ok) {
            cerrarModalPlan();
            toast(data.msg, 'ok');
            /* Recargar la tabla correspondiente al tipo de plan editado */
            if (tipo === 'turistico') cargarPlanesTuristicos();
            else cargarPlanesGastronomicos();
        } else {
            alertaModalPlan(data.msg, 'error');
        }
    } catch (err) {
        alertaModalPlan('Error de conexión.', 'error');
    }

    btn.disabled    = false;
    btn.textContent = 'Guardar cambios';
});

/* =============================================
   TOGGLE — ACTIVAR / INACTIVAR PLAN
   Detecta el estado actual mirando la clase CSS
   del punto de estado en la fila, luego envía
   { action: 'toggle', id } al endpoint.
   Actualiza la fila y el botón EN EL LUGAR sin
   recargar toda la tabla (optimización UX).
   ============================================= */
async function togglePlan(id, tipo) {
    /* Determinar si el plan está activo inspeccionando el DOM de su fila */
    const esActivo = document.getElementById(`estado-${tipo}-${id}`)
        ?.querySelector('.estado-usuario__punto--activo') !== null;

    const accion = esActivo ? 'inactivar' : 'activar';
    if (!confirm(`¿Deseas ${accion} este plan?`)) return;

    const endpoint = tipo === 'turistico'
        ? '/ajax/admin_planes_turisticos.php'
        : '/ajax/admin_planes_gastronomicos.php';

    try {
        const res  = await fetch(endpoint, {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify({ action: 'toggle', id }),
        });
        const data = await res.json();

        if (data.ok) {
            toast(data.msg, 'ok');

            /* Actualizar badge de estado en la celda correspondiente */
            const celdaEstado = document.getElementById(`estado-${tipo}-${id}`);
            if (celdaEstado) celdaEstado.innerHTML = estadoBadgePlan(data.estado);

            /* Actualizar icono y color del botón toggle */
            const btnToggle = document.getElementById(`btn-toggle-${tipo}-${id}`);
            if (btnToggle) {
                const nuevoActivo = data.estado === 'activo';
                btnToggle.className = `boton-accion ${nuevoActivo ? 'boton-accion--eliminar' : 'boton-accion--editar'}`;
                btnToggle.title     = nuevoActivo ? 'Inactivar' : 'Activar';
                btnToggle.querySelector('.material-symbols-outlined').textContent =
                    nuevoActivo ? 'toggle_on' : 'toggle_off';
            }

            /* Recalcular las tarjetas de estadísticas contando puntos activos en el DOM */
            const selectorTbody = tipo === 'turistico'
                ? '#tbody-planes-turisticos'
                : '#tbody-planes-gastronomicos';
            const activos = document.querySelectorAll(`${selectorTbody} .estado-usuario__punto--activo`).length;
            const total   = document.querySelectorAll(`${selectorTbody} tr.fila-usuario`).length;
            document.getElementById('stat-total').textContent   = total;
            document.getElementById('stat-activos').textContent = activos;
        } else {
            toast(data.msg, 'error');
        }
    } catch (err) {
        toast('Error de conexión.', 'error');
        console.error(err);
    }
}

/* =============================================
   MODAL — CREAR NUEVO PLAN (turístico / gastronómico)
   abrirCrearPlan(tipo) → limpia el formulario,
   muestra los campos del tipo correcto y abre el modal.
   Si es gastronómico, carga la lista de restaurantes.
   ============================================= */
function abrirCrearPlan(tipo) {
    document.getElementById('crear-plan-tipo').value               = tipo;
    document.getElementById('modal-crear-plan-alerta').style.display = 'none';
    document.getElementById('preview-contenedor').style.display     = 'none';

    /* Resetear todos los campos del formulario */
    document.getElementById('form-crear-plan').reset();

    if (tipo === 'turistico') {
        document.getElementById('modal-crear-plan-titulo').textContent = 'Nuevo Plan Turístico';
        document.getElementById('campos-crear-turistico').style.display    = '';
        document.getElementById('campos-crear-gastronomico').style.display = 'none';
    } else {
        document.getElementById('modal-crear-plan-titulo').textContent = 'Nuevo Plan Gastronómico';
        document.getElementById('campos-crear-turistico').style.display    = 'none';
        document.getElementById('campos-crear-gastronomico').style.display = '';
        cargarRestaurantes(); /* Poblar el <select> de restaurantes */
    }

    document.getElementById('modal-crear-plan').style.display = 'flex';
}

function cerrarCrearPlan() {
    document.getElementById('modal-crear-plan').style.display = 'none';
}

/* Alerta interna del modal de crear plan */
function alertaModalCrearPlan(msg, tipo) {
    const el = document.getElementById('modal-crear-plan-alerta');
    el.textContent      = msg;
    el.style.display    = 'block';
    el.style.background = tipo === 'ok' ? 'rgba(16,185,129,.12)' : 'rgba(239,68,68,.12)';
    el.style.color      = tipo === 'ok' ? '#065f46' : '#7f1d1d';
    el.style.border     = `1px solid ${tipo === 'ok' ? '#6ee7b7' : '#fca5a5'}`;
}

/* ─── Cargar restaurantes para el <select> del plan gastronómico ───
   Consulta el mismo endpoint que la lista de planes gastronómicos,
   que también devuelve la clave `restaurantes`.
   Preserva la selección actual si el select ya tenía un valor. */
async function cargarRestaurantes() {
    try {
        const res  = await fetch('/ajax/admin_planes_gastronomicos.php');
        const data = await res.json();

        if (data.ok && data.restaurantes) {
            const select       = document.getElementById('crear-plan-restaurante');
            const opcionActual = select.value;

            select.innerHTML = '<option value="">Selecciona un restaurante</option>';
            data.restaurantes.forEach(r => {
                const opt       = document.createElement('option');
                opt.value       = r.id;
                opt.textContent = r.nombre;
                select.appendChild(opt);
            });

            select.value = opcionActual; /* Restaurar la selección previa si existía */
        }
    } catch (err) {
        console.error('Error cargando restaurantes:', err);
    }
}

/* ─── Preview de imagen al seleccionar archivo ───
   Usa FileReader para leer el archivo localmente
   y mostrarlo antes de enviarlo al servidor. */
document.getElementById('crear-plan-imagen').addEventListener('change', function() {
    const archivo = this.files[0];
    if (!archivo) {
        document.getElementById('preview-contenedor').style.display = 'none';
        return;
    }

    const reader    = new FileReader();
    reader.onload   = (evt) => {
        document.getElementById('preview-imagen').src = evt.target.result;
        document.getElementById('preview-contenedor').style.display = 'block';
    };
    reader.readAsDataURL(archivo);
});

/* — Listeners de apertura del modal de crear plan — */
document.getElementById('btn-agregar-plan-turistico').addEventListener('click', () => {
    abrirCrearPlan('turistico');
});
document.getElementById('btn-agregar-plan-gastronomico').addEventListener('click', () => {
    abrirCrearPlan('gastronomico');
});

/* — Listeners de cierre del modal de crear plan — */
document.getElementById('modal-crear-plan-cerrar').addEventListener('click', cerrarCrearPlan);
document.getElementById('modal-crear-plan-btn-cancelar').addEventListener('click', cerrarCrearPlan);
document.getElementById('modal-crear-plan').addEventListener('click', function(e) {
    if (e.target === this) cerrarCrearPlan();
});

/* ─── Envío del formulario de crear plan (POST multipart) ───
   Se usa FormData porque el formulario incluye un archivo de imagen.
   El navegador establece automáticamente el Content-Type multipart/form-data.
   Las validaciones se ejecutan antes de armar el FormData para dar
   feedback rápido sin desperdiciar una petición. */
document.getElementById('form-crear-plan').addEventListener('submit', async function(e) {
    e.preventDefault();

    const tipo    = document.getElementById('crear-plan-tipo').value;
    const titulo  = document.getElementById('crear-plan-titulo').value.trim();
    const desc    = document.getElementById('crear-plan-descripcion').value.trim();
    const precio  = parseFloat(document.getElementById('crear-plan-precio').value) || 0;
    const estado  = document.getElementById('crear-plan-estado').value;
    const archivo = document.getElementById('crear-plan-imagen').files[0];

    /* — Validaciones generales — */
    if (!titulo) {
        alertaModalCrearPlan('El título es requerido.', 'error'); return;
    }
    if (precio < 0) {
        alertaModalCrearPlan('El precio no puede ser negativo.', 'error'); return;
    }

    /* — Validaciones específicas por tipo — */
    if (tipo === 'turistico') {
        const ubicacion = document.getElementById('crear-plan-ubicacion').value.trim();
        if (!ubicacion) {
            alertaModalCrearPlan('La ubicación es requerida.', 'error'); return;
        }
    } else {
        const restaurante = document.getElementById('crear-plan-restaurante').value;
        const categoria   = document.getElementById('crear-plan-categoria').value.trim();
        if (!restaurante) {
            alertaModalCrearPlan('Debes seleccionar un restaurante.', 'error'); return;
        }
        if (!categoria) {
            alertaModalCrearPlan('La categoría es requerida.', 'error'); return;
        }
    }

    /* — Construir FormData (necesario para enviar el archivo de imagen) — */
    const formData = new FormData();
    formData.append('titulo',       titulo);
    formData.append('descripcion',  desc);
    formData.append('precio_desde', precio);
    formData.append('estado',       estado);
    if (archivo) formData.append('imagen', archivo);

    if (tipo === 'turistico') {
        formData.append('ubicacion',     document.getElementById('crear-plan-ubicacion').value.trim());
        formData.append('duracion_dias', parseInt(document.getElementById('crear-plan-duracion-dias').value) || 1);
    } else {
        formData.append('restaurante_id',  document.getElementById('crear-plan-restaurante').value);
        formData.append('categoria',       document.getElementById('crear-plan-categoria').value.trim());
        formData.append('duracion_horas',  parseFloat(document.getElementById('crear-plan-duracion-horas').value) || 0);
        formData.append('max_personas',    parseInt(document.getElementById('crear-plan-max-personas').value) || 10);
    }

    const btn = document.getElementById('modal-crear-plan-btn-guardar');
    btn.disabled    = true;
    btn.textContent = 'Creando…';

    try {
        const endpoint = tipo === 'turistico'
            ? '/ajax/crear_plan_turistico.php'
            : '/ajax/crear_plan_gastronomico.php';

        /* No se pasa Content-Type manual: el navegador lo añade con el boundary correcto */
        const res  = await fetch(endpoint, { method: 'POST', body: formData });
        const data = await res.json();

        if (data.ok) {
            cerrarCrearPlan();
            toast(data.msg, 'ok');
            /* Refrescar la tabla del tipo creado */
            if (tipo === 'turistico') cargarPlanesTuristicos();
            else cargarPlanesGastronomicos();
        } else {
            alertaModalCrearPlan(data.msg || 'Error al crear el plan.', 'error');
        }
    } catch (err) {
        alertaModalCrearPlan('Error de conexión: ' + err.message, 'error');
        console.error(err);
    }

    btn.disabled    = false;
    btn.textContent = 'Crear Plan';
});

/* =============================================
   MÓDULO — GESTIÓN DE RESERVAS
   Variables de estado para paginación,
   filtros y la reserva pendiente de eliminar.
   ============================================= */
let paginaReservas          = 1;
let busquedaReservasTimer   = null;
let idReservaEliminar       = null;
let precioUnitarioReserva   = 0; // para recalcular precio al editar

/* ─── Badge de estado de reserva ─── */
function estadoBadgeReserva(estado) {
    const mapa = {
        pendiente:  { cls: 'etiqueta-rol--viewer',  icono: 'schedule'      },
        confirmada: { cls: 'etiqueta-rol--editor',  icono: 'check_circle'  },
        cancelada:  { cls: 'etiqueta-rol--admin',   icono: 'cancel'        },
        completada: { cls: 'etiqueta-estado-completada', icono: 'task_alt' },
    };
    const cfg = mapa[estado] || mapa.pendiente;
    return `<span class="etiqueta-rol ${cfg.cls}" style="display:inline-flex;align-items:center;gap:.25rem;">
        <span class="material-symbols-outlined" style="font-size:.9rem;">${cfg.icono}</span>
        ${estado.charAt(0).toUpperCase() + estado.slice(1)}
    </span>`;
}

/* ─── Badge de tipo de plan ─── */
function tipoBadgeReserva(tipo) {
    const esGastro = tipo === 'gastronomico';
    return `<span class="etiqueta-rol ${esGastro ? 'etiqueta-rol--viewer' : 'etiqueta-rol--editor'}"
                  style="display:inline-flex;align-items:center;gap:.25rem;">
        <span class="material-symbols-outlined" style="font-size:.9rem;">${esGastro ? 'restaurant' : 'explore'}</span>
        ${esGastro ? 'Gastronómico' : 'Turístico'}
    </span>`;
}

/* ─── Alerta dentro del modal de reserva ─── */
function alertaModalReserva(msg, tipo) {
    const el = document.getElementById('modal-reserva-alerta');
    el.textContent      = msg;
    el.style.display    = 'block';
    el.style.background = tipo === 'ok' ? 'rgba(16,185,129,.12)' : 'rgba(239,68,68,.12)';
    el.style.color      = tipo === 'ok' ? '#065f46' : '#7f1d1d';
    el.style.border     = `1px solid ${tipo === 'ok' ? '#6ee7b7' : '#fca5a5'}`;
}

/* =============================================
   CARGAR RESERVAS (AJAX)
   Consulta el endpoint con paginación y filtros,
   renderiza la tabla y las estadísticas.
   ============================================= */
async function cargarReservas(pagina = 1) {
    paginaReservas = pagina;
    const tbody = document.getElementById('tbody-reservas');
    tbody.innerHTML = '<tr><td colspan="9" class="celda-cargando">Cargando reservas…</td></tr>';

    const q      = document.getElementById('buscador-reservas').value.trim();
    const estado = document.getElementById('filtro-reserva-estado').value;
    const tipo   = document.getElementById('filtro-reserva-tipo').value;

    try {
        const params = new URLSearchParams({ page: pagina });
        if (q)      params.append('q',      q);
        if (estado) params.append('estado', estado);
        if (tipo)   params.append('tipo',   tipo);

        const res  = await fetch('/ajax/admin_reservas.php?' + params.toString());
        const data = await res.json();

        if (!data.ok) { toast(data.msg || 'Error al cargar reservas.', 'error'); return; }

        /* — Actualizar estadísticas — */
        document.getElementById('stat-total').textContent   = data.stats.total   ?? '—';
        document.getElementById('stat-activos').textContent = data.stats.confirmadas ?? '—';
        document.getElementById('stat-pendientes').textContent = data.stats.pendientes ?? '—';
        document.getElementById('stat-ingresos').textContent = '$' + (data.stats.ingresos_formateados ?? '0');
        document.getElementById('reservas-count').textContent =
            `${data.total} reserva${data.total !== 1 ? 's' : ''}`;

        /* — Renderizar filas — */
        if (!data.reservas.length) {
            tbody.innerHTML = '<tr><td colspan="9" class="celda-cargando">No se encontraron reservas.</td></tr>';
            document.getElementById('info-paginacion-reservas').textContent = '';
            document.getElementById('paginacion-reservas').innerHTML = '';
            return;
        }

        tbody.innerHTML = data.reservas.map(r => {
            const fecha = r.fecha_inicio
                ? new Date(r.fecha_inicio + 'T00:00:00').toLocaleDateString('es-CO', { day:'2-digit', month:'short', year:'numeric' })
                : '—';
            const esEliminable = r.estado === 'pendiente' || r.estado === 'cancelada';
            return `<tr>
                <td class="tabla-usuarios__td" style="font-weight:600;color:#054da4;">#${r.id}</td>
                <td class="tabla-usuarios__td">
                    <div style="line-height:1.3;">
                        <span style="font-weight:600;">${escHtml(r.usuario_nombre ?? '—')}</span><br/>
                        <span style="font-size:.75rem;color:#737783;">${escHtml(r.usuario_email ?? '')}</span>
                    </div>
                </td>
                <td class="tabla-usuarios__td" style="max-width:180px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="${escHtml(r.plan_titulo ?? '')}">
                    ${escHtml(r.plan_titulo ?? '—')}
                </td>
                <td class="tabla-usuarios__td">${tipoBadgeReserva(r.tipo_plan)}</td>
                <td class="tabla-usuarios__td">${fecha}</td>
                <td class="tabla-usuarios__td" style="text-align:center;">${r.num_adultos}</td>
                <td class="tabla-usuarios__td" style="font-weight:600;">
                    $${r.precio_formateado} <span style="font-size:.7rem;color:#737783;">${r.moneda}</span>
                </td>
                <td class="tabla-usuarios__td">${estadoBadgeReserva(r.estado)}</td>
                <td class="tabla-usuarios__td--derecha">
                    <div class="acciones-fila">
                        <button class="boton-accion boton-accion--editar"
                                title="Editar reserva"
                                onclick="abrirEditarReserva(${r.id},'${escHtml(r.usuario_nombre)}','${escHtml(r.plan_titulo)}',${r.num_adultos},'${r.fecha_inicio}','${r.estado}',${r.precio_total})">
                            <span class="material-symbols-outlined">edit</span>
                        </button>
                        ${esEliminable ? `
                        <button class="boton-accion boton-accion--eliminar"
                                title="Eliminar reserva"
                                onclick="confirmarEliminarReserva(${r.id},'${escHtml(r.usuario_nombre)}')">
                            <span class="material-symbols-outlined">delete</span>
                        </button>` : ''}
                    </div>
                </td>
            </tr>`;
        }).join('');

        /* — Paginación — */
        const inicio = (pagina - 1) * 10 + 1;
        const fin    = Math.min(pagina * 10, data.total);
        document.getElementById('info-paginacion-reservas').textContent =
            `Mostrando ${inicio}–${fin} de ${data.total} reservas`;
        renderPaginacionReservas(pagina, data.paginas);

    } catch (err) {
        toast('Error de conexión al cargar reservas.', 'error');
        console.error(err);
    }
}

/* Helper para escapar HTML en atributos */
function escHtml(str) {
    return String(str ?? '').replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

/* ─── Renderizar paginación de reservas ─── */
function renderPaginacionReservas(actual, total) {
    const cont = document.getElementById('paginacion-reservas');
    if (total <= 1) { cont.innerHTML = ''; return; }
    let html = `<button class="paginacion__boton" ${actual <= 1 ? 'disabled' : ''} onclick="cargarReservas(${actual - 1})">
        <span class="material-symbols-outlined">chevron_left</span>
    </button>`;
    for (let i = 1; i <= total; i++) {
        html += `<button class="${i === actual ? 'paginacion__boton--activo' : 'paginacion__boton--inactivo'}"
                         onclick="cargarReservas(${i})">${i}</button>`;
    }
    html += `<button class="paginacion__boton" ${actual >= total ? 'disabled' : ''} onclick="cargarReservas(${actual + 1})">
        <span class="material-symbols-outlined">chevron_right</span>
    </button>`;
    cont.innerHTML = html;
}

/* =============================================
   MODAL — EDITAR RESERVA
   Abre el modal pre-cargado con los datos
   actuales de la reserva.
   ============================================= */
function abrirEditarReserva(id, usuario, plan, adultos, fecha, estado, precioTotal) {
    document.getElementById('reserva-id').value             = id;
    document.getElementById('reserva-usuario').value        = usuario;
    document.getElementById('reserva-plan').value           = plan;
    document.getElementById('reserva-adultos').value        = adultos;
    document.getElementById('reserva-fecha').value          = fecha ? fecha.substring(0, 10) : '';
    document.getElementById('reserva-estado').value         = estado;
    document.getElementById('reserva-precio-display').value = '—';
    document.getElementById('modal-reserva-alerta').style.display = 'none';

    /* Guardar precio unitario para recalcular al cambiar adultos */
    precioUnitarioReserva = adultos > 0 ? precioTotal / adultos : 0;
    actualizarPrecioDisplay();

    document.getElementById('modal-reserva').style.display = 'flex';
}

function cerrarModalReserva() {
    document.getElementById('modal-reserva').style.display = 'none';
}

/* Recalcula el precio estimado al cambiar el número de adultos */
function actualizarPrecioDisplay() {
    const adultos = parseInt(document.getElementById('reserva-adultos').value) || 1;
    const total   = precioUnitarioReserva * adultos;
    document.getElementById('reserva-precio-display').value =
        '$' + total.toLocaleString('es-CO', { minimumFractionDigits: 0 }) + ' ' +
        (document.getElementById('reserva-precio-display').dataset.moneda || 'USD');
}

document.getElementById('reserva-adultos').addEventListener('input', actualizarPrecioDisplay);

/* ─── Envío del formulario de editar reserva ─── */
document.getElementById('form-reserva').addEventListener('submit', async function(e) {
    e.preventDefault();

    const id     = parseInt(document.getElementById('reserva-id').value);
    const fecha  = document.getElementById('reserva-fecha').value;
    const adultos = parseInt(document.getElementById('reserva-adultos').value) || 1;
    const estado  = document.getElementById('reserva-estado').value;

    if (!fecha) { alertaModalReserva('La fecha de inicio es requerida.', 'error'); return; }
    if (adultos < 1) { alertaModalReserva('El número de personas debe ser al menos 1.', 'error'); return; }

    const btn = document.getElementById('modal-reserva-btn-guardar');
    btn.disabled    = true;
    btn.textContent = 'Guardando…';

    try {
        const res  = await fetch('/ajax/admin_reservas.php', {
            method:  'PUT',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify({ id, fecha_inicio: fecha, num_adultos: adultos, estado }),
        });
        const data = await res.json();

        if (data.ok) {
            cerrarModalReserva();
            toast(data.msg, 'ok');
            cargarReservas(paginaReservas);
        } else {
            alertaModalReserva(data.msg || 'Error al guardar.', 'error');
        }
    } catch (err) {
        alertaModalReserva('Error de conexión: ' + err.message, 'error');
        console.error(err);
    }

    btn.disabled    = false;
    btn.textContent = 'Guardar cambios';
});

/* ─── Listeners cierre modal reserva ─── */
document.getElementById('modal-reserva-cerrar').addEventListener('click', cerrarModalReserva);
document.getElementById('modal-reserva-btn-cancelar').addEventListener('click', cerrarModalReserva);
document.getElementById('modal-reserva').addEventListener('click', function(e) {
    if (e.target === this) cerrarModalReserva();
});

/* =============================================
   ELIMINAR RESERVA — Confirmación
   Solo reservas en estado pendiente o cancelada.
   ============================================= */
function confirmarEliminarReserva(id, nombre) {
    idReservaEliminar = id;
    document.getElementById('eliminar-reserva-txt').textContent =
        `Se eliminará la reserva #${id} de "${nombre}". Esta acción no se puede deshacer.`;
    document.getElementById('modal-eliminar-reserva').style.display = 'flex';
}

document.getElementById('eliminar-reserva-cancelar').addEventListener('click', () => {
    document.getElementById('modal-eliminar-reserva').style.display = 'none';
    idReservaEliminar = null;
});

document.getElementById('eliminar-reserva-confirmar').addEventListener('click', async () => {
    if (!idReservaEliminar) return;
    const btn = document.getElementById('eliminar-reserva-confirmar');
    btn.disabled    = true;
    btn.textContent = 'Eliminando…';

    try {
        const res  = await fetch('/ajax/admin_reservas.php', {
            method:  'DELETE',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify({ id: idReservaEliminar }),
        });
        const data = await res.json();

        document.getElementById('modal-eliminar-reserva').style.display = 'none';
        idReservaEliminar = null;

        if (data.ok) {
            toast(data.msg, 'ok');
            cargarReservas(paginaReservas);
        } else {
            toast(data.msg || 'No se pudo eliminar.', 'error');
        }
    } catch (err) {
        toast('Error de conexión.', 'error');
        console.error(err);
    }

    btn.disabled    = false;
    btn.textContent = 'Eliminar';
});

/* ─── Filtros de reservas (debounce) ─── */
document.getElementById('buscador-reservas').addEventListener('input', () => {
    clearTimeout(busquedaReservasTimer);
    busquedaReservasTimer = setTimeout(() => cargarReservas(1), 400);
});

document.getElementById('filtro-reserva-estado').addEventListener('change', () => cargarReservas(1));
document.getElementById('filtro-reserva-tipo').addEventListener('change',   () => cargarReservas(1));