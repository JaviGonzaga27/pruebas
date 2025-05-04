<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/mecanica2/auth.php';

// Configuración de la página
$title = 'Registro de Compras';
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
    
    /* Mejoras de estilo */
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 2px solid #eaeaea;
    }
    .card-title {
        font-weight: 600;
        color: #2c3e50;
    }
    .form-group label {
        font-weight: 500;
        margin-bottom: 6px;
        color: #34495e;
    }
    .btn-primary, .btn-success {
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    .btn-primary:hover, .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.02);
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
    'js/compras.js'
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
                            <input type="text" class="form-control" id="usuario"
                                value="<?php echo $_SESSION['usuario_nombre'] ?? 'Administrador'; ?>" readonly>
                        </div>
                    </div>
                </div>

                <h5 class="mt-4 mb-3">Agregar Productos</h5>
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
                            <input type="number" step="0.01" class="form-control" id="precioUnitario" required>
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

                <div class="card mb-4 mt-4">
                    <div class="card-header">
                        <h5 class="card-title">Productos Seleccionados</h5>
                    </div>
                    <div class="card-body">
                        <div id="productosSeleccionados" class="mb-3">
                            <p class="text-muted text-center">No hay productos agregados</p>
                        </div>
                        <div class="total-container">
                            <h4 class="text-end">Total Compra: <span id="totalCompra">$0.00</span></h4>
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

    <?php
    // Incluir el pie de página del template
    include '../includes/template_footer.php';
    ?>