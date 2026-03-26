<!DOCTYPE html>
<html class="light" lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Voyager Admin - Gestión de Usuarios</title>
    <!-- Google Fonts: Plus Jakarta Sans & Manrope -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <!-- Material Symbols Outlined -->
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
        
        <a class="nav__enlace--activo" href="#">
            <span class="material-symbols-outlined nav__icono--activo">group</span>
            <span class="nav__etiqueta">Usuarios</span>
        </a>
        <a class="nav__enlace" href="#">
            <span class="material-symbols-outlined">explore</span>
            <span class="nav__etiqueta">Planes Turísticos</span>
        </a>
        <a class="nav__enlace" href="#">
            <span class="material-symbols-outlined">restaurant</span>
            <span class="nav__etiqueta">Planes Gastronómicos</span>
        </a>
        
    </nav>

    <div class="barra-lateral__pie">
        
        <button class="boton-cerrar-sesion">
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
            <input
                class="buscador__input"
                placeholder="Buscar usuarios o destinos..."
                type="text"
            />
        </div>
    </div>
    <div class="barra-superior__acciones">
        
        <div class="divisor-vertical"></div>
        <div class="perfil-admin">
            <span class="perfil-admin__texto">
                <p class="perfil-admin__nombre">Admin User</p>
                <p class="perfil-admin__cargo">Super Administrador</p>
            </span>
            <div class="perfil-admin__avatar-contenedor">
                <img
                    alt="Perfil Admin"
                    class="perfil-admin__avatar"
                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuBbx33KNFbf4t5GL3yUCRv4BOytix3gw_f1AHLCE2zB77tW2tIrwwEhY8bamwWcfkJv5Ln8Vd167ngGwxEIFX26tAljfSX4cP4zV8W7uI9a4IQLjCLB-F_KL8Y9ZhJzrCvdVTimENDgAUYVfPIsFJb_sDxbRMeG_jQea9QyXXswXDHTyO_rrXTesKLQVjGHhVfhbAc8F0Yu7kD83Yc499M0c73OmKNvB5bJd5-AGzwIpgWtmRPI9wc99KTerpZ0U56FctP_PM2g9E3d"
                />
            </div>
        </div>
    </div>
</header>

<!-- Contenido Principal -->
<main class="contenido-principal">

    <!-- Encabezado del Dashboard -->
    <section class="encabezado-seccion">
        <div class="encabezado-seccion__texto">
            <h2 class="encabezado-seccion__titulo">Gestión de Usuarios</h2>
            <p class="encabezado-seccion__descripcion">Controla el acceso, roles y estados de los miembros de la plataforma Voyager Admin.</p>
        </div>
        <button class="boton-nuevo-usuario">
            <span class="material-symbols-outlined">add</span>
            Nuevo Usuario
        </button>
    </section>

    <!-- Grilla de Estadísticas -->
    <div class="grilla-estadisticas">
        <div class="tarjeta-estadistica panel-vidrio">
            <div>
                <p class="tarjeta-estadistica__etiqueta">Total Usuarios</p>
                <h3 class="tarjeta-estadistica__valor">1,284</h3>
            </div>
            <div class="tarjeta-estadistica__icono tarjeta-estadistica__icono--primario">
                <span class="material-symbols-outlined">group</span>
            </div>
        </div>

        <div class="tarjeta-estadistica panel-vidrio">
            <div>
                <p class="tarjeta-estadistica__etiqueta">Usuarios Activos</p>
                <h3 class="tarjeta-estadistica__valor">842</h3>
            </div>
            <div class="tarjeta-estadistica__icono tarjeta-estadistica__icono--terciario">
                <span class="material-symbols-outlined">how_to_reg</span>
            </div>
        </div>

        
    </div>

    <!-- Sección Tabla de Usuarios -->
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
                <tbody class="tabla-usuarios__cuerpo">

                    <!-- Fila Usuario 1 -->
                    <tr class="fila-usuario">
                        <td class="tabla-usuarios__celda">
                            <div class="avatar-usuario">
                                <img alt="Elena Rodriguez" class="avatar-usuario__imagen" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBzM9rex7PCMzb6GEwXoMaB6MLFZmYobl88ANVonMw4V-Jrc4JPLNr9Q9XcEckkwT4d6ZplFNLTdxOhJ3mp2qQEkrBSEWf5DpezvhemT_DZY-anyCMuFx4_bUVeZXfarwh_cU5V6GrnY5vDxPN5ne_j4nXXxfgJCIvFST33GaoEK6GGjs2ygDYeigtswSIoYLB_VxEEM866QuGPUhc-TBjq3P6Fn6nyTboVskjhftEmy1UWfOYQnTFVBUc274PMEWX9PX1DFOXQUSct"/>
                            </div>
                        </td>
                        <td class="tabla-usuarios__celda">
                            <p class="usuario__nombre">Elena Rodriguez</p>
                        </td>
                        <td class="tabla-usuarios__celda">
                            <p class="usuario__email">elena@voyager.com</p>
                        </td>
                        <td class="tabla-usuarios__celda">
                            <span class="etiqueta-rol etiqueta-rol--editor">Editor</span>
                        </td>
                        <td class="tabla-usuarios__celda">
                            <div class="estado-usuario">
                                <div class="estado-usuario__punto estado-usuario__punto--activo"></div>
                                <span class="estado-usuario__texto--activo">Activo</span>
                            </div>
                        </td>
                        <td class="tabla-usuarios__celda--derecha">
                            <div class="acciones-fila">
                                <button class="boton-accion boton-accion--editar">
                                    <span class="material-symbols-outlined">edit</span>
                                </button>
                                <button class="boton-accion boton-accion--eliminar">
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Fila Usuario 2 -->
                    <tr class="fila-usuario">
                        <td class="tabla-usuarios__celda">
                            <div class="avatar-usuario">
                                <img alt="Marco Valencia" class="avatar-usuario__imagen" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBl4NATyzIRJQPTrw20Mj0h0Cf2wolLrFwUISFrtXtZ6qkfidnRvvy0NTipdRHkJ72xFlMT5jcO48GiuFQbXThH5aTHDF4nggFuEBX2BLcLFxv3eGgxHFYNHYE7m-MnnwiEu_Xh3aFxH1fZ6jqYKQ2bJKF-pi6s2WTARUeW6UCNWXTfliepxEPEZavdR1fhQiOKxgbD8DjCjn6DYW2c_gDjD7C81fFskA2K_CCnR2WuA8Sk3Rvyx8U-Yz-u1SzwDJr1w82HJl2kKzYn"/>
                            </div>
                        </td>
                        <td class="tabla-usuarios__celda">
                            <p class="usuario__nombre">Marco Valencia</p>
                        </td>
                        <td class="tabla-usuarios__celda">
                            <p class="usuario__email">marco@voyager.com</p>
                        </td>
                        <td class="tabla-usuarios__celda">
                            <span class="etiqueta-rol etiqueta-rol--admin">Admin</span>
                        </td>
                        <td class="tabla-usuarios__celda">
                            <div class="estado-usuario">
                                <div class="estado-usuario__punto estado-usuario__punto--activo"></div>
                                <span class="estado-usuario__texto--activo">Activo</span>
                            </div>
                        </td>
                        <td class="tabla-usuarios__celda--derecha">
                            <div class="acciones-fila">
                                <button class="boton-accion boton-accion--editar">
                                    <span class="material-symbols-outlined">edit</span>
                                </button>
                                <button class="boton-accion boton-accion--eliminar">
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Fila Usuario 3 -->
                    <tr class="fila-usuario">
                        <td class="tabla-usuarios__celda">
                            <div class="avatar-usuario">
                                <img alt="Sara Mendez" class="avatar-usuario__imagen" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBvKakOUsISxqYIojOZDygt9ftwk_OnlEz3xTEg0LNTh7mivt2WF0ND9Oo1H_VAwxPzcg68e67c7xmiSJkiwC30ar2gh9MdtfWegyNyeOvNIewkWQHLsyaah8Fw8aloQNZ4UimtZvtmONfnqJA5bml0d_zLB-GFx9CedUvDTI7HW9Hu4BRQAJi7OkWjRgpC2CE-Zbd-kp9hrWZa8x6MZGqFmkP3frr2XzRcHAur-ZBmBeMDkG7Iy2W9SNG6TkZSgqjqtTHO1pQ2JdeI"/>
                            </div>
                        </td>
                        <td class="tabla-usuarios__celda">
                            <p class="usuario__nombre">Sara Mendez</p>
                        </td>
                        <td class="tabla-usuarios__celda">
                            <p class="usuario__email">sara@voyager.com</p>
                        </td>
                        <td class="tabla-usuarios__celda">
                            <span class="etiqueta-rol etiqueta-rol--viewer">Viewer</span>
                        </td>
                        <td class="tabla-usuarios__celda">
                            <div class="estado-usuario">
                                <div class="estado-usuario__punto estado-usuario__punto--inactivo"></div>
                                <span class="estado-usuario__texto--inactivo">Inactivo</span>
                            </div>
                        </td>
                        <td class="tabla-usuarios__celda--derecha">
                            <div class="acciones-fila">
                                <button class="boton-accion boton-accion--editar">
                                    <span class="material-symbols-outlined">edit</span>
                                </button>
                                <button class="boton-accion boton-accion--eliminar">
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Fila Usuario 4 -->
                    <tr class="fila-usuario">
                        <td class="tabla-usuarios__celda">
                            <div class="avatar-usuario">
                                <img alt="Lucas Kim" class="avatar-usuario__imagen" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDUGu40F6tWaU-2RWs1BKjtpt0bVFu6WEGTfi2p_Rh4jI2AtWccKTkrbEOtUE7gC1H1Fygsmmlgco_O29S3R2ZSV5WfOpxNaw5E-yiaZTzoRZuSxfTKxzjuOqFCTcNBbgvIRaMpvMgmp3uppYjjpaxN-_4PSFqUUWhh8HM8NvO7GGuODLBFBERalHb4o_Ey7AFut1hKa1CmsdtQ4x0FIQEVPVsDiROuhCJljgAvaMqkjUkJ4P4ArgZQQr5WSKLKyNMtYA0YBLcQqgdw"/>
                            </div>
                        </td>
                        <td class="tabla-usuarios__celda">
                            <p class="usuario__nombre">Lucas Kim</p>
                        </td>
                        <td class="tabla-usuarios__celda">
                            <p class="usuario__email">lucas@voyager.com</p>
                        </td>
                        <td class="tabla-usuarios__celda">
                            <span class="etiqueta-rol etiqueta-rol--editor">Editor</span>
                        </td>
                        <td class="tabla-usuarios__celda">
                            <div class="estado-usuario">
                                <div class="estado-usuario__punto estado-usuario__punto--activo"></div>
                                <span class="estado-usuario__texto--activo">Activo</span>
                            </div>
                        </td>
                        <td class="tabla-usuarios__celda--derecha">
                            <div class="acciones-fila">
                                <button class="boton-accion boton-accion--editar">
                                    <span class="material-symbols-outlined">edit</span>
                                </button>
                                <button class="boton-accion boton-accion--eliminar">
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

        <!-- Pie de Tabla / Paginación -->
        <div class="pie-tabla">
            <p class="pie-tabla__info">Mostrando 4 de 1,284 usuarios</p>
            <div class="paginacion">
                <button class="paginacion__boton">
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>
                <button class="paginacion__boton--activo">1</button>
                <button class="paginacion__boton--inactivo">2</button>
                <button class="paginacion__boton--inactivo">3</button>
                <button class="paginacion__boton">
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>
            </div>
        </div>
    </section>
</main>
<script src="/scripts/script-dashboard-administrador.js"></script>

</body>
</html>