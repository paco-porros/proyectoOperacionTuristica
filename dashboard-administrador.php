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

<!-- ════════════════════════════════════════
     BARRA LATERAL
════════════════════════════════════════ -->
<aside class="barra-lateral">
    <div class="barra-lateral__marca">
        <div>
            <h1 class="barra-lateral__nombre">Operador Turístico y Gastronomico | Santa Rosa de Cabal</h1>
            <p class="barra-lateral__subtitulo">Admin Console</p>
        </div>
    </div>

    <nav class="barra-lateral__nav">
        <a class="nav__enlace--activo" href="#" data-seccion="usuarios" id="nav-usuarios">
            <span class="material-symbols-outlined nav__icono--activo">group</span>
            <span class="nav__etiqueta">Usuarios</span>
        </a>
        <a class="nav__enlace" href="#" data-seccion="planes-turisticos" id="nav-planes-turisticos">
            <span class="material-symbols-outlined">explore</span>
            <span class="nav__etiqueta">Planes Turísticos</span>
        </a>
        <a class="nav__enlace" href="#" data-seccion="planes-gastronomicos" id="nav-planes-gastronomicos">
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

<!-- ════════════════════════════════════════
     BARRA SUPERIOR
════════════════════════════════════════ -->
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

<!-- ════════════════════════════════════════
     CONTENIDO PRINCIPAL
════════════════════════════════════════ -->
<main class="contenido-principal">

    <!-- Encabezado dinámico (cambia según sección activa vía JS) -->
    <section class="encabezado-seccion">
        <div class="encabezado-seccion__texto">
            <h2 class="encabezado-seccion__titulo" id="seccion-titulo">Gestión de Usuarios</h2>
            <p class="encabezado-seccion__descripcion" id="seccion-descripcion">Controla el acceso, roles y estados de los miembros de la plataforma.</p>
        </div>
        <button class="boton-nuevo-usuario" id="btn-nuevo-usuario">
            <span class="material-symbols-outlined">add</span>
            Nuevo Usuario
        </button>
    </section>

    <!-- Tarjetas de estadísticas -->
    <div class="grilla-estadisticas" id="grilla-stats">
        <div class="tarjeta-estadistica panel-vidrio">
            <div>
                <p class="tarjeta-estadistica__etiqueta" id="stat-etiqueta-1">Total Usuarios</p>
                <h3 class="tarjeta-estadistica__valor" id="stat-total">—</h3>
            </div>
            <div class="tarjeta-estadistica__icono tarjeta-estadistica__icono--primario">
                <span class="material-symbols-outlined">group</span>
            </div>
        </div>
        <div class="tarjeta-estadistica panel-vidrio">
            <div>
                <p class="tarjeta-estadistica__etiqueta" id="stat-etiqueta-2">Usuarios Activos</p>
                <h3 class="tarjeta-estadistica__valor" id="stat-activos">—</h3>
            </div>
            <div class="tarjeta-estadistica__icono tarjeta-estadistica__icono--terciario">
                <span class="material-symbols-outlined">how_to_reg</span>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════
         SECCIÓN — Tabla de Usuarios
    ═══════════════════════════════════════════ -->
    <section class="seccion-tabla panel-vidrio" id="seccion-usuarios">
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
                        <td colspan="6" class="celda-cargando">Cargando usuarios…</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="pie-tabla">
            <p class="pie-tabla__info" id="info-paginacion">Cargando…</p>
            <div class="paginacion" id="paginacion"></div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════
         SECCIÓN DINÁMICA — Planes Turísticos
    ═══════════════════════════════════════════ -->
    <section class="seccion-tabla panel-vidrio seccion-dinamica" id="seccion-planes-turisticos" style="display:none;">
        <div class="seccion-planes__encabezado">
            <div>
                <h3 class="seccion-planes__subtitulo">Planes turísticos</h3>
                <span class="etiqueta-planes-count" id="planes-turisticos-count"></span>
            </div>
            <button class="boton-nuevo-usuario" id="btn-agregar-plan-turistico" style="margin:0;">
                <span class="material-symbols-outlined">add</span>
                + Agregar Plan
            </button>
        </div>
        <div class="tabla-contenedor">
            <table class="tabla-usuarios">
                <thead class="tabla-usuarios__encabezado">
                    <tr>
                        <th class="tabla-usuarios__th">Imagen</th>
                        <th class="tabla-usuarios__th">Título</th>
                        <th class="tabla-usuarios__th">Etiqueta</th>
                        <th class="tabla-usuarios__th">Precio desde</th>
                        <th class="tabla-usuarios__th">Estado</th>
                        <th class="tabla-usuarios__th--derecha">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tbody-planes-turisticos">
                    <tr><td colspan="6" class="celda-cargando">Cargando planes…</td></tr>
                </tbody>
            </table>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════
         SECCIÓN DINÁMICA — Planes Gastronómicos
    ═══════════════════════════════════════════ -->
    <section class="seccion-tabla panel-vidrio seccion-dinamica" id="seccion-planes-gastronomicos" style="display:none;">
        <div class="seccion-planes__encabezado">
            <div>
                <h3 class="seccion-planes__subtitulo">Planes gastronómicos</h3>
                <span class="etiqueta-planes-count" id="planes-gastronomicos-count"></span>
            </div>
            <button class="boton-nuevo-usuario" id="btn-agregar-plan-gastronomico" style="margin:0;">
                <span class="material-symbols-outlined">add</span>
                + Agregar Plan
            </button>
        </div>
        <div class="tabla-contenedor">
            <table class="tabla-usuarios">
                <thead class="tabla-usuarios__encabezado">
                    <tr>
                        <th class="tabla-usuarios__th">Imagen</th>
                        <th class="tabla-usuarios__th">Título</th>
                        <th class="tabla-usuarios__th">Restaurante</th>
                        <th class="tabla-usuarios__th">Precio desde</th>
                        <th class="tabla-usuarios__th">Estado</th>
                        <th class="tabla-usuarios__th--derecha">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tbody-planes-gastronomicos">
                    <tr><td colspan="6" class="celda-cargando">Cargando planes…</td></tr>
                </tbody>
            </table>
        </div>
    </section>

</main>

<!-- ════════════════════════════════════════
     MODAL — Crear / Editar usuario
════════════════════════════════════════ -->
<div id="modal-usuario" class="modal-fondo" style="z-index:1000;">
    <div class="modal-caja">
        <div class="modal-encabezado">
            <h3 id="modal-titulo" class="modal-titulo">Nuevo Usuario</h3>
            <button id="modal-cerrar" class="modal-boton-cerrar">✕</button>
        </div>

        <div id="modal-alerta" class="modal-alerta-interna"></div>

        <form id="form-usuario" novalidate>
            <input type="hidden" id="modal-user-id" value=""/>

            <div class="campo-grupo">
                <label class="campo-etiqueta">Nombre completo *</label>
                <input id="modal-nombre" type="text" placeholder="Juan Pérez" class="campo-input"/>
            </div>

            <div class="campo-grupo">
                <label class="campo-etiqueta">Correo electrónico *</label>
                <input id="modal-email" type="email" placeholder="usuario@ejemplo.com" class="campo-input"/>
            </div>

            <div class="campo-grupo">
                <label class="campo-etiqueta">
                    Contraseña <span id="pass-hint" style="text-transform:none;font-weight:400;">(dejar vacío para no cambiar)</span>
                </label>
                <input id="modal-password" type="password" placeholder="••••••••" class="campo-input"/>
            </div>

            <div class="campo-cuadricula-2" style="margin-bottom:1.5rem;">
                <div>
                    <label class="campo-etiqueta">Rol</label>
                    <select id="modal-rol" class="campo-select">
                        <option value="cliente">Cliente</option>
                        <option value="viewer">Viewer</option>
                        <option value="editor">Editor</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div>
                    <label class="campo-etiqueta">Estado</label>
                    <select id="modal-estado" class="campo-select">
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                </div>
            </div>

            <div class="modal-pie-botones">
                <button type="button" id="modal-btn-cancelar" class="boton-modal-cancelar">Cancelar</button>
                <button type="submit"  id="modal-btn-guardar"  class="boton-modal-guardar">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- ════════════════════════════════════════
     MODAL — Confirmar eliminación
════════════════════════════════════════ -->
<div id="modal-eliminar" class="modal-fondo" style="z-index:1001;">
    <div class="modal-caja modal-caja--confirmacion">
        <span class="material-symbols-outlined modal-icono-advertencia">warning</span>
        <h3 style="font-family:'Plus Jakarta Sans',sans-serif;font-size:1.25rem;font-weight:700;color:#002021;margin:0 0 .5rem;">¿Eliminar usuario?</h3>
        <p id="eliminar-nombre-txt" style="color:#424752;margin:0 0 1.5rem;font-size:.875rem;"></p>
        <div class="modal-pie-botones">
            <button id="eliminar-cancelar"  class="boton-modal-cancelar">Cancelar</button>
            <button id="eliminar-confirmar" class="boton-modal-eliminar">Eliminar</button>
        </div>
    </div>
</div>

<!-- ════════════════════════════════════════
     MODAL — Editar Plan (turístico / gastronómico)
════════════════════════════════════════ -->
<div id="modal-plan" class="modal-fondo" style="z-index:1002;">
    <div class="modal-caja modal-caja--ancha">
        <div class="modal-encabezado">
            <h3 id="modal-plan-titulo" class="modal-titulo">Editar Plan</h3>
            <button id="modal-plan-cerrar" class="modal-boton-cerrar">✕</button>
        </div>

        <div id="modal-plan-alerta" class="modal-alerta-interna"></div>

        <form id="form-plan" novalidate>
            <input type="hidden" id="plan-id"   value=""/>
            <input type="hidden" id="plan-tipo" value=""/>

            <div class="campo-grupo">
                <label class="campo-etiqueta">Título *</label>
                <input id="plan-titulo-input" type="text" placeholder="Nombre del plan" class="campo-input"/>
            </div>

            <div class="campo-grupo">
                <label class="campo-etiqueta">Descripción</label>
                <textarea id="plan-descripcion" rows="3" placeholder="Descripción del plan" class="campo-textarea"></textarea>
            </div>

            <!-- Campos exclusivos para planes turísticos -->
            <div id="campos-turistico">
                <div class="campo-cuadricula-2">
                    <div>
                        <label class="campo-etiqueta">Ubicación</label>
                        <input id="plan-ubicacion" type="text" placeholder="Santa Rosa de Cabal" class="campo-input"/>
                    </div>
                    <div>
                        <label class="campo-etiqueta">Duración (días)</label>
                        <input id="plan-duracion-dias" type="number" min="1" placeholder="3" class="campo-input"/>
                    </div>
                </div>
            </div>

            <!-- Campos exclusivos para planes gastronómicos -->
            <div id="campos-gastronomico">
                <div class="campo-cuadricula-2">
                    <div>
                        <label class="campo-etiqueta">Categoría</label>
                        <input id="plan-categoria" type="text" placeholder="Degustación, Parrilla…" class="campo-input"/>
                    </div>
                    <div>
                        <label class="campo-etiqueta">Duración (horas)</label>
                        <input id="plan-duracion-horas" type="number" min="0" step="0.5" placeholder="2" class="campo-input"/>
                    </div>
                </div>
            </div>

            <div class="campo-cuadricula-2">
                <div>
                    <label class="campo-etiqueta">Precio desde</label>
                    <input id="plan-precio" type="number" min="0" step="0.01" placeholder="0.00" class="campo-input"/>
                </div>
                <div>
                    <label class="campo-etiqueta">Estado</label>
                    <select id="plan-estado" class="campo-select">
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                </div>
            </div>

            <div class="campo-grupo" style="margin-bottom:1.5rem;">
                <label class="campo-etiqueta">URL Imagen</label>
                <input id="plan-imagen" type="url" placeholder="https://…" class="campo-input"/>
            </div>

            <div class="modal-pie-botones">
                <button type="button" id="modal-plan-btn-cancelar" class="boton-modal-cancelar">Cancelar</button>
                <button type="submit"  id="modal-plan-btn-guardar"  class="boton-modal-guardar">Guardar cambios</button>
            </div>
        </form>
    </div>
</div>

<!-- ════════════════════════════════════════
     MODAL — Crear Plan (turístico / gastronómico)
════════════════════════════════════════ -->
<div id="modal-crear-plan" class="modal-fondo" style="z-index:1003;">
    <div class="modal-caja modal-caja--ancha">
        <div class="modal-encabezado">
            <h3 id="modal-crear-plan-titulo" class="modal-titulo">Nuevo Plan</h3>
            <button id="modal-crear-plan-cerrar" class="modal-boton-cerrar">✕</button>
        </div>

        <div id="modal-crear-plan-alerta" class="modal-alerta-interna"></div>

        <!-- Vista previa de imagen antes de subir -->
        <div id="preview-contenedor" class="preview-contenedor">
            <img id="preview-imagen" src="" class="preview-imagen"/>
        </div>

        <form id="form-crear-plan" novalidate enctype="multipart/form-data">
            <input type="hidden" id="crear-plan-tipo" value=""/>

            <div class="campo-grupo">
                <label class="campo-etiqueta">Título *</label>
                <input id="crear-plan-titulo" type="text" placeholder="Nombre del plan" class="campo-input"/>
            </div>

            <div class="campo-grupo">
                <label class="campo-etiqueta">Descripción</label>
                <textarea id="crear-plan-descripcion" rows="3" placeholder="Descripción del plan" class="campo-textarea"></textarea>
            </div>

            <!-- Campos exclusivos para planes turísticos -->
            <div id="campos-crear-turistico" style="display:none;">
                <div class="campo-cuadricula-2">
                    <div>
                        <label class="campo-etiqueta">Ubicación</label>
                        <input id="crear-plan-ubicacion" type="text" placeholder="Santa Rosa de Cabal" class="campo-input"/>
                    </div>
                    <div>
                        <label class="campo-etiqueta">Duración (días)</label>
                        <input id="crear-plan-duracion-dias" type="number" min="1" placeholder="3" value="1" class="campo-input"/>
                    </div>
                </div>
            </div>

            <!-- Campos exclusivos para planes gastronómicos -->
            <div id="campos-crear-gastronomico" style="display:none;">
                <div class="campo-grupo">
                    <label class="campo-etiqueta">Restaurante *</label>
                    <select id="crear-plan-restaurante" class="campo-select">
                        <option value="">Selecciona un restaurante</option>
                    </select>
                </div>
                <div class="campo-cuadricula-2">
                    <div>
                        <label class="campo-etiqueta">Categoría</label>
                        <input id="crear-plan-categoria" type="text" placeholder="Degustación, Parrilla…" class="campo-input"/>
                    </div>
                    <div>
                        <label class="campo-etiqueta">Duración (horas)</label>
                        <input id="crear-plan-duracion-horas" type="number" min="0" step="0.5" placeholder="2" value="1" class="campo-input"/>
                    </div>
                </div>
                <div class="campo-cuadricula-2">
                    <div>
                        <label class="campo-etiqueta">Máximo de personas</label>
                        <input id="crear-plan-max-personas" type="number" min="1" placeholder="10" value="10" class="campo-input"/>
                    </div>
                    <div></div>
                </div>
            </div>

            <div class="campo-cuadricula-2">
                <div>
                    <label class="campo-etiqueta">Precio (USD)</label>
                    <input id="crear-plan-precio" type="number" min="0" step="0.01" placeholder="0.00" value="0" class="campo-input"/>
                </div>
                <div>
                    <label class="campo-etiqueta">Estado</label>
                    <select id="crear-plan-estado" class="campo-select">
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                </div>
            </div>

            <div class="campo-grupo" style="margin-bottom:1.5rem;">
                <label class="campo-etiqueta">Imagen (JPG, PNG, WebP — máx 5 MB)</label>
                <input id="crear-plan-imagen" type="file" accept="image/jpeg,image/png,image/webp" class="campo-input"/>
            </div>

            <div class="modal-pie-botones">
                <button type="button" id="modal-crear-plan-btn-cancelar" class="boton-modal-cancelar">Cancelar</button>
                <button type="submit"  id="modal-crear-plan-btn-guardar"  class="boton-modal-guardar">Crear Plan</button>
            </div>
        </form>
    </div>
</div>

<!-- Toast de notificación flotante (esquina inferior derecha) -->
<div id="toast-admin" class="toast-admin"></div>

<!-- Script externo: configuración de Tailwind + toda la lógica AJAX del dashboard -->
<script src="/scripts/script-dashboard-administrador.js"></script>

</body>
</html>