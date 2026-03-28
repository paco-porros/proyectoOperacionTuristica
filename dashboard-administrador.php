<?php
/**
 * dashboard-administrador.php
 * Solo accesible por admin y editor.
 * Gestión de usuarios completamente vía AJAX.
 */
require_once __DIR__ . '/includes/session.php';
requiereLogin('/login.php');
requiereRol(['admin', 'editor'], '/home.php');

$usuario = usuarioActual();
?>
<!DOCTYPE html>
<html class="light" lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Voyager Admin - Gestión de Usuarios</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link rel="stylesheet" href="/estilos/style-dashboard-administrador.css">
</head>
<body>

<!-- Barra Lateral -->
<aside class="barra-lateral">
    <div class="barra-lateral__marca">
        <div>
            <h1 class="barra-lateral__nombre">Operador Turístico y Gastronomico | Santa Rosa de Cabal</h1>
            <p class="barra-lateral__subtitulo">Admin Console</p>
        </div>
    </div>

    <nav class="barra-lateral__nav">
        <a class="nav__enlace--activo" href="#" id="nav-usuarios">
            <span class="material-symbols-outlined nav__icono--activo">group</span>
            <span class="nav__etiqueta">Usuarios</span>
        </a>
        <a class="nav__enlace" href="index.php">
            <span class="material-symbols-outlined">explore</span>
            <span class="nav__etiqueta">Planes Turísticos</span>
        </a>
        <a class="nav__enlace" href="detalles-gastronomicos.php">
            <span class="material-symbols-outlined">restaurant</span>
            <span class="nav__etiqueta">Planes Gastronómicos</span>
        </a>
    </nav>

    <div class="barra-lateral__pie">
        <button class="boton-cerrar-sesion" id="btn-logout-admin">
            <span class="material-symbols-outlined">logout</span>
            <span class="nav__etiqueta">Cerrar Sesión</span>
        </button>
    </div>
</aside>

<!-- Barra Superior -->
<header class="barra-superior">
    <div class="barra-superior__buscador">
        <div class="buscador__contenedor">
            <span class="material-symbols-outlined buscador__icono">search</span>
            <input class="buscador__input" id="buscador-usuarios" placeholder="Buscar usuarios..." type="text"/>
        </div>
    </div>
    <div class="barra-superior__acciones">
        <div class="divisor-vertical"></div>
        <div class="perfil-admin">
            <span class="perfil-admin__texto">
                <p class="perfil-admin__nombre"><?= htmlspecialchars($usuario['nombre']) ?></p>
                <p class="perfil-admin__cargo"><?= ucfirst($usuario['rol']) ?></p>
            </span>
            <div class="perfil-admin__avatar-contenedor">
                <img alt="Perfil Admin" class="perfil-admin__avatar"
                     src="https://lh3.googleusercontent.com/aida-public/AB6AXuBbx33KNFbf4t5GL3yUCRv4BOytix3gw_f1AHLCE2zB77tW2tIrwwEhY8bamwWcfkJv5Ln8Vd167ngGwxEIFX26tAljfSX4cP4zV8W7uI9a4IQLjCLB-F_KL8Y9ZhJzrCvdVTimENDgAUYVfPIsFJb_sDxbRMeG_jQea9QyXXswXDHTyO_rrXTesKLQVjGHhVfhbAc8F0Yu7kD83Yc499M0c73OmKNvB5bJd5-AGzwIpgWtmRPI9wc99KTerpZ0U56FctP_PM2g9E3d"/>
            </div>
        </div>
    </div>
</header>

<!-- Contenido Principal -->
<main class="contenido-principal">

    <!-- Encabezado -->
    <section class="encabezado-seccion">
        <div class="encabezado-seccion__texto">
            <h2 class="encabezado-seccion__titulo">Gestión de Usuarios</h2>
            <p class="encabezado-seccion__descripcion">Controla el acceso, roles y estados de los miembros de la plataforma.</p>
        </div>
        <button class="boton-nuevo-usuario" id="btn-nuevo-usuario">
            <span class="material-symbols-outlined">add</span>
            Nuevo Usuario
        </button>
    </section>

    <!-- Estadísticas -->
    <div class="grilla-estadisticas" id="grilla-stats">
        <div class="tarjeta-estadistica panel-vidrio">
            <div>
                <p class="tarjeta-estadistica__etiqueta">Total Usuarios</p>
                <h3 class="tarjeta-estadistica__valor" id="stat-total">—</h3>
            </div>
            <div class="tarjeta-estadistica__icono tarjeta-estadistica__icono--primario">
                <span class="material-symbols-outlined">group</span>
            </div>
        </div>
        <div class="tarjeta-estadistica panel-vidrio">
            <div>
                <p class="tarjeta-estadistica__etiqueta">Usuarios Activos</p>
                <h3 class="tarjeta-estadistica__valor" id="stat-activos">—</h3>
            </div>
            <div class="tarjeta-estadistica__icono tarjeta-estadistica__icono--terciario">
                <span class="material-symbols-outlined">how_to_reg</span>
            </div>
        </div>
    </div>

    <!-- Tabla de Usuarios -->
    <section class="seccion-tabla panel-vidrio">
        <div class="tabla-contenedor">
            <table class="tabla-usuarios">
                <thead class="tabla-usuarios__encabezado">
                    <tr>
                        <th class="tabla-usuarios__th">Avatar</th>
                        <th class="tabla-usuarios__th">Nombre</th>
                        <th class="tabla-usuarios__th">Email</th>
                        <th class="tabla-usuarios__th">Rol</th>
                        <th class="tabla-usuarios__th">Estado</th>
                        <th class="tabla-usuarios__th--derecha">Acciones</th>
                    </tr>
                </thead>
                <tbody class="tabla-usuarios__cuerpo" id="tabla-cuerpo">
                    <tr>
                        <td colspan="6" style="padding:2rem;text-align:center;color:#306388;">
                            Cargando usuarios…
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="pie-tabla">
            <p class="pie-tabla__info" id="info-paginacion">Cargando…</p>
            <div class="paginacion" id="paginacion"></div>
        </div>
    </section>
</main>

<!-- ════════════════════════════════════════
     MODAL — Crear / Editar usuario
════════════════════════════════════════ -->
<div id="modal-usuario" style="
    display:none; position:fixed; inset:0; z-index:1000;
    background:rgba(0,32,33,.55); backdrop-filter:blur(4px);
    align-items:center; justify-content:center;
">
    <div style="
        background:#e5feff; border-radius:1rem; padding:2.5rem;
        max-width:520px; width:90%; box-shadow:0 20px 40px rgba(0,32,33,.15);
        max-height:90vh; overflow-y:auto;
    ">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
            <h3 id="modal-titulo" style="font-family:'Plus Jakarta Sans',sans-serif;font-size:1.5rem;font-weight:700;color:#054da4;margin:0;">
                Nuevo Usuario
            </h3>
            <button id="modal-cerrar" style="background:transparent;border:none;cursor:pointer;color:#306388;font-size:1.5rem;line-height:1;">✕</button>
        </div>

        <!-- Alerta modal -->
        <div id="modal-alerta" style="display:none;margin-bottom:1rem;padding:.75rem 1rem;border-radius:.5rem;font-size:.875rem;font-weight:600;"></div>

        <form id="form-usuario" novalidate>
            <input type="hidden" id="modal-user-id" value=""/>

            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#306388;margin-bottom:.5rem;">Nombre completo *</label>
                <input id="modal-nombre" type="text" placeholder="Juan Pérez" style="
                    width:100%;padding:.75rem 1rem;border:1px solid #afedf0;border-radius:.5rem;
                    font-family:'Manrope',sans-serif;color:#002021;background:#fff;outline:none;box-sizing:border-box;
                "/>
            </div>

            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#306388;margin-bottom:.5rem;">Correo electrónico *</label>
                <input id="modal-email" type="email" placeholder="usuario@ejemplo.com" style="
                    width:100%;padding:.75rem 1rem;border:1px solid #afedf0;border-radius:.5rem;
                    font-family:'Manrope',sans-serif;color:#002021;background:#fff;outline:none;box-sizing:border-box;
                "/>
            </div>

            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#306388;margin-bottom:.5rem;">
                    Contraseña <span id="pass-hint" style="text-transform:none;font-weight:400;">(dejar vacío para no cambiar)</span>
                </label>
                <input id="modal-password" type="password" placeholder="••••••••" style="
                    width:100%;padding:.75rem 1rem;border:1px solid #afedf0;border-radius:.5rem;
                    font-family:'Manrope',sans-serif;color:#002021;background:#fff;outline:none;box-sizing:border-box;
                "/>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.5rem;">
                <div>
                    <label style="display:block;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#306388;margin-bottom:.5rem;">Rol</label>
                    <select id="modal-rol" style="
                        width:100%;padding:.75rem 1rem;border:1px solid #afedf0;border-radius:.5rem;
                        font-family:'Manrope',sans-serif;color:#002021;background:#fff;outline:none;box-sizing:border-box;
                    ">
                        <option value="cliente">Cliente</option>
                        <option value="viewer">Viewer</option>
                        <option value="editor">Editor</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#306388;margin-bottom:.5rem;">Estado</label>
                    <select id="modal-estado" style="
                        width:100%;padding:.75rem 1rem;border:1px solid #afedf0;border-radius:.5rem;
                        font-family:'Manrope',sans-serif;color:#002021;background:#fff;outline:none;box-sizing:border-box;
                    ">
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                </div>
            </div>

            <div style="display:flex;gap:1rem;">
                <button type="button" id="modal-btn-cancelar" style="
                    flex:1;padding:1rem;border-radius:9999px;border:2px solid #afedf0;
                    background:transparent;color:#306388;font-weight:700;cursor:pointer;
                    font-family:'Plus Jakarta Sans',sans-serif;
                ">Cancelar</button>
                <button type="submit" id="modal-btn-guardar" style="
                    flex:2;padding:1rem;border-radius:9999px;background:#054da4;color:#fff;
                    font-weight:700;border:none;cursor:pointer;
                    font-family:'Plus Jakarta Sans',sans-serif;font-size:1rem;
                ">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- ════════════════════════════════════════
     MODAL — Confirmar eliminación
════════════════════════════════════════ -->
<div id="modal-eliminar" style="
    display:none; position:fixed; inset:0; z-index:1001;
    background:rgba(0,32,33,.55); backdrop-filter:blur(4px);
    align-items:center; justify-content:center;
">
    <div style="
        background:#e5feff; border-radius:1rem; padding:2rem;
        max-width:400px; width:90%; box-shadow:0 20px 40px rgba(0,32,33,.15);
        text-align:center;
    ">
        <span class="material-symbols-outlined" style="font-size:3rem;color:#ba1a1a;margin-bottom:1rem;display:block;">warning</span>
        <h3 style="font-family:'Plus Jakarta Sans',sans-serif;font-size:1.25rem;font-weight:700;color:#002021;margin:0 0 .5rem;">¿Eliminar usuario?</h3>
        <p id="eliminar-nombre-txt" style="color:#424752;margin:0 0 1.5rem;font-size:.875rem;"></p>
        <div style="display:flex;gap:1rem;">
            <button id="eliminar-cancelar" style="
                flex:1;padding:.875rem;border-radius:9999px;border:2px solid #afedf0;
                background:transparent;color:#306388;font-weight:700;cursor:pointer;
                font-family:'Plus Jakarta Sans',sans-serif;
            ">Cancelar</button>
            <button id="eliminar-confirmar" style="
                flex:1;padding:.875rem;border-radius:9999px;background:#ba1a1a;color:#fff;
                font-weight:700;border:none;cursor:pointer;
                font-family:'Plus Jakarta Sans',sans-serif;
            ">Eliminar</button>
        </div>
    </div>
</div>

<!-- Toast -->
<div id="toast-admin" style="
    display:none; position:fixed; bottom:1.5rem; right:1.5rem; z-index:9999;
    padding:.875rem 1.5rem; border-radius:.75rem; font-size:.875rem; font-weight:600;
    font-family:'Manrope',sans-serif; box-shadow:0 4px 16px rgba(0,0,0,.1);
    transition:opacity .3s; opacity:0;
"></div>

<script src="/scripts/script-dashboard-administrador.js"></script>
<script>
/* ════════════════════════════════════════════════════
   DASHBOARD ADMINISTRADOR — AJAX completo
════════════════════════════════════════════════════ */

let paginaActual  = 1;
let busquedaTimer = null;
let idEliminarPendiente = null;

/* ── Toast helper ── */
function toast(msg, tipo) {
    const t = document.getElementById('toast-admin');
    t.textContent    = msg;
    t.style.display  = 'block';
    t.style.background = tipo === 'ok' ? '#d1fae5' : '#fee2e2';
    t.style.color      = tipo === 'ok' ? '#065f46' : '#7f1d1d';
    t.style.border     = `1px solid ${tipo === 'ok' ? '#6ee7b7' : '#fca5a5'}`;
    requestAnimationFrame(() => { t.style.opacity = '1'; });
    setTimeout(() => {
        t.style.opacity = '0';
        setTimeout(() => { t.style.display = 'none'; }, 300);
    }, 3500);
}

/* ── Alerta dentro del modal ── */
function alertaModal(msg, tipo) {
    const el = document.getElementById('modal-alerta');
    el.textContent   = msg;
    el.style.display = 'block';
    el.style.background = tipo === 'ok' ? 'rgba(16,185,129,.12)' : 'rgba(239,68,68,.12)';
    el.style.color      = tipo === 'ok' ? '#065f46' : '#7f1d1d';
    el.style.border     = `1px solid ${tipo === 'ok' ? '#6ee7b7' : '#fca5a5'}`;
}

/* ── Rol badge ── */
function rolBadge(rol) {
    const map = {
        admin:   'etiqueta-rol--admin',
        editor:  'etiqueta-rol--editor',
        viewer:  'etiqueta-rol--viewer',
        cliente: 'etiqueta-rol--viewer',
    };
    return `<span class="etiqueta-rol ${map[rol] || 'etiqueta-rol--viewer'}">${rol}</span>`;
}

/* ── Avatar inicial (cuando no hay url) ── */
function avatarSrc(u) {
    return u.avatar_url ||
        `https://ui-avatars.com/api/?name=${encodeURIComponent(u.nombre)}&background=afedf0&color=054da4&size=40`;
}

/* ════════════════════════════════════════
   CARGAR USUARIOS
════════════════════════════════════════ */
async function cargarUsuarios(pagina = 1) {
    paginaActual = pagina;
    const q   = document.getElementById('buscador-usuarios').value.trim();
    const url = `/ajax/admin_usuarios.php?page=${pagina}&q=${encodeURIComponent(q)}`;

    try {
        const res  = await fetch(url);
        const data = await res.json();

        if (!data.ok) { toast(data.msg || 'Error al cargar usuarios.', 'error'); return; }

        // Stats
        document.getElementById('stat-total').textContent   = data.stats.total   ?? '—';
        document.getElementById('stat-activos').textContent = data.stats.activos  ?? '—';

        // Info paginación
        const desde = (pagina - 1) * 10 + 1;
        const hasta = Math.min(pagina * 10, data.total);
        document.getElementById('info-paginacion').textContent =
            `Mostrando ${desde}–${hasta} de ${data.total} usuarios`;

        // Filas
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

        // Paginación
        renderPaginacion(data.pagina, data.paginas);

    } catch (err) {
        toast('Error de conexión al cargar usuarios.', 'error');
        console.error(err);
    }
}

/* ── Paginación ── */
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

/* ════════════════════════════════════════
   MODAL — Crear / Editar
════════════════════════════════════════ */
function abrirModal(titulo, id = '', nombre = '', email = '', rol = 'cliente', estado = 'activo') {
    document.getElementById('modal-titulo').textContent        = titulo;
    document.getElementById('modal-user-id').value            = id;
    document.getElementById('modal-nombre').value             = nombre;
    document.getElementById('modal-email').value              = email;
    document.getElementById('modal-rol').value                = rol;
    document.getElementById('modal-estado').value             = estado;
    document.getElementById('modal-password').value           = '';
    document.getElementById('modal-alerta').style.display     = 'none';
    document.getElementById('pass-hint').style.display        = id ? 'inline' : 'none';

    const modal = document.getElementById('modal-usuario');
    modal.style.display = 'flex';
}

function abrirNuevo() {
    abrirModal('Nuevo Usuario');
}

function abrirEditar(id, nombre, email, rol, estado) {
    abrirModal('Editar Usuario', id, nombre, email, rol, estado);
}

function cerrarModal() {
    document.getElementById('modal-usuario').style.display = 'none';
}

document.getElementById('modal-cerrar').addEventListener('click', cerrarModal);
document.getElementById('modal-btn-cancelar').addEventListener('click', cerrarModal);
document.getElementById('btn-nuevo-usuario').addEventListener('click', abrirNuevo);

/* Cerrar clickando fuera */
document.getElementById('modal-usuario').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});

/* ── Envío del formulario ── */
document.getElementById('form-usuario').addEventListener('submit', async function(e) {
    e.preventDefault();

    const id       = document.getElementById('modal-user-id').value;
    const nombre   = document.getElementById('modal-nombre').value.trim();
    const email    = document.getElementById('modal-email').value.trim();
    const password = document.getElementById('modal-password').value.trim();
    const rol      = document.getElementById('modal-rol').value;
    const estado   = document.getElementById('modal-estado').value;
    const btn      = document.getElementById('modal-btn-guardar');

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
        const body   = id
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

/* ════════════════════════════════════════
   MODAL — Eliminar
════════════════════════════════════════ */
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

/* Cerrar modal eliminar clickando fuera */
document.getElementById('modal-eliminar').addEventListener('click', function(e) {
    if (e.target === this) {
        this.style.display = 'none';
        idEliminarPendiente = null;
    }
});

/* ════════════════════════════════════════
   BUSCADOR con debounce
════════════════════════════════════════ */
document.getElementById('buscador-usuarios').addEventListener('input', () => {
    clearTimeout(busquedaTimer);
    busquedaTimer = setTimeout(() => cargarUsuarios(1), 400);
});

/* ════════════════════════════════════════
   LOGOUT AJAX
════════════════════════════════════════ */
document.getElementById('btn-logout-admin').addEventListener('click', async () => {
    await fetch('/ajax/logout.php', { method: 'POST' });
    window.location.href = '/login.php';
});

/* ── Carga inicial ── */
document.addEventListener('DOMContentLoaded', () => cargarUsuarios(1));
</script>

</body>
</html>
