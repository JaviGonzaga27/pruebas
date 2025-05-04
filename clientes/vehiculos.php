<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/mecanica2/auth.php';

// Configuración de la página
$title = 'Vehículos';
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
    'js/vehiculo.js'
];

// Incluir plantilla header
include '../includes/template.php';
?>

<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Vehículos</h3>
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
                <a href="#">Vehículos</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Gestión de Vehículos</h4>
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
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addVehiculoModalLabel">Nuevo Vehículo</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="addVehiculoForm">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Cliente <span class="text-danger">*</span></label>
                                                    <select class="form-control" id="clienteID" required></select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Marca <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="marca" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Modelo <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="modelo" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Año <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control" id="anio" required min="1900" max="2099">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Placa <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="placa" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>VIN (Número de chasis)</label>
                                                    <input type="text" class="form-control" id="vin">
                                                </div>
                                                <div class="form-group">
                                                    <label>Kilometraje</label>
                                                    <input type="number" class="form-control" id="kilometraje" min="0">
                                                </div>
                                                <div class="form-group">
                                                    <label>Color</label>
                                                    <input type="text" class="form-control" id="color">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Último Servicio</label>
                                                    <input type="date" class="form-control" id="ultimoServicio">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Próximo Servicio</label>
                                                    <input type="date" class="form-control" id="proximoServicio">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Tipo</label>
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
                                                    <label>Combustible</label>
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
                                                    <label>Transmisión</label>
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
                                            <label>Observaciones</label>
                                            <textarea class="form-control" id="observaciones" rows="3"></textarea>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" id="addVehiculoBtn" class="btn btn-primary">Guardar</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal para Editar Vehículo -->
                    <div class="modal fade" id="editVehiculoModal" tabindex="-1" aria-labelledby="editVehiculoModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editVehiculoModalLabel">Editar Vehículo</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="editVehiculoForm">
                                        <input type="hidden" id="editVehiculoID">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Cliente <span class="text-danger">*</span></label>
                                                    <select class="form-control" id="editClienteID" required></select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Marca <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="editMarca" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Modelo <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="editModelo" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Año <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control" id="editAnio" required min="1900" max="2099">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Placa <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="editPlaca" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>VIN (Número de chasis)</label>
                                                    <input type="text" class="form-control" id="editVin">
                                                </div>
                                                <div class="form-group">
                                                    <label>Kilometraje</label>
                                                    <input type="number" class="form-control" id="editKilometraje" min="0">
                                                </div>
                                                <div class="form-group">
                                                    <label>Color</label>
                                                    <input type="text" class="form-control" id="editColor">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Último Servicio</label>
                                                    <input type="date" class="form-control" id="editUltimoServicio">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Próximo Servicio</label>
                                                    <input type="date" class="form-control" id="editProximoServicio">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Tipo</label>
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
                                                    <label>Combustible</label>
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
                                                    <label>Transmisión</label>
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
                                            <label>Observaciones</label>
                                            <textarea class="form-control" id="editObservaciones" rows="3"></textarea>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" id="editVehiculoBtn" class="btn btn-primary">Guardar Cambios</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal para Ver Vehículo -->
                    <div class="modal fade" id="viewVehiculoModal" tabindex="-1" aria-labelledby="viewVehiculoModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="viewVehiculoModalLabel">Detalles del Vehículo</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
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

<?php
// Incluir plantilla footer
include '../includes/template_footer.php';
?>