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

    <!-- SweetAlert2 CSS -->
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

    <!-- Estilos personalizados para categorías -->
    <style>
      .badge-categoria {
        font-size: 0.9em;
        padding: 5px 10px;
        border-radius: 4px;
      }
      .table-categorias th {
        background-color: #f8f9fa;
        font-weight: 600;
      }
      .table-categorias td {
        vertical-align: middle;
      }
      .action-buttons .btn {
        padding: 0.375rem 0.5rem;
        margin: 0 2px;
      }
      .modal-categoria .modal-header {
        background-color: #4e73df;
        color: white;
      }
      .scrollable-modal-body {
        max-height: 70vh;
        overflow-y: auto;
      }
    </style>
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
                    <li class="active">
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
                  <a href="#">Categorías</a>
                </li>
              </ul>
            </div>
            <div class="row">
            <div class="col-md-12">
              <div class="card">
              <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Categorías</h4>
                    <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#addRowModal">
                        <i class="fa fa-plus"></i>
                        Agregar Categoría
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Modal para Agregar Categoría -->
                <div class="modal fade modal-categoria" id="addRowModal" tabindex="-1" aria-labelledby="addRowModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addRowModalLabel">
                                    <span class="fw-mediumbold">Nueva</span>
                                    <span class="fw-light">Categoría</span>
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body scrollable-modal-body">
                                <form id="addCategoriaForm">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Nombre <span class="text-danger">*</span></label>
                                                <input id="nombre" type="text" class="form-control" placeholder="Ingrese nombre" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Descripción</label>
                                                <textarea id="descripcion" class="form-control" placeholder="Ingrese descripción"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" id="addCategoriaButton" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Guardar
                                </button>
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                    <i class="fas fa-times"></i> Cancelar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal para Editar Categoría -->
                <div class="modal fade modal-categoria" id="editRowModal" tabindex="-1" aria-labelledby="editRowModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editRowModalLabel">
                                    <span class="fw-mediumbold">Editar</span>
                                    <span class="fw-light">Categoría</span>
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body scrollable-modal-body">
                                <form id="editCategoriaForm">
                                    <input type="hidden" id="editCategoriaID">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Nombre <span class="text-danger">*</span></label>
                                                <input id="editNombre" type="text" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Descripción</label>
                                                <textarea id="editDescripcion" class="form-control"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" id="editCategoriaButton" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Guardar Cambios
                                </button>
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                    <i class="fas fa-times"></i> Cancelar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla de Categorías -->
                <div class="table-responsive">
                    <table id="categoriasTable" class="table table-striped table-hover table-categorias">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Los datos se cargarán dinámicamente con JavaScript -->
                        </tbody>
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

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


 <!-- Script para manejar la tabla dinámica -->
    <!-- Script para manejar la tabla dinámica -->
    <script>
        $(document).ready(function () {
            // Inicializar DataTable con configuración mejorada
            const table = $('#categoriasTable').DataTable({
                ajax: {
                    url: 'crud_categorias.php?action=read',
                    dataSrc: '',
                    error: function(xhr, error, thrown) {
                        console.error('Error al cargar datos:', xhr.responseText);
                        // Mostrar fila de error en la tabla
                        let mensaje = 'Error al cargar las categorías. ';
                        try {
                            const response = JSON.parse(xhr.responseText);
                            mensaje += response.error || '';
                        } catch(e) {
                            mensaje += xhr.responseText || error;
                        }
                        
                        $('#categoriasTable tbody').html(`
                            <tr class="odd">
                                <td valign="top" colspan="4" class="dataTables_empty text-center text-danger">
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
                    { data: 'CategoriaID' },
                    { 
                        data: 'Nombre',
                        className: 'fw-bold'
                    },
                    { 
                        data: 'Descripcion',
                        render: function(data) {
                            return data || '<span class="text-muted">Sin descripción</span>';
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, row) {
                            return `
                                <div class="action-buttons text-center">
                                    <button type="button" class="btn btn-link btn-primary btn-sm" 
                                        onclick="editarCategoria(${row.CategoriaID})" title="Editar">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-link btn-danger btn-sm" 
                                        onclick="eliminarCategoria(${row.CategoriaID})" title="Eliminar">
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
                autoWidth: false,
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50, 100],
                dom: '<"top"lf>rt<"bottom"ip><"clear">'
            });

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

            // Agregar categoría
            $('#addCategoriaButton').click(function () {
                const nombre = $('#nombre').val().trim();
                const descripcion = $('#descripcion').val().trim();
                
                if (!nombre) {
                    showNotification('error', 'El nombre de la categoría es requerido');
                    $('#nombre').addClass('is-invalid');
                    return;
                }
                
                const $btn = $(this);
                const originalHtml = $btn.html();
                $btn.html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
                $btn.prop('disabled', true);

                $.ajax({
                    url: 'crud_categorias.php?action=create',
                    method: 'POST',
                    data: {
                        nombre: nombre,
                        descripcion: descripcion
                    },
                    success: function (response) {
                        $btn.html(originalHtml);
                        $btn.prop('disabled', false);
                        
                        if (response.includes('correctamente')) {
                            table.ajax.reload(null, false);
                            $('#addRowModal').modal('hide');
                            $('#addCategoriaForm')[0].reset();
                            showNotification('success', 'Categoría agregada correctamente');
                        } else {
                            showNotification('error', response || 'Error al agregar categoría');
                        }
                    },
                    error: function(xhr, status, error) {
                        $btn.html(originalHtml);
                        $btn.prop('disabled', false);
                        
                        let errorMsg = 'Error al agregar categoría: ';
                        try {
                            const res = JSON.parse(xhr.responseText);
                            errorMsg += res.error || error;
                        } catch(e) {
                            errorMsg += xhr.responseText || error;
                        }
                        
                        showNotification('error', errorMsg);
                    }
                });
            });

            // Eliminar categoría
            window.eliminarCategoria = function (categoriaID) {
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
                            url: 'crud_categorias.php?action=delete',
                            method: 'POST',
                            data: { categoriaID: categoriaID },
                            success: function (response) {
                                if (response.includes('correctamente')) {
                                    table.ajax.reload(null, false);
                                    showNotification('success', 'Categoría eliminada correctamente');
                                } else {
                                    showNotification('error', response || 'Error al eliminar categoría');
                                }
                            },
                            error: function(xhr, status, error) {
                                let errorMsg = 'Error al eliminar categoría: ';
                                try {
                                    const res = JSON.parse(xhr.responseText);
                                    errorMsg += res.error || error;
                                } catch(e) {
                                    errorMsg += xhr.responseText || error;
                                }
                                showNotification('error', errorMsg);
                            }
                        });
                    }
                });
            };

            // Editar categoría
            window.editarCategoria = function (categoriaID) {
                // Mostrar spinner en el modal
                $('#editRowModal .modal-body').prepend(`
                    <div class="overlay d-flex justify-content-center align-items-center" 
                        style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(255,255,255,0.7); z-index: 1000;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                    </div>
                `);
                
                $('#editRowModal').modal('show');

                $.ajax({
                    url: 'crud_categorias.php?action=read',
                    method: 'GET',
                    success: function (data) {
                        try {
                            const categorias = JSON.parse(data);
                            const categoria = categorias.find(c => c.CategoriaID == categoriaID);

                            if (categoria) {
                                $('#editCategoriaID').val(categoria.CategoriaID);
                                $('#editNombre').val(categoria.Nombre);
                                $('#editDescripcion').val(categoria.Descripcion || '');
                                
                                // Ocultar spinner
                                $('#editRowModal .overlay').remove();
                            } else {
                                $('#editRowModal').modal('hide');
                                showNotification('error', 'Categoría no encontrada');
                            }
                        } catch(e) {
                            $('#editRowModal').modal('hide');
                            showNotification('error', 'Error al procesar los datos de la categoría');
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#editRowModal').modal('hide');
                        
                        let errorMsg = 'Error al obtener categoría: ';
                        try {
                            const res = JSON.parse(xhr.responseText);
                            errorMsg += res.error || error;
                        } catch(e) {
                            errorMsg += xhr.responseText || error;
                        }
                        
                        showNotification('error', errorMsg);
                    }
                });
            };

            // Guardar cambios al editar categoría
            $('#editCategoriaButton').click(function () {
                const categoriaID = $('#editCategoriaID').val();
                const nombre = $('#editNombre').val().trim();
                const descripcion = $('#editDescripcion').val().trim();
                
                if (!nombre) {
                    showNotification('error', 'El nombre de la categoría es requerido');
                    $('#editNombre').addClass('is-invalid');
                    return;
                }
                
                const $btn = $(this);
                const originalHtml = $btn.html();
                $btn.html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
                $btn.prop('disabled', true);

                $.ajax({
                    url: 'crud_categorias.php?action=update',
                    method: 'POST',
                    data: {
                        categoriaID: categoriaID,
                        nombre: nombre,
                        descripcion: descripcion
                    },
                    success: function (response) {
                        $btn.html(originalHtml);
                        $btn.prop('disabled', false);
                        
                        if (response.includes('correctamente')) {
                            table.ajax.reload(null, false);
                            $('#editRowModal').modal('hide');
                            showNotification('success', 'Categoría actualizada correctamente');
                        } else {
                            showNotification('error', response || 'Error al actualizar categoría');
                        }
                    },
                    error: function(xhr, status, error) {
                        $btn.html(originalHtml);
                        $btn.prop('disabled', false);
                        
                        let errorMsg = 'Error al actualizar categoría: ';
                        try {
                            const res = JSON.parse(xhr.responseText);
                            errorMsg += res.error || error;
                        } catch(e) {
                            errorMsg += xhr.responseText || error;
                        }
                        
                        showNotification('error', errorMsg);
                    }
                });
            });

            // Limpiar formulario cuando se cierra el modal
            $('#addRowModal').on('hidden.bs.modal', function () {
                $(this).find('form')[0].reset();
                $(this).find('.is-invalid').removeClass('is-invalid');
            });

            $('#editRowModal').on('hidden.bs.modal', function () {
                $(this).find('.is-invalid').removeClass('is-invalid');
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
