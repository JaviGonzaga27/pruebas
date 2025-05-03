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
    <title>Sistema de Taller Mecánico - Compras</title>
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
    
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
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
            align-items: center;
        }
        .loading-products {
            text-align: center;
            padding: 20px;
        }
        .producto-liquido-info {
            background-color: #e3f2fd;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <?php include '../navbar.php'; ?>
        <!-- End Sidebar -->
        
        <div class="main-panel">
            <!-- Navbar Header -->
            <?php include '../includes/header.php'; ?>
            <!-- End Navbar Header -->

            <div class="container">
                <div class="page-inner">
                    <div class="page-header">
                        <h3 class="fw-bold mb-3">Gestión de Compras</h3>
                        <ul class="breadcrumbs mb-3">
                            <li class="nav-home">
                                <a href="<?= $base_path ?>index.php">
                                    <i class="icon-home"></i>
                                </a>
                            </li>
                            <li class="separator">
                                <i class="icon-arrow-right"></i>
                            </li>
                            <li class="nav-item">
                                <a href="#">Admin</a>
                            </li>
                            <li class="separator">
                                <i class="icon-arrow-right"></i>
                            </li>
                            <li class="nav-item">
                                <a href="#">Compras</a>
                            </li>
                        </ul>
                    </div>

                    <!-- Formulario de Compra -->
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
                                <div class="producto-liquido-info" id="campoLiquido" style="display: none;">
                                    <h6 class="mb-2">Información de Producto Líquido</h6>
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

                                <!-- Sección de Plazos -->
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
                                            <th>Total</th>
                                            <th>Estado</th>
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
                                        <div class="col-md-6">
                                            <strong>Usuario:</strong> <span id="detalleUsuario"></span>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Estado:</strong> <span id="detalleEstado"></span>
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

            <?php include '../includes/footer.php'; ?>
        </div>
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
            
            // Validaciones para Ecuador
            const validationsEcu = {            
                validarNumero: function(valor) {
                    return !isNaN(valor) && isFinite(valor) && valor > 0;
                },
                
                validarFecha: function(fecha) {
                    if (!fecha) return false;
                    const hoy = new Date();
                    const fechaCompra = new Date(fecha);
                    // No puede ser de hace más de 30 días ni futura
                    const diferenciaDias = (hoy - fechaCompra) / (1000 * 3600 * 24);
                    return diferenciaDias >= -1 && diferenciaDias <= 30;
                }
            };
            
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
                                    <td colspan="7" class="text-center text-danger">
                                        Error al cargar los datos. <button onclick="location.reload()" class="btn btn-sm btn-primary ms-2">Recargar</button>
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
                    order: [[1, 'desc']],
                    responsive: true
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
                            <div class="flex-grow-1 me-2">
                                <input type="date" class="form-control form-control-sm mb-2" id="plazoFecha-${plazoId}" placeholder="Fecha">
                            </div>
                            <div class="flex-grow-1 me-2">
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
                        showNotification('error', 'Error al cargar proveedores');
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
                        showNotification('error', 'Error al cargar productos');
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
                    const producto = productos.find(p => p.ProductoID == productoID);
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
                    const producto = productos.find(p => p.ProductoID == productoID);
                    if (producto && producto.es_liquido == 1) {
                        actualizarCantidadLitros();
                    }
                }
            });

            // Agregar producto a la lista - VERSIÓN CORREGIDA
            $('#agregarProductoBtn').click(function() {
                const productoID = $('#producto').val();
                let cantidad = parseFloat($('#cantidad').val());
                const precioUnitario = parseFloat($('#precioUnitario').val());
                
                // Deshabilitar el botón para evitar doble clic
                $(this).prop('disabled', true);
                
                // Validaciones
                if (!productoID) {
                    showNotification('error', 'Seleccione un producto');
                    $(this).prop('disabled', false);
                    return;
                }
                
                if (isNaN(cantidad) || cantidad <= 0) {
                    showNotification('error', 'Ingrese una cantidad válida');
                    $(this).prop('disabled', false);
                    return;
                }
                
                if (isNaN(precioUnitario) || precioUnitario <= 0) {
                    showNotification('error', 'Ingrese un precio unitario válido');
                    $(this).prop('disabled', false);
                    return;
                }

                const producto = productos.find(p => p.ProductoID == productoID);
                if (!producto) {
                    showNotification('error', 'Producto no encontrado');
                    $(this).prop('disabled', false);
                    return;
                }

                const index = productosSeleccionados.findIndex(p => p.productoID == productoID);
                
                if (index >= 0) {
                    // Verificar que el precio coincida con el existente
                    if (productosSeleccionados[index].precio !== precioUnitario) {
                        showNotification('error', 'El precio unitario no coincide con el producto existente');
                        $(this).prop('disabled', false);
                        return;
                    }
                    
                    // Actualizar cantidad y subtotal manteniendo el precio original
                    productosSeleccionados[index].cantidad += cantidad;
                    productosSeleccionados[index].subtotal = productosSeleccionados[index].cantidad * productosSeleccionados[index].precio;
                    
                    // Actualizar litros si es líquido
                    if (productosSeleccionados[index].esLiquido) {
                        productosSeleccionados[index].cantidadLitros = productosSeleccionados[index].cantidad * productosSeleccionados[index].capacidadEnvase;
                    }
                } else {
                    // Agregar nuevo producto
                    productosSeleccionados.push({
                        productoID: productoID,
                        nombre: producto.Nombre,
                        cantidad: cantidad,
                        precio: precioUnitario,
                        subtotal: cantidad * precioUnitario,
                        esLiquido: producto.es_liquido == 1,
                        capacidadEnvase: producto.capacidad_envase,
                        cantidadLitros: producto.es_liquido == 1 ? cantidad * producto.capacidad_envase : 0
                    });
                }

                actualizarListaProductos();
                calcularTotal();
                resetearCamposProducto();
                
                // Reactivar el botón
                $(this).prop('disabled', false);
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
                        cantidadDisplay = `${producto.cantidad} envases (${producto.cantidadLitros.toFixed(3)} L)`;
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

            // Consolidar productos para evitar duplicados
            function consolidarProductos() {
                const productosConsolidados = {};
                
                productosSeleccionados.forEach(p => {
                    if (productosConsolidados[p.productoID]) {
                        productosConsolidados[p.productoID].cantidad += p.cantidad;
                    } else {
                        productosConsolidados[p.productoID] = {
                            productoID: p.productoID,
                            cantidad: p.cantidad,
                            precio: p.precio
                        };
                    }
                });
                
                return Object.values(productosConsolidados);
            }

            // Eliminar todos los event listeners previos
            $('#registrarCompraBtn').off('click');
            
            // Agregar el event listener una sola vez
            $('#registrarCompraBtn').on('click', function() {
                const esPlazo = $('#esPlazo').is(':checked');
                const total = calcularTotal();
                
                if (!validarFormularioCompra()) return;

                const compraData = {
                    ruc: $('#proveedor').val(),
                    numFactura: $('#numeroFactura').val(),
                    fecha: $('#fechaCompra').val(),
                    total: total,
                    usuarioID: usuarioID,
                    productos: consolidarProductos(),
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
                const errores = [];
                
                // Validar fecha
                const fecha = $('#fechaCompra').val();
                if(!fecha) {
                    errores.push('Ingrese la fecha de compra');
                } else if(!validationsEcu.validarFecha(fecha)) {
                    errores.push('La fecha de compra debe estar entre hoy y 30 días atrás');
                }
                
                // Validar productos
                if(productosSeleccionados.length === 0) {
                    errores.push('Agregue al menos un producto');
                }
                
                if(errores.length > 0) {
                    showNotification('error', errores.join('<br>'));
                    return false;
                }
                
                return true;
            }

            function validarPlazos(total) {
                const fechaPrimerPago = $('#fechaPrimerPago').val();
                const montoPrimerPago = parseFloat($('#montoPrimerPago').val());
                
                const errores = [];
                
                if(!fechaPrimerPago) {
                    errores.push('Ingrese la fecha del primer pago');
                } else {
                    const hoy = new Date().toISOString().split('T')[0];
                    if(fechaPrimerPago < hoy) {
                        errores.push('La fecha del primer pago no puede ser anterior a hoy');
                    }
                }
                
                if(isNaN(montoPrimerPago) || montoPrimerPago <= 0) {
                    errores.push('Ingrese un monto válido para el primer pago');
                }
                
                if($('#abonoInicial').val() === '1') {
                    const montoAbono = parseFloat($('#montoAbonoInicial').val());
                    if(isNaN(montoAbono) || montoAbono <= 0) {
                        errores.push('Ingrese un monto válido para el abono inicial');
                    }
                    
                    if(montoAbono >= total) {
                        errores.push('El abono inicial no puede ser mayor o igual al total');
                    }
                }
                
                // Validar plazos adicionales
                let totalPlazos = montoPrimerPago || 0;
                const plazos = procesarPlazosAdicionales();
                
                plazos.forEach((plazo, index) => {
                    if(!plazo.fecha) {
                        errores.push(`El plazo ${index + 1} debe tener una fecha válida`);
                    }
                    if(isNaN(plazo.monto) || plazo.monto <= 0) {
                        errores.push(`El plazo ${index + 1} debe tener un monto válido`);
                    }
                    totalPlazos += plazo.monto;
                });
                
                // Verificar que la suma de plazos coincida con el total (considerando abono inicial)
                const abonoInicial = ($('#abonoInicial').val() === '1') ? parseFloat($('#montoAbonoInicial').val()) || 0 : 0;
                
                if(Math.abs((totalPlazos + abonoInicial) - total) > 0.01) {
                    errores.push(`La suma de los plazos ($${(totalPlazos + abonoInicial).toFixed(2)}) no coincide con el total ($${total.toFixed(2)})`);
                }
                
                if(errores.length > 0) {
                    showNotification('error', errores.join('<br>'));
                    return false;
                }
                
                return true;
            }

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
                                            cargarProductos();
                                        });
                                    } else {
                                        showNotification('error', res.message || 'Error al registrar la compra');
                                    }
                                } catch (e) {
                                    showNotification('error', 'Error al procesar la respuesta del servidor');
                                }
                            },
                            error: function(xhr, status, error) {
                                showNotification('error', 'Error al registrar compra: ' + error);
                            }
                        });
                    }
                });
            }

            // Función para ver detalle de compra
            window.verDetalleCompra = function(compraID) {
                $('#detalleCompraModal').modal('show');
                $('#detalleCompraID').text(compraID);
                
                const tbody = $('#detalleCompraTable tbody');
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
                        $('#detalleEstado').html(`<span class="badge badge-${compra.estado_pago === 'completo' ? 'success' : 'warning'}">${compra.estado_pago}</span>`);
                        
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

            // Validación en tiempo real
            $('#fechaCompra').change(function() {
                const fecha = $(this).val();
                if (fecha && !validationsEcu.validarFecha(fecha)) {
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            // Inicialización
            $(function() {
                initDataTable();
                cargarProveedores();
                cargarProductos();
                $('#fechaCompra').val(new Date().toISOString().split('T')[0]);
            });
        });
    </script>
</body>
</html>