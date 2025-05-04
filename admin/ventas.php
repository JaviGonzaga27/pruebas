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
<!-- En el head de ventas.php -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- Antes del cierre del body en ventas.php -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
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
    </style>

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
  </head>
  <body>
    <div class="wrapper">
     <!-- Sidebar -->
     <?php include '../navbar.php'; ?>
      <!-- End Sidebar -->
      
      <div class="main-panel">
        <?php include '../includes/header.php'; ?>

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
                  <a href="#">Proveedores</a>
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

 <!-- Script para manejar la tabla dinámica -->
   <!-- Script para manejar la lógica de ventas -->
   <script>
    // En el script principal, agregar:
let usuarioID = <?php echo $_SESSION['usuario_id'] ?? 1; ?>;

        $(document).ready(function() {
            // Variables globales
            let productosSeleccionados = [];
            let tieneIVA = true;
            let porcentajeIVA = 15.00;
            let descuentoGeneral = 0;
            
            // Configuración de DataTables para la tabla de ventas
            let ventasTable = $('#ventasTable').DataTable({
                ajax: {
                    url: 'crud_ventas.php?action=get_ventas',
                    dataSrc: ''
                },
                columns: [
                    { data: 'VentaID' },
                    { 
                        data: 'FechaVenta',
                        render: function(data) {
                            return new Date(data).toLocaleDateString('es-ES');
                        }
                    },
                    { data: 'ClienteNombre' },
                    { 
                        data: 'Subtotal',
                        render: function(data) {
                            return '$' + parseFloat(data).toFixed(2);
                        }
                    },
                    { 
                        data: null,
                        render: function(data) {
                            const iva = data.TieneIVA ? (data.Subtotal * data.PorcentajeIVA / 100) : 0;
                            return '$' + iva.toFixed(2);
                        }
                    },
                    { 
                        data: 'TotalVenta',
                        render: function(data) {
                            return '$' + parseFloat(data).toFixed(2);
                        }
                    },
                    {
                        data: null,
                        render: function(data) {
                            return `
                                <button class="btn btn-sm btn-info" onclick="verDetalleVenta(${data.VentaID})">
                                    <i class="fas fa-eye"></i> Ver Detalle
                                </button>
                            `;
                        }
                    }
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                }
            });

            // Cargar clientes y productos al iniciar
            cargarClientes();
            cargarProductos();

            // Establecer fecha actual por defecto
            $('#fechaVenta').val(new Date().toISOString().split('T')[0]);

            // Evento cuando se selecciona un producto
            $('#producto').change(function() {
                const productoID = $(this).val();
                
                if (productoID) {
                    const producto = productos.find(p => p.ProductoID == productoID);
                    
                    if (producto) {
                        $('#precioUnitario').val(parseFloat(producto.PrecioVenta).toFixed(2));
                        $('#cantidad').val(1);
                        $('#cantidad').focus();
                        $('#cantidad').attr('max', producto.Stock);
                    } else {
                        $('#precioUnitario').val('');
                    }
                } else {
                    $('#precioUnitario').val('');
                }
            });

            // Manejar cambios en configuración de IVA y descuentos
            $('#tieneIVA').change(function() {
                tieneIVA = $(this).is(':checked');
                calcularTotales();
            });

            $('#porcentajeIVA').change(function() {
                porcentajeIVA = parseFloat($(this).val()) || 0;
                $('#porcentajeIVALabel').text(porcentajeIVA.toFixed(2));
                calcularTotales();
            });

            $('#descuentoGeneral').change(function() {
                descuentoGeneral = parseFloat($(this).val()) || 0;
                $('#descuentoGeneralVenta').text(descuentoGeneral + '%');
                calcularTotales();
            });

            // Agregar producto a la lista
            $('#agregarProductoBtn').click(function() {
                const productoID = $('#producto').val();
                const cantidad = parseInt($('#cantidad').val());
                const descuentoProducto = parseFloat($('#descuentoProducto').val()) || 0;
                
                if (!productoID || !cantidad || cantidad < 1) {
                    alert('Por favor complete todos los campos del producto');
                    return;
                }

                const producto = productos.find(p => p.ProductoID == productoID);
                
                // Validar stock disponible
                if (cantidad > producto.Stock) {
                    alert('No hay suficiente stock disponible');
                    return;
                }

                const precioUnitario = parseFloat($('#precioUnitario').val());
                
                // Verificar si el producto ya está en la lista
                const index = productosSeleccionados.findIndex(p => p.productoID == productoID);
                
                if (index >= 0) {
                    // Actualizar cantidad si ya existe
                    productosSeleccionados[index].cantidad += cantidad;
                } else {
                    // Agregar nuevo producto
                    productosSeleccionados.push({
                        productoID: productoID,
                        nombre: producto.Nombre,
                        cantidad: cantidad,
                        precio: precioUnitario,
                        descuentoProducto: descuentoProducto
                    });
                }

                actualizarListaProductos();
                calcularTotales();
                resetearCamposProducto();
            });

            // Registrar venta
            $('#registrarVentaBtn').click(function() {
    const clienteID = $('#cliente').val();
    const fecha = $('#fechaVenta').val();
    const metodoPagoID = $('#metodoPago').val();
    
    if (!clienteID || !fecha || !metodoPagoID || productosSeleccionados.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Por favor complete todos los campos requeridos'
        });
        return;
    }

    const { total } = calcularTotales();

    const ventaData = {
        clienteID: clienteID,
        fecha: fecha,
        metodoPagoID: metodoPagoID,
        tieneIVA: tieneIVA,
        porcentajeIVA: porcentajeIVA,
        descuentoGeneral: descuentoGeneral,
        usuarioID: usuarioID,
        productos: productosSeleccionados.map(p => ({
            productoID: p.productoID,
            cantidad: p.cantidad,
            precio: p.precio,
            descuentoProducto: p.descuentoProducto
        }))
    };

    $.ajax({
        url: 'crud_ventas.php?action=create_venta',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(ventaData),
        success: function(response) {
    const res = JSON.parse(response);
    if (res.success) {
        Swal.fire({
            icon: 'success',
            title: 'Venta registrada',
            text: `Venta registrada correctamente. ID: ${res.ventaID}`,
            showConfirmButton: false,
            timer: 2000
        }).then(() => {
            // Recargar la página después de que se cierre el alert
            location.reload();
        });
        resetearFormulario();
        ventasTable.ajax.reload();
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: res.error
        });
    }
},
        error: function(xhr, status, error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al registrar la venta: ' + error
            });
        }
    });
});

            // Función para cargar clientes
            function cargarClientes() {
                $.ajax({
                    url: 'crud_ventas.php?action=get_clientes',
                    method: 'GET',
                    success: function(data) {
                        try {
                            const clientes = JSON.parse(data);
                            const select = $('#cliente');
                            select.empty();
                            select.append('<option value="">Seleccione un cliente</option>');
                            
                            clientes.forEach(cliente => {
                                select.append(`<option value="${cliente.ClienteID}">${cliente.Nombre} (${cliente.Cedula})</option>`);
                            });
                        } catch (e) {
                            console.error("Error al cargar clientes:", e);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error al obtener clientes:", error);
                    }
                });
            }

            // Función para cargar productos
            function cargarProductos() {
                $.ajax({
                    url: 'crud_ventas.php?action=get_productos',
                    method: 'GET',
                    success: function(data) {
                        try {
                            productos = JSON.parse(data);
                            const select = $('#producto');
                            select.empty();
                            select.append('<option value="">Seleccione un producto</option>');
                            
                            productos.forEach(producto => {
                                select.append(`<option value="${producto.ProductoID}">${producto.Nombre} (Stock: ${producto.Stock})</option>`);
                            });
                        } catch (e) {
                            console.error("Error al cargar productos:", e);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error al obtener productos:", error);
                    }
                });
            }
// Agregar función para cargar métodos de pago
function cargarMetodosPago() {
    $.ajax({
        url: 'crud_ventas.php?action=get_metodos_pago',
        method: 'GET',
        success: function(data) {
            try {
                const metodos = JSON.parse(data);
                const select = $('#metodoPago');
                select.empty();
                select.append('<option value="">Seleccione un método</option>');
                
                metodos.forEach(metodo => {
                    select.append(`<option value="${metodo.MetodoPagoID}">${metodo.Nombre}</option>`);
                });
            } catch (e) {
                console.error("Error al cargar métodos de pago:", e);
            }
        },
        error: function(xhr, status, error) {
            console.error("Error al obtener métodos de pago:", error);
        }
    });
}

// Llamar a esta función al cargar la página
cargarMetodosPago();

            // Función para actualizar la lista visual de productos
            function actualizarListaProductos() {
                const container = $('#productosSeleccionados');
                container.empty();

                if (productosSeleccionados.length === 0) {
                    container.html('<p class="text-muted text-center">No hay productos agregados</p>');
                    $('#registrarVentaBtn').prop('disabled', true);
                    return;
                }

                const table = $('<table class="table table-sm"></table>');
                const thead = $(`
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>P. Unitario</th>
                            <th>Desc. (%)</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                `);
                const tbody = $('<tbody></tbody>');
                
                table.append(thead, tbody);
                container.append(table);

                productosSeleccionados.forEach((producto, index) => {
                    const subtotal = producto.cantidad * producto.precio * (1 - producto.descuentoProducto/100);
                    
                    const row = $(`
                        <tr>
                            <td>${producto.nombre}</td>
                            <td>${producto.cantidad}</td>
                            <td>$${producto.precio.toFixed(2)}</td>
                            <td>${producto.descuentoProducto}%</td>
                            <td>$${subtotal.toFixed(2)}</td>
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

                $('#registrarVentaBtn').prop('disabled', false);
            }

            // Función para calcular totales
            function calcularTotales() {
                // 1. Calcular subtotal con descuentos por producto
                let subtotal = 0;
                
                productosSeleccionados.forEach(producto => {
                    const precioConDescuento = producto.precio * (1 - producto.descuentoProducto/100);
                    subtotal += producto.cantidad * precioConDescuento;
                });
                
                // 2. Calcular IVA si aplica
                let iva = 0;
                if (tieneIVA) {
                    iva = subtotal * (porcentajeIVA/100);
                }
                
                // 3. Calcular total con descuento general (después de IVA)
                const totalConIVA = subtotal + iva;
                const total = totalConIVA * (1 - descuentoGeneral/100);
                
                // Actualizar UI
                $('#subtotalVenta').text('$' + subtotal.toFixed(2));
                $('#ivaVenta').text('$' + iva.toFixed(2));
                $('#totalAntesDescuento').text('$' + totalConIVA.toFixed(2));
                $('#totalVenta').text('$' + total.toFixed(2));
                
                return {
                    subtotal: subtotal,
                    iva: iva,
                    total: total
                };
            }

            // Función para eliminar producto de la lista
            function eliminarProductoSeleccionado(index) {
                if (!confirm('¿Está seguro de eliminar este producto de la lista?')) {
                    return;
                }
                
                productosSeleccionados.splice(index, 1);
                actualizarListaProductos();
                calcularTotales();
            }

            // Función para resetear campos de producto
            function resetearCamposProducto() {
                $('#producto').val('').trigger('change');
                $('#cantidad').val(1);
                $('#descuentoProducto').val(0);
            }

            // Función para resetear todo el formulario
            function resetearFormulario() {
                productosSeleccionados = [];
                $('#productosSeleccionados').html('<p class="text-muted text-center">No hay productos agregados</p>');
                $('#ventaForm')[0].reset();
                $('#fechaVenta').val(new Date().toISOString().split('T')[0]);
                $('#tieneIVA').prop('checked', true);
                $('#porcentajeIVA').val('15.00');
                $('#descuentoGeneral').val('0');
                $('#porcentajeIVALabel').text('15.00');
                $('#descuentoGeneralVenta').text('0%');
                $('#registrarVentaBtn').prop('disabled', true);
                calcularTotales();
            }
        });

        // Función para ver detalle de venta (global para que DataTables pueda llamarla)
        function verDetalleVenta(ventaID) {
    $('#detalleVentaModal').modal('show');
    $('#detalleVentaID').text(ventaID);
    
    // Limpiar y mostrar loader
    const tbody = $('#detalleVentaTable tbody');
    tbody.html('<tr><td colspan="5" class="text-center"><div class="spinner-border text-primary"></div></td></tr>');
    
    $.ajax({
        url: 'crud_ventas.php?action=get_detalle&ventaID=' + ventaID,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log("Respuesta recibida:", response);
            
            if (!response || !response.success) {
                throw new Error(response?.error || "Respuesta inválida del servidor");
            }

            const { venta, detalles } = response;
            
            // Mostrar información general
            $('#detalleTieneIVA').text(venta.TieneIVA ? 'Sí' : 'No');
            $('#detallePorcentajeIVA').text(venta.PorcentajeIVA + '%');
            $('#detalleDescuentoGeneral').text(venta.DescuentoGeneral + '%');
            
            // Mostrar productos
            tbody.empty();
            
            if (!detalles || detalles.length === 0) {
                tbody.append('<tr><td colspan="5" class="text-center text-muted">No se encontraron productos</td></tr>');
            } else {
                detalles.forEach(detalle => {
                    const subtotal = detalle.Cantidad * detalle.PrecioUnitario * (1 - detalle.DescuentoProducto/100);
                    
                    tbody.append(`
                        <tr>
                            <td>${detalle.ProductoNombre || 'Producto no disponible'}</td>
                            <td>${detalle.Cantidad}</td>
                            <td>$${detalle.PrecioUnitario.toFixed(2)}</td>
                            <td>${detalle.DescuentoProducto}%</td>
                            <td>$${subtotal.toFixed(2)}</td>
                        </tr>
                    `);
                });
            }
            
            // Mostrar totales
            $('#detalleSubtotal').text('$' + venta.Subtotal.toFixed(2));
            $('#detalleIVA').text('$' + (venta.TieneIVA ? (venta.Subtotal * venta.PorcentajeIVA / 100).toFixed(2) : '0.00'));
            $('#detalleTotalAntesDescuento').text('$' + (venta.Subtotal + (venta.TieneIVA ? venta.Subtotal * venta.PorcentajeIVA / 100 : 0)).toFixed(2));
            $('#detalleTotal').text('$' + venta.TotalVenta.toFixed(2));
        },
        error: function(xhr, status, error) {
            console.error("Error en la petición:", status, error, xhr.responseText);
            tbody.html(`
                <tr>
                    <td colspan="5" class="text-center text-danger">
                        Error al cargar los detalles. Consulte la consola para más información.
                    </td>
                </tr>
            `);
        }
    });
}
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
