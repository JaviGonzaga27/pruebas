<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/mecanica2/auth.php';

// Configuración de la página
$title = 'Clientes';
$config = [
    'base_path' => '/mecanica2/',
    'assets_path' => '/mecanica2/assets/'
];

// CSS específico
$inline_css = '
    .form-button-action {
        display: flex;
        gap: 10px;
    }
    .modal-body {
        max-height: 70vh;
        overflow-y: auto;
    }
    .is-invalid {
        border-color: #dc3545 !important;
    }
    .is-valid {
        border-color: #28a745 !important;
    }
    .invalid-feedback {
        display: none;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 80%;
        color: #dc3545;
    }
    .is-invalid ~ .invalid-feedback {
        display: block;
    }
';

// CSS desde CDN
$cdn_css = [
    'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css'
];

// Plugins específicos (dentro de /assets/js/plugin/)
$page_plugins = [
    'datatables/datatables.min.js'
];

// Scripts desde CDN
$cdn_scripts = [
    'https://cdn.jsdelivr.net/npm/sweetalert2@11'
];

// Scripts específicos (bajo /assets/js/)
$page_scripts = [
    'js/clientes.js'
];

// Incluir plantilla header
include '../includes/template.php';
?>

<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Clientes</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home">
                <a href="../index.php">
                    <i class="icon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Clientes</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Gestión de Clientes</h4>
                        <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#addClienteModal">
                            <i class="fa fa-plus"></i>
                            Agregar Cliente
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Modal para Agregar Cliente -->
                    <div class="modal fade" id="addClienteModal" tabindex="-1" aria-labelledby="addClienteModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addClienteModalLabel">Nuevo Cliente</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="addClienteForm">
                                        <div class="form-group">
                                            <label>Cédula <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="cedula" required maxlength="10">
                                            <div class="invalid-feedback">La cédula debe tener 10 dígitos válidos</div>
                                        </div>
                                        <div class="form-group">
                                            <label>Nombre <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="nombre" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Dirección</label>
                                            <input type="text" class="form-control" id="direccion">
                                        </div>
                                        <div class="form-group">
                                            <label>Teléfono</label>
                                            <input type="text" class="form-control" id="telefono">
                                            <div class="invalid-feedback">Formato inválido (ej: 0987654321 o 022345678)</div>
                                        </div>
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" class="form-control" id="email">
                                            <div class="invalid-feedback">Ingrese un email válido</div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" id="addClienteBtn" class="btn btn-primary">Guardar</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal para Editar Cliente -->
                    <div class="modal fade" id="editClienteModal" tabindex="-1" aria-labelledby="editClienteModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editClienteModalLabel">Editar Cliente</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="editClienteForm">
                                        <input type="hidden" id="editCedula">
                                        <div class="form-group">
                                            <label>Cédula</label>
                                            <input type="text" class="form-control" id="editCedulaDisplay" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Nombre <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="editNombre" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Dirección</label>
                                            <input type="text" class="form-control" id="editDireccion">
                                        </div>
                                        <div class="form-group">
                                            <label>Teléfono</label>
                                            <input type="text" class="form-control" id="editTelefono">
                                            <div class="invalid-feedback">Formato inválido (ej: 0987654321 o 022345678)</div>
                                        </div>
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" class="form-control" id="editEmail">
                                            <div class="invalid-feedback">Ingrese un email válido</div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" id="editClienteBtn" class="btn btn-primary">Guardar Cambios</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de Clientes -->
                    <div class="table-responsive">
                        <table id="clientesTable" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Cédula</th>
                                    <th>Nombre</th>
                                    <th>Dirección</th>
                                    <th>Teléfono</th>
                                    <th>Email</th>
                                    <th>Fecha Registro</th>
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

<?php
// Incluir plantilla footer
include '../includes/template_footer.php';
?>