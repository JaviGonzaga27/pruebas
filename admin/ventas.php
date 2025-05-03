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
<!-- En el head de ventas.php -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- Antes del cierre del body en ventas.php -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        .iva-descuento-section {
            background-color: #f1f8ff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
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
                  <p>Inicio</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="dashboard">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="../index.php">
                        <span class="sub-item">Gráficas</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="nav-item  ">
                <a data-bs-toggle="collapse" href="#base">
                  <i class="fas fa-dolly"></i>
                  <p>Inventario</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse " id="base">
                  <ul class="nav nav-collapse">
                    <li >
                      <a href="../inventario/proveedor.php">
                        <span class="sub-item">Proveedor</span>
                      </a>
                    </li>
                    <li >
                      <a href="../inventario/categoria.php">
                        <span class="sub-item">Catogorias</span>
                      </a>
                    </li>
                    <li  >
                      <a href="../inventario/productos.php">
                        <span class="sub-item">Productos</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="nav-item active submenu">
                <a data-bs-toggle="collapse" href="#sidebarLayouts">
                  <i class="fas fa-dollar-sign"></i>
                  <p>Admin</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse show" id="sidebarLayouts">
                  <ul class="nav nav-collapse">
                    <li >
                      <a href="compras.php">
                        <span class="sub-item">Compras</span>
                      </a>
                    </li>
                    <li class="active">
                      <a href="ventas.php">
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
                        <h4 class="card-title">Registro de Ventas</h4>
                    </div>
                    <div class="card-body">
                        <form id="ventaForm">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Cliente</label>
                                        <select class="form-control" id="cliente" required>
                                            <option value="">Seleccione un cliente</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Fecha de Venta</label>
                                        <input type="date" class="form-control" id="fechaVenta" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
    <div class="form-group">
        <label>Método de Pago</label>
        <select class="form-control" id="metodoPago" required>
            <option value="">Seleccione un método</option>
        </select>
    </div>
</div>
                            <!-- Sección de IVA y Descuento General -->
                            <div class="iva-descuento-section mb-4">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group form-check">
                                            <input type="checkbox" class="form-check-input" id="tieneIVA" checked>
                                            <label class="form-check-label" for="tieneIVA">¿Aplica IVA?</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Porcentaje de IVA</label>
                                            <input type="number" step="0.01" class="form-control" id="porcentajeIVA" value="15.00" min="0">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Descuento General (%)</label>
                                            <input type="number" class="form-control" id="descuentoGeneral" min="0" max="100" value="0">
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <h5>Agregar Productos</h5>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Producto</label>
                                        <select class="form-control" id="producto">
                                            <option value="">Seleccione un producto</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Cantidad</label>
                                        <input type="number" class="form-control" id="cantidad" min="1" value="1">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Precio Unitario</label>
                                        <input type="number" step="0.01" class="form-control" id="precioUnitario" readonly>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Descuento (%)</label>
                                        <input type="number" class="form-control" id="descuentoProducto" min="0" max="100" value="0">
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
                                    <h5 class="card-title">Productos Seleccionados</h5>
                                </div>
                                <div class="card-body">
                                    <div id="productosSeleccionados" class="mb-3">
                                        <p class="text-muted text-center">No hay productos agregados</p>
                                    </div>
                                    <div class="total-container">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h5>Subtotal: <span id="subtotalVenta">$0.00</span></h5>
                                                <h5>IVA (<span id="porcentajeIVALabel">15.00</span>%): <span id="ivaVenta">$0.00</span></h5>
                                                <h5>Total antes de descuento: <span id="totalAntesDescuento">$0.00</span></h5>
                                                <h5>Descuento general: <span id="descuentoGeneralVenta">0%</span></h5>
                                            </div>
                                            <div class="col-md-6">
                                                <h4 class="text-end">Total: <span id="totalVenta">$0.00</span></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            

                            <div class="d-flex justify-content-end">
                                <button type="button" id="registrarVentaBtn" class="btn btn-success btn-lg" disabled>
                                    <i class="fas fa-save"></i> Registrar Venta
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tabla de Ventas Registradas -->
                <div class="card mt-5">
                    <div class="card-header">
                        <h4 class="card-title">Historial de Ventas</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="ventasTable" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Fecha</th>
                                        <th>Cliente</th>
                                        <th>Subtotal</th>
                                        <th>IVA</th>
                                        <th>Total</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para Ver Detalle de Venta -->
            <div class="modal fade" id="detalleVentaModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Detalle de Venta #<span id="detalleVentaID"></span></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Aplica IVA:</strong> <span id="detalleTieneIVA"></span>
                                </div>
                                <div class="col-md-4">
                                    <strong>% IVA:</strong> <span id="detallePorcentajeIVA"></span>
                                </div>
                                <div class="col-md-4">
                                    <strong>Descuento general:</strong> <span id="detalleDescuentoGeneral"></span>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table id="detalleVentaTable" class="table">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Cantidad</th>
                                            <th>P. Unitario</th>
                                            <th>Desc. (%)</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                            
                            <div class="total-container mt-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5>Subtotal: <span id="detalleSubtotal">$0.00</span></h5>
                                        <h5>IVA: <span id="detalleIVA">$0.00</span></h5>
                                        <h5>Total antes de descuento: <span id="detalleTotalAntesDescuento">$0.00</span></h5>
                                    </div>
                                    <div class="col-md-6">
                                        <h4 class="text-end">Total: <span id="detalleTotal">$0.00</span></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
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
   <!-- Script para manejar la lógica de ventas -->
   <script>
    // En el script principal, agregar:
let usuarioID = <?php echo $_SESSION['usuario_id'] ?? 1; ?>;

        $(document).ready(function() {
            // Variables globales
            let productosSeleccionados = [];
            let tieneIVA = true;
            let porcentajeIVA = 15.00;
            let descuentoGeneral = 0;
            
            // Configuración de DataTables para la tabla de ventas
            let ventasTable = $('#ventasTable').DataTable({
                ajax: {
                    url: 'crud_ventas.php?action=get_ventas',
                    dataSrc: ''
                },
                columns: [
                    { data: 'VentaID' },
                    { 
                        data: 'FechaVenta',
                        render: function(data) {
                            return new Date(data).toLocaleDateString('es-ES');
                        }
                    },
                    { data: 'ClienteNombre' },
                    { 
                        data: 'Subtotal',
                        render: function(data) {
                            return '$' + parseFloat(data).toFixed(2);
                        }
                    },
                    { 
                        data: null,
                        render: function(data) {
                            const iva = data.TieneIVA ? (data.Subtotal * data.PorcentajeIVA / 100) : 0;
                            return '$' + iva.toFixed(2);
                        }
                    },
                    { 
                        data: 'TotalVenta',
                        render: function(data) {
                            return '$' + parseFloat(data).toFixed(2);
                        }
                    },
                    {
                        data: null,
                        render: function(data) {
                            return `
                                <button class="btn btn-sm btn-info" onclick="verDetalleVenta(${data.VentaID})">
                                    <i class="fas fa-eye"></i> Ver Detalle
                                </button>
                            `;
                        }
                    }
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                }
            });

            // Cargar clientes y productos al iniciar
            cargarClientes();
            cargarProductos();

            // Establecer fecha actual por defecto
            $('#fechaVenta').val(new Date().toISOString().split('T')[0]);

            // Evento cuando se selecciona un producto
            $('#producto').change(function() {
                const productoID = $(this).val();
                
                if (productoID) {
                    const producto = productos.find(p => p.ProductoID == productoID);
                    
                    if (producto) {
                        $('#precioUnitario').val(parseFloat(producto.PrecioVenta).toFixed(2));
                        $('#cantidad').val(1);
                        $('#cantidad').focus();
                        $('#cantidad').attr('max', producto.Stock);
                    } else {
                        $('#precioUnitario').val('');
                    }
                } else {
                    $('#precioUnitario').val('');
                }
            });

            // Manejar cambios en configuración de IVA y descuentos
            $('#tieneIVA').change(function() {
                tieneIVA = $(this).is(':checked');
                calcularTotales();
            });

            $('#porcentajeIVA').change(function() {
                porcentajeIVA = parseFloat($(this).val()) || 0;
                $('#porcentajeIVALabel').text(porcentajeIVA.toFixed(2));
                calcularTotales();
            });

            $('#descuentoGeneral').change(function() {
                descuentoGeneral = parseFloat($(this).val()) || 0;
                $('#descuentoGeneralVenta').text(descuentoGeneral + '%');
                calcularTotales();
            });

            // Agregar producto a la lista
            $('#agregarProductoBtn').click(function() {
                const productoID = $('#producto').val();
                const cantidad = parseInt($('#cantidad').val());
                const descuentoProducto = parseFloat($('#descuentoProducto').val()) || 0;
                
                if (!productoID || !cantidad || cantidad < 1) {
                    alert('Por favor complete todos los campos del producto');
                    return;
                }

                const producto = productos.find(p => p.ProductoID == productoID);
                
                // Validar stock disponible
                if (cantidad > producto.Stock) {
                    alert('No hay suficiente stock disponible');
                    return;
                }

                const precioUnitario = parseFloat($('#precioUnitario').val());
                
                // Verificar si el producto ya está en la lista
                const index = productosSeleccionados.findIndex(p => p.productoID == productoID);
                
                if (index >= 0) {
                    // Actualizar cantidad si ya existe
                    productosSeleccionados[index].cantidad += cantidad;
                } else {
                    // Agregar nuevo producto
                    productosSeleccionados.push({
                        productoID: productoID,
                        nombre: producto.Nombre,
                        cantidad: cantidad,
                        precio: precioUnitario,
                        descuentoProducto: descuentoProducto
                    });
                }

                actualizarListaProductos();
                calcularTotales();
                resetearCamposProducto();
            });

            // Registrar venta
            $('#registrarVentaBtn').click(function() {
    const clienteID = $('#cliente').val();
    const fecha = $('#fechaVenta').val();
    const metodoPagoID = $('#metodoPago').val();
    
    if (!clienteID || !fecha || !metodoPagoID || productosSeleccionados.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Por favor complete todos los campos requeridos'
        });
        return;
    }

    const { total } = calcularTotales();

    const ventaData = {
        clienteID: clienteID,
        fecha: fecha,
        metodoPagoID: metodoPagoID,
        tieneIVA: tieneIVA,
        porcentajeIVA: porcentajeIVA,
        descuentoGeneral: descuentoGeneral,
        usuarioID: usuarioID,
        productos: productosSeleccionados.map(p => ({
            productoID: p.productoID,
            cantidad: p.cantidad,
            precio: p.precio,
            descuentoProducto: p.descuentoProducto
        }))
    };

    $.ajax({
        url: 'crud_ventas.php?action=create_venta',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(ventaData),
        success: function(response) {
    const res = JSON.parse(response);
    if (res.success) {
        Swal.fire({
            icon: 'success',
            title: 'Venta registrada',
            text: `Venta registrada correctamente. ID: ${res.ventaID}`,
            showConfirmButton: false,
            timer: 2000
        }).then(() => {
            // Recargar la página después de que se cierre el alert
            location.reload();
        });
        resetearFormulario();
        ventasTable.ajax.reload();
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: res.error
        });
    }
},
        error: function(xhr, status, error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al registrar la venta: ' + error
            });
        }
    });
});

            // Función para cargar clientes
            function cargarClientes() {
                $.ajax({
                    url: 'crud_ventas.php?action=get_clientes',
                    method: 'GET',
                    success: function(data) {
                        try {
                            const clientes = JSON.parse(data);
                            const select = $('#cliente');
                            select.empty();
                            select.append('<option value="">Seleccione un cliente</option>');
                            
                            clientes.forEach(cliente => {
                                select.append(`<option value="${cliente.ClienteID}">${cliente.Nombre} (${cliente.Cedula})</option>`);
                            });
                        } catch (e) {
                            console.error("Error al cargar clientes:", e);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error al obtener clientes:", error);
                    }
                });
            }

            // Función para cargar productos
            function cargarProductos() {
                $.ajax({
                    url: 'crud_ventas.php?action=get_productos',
                    method: 'GET',
                    success: function(data) {
                        try {
                            productos = JSON.parse(data);
                            const select = $('#producto');
                            select.empty();
                            select.append('<option value="">Seleccione un producto</option>');
                            
                            productos.forEach(producto => {
                                select.append(`<option value="${producto.ProductoID}">${producto.Nombre} (Stock: ${producto.Stock})</option>`);
                            });
                        } catch (e) {
                            console.error("Error al cargar productos:", e);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error al obtener productos:", error);
                    }
                });
            }
// Agregar función para cargar métodos de pago
function cargarMetodosPago() {
    $.ajax({
        url: 'crud_ventas.php?action=get_metodos_pago',
        method: 'GET',
        success: function(data) {
            try {
                const metodos = JSON.parse(data);
                const select = $('#metodoPago');
                select.empty();
                select.append('<option value="">Seleccione un método</option>');
                
                metodos.forEach(metodo => {
                    select.append(`<option value="${metodo.MetodoPagoID}">${metodo.Nombre}</option>`);
                });
            } catch (e) {
                console.error("Error al cargar métodos de pago:", e);
            }
        },
        error: function(xhr, status, error) {
            console.error("Error al obtener métodos de pago:", error);
        }
    });
}

// Llamar a esta función al cargar la página
cargarMetodosPago();

            // Función para actualizar la lista visual de productos
            function actualizarListaProductos() {
                const container = $('#productosSeleccionados');
                container.empty();

                if (productosSeleccionados.length === 0) {
                    container.html('<p class="text-muted text-center">No hay productos agregados</p>');
                    $('#registrarVentaBtn').prop('disabled', true);
                    return;
                }

                const table = $('<table class="table table-sm"></table>');
                const thead = $(`
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>P. Unitario</th>
                            <th>Desc. (%)</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                `);
                const tbody = $('<tbody></tbody>');
                
                table.append(thead, tbody);
                container.append(table);

                productosSeleccionados.forEach((producto, index) => {
                    const subtotal = producto.cantidad * producto.precio * (1 - producto.descuentoProducto/100);
                    
                    const row = $(`
                        <tr>
                            <td>${producto.nombre}</td>
                            <td>${producto.cantidad}</td>
                            <td>$${producto.precio.toFixed(2)}</td>
                            <td>${producto.descuentoProducto}%</td>
                            <td>$${subtotal.toFixed(2)}</td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `);
                    
                    row.find('button').click(function() {
                        eliminarProductoSeleccionado(index);
                    });
                    
                    tbody.append(row);
                });

                $('#registrarVentaBtn').prop('disabled', false);
            }

            // Función para calcular totales
            function calcularTotales() {
                // 1. Calcular subtotal con descuentos por producto
                let subtotal = 0;
                
                productosSeleccionados.forEach(producto => {
                    const precioConDescuento = producto.precio * (1 - producto.descuentoProducto/100);
                    subtotal += producto.cantidad * precioConDescuento;
                });
                
                // 2. Calcular IVA si aplica
                let iva = 0;
                if (tieneIVA) {
                    iva = subtotal * (porcentajeIVA/100);
                }
                
                // 3. Calcular total con descuento general (después de IVA)
                const totalConIVA = subtotal + iva;
                const total = totalConIVA * (1 - descuentoGeneral/100);
                
                // Actualizar UI
                $('#subtotalVenta').text('$' + subtotal.toFixed(2));
                $('#ivaVenta').text('$' + iva.toFixed(2));
                $('#totalAntesDescuento').text('$' + totalConIVA.toFixed(2));
                $('#totalVenta').text('$' + total.toFixed(2));
                
                return {
                    subtotal: subtotal,
                    iva: iva,
                    total: total
                };
            }

            // Función para eliminar producto de la lista
            function eliminarProductoSeleccionado(index) {
                if (!confirm('¿Está seguro de eliminar este producto de la lista?')) {
                    return;
                }
                
                productosSeleccionados.splice(index, 1);
                actualizarListaProductos();
                calcularTotales();
            }

            // Función para resetear campos de producto
            function resetearCamposProducto() {
                $('#producto').val('').trigger('change');
                $('#cantidad').val(1);
                $('#descuentoProducto').val(0);
            }

            // Función para resetear todo el formulario
            function resetearFormulario() {
                productosSeleccionados = [];
                $('#productosSeleccionados').html('<p class="text-muted text-center">No hay productos agregados</p>');
                $('#ventaForm')[0].reset();
                $('#fechaVenta').val(new Date().toISOString().split('T')[0]);
                $('#tieneIVA').prop('checked', true);
                $('#porcentajeIVA').val('15.00');
                $('#descuentoGeneral').val('0');
                $('#porcentajeIVALabel').text('15.00');
                $('#descuentoGeneralVenta').text('0%');
                $('#registrarVentaBtn').prop('disabled', true);
                calcularTotales();
            }
        });

        // Función para ver detalle de venta (global para que DataTables pueda llamarla)
        function verDetalleVenta(ventaID) {
    $('#detalleVentaModal').modal('show');
    $('#detalleVentaID').text(ventaID);
    
    // Limpiar y mostrar loader
    const tbody = $('#detalleVentaTable tbody');
    tbody.html('<tr><td colspan="5" class="text-center"><div class="spinner-border text-primary"></div></td></tr>');
    
    $.ajax({
        url: 'crud_ventas.php?action=get_detalle&ventaID=' + ventaID,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log("Respuesta recibida:", response);
            
            if (!response || !response.success) {
                throw new Error(response?.error || "Respuesta inválida del servidor");
            }

            const { venta, detalles } = response;
            
            // Mostrar información general
            $('#detalleTieneIVA').text(venta.TieneIVA ? 'Sí' : 'No');
            $('#detallePorcentajeIVA').text(venta.PorcentajeIVA + '%');
            $('#detalleDescuentoGeneral').text(venta.DescuentoGeneral + '%');
            
            // Mostrar productos
            tbody.empty();
            
            if (!detalles || detalles.length === 0) {
                tbody.append('<tr><td colspan="5" class="text-center text-muted">No se encontraron productos</td></tr>');
            } else {
                detalles.forEach(detalle => {
                    const subtotal = detalle.Cantidad * detalle.PrecioUnitario * (1 - detalle.DescuentoProducto/100);
                    
                    tbody.append(`
                        <tr>
                            <td>${detalle.ProductoNombre || 'Producto no disponible'}</td>
                            <td>${detalle.Cantidad}</td>
                            <td>$${detalle.PrecioUnitario.toFixed(2)}</td>
                            <td>${detalle.DescuentoProducto}%</td>
                            <td>$${subtotal.toFixed(2)}</td>
                        </tr>
                    `);
                });
            }
            
            // Mostrar totales
            $('#detalleSubtotal').text('$' + venta.Subtotal.toFixed(2));
            $('#detalleIVA').text('$' + (venta.TieneIVA ? (venta.Subtotal * venta.PorcentajeIVA / 100).toFixed(2) : '0.00'));
            $('#detalleTotalAntesDescuento').text('$' + (venta.Subtotal + (venta.TieneIVA ? venta.Subtotal * venta.PorcentajeIVA / 100 : 0)).toFixed(2));
            $('#detalleTotal').text('$' + venta.TotalVenta.toFixed(2));
        },
        error: function(xhr, status, error) {
            console.error("Error en la petición:", status, error, xhr.responseText);
            tbody.html(`
                <tr>
                    <td colspan="5" class="text-center text-danger">
                        Error al cargar los detalles. Consulte la consola para más información.
                    </td>
                </tr>
            `);
        }
    });
}
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
