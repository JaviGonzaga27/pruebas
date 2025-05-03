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
<!-- Incluye SweetAlert2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<style>
  /* Agrega esto en tu archivo CSS o en una etiqueta <style> */
#productosTable .dropdown-toggle::after {
    display: none; /* Oculta la flecha del dropdown */
}

#productosTable .dropdown-menu {
    min-width: 150px;
}

#productosTable .dropdown-item {
    cursor: pointer;
    padding: 0.25rem 1rem;
}

#productosTable .dropdown-item i {
    width: 20px;
    text-align: center;
}
</style>
<!-- Verifica que tienes estos enlaces en tu head -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Incluye SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
              <li class="nav-item active submenu">
                <a data-bs-toggle="collapse" href="#base">
                  <i class="fas fa-dolly"></i>
                  <p>Inventario</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse show" id="base">
                  <ul class="nav nav-collapse">
                    <li >
                      <a href="proveedor.php">
                        <span class="sub-item">Proveedor</span>
                      </a>
                    </li>
                    <li>
                      <a href="categoria.php">
                        <span class="sub-item">Catogorias</span>
                      </a>
                    </li>
                    <li class="active" >
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
                      <a href="..admin/ventas.php">
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
                  <a href="#">Productos</a>
                </li>
              </ul>
            </div>
            <div class="row">

            <div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <h4 class="card-title">Productos</h4>
                <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#addProductoModal">
                    <i class="fa fa-plus"></i>
                    Agregar Producto
                </button>
            </div>
        </div>
        <div class="card-body">
            <!-- Modal para Agregar Producto -->
            <div class="modal fade" id="addProductoModal" tabindex="-1" aria-labelledby="addProductoModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addProductoModalLabel">Nuevo Producto</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="addProductoForm" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>ID del Producto</label>
                                            <input type="text" class="form-control" id="productoID" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Nombre</label>
                                            <input type="text" class="form-control" id="nombre" required>
                                        </div>
                                        <div class="form-group">
            <label>Marca</label>
            <input type="text" class="form-control" id="marca">
        </div>
                                        <div class="form-group">
                                            <label>Descripción</label>
                                            <textarea class="form-control" id="descripcion" rows="2"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Precio de Compra</label>
                                            <input type="number" step="0.01" class="form-control" id="precioCompra" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Precio de Venta</label>
                                            <input type="number" step="0.01" class="form-control" id="precioVenta" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Stock</label>
                                            <input type="number" class="form-control" id="stock" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Stock Mínimo</label>
                                            <input type="number" class="form-control" id="stockMinimo" value="5">
                                        </div>
                                        <div class="form-group">
                                            <label>Estado</label>
                                            <select class="form-control" id="estado" required>
                                                <option value="Activo">Activo</option>
                                                <option value="Inactivo">Inactivo</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Compatibilidad</label>
                                            <input type="text" class="form-control" id="compatibilidad">
                                        </div>
                                        <div class="form-group">
                                            <label>Fecha de Ingreso</label>
                                            <input type="date" class="form-control" id="fechaIngreso" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Categoría</label>
                                            <select class="form-control" id="categoriaID" required></select>
                                        </div>
                                        <div class="form-group">
                                            <label>Proveedor</label>
                                            <select class="form-control" id="ruc" required></select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Código de Barras</label>
                                            <input type="text" class="form-control" id="codigoBarras">
                                        </div>
                                        <div class="form-group">
                                            <label>Ubicación en Almacén</label>
                                            <input type="text" class="form-control" id="ubicacionAlmacen">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="checkbox" id="tieneGarantia">
                                                    <span class="form-check-sign">Tiene Garantía</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Días de Garantía</label>
                                            <input type="number" class="form-control" id="diasGarantia" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Imagen del Producto</label>
                                    <input type="file" class="form-control" id="imagen" accept="image/*">
                                </div>

                              <!-- Checkbox para líquido - Siempre desmarcado por defecto -->
<div class="row mt-3">
    <div class="col-md-12">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="esLiquido" name="esLiquido">
            <label class="form-check-label" for="esLiquido">¿Es un producto líquido (aceite)?</label>
        </div>
    </div>
</div>

<!-- Campos de líquido - Ocultos inicialmente -->
<div id="liquidFields" style="display: none;">
    <div class="row mt-3">
        <div class="col-md-4">
            <div class="form-group">
                <label>Tipo de Aceite</label>
                <input type="text" class="form-control" id="tipoAceite" name="tipoAceite">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Capacidad del Envase (litros)</label>
                <input type="number" step="0.001" class="form-control" id="capacidadEnvase" name="capacidadEnvase" value="0">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Contenido Actual (litros)</label>
                <input type="number" step="0.001" class="form-control" id="contenidoActual" name="contenidoActual" value="0">
            </div>
        </div>
    </div>
</div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="addProductoBtn" class="btn btn-primary">Guardar</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>

         <!-- Modal para Editar Producto -->
<div class="modal fade" id="editProductoModal" tabindex="-1" aria-labelledby="editProductoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editProductoModalLabel">Editar Producto</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editProductoForm" enctype="multipart/form-data">
                    <input type="hidden" id="editProductoID">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Nombre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editNombre" required>
                            </div>
                            <div class="form-group">
            <label class="form-label">Marca</label>
            <input type="text" class="form-control" id="editMarca">
        </div>
                            <div class="form-group">
                                <label class="form-label">Descripción</label>
                                <textarea class="form-control" id="editDescripcion" rows="2"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Precio Compra <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" step="0.01" class="form-control" id="editPrecioCompra" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Precio Venta <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" step="0.01" class="form-control" id="editPrecioVenta" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Stock <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="editStock" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Stock Mínimo</label>
                                        <input type="number" class="form-control" id="editStockMinimo" value="5">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Estado <span class="text-danger">*</span></label>
                                <select class="form-select" id="editEstado" required>
                                    <option value="Activo">Activo</option>
                                    <option value="Inactivo">Inactivo</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Fecha Ingreso <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="editFechaIngreso" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Categoría <span class="text-danger">*</span></label>
                                <select class="form-select" id="editCategoriaID" required></select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Proveedor <span class="text-danger">*</span></label>
                                <select class="form-select" id="editRuc" required></select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Compatibilidad</label>
                                <input type="text" class="form-control" id="editCompatibilidad">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Código de Barras</label>
                                <input type="text" class="form-control" id="editCodigoBarras">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Ubicación en Almacén</label>
                                <input type="text" class="form-control" id="editUbicacionAlmacen">
                            </div>
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="editTieneGarantia">
                                    <label class="form-check-label" for="editTieneGarantia">Tiene Garantía</label>
                                </div>
                                <div class="mt-2" id="editGarantiaGroup" style="display: none;">
                                    <label class="form-label">Días de Garantía</label>
                                    <input type="number" class="form-control" id="editDiasGarantia">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mt-3">
                        <label class="form-label">Imagen del Producto</label>
                        <input type="file" class="form-control" id="editImagen" accept="image/*">
                        <small class="text-muted">Formatos aceptados: JPG, PNG. Tamaño máximo: 2MB</small>
                        <div id="imagenPreview" class="mt-3 text-center"></div>
                    </div>


                    <div class="row mt-3">
    <div class="col-md-12">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="editEsLiquido">
            <label class="form-check-label" for="editEsLiquido">¿Es un producto líquido (aceite)?</label>
        </div>
    </div>
</div>

<div id="editLiquidFields" style="display: none;">
    <div class="row mt-3">
        <div class="col-md-4">
            <div class="form-group">
                <label>Tipo de Aceite</label>
                <input type="text" class="form-control" id="editTipoAceite">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Capacidad del Envase (litros)</label>
                <input type="number" step="0.01" class="form-control" id="editCapacidadEnvase">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Contenido Actual (litros)</label>
                <input type="number" step="0.01" class="form-control" id="editContenidoActual">
            </div>
        </div>
    </div>
</div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="button" id="editProductoBtn" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
            </div>
        </div>
    </div>
</div>


            <!-- Modal para Ver Detalles del Producto -->
            <div class="modal fade" id="viewProductoModal" tabindex="-1" aria-labelledby="viewProductoModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="viewProductoModalLabel">Detalles del Producto</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><strong>ID del Producto:</strong></label>
                                        <p id="viewProductoID"></p>
                                    </div>
                                    <div class="form-group">
                                        <label><strong>Nombre:</strong></label>
                                        <p id="viewNombre"></p>
                                    </div>
                                    <div class="form-group">
                                        <label><strong>Descripción:</strong></label>
                                        <p id="viewDescripcion"></p>
                                    </div>
                                    <div class="form-group">
                                        <label><strong>Precio de Compra:</strong></label>
                                        <p id="viewPrecioCompra"></p>
                                    </div>
                                    <div class="form-group">
                                        <label><strong>Precio de Venta:</strong></label>
                                        <p id="viewPrecioVenta"></p>
                                    </div>
                                    <div class="form-group">
                                        <label><strong>Stock:</strong></label>
                                        <p id="viewStock"></p>
                                    </div>
                                    <div class="form-group">
                                        <label><strong>Stock Mínimo:</strong></label>
                                        <p id="viewStockMinimo"></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><strong>Estado:</strong></label>
                                        <p id="viewEstado"></p>
                                    </div>
                                    <div class="form-group">
                                        <label><strong>Compatibilidad:</strong></label>
                                        <p id="viewCompatibilidad"></p>
                                    </div>
                                    <div class="form-group">
                                        <label><strong>Fecha de Ingreso:</strong></label>
                                        <p id="viewFechaIngreso"></p>
                                    </div>
                                    <div class="form-group">
                                        <label><strong>Categoría:</strong></label>
                                        <p id="viewCategoria"></p>
                                    </div>
                                    <div class="form-group">
                                        <label><strong>Proveedor:</strong></label>
                                        <p id="viewProveedor"></p>
                                    </div>
                                    <div class="form-group">
                                        <label><strong>Código de Barras:</strong></label>
                                        <p id="viewCodigoBarras"></p>
                                    </div>
                                    <div class="form-group">
                                        <label><strong>Ubicación en Almacén:</strong></label>
                                        <p id="viewUbicacionAlmacen"></p>
                                    </div>
                                    <div class="form-group">
                                        <label><strong>Garantía:</strong></label>
                                        <p id="viewGarantia"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <label><strong>Imagen del Producto:</strong></label>
                                    <div id="viewImagen" class="text-center">
                                        <img id="productoImagen" src="" class="img-fluid" style="max-height: 300px;">
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

            <!-- Tabla de Productos -->
            <div class="table-responsive">
                <table id="productosTable" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Precio Compra</th>
                            <th>Precio Venta</th>
                            <th>Ganancia</th>
                            <th>Stock</th>
                            <th>Estado</th>
                            <th>Categoría</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
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
<!-- jQuery primero -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Luego Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Luego SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Luego DataTables -->
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
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
        // Habilitar/deshabilitar días de garantía según checkbox
        $('#tieneGarantia').change(function() {
            $('#diasGarantia').prop('disabled', !$(this).is(':checked'));
        });

        $('#editTieneGarantia').change(function() {
            $('#editDiasGarantia').prop('disabled', !$(this).is(':checked'));
        });

       // Inicializar DataTable con configuración mejorada
const table = $('#productosTable').DataTable({
    ajax: {
        url: 'crud_productos.php?action=read',
        dataSrc: '',
        error: function(xhr, error, thrown) {
            console.error('Error al cargar datos:', xhr.responseText);
            // Mostrar fila de error en la tabla
            let mensaje = 'Error al cargar los datos. ';
            try {
                const response = JSON.parse(xhr.responseText);
                mensaje += response.error || '';
            } catch(e) {
                mensaje += xhr.responseText || error;
            }
            
            $('#productosTable tbody').html(`
                <tr class="odd">
                    <td valign="top" colspan="9" class="dataTables_empty text-center text-danger">
                        ${mensaje}<br>
                        <button onclick="location.reload()" class="btn btn-sm btn-primary mt-2">
                            <i class="fa fa-refresh"></i> Recargar
                        </button>
                    </td>
                </tr>
            `);
        }
    },
    columns: [
        { data: 'ProductoID' },
        { 
          data: 'Nombre',
    render: function(data, type, row) {
        // Resaltar si el stock está bajo mínimo
        const stockMinimo = row.StockMinimo || 5;
        let nombreHtml = data;
        
        if (row.Stock < stockMinimo) {
            nombreHtml = `<span class="text-danger fw-bold">${data}</span>`;
        }
        
        // Agregar marca si existe
        if (row.marca) {
            nombreHtml += `<br><small class="text-muted">${row.marca}</small>`;
        }
        
        return nombreHtml;
    }
},
        { 
            data: 'PrecioCompra',
            render: function(data) {
                return data ? '$' + parseFloat(data).toFixed(2) : '$0.00';
            },
            className: 'text-end'
        },
        { 
            data: 'PrecioVenta',
            render: function(data) {
                return data ? '$' + parseFloat(data).toFixed(2) : '$0.00';
            },
            className: 'text-end'
        },
        { 
            data: null,
            render: function(data) {
                const ganancia = parseFloat(data.PrecioVenta) - parseFloat(data.PrecioCompra);
                return '$' + ganancia.toFixed(2);
            },
            className: 'text-end'
        },
        {
          data: 'Stock',
    render: function(data, type, row) {
        const stockMinimo = row.StockMinimo || 5;
        
        if (row.es_liquido == 1) {
            // Mostrar información de líquido
            const badgeClass = row.contenido_actual < stockMinimo ? 'badge bg-danger' : 'badge bg-primary';
            return `
                <span class="${badgeClass}" title="Tipo: ${row.tipo_aceite || 'N/A'}, Capacidad: ${row.capacidad_envase || 0}L">
                    ${row.contenido_actual || 0}L / ${row.capacidad_envase || 0}L
                </span>
                ${row.marca ? '<br><small class="text-muted">' + row.marca + '</small>' : ''}
            `;
        } else {
            // Mostrar información normal de stock
            const badgeClass = data < stockMinimo ? 'badge bg-danger' : 'badge bg-success';
            return `<span class="${badgeClass}">${data} unidades</span>`;
        }
    },
    className: 'text-center'
},
        { 
            data: 'Estado',
            render: function(data) {
                const badgeClass = data === 'Activo' ? 'badge bg-success' : 'badge bg-warning text-dark';
                return `<span class="${badgeClass}">${data}</span>`;
            },
            className: 'text-center'
        },
        { 
            data: 'CategoriaNombre',
            className: 'text-center'
        },
        {
          data: null,
    render: function(data, type, row) {
        return `
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" 
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <button class="dropdown-item" onclick="verProducto('${row.ProductoID}')">
                            <i class="fas fa-eye me-2"></i> Ver
                        </button>
                    </li>
                    <li>
                        <button class="dropdown-item" onclick="editarProducto('${row.ProductoID}')">
                            <i class="fas fa-edit me-2"></i> Editar
                        </button>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <button class="dropdown-item text-danger" onclick="eliminarProducto('${row.ProductoID}')">
                            <i class="fas fa-trash me-2"></i> Eliminar
                        </button>
                    </li>
                </ul>
            </div>
        `;
    },
    orderable: false,
    className: 'text-center'
}
    ],
    order: [[1, 'asc']], // Ordenar por nombre ascendente por defecto
    language: {
        url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
    },
    responsive: true,
    // Opciones adicionales recomendadas:
    processing: true,
    serverSide: false, // Cambiar a true si tienes muchos registros
    stateSave: true, // Recordar estado (página, orden, etc.)
    autoWidth: false,
    pageLength: 10,
    lengthMenu: [5, 10, 25, 50, 100],
    dom: '<"top"lf>rt<"bottom"ip><"clear">'
});

        // Cargar categorías y proveedores al abrir el modal de agregar
        $('#addProductoModal').on('show.bs.modal', function() {
          $('#esLiquido').prop('checked', false).trigger('change');
    $('#liquidFields').hide();
    $('#stock').prop('disabled', false).val('');
            cargarSelectCategorias('#categoriaID');
            cargarSelectProveedores('#ruc');
            $('#fechaIngreso').val(new Date().toISOString().split('T')[0]);
        });

        // Cargar categorías y proveedores al abrir el modal de editar
        $('#editProductoModal').on('show.bs.modal', function() {
            cargarSelectCategorias('#editCategoriaID');
            cargarSelectProveedores('#editRuc');
        });

        // Manejar cambio del checkbox
$('#esLiquido').change(function() {
    if ($(this).is(':checked')) {
        $('#liquidFields').show();
        $('#stock').val('0').prop('disabled', true);
    } else {
        $('#liquidFields').hide();
        $('#stock').prop('disabled', false);
        // Limpiar campos de líquido
        $('#tipoAceite').val('');
        $('#capacidadEnvase').val('0');
        $('#contenidoActual').val('0');
    }
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

        // Agregar producto con FormData para manejar la imagen
        // Modificación para el botón de guardar (agregar producto)
        $('#addProductoBtn').click(function() {
          const esLiquido = $('#esLiquido').is(':checked') ? 1 : 0;
    // Validar campos requeridos
    if (!$('#productoID').val() || !$('#nombre').val() || !$('#precioCompra').val() || 
        !$('#precioVenta').val() || !$('#stock').val() || !$('#categoriaID').val() || 
        !$('#ruc').val()) {
        showNotification('top', 'right', 'danger', 'Por favor complete todos los campos requeridos');
        return;
    }

    // Crear FormData correctamente
    const formData = new FormData();
    formData.append('action', 'create');
    formData.append('productoID', $('#productoID').val());
    formData.append('nombre', $('#nombre').val());
    formData.append('descripcion', $('#descripcion').val());
    formData.append('precioCompra', $('#precioCompra').val());
    formData.append('precioVenta', $('#precioVenta').val());
    formData.append('stock', $('#stock').val());
    formData.append('stockMinimo', $('#stockMinimo').val() || 5);
    formData.append('estado', $('#estado').val() || 'Activo');
    formData.append('compatibilidad', $('#compatibilidad').val() || '');
    formData.append('fechaIngreso', $('#fechaIngreso').val() || new Date().toISOString().split('T')[0]);
    formData.append('categoriaID', $('#categoriaID').val());
    formData.append('ruc', $('#ruc').val());
    formData.append('tieneGarantia', $('#tieneGarantia').is(':checked') ? 1 : 0);
    formData.append('diasGarantia', $('#diasGarantia').val() || null);
    formData.append('codigoBarras', $('#codigoBarras').val() || '');
    formData.append('ubicacionAlmacen', $('#ubicacionAlmacen').val() || '');
    formData.append('marca', $('#marca').val() || '');
    formData.append('esLiquido', esLiquido);
    
    // Agregar imagen si existe
    const imagenInput = $('#imagen')[0];
    if (imagenInput.files.length > 0) {
        formData.append('imagen', imagenInput.files[0]);
    }
    if ($('#esLiquido').is(':checked')) {
        formData.append('esLiquido', '1');
        formData.append('tipoAceite', $('#tipoAceite').val() || '');
        formData.append('capacidadEnvase', $('#capacidadEnvase').val() || '0');
        formData.append('contenidoActual', $('#contenidoActual').val() || '0');
    } else {
        formData.append('esLiquido', '0');
    }
     // Solo agregar campos de líquido si está marcado
     if(esLiquido) {
        formData.append('tipoAceite', $('#tipoAceite').val());
        formData.append('capacidadEnvase', $('#capacidadEnvase').val());
        formData.append('contenidoActual', $('#contenidoActual').val());
    } else {
        // Forzar valores vacíos/cero si no es líquido
        formData.append('tipoAceite', '');
        formData.append('capacidadEnvase', '0');
        formData.append('contenidoActual', '0');
    }
    // Mostrar loading
    const $btn = $(this);
    const originalHtml = $btn.html();
    $btn.html('<i class="fa fa-spinner fa-spin"></i> Guardando...');
    $btn.prop('disabled', true);

    $.ajax({
        url: 'crud_productos.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            // Restaurar botón
            $btn.html(originalHtml);
            $btn.prop('disabled', false);
            
            if (response.success) {
                // Cerrar el modal correctamente
                $('#addProductoModal').modal('hide');
                
                // Limpiar el formulario
                $('#addProductoForm')[0].reset();
                
                // Recargar la tabla
                table.ajax.reload(null, false);
                
                // Mostrar notificación
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: response.success,
                    timer: 2000,
                    showConfirmButton: false,
                    didClose: () => {
                        // Eliminar manualmente el backdrop si persiste
                        $('.modal-backdrop').remove();
                        $('body').removeClass('modal-open');
                    }
                });
            } else {
                showNotification('error', response.error || 'Error desconocido al guardar');
            }
        },
        error: function(xhr, status, error) {
            // Restaurar botón
            $btn.html(originalHtml);
            $btn.prop('disabled', false);
            
            // Mostrar error
            let errorMsg = 'Error al guardar el producto: ';
            try {
                const res = JSON.parse(xhr.responseText);
                errorMsg += res.error || error;
            } catch (e) {
                errorMsg += xhr.responseText || error;
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorMsg
            });
        }
    });
});

// Mostrar/ocultar campos de líquido al cambiar el checkbox
$('#esLiquido').change(function() {
    if ($(this).is(':checked')) {
        $('#liquidFields').show();
        $('#stock').val('0').prop('disabled', true);
    } else {
        $('#liquidFields').hide();
        $('#stock').prop('disabled', false);
        // Limpiar los campos de líquido
        $('#tipoAceite').val('');
        $('#capacidadEnvase').val('0');
        $('#contenidoActual').val('0');
    }
});

$('#editEsLiquido').change(function() {
    if ($(this).is(':checked')) {
        $('#editLiquidFields').show();
        $('#editStock').val('0').prop('disabled', true);
    } else {
        $('#editLiquidFields').hide();
        $('#editStock').prop('disabled', false);
        // Limpiar los campos de líquido
        $('#editTipoAceite').val('');
        $('#editCapacidadEnvase').val('0');
        $('#editContenidoActual').val('0');
    }
});
window.verProducto = function(productoID) {
    // Limpiar el modal y mostrar spinner
    $('#viewProductoModal .modal-body').html(`
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2">Cargando información del producto...</p>
        </div>
    `);
    
    $('#viewProductoModal').modal('show');

    $.ajax({
        url: 'crud_productos.php?action=get_producto&id=' + encodeURIComponent(productoID),
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            try {
                // Verificar si la respuesta es válida
                if (!response || typeof response !== 'object') {
                    throw new Error('Respuesta del servidor no válida');
                }
                
                // Verificar si hay error
                if (response.error) {
                    throw new Error(response.error);
                }

                // Plantilla base para el contenido del modal
                let contenido = `
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>ID del Producto:</strong></label>
                                <p>${response.ProductoID || 'N/A'}</p>
                            </div>
                            <div class="form-group">
                                <label><strong>Nombre:</strong></label>
                                <p>${response.Nombre || 'N/A'}</p>
                            </div>
                            <div class="form-group">
                                <label><strong>Marca:</strong></label>
                                <p>${response.marca || 'N/A'}</p>
                            </div>
                            <div class="form-group">
                                <label><strong>Descripción:</strong></label>
                                <p>${response.Descripcion || 'N/A'}</p>
                            </div>`;

                // Agregar información específica para líquidos
                if (response.es_liquido == 1) {
                    contenido += `
                            <div class="form-group">
                                <label><strong>Tipo de Aceite:</strong></label>
                                <p>${response.tipo_aceite || 'N/A'}</p>
                            </div>
                            <div class="form-group">
                                <label><strong>Capacidad del Envase:</strong></label>
                                <p>${response.capacidad_envase || '0'} litros</p>
                            </div>
                            <div class="form-group">
                                <label><strong>Contenido Actual:</strong></label>
                                <p>${response.contenido_actual || '0'} litros</p>
                            </div>`;
                }

                // Continuar con el resto de campos comunes
                contenido += `
                            <div class="form-group">
                                <label><strong>Precio de Compra:</strong></label>
                                <p>$${response.PrecioCompra ? parseFloat(response.PrecioCompra).toFixed(2) : '0.00'}</p>
                            </div>
                            <div class="form-group">
                                <label><strong>Precio de Venta:</strong></label>
                                <p>$${response.PrecioVenta ? parseFloat(response.PrecioVenta).toFixed(2) : '0.00'}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Stock:</strong></label>
                                <p>${response.es_liquido == 1 ? (response.contenido_actual || '0') + ' litros' : (response.Stock || '0') + ' unidades'}</p>
                            </div>
                            <div class="form-group">
                                <label><strong>Categoría:</strong></label>
                                <p>${response.CategoriaNombre || 'N/A'}</p>
                            </div>
                            <div class="form-group">
                                <label><strong>Proveedor:</strong></label>
                                <p>${response.ProveedorNombre || 'N/A'}</p>
                            </div>
                            <div class="form-group">
                                <label><strong>Código de Barras:</strong></label>
                                <p>${response.CodigoBarras || 'N/A'}</p>
                            </div>
                            <div class="form-group">
                                <label><strong>Ubicación en Almacén:</strong></label>
                                <p>${response.UbicacionAlmacen || 'N/A'}</p>
                            </div>
                            <div class="form-group">
                                <label><strong>Garantía:</strong></label>
                                <p>${response.TieneGarantia ? 'Sí (' + (response.DiasGarantia || '0') + ' días)' : 'No'}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label><strong>Imagen del Producto:</strong></label>
                            <div id="viewImagen" class="text-center">
                                ${response.TieneImagen && response.ImagenBase64 ? 
                                    `<img src="data:image/jpeg;base64,${response.ImagenBase64}" class="img-fluid" style="max-height: 300px;">` : 
                                    '<div class="alert alert-info">No hay imagen disponible</div>'}
                            </div>
                        </div>
                    </div>`;

                // Insertar todo el contenido en el modal de una vez
                $('#viewProductoModal .modal-body').html(contenido);
                
            } catch (e) {
                console.error('Error al procesar respuesta:', e);
                $('#viewProductoModal .modal-body').html(`
                    <div class="alert alert-danger">
                        Error al mostrar el producto: ${e.message}
                        <button onclick="verProducto('${productoID}')" class="btn btn-sm btn-primary mt-2">
                            <i class="fa fa-refresh"></i> Intentar nuevamente
                        </button>
                    </div>
                `);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error en la solicitud:', error, 'Respuesta:', xhr.responseText);
            let errorMsg = 'Error al cargar el producto: ';
            
            try {
                const res = JSON.parse(xhr.responseText);
                errorMsg += res.error || error;
            } catch(e) {
                errorMsg += xhr.responseText || error;
            }
            
            $('#viewProductoModal .modal-body').html(`
                <div class="alert alert-danger">
                    ${errorMsg}
                    <button onclick="verProducto('${productoID}')" class="btn btn-sm btn-primary mt-2">
                        <i class="fa fa-refresh"></i> Intentar nuevamente
                    </button>
                </div>
            `);
        }
    });
};


// Función para editar producto
window.editarProducto = function(productoID) {
    console.log('Iniciando edición para producto ID:', productoID);
    
    // Mostrar spinner
    $('#editProductoModal .modal-body').prepend(
        '<div class="overlay d-flex justify-content-center align-items-center" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(255,255,255,0.7); z-index: 1000;">' +
        '<div class="spinner-border text-primary" role="status">' +
        '<span class="visually-hidden">Cargando...</span>' +
        '</div></div>'
    );

    $.ajax({
        url: 'crud_productos.php?action=get_producto&id=' + productoID,
        method: 'GET',
        dataType: 'json',
        success: function(producto) {
            console.log('Producto recibido:', producto);
            
            if (producto.error) {
                $('#editProductoModal .overlay').remove();
                showNotification('error', producto.error);
                return;
            }

            // Llenar campos del formulario
            $('#editProductoID').val(producto.ProductoID);
            $('#editNombre').val(producto.Nombre);
            $('#editDescripcion').val(producto.Descripcion || '');
            $('#editPrecioCompra').val(parseFloat(producto.PrecioCompra).toFixed(2));
            $('#editPrecioVenta').val(parseFloat(producto.PrecioVenta).toFixed(2));
            $('#editStock').val(producto.Stock);
            $('#editStockMinimo').val(producto.StockMinimo || 5);
            $('#editEstado').val(producto.Estado);
            $('#editCompatibilidad').val(producto.Compatibilidad || '');
            $('#editFechaIngreso').val(producto.FechaIngreso);
            $('#editCodigoBarras').val(producto.CodigoBarras || '');
            $('#editUbicacionAlmacen').val(producto.UbicacionAlmacen || '');
            $('#editMarca').val(producto.marca || '');
            
            // Manejar garantía
            if (producto.TieneGarantia) {
                $('#editTieneGarantia').prop('checked', true).trigger('change');
                $('#editDiasGarantia').val(producto.DiasGarantia || '');
            } else {
                $('#editTieneGarantia').prop('checked', false).trigger('change');
            }

            // Mostrar imagen actual
            if (producto.ImagenBase64) {
                $('#imagenPreview').html(
                    '<img src="data:image/jpeg;base64,' + producto.ImagenBase64 + 
                    '" class="img-thumbnail" style="max-height: 200px;">' +
                    '<p class="text-muted mt-2">Imagen actual. Suba una nueva para reemplazar.</p>'
                );
            } else {
                $('#imagenPreview').html(
                    '<div class="alert alert-info">No hay imagen actual para este producto</div>'
                );
            }

            // Manejar estado de líquido
            if (producto.es_liquido == 1) {
                $('#editEsLiquido').prop('checked', true).trigger('change');
                $('#editTipoAceite').val(producto.tipo_aceite || '');
                $('#editCapacidadEnvase').val(producto.capacidad_envase || '0');
                $('#editContenidoActual').val(producto.contenido_actual || '0');
                $('#editStock').val('0').prop('disabled', true);
            } else {
                $('#editEsLiquido').prop('checked', false).trigger('change');
                $('#editStock').val(producto.Stock).prop('disabled', false);
                $('#editTipoAceite').val('');
                $('#editCapacidadEnvase').val('0');
                $('#editContenidoActual').val('0');
            }

            // Cargar selects con valores actuales
            cargarSelectCategorias('#editCategoriaID', producto.CategoriaID);
            cargarSelectProveedores('#editRuc', producto.RUC);
            
            // Ocultar spinner y mostrar modal
            $('#editProductoModal .overlay').remove();
            $('#editProductoModal').modal('show');
        },
        error: function(xhr, status, error) {
            console.error('Error en la solicitud:', error, 'Respuesta:', xhr.responseText);
            $('#editProductoModal .overlay').remove();
            
            let errorMsg = 'Error al obtener producto: ';
            try {
                const res = JSON.parse(xhr.responseText);
                errorMsg += res.error || error;
            } catch (e) {
                errorMsg += xhr.responseText || error;
            }
            
            showNotification('error', errorMsg);
        }
    });
};

// Función modificada para cargar categorías con selección
function cargarSelectCategorias(selector, selectedId = null) {
    $.ajax({
        url: 'crud_productos.php?action=get_categorias',
        method: 'GET',
        success: function(data) {
            const categorias = JSON.parse(data);
            const select = $(selector);
            select.empty();
            categorias.forEach(categoria => {
                const option = new Option(categoria.Nombre, categoria.CategoriaID);
                if (categoria.CategoriaID == selectedId) {
                    option.selected = true;
                }
                select.append(option);
            });
        }
    });
}

// Función modificada para cargar proveedores con selección
function cargarSelectProveedores(selector, selectedRuc = null) {
    $.ajax({
        url: 'crud_productos.php?action=get_proveedores',
        method: 'GET',
        success: function(data) {
            const proveedores = JSON.parse(data);
            const select = $(selector);
            select.empty();
            proveedores.forEach(proveedor => {
                const option = new Option(`${proveedor.Nombre} (${proveedor.RUC})`, proveedor.RUC);
                if (proveedor.RUC == selectedRuc) {
                    option.selected = true;
                }
                select.append(option);
            });
        }
    });
}

// Manejar el envío del formulario de edición
$('#editProductoBtn').click(function() {
    // Validar campos requeridos
    const requiredFields = [
        '#editNombre', 
        '#editPrecioCompra', 
        '#editPrecioVenta', 
        '#editStock', 
        '#editCategoriaID', 
        '#editRuc'
    ];
    
    let isValid = true;
    requiredFields.forEach(field => {
        if (!$(field).val()) {
            $(field).addClass('is-invalid');
            isValid = false;
        } else {
            $(field).removeClass('is-invalid');
        }
    });

    if (!isValid) {
        showNotification('error', 'Por favor complete todos los campos requeridos');
        return;
    }

    // Preparar FormData
    const formData = new FormData();
    formData.append('action', 'update');
    formData.append('productoID', $('#editProductoID').val());
    formData.append('nombre', $('#editNombre').val());
    formData.append('descripcion', $('#editDescripcion').val());
    formData.append('precioCompra', $('#editPrecioCompra').val());
    formData.append('precioVenta', $('#editPrecioVenta').val());
    formData.append('stock', $('#editStock').val());
    formData.append('stockMinimo', $('#editStockMinimo').val() || 5);
    formData.append('estado', $('#editEstado').val());
    formData.append('compatibilidad', $('#editCompatibilidad').val() || '');
    formData.append('fechaIngreso', $('#editFechaIngreso').val());
    formData.append('categoriaID', $('#editCategoriaID').val());
    formData.append('ruc', $('#editRuc').val());
    formData.append('tieneGarantia', $('#editTieneGarantia').is(':checked') ? 1 : 0);
    formData.append('diasGarantia', $('#editDiasGarantia').val() || null);
    formData.append('codigoBarras', $('#editCodigoBarras').val() || '');
    formData.append('ubicacionAlmacen', $('#editUbicacionAlmacen').val() || '');
    formData.append('marca', $('#editMarca').val() || '');
    
    // Asegúrate de capturar correctamente el estado del checkbox
    formData.append('esLiquido', $('#editEsLiquido').is(':checked') ? '1' : '0');
    
    // Solo agregar campos de líquido si está marcado
    if($('#editEsLiquido').is(':checked')) {
        formData.append('tipoAceite', $('#editTipoAceite').val());
        formData.append('capacidadEnvase', $('#editCapacidadEnvase').val());
        formData.append('contenidoActual', $('#editContenidoActual').val());
    } else {
        // Forzar valores vacíos/cero si no es líquido
        formData.append('tipoAceite', '');
        formData.append('capacidadEnvase', '0');
        formData.append('contenidoActual', '0');
    }

    // Agregar imagen si se seleccionó
    const imagenInput = $('#editImagen')[0];
    if (imagenInput.files.length > 0) {
        formData.append('imagen', imagenInput.files[0]);
    }

    // Deshabilitar botón y mostrar spinner
    const $btn = $(this);
    const originalHtml = $btn.html();
    $btn.html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
    $btn.prop('disabled', true);

    // Enviar datos al servidor
    $.ajax({
        url: 'crud_productos.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            console.log('Respuesta del servidor:', response);
            
            // Restaurar botón
            $btn.html(originalHtml);
            $btn.prop('disabled', false);

            if (response && response.success) {
                $('#productosTable').DataTable().ajax.reload(null, false);
                $('#editProductoModal').modal('hide');
                showNotification('success', response.success);
            } else {
                const errorMsg = response?.error || 'Error desconocido al actualizar el producto';
                showNotification('error', errorMsg);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error en la solicitud:', error, 'Respuesta:', xhr.responseText);
            
            // Restaurar botón
            $btn.html(originalHtml);
            $btn.prop('disabled', false);
            
            let errorMsg = 'Error al actualizar el producto: ';
            if (xhr.responseText) {
                try {
                    const res = JSON.parse(xhr.responseText);
                    errorMsg += res.error || error;
                } catch (e) {
                    errorMsg += xhr.responseText;
                }
            } else {
                errorMsg += error;
            }
            
            showNotification('error', errorMsg);
        }
    });
});

// Mostrar/ocultar días de garantía según checkbox
$('#editTieneGarantia').change(function() {
    if ($(this).is(':checked')) {
        $('#editGarantiaGroup').show();
        $('#editDiasGarantia').prop('disabled', false);
    } else {
        $('#editGarantiaGroup').hide();
        $('#editDiasGarantia').prop('disabled', true).val('');
    }
});

// Eliminar producto
window.eliminarProducto = function(productoID) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡No podrás revertir esta acción!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'crud_productos.php?action=delete',
                method: 'POST',
                data: { productoID: productoID },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#productosTable').DataTable().ajax.reload(null, false);
                        showNotification('success', response.success);
                    } else {
                        showNotification('error', response.error || 'Error al eliminar el producto');
                    }
                },
                error: function(xhr, status, error) {
                    let errorMsg = 'Error al eliminar producto: ';
                    try {
                        const res = JSON.parse(xhr.responseText);
                        errorMsg += res.error || error;
                    } catch (e) {
                        errorMsg += xhr.responseText || error;
                    }
                    showNotification('error', errorMsg);
                }
            });
        }
    });
};

function showNotification(type, message) {
    // Cerrar notificaciones previas si existen
    Swal.close();
    
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        },
        didClose: () => {
            // Asegurarse de que no quedan elementos bloqueantes
            $('.modal-backdrop').not('.show').remove();
            $('body').removeClass('modal-open');
        }
    });
    
    Toast.fire({
        icon: type,
        title: message
    });
}
    });


// Limpiar el modal cuando se cierre
$('#addProductoModal').on('hidden.bs.modal', function () {
    $(this).find('form').trigger('reset');
    $('.modal-backdrop').remove();
    $('body').removeClass('modal-open');
});

// Forzar eliminación del backdrop si persiste
$(document).on('click', function() {
    if($('body').hasClass('modal-open') && $('.modal.show').length === 0) {
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
    }
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
