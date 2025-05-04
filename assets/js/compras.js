// compras.js - Lógica de negocio para el módulo de compras (versión simplificada)

// Variables globales
let productosSeleccionados = [];
let productos = [];
let proveedores = [];
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
    // Destruir si ya existe
    if (comprasTable) {
        comprasTable.destroy();
        $('#comprasTable').empty(); // Limpiar contenido
    }
    comprasTable = $('#comprasTable').DataTable({
        ajax: {
            url: 'crud_compras.php?action=get_compras',
            dataSrc: '',
            error: function(xhr, error, thrown) {
                console.error('Error al cargar compras:', error);
                $('#comprasTable tbody').html(`
                    <tr>
                        <td colspan="6" class="text-center text-danger">
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
                data: null,
                render: function(data) {
                    return `
                        <button class="btn btn-sm btn-info" onclick="verDetalleCompra(${data.CompraID})">
                            <i class="fas fa-eye"></i> Detalle
                        </button>
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

// Cargar proveedores desde la API
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

// Cargar productos desde la API
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

// Manejar cambio de producto seleccionado
$(document).ready(function() {
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

    // Agregar producto a la lista
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

    // Registrar compra
    $('#registrarCompraBtn').click(function() {
        const total = calcularTotal();
        
        if (!validarFormularioCompra()) return;

        const compraData = {
            ruc: $('#proveedor').val(),
            numFactura: $('#numeroFactura').val(),
            fecha: $('#fechaCompra').val(),
            total: total,
            usuarioID: window.usuarioID || 1,
            productos: consolidarProductos()
        };
        
        registrarCompra(compraData);
    });
});

// Función para actualizar cantidad de litros
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

// Validar formulario de compra
function validarFormularioCompra() {
    const errores = [];
    
    // Validar proveedor
    if(!$('#proveedor').val()) {
        errores.push('Seleccione un proveedor');
    }
    
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

// Registrar compra
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
// Función para ver detalle de compra - Versión corregida
function verDetalleCompra(compraID) {
    $('#detalleCompraModal').modal('show');
    $('#detalleCompraID').text(compraID);
    
    const tbody = $('#detalleCompraTable tbody');
    tbody.html('<tr><td colspan="4" class="text-center"><div class="spinner-border text-primary"></div></td></tr>');
    
    $.ajax({
        url: 'crud_compras.php?action=get_detalle&compraID=' + compraID,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            try {
                // Verificar si la respuesta es válida
                if (!response || !response.success) {
                    throw new Error(response?.message || "Respuesta inválida del servidor");
                }

                const { compra, detalles } = response;
                
                // Verificar que los datos necesarios existan
                if (!compra || !detalles) {
                    throw new Error("Datos de compra incompletos");
                }
                
                // Actualizar información básica de la compra
                $('#detalleProveedor').text((compra.ProveedorNombre || 'N/A') + ' (' + (compra.RUC || 'N/A') + ')');
                $('#detalleFactura').text(compra.NumeroFactura || 'N/A');
                $('#detalleFecha').text(compra.FechaCompra ? new Date(compra.FechaCompra).toLocaleDateString('es-ES') : 'N/A');
                $('#detalleUsuario').text(compra.UsuarioNombre || 'Administrador');
                
                tbody.empty();
                
                if (!Array.isArray(detalles) || detalles.length === 0) {
                    tbody.append('<tr><td colspan="4" class="text-center text-muted">No se encontraron productos</td></tr>');
                } else {
                    let subtotal = 0;
                    detalles.forEach(detalle => {
                        // Validar datos del detalle
                        const cantidad = parseFloat(detalle.Cantidad) || 0;
                        const precioUnitario = parseFloat(detalle.PrecioUnitario) || 0;
                        const itemSubtotal = cantidad * precioUnitario;
                        subtotal += itemSubtotal;
                        
                        tbody.append(`
                            <tr>
                                <td>${detalle.ProductoNombre || 'Producto no disponible'}</td>
                                <td>${cantidad}</td>
                                <td>$${precioUnitario.toFixed(2)}</td>
                                <td>$${itemSubtotal.toFixed(2)}</td>
                            </tr>
                        `);
                    });
                    
                    $('#detalleSubtotal').text('$' + subtotal.toFixed(2));
                    $('#detalleTotal').text('$' + (parseFloat(compra.TotalCompra) || 0).toFixed(2));
                }
            } catch (e) {
                tbody.html(`
                    <tr>
                        <td colspan="4" class="text-center text-danger">
                            Error al procesar los datos: ${e.message}
                        </td>
                    </tr>
                `);
                console.error('Error al procesar detalle:', e);
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
            console.error('Error en la solicitud AJAX:', error);
        }
    });
}

// Resetear campos de producto
function resetearCamposProducto() {
    $('#producto').val('').trigger('change');
    $('#cantidad').val(1);
    $('#precioUnitario').val('');
    $('#campoLiquido').hide();
}

// Función para resetear todo el formulario
function resetearFormulario() {
    productosSeleccionados = [];
    $('#productosSeleccionados').html('<p class="text-muted text-center">No hay productos agregados</p>');
    $('#compraForm')[0].reset();
    $('#fechaCompra').val(new Date().toISOString().split('T')[0]);
    $('#registrarCompraBtn').prop('disabled', true);
    $('#totalCompra').text('$0.00');
}

// Mostrar notificación con SweetAlert2
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

$(document).ready(function() {
    // Inicializar elementos de la interfaz
    inicializarCompras();
});

function inicializarCompras() {
    // Iniciar DataTable
    initDataTable();
    
    // Cargar proveedores y productos
    cargarProveedores();
    cargarProductos();
    
    // Establecer fecha actual
    $('#fechaCompra').val(new Date().toISOString().split('T')[0]);
}

// Imprimir compra
function imprimirCompra() {
    const compraID = $('#detalleCompraID').text();
    window.open(`reporte_compra.php?compraID=${compraID}`, '_blank');
}