<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/mecanica2/auth.php';
?>
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

<style>
        .producto-item {
            border-bottom: 1px solid #eee;
            padding: 10px 0;
        }
        #productosSeleccionados {
            max-height: 400px;
            overflow-y: auto;
        }
        .total-container {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .select2-container--default .select2-selection--single {
            height: 38px;
            padding: 5px;
            border: 1px solid #ced4da;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }
    </style>

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
                    <li>
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
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#forms">
                  <i class="fas fa-user-plus"></i>
                  <p>Clientes</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="forms">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="../users/clientes.php">
                        <span class="sub-item">Clientes</span>
                      </a>
                    </li>
                    <li>
                      <a href="../users/vehiculo.php">
                        <span class="sub-item">Vehiculos</span>
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
<!-- JavaScript para Órdenes de Trabajo -->
$(document).ready(function() {
    // Variables globales
    let serviciosSeleccionados = [];
    let productosSeleccionados = [];
    let servicios = [];
    let productos = [];
    
    // Configuración de DataTables para la tabla de órdenes
    let ordenesTable = $('#ordenesTable').DataTable({
        ajax: {
            url: 'crud_ordenes.php?action=get_ordenes',
            dataSrc: ''
        },
        columns: [
            { data: 'OrdenID' },
            { 
                data: 'FechaOrden',
                render: function(data) {
                    return new Date(data).toLocaleDateString('es-ES');
                }
            },
            { data: 'ClienteNombre' },
            { 
                data: null,
                render: function(data) {
                    return `${data.Marca} ${data.Modelo} (${data.Placa})`;
                }
            },
            { 
                data: 'Total',
                render: function(data) {
                    return '$' + parseFloat(data).toFixed(2);
                }
            },
            { 
                data: 'Estado',
                render: function(data) {
                    let badgeClass = 'badge-secondary';
                    if (data === 'En Proceso') badgeClass = 'badge-warning';
                    if (data === 'Completada') badgeClass = 'badge-success';
                    
                    return `<span class="badge ${badgeClass}">${data}</span>`;
                }
            },
            {
                data: null,
                render: function(data) {
                    return `
                        <button class="btn btn-sm btn-info me-1" onclick="verDetalleOrden(${data.OrdenID})">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-warning me-1" onclick="mostrarCambioEstado(${data.OrdenID}, '${data.Estado}')">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="mostrarConfirmacionEliminar(${data.OrdenID})">
                            <i class="fas fa-trash"></i>
                        </button>
                    `;
                },
                orderable: false
            }
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        }
    });

    // Cargar clientes, servicios y productos al iniciar
    cargarClientes();
    cargarServicios();
    cargarProductos();

    // Establecer fecha actual por defecto
    $('#fechaOrden').val(new Date().toISOString().split('T')[0]);

    // Cuando se selecciona un cliente, cargar sus vehículos
    $('#clienteOrden').change(function() {
        const cedula = $(this).val();
        if (cedula) {
            cargarVehiculosCliente(cedula);
            $('#vehiculoOrden').prop('disabled', false);
        } else {
            $('#vehiculoOrden').prop('disabled', true).val('');
        }
    });

    $('#productoOrden').change(function() {
    const productoID = $(this).val();
    if (productoID) {
        const producto = productos.find(p => p.ProductoID == productoID);
        if (producto) {
            // Asegurar que el precio es un número
            const precio = parseFloat(producto.Precio) || 0;
            $('#precioProducto').val(precio.toFixed(2));
            $('#cantidadProducto').val(1);
            $('#cantidadProducto').attr('max', producto.Stock);
        }
    } else {
        $('#precioProducto').val('');
        $('#cantidadProducto').val(1);
        $('#cantidadProducto').removeAttr('max');
    }
});

    // Agregar servicio a la orden
    $('#agregarServicioBtn').click(function() {
        const servicioID = $('#servicioOrden').val();
        
        if (!servicioID) {
            alert('Por favor seleccione un servicio');
            return;
        }

        const servicio = servicios.find(s => s.ServicioID == servicioID);
        if (!servicio) {
            alert('Servicio no encontrado');
            return;
        }

        const precio = servicio.Precio;

        // Verificar si el servicio ya está en la lista
        const index = serviciosSeleccionados.findIndex(s => s.servicioID == servicioID);
        
        if (index >= 0) {
            alert('Este servicio ya ha sido agregado');
        } else {
            // Agregar nuevo servicio
            serviciosSeleccionados.push({
                servicioID: servicioID,
                nombre: servicio.Nombre,
                precio: precio
            });

            actualizarListaServicios();
            calcularTotal();
            $('#servicioOrden').val('').trigger('change');
            $('#registrarOrdenBtn').prop('disabled', false);
        }
    });

    // Agregar producto a la orden
    $('#agregarProductoBtn').click(function() {
    const productoID = $('#productoOrden').val();
    const cantidad = parseInt($('#cantidadProducto').val());
    
    if (!productoID || isNaN(cantidad) || cantidad < 1) {
        alert('Por favor complete todos los campos del producto');
        return;
    }

    const producto = productos.find(p => p.ProductoID == productoID);
    if (!producto) {
        alert('Producto no encontrado');
        return;
    }

    // Asegurar que el precio es un número
    const precio = parseFloat(producto.Precio) || 0;
        
        // Validar stock disponible
        if (cantidad > producto.Stock) {
            alert('No hay suficiente stock disponible');
            return;
        }

        const subtotal = cantidad * precio;

        // Verificar si el producto ya está en la lista
        const index = productosSeleccionados.findIndex(p => p.productoID == productoID);
        
        if (index >= 0) {
            // Validar que no supere el stock al sumar cantidades
            const nuevaCantidad = productosSeleccionados[index].cantidad + cantidad;
            if (nuevaCantidad > producto.Stock) {
                alert(`No puedes agregar más de ${producto.Stock} unidades de este producto (ya tienes ${productosSeleccionados[index].cantidad} en la lista)`);
                return;
            }
            
            // Actualizar cantidad y subtotal si ya existe
            productosSeleccionados[index].cantidad = nuevaCantidad;
            productosSeleccionados[index].subtotal = nuevaCantidad * precio;
        } else {
            // Agregar nuevo producto
            productosSeleccionados.push({
                productoID: productoID,
                nombre: producto.Nombre,
                cantidad: cantidad,
                precio: precio,
                subtotal: subtotal
            });
        }

        actualizarListaProductos();
        calcularTotal();
        $('#productoOrden').val('').trigger('change');
        $('#cantidadProducto').val(1);
        $('#registrarOrdenBtn').prop('disabled', false);
    });

    // Registrar orden
    $('#registrarOrdenBtn').click(function() {
        const cliente = $('#clienteOrden').val();
        const vehiculo = $('#vehiculoOrden').val();
        const fecha = $('#fechaOrden').val();
        const total = parseFloat($('#totalOrden').text().replace('$', ''));

        if (!cliente || !vehiculo || !fecha || (serviciosSeleccionados.length === 0 && productosSeleccionados.length === 0)) {
            alert('Por favor complete todos los campos requeridos');
            return;
        }

        const ordenData = {
            cedula: cliente,
            vehiculoID: vehiculo,
            fecha: fecha,
            total: total,
            servicios: serviciosSeleccionados,
            productos: productosSeleccionados
        };

        $.ajax({
            url: 'crud_ordenes.php?action=create_orden',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(ordenData),
            success: function(response) {
                try {
                    const res = JSON.parse(response);
                    if (res.success) {
                        alert(res.success);
                        // Limpiar formulario
                        serviciosSeleccionados = [];
                        productosSeleccionados = [];
                        $('#serviciosSeleccionados').html('<p class="text-muted text-center">No hay servicios agregados</p>');
                        $('#productosSeleccionados').html('<p class="text-muted text-center">No hay productos agregados</p>');
                        $('#totalOrden').text('$0.00');
                        $('#ordenForm')[0].reset();
                        $('#vehiculoOrden').prop('disabled', true);
                        $('#fechaOrden').val(new Date().toISOString().split('T')[0]);
                        $('#registrarOrdenBtn').prop('disabled', true);
                        
                        // Actualizar tabla de órdenes
                        ordenesTable.ajax.reload();
                    } else {
                        alert(res.error || 'Error desconocido al crear la orden');
                    }
                } catch (e) {
                    alert('Error al procesar la respuesta del servidor');
                    console.error(e);
                }
            },
            error: function(xhr, status, error) {
                alert('Error al enviar la orden: ' + error);
                console.error(xhr);
            }
        });
    });

    // Función para cargar clientes
    function cargarClientes() {
        $.ajax({
            url: 'crud_ordenes.php?action=get_clientes',
            method: 'GET',
            success: function(data) {
                try {
                    const clientes = JSON.parse(data);
                    const select = $('#clienteOrden');
                    select.empty().append('<option value="">Seleccione un cliente</option>');
                    clientes.forEach(cliente => {
                        select.append(`<option value="${cliente.Cedula}">${cliente.Nombre} (${cliente.Cedula})</option>`);
                    });
                } catch (e) {
                    console.error('Error al cargar clientes:', e);
                    alert('Error al cargar la lista de clientes');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error al obtener clientes:', error);
                alert('Error al cargar clientes');
            }
        });
    }

    // Función para cargar vehículos de un cliente
    function cargarVehiculosCliente(cedula) {
        $.ajax({
            url: `crud_ordenes.php?action=get_vehiculos&cedula=${cedula}`,
            method: 'GET',
            success: function(data) {
                try {
                    const vehiculos = JSON.parse(data);
                    const select = $('#vehiculoOrden');
                    select.empty().append('<option value="">Seleccione un vehículo</option>');
                    vehiculos.forEach(vehiculo => {
                        select.append(`<option value="${vehiculo.VehiculoID}">${vehiculo.InfoVehiculo}</option>`);
                    });
                } catch (e) {
                    console.error('Error al cargar vehículos:', e);
                    alert('Error al cargar la lista de vehículos');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error al obtener vehículos:', error);
                alert('Error al cargar vehículos');
            }
        });
    }
    function cargarServicios() {
    $.ajax({
        url: 'crud_ordenes.php?action=get_servicios',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            try {
                servicios = data;
                const select = $('#servicioOrden');
                select.empty().append('<option value="">Seleccione un servicio</option>');
                
                servicios.forEach(servicio => {
                    // Asegurar que el precio es un número
                    const precio = parseFloat(servicio.Precio) || 0;
                    select.append(`<option value="${servicio.ServicioID}">${servicio.Nombre} - $${precio.toFixed(2)}</option>`);
                });
            } catch (e) {
                console.error('Error al cargar servicios:', e);
                alert('Error al cargar la lista de servicios');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error al obtener servicios:', {
                status: status,
                error: error,
                responseText: xhr.responseText
            });
            alert('Error al cargar servicios. Ver consola para detalles.');
        }
    });
}

function cargarProductos() {
    $.ajax({
        url: 'crud_ordenes.php?action=get_productos',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            try {
                productos = data;
                console.log("Productos cargados:", productos);
                const select = $('#productoOrden');
                select.empty().append('<option value="">Seleccione un producto</option>');
                
                productos.forEach(producto => {
                    // Asegurar que el precio es un número
                    const precio = parseFloat(producto.Precio) || 0;
                    select.append(`<option value="${producto.ProductoID}">${producto.Nombre} - $${precio.toFixed(2)} (Stock: ${producto.Stock})</option>`);
                });
            } catch (e) {
                console.error('Error al cargar productos:', e);
                alert('Error al cargar la lista de productos');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error al obtener productos:', {
                status: status,
                error: error,
                responseText: xhr.responseText
            });
            alert('Error al cargar productos. Ver consola para detalles.');
        }
    });
}

    // Función para actualizar la lista visual de servicios
    function actualizarListaServicios() {
        const container = $('#serviciosSeleccionados');
        container.empty();

        if (serviciosSeleccionados.length === 0) {
            container.html('<p class="text-muted text-center">No hay servicios agregados</p>');
            return;
        }

        const table = $('<table class="table table-sm"></table>');
        const thead = $('<thead><tr><th>Servicio</th><th>Precio</th><th></th></tr></thead>');
        const tbody = $('<tbody></tbody>');
        
        table.append(thead);
        table.append(tbody);
        container.append(table);

        serviciosSeleccionados.forEach((servicio, index) => {
            const row = $(`
                <tr>
                    <td>${servicio.nombre}</td>
                    <td>$${servicio.precio.toFixed(2)}</td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `);
            
            // Asignar evento de eliminación
            row.find('button').click(function() {
                eliminarServicioSeleccionado(index);
            });
            
            tbody.append(row);
        });
    }

    // Función para actualizar la lista visual de productos
    function actualizarListaProductos() {
        const container = $('#productosSeleccionados');
        container.empty();

        if (productosSeleccionados.length === 0) {
            container.html('<p class="text-muted text-center">No hay productos agregados</p>');
            return;
        }

        const table = $('<table class="table table-sm"></table>');
        const thead = $('<thead><tr><th>Producto</th><th>Cantidad</th><th>P. Unitario</th><th>Subtotal</th><th></th></tr></thead>');
        const tbody = $('<tbody></tbody>');
        
        table.append(thead);
        table.append(tbody);
        container.append(table);

        productosSeleccionados.forEach((producto, index) => {
            const row = $(`
                <tr>
                    <td>${producto.nombre}</td>
                    <td>${producto.cantidad}</td>
                    <td>$${producto.precio.toFixed(2)}</td>
                    <td>$${producto.subtotal.toFixed(2)}</td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `);
            
            // Asignar evento de eliminación
            row.find('button').click(function() {
                eliminarProductoSeleccionado(index);
            });
            
            tbody.append(row);
        });
    }

    // Función para calcular el total
    function calcularTotal() {
        const totalServicios = serviciosSeleccionados.reduce((sum, servicio) => sum + servicio.precio, 0);
        const totalProductos = productosSeleccionados.reduce((sum, producto) => sum + producto.subtotal, 0);
        const total = totalServicios + totalProductos;
        $('#totalOrden').text('$' + total.toFixed(2));
    }
});

// Función para eliminar servicio de la lista
function eliminarServicioSeleccionado(index) {
    if (!confirm('¿Está seguro de eliminar este servicio de la lista?')) {
        return;
    }
    
    serviciosSeleccionados.splice(index, 1);
    
    // Actualizar la lista visual
    const container = $('#serviciosSeleccionados');
    container.empty();
    
    if (serviciosSeleccionados.length === 0) {
        container.html('<p class="text-muted text-center">No hay servicios agregados</p>');
    } else {
        // Llamar a la función de actualización
        $(document).ready(function() {
            actualizarListaServicios();
            calcularTotal();
        });
    }
    
    if (serviciosSeleccionados.length === 0 && productosSeleccionados.length === 0) {
        $('#registrarOrdenBtn').prop('disabled', true);
    }
}

// Función para eliminar producto de la lista
function eliminarProductoSeleccionado(index) {
    if (!confirm('¿Está seguro de eliminar este producto de la lista?')) {
        return;
    }
    
    productosSeleccionados.splice(index, 1);
    
    // Actualizar la lista visual
    const container = $('#productosSeleccionados');
    container.empty();
    
    if (productosSeleccionados.length === 0) {
        container.html('<p class="text-muted text-center">No hay productos agregados</p>');
    } else {
        // Llamar a la función de actualización
        $(document).ready(function() {
            actualizarListaProductos();
            calcularTotal();
        });
    }
    
    if (serviciosSeleccionados.length === 0 && productosSeleccionados.length === 0) {
        $('#registrarOrdenBtn').prop('disabled', true);
    }
}

// Función para ver detalle de orden
function verDetalleOrden(ordenID) {
    $('#detalleOrdenID').text(ordenID);
    
    $.ajax({
        url: 'crud_ordenes.php?action=get_ordenes',
        method: 'GET',
        success: function(data) {
            try {
                const ordenes = JSON.parse(data);
                const orden = ordenes.find(o => o.OrdenID == ordenID);
                
                if (orden) {
                    $('#detalleCliente').text(orden.ClienteNombre);
                    $('#detalleVehiculo').text(`${orden.Marca} ${orden.Modelo} (${orden.Placa})`);
                    $('#detalleFecha').text(new Date(orden.FechaOrden).toLocaleDateString('es-ES'));
                    $('#detalleEstado').text(orden.Estado);
                }
            } catch (e) {
                console.error('Error al procesar detalle de orden:', e);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error al obtener detalle de orden:', error);
        }
    });
    
    $.ajax({
        url: 'crud_ordenes.php?action=get_detalle&ordenID=' + ordenID,
        method: 'GET',
        success: function(data) {
            try {
                const detalles = JSON.parse(data);
                const tbodyServicios = $('#detalleServiciosTable tbody');
                const tbodyProductos = $('#detalleProductosTable tbody');
                
                tbodyServicios.empty();
                tbodyProductos.empty();
                
                let total = 0;
                
                // Mostrar servicios
                if (detalles.servicios && detalles.servicios.length > 0) {
                    detalles.servicios.forEach(servicio => {
                        // Asegurar que el precio es un número
                        const precioUnitario = parseFloat(servicio.PrecioUnitario) || 0;
                        total += precioUnitario;
                        
                        tbodyServicios.append(`
                            <tr>
                                <td>${servicio.ServicioNombre}</td>
                                <td>$${precioUnitario.toFixed(2)}</td>
                            </tr>
                        `);
                    });
                }
                
                // Mostrar productos
                if (detalles.productos && detalles.productos.length > 0) {
                    detalles.productos.forEach(producto => {
                        // Asegurar que los valores son números
                        const precioUnitario = parseFloat(producto.PrecioUnitario) || 0;
                        const cantidad = parseInt(producto.Cantidad) || 0;
                        const subtotal = cantidad * precioUnitario;
                        total += subtotal;
                        
                        tbodyProductos.append(`
                            <tr>
                                <td>${producto.ProductoNombre}</td>
                                <td>${cantidad}</td>
                                <td>$${precioUnitario.toFixed(2)}</td>
                                <td>$${subtotal.toFixed(2)}</td>
                            </tr>
                        `);
                    });
                }
                
                // Asegurar que el total es un número antes de usar toFixed
                $('#detalleTotalOrden').text('$' + parseFloat(total).toFixed(2));
                $('#detalleOrdenModal').modal('show');
            } catch (e) {
                console.error('Error al procesar detalle:', e);
                alert('Error al mostrar el detalle de la orden');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error al obtener detalle:', error);
            alert('Error al cargar el detalle de la orden');
        }
    });
}

// Función para mostrar modal de cambio de estado
function mostrarCambioEstado(ordenID, estadoActual) {
    $('#estadoOrdenID').text(ordenID);
    $('#nuevoEstado').val(estadoActual);
    $('#cambiarEstadoModal').modal('show');
}

// Función para cambiar estado de la orden
$('#confirmarCambioEstadoBtn').click(function() {
    const ordenID = $('#estadoOrdenID').text();
    const nuevoEstado = $('#nuevoEstado').val();
    
    $.ajax({
        url: 'crud_ordenes.php?action=update_estado',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            ordenID: ordenID,
            estado: nuevoEstado
        }),
        success: function(response) {
            try {
                const res = JSON.parse(response);
                if (res.success) {
                    alert(res.success);
                    $('#cambiarEstadoModal').modal('hide');
                    $('#ordenesTable').DataTable().ajax.reload();
                } else {
                    alert(res.error || 'Error desconocido al actualizar estado');
                }
            } catch (e) {
                alert('Error al procesar la respuesta del servidor');
                console.error(e);
            }
        },
        error: function(xhr, status, error) {
            alert('Error al actualizar el estado: ' + error);
            console.error(xhr);
        }
    });
});

// Función para mostrar confirmación de eliminación
function mostrarConfirmacionEliminar(ordenID) {
    $('#confirmarEliminarBtn').data('id', ordenID);
    $('#confirmarEliminarModal').modal('show');
}

// Función para eliminar orden
$('#confirmarEliminarBtn').click(function() {
    const ordenID = $(this).data('id');
    
    $.ajax({
        url: 'crud_ordenes.php?action=delete_orden',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ ordenID: ordenID }),
        success: function(response) {
            try {
                const res = JSON.parse(response);
                if (res.success) {
                    alert(res.success);
                    $('#confirmarEliminarModal').modal('hide');
                    $('#ordenesTable').DataTable().ajax.reload();
                } else {
                    alert(res.error || 'Error desconocido al eliminar la orden');
                }
            } catch (e) {
                alert('Error al procesar la respuesta del servidor');
                console.error(e);
            }
        },
        error: function(xhr, status, error) {
            alert('Error al eliminar la orden: ' + error);
            console.error(xhr);
        }
    });
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
