<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>Voyager Admin - Gestión de Usuarios</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="/estilos/style-dashboard-usuarios.css"/>
</head>
<body>

  <!-- Barra Lateral -->
  <aside class="barra-lateral">
    <div class="barra-lateral__encabezado">
      <div class="contenedor-logo">
        <img alt="Voyager Logo" class="imagen-logo" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAakvqdFX4lz9t9GmDwuyuvVKh2u3z4hXV8W_QXeTMsXypg9g-rZ0NlH5W518rA7ueFX0PKz391dlNc2AtIvMevy1WlLA_7kkOJlsI5_tvV7XjaOWrUPBFpCd3deVIfVF7NsU3W0DoKNiYX8UYYypnuhq3PScdOOkDdXHCKlOt5FNoYrSYgXTT0WKx72ax_s-jv4wd0gxUi0ngCUNNmYKoBfWf2umXASylviTyQXmkF2ALUxrqeXghdm2vxuvjCQMEAKlu4tnQx_0sK"/>
      </div>
      <div>
        <h1 class="titulo-marca">Voyager</h1>
        <p class="subtitulo-marca">Admin Console</p>
      </div>
    </div>

    <nav class="nav-lateral">
      <a class="enlace-nav" href="#">
        <span class="material-symbols-outlined">dashboard</span>
        <span>Dashboard</span>
      </a>
      <a class="enlace-nav enlace-nav--activo" href="#">
        <span class="material-symbols-outlined icono-relleno">group</span>
        <span>Usuarios</span>
      </a>
      <a class="enlace-nav" href="#">
        <span class="material-symbols-outlined">insights</span>
        <span>Analítica</span>
      </a>
      <a class="enlace-nav" href="#">
        <span class="material-symbols-outlined">verified_user</span>
        <span>Roles</span>
      </a>
      <a class="enlace-nav" href="#">
        <span class="material-symbols-outlined">history</span>
        <span>Registros</span>
      </a>
    </nav>

    <div class="barra-lateral__pie">
      <button class="boton-nav">
        <span class="material-symbols-outlined">help</span>
        <span>Ayuda</span>
      </button>
      <button class="boton-nav boton-nav--cerrar-sesion">
        <span class="material-symbols-outlined">logout</span>
        <span>Cerrar Sesión</span>
      </button>
    </div>
  </aside>

  <!-- Barra Superior -->
  <header class="barra-superior">
    <div class="barra-superior__busqueda">
      <div class="contenedor-busqueda">
        <span class="material-symbols-outlined icono-busqueda">search</span>
        <input class="campo-busqueda" placeholder="Buscar usuarios o destinos..." type="text"/>
      </div>
    </div>
    <div class="barra-superior__acciones">
      <button class="boton-icono-redondo">
        <span class="material-symbols-outlined">notifications</span>
      </button>
      <button class="boton-icono-redondo">
        <span class="material-symbols-outlined">settings</span>
      </button>
      <div class="separador-vertical"></div>
      <div class="perfil-admin">
        <span class="perfil-admin__info">
          <p class="texto-nombre-admin">Admin User</p>
          <p class="texto-rol-admin">Super Administrator</p>
        </span>
        <div class="avatar-admin">
          <img alt="Admin Profile" class="imagen-avatar" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBbx33KNFbf4t5GL3yUCRv4BOytix3gw_f1AHLCE2zB77tW2tIrwwEhY8bamwWcfkJv5Ln8Vd167ngGwxEIFX26tAljfSX4cP4zV8W7uI9a4IQLjCLB-F_KL8Y9ZhJzrCvdVTimENDgAUYVfPIsFJb_sDxbRMeG_jQea9QyXXswXDHTyO_rrXTesKLQVjGHhVfhbAc8F0Yu7kD83Yc499M0c73OmKNvB5bJd5-AGzwIpgWtmRPI9wc99KTerpZ0U56FctP_PM2g9E3d"/>
        </div>
      </div>
    </div>
  </header>

  <!-- Contenido Principal -->
  <main class="contenido-principal">

    <!-- Encabezado de sección -->
    <section class="encabezado-seccion">
      <div class="encabezado-seccion__texto">
        <h2 class="titulo-seccion">Gestión de Usuarios</h2>
        <p class="descripcion-seccion">Controla el acceso, roles y estados de los miembros de la plataforma Voyager Admin.</p>
      </div>
      <button class="boton-nuevo-usuario">
        <span class="material-symbols-outlined">add</span>
        Nuevo Usuario
      </button>
    </section>

    <!-- Cuadrícula de Estadísticas -->
    <div class="cuadricula-estadisticas">

      <div class="tarjeta-estadistica">
        <div>
          <p class="etiqueta-estadistica">Total Usuarios</p>
          <h3 class="valor-estadistica">1,284</h3>
        </div>
        <div class="icono-estadistica icono-estadistica--primario">
          <span class="material-symbols-outlined">group</span>
        </div>
      </div>

      <div class="tarjeta-estadistica">
        <div>
          <p class="etiqueta-estadistica">Usuarios Activos</p>
          <h3 class="valor-estadistica">842</h3>
        </div>
        <div class="icono-estadistica icono-estadistica--terciario">
          <span class="material-symbols-outlined">how_to_reg</span>
        </div>
      </div>

      <div class="tarjeta-estadistica tarjeta-estadistica--vacia">
        <p class="texto-proximas-metricas">Próximas métricas...</p>
      </div>

      <div class="tarjeta-estadistica tarjeta-estadistica--vacia">
        <p class="texto-proximas-metricas">Próximas métricas...</p>
      </div>

    </div>

    <!-- Tabla de Usuarios -->
    <section class="seccion-tabla">
      <div class="contenedor-tabla">
        <table class="tabla-usuarios">
          <thead>
            <tr class="encabezado-tabla-fila">
              <th class="encabezado-tabla">Avatar</th>
              <th class="encabezado-tabla">Nombre</th>
              <th class="encabezado-tabla">Email</th>
              <th class="encabezado-tabla">Rol</th>
              <th class="encabezado-tabla">Estado</th>
              <th class="encabezado-tabla encabezado-tabla--derecha">Acciones</th>
            </tr>
          </thead>
          <tbody class="cuerpo-tabla">

            <!-- Fila Usuario 1 -->
            <tr class="fila-usuario">
              <td class="celda-tabla">
                <div class="contenedor-avatar">
                  <img alt="Elena Rodriguez" class="imagen-avatar" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBzM9rex7PCMzb6GEwXoMaB6MLFZmYobl88ANVonMw4V-Jrc4JPLNr9Q9XcEckkwT4d6ZplFNLTdxOhJ3mp2qQEkrBSEWf5DpezvhemT_DZY-anyCMuFx4_bUVeZXfarwh_cU5V6GrnY5vDxPN5ne_j4nXXxfgJCIvFST33GaoEK6GGjs2ygDYeigtswSIoYLB_VxEEM866QuGPUhc-TBjq3P6Fn6nyTboVskjhftEmy1UWfOYQnTFVBUc274PMEWX9PX1DFOXQUSct"/>
                </div>
              </td>
              <td class="celda-tabla"><p class="nombre-usuario">Elena Rodriguez</p></td>
              <td class="celda-tabla"><p class="correo-usuario">[email protected]</p></td>
              <td class="celda-tabla"><span class="insignia-rol insignia-rol--editor">Editor</span></td>
              <td class="celda-tabla">
                <div class="estado-usuario">
                  <div class="indicador-estado indicador-estado--activo"></div>
                  <span class="texto-estado texto-estado--activo">Activo</span>
                </div>
              </td>
              <td class="celda-tabla celda-tabla--derecha">
                <div class="contenedor-acciones">
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
              <td class="celda-tabla">
                <div class="contenedor-avatar">
                  <img alt="Marco Valencia" class="imagen-avatar" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBl4NATyzIRJQPTrw20Mj0h0Cf2wolLrFwUISFrtXtZ6qkfidnRvvy0NTipdRHkJ72xFlMT5jcO48GiuFQbXThH5aTHDF4nggFuEBX2BLcLFxv3eGgxHFYNHYE7m-MnnwiEu_Xh3aFxH1fZ6jqYKQ2bJKF-pi6s2WTARUeW6UCNWXTfliepxEPEZavdR1fhQiOKxgbD8DjCjn6DYW2c_gDjD7C81fFskA2K_CCnR2WuA8Sk3Rvyx8U-Yz-u1SzwDJr1w82HJl2kKzYn"/>
                </div>
              </td>
              <td class="celda-tabla"><p class="nombre-usuario">Marco Valencia</p></td>
              <td class="celda-tabla"><p class="correo-usuario">[email protected]</p></td>
              <td class="celda-tabla"><span class="insignia-rol insignia-rol--admin">Admin</span></td>
              <td class="celda-tabla">
                <div class="estado-usuario">
                  <div class="indicador-estado indicador-estado--activo"></div>
                  <span class="texto-estado texto-estado--activo">Activo</span>
                </div>
              </td>
              <td class="celda-tabla celda-tabla--derecha">
                <div class="contenedor-acciones">
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
              <td class="celda-tabla">
                <div class="contenedor-avatar">
                  <img alt="Sara Mendez" class="imagen-avatar" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBvKakOUsISxqYIojOZDygt9ftwk_OnlEz3xTEg0LNTh7mivt2WF0ND9Oo1H_VAwxPzcg68e67c7xmiSJkiwC30ar2gh9MdtfWegyNyeOvNIewkWQHLsyaah8Fw8aloQNZ4UimtZvtmONfnqJA5bml0d_zLB-GFx9CedUvDTI7HW9Hu4BRQAJi7OkWjRgpC2CE-Zbd-kp9hrWZa8x6MZGqFmkP3frr2XzRcHAur-ZBmBeMDkG7Iy2W9SNG6TkZSgqjqtTHO1pQ2JdeI"/>
                </div>
              </td>
              <td class="celda-tabla"><p class="nombre-usuario">Sara Mendez</p></td>
              <td class="celda-tabla"><p class="correo-usuario">[email protected]</p></td>
              <td class="celda-tabla"><span class="insignia-rol insignia-rol--visor">Viewer</span></td>
              <td class="celda-tabla">
                <div class="estado-usuario">
                  <div class="indicador-estado indicador-estado--inactivo"></div>
                  <span class="texto-estado texto-estado--inactivo">Inactivo</span>
                </div>
              </td>
              <td class="celda-tabla celda-tabla--derecha">
                <div class="contenedor-acciones">
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
              <td class="celda-tabla">
                <div class="contenedor-avatar">
                  <img alt="Lucas Kim" class="imagen-avatar" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDUGu40F6tWaU-2RWs1BKjtpt0bVFu6WEGTfi2p_Rh4jI2AtWccKTkrbEOtUE7gC1H1Fygsmmlgco_O29S3R2ZSV5WfOpxNaw5E-yiaZTzoRZuSxfTKxzjuOqFCTcNBbgvIRaMpvMgmp3uppYjjpaxN-_4PSFqUUWhh8HM8NvO7GGuODLBFBERalHb4o_Ey7AFut1hKa1CmsdtQ4x0FIQEVPVsDiROuhCJljgAvaMqkjUkJ4P4ArgZQQr5WSKLKyNMtYA0YBLcQqgdw"/>
                </div>
              </td>
              <td class="celda-tabla"><p class="nombre-usuario">Lucas Kim</p></td>
              <td class="celda-tabla"><p class="correo-usuario">[email protected]</p></td>
              <td class="celda-tabla"><span class="insignia-rol insignia-rol--editor">Editor</span></td>
              <td class="celda-tabla">
                <div class="estado-usuario">
                  <div class="indicador-estado indicador-estado--activo"></div>
                  <span class="texto-estado texto-estado--activo">Activo</span>
                </div>
              </td>
              <td class="celda-tabla celda-tabla--derecha">
                <div class="contenedor-acciones">
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
        <p class="texto-paginacion">Mostrando 4 de 1,284 usuarios</p>
        <div class="controles-paginacion">
          <button class="boton-paginacion">
            <span class="material-symbols-outlined">chevron_left</span>
          </button>
          <button class="boton-paginacion boton-paginacion--activo">1</button>
          <button class="boton-paginacion">2</button>
          <button class="boton-paginacion">3</button>
          <button class="boton-paginacion">
            <span class="material-symbols-outlined">chevron_right</span>
          </button>
        </div>
      </div>
    </section>

  </main>

  <script src="/scripts/script-dashboard-usuarios.js"></script>
</body>
</html>