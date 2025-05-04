<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/mecanica2/auth.php';

// Configuración de la página
$title = 'Registro de Ventas';
$config = [
    'base_path' => '/mecanica2/',
    'assets_path' => '/mecanica2/assets/',
    'site_name' => 'Sistema de Taller Mecánico'
];

// CSS adicional específico para esta página
$inline_css = "
    .producto-item {
        border-bottom: 1px solid #eee;
        padding: 10px 0;
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
    .iva-descuento-section {
        background-color: #f1f8ff;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    .select2-container--default .select2-selection--single {
        height: 38px;
        padding: 5px;
        border: 1px solid #ced4da;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
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
    'js/ventas.js'
];

// Script inline (para pasar variables PHP a JavaScript)
$inline_script = "
    // Pasar el ID del usuario logueado al script JS
    const usuarioID = " . ($_SESSION['usuario_id'] ?? 1) . ";
";

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
                <a href="#">Ventas</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Registro de Ventas</h4>
            </div>
            <div class="card-body">
                <form id="ventaForm">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Cliente</label>
                                <select class="form-control" id="cliente" required>
                                    <option value="">Seleccione un cliente</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fecha de Venta</label>
                                <input type="date" class="form-control" id="fechaVenta" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Método de Pago</label>
                            <select class="form-control" id="metodoPago" required>
                                <option value="">Seleccione un método</option>
                            </select>
                        </div>
                    </div>
                    <!-- Sección de IVA y Descuento General -->
                    <div class="iva-descuento-section mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="tieneIVA" checked>
                                    <label class="form-check-label" for="tieneIVA">¿Aplica IVA?</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Porcentaje de IVA</label>
                                    <input type="number" step="0.01" class="form-control" id="porcentajeIVA" value="15.00" min="0">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Descuento General (%)</label>
                                    <input type="number" class="form-control" id="descuentoGeneral" min="0" max="100" value="0">
                                </div>
                            </div>
                        </div>
                    </div>

                    <h5>Agregar Productos</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
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
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Precio Unitario</label>
                                <input type="number" step="0.01" class="form-control" id="precioUnitario" readonly>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Descuento (%)</label>
                                <input type="number" class="form-control" id="descuentoProducto" min="0" max="100" value="0">
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" id="agregarProductoBtn" class="btn btn-primary w-100">
                                <i class="fas fa-plus"></i> Agregar
                            </button>
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
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5>Subtotal: <span id="subtotalVenta">$0.00</span></h5>
                                        <h5>IVA (<span id="porcentajeIVALabel">15.00</span>%): <span id="ivaVenta">$0.00</span></h5>
                                        <h5>Total antes de descuento: <span id="totalAntesDescuento">$0.00</span></h5>
                                        <h5>Descuento general: <span id="descuentoGeneralVenta">0%</span></h5>
                                    </div>
                                    <div class="col-md-6">
                                        <h4 class="text-end">Total: <span id="totalVenta">$0.00</span></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="button" id="registrarVentaBtn" class="btn btn-success btn-lg" disabled>
                            <i class="fas fa-save"></i> Registrar Venta
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabla de Ventas Registradas -->
        <div class="card mt-5">
            <div class="card-header">
                <h4 class="card-title">Historial de Ventas</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="ventasTable" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Fecha</th>
                                <th>Cliente</th>
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
    </div>

    <!-- Modal para Ver Detalle de Venta -->
    <div class="modal fade" id="detalleVentaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalle de Venta #<span id="detalleVentaID"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Aplica IVA:</strong> <span id="detalleTieneIVA"></span>
                        </div>
                        <div class="col-md-4">
                            <strong>% IVA:</strong> <span id="detallePorcentajeIVA"></span>
                        </div>
                        <div class="col-md-4">
                            <strong>Descuento general:</strong> <span id="detalleDescuentoGeneral"></span>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table id="detalleVentaTable" class="table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>P. Unitario</th>
                                    <th>Desc. (%)</th>
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
                                <h5>Total antes de descuento: <span id="detalleTotalAntesDescuento">$0.00</span></h5>
                            </div>
                            <div class="col-md-6">
                                <h4 class="text-end">Total: <span id="detalleTotal">$0.00</span></h4>
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
</div>

<?php
// Incluir el pie de página del template
include '../includes/template_footer.php';
?>