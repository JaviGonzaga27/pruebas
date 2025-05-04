<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/mecanica2/auth.php';

// Configuración de la página
$title = 'Gestión de Proveedores';
$config = [
    'base_path' => '/mecanica2/',
    'assets_path' => '/mecanica2/assets/',
    'site_name' => 'Sistema de Taller Mecánico'
];

// CSS adicional específico para esta página
$inline_css = "
    .badge-proveedor {
        font-size: 0.9em;
        padding: 5px 10px;
        border-radius: 4px;
    }
    .table-proveedores th {
        background-color: #f8f9fa;
        font-weight: 600;
    }
    .table-proveedores td {
        vertical-align: middle;
    }
    .action-buttons .btn {
        padding: 0.375rem 0.5rem;
        margin: 0 2px;
    }
    .modal-proveedor .modal-header {
        background-color: #4e73df;
        color: white;
    }
    .scrollable-modal-body {
        max-height: 70vh;
        overflow-y: auto;
    }
";

// CDN CSS
$cdn_css = [
    'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css'
];

// Plugins específicos
$page_plugins = [
    'datatables/datatables.min.js'
];

// CDN scripts
$cdn_scripts = [
    'https://cdn.jsdelivr.net/npm/sweetalert2@11'
];

// Scripts de la página
$page_scripts = [
    'js/proveedores.js'
];

// Iniciar el template
include '../includes/template.php';
?>

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
                        <h4 class="card-title">Proveedores</h4>
                        <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#addRowModal">
                            <i class="fa fa-plus"></i>
                            Agregar Proveedor
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Modal para Agregar Proveedor -->
                    <div class="modal fade modal-proveedor" id="addRowModal" tabindex="-1" aria-labelledby="addRowModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addRowModalLabel">
                                        <span class="fw-mediumbold">Nuevo</span>
                                        <span class="fw-light">Proveedor</span>
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body scrollable-modal-body">
                                    <form id="addProveedorForm">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label>RUC <span class="text-danger">*</span></label>
                                                    <input id="ruc" type="text" class="form-control" placeholder="Ingrese RUC" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label>Nombre <span class="text-danger">*</span></label>
                                                    <input id="nombre" type="text" class="form-control" placeholder="Ingrese nombre" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label>Dirección</label>
                                                    <input id="direccion" type="text" class="form-control" placeholder="Ingrese dirección">
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label>Teléfono</label>
                                                    <input id="telefono" type="text" class="form-control" placeholder="Ingrese teléfono">
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label>Celular</label>
                                                    <input id="celular" type="text" class="form-control" placeholder="Ingrese celular">
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input id="email" type="email" class="form-control" placeholder="Ingrese email">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" id="addProveedorButton" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Guardar
                                    </button>
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                        <i class="fas fa-times"></i> Cancelar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal para Editar Proveedor -->
                    <div class="modal fade modal-proveedor" id="editRowModal" tabindex="-1" aria-labelledby="editRowModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editRowModalLabel">
                                        <span class="fw-mediumbold">Editar</span>
                                        <span class="fw-light">Proveedor</span>
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body scrollable-modal-body">
                                    <form id="editProveedorForm">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label>RUC</label>
                                                    <input id="editRuc" type="text" class="form-control" readonly>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label>Nombre <span class="text-danger">*</span></label>
                                                    <input id="editNombre" type="text" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label>Dirección</label>
                                                    <input id="editDireccion" type="text" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label>Teléfono</label>
                                                    <input id="editTelefono" type="text" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label>Celular</label>
                                                    <input id="editCelular" type="text" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input id="editEmail" type="email" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" id="editProveedorButton" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Guardar Cambios
                                    </button>
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                        <i class="fas fa-times"></i> Cancelar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de Proveedores -->
                    <div class="table-responsive">
                        <table id="proveedoresTable" class="table table-striped table-hover table-proveedores">
                            <thead>
                                <tr>
                                    <th>RUC</th>
                                    <th>Nombre</th>
                                    <th>Dirección</th>
                                    <th>Teléfono</th>
                                    <th>Celular</th>
                                    <th>Email</th>
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

<?php
// Incluir el pie de página del template
include '../includes/template_footer.php';
?>