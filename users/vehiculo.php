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
    <style>
        .form-button-action {
            display: flex;
            gap: 10px;
        }
        .modal-body {
            max-height: 70vh;
            overflow-y: auto;
        }
    </style>

    <!-- Incluye SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
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
              <li class="nav-item ">
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
                    <li>
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
                      <a href="../admin/ventas.php">
                        <span class="sub-item">Ventas</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="nav-item active submenu">
                <a data-bs-toggle="collapse" href="#forms">
                  <i class="fas fa-user-plus"></i>
                  <p>Clientes</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse show" id="forms">
                  <ul class="nav nav-collapse">
                    <li >
                      <a href="clientes.php">
                        <span class="sub-item">Clientes</span>
                      </a>
                    </li>
                    <li class="active">
                      <a href="vehiculo.php">
                        <span class="sub-item">Vehiculo</span>
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
             

            <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <div class="d-flex align-items-center">
                      <h4 class="card-title">Vehículos</h4>
                      <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#addVehiculoModal">
                        <i class="fa fa-plus"></i>
                        Agregar Vehículo
                      </button>
                    </div>
                  </div>
                  <div class="card-body">
                    <!-- Modal para Agregar Vehículo -->
                    <div class="modal fade" id="addVehiculoModal" tabindex="-1" aria-labelledby="addVehiculoModalLabel" aria-hidden="true">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                          <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="addVehiculoModalLabel">Nuevo Vehículo</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <form id="addVehiculoForm">
                              <div class="row">
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label class="form-label">Cliente <span class="text-danger">*</span></label>
                                    <select class="form-control" id="clienteID" required></select>
                                  </div>
                                  <div class="form-group">
                                    <label class="form-label">Marca <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="marca" required>
                                  </div>
                                  <div class="form-group">
                                    <label class="form-label">Modelo <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="modelo" required>
                                  </div>
                                  <div class="form-group">
                                    <label class="form-label">Año <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="anio" required min="1900" max="2099">
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label class="form-label">Placa <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="placa" required>
                                  </div>
                                  <div class="form-group">
                                    <label class="form-label">VIN (Número de chasis)</label>
                                    <input type="text" class="form-control" id="vin">
                                  </div>
                                  <div class="form-group">
                                    <label class="form-label">Kilometraje</label>
                                    <input type="number" class="form-control" id="kilometraje" min="0">
                                  </div>
                                  <div class="form-group">
                                    <label class="form-label">Color</label>
                                    <input type="text" class="form-control" id="color">
                                  </div>
                                </div>
                              </div>
                              <div class="row mt-3">
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label class="form-label">Último Servicio</label>
                                    <input type="date" class="form-control" id="ultimoServicio">
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label class="form-label">Próximo Servicio</label>
                                    <input type="date" class="form-control" id="proximoServicio">
                                  </div>
                                </div>
                              </div>
                              <div class="row mt-3">
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label class="form-label">Tipo</label>
                                    <select class="form-control" id="tipo">
                                      <option value="Automovil">Automóvil</option>
                                      <option value="Camioneta">Camioneta</option>
                                      <option value="Motocicleta">Motocicleta</option>
                                      <option value="Camion">Camión</option>
                                      <option value="Otro">Otro</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label class="form-label">Combustible</label>
                                    <select class="form-control" id="combustible">
                                      <option value="Gasolina">Gasolina</option>
                                      <option value="Diesel">Diesel</option>
                                      <option value="Electrico">Eléctrico</option>
                                      <option value="Hibrido">Híbrido</option>
                                      <option value="Gas">Gas</option>
                                      <option value="Otro">Otro</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label class="form-label">Transmisión</label>
                                    <select class="form-control" id="transmision">
                                      <option value="">Seleccione...</option>
                                      <option value="Automatica">Automática</option>
                                      <option value="Manual">Manual</option>
                                      <option value="CVT">CVT</option>
                                      <option value="Otro">Otro</option>
                                    </select>
                                  </div>
                                </div>
                              </div>
                              <div class="form-group mt-3">
                                <label class="form-label">Observaciones</label>
                                <textarea class="form-control" id="observaciones" rows="3"></textarea>
                              </div>
                            </form>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                              <i class="fas fa-times"></i> Cancelar
                            </button>
                            <button type="button" id="addVehiculoBtn" class="btn btn-primary">
                              <i class="fas fa-save"></i> Guardar
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Modal para Editar Vehículo -->
                    <div class="modal fade" id="editVehiculoModal" tabindex="-1" aria-labelledby="editVehiculoModalLabel" aria-hidden="true">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                          <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="editVehiculoModalLabel">Editar Vehículo</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <form id="editVehiculoForm">
                              <input type="hidden" id="editVehiculoID">
                              <div class="row">
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label class="form-label">Cliente <span class="text-danger">*</span></label>
                                    <select class="form-control" id="editClienteID" required></select>
                                  </div>
                                  <div class="form-group">
                                    <label class="form-label">Marca <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="editMarca" required>
                                  </div>
                                  <div class="form-group">
                                    <label class="form-label">Modelo <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="editModelo" required>
                                  </div>
                                  <div class="form-group">
                                    <label class="form-label">Año <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="editAnio" required min="1900" max="2099">
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label class="form-label">Placa <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="editPlaca" required>
                                  </div>
                                  <div class="form-group">
                                    <label class="form-label">VIN (Número de chasis)</label>
                                    <input type="text" class="form-control" id="editVin">
                                  </div>
                                  <div class="form-group">
                                    <label class="form-label">Kilometraje</label>
                                    <input type="number" class="form-control" id="editKilometraje" min="0">
                                  </div>
                                  <div class="form-group">
                                    <label class="form-label">Color</label>
                                    <input type="text" class="form-control" id="editColor">
                                  </div>
                                </div>
                              </div>
                              <div class="row mt-3">
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label class="form-label">Último Servicio</label>
                                    <input type="date" class="form-control" id="editUltimoServicio">
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label class="form-label">Próximo Servicio</label>
                                    <input type="date" class="form-control" id="editProximoServicio">
                                  </div>
                                </div>
                              </div>
                              <div class="row mt-3">
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label class="form-label">Tipo</label>
                                    <select class="form-control" id="editTipo">
                                      <option value="Automovil">Automóvil</option>
                                      <option value="Camioneta">Camioneta</option>
                                      <option value="Motocicleta">Motocicleta</option>
                                      <option value="Camion">Camión</option>
                                      <option value="Otro">Otro</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label class="form-label">Combustible</label>
                                    <select class="form-control" id="editCombustible">
                                      <option value="Gasolina">Gasolina</option>
                                      <option value="Diesel">Diesel</option>
                                      <option value="Electrico">Eléctrico</option>
                                      <option value="Hibrido">Híbrido</option>
                                      <option value="Gas">Gas</option>
                                      <option value="Otro">Otro</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label class="form-label">Transmisión</label>
                                    <select class="form-control" id="editTransmision">
                                      <option value="">Seleccione...</option>
                                      <option value="Automatica">Automática</option>
                                      <option value="Manual">Manual</option>
                                      <option value="CVT">CVT</option>
                                      <option value="Otro">Otro</option>
                                    </select>
                                  </div>
                                </div>
                              </div>
                              <div class="form-group mt-3">
                                <label class="form-label">Observaciones</label>
                                <textarea class="form-control" id="editObservaciones" rows="3"></textarea>
                              </div>
                            </form>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                              <i class="fas fa-times"></i> Cancelar
                            </button>
                            <button type="button" id="editVehiculoBtn" class="btn btn-primary">
                              <i class="fas fa-save"></i> Guardar Cambios
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Modal para Ver Vehículo -->
                    <div class="modal fade" id="viewVehiculoModal" tabindex="-1" aria-labelledby="viewVehiculoModalLabel" aria-hidden="true">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                          <div class="modal-header bg-info text-white">
                            <h5 class="modal-title" id="viewVehiculoModalLabel">Detalles del Vehículo</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label><strong>ID:</strong></label>
                                  <p id="viewVehiculoID"></p>
                                </div>
                                <div class="form-group">
                                  <label><strong>Cliente:</strong></label>
                                  <p id="viewCliente"></p>
                                </div>
                                <div class="form-group">
                                  <label><strong>Marca:</strong></label>
                                  <p id="viewMarca"></p>
                                </div>
                                <div class="form-group">
                                  <label><strong>Modelo:</strong></label>
                                  <p id="viewModelo"></p>
                                </div>
                                <div class="form-group">
                                  <label><strong>Año:</strong></label>
                                  <p id="viewAnio"></p>
                                </div>
                                <div class="form-group">
                                  <label><strong>Placa:</strong></label>
                                  <p id="viewPlaca"></p>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label><strong>VIN:</strong></label>
                                  <p id="viewVin"></p>
                                </div>
                                <div class="form-group">
                                  <label><strong>Kilometraje:</strong></label>
                                  <p id="viewKilometraje"></p>
                                </div>
                                <div class="form-group">
                                  <label><strong>Color:</strong></label>
                                  <p id="viewColor"></p>
                                </div>
                                <div class="form-group">
                                  <label><strong>Tipo:</strong></label>
                                  <p id="viewTipo"></p>
                                </div>
                                <div class="form-group">
                                  <label><strong>Combustible:</strong></label>
                                  <p id="viewCombustible"></p>
                                </div>
                                <div class="form-group">
                                  <label><strong>Transmisión:</strong></label>
                                  <p id="viewTransmision"></p>
                                </div>
                              </div>
                            </div>
                            <div class="row mt-3">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label><strong>Último Servicio:</strong></label>
                                  <p id="viewUltimoServicio"></p>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label><strong>Próximo Servicio:</strong></label>
                                  <p id="viewProximoServicio"></p>
                                </div>
                              </div>
                            </div>
                            <div class="row mt-3">
                              <div class="col-md-12">
                                <label><strong>Observaciones:</strong></label>
                                <p id="viewObservaciones" class="p-2 bg-light rounded"></p>
                              </div>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                              <i class="fas fa-times"></i> Cerrar
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Tabla de Vehículos -->
                    <div class="table-responsive">
                      <table id="vehiculosTable" class="table table-striped table-hover">
                        <thead>
                          <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Año</th>
                            <th>Placa</th>
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

    <!-- jQuery Scrollbar -->
    <script src="../assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <!-- Datatables -->
    <script src="../assets/js/plugin/datatables/datatables.min.js"></script>
    <!-- Kaiadmin JS -->
    <script src="../assets/js/kaiadmin.min.js"></script>
    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
    <script src="../assets/js/setting-demo2.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Script para manejar la tabla dinámica -->
    <!-- Script para manejar la tabla dinámica -->
    <script>
      // Función para mostrar notificaciones
      function showNotification(type, message) {
        const Toast = Swal.mixin({
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 3000,
          timerProgressBar: true,
          didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
          }
        });
        
        Toast.fire({
          icon: type,
          title: message
        });
      }

      $(document).ready(function () {
        // Inicializar DataTable
        const table = $('#vehiculosTable').DataTable({
          ajax: {
            url: 'crud_vehiculos.php?action=read',
            dataSrc: '',
            error: function(xhr, error, thrown) {
              console.error('Error al cargar datos:', xhr.responseText);
              let mensaje = 'Error al cargar los datos. ';
              try {
                const response = JSON.parse(xhr.responseText);
                mensaje += response.error || '';
              } catch(e) {
                mensaje += xhr.responseText || error;
              }
              
              $('#vehiculosTable tbody').html(`
                <tr class="odd">
                  <td valign="top" colspan="7" class="dataTables_empty text-center text-danger">
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
            { data: 'VehiculoID' },
            { 
              data: 'ClienteNombre',
              render: function(data, type, row) {
                return `${data} (${row.ClienteCedula})`;
              }
            },
            { data: 'Marca' },
            { data: 'Modelo' },
            { data: 'Anio' },
            { data: 'Placa' },
            {
              data: null,
              render: function(data, type, row) {
                return `
                  <div class="form-button-action">
                    <button type="button" class="btn btn-link btn-info btn-lg" 
                      onclick="verVehiculo(${row.VehiculoID})" title="Ver detalles">
                      <i class="fa fa-eye"></i>
                    </button>
                    <button type="button" class="btn btn-link btn-primary btn-lg" 
                      onclick="editarVehiculo(${row.VehiculoID})" title="Editar">
                      <i class="fa fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-link btn-danger" 
                      onclick="eliminarVehiculo(${row.VehiculoID})" title="Eliminar">
                      <i class="fa fa-trash"></i>
                    </button>
                  </div>
                `;
              },
              orderable: false,
              className: 'text-center'
            }
          ],
          language: {
            url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
          },
          responsive: true,
          pageLength: 10,
          lengthMenu: [5, 10, 25, 50, 100]
        });

        // En la función cargarClientes
        function cargarClientes(selector, selectedClienteID = null) {
          $.ajax({
            url: 'crud_vehiculos.php?action=get_clientes',
            method: 'GET',
            success: function(response) {
              try {
                const clientes = typeof response === 'string' ? JSON.parse(response) : response;
                const select = $(selector);
                select.empty();
                select.append('<option value="">Seleccione un cliente</option>');
                
                clientes.forEach(cliente => {
                  const optionText = `${cliente.Nombre} (${cliente.Cedula})`;
                  const option = new Option(optionText, cliente.ClienteID);
                  if (cliente.ClienteID == selectedClienteID) {
                    option.selected = true;
                  }
                  select.append(option);
                });
                
                // Forzar actualización del select si es un plugin (como select2)
                if (select.hasClass('select2-hidden-accessible')) {
                  select.trigger('change');
                }
              } catch (e) {
                console.error('Error al parsear clientes:', e);
                showNotification('error', 'Error al cargar la lista de clientes');
              }
            },
            error: function(xhr) {
              console.error('Error en AJAX:', xhr.responseText);
              showNotification('error', 'Error al obtener clientes del servidor');
            }
          });
        }

        // Cargar clientes al abrir modal de agregar
        $('#addVehiculoModal').on('show.bs.modal', function () {
          cargarClientes('#clienteID');
        });

        // Agregar vehículo
        $('#addVehiculoBtn').click(function () {
          const $btn = $(this);
          const originalHtml = $btn.html();
          $btn.html('<i class="fa fa-spinner fa-spin"></i> Guardando...');
          $btn.prop('disabled', true);

          // Validar campos requeridos
          const required = {
            '#clienteID': 'Cliente',
            '#marca': 'Marca',
            '#modelo': 'Modelo',
            '#anio': 'Año',
            '#placa': 'Placa'
          };

          let isValid = true;
          for (const [selector, field] of Object.entries(required)) {
            if (!$(selector).val()) {
              $(selector).addClass('is-invalid');
              isValid = false;
            } else {
              $(selector).removeClass('is-invalid');
            }
          }

          if (!isValid) {
            showNotification('error', 'Por favor complete todos los campos requeridos');
            $btn.html(originalHtml);
            $btn.prop('disabled', false);
            return;
          }

          const formData = {
            clienteID: $('#clienteID').val(),
            marca: $('#marca').val(),
            modelo: $('#modelo').val(),
            anio: $('#anio').val(),
            placa: $('#placa').val(),
            vin: $('#vin').val() || null,
            kilometraje: $('#kilometraje').val() || 0,
            ultimoServicio: $('#ultimoServicio').val() || null,
            proximoServicio: $('#proximoServicio').val() || null,
            color: $('#color').val() || null,
            tipo: $('#tipo').val() || 'Automovil',
            combustible: $('#combustible').val() || 'Gasolina',
            transmision: $('#transmision').val() || null,
            observaciones: $('#observaciones').val() || null
          };

          $.ajax({
            url: 'crud_vehiculos.php?action=create',
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {
              $btn.html(originalHtml);
              $btn.prop('disabled', false);
              
              if (response.success) {
                $('#addVehiculoModal').modal('hide');
                $('#addVehiculoForm')[0].reset();
                table.ajax.reload(null, false);
                showNotification('success', response.success);
              } else if (response.error) {
                showNotification('error', response.error);
              }
            },
            error: function(xhr) {
              $btn.html(originalHtml);
              $btn.prop('disabled', false);
              
              let errorMsg = 'Error al guardar vehículo: ';
              try {
                const res = JSON.parse(xhr.responseText);
                errorMsg += res.error || xhr.statusText;
              } catch (e) {
                errorMsg += xhr.statusText;
              }
              showNotification('error', errorMsg);
            }
          });
        });

        // Ver detalles del vehículo
        window.verVehiculo = function(vehiculoID) {
          // Mostrar el modal inmediatamente con spinner de carga
          $('#viewVehiculoModal').modal('show');
          $('#viewVehiculoModal .modal-body').html(`
            <div class="text-center py-4">
              <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
              </div>
              <p class="mt-2">Cargando información del vehículo...</p>
            </div>
          `);

          // Hacer la petición AJAX
          $.ajax({
            url: 'crud_vehiculos.php?action=read_one&vehiculoID=' + vehiculoID,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
              // Verificar si hay error en la respuesta
              if (response.error) {
                throw new Error(response.error);
              }

              // Verificar que la respuesta tenga datos
              if (!response || Object.keys(response).length === 0) {
                throw new Error('No se recibieron datos del vehículo');
              }

              // Función para formatear fechas
              function formatDate(dateString) {
                if (!dateString) return 'N/A';
                const date = new Date(dateString);
                return date.toLocaleDateString('es-ES');
              }

              // Construir el contenido del modal
              const modalContent = `
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label><strong>ID:</strong></label>
                      <p>${response.VehiculoID || 'N/A'}</p>
                    </div>
                    <div class="form-group">
                      <label><strong>Cliente:</strong></label>
                      <p>${response.ClienteNombre ? `${response.ClienteNombre} (${response.ClienteCedula || 'Sin cédula'})` : 'N/A'}</p>
                    </div>
                    <div class="form-group">
                      <label><strong>Marca:</strong></label>
                      <p>${response.Marca || 'N/A'}</p>
                    </div>
                    <div class="form-group">
                      <label><strong>Modelo:</strong></label>
                      <p>${response.Modelo || 'N/A'}</p>
                    </div>
                    <div class="form-group">
                      <label><strong>Año:</strong></label>
                      <p>${response.Anio || 'N/A'}</p>
                    </div>
                    <div class="form-group">
                      <label><strong>Placa:</strong></label>
                      <p>${response.Placa || 'N/A'}</p>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label><strong>VIN:</strong></label>
                      <p>${response.VIN || 'N/A'}</p>
                    </div>
                    <div class="form-group">
                      <label><strong>Kilometraje:</strong></label>
                      <p>${response.Kilometraje ? response.Kilometraje + ' km' : 'N/A'}</p>
                    </div>
                    <div class="form-group">
                      <label><strong>Color:</strong></label>
                      <p>${response.Color || 'N/A'}</p>
                    </div>
                    <div class="form-group">
                      <label><strong>Tipo:</strong></label>
                      <p>${response.Tipo || 'N/A'}</p>
                    </div>
                    <div class="form-group">
                      <label><strong>Combustible:</strong></label>
                      <p>${response.Combustible || 'N/A'}</p>
                    </div>
                    <div class="form-group">
                      <label><strong>Transmisión:</strong></label>
                      <p>${response.Transmision || 'N/A'}</p>
                    </div>
                  </div>
                </div>
                <div class="row mt-3">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label><strong>Último Servicio:</strong></label>
                      <p>${formatDate(response.UltimoServicio)}</p>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label><strong>Próximo Servicio:</strong></label>
                      <p>${formatDate(response.ProximoServicio)}</p>
                    </div>
                  </div>
                </div>
                <div class="row mt-3">
                  <div class="col-md-12">
                    <label><strong>Observaciones:</strong></label>
                    <p class="p-2 bg-light rounded">${response.Observaciones || 'Ninguna'}</p>
                  </div>
                </div>
              `;

              // Actualizar el contenido del modal
              $('#viewVehiculoModal .modal-body').html(modalContent);
            },
            error: function(xhr, status, error) {
              console.error('Error en la petición:', xhr.responseText);
              
              let errorMsg = 'Error al cargar los detalles del vehículo: ';
              try {
                const res = JSON.parse(xhr.responseText);
                errorMsg += res.error || xhr.statusText;
              } catch(e) {
                errorMsg += xhr.statusText || error;
              }
              
              $('#viewVehiculoModal .modal-body').html(`
                <div class="alert alert-danger">
                  ${errorMsg}
                  <button onclick="verVehiculo(${vehiculoID})" class="btn btn-sm btn-primary mt-2">
                    <i class="fa fa-refresh"></i> Intentar nuevamente
                  </button>
                </div>
              `);
            }
          });
        };

        window.editarVehiculo = function(vehiculoID) {
          $('#editVehiculoModal').modal('show');
          $('#editVehiculoModal .modal-body').prepend(`
            <div class="overlay d-flex justify-content-center align-items-center" 
                 style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(255,255,255,0.7); z-index: 1000;">
              <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
              </div>
            </div>
          `);

          $.ajax({
            url: 'crud_vehiculos.php?action=read_one&vehiculoID=' + vehiculoID,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
              if (response.error) {
                $('#editVehiculoModal .overlay').remove();
                showNotification('error', response.error);
                return;
              }

              // Llenar el formulario de edición
              $('#editVehiculoID').val(response.VehiculoID);
              $('#editMarca').val(response.Marca);
              $('#editModelo').val(response.Modelo);
              $('#editAnio').val(response.Anio);
              $('#editPlaca').val(response.Placa);
              $('#editVin').val(response.VIN || '');
              $('#editKilometraje').val(response.Kilometraje || 0);
              $('#editUltimoServicio').val(response.UltimoServicio || '');
              $('#editProximoServicio').val(response.ProximoServicio || '');
              $('#editColor').val(response.Color || '');
              $('#editTipo').val(response.Tipo || 'Automovil');
              $('#editCombustible').val(response.Combustible || 'Gasolina');
              $('#editTransmision').val(response.Transmision || '');
              $('#editObservaciones').val(response.Observaciones || '');

              // Cargar clientes y seleccionar el correcto
              cargarClientes('#editClienteID', response.ClienteID);
              
              // Ocultar spinner
              $('#editVehiculoModal .overlay').remove();
            },
            error: function(xhr) {
              $('#editVehiculoModal .overlay').remove();
              let errorMsg = 'Error al cargar vehículo: ';
              try {
                const res = JSON.parse(xhr.responseText);
                errorMsg += res.error || xhr.statusText;
              } catch (e) {
                errorMsg += xhr.statusText;
              }
              showNotification('error', errorMsg);
            }
          });
        };

        // Actualizar esta parte del código para el botón de edición
        $('#editVehiculoBtn').click(function() {
          const $btn = $(this);
          const originalHtml = $btn.html();
          $btn.html('<i class="fa fa-spinner fa-spin"></i> Guardando...');
          $btn.prop('disabled', true);

          // Validar campos requeridos - CORREGIDO
          const required = {
            '#editClienteID': 'Cliente',
            '#editMarca': 'Marca',
            '#editModelo': 'Modelo',
            '#editAnio': 'Año',
            '#editPlaca': 'Placa'
          };

          let isValid = true;
          for (const [selector, field] of Object.entries(required)) {
            if (!$(selector).val()) {
              $(selector).addClass('is-invalid');
              isValid = false;
            } else {
              $(selector).removeClass('is-invalid');
            }
          }

          if (!isValid) {
            showNotification('error', 'Por favor complete todos los campos requeridos');
            $btn.html(originalHtml);
            $btn.prop('disabled', false);
            return;
          }

          const formData = {
            vehiculoID: $('#editVehiculoID').val(),
            clienteID: $('#editClienteID').val(),
            marca: $('#editMarca').val(),
            modelo: $('#editModelo').val(),
            anio: $('#editAnio').val(),
            placa: $('#editPlaca').val(),
            vin: $('#editVin').val() || null,
            kilometraje: $('#editKilometraje').val() || 0,
            ultimoServicio: $('#editUltimoServicio').val() || null,
            proximoServicio: $('#editProximoServicio').val() || null,
            color: $('#editColor').val() || null,
            tipo: $('#editTipo').val() || 'Automovil',
            combustible: $('#editCombustible').val() || 'Gasolina',
            transmision: $('#editTransmision').val() || null,
            observaciones: $('#editObservaciones').val() || null
          };

          $.ajax({
            url: 'crud_vehiculos.php?action=update',
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
              $btn.html(originalHtml);
              $btn.prop('disabled', false);
              
              if (response.success) {
                $('#editVehiculoModal').modal('hide');
                $('#vehiculosTable').DataTable().ajax.reload(null, false);
                showNotification('success', response.success);
              } else if (response.error) {
                showNotification('error', response.error);
              }
            },
            error: function(xhr) {
              $btn.html(originalHtml);
              $btn.prop('disabled', false);
              
              let errorMsg = 'Error al actualizar vehículo: ';
              try {
                const res = JSON.parse(xhr.responseText);
                errorMsg += res.error || xhr.statusText;
              } catch (e) {
                errorMsg += xhr.statusText;
              }
              showNotification('error', errorMsg);
            }
          });
        });

        // Eliminar vehículo
        window.eliminarVehiculo = function(vehiculoID) {
          Swal.fire({
            title: '¿Estás seguro?',
            text: "¡Esta acción no se puede deshacer!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            allowOutsideClick: false,
            showLoaderOnConfirm: true,
            preConfirm: () => {
              return new Promise((resolve, reject) => {
                $.ajax({
                  url: 'crud_vehiculos.php?action=delete',
                  method: 'POST',
                  data: { vehiculoID: vehiculoID },
                  dataType: 'json',
                  success: function(response) {
                    resolve(response);
                  },
                  error: function(xhr) {
                    let errorMsg = 'Error en la conexión: ';
                    try {
                      const res = JSON.parse(xhr.responseText);
                      errorMsg += res.error || xhr.statusText;
                    } catch (e) {
                      errorMsg += xhr.statusText;
                    }
                    reject(errorMsg);
                  }
                });
              });
            }
          }).then((result) => {
            if (result.isConfirmed) {
              if (result.value && result.value.success) {
                // Recargar la tabla correctamente
                $('#vehiculosTable').DataTable().ajax.reload(null, false);
                showNotification('success', result.value.success);
              } else if (result.value && result.value.error) {
                showNotification('error', result.value.error);
              } else {
                showNotification('error', 'Respuesta inesperada del servidor');
              }
            }
          }).catch((error) => {
            showNotification('error', error);
          });
        };

        // Limpiar el modal cuando se cierre
        $('#addVehiculoModal').on('hidden.bs.modal', function () {
          $(this).find('form').trigger('reset');
          $('.modal-backdrop').remove();
          $('body').removeClass('modal-open');
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
