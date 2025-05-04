<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/mecanica2/auth.php';

// Configuración de la página
$title = 'Gestión de Productos';
$config = [
    'base_path' => '/mecanica2/',
    'assets_path' => '/mecanica2/assets/',
    'site_name' => 'Sistema de Taller Mecánico'
];

// CSS adicional específico para esta página
$inline_css = "
    .badge-producto {
        font-size: 0.9em;
        padding: 5px 10px;
        border-radius: 4px;
    }
    .table-productos th {
        background-color: #f8f9fa;
        font-weight: 600;
    }
    .table-productos td {
        vertical-align: middle;
    }
    .action-buttons .btn {
        padding: 0.375rem 0.5rem;
        margin: 0 2px;
    }
    .modal-producto .modal-header {
        background-color: #4e73df;
        color: white;
    }
    .scrollable-modal-body {
        max-height: 70vh;
        overflow-y: auto;
    }
    #productosTable .dropdown-toggle::after {
        display: none; /* Oculta la flecha del dropdown */
    }
    #productosTable .dropdown-menu {
        min-width: 150px;
    }
    #productosTable .dropdown-item {
        cursor: pointer;
        padding: 0.25rem 1rem;
    }
    #productosTable .dropdown-item i {
        width: 20px;
        text-align: center;
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
    'js/productos.js'
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
                <a href="#">Productos</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Productos</h4>
                        <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#addProductoModal">
                            <i class="fa fa-plus"></i>
                            Agregar Producto
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Modal para Agregar Producto -->
                    <div class="modal fade modal-producto" id="addProductoModal" tabindex="-1" aria-labelledby="addProductoModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addProductoModalLabel">
                                        <span class="fw-mediumbold">Nuevo</span>
                                        <span class="fw-light">Producto</span>
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body scrollable-modal-body">
                                    <form id="addProductoForm" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>ID del Producto <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="productoID" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Nombre <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="nombre" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Marca</label>
                                                    <input type="text" class="form-control" id="marca">
                                                </div>
                                                <div class="form-group">
                                                    <label>Descripción</label>
                                                    <textarea class="form-control" id="descripcion" rows="2"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Precio de Compra <span class="text-danger">*</span></label>
                                                    <input type="number" step="0.01" class="form-control" id="precioCompra" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Precio de Venta <span class="text-danger">*</span></label>
                                                    <input type="number" step="0.01" class="form-control" id="precioVenta" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Stock <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control" id="stock" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Stock Mínimo</label>
                                                    <input type="number" class="form-control" id="stockMinimo" value="5">
                                                </div>
                                                <div class="form-group">
                                                    <label>Estado <span class="text-danger">*</span></label>
                                                    <select class="form-control" id="estado" required>
                                                        <option value="Activo">Activo</option>
                                                        <option value="Inactivo">Inactivo</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Compatibilidad</label>
                                                    <input type="text" class="form-control" id="compatibilidad">
                                                </div>
                                                <div class="form-group">
                                                    <label>Fecha de Ingreso <span class="text-danger">*</span></label>
                                                    <input type="date" class="form-control" id="fechaIngreso" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Categoría <span class="text-danger">*</span></label>
                                                    <select class="form-control" id="categoriaID" required></select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Proveedor <span class="text-danger">*</span></label>
                                                    <select class="form-control" id="ruc" required></select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Código de Barras</label>
                                                    <input type="text" class="form-control" id="codigoBarras">
                                                </div>
                                                <div class="form-group">
                                                    <label>Ubicación en Almacén</label>
                                                    <input type="text" class="form-control" id="ubicacionAlmacen">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input class="form-check-input" type="checkbox" id="tieneGarantia">
                                                            <span class="form-check-sign">Tiene Garantía</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Días de Garantía</label>
                                                    <input type="number" class="form-control" id="diasGarantia" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Imagen del Producto</label>
                                            <input type="file" class="form-control" id="imagen" accept="image/*">
                                            <small class="text-muted">Formatos aceptados: JPG, PNG. Tamaño máximo: 2MB</small>
                                        </div>

                                        <!-- Checkbox para líquido -->
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="esLiquido" name="esLiquido">
                                                    <label class="form-check-label" for="esLiquido">¿Es un producto líquido (aceite)?</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Campos de líquido - Ocultos inicialmente -->
                                        <div id="liquidFields" style="display: none;">
                                            <div class="row mt-3">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Tipo de Aceite</label>
                                                        <input type="text" class="form-control" id="tipoAceite" name="tipoAceite">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Capacidad del Envase (litros)</label>
                                                        <input type="number" step="0.001" class="form-control" id="capacidadEnvase" name="capacidadEnvase" value="0">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Contenido Actual (litros)</label>
                                                        <input type="number" step="0.001" class="form-control" id="contenidoActual" name="contenidoActual" value="0">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" id="addProductoBtn" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Guardar
                                    </button>
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                        <i class="fas fa-times"></i> Cancelar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal para Editar Producto -->
                    <div class="modal fade modal-producto" id="editProductoModal" tabindex="-1" aria-labelledby="editProductoModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="editProductoModalLabel">
                                        <span class="fw-mediumbold">Editar</span>
                                        <span class="fw-light">Producto</span>
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body scrollable-modal-body">
                                    <form id="editProductoForm" enctype="multipart/form-data">
                                        <input type="hidden" id="editProductoID">
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Nombre <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="editNombre" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Marca</label>
                                                    <input type="text" class="form-control" id="editMarca">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Descripción</label>
                                                    <textarea class="form-control" id="editDescripcion" rows="2"></textarea>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Precio Compra <span class="text-danger">*</span></label>
                                                            <div class="input-group">
                                                                <span class="input-group-text">$</span>
                                                                <input type="number" step="0.01" class="form-control" id="editPrecioCompra" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Precio Venta <span class="text-danger">*</span></label>
                                                            <div class="input-group">
                                                                <span class="input-group-text">$</span>
                                                                <input type="number" step="0.01" class="form-control" id="editPrecioVenta" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Stock <span class="text-danger">*</span></label>
                                                            <input type="number" class="form-control" id="editStock" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Stock Mínimo</label>
                                                            <input type="number" class="form-control" id="editStockMinimo" value="5">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Estado <span class="text-danger">*</span></label>
                                                    <select class="form-select" id="editEstado" required>
                                                        <option value="Activo">Activo</option>
                                                        <option value="Inactivo">Inactivo</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Fecha Ingreso <span class="text-danger">*</span></label>
                                                    <input type="date" class="form-control" id="editFechaIngreso" required>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Categoría <span class="text-danger">*</span></label>
                                                    <select class="form-select" id="editCategoriaID" required></select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Proveedor <span class="text-danger">*</span></label>
                                                    <select class="form-select" id="editRuc" required></select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Compatibilidad</label>
                                                    <input type="text" class="form-control" id="editCompatibilidad">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Código de Barras</label>
                                                    <input type="text" class="form-control" id="editCodigoBarras">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Ubicación en Almacén</label>
                                                    <input type="text" class="form-control" id="editUbicacionAlmacen">
                                                </div>
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="editTieneGarantia">
                                                        <label class="form-check-label" for="editTieneGarantia">Tiene Garantía</label>
                                                    </div>
                                                    <div class="mt-2" id="editGarantiaGroup" style="display: none;">
                                                        <label class="form-label">Días de Garantía</label>
                                                        <input type="number" class="form-control" id="editDiasGarantia">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group mt-3">
                                            <label class="form-label">Imagen del Producto</label>
                                            <input type="file" class="form-control" id="editImagen" accept="image/*">
                                            <small class="text-muted">Formatos aceptados: JPG, PNG. Tamaño máximo: 2MB</small>
                                            <div id="imagenPreview" class="mt-3 text-center"></div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="editEsLiquido">
                                                    <label class="form-check-label" for="editEsLiquido">¿Es un producto líquido (aceite)?</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="editLiquidFields" style="display: none;">
                                            <div class="row mt-3">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Tipo de Aceite</label>
                                                        <input type="text" class="form-control" id="editTipoAceite">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Capacidad del Envase (litros)</label>
                                                        <input type="number" step="0.01" class="form-control" id="editCapacidadEnvase">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Contenido Actual (litros)</label>
                                                        <input type="number" step="0.01" class="form-control" id="editContenidoActual">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times"></i> Cancelar
                                    </button>
                                    <button type="button" id="editProductoBtn" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Guardar Cambios
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal para Ver Detalles del Producto -->
                    <div class="modal fade" id="viewProductoModal" tabindex="-1" aria-labelledby="viewProductoModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="viewProductoModalLabel">Detalles del Producto</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- El contenido se cargará dinámicamente con JavaScript -->
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de Productos -->
                    <div class="table-responsive">
                        <table id="productosTable" class="table table-striped table-hover table-productos">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Precio Compra</th>
                                    <th>Precio Venta</th>
                                    <th>Ganancia</th>
                                    <th>Stock</th>
                                    <th>Estado</th>
                                    <th>Categoría</th>
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