<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/mecanica2/auth.php';

// Configuración de la página
$title = 'Gestión de Categorías';
$config = [
    'base_path' => '/mecanica2/',
    'assets_path' => '/mecanica2/assets/',
    'site_name' => 'Sistema de Taller Mecánico'
];

// CSS adicional específico para esta página
$inline_css = "
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
    'js/categorias.js'
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

<?php
// Incluir el pie de página del template
include '../includes/template_footer.php';
?>