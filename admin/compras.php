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
        .producto-item {
            border-bottom: 1px solid #eee;
            padding: 10px 0;
        }
        .producto-item:last-child {
            border-bottom: none;
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

        /* Nuevos estilos para plazos */
        .plazo-item {
            background-color: #f8f9fa;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .plazo-header {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .plazo-details {
            display: flex;
            justify-content: space-between;
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
                    <li class="active">
                      <a href="compras.php">
                        <span class="sub-item">Compras</span>
                      </a>
                    </li>
                    <li>
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
                    <h4 class="card-title">Registro de Compras</h4>
                </div>
                <div class="card-body">
                    <form id="compraForm">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Proveedor</label>
                                    <select class="form-control" id="proveedor" required>
                                        <option value="">Seleccione un proveedor</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha de Compra</label>
                                    <input type="date" class="form-control" id="fechaCompra" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Número de Factura</label>
                                    <input type="text" class="form-control" id="numeroFactura" placeholder="Opcional">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Usuario</label>
                                    <input type="text" class="form-control" id="usuario" value="<?php echo $_SESSION['usuario_nombre'] ?? 'Administrador'; ?>" readonly>
                                </div>
                            </div>
                        </div>


                        <h5>Agregar Productos</h5>
                        <div class="row mb-3">
                            <div class="col-md-5">
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
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Precio Unitario</label>
                                    <input type="number" step="0.01" class="form-control" id="precioUnitario">
                                </div>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" id="agregarProductoBtn" class="btn btn-primary w-100">
                                    <i class="fas fa-plus"></i> Agregar
                                </button>
                            </div>
                        </div>
                        
                        <!-- Campos adicionales para productos líquidos -->
                        <div class="campo-liquido" id="campoLiquido">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Capacidad por envase (L)</label>
                                        <span class="form-control" id="capacidadEnvase">0.00</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Cantidad en litros</label>
                                        <span class="form-control" id="cantidadLitros">0.00</span>
                                    </div>
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
                                <div class="total-container">
    <h4 class="text-end">Total Compra (IVA incluido): <span id="totalCompra">$0.00</span></h4>
</div>
                            </div>
                        </div>
                        <!-- Después de los datos básicos de compra -->
                        <div class="form-group form-check mb-3">
        <input type="checkbox" class="form-check-input" id="esPlazo">
        <label class="form-check-label" for="esPlazo">¿Es compra a plazos con el proveedor?</label>
    </div>

    <div class="card mb-4" id="seccionPlazos" style="display: none;">
        <div class="card-header">
            <h5 class="card-title">Condiciones de Pago a Plazos</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Fecha Primer Pago</label>
                        <input type="date" class="form-control" id="fechaPrimerPago">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Monto Primer Pago</label>
                        <input type="number" step="0.01" class="form-control" id="montoPrimerPago" value="0">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>¿Dará abono inicial?</label>
                        <select class="form-control" id="abonoInicial">
                            <option value="0">No</option>
                            <option value="1">Sí</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div id="seccionAbonoInicial" style="display: none;">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Monto Abono Inicial</label>
                            <input type="number" step="0.01" class="form-control" id="montoAbonoInicial" value="0">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Fecha Abono Inicial</label>
                            <input type="date" class="form-control" id="fechaAbonoInicial">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Plazos Adicionales</label>
                        <div id="plazosContainer"></div>
                        <button type="button" id="agregarPlazoBtn" class="btn btn-sm btn-primary mt-2">
                            <i class="fas fa-plus"></i> Agregar Plazo
                        </button>
                    </div>
                </div>
            </div>
    </div>
</div>

 <!-- Resumen de plazos -->
 <div class="mt-4">
                <h6>Resumen de Plazos</h6>
                <div id="resumenPlazos"></div>
            </div>
        </div>
    </div>

                        <div class="d-flex justify-content-end">
                            <button type="button" id="registrarCompraBtn" class="btn btn-success btn-lg" disabled>
                                <i class="fas fa-save"></i> Registrar Compra
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabla de Compras Registradas -->
            <div class="card mt-5">
                <div class="card-header">
                    <h4 class="card-title">Historial de Compras</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="comprasTable" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Fecha</th>
                                    <th>Proveedor</th>
                                    <th>Factura</th>
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
            
            <!-- Modal para Ver Detalle de Compra -->
            <div class="modal fade" id="detalleCompraModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Detalle de Compra #<span id="detalleCompraID"></span></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Proveedor:</strong> <span id="detalleProveedor"></span>
                                </div>
                                <div class="col-md-4">
                                    <strong>Factura:</strong> <span id="detalleFactura"></span>
                                </div>
                                <div class="col-md-4">
                                    <strong>Fecha:</strong> <span id="detalleFecha"></span>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Aplica IVA:</strong> <span id="detalleTieneIVA"></span>
                                </div>
                                <div class="col-md-4">
                                    <strong>% IVA:</strong> <span id="detallePorcentajeIVA"></span>
                                </div>
                                <div class="col-md-4">
                                    <strong>Usuario:</strong> <span id="detalleUsuario"></span>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table id="detalleCompraTable" class="table">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Cantidad</th>
                                            <th>P. Unitario</th>
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
                                    </div>
                                    <div class="col-md-6">
                                        <h4 class="text-end">Total: <span id="detalleTotal">$0.00</span></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" onclick="imprimirCompra()">
                                <i class="fas fa-print"></i> Imprimir
                            </button>
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

     <!-- SweetAlert2 JS -->
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Script principal para compras -->
<script>
    $(document).ready(function() {
        // Variables globales
        let productosSeleccionados = [];
        let productos = [];
        let proveedores = [];
        let plazosAdicionales = [];
        let usuarioID = <?php echo $_SESSION['usuario_id'] ?? 1; ?>;
        let comprasTable;
        
        // Inicializar DataTable
        function initDataTable() {
    comprasTable = $('#comprasTable').DataTable({
        ajax: {
            url: 'crud_compras.php?action=get_compras',
            dataSrc: '',
            error: function(xhr, error, thrown) {
                console.error('Error al cargar compras:', error);
                $('#comprasTable tbody').html(`
                    <tr>
                        <td colspan="8" class="text-center text-danger">
                            Error al cargar los datos. Intente recargar la página.
                        </td>
                    </tr>
                `);
            }
        },
        columns: [
                    { data: 'CompraID' },
                    { 
                        data: 'FechaCompra',
                        render: function(data) {
                            return new Date(data).toLocaleDateString('es-ES');
                        }
                    },
                    { 
                        data: 'ProveedorNombre',
                        render: function(data, type, row) {
                            return `${data} <small class="text-muted">${row.RUC}</small>`;
                        }
                    },
                    { data: 'NumeroFactura' },
                    { 
                        data: 'TotalCompra',
                        render: function(data) {
                            return `$${parseFloat(data).toFixed(2)}`;
                        }
                    },
                    { 
                        data: 'estado_pago',
                        render: function(data) {
                            let badgeClass = 'badge-secondary';
                            if(data === 'completo') badgeClass = 'badge-success';
                            if(data === 'parcial') badgeClass = 'badge-warning';
                            return `<span class="badge ${badgeClass}">${data}</span>`;
                        }
                    },
                    {
                        data: null,
                        render: function(data) {
                            return `
                                <button class="btn btn-sm btn-info" onclick="verDetalleCompra(${data.CompraID})">
                                    <i class="fas fa-eye"></i> Detalle
                                </button>
                                ${data.es_plazo ? `
                                <button class="btn btn-sm btn-warning" onclick="verPlazosCompra(${data.CompraID})">
                                    <i class="fas fa-calendar-alt"></i> Plazos
                                </button>` : ''}
                            `;
                        }
                    }
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                
              },
              order: [[1, 'desc']] // Ordenar por fecha más reciente primero
            });
        }

        // Mostrar/ocultar sección de plazos
        $('#esPlazo').change(function() {
            if($(this).is(':checked')) {
                $('#seccionPlazos').show();
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                $('#fechaPrimerPago').val(tomorrow.toISOString().split('T')[0]);
                $('#fechaAbonoInicial').val(new Date().toISOString().split('T')[0]);
                
                // Actualizar monto primer pago con el total actual
                const total = calcularTotal();
                $('#montoPrimerPago').val(total.toFixed(2));
            } else {
                $('#seccionPlazos').hide();
            }
        });

        // Mostrar/ocultar abono inicial
        $('#abonoInicial').change(function() {
            if($(this).val() === '1') {
                $('#seccionAbonoInicial').show();
            } else {
                $('#seccionAbonoInicial').hide();
            }
        });

        // Agregar nuevo plazo
        $('#agregarPlazoBtn').click(function() {
            const plazoId = Date.now();
            
            const plazoHtml = `
                <div class="plazo-item" id="plazo-${plazoId}">
                    <div class="plazo-header">Plazo Adicional</div>
                    <div class="plazo-details">
                        <div>
                            <input type="date" class="form-control form-control-sm mb-2" id="plazoFecha-${plazoId}" placeholder="Fecha">
                        </div>
                        <div>
                            <input type="number" step="0.01" class="form-control form-control-sm mb-2" id="plazoMonto-${plazoId}" placeholder="Monto">
                        </div>
                        <div>
                            <button class="btn btn-sm btn-danger" onclick="eliminarPlazo(${plazoId})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            $('#plazosContainer').append(plazoHtml);
            plazosAdicionales.push({ id: plazoId });
            actualizarResumenPlazos();
        });

        // Función para eliminar un plazo
        window.eliminarPlazo = function(plazoId) {
            $(`#plazo-${plazoId}`).remove();
            plazosAdicionales = plazosAdicionales.filter(p => p.id !== plazoId);
            actualizarResumenPlazos();
        }

        // Función para actualizar el resumen de plazos
        function actualizarResumenPlazos() {
            let html = '';
            
            // Abono inicial (si existe)
            if($('#abonoInicial').val() === '1') {
                const abonoFecha = $('#fechaAbonoInicial').val();
                const abonoMonto = parseFloat($('#montoAbonoInicial').val()) || 0;
                
                if(abonoFecha && abonoMonto > 0) {
                    html += `
                        <div class="plazo-item bg-light">
                            <div class="plazo-header">Abono Inicial</div>
                            <div class="plazo-details">
                                <span>${new Date(abonoFecha).toLocaleDateString('es-ES')}</span>
                                <span>$${abonoMonto.toFixed(2)}</span>
                            </div>
                        </div>
                    `;
                }
            }
            
            // Primer pago
            const primerPagoFecha = $('#fechaPrimerPago').val();
            const primerPagoMonto = parseFloat($('#montoPrimerPago').val()) || 0;
            
            if(primerPagoFecha && primerPagoMonto > 0) {
                html += `
                    <div class="plazo-item">
                        <div class="plazo-header">Primer Pago</div>
                        <div class="plazo-details">
                            <span>${new Date(primerPagoFecha).toLocaleDateString('es-ES')}</span>
                            <span>$${primerPagoMonto.toFixed(2)}</span>
                        </div>
                    </div>
                `;
            }
            
            // Plazos adicionales
            plazosAdicionales.forEach(plazo => {
                const fecha = $(`#plazoFecha-${plazo.id}`).val();
                const monto = parseFloat($(`#plazoMonto-${plazo.id}`).val()) || 0;
                
                if(fecha && monto > 0) {
                    html += `
                        <div class="plazo-item">
                            <div class="plazo-header">Plazo Adicional</div>
                            <div class="plazo-details">
                                <span>${new Date(fecha).toLocaleDateString('es-ES')}</span>
                                <span>$${monto.toFixed(2)}</span>
                            </div>
                        </div>
                    `;
                }
            });
            
            $('#resumenPlazos').html(html || '<p class="text-muted text-center">No hay plazos definidos</p>');
        }

        // Función para procesar los plazos adicionales
        function procesarPlazosAdicionales() {
            const plazos = [];
            
            plazosAdicionales.forEach(plazo => {
                const fecha = $(`#plazoFecha-${plazo.id}`).val();
                const monto = parseFloat($(`#plazoMonto-${plazo.id}`).val());
                
                if(fecha && !isNaN(monto)) {
                    plazos.push({
                        fecha: fecha,
                        monto: monto
                    });
                }
            });
            
            return plazos;
        }

        // Cargar proveedores
        function cargarProveedores() {
            $.ajax({
                url: 'crud_compras.php?action=get_proveedores',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    proveedores = data;
                    const select = $('#proveedor');
                    select.empty().append('<option value="">Seleccione un proveedor</option>');
                    
                    proveedores.forEach(proveedor => {
                        select.append(new Option(
                            `${proveedor.Nombre} (${proveedor.RUC})`,
                            proveedor.RUC
                        ));
                    });
                },
                error: function(xhr, status, error) {
                    mostrarAlerta('Error al cargar proveedores', 'error');
                }
            });
        }

        // Cargar productos
        function cargarProductos() {
            $.ajax({
                url: 'crud_compras.php?action=get_productos',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    productos = data;
                    const select = $('#producto');
                    select.empty().append('<option value="">Seleccione un producto</option>');
                    
                    productos.forEach(producto => {
                        let texto = producto.Nombre;
                        if(producto.marca) texto += ` - ${producto.marca}`;
                        if(producto.Stock !== undefined) texto += ` (Stock: ${producto.Stock})`;
                        
                        select.append(new Option(
                            texto,
                            producto.ProductoID
                        ));
                    });
                },
                error: function(xhr, status, error) {
                    mostrarAlerta('Error al cargar productos', 'error');
                }
            });
        }

        // Manejar cambio de producto
        $('#producto').change(function() {
    const productoID = $(this).val();
    if (productoID) {
        const producto = productos.find(p => p.ProductoID == productoID);
        
        if (producto) {
            $('#precioUnitario').val(parseFloat(producto.PrecioCompra).toFixed(2));
            $('#cantidad').val(1).focus();
            
            // Mostrar/ocultar campos líquidos
            if (producto.es_liquido == 1) {
                $('#campoLiquido').show();
                $('#capacidadEnvase').text(parseFloat(producto.capacidad_envase).toFixed(3) + ' L');
                actualizarCantidadLitros();
            } else {
                $('#campoLiquido').hide();
            }
        }
    }
});

function actualizarCantidadLitros() {
    const productoID = $('#producto').val();
    if (productoID) {
        const producto = productos.find(p => p.ProductoID === productoID);
        if (producto && producto.es_liquido == 1) {
            const cantidad = parseFloat($('#cantidad').val()) || 0;
            const litros = cantidad * parseFloat(producto.capacidad_envase);
            $('#cantidadLitros').text(litros.toFixed(3) + ' L');
        }
    }
}

        // Manejar cambios en cantidad
        $('#cantidad').on('input', function() {
            const productoID = $('#producto').val();
            if (productoID) {
                const producto = productos.find(p => p.ProductoID === productoID);
                if (producto && producto.es_liquido == 1) {
                    actualizarCantidadLitros();
                }
            }
        });

        // Agregar producto a la lista
        $('#agregarProductoBtn').click(function() {
            const productoID = $('#producto').val();
            let cantidad = parseFloat($('#cantidad').val());
            const precioUnitario = parseFloat($('#precioUnitario').val());
            
            if (!productoID || isNaN(cantidad) || cantidad <= 0 || isNaN(precioUnitario)) {
                mostrarAlerta('Complete todos los campos del producto con valores válidos', 'error');
                return;
            }

            const producto = productos.find(p => p.ProductoID === productoID);
            if (!producto) {
                mostrarAlerta('Producto no encontrado', 'error');
                return;
            }

            // Para líquidos, usar cantidad directa (ya se calculó en litros)
            const subtotal = cantidad * precioUnitario;
            const index = productosSeleccionados.findIndex(p => p.productoID === productoID);
            
            if (index >= 0) {
                productosSeleccionados[index].cantidad += cantidad;
                productosSeleccionados[index].subtotal += subtotal;
            } else {
                productosSeleccionados.push({
                    productoID: productoID,
                    nombre: producto.Nombre,
                    cantidad: cantidad,
                    precio: precioUnitario,
                    subtotal: subtotal,
                    esLiquido: producto.es_liquido == 1,
                    capacidadEnvase: producto.capacidad_envase
                });
            }

            actualizarListaProductos();
            calcularTotal();
            resetearCamposProducto();
        });

        // Actualizar lista visual de productos
        function actualizarListaProductos() {
            const container = $('#productosSeleccionados');
            container.empty();

            if (productosSeleccionados.length === 0) {
                container.html('<p class="text-muted text-center">No hay productos agregados</p>');
                $('#registrarCompraBtn').prop('disabled', true);
                return;
            }

            const table = $('<table class="table table-sm"></table>');
            const thead = $(`
                <thead class="table-light">
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>P. Unitario</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
            `);
            const tbody = $('<tbody></tbody>');
            
            table.append(thead, tbody);
            container.append(table);

            productosSeleccionados.forEach((producto, index) => {
                let cantidadDisplay = producto.cantidad;
                if (producto.esLiquido) {
                    cantidadDisplay = `${producto.cantidad} L (${(producto.cantidad / producto.capacidadEnvase).toFixed(0)} envases)`;
                }
                
                const row = $(`
                    <tr>
                        <td>${producto.nombre}</td>
                        <td>${cantidadDisplay}</td>
                        <td>$${producto.precio.toFixed(2)}</td>
                        <td>$${producto.subtotal.toFixed(2)}</td>
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

            $('#registrarCompraBtn').prop('disabled', false);
        }

        // Eliminar producto de la lista
        function eliminarProductoSeleccionado(index) {
            Swal.fire({
                title: '¿Eliminar producto?',
                text: "¿Está seguro de eliminar este producto de la lista?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    productosSeleccionados.splice(index, 1);
                    actualizarListaProductos();
                    calcularTotal();
                }
            });
        }

        // Calcular total de la compra
        function calcularTotal() {
            const total = productosSeleccionados.reduce((sum, producto) => sum + producto.subtotal, 0);
            $('#totalCompra').text('$' + total.toFixed(2));
            
            // Si es a plazos, actualizar monto del primer pago
            if($('#esPlazo').is(':checked')) {
                const montoPrimerPago = parseFloat($('#montoPrimerPago').val()) || 0;
                if(montoPrimerPago <= 0) {
                    $('#montoPrimerPago').val(total.toFixed(2));
                }
            }
            
            return total;
        }

        // Registrar compra
        $('#registrarCompraBtn').click(function() {
            const esPlazo = $('#esPlazo').is(':checked');
            const total = calcularTotal();
            
            if (!validarFormularioCompra()) return;

            const compraData = {
                ruc: $('#proveedor').val(),
                numFactura: $('#numeroFactura').val(),
                fecha: $('#fechaCompra').val(),
                total: total,
                usuarioID: usuarioID,
                productos: productosSeleccionados.map(p => ({
                    productoID: p.productoID,
                    cantidad: p.cantidad,
                    precio: p.precio
                })),
                es_plazo: esPlazo,
                estado_pago: 'pendiente',
                saldo_pendiente: total
            };
            
            if(esPlazo) {
                if(!validarPlazos(total)) return;
                
                compraData.plazos = {
                    abonoInicial: $('#abonoInicial').val() === '1' ? {
                        monto: parseFloat($('#montoAbonoInicial').val()),
                        fecha: $('#fechaAbonoInicial').val()
                    } : null,
                    primerPago: {
                        fecha: $('#fechaPrimerPago').val(),
                        monto: parseFloat($('#montoPrimerPago').val())
                    },
                    plazosAdicionales: procesarPlazosAdicionales()
                };
            }
            
            registrarCompra(compraData);
        });

        function validarFormularioCompra() {
            if(!$('#proveedor').val()) {
                mostrarAlerta('Seleccione un proveedor', 'error');
                return false;
            }
            if(productosSeleccionados.length === 0) {
                mostrarAlerta('Agregue al menos un producto', 'error');
                return false;
            }
            return true;
        }

        function validarPlazos(total) {
            const fechaPrimerPago = $('#fechaPrimerPago').val();
            const montoPrimerPago = parseFloat($('#montoPrimerPago').val());
            
            if(!fechaPrimerPago) {
                mostrarAlerta('Ingrese la fecha del primer pago', 'error');
                return false;
            }
            
            if(isNaN(montoPrimerPago) || montoPrimerPago <= 0) {
                mostrarAlerta('Ingrese un monto válido para el primer pago', 'error');
                return false;
            }
            // Validar que la fecha no sea anterior a hoy
    const hoy = new Date().toISOString().split('T')[0];
    if(fechaPrimerPago < hoy) {
        mostrarAlerta('La fecha del primer pago no puede ser anterior a hoy', 'error');
        return false;
    }
            
            if($('#abonoInicial').val() === '1') {
                const montoAbono = parseFloat($('#montoAbonoInicial').val());
                if(isNaN(montoAbono) || montoAbono <= 0) {
                    mostrarAlerta('Ingrese un monto válido para el abono inicial', 'error');
                    return false;
                }
                
                if(montoAbono >= total) {
                    mostrarAlerta('El abono inicial no puede ser mayor o igual al total', 'error');
                    return false;
                }
            }
            
            // Validar plazos adicionales
            let totalPlazos = parseFloat($('#montoPrimerPago').val()) || 0;
            const plazos = procesarPlazosAdicionales();
            
            plazos.forEach(plazo => {
                if(!plazo.fecha) {
                    mostrarAlerta('Todos los plazos adicionales deben tener una fecha válida', 'error');
                    return false;
                }
                if(isNaN(plazo.monto) || plazo.monto <= 0) {
                    mostrarAlerta('Todos los plazos adicionales deben tener un monto válido', 'error');
                    return false;
                }
                totalPlazos += plazo.monto;
            });
            
            // Verificar que la suma de plazos coincida con el total (considerando abono inicial)
            const abonoInicial = ($('#abonoInicial').val() === '1') ? parseFloat($('#montoAbonoInicial').val()) || 0 : 0;
            
            if(Math.abs((totalPlazos + abonoInicial) - total) > 0.01) {
                mostrarAlerta(`La suma de los plazos ($${(totalPlazos + abonoInicial).toFixed(2)}) no coincide con el total ($${total.toFixed(2)})`, 'error');
                return false;
            }
            
            return true;
        }

        function registrarCompra(compraData) {
            Swal.fire({
                title: '¿Registrar compra?',
                text: "¿Está seguro de registrar esta compra?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, registrar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'crud_compras.php?action=create_compra',
                        method: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify(compraData),
                        success: function(response) {
                            try {
                                const res = JSON.parse(response);
                                if (res.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Compra registrada',
                                        text: `Compra #${res.compraID} registrada correctamente`,
                                        showConfirmButton: false,
                                        timer: 2000
                                    }).then(() => {
                                        resetearFormulario();
                                        comprasTable.ajax.reload();
                                    });
                                } else {
                                    mostrarError(res.message || 'Error al registrar la compra');
                                }
                            } catch (e) {
                                mostrarError('Error al procesar la respuesta del servidor');
                            }
                        },
                        error: function(xhr, status, error) {
                            mostrarError('Error al registrar compra: ' + error);
                        }
                    });
                }
            });
        }

        // Función para ver detalle de compra
        window.verDetalleCompra = function(compraID) {
            $('#detalleCompraModal').modal('show');
            $('#detalleCompraID').text(compraID);
            
            const tbody = $('#detalleCompraTable');
            tbody.html('<tr><td colspan="4" class="text-center"><div class="spinner-border text-primary"></div></td></tr>');
            
            $.ajax({
                url: 'crud_compras.php?action=get_detalle&compraID=' + compraID,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (!response || !response.success) {
                        throw new Error(response?.error || "Respuesta inválida del servidor");
                    }

                    const { compra, detalles } = response;
                    
                    $('#detalleProveedor').text(compra.ProveedorNombre + ' (' + compra.RUC + ')');
                    $('#detalleFactura').text(compra.NumeroFactura || 'N/A');
                    $('#detalleFecha').text(new Date(compra.FechaCompra).toLocaleDateString('es-ES'));
                    $('#detalleUsuario').text(compra.UsuarioNombre || 'Administrador');
                    
                    tbody.empty();
                    
                    if (!detalles || detalles.length === 0) {
                        tbody.append('<tr><td colspan="4" class="text-center text-muted">No se encontraron productos</td></tr>');
                    } else {
                        let subtotal = 0;
                        detalles.forEach(detalle => {
                            const itemSubtotal = detalle.Cantidad * detalle.PrecioUnitario;
                            subtotal += itemSubtotal;
                            
                            tbody.append(`
                                <tr>
                                    <td>${detalle.ProductoNombre || 'Producto no disponible'}</td>
                                    <td>${detalle.Cantidad}</td>
                                    <td>$${detalle.PrecioUnitario.toFixed(2)}</td>
                                    <td>$${itemSubtotal.toFixed(2)}</td>
                                </tr>
                            `);
                        });
                        
                        $('#detalleSubtotal').text('$' + subtotal.toFixed(2));
                        $('#detalleTotal').text('$' + compra.TotalCompra.toFixed(2));
                    }
                },
                error: function(xhr, status, error) {
                    tbody.html(`
                        <tr>
                            <td colspan="4" class="text-center text-danger">
                                Error al cargar los detalles: ${error}
                            </td>
                        </tr>
                    `);
                }
            });
        }

        // Función para ver plazos de compra
        window.verPlazosCompra = function(compraID) {
            Swal.fire({
                title: 'Plazos de Compra',
                html: '<div class="text-center"><div class="spinner-border text-primary"></div></div>',
                showConfirmButton: false,
                allowOutsideClick: false
            });
            
            $.ajax({
                url: 'crud_compras.php?action=get_compra_plazos&compra_id=' + compraID,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (!response || !response.success) {
                        Swal.fire('Error', response?.message || 'Error al obtener plazos', 'error');
                        return;
                    }

                    const { compra, plazos, abonos } = response;
                    let html = `
                        <div class="text-start">
                            <h5>Compra #${compra.CompraID}</h5>
                            <p><strong>Proveedor:</strong> ${compra.proveedor_nombre}</p>
                            <p><strong>Total:</strong> $${compra.TotalCompra.toFixed(2)}</p>
                            <p><strong>Saldo Pendiente:</strong> $${compra.saldo_pendiente.toFixed(2)}</p>
                            <hr>
                            <h6>Plazos</h6>
                    `;
                    
                    if (plazos.length === 0) {
                        html += '<p class="text-muted">No hay plazos registrados</p>';
                    } else {
                        plazos.forEach(plazo => {
                            const estado = plazo.estado_actual === 'Vencido' ? 'badge-danger' : 
                                         plazo.estado_actual === 'Pagado' ? 'badge-success' : 'badge-warning';
                            html += `
                                <div class="plazo-item mb-2">
                                    <div class="plazo-details">
                                        <span>${new Date(plazo.fecha_vencimiento).toLocaleDateString('es-ES')}</span>
                                        <span>$${plazo.monto_esperado.toFixed(2)}</span>
                                        <span class="badge ${estado}">${plazo.estado_actual}</span>
                                    </div>
                                </div>
                            `;
                        });
                    }
                    
                    html += `<hr><h6>Abonos Realizados</h6>`;
                    
                    if (abonos.length === 0) {
                        html += '<p class="text-muted">No hay abonos registrados</p>';
                    } else {
                        abonos.forEach(abono => {
                            html += `
                                <div class="plazo-item mb-2 bg-light">
                                    <div class="plazo-details">
                                        <span>${new Date(abono.fecha_abono).toLocaleDateString('es-ES')}</span>
                                        <span>$${abono.monto.toFixed(2)}</span>
                                        <span>${abono.observaciones || 'Abono'}</span>
                                    </div>
                                </div>
                            `;
                        });
                    }
                    
                    html += `</div>`;
                    
                    Swal.fire({
                        title: 'Plazos y Abonos',
                        html: html,
                        width: '600px',
                        confirmButtonText: 'Cerrar'
                    });
                },
                error: function(xhr, status, error) {
                    Swal.fire('Error', 'Error al obtener plazos: ' + error, 'error');
                }
            });
        }

        // Función para imprimir compra
        window.imprimirCompra = function() {
            const compraID = $('#detalleCompraID').text();
            window.open(`reporte_compra.php?compraID=${compraID}`, '_blank');
        }

        // Función para mostrar alertas
        function mostrarAlerta(mensaje, tipo) {
            Swal.fire({
                icon: tipo,
                title: tipo === 'error' ? 'Error' : 'Advertencia',
                text: mensaje,
                confirmButtonText: 'Entendido'
            });
        }

        // Función para mostrar errores
        function mostrarError(mensaje) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: mensaje,
                confirmButtonText: 'Entendido'
            });
        }

        // Función para resetear campos de producto
        function resetearCamposProducto() {
            $('#producto').val('').trigger('change');
            $('#cantidad').val(1);
            $('#precioUnitario').val('');
            $('#campoLiquido').hide();
        }

        // Función para resetear todo el formulario
        function resetearFormulario() {
            productosSeleccionados = [];
            plazosAdicionales = [];
            $('#productosSeleccionados').html('<p class="text-muted text-center">No hay productos agregados</p>');
            $('#compraForm')[0].reset();
            $('#fechaCompra').val(new Date().toISOString().split('T')[0]);
            $('#esPlazo').prop('checked', false);
            $('#seccionPlazos').hide();
            $('#registrarCompraBtn').prop('disabled', true);
            $('#totalCompra').text('$0.00');
            $('#resumenPlazos').empty();
            $('#plazosContainer').empty();
        }

        // Inicialización
        $(function() {
            initDataTable();
            cargarProveedores();
            cargarProductos();
            $('#fechaCompra').val(new Date().toISOString().split('T')[0]);
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











