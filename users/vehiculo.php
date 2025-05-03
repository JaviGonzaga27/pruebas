<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/mecanica2/auth.php';

// Configuración de rutas
$base_path = '/mecanica2/';
$assets_path = $base_path . 'assets/';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sistema de Taller Mecánico - Vehículos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="icon" href="<?= $assets_path ?>img/kaiadmin/favicon.ico" type="image/x-icon">

    <!-- Fonts and icons -->
    <script src="<?= $assets_path ?>js/plugin/webfont/webfont.min.js"></script>
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
                urls: ["<?= $assets_path ?>css/fonts.min.css"],
            },
            active: function () {
                sessionStorage.fonts = true;
            },
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="<?= $assets_path ?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= $assets_path ?>css/plugins.min.css">
    <link rel="stylesheet" href="<?= $assets_path ?>css/kaiadmin.min.css">
    <link rel="stylesheet" href="<?= $assets_path ?>css/demo.css">
    
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
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
</head>
<body>
    <div class="wrapper">
        <?php include '../navbar.php'; ?>

        <div class="main-panel">
            <?php include '../includes/header.php'; ?>

            <div class="container">
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

            <?php include '../includes/footer.php'; ?>
        </div>
    </div>

    <!-- Scripts Core -->
    <script src="<?= $assets_path ?>js/core/jquery-3.7.1.min.js"></script>
    <script src="<?= $assets_path ?>js/core/popper.min.js"></script>
    <script src="<?= $assets_path ?>js/core/bootstrap.min.js"></script>
    
    <!-- Plugins -->
    <script src="<?= $assets_path ?>js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <script src="<?= $assets_path ?>js/plugin/datatables/datatables.min.js"></script>
    <script src="<?= $assets_path ?>js/kaiadmin.min.js"></script>
    <script src="<?= $assets_path ?>js/setting-demo2.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                $.ajax({
                    url: 'crud_vehiculos.php?action=read_one&vehiculoID=' + vehiculoID,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.error) {
                            showNotification('error', response.error);
                            return;
                        }

                        // Función para formatear fechas
                        function formatDate(dateString) {
                            if (!dateString) return 'N/A';
                            const date = new Date(dateString);
                            return date.toLocaleDateString('es-ES');
                        }

                        // Llenar el modal con los datos
                        $('#viewVehiculoID').text(response.VehiculoID || 'N/A');
                        $('#viewCliente').text(response.ClienteNombre ? `${response.ClienteNombre} (${response.ClienteCedula || 'Sin cédula'})` : 'N/A');
                        $('#viewMarca').text(response.Marca || 'N/A');
                        $('#viewModelo').text(response.Modelo || 'N/A');
                        $('#viewAnio').text(response.Anio || 'N/A');
                        $('#viewPlaca').text(response.Placa || 'N/A');
                        $('#viewVin').text(response.VIN || 'N/A');
                        $('#viewKilometraje').text(response.Kilometraje ? response.Kilometraje + ' km' : 'N/A');
                        $('#viewColor').text(response.Color || 'N/A');
                        $('#viewTipo').text(response.Tipo || 'N/A');
                        $('#viewCombustible').text(response.Combustible || 'N/A');
                        $('#viewTransmision').text(response.Transmision || 'N/A');
                        $('#viewUltimoServicio').text(formatDate(response.UltimoServicio));
                        $('#viewProximoServicio').text(formatDate(response.ProximoServicio));
                        $('#viewObservaciones').text(response.Observaciones || 'Ninguna');

                        $('#viewVehiculoModal').modal('show');
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al cargar los detalles del vehículo: ';
                        try {
                            const res = JSON.parse(xhr.responseText);
                            errorMsg += res.error || xhr.statusText;
                        } catch(e) {
                            errorMsg += xhr.statusText;
                        }
                        showNotification('error', errorMsg);
                    }
                });
            };

            window.editarVehiculo = function(vehiculoID) {
                $.ajax({
                    url: 'crud_vehiculos.php?action=read_one&vehiculoID=' + vehiculoID,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.error) {
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
                        
                        $('#editVehiculoModal').modal('show');
                    },
                    error: function(xhr) {
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

                // Validar campos requeridos
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
        // Agregar al final del script en vehiculo.php, antes de cerrar el documento

    // Validaciones para Ecuador (agregar a las validaciones existentes)
    const validationsEcu = {
        validarPlaca: function(placa) {
            const regexPlacaActual = /^[A-Z]{3}-[0-9]{4}$/;
            const regexPlacaAntigua = /^[A-Z]{2}-[0-9]{4}$/;
            return regexPlacaActual.test(placa) || regexPlacaAntigua.test(placa);
        }
    };

    // Modificar el botón agregar vehículo para incluir validaciones
    $('#addVehiculoBtn').off('click').click(function () {
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

        // Validaciones ecuatorianas
        const errores = [];
        const placa = $('#placa').val();
        const anio = $('#anio').val();
        
        if (!validationsEcu.validarPlaca(placa)) {
            errores.push('La placa debe tener formato ecuatoriano (AAA-1234 o AA-1234)');
        }
        
        const anioActual = new Date().getFullYear();
        if (anio < 1900 || anio > anioActual + 1) {
            errores.push(`El año debe estar entre 1900 y ${anioActual + 1}`);
        }
        
        if (errores.length > 0) {
            showNotification('error', errores.join('<br>'));
            $btn.html(originalHtml);
            $btn.prop('disabled', false);
            return;
        }

        const formData = {
            clienteID: $('#clienteID').val(),
            marca: $('#marca').val(),
            modelo: $('#modelo').val(),
            anio: anio,
            placa: placa,
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

    // Validación en tiempo real para placa
    $('#placa, #editPlaca').on('input', function() {
        let placa = $(this).val().toUpperCase().replace(/[^A-Z0-9]/g, '');
        
        // Formatear automáticamente la placa
        if (placa.length > 2 && placa.length <= 6) {
            placa = placa.slice(0, 2) + '-' + placa.slice(2);
        } else if (placa.length > 3) {
            placa = placa.slice(0, 3) + '-' + placa.slice(3);
        }
        
        if (placa.length > 8) {
            placa = placa.slice(0, 8);
        }
        
        $(this).val(placa);
        

    });
    
    // Validación en tiempo real para año
    $('#anio, #editAnio').on('input', function() {
        const anio = $(this).val();
        const anioActual = new Date().getFullYear();
        
        if (anio) {
            if (anio >= 1900 && anio <= anioActual + 1) {
                $(this).removeClass('is-invalid').addClass('is-valid');
            } else {
                $(this).removeClass('is-valid').addClass('is-invalid');
            }
        }
    });
    
    // Validación en tiempo real para kilometraje
    $('#kilometraje, #editKilometraje').on('input', function() {
        const km = $(this).val();
        $(this).val($(this).val().replace(/[^0-9]/g, ''));
        
        if (km >= 0) {
            $(this).removeClass('is-invalid').addClass('is-valid');
        } else {
            $(this).removeClass('is-valid').addClass('is-invalid');
        }
    });
    
    // Limitar VIN a 17 caracteres
    $('#vin, #editVin').on('input', function() {
        $(this).val($(this).val().toUpperCase().replace(/[^A-Z0-9]/g, '').substr(0, 17));
    });

    // Para vehiculo.php
$(document).ready(function() {
    
    // Limpiar validaciones al cerrar modal de agregar
    $('#addVehiculoModal').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
        // Remover todas las clases de validación
        $(this).find('input, select, textarea').removeClass('is-valid is-invalid');
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
    });
    
    // Limpiar validaciones al cerrar modal de editar
    $('#editVehiculoModal').on('hidden.bs.modal', function () {
        // Remover todas las clases de validación
        $(this).find('input, select, textarea').removeClass('is-valid is-invalid');
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
    });
    
    // Limpiar validaciones al cerrar modal de ver
    $('#viewVehiculoModal').on('hidden.bs.modal', function () {
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
    });
});
});
    </script>
</body>
</html>