<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Sistema de Inventario</title>
    <meta
      content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
      name="viewport"
    />
    <link
      rel="icon"
      href="../assets/img/kaiadmin/favicon.ico"
      type="image/x-icon"
    />

    <!-- Fonts and icons -->
    <script src="../assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["../assets/css/fonts.min.css"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../assets/css/plugins.min.css" />
    <link rel="stylesheet" href="../assets/css/kaiadmin.min.css" />

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="../assets/css/demo.css" />
  </head>
  <body>
    <div class="wrapper sidebar_minimize">
      <!-- Sidebar -->
      <div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
          <!-- Logo Header -->
          <div class="logo-header" data-background-color="dark">
            <a href="../index.php" class="logo">
              <img
                src="../assets/img/kaiadmin/logo_light.svg"
                alt="navbar brand"
                class="navbar-brand"
                height="20"
              />
            </a>
            <div class="nav-toggle">
              <button class="btn btn-toggle toggle-sidebar">
                <i class="gg-menu-right"></i>
              </button>
              <button class="btn btn-toggle sidenav-toggler">
                <i class="gg-menu-left"></i>
              </button>
            </div>
            <button class="topbar-toggler more">
              <i class="gg-more-vertical-alt"></i>
            </button>
          </div>
          <!-- End Logo Header -->
        </div>
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
          <div class="sidebar-content">
            <ul class="nav nav-secondary">
              <li class="nav-item">
                <a
                  data-bs-toggle="collapse"
                  href="#dashboard"
                  class="collapsed"
                  aria-expanded="false"
                >
                  <i class="fas fa-home"></i>
                  <p>Dashboard</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="dashboard">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="../../demo1/index.php">
                        <span class="sub-item">Dashboard 1</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="nav-item active submenu">
                <a data-bs-toggle="collapse" href="#base">
                  <i class="fas fa-dolly"></i>
                  <p>Inventario</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse show" id="base">
                  <ul class="nav nav-collapse">
                    <li class="active">
                      <a href="proveedor.php">
                        <span class="sub-item">Proveedor</span>
                      </a>
                    </li>
                    <li>
                      <a href="categoria.php">
                        <span class="sub-item">Catogorias</span>
                      </a>
                    </li>
                    <li  >
                      <a href="productos.php">
                        <span class="sub-item">Productos</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#sidebarLayouts">
                  <i class="fas fa-dollar-sign"></i>
                  <p>Admin</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="sidebarLayouts">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="../admin/compras.php">
                        <span class="sub-item">Compras</span>
                      </a>
                    </li>
                    <li>
                      <a href="components/buttons.html">
                        <span class="sub-item">Ventas</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
            
            </ul>
          </div>
        </div>
      </div>
      <!-- End Sidebar -->
      
      <div class="main-panel">
        <div class="main-header">
          <div class="main-header-logo">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="dark">
              <a href="../index.php" class="logo">
                <img
                  src="../assets/img/kaiadmin/logo_light.svg"
                  alt="navbar brand"
                  class="navbar-brand"
                  height="20"
                />
              </a>
              <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                  <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                  <i class="gg-menu-left"></i>
                </button>
              </div>
              <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
              </button>
            </div>
            <!-- End Logo Header -->
          </div>
          <!-- Navbar Header -->
          <nav
            class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom"
          >
            <div class="container-fluid">
              <nav
                class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex"
              >
                <div class="input-group">
                  <div class="input-group-prepend">
                    <button type="submit" class="btn btn-search pe-1">
                      <i class="fa fa-search search-icon"></i>
                    </button>
                  </div>
                  <input
                    type="text"
                    placeholder="Buscar ..."
                    class="form-control"
                  />
                </div>
              </nav>

              <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                <li
                  class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none"
                >
                  <a
                    class="nav-link dropdown-toggle"
                    data-bs-toggle="dropdown"
                    href="#"
                    role="button"
                    aria-expanded="false"
                    aria-haspopup="true"
                  >
                    <i class="fa fa-search"></i>
                  </a>
                  <ul class="dropdown-menu dropdown-search animated fadeIn">
                    <form class="navbar-left navbar-form nav-search">
                      <div class="input-group">
                        <input
                          type="text"
                          placeholder="Buscar ..."
                          class="form-control"
                        />
                      </div>
                    </form>
                  </ul>
                </li>

                <li class="nav-item topbar-icon dropdown hidden-caret">
                  <a
                    class="nav-link dropdown-toggle"
                    href="#"
                    id="notifDropdown"
                    role="button"
                    data-bs-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                  >
                    <i class="fa fa-bell"></i>
                    <span class="notification">4</span>
                  </a>
                  <ul
                    class="dropdown-menu notif-box animated fadeIn"
                    aria-labelledby="notifDropdown"
                  >
                    <li>
                      <div class="dropdown-title">
                        You have 4 new notification
                      </div>
                    </li>
                    <li>
                      <div class="notif-scroll scrollbar-outer">
                        <div class="notif-center">
                          <a href="#">
                            <div class="notif-icon notif-primary">
                              <i class="fa fa-user-plus"></i>
                            </div>
                            <div class="notif-content">
                              <span class="block"> New user registered </span>
                              <span class="time">5 minutes ago</span>
                            </div>
                          </a>
                          <a href="#">
                            <div class="notif-icon notif-success">
                              <i class="fa fa-comment"></i>
                            </div>
                            <div class="notif-content">
                              <span class="block">
                                Rahmad commented on Admin
                              </span>
                              <span class="time">12 minutes ago</span>
                            </div>
                          </a>
                          <a href="#">
                            <div class="notif-img">
                              <img
                                src="../assets/img/profile2.jpg"
                                alt="Img Profile"
                              />
                            </div>
                            <div class="notif-content">
                              <span class="block">
                                Reza send messages to you
                              </span>
                              <span class="time">12 minutes ago</span>
                            </div>
                          </a>
                          <a href="#">
                            <div class="notif-icon notif-danger">
                              <i class="fa fa-heart"></i>
                            </div>
                            <div class="notif-content">
                              <span class="block"> Farrah liked Admin </span>
                              <span class="time">17 minutes ago</span>
                            </div>
                          </a>
                        </div>
                      </div>
                    </li>
                    <li>
                      <a class="see-all" href="javascript:void(0);"
                        >See all notifications<i class="fa fa-angle-right"></i>
                      </a>
                    </li>
                  </ul>
                </li>
                <li class="nav-item topbar-icon dropdown hidden-caret">
                  <a
                    class="nav-link"
                    data-bs-toggle="dropdown"
                    href="#"
                    aria-expanded="false"
                  >
                    <i class="fas fa-layer-group"></i>
                  </a>
                  <div class="dropdown-menu quick-actions animated fadeIn">
                    <div class="quick-actions-header">
                      <span class="title mb-1">Quick Actions</span>
                      <span class="subtitle op-7">Shortcuts</span>
                    </div>
                    <div class="quick-actions-scroll scrollbar-outer">
                      <div class="quick-actions-items">
                        <div class="row m-0">
                          <a class="col-6 col-md-4 p-0" href="#">
                            <div class="quick-actions-item">
                              <div class="avatar-item bg-danger rounded-circle">
                                <i class="far fa-calendar-alt"></i>
                              </div>
                              <span class="text">Calendar</span>
                            </div>
                          </a>
                          <a class="col-6 col-md-4 p-0" href="#">
                            <div class="quick-actions-item">
                              <div
                                class="avatar-item bg-warning rounded-circle"
                              >
                                <i class="fas fa-map"></i>
                              </div>
                              <span class="text">Maps</span>
                            </div>
                          </a>
                          <a class="col-6 col-md-4 p-0" href="#">
                            <div class="quick-actions-item">
                              <div class="avatar-item bg-info rounded-circle">
                                <i class="fas fa-file-excel"></i>
                              </div>
                              <span class="text">Reports</span>
                            </div>
                          </a>
                          <a class="col-6 col-md-4 p-0" href="#">
                            <div class="quick-actions-item">
                              <div
                                class="avatar-item bg-success rounded-circle"
                              >
                                <i class="fas fa-envelope"></i>
                              </div>
                              <span class="text">Emails</span>
                            </div>
                          </a>
                          <a class="col-6 col-md-4 p-0" href="#">
                            <div class="quick-actions-item">
                              <div
                                class="avatar-item bg-primary rounded-circle"
                              >
                                <i class="fas fa-file-invoice-dollar"></i>
                              </div>
                              <span class="text">Invoice</span>
                            </div>
                          </a>
                          <a class="col-6 col-md-4 p-0" href="#">
                            <div class="quick-actions-item">
                              <div
                                class="avatar-item bg-secondary rounded-circle"
                              >
                                <i class="fas fa-credit-card"></i>
                              </div>
                              <span class="text">Payments</span>
                            </div>
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                </li>

                <li class="nav-item topbar-user dropdown hidden-caret">
                  <a
                    class="dropdown-toggle profile-pic"
                    data-bs-toggle="dropdown"
                    href="#"
                    aria-expanded="false"
                  >
                    <div class="avatar-sm">
                      <img
                        src="../assets/img/profile.jpg"
                        alt="..."
                        class="avatar-img rounded-circle"
                      />
                    </div>
                    <span class="profile-username">
                      <span class="op-7">Hi,</span>
                      <span class="fw-bold">Hizrian</span>
                    </span>
                  </a>
                  <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                      <li>
                        <div class="user-box">
                          <div class="avatar-lg">
                            <img
                              src="../assets/img/profile.jpg"
                              alt="image profile"
                              class="avatar-img rounded"
                            />
                          </div>
                          <div class="u-text">
                            <h4>Hizrian</h4>
                            <p class="text-muted">hello@example.com</p>
                            <a
                              href="profile.html"
                              class="btn btn-xs btn-secondary btn-sm"
                              >View Profile</a
                            >
                          </div>
                        </div>
                      </li>
                      <li>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">My Profile</a>
                        <a class="dropdown-item" href="#">My Balance</a>
                        <a class="dropdown-item" href="#">Inbox</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Account Setting</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Logout</a>
                      </li>
                    </div>
                  </ul>
                </li>
              </ul>
            </div>
          </nav>
          <!-- End Navbar -->
        </div>

        <div class="container">
          <div class="page-inner">
            <div class="page-header">
              <h3 class="fw-bold mb-3">Sistema de Inventario</h3>
              <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                  <a href="#">
                    <i class="icon-home"></i>
                  </a>
                </li>
                <li class="separator">
                  <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                  <a href="#">Inventario</a>
                </li>
                <li class="separator">
                  <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                  <a href="#">Proveedores</a>
                </li>
              </ul>
            </div>
            <div class="row">

            <div class="card">
    <div class="card-header">
        <h4 class="card-title">Registro de Órdenes de Trabajo</h4>
    </div>
    <div class="card-body">
        <form id="ordenForm">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Cliente</label>
                        <select class="form-control" id="clienteOrden" required>
                            <option value="">Seleccione un cliente</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Vehículo</label>
                        <select class="form-control" id="vehiculoOrden" required disabled>
                            <option value="">Seleccione un vehículo</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Fecha de Orden</label>
                        <input type="date" class="form-control" id="fechaOrden" required>
                    </div>
                </div>
            </div>

            <h5>Agregar Servicios</h5>
            <div class="row mb-3">
                <div class="col-md-8">
                    <div class="form-group">
                        <label>Servicio</label>
                        <select class="form-control" id="servicioOrden">
                            <option value="">Seleccione un servicio</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="button" id="agregarServicioBtn" class="btn btn-primary w-100">
                        <i class="fas fa-plus"></i> Agregar
                    </button>
                </div>
            </div>

            <h5>Agregar Productos</h5>
            <div class="row mb-3">
                <div class="col-md-5">
                    <div class="form-group">
                        <label>Producto</label>
                        <select class="form-control" id="productoOrden">
                            <option value="">Seleccione un producto</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Cantidad</label>
                        <input type="number" class="form-control" id="cantidadProducto" min="1" value="1">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Precio Unitario</label>
                        <input type="number" step="0.01" class="form-control" id="precioProducto" readonly>
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" id="agregarProductoBtn" class="btn btn-primary w-100">
                        <i class="fas fa-plus"></i> Agregar
                    </button>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">Servicios Seleccionados</h5>
                </div>
                <div class="card-body">
                    <div id="serviciosSeleccionados" class="mb-3">
                        <p class="text-muted text-center">No hay servicios agregados</p>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">Productos Seleccionados</h5>
                </div>
                <div class="card-body">
                    <div id="productosSeleccionados" class="mb-3">
                        <p class="text-muted text-center">No hay productos agregados</p>
                    </div>
                </div>
            </div>

            <div class="total-container">
                <h4 class="text-end">Total: <span id="totalOrden">$0.00</span></h4>
            </div>

            <div class="d-flex justify-content-end">
                <button type="button" id="registrarOrdenBtn" class="btn btn-success btn-lg" disabled>
                    <i class="fas fa-save"></i> Registrar Orden
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tabla de Órdenes Registradas -->
<div class="card mt-5">
    <div class="card-header">
        <h4 class="card-title">Historial de Órdenes</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="ordenesTable" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Vehículo</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
</div>
<!-- Modal para Ver Detalle de Orden -->
<div class="modal fade" id="detalleOrdenModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalle de Orden #<span id="detalleOrdenID"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <p><strong>Cliente:</strong> <span id="detalleCliente"></span></p>
                        <p><strong>Vehículo:</strong> <span id="detalleVehiculo"></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Fecha:</strong> <span id="detalleFecha"></span></p>
                        <p><strong>Estado:</strong> <span id="detalleEstado"></span></p>
                    </div>
                </div>
                
                <h5>Servicios</h5>
                <div class="table-responsive mb-4">
                    <table id="detalleServiciosTable" class="table">
                        <thead>
                            <tr>
                                <th>Servicio</th>
                                <th>Precio Unitario</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                
                <h5>Productos</h5>
                <div class="table-responsive mb-4">
                    <table id="detalleProductosTable" class="table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio Unitario</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                
                <div class="total-container mt-3">
                    <h4 class="text-end">Total: <span id="detalleTotalOrden">$0.00</span></h4>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Cambiar Estado -->
<div class="modal fade" id="cambiarEstadoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cambiar Estado Orden #<span id="estadoOrdenID"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nuevo Estado</label>
                    <select class="form-control" id="nuevoEstado">
                        <option value="Pendiente">Pendiente</option>
                        <option value="En Proceso">En Proceso</option>
                        <option value="Completada">Completada</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="confirmarCambioEstadoBtn" class="btn btn-primary">Confirmar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Confirmar Eliminación -->
<div class="modal fade" id="confirmarEliminarModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar esta orden?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" id="confirmarEliminarBtn" class="btn btn-danger">Eliminar</button>
            </div>
        </div>
    </div>
</div>
          </div>

        <footer class="footer">
          <div class="container-fluid d-flex justify-content-between">
            <nav class="pull-left">
              <ul class="nav">
                <li class="nav-item">
                  <a class="nav-link" href="http://www.themekita.com">
                    NAVETECH
                  </a>
                </li>
              </ul>
            </nav>
            <div class="copyright">
              2025, desarollado <i class="fa text-danger"></i> por
              <a href="http://www.themekita.com">Ing: Esteban Loachamin</a>
            </div>
            <div>
              Distribuido Por
              <a target="_blank" href="https://themewagon.com/">Nave Tech</a>.
            </div>
          </div>
        </footer>
      </div>


      <!-- End Custom template -->
    </div>
    <!--   Core JS Files   -->
    <script src="../assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="../assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <!-- Datatables -->
    <script src="../assets/js/plugin/datatables/datatables.min.js"></script>
    <!-- Kaiadmin JS -->
    <script src="../assets/js/kaiadmin.min.js"></script>
    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
    <script src="../assets/js/setting-demo2.js"></script>

 <!-- Script para manejar la tabla dinámica -->
 <script>
        $(document).ready(function() {
            // Inicializar DataTable
            const table = $('#productosTable').DataTable({
                ajax: {
                    url: 'crud_productos.php?action=read',
                    dataSrc: ''
                },
                columns: [
                    { data: 'ProductoID' },
                    { data: 'Nombre' },
                    { 
                        data: 'PrecioCompra',
                        render: function(data) {
                            return '$' + parseFloat(data).toFixed(2);
                        }
                    },
                    { 
                        data: 'PrecioVenta',
                        render: function(data) {
                            return '$' + parseFloat(data).toFixed(2);
                        }
                    },
                    { 
                        data: 'Ganancia',
                        render: function(data) {
                            return '$' + parseFloat(data).toFixed(2);
                        }
                    },
                    { data: 'Stock' },
                    { 
                        data: 'Estado',
                        render: function(data) {
                            const badgeClass = data === 'Normal' ? 'badge bg-success' : 'badge bg-warning text-dark';
                            return `<span class="${badgeClass}">${data}</span>`;
                        }
                    },
                    { data: 'CategoriaNombre' },
                    { data: 'ProveedorNombre' },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `
                                <div class="form-button-action">
                                    <button type="button" class="btn btn-link btn-primary btn-lg" onclick="editarProducto('${row.ProductoID}')">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-link btn-danger" onclick="eliminarProducto('${row.ProductoID}')">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            `;
                        }
                    }
                ]
            });

            // Cargar categorías y proveedores al abrir el modal de agregar
            $('#addProductoModal').on('show.bs.modal', function() {
                cargarSelectCategorias('#categoriaID');
                cargarSelectProveedores('#ruc');
                $('#fechaIngreso').val(new Date().toISOString().split('T')[0]);
            });

            // Cargar categorías y proveedores al abrir el modal de editar
            $('#editProductoModal').on('show.bs.modal', function() {
                cargarSelectCategorias('#editCategoriaID');
                cargarSelectProveedores('#editRuc');
            });

            // Función para cargar categorías en un select
            function cargarSelectCategorias(selector) {
                $.ajax({
                    url: 'crud_productos.php?action=get_categorias',
                    method: 'GET',
                    success: function(data) {
                        const categorias = JSON.parse(data);
                        const select = $(selector);
                        select.empty();
                        categorias.forEach(categoria => {
                            select.append(`<option value="${categoria.CategoriaID}">${categoria.Nombre}</option>`);
                        });
                    }
                });
            }

            // Función para cargar proveedores en un select
            function cargarSelectProveedores(selector) {
                $.ajax({
                    url: 'crud_productos.php?action=get_proveedores',
                    method: 'GET',
                    success: function(data) {
                        const proveedores = JSON.parse(data);
                        const select = $(selector);
                        select.empty();
                        proveedores.forEach(proveedor => {
                            select.append(`<option value="${proveedor.RUC}">${proveedor.Nombre} (${proveedor.RUC})</option>`);
                        });
                    }
                });
            }

            // Agregar producto
            $('#addProductoBtn').click(function() {
                const productoData = {
                    productoID: $('#productoID').val(),
                    nombre: $('#nombre').val(),
                    descripcion: $('#descripcion').val(),
                    precioCompra: $('#precioCompra').val(),
                    precioVenta: $('#precioVenta').val(),
                    stock: $('#stock').val(),
                    estado: $('#estado').val(),
                    compatibilidad: $('#compatibilidad').val(),
                    medidas: $('#medidas').val(),
                    fechaIngreso: $('#fechaIngreso').val(),
                    categoriaID: $('#categoriaID').val(),
                    ruc: $('#ruc').val()
                };

                $.ajax({
                    url: 'crud_productos.php?action=create',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(productoData),
                    success: function(response) {
                        const res = JSON.parse(response);
                        if (res.success) {
                            table.ajax.reload();
                            $('#addProductoModal').modal('hide');
                            $('#addProductoForm')[0].reset();
                            alert(res.success);
                        } else {
                            alert(res.error);
                        }
                    }
                });
            });

            // Editar producto
            window.editarProducto = function(productoID) {
                $.ajax({
                    url: 'crud_productos.php?action=read',
                    method: 'GET',
                    success: function(data) {
                        const productos = JSON.parse(data);
                        const producto = productos.find(p => p.ProductoID === productoID);

                        if (producto) {
                            $('#editProductoID').val(producto.ProductoID);
                            $('#editNombre').val(producto.Nombre);
                            $('#editDescripcion').val(producto.Descripcion);
                            $('#editPrecioCompra').val(producto.PrecioCompra);
                            $('#editPrecioVenta').val(producto.PrecioVenta);
                            $('#editStock').val(producto.Stock);
                            $('#editEstado').val(producto.Estado);
                            $('#editCompatibilidad').val(producto.Compatibilidad);
                            $('#editMedidas').val(producto.Medidas);
                            $('#editFechaIngreso').val(producto.FechaIngreso);
                            
                            // Necesitamos esperar a que se carguen los selects
                            $('#editProductoModal').modal('show');
                            
                            // Usamos setTimeout para asegurar que los selects estén cargados
                            setTimeout(() => {
                                $('#editCategoriaID').val(producto.CategoriaID);
                                $('#editRuc').val(producto.RUC);
                            }, 500);
                        }
                    }
                });
            };

            // Guardar cambios al editar
            $('#editProductoBtn').click(function() {
                const productoData = {
                    productoID: $('#editProductoID').val(),
                    nombre: $('#editNombre').val(),
                    descripcion: $('#editDescripcion').val(),
                    precioCompra: $('#editPrecioCompra').val(),
                    precioVenta: $('#editPrecioVenta').val(),
                    stock: $('#editStock').val(),
                    estado: $('#editEstado').val(),
                    compatibilidad: $('#editCompatibilidad').val(),
                    medidas: $('#editMedidas').val(),
                    fechaIngreso: $('#editFechaIngreso').val(),
                    categoriaID: $('#editCategoriaID').val(),
                    ruc: $('#editRuc').val()
                };

                $.ajax({
                    url: 'crud_productos.php?action=update',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(productoData),
                    success: function(response) {
                        const res = JSON.parse(response);
                        if (res.success) {
                            table.ajax.reload();
                            $('#editProductoModal').modal('hide');
                            alert(res.success);
                        } else {
                            alert(res.error);
                        }
                    }
                });
            });

            // Eliminar producto
            window.eliminarProducto = function(productoID) {
                if (confirm('¿Estás seguro de eliminar este producto?')) {
                    $.ajax({
                        url: 'crud_productos.php?action=delete',
                        method: 'POST',
                        data: { productoID: productoID },
                        success: function(response) {
                            const res = JSON.parse(response);
                            if (res.success) {
                                table.ajax.reload();
                                alert(res.success);
                            } else {
                                alert(res.error);
                            }
                        }
                    });
                }
            };
        });
    </script>

    <script>
      $(document).ready(function () {
        $("#basic-datatables").DataTable({});

        $("#multi-filter-select").DataTable({
          pageLength: 5,
          initComplete: function () {
            this.api()
              .columns()
              .every(function () {
                var column = this;
                var select = $(
                  '<select class="form-select"><option value=""></option></select>'
                )
                  .appendTo($(column.footer()).empty())
                  .on("change", function () {
                    var val = $.fn.dataTable.util.escapeRegex($(this).val());

                    column
                      .search(val ? "^" + val + "$" : "", true, false)
                      .draw();
                  });

                column
                  .data()
                  .unique()
                  .sort()
                  .each(function (d, j) {
                    select.append(
                      '<option value="' + d + '">' + d + "</option>"
                    );
                  });
              });
          },
        });

        // Add Row
        $("#add-row").DataTable({
          pageLength: 5,
        });

        var action =
          '<td> <div class="form-button-action"> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';

        $("#addRowButton").click(function () {
          $("#add-row")
            .dataTable()
            .fnAddData([
              $("#addName").val(),
              $("#addPosition").val(),
              $("#addOffice").val(),
              action,
            ]);
          $("#addRowModal").modal("hide");
        });
      });
    </script>
  </body>
</html>
