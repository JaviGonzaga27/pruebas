/**
 * Archivo JavaScript para manejar la lógica de ventas
 */

// Variables globales
let productos = [];
let productosSeleccionados = [];
let tieneIVA = true;
let porcentajeIVA = 15.00;
let descuentoGeneral = 0;
let ventasTable;

/**
 * Inicializar la página cuando se cargue el DOM
 */
$(document).ready(function () {
    // Configuración de DataTables para la tabla de ventas
    ventasTable = $('#ventasTable').DataTable({
        ajax: {
            url: 'crud_ventas.php?action=get_ventas',
            dataSrc: ''
        },
        columns: [
            { data: 'VentaID' },
            {
                data: 'FechaVenta',
                render: function (data) {
                    return new Date(data).toLocaleDateString('es-ES');
                }
            },
            { data: 'ClienteNombre' },
            {
                data: 'Subtotal',
                render: function (data) {
                    return '$' + parseFloat(data).toFixed(2);
                }
            },
            {
                data: null,
                render: function (data) {
                    const iva = data.TieneIVA ? (data.Subtotal * data.PorcentajeIVA / 100) : 0;
                    return '$' + iva.toFixed(2);
                }
            },
            {
                data: 'TotalVenta',
                render: function (data) {
                    return '$' + parseFloat(data).toFixed(2);
                }
            },
            {
                data: null,
                render: function (data) {
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

    // Cargar datos iniciales
    cargarClientes();
    cargarProductos();
    cargarMetodosPago();

    // Establecer fecha actual por defecto
    $('#fechaVenta').val(new Date().toISOString().split('T')[0]);

    // Configurar eventos
    configurarEventos();
});

/**
 * Configurar todos los eventos de la página
 */
function configurarEventos() {
    // Evento cuando se selecciona un producto
    $('#producto').change(function () {
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
    $('#tieneIVA').change(function () {
        tieneIVA = $(this).is(':checked');
        calcularTotales();
    });

    $('#porcentajeIVA').change(function () {
        porcentajeIVA = parseFloat($(this).val()) || 0;
        $('#porcentajeIVALabel').text(porcentajeIVA.toFixed(2));
        calcularTotales();
    });

    $('#descuentoGeneral').change(function () {
        descuentoGeneral = parseFloat($(this).val()) || 0;
        $('#descuentoGeneralVenta').text(descuentoGeneral + '%');
        calcularTotales();
    });

    // Agregar producto a la lista
    $('#agregarProductoBtn').click(function () {
        agregarProducto();
    });

    // Registrar venta
    $('#registrarVentaBtn').click(function () {
        registrarVenta();
    });
}

/**
 * Cargar lista de clientes desde el servidor
 */
function cargarClientes() {
    $.ajax({
        url: 'crud_ventas.php?action=get_clientes',
        method: 'GET',
        success: function (data) {
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
        error: function (xhr, status, error) {
            console.error("Error al obtener clientes:", error);
        }
    });
}

/**
 * Cargar lista de productos desde el servidor
 */
function cargarProductos() {
    $.ajax({
        url: 'crud_ventas.php?action=get_productos',
        method: 'GET',
        success: function (data) {
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
        error: function (xhr, status, error) {
            console.error("Error al obtener productos:", error);
        }
    });
}

/**
 * Cargar métodos de pago desde el servidor
 */
function cargarMetodosPago() {
    $.ajax({
        url: 'crud_ventas.php?action=get_metodos_pago',
        method: 'GET',
        success: function (data) {
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
        error: function (xhr, status, error) {
            console.error("Error al obtener métodos de pago:", error);
        }
    });
}

/**
 * Agregar un producto a la lista de productos seleccionados
 */
function agregarProducto() {
    const productoID = $('#producto').val();
    const cantidad = parseInt($('#cantidad').val());
    const descuentoProducto = parseFloat($('#descuentoProducto').val()) || 0;

    if (!productoID || !cantidad || cantidad < 1) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Por favor complete todos los campos del producto'
        });
        return;
    }

    const producto = productos.find(p => p.ProductoID == productoID);

    // Validar stock disponible
    if (cantidad > producto.Stock) {
        Swal.fire({
            icon: 'warning',
            title: 'Stock insuficiente',
            text: 'No hay suficiente stock disponible'
        });
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
}

/**
 * Actualizar la lista visual de productos en la interfaz
 */
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
        const subtotal = producto.cantidad * producto.precio * (1 - producto.descuentoProducto / 100);

        const row = $(`
            <tr>
                <td>${producto.nombre}</td>
                <td>${producto.cantidad}</td>
                <td>$${producto.precio.toFixed(2)}</td>
                <td>${producto.descuentoProducto}%</td>
                <td>$${subtotal.toFixed(2)}</td>
                <td class="text-end">
                    <button class="btn btn-sm btn-danger btn-eliminar-producto" data-index="${index}">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `);

        tbody.append(row);
    });

    // Configurar evento para los botones de eliminar
    $('.btn-eliminar-producto').click(function () {
        const index = $(this).data('index');
        eliminarProductoSeleccionado(index);
    });

    $('#registrarVentaBtn').prop('disabled', false);
}

/**
 * Calcular subtotales, IVA y total de la venta
 */
function calcularTotales() {
    // 1. Calcular subtotal con descuentos por producto
    let subtotal = 0;

    productosSeleccionados.forEach(producto => {
        const precioConDescuento = producto.precio * (1 - producto.descuentoProducto / 100);
        subtotal += producto.cantidad * precioConDescuento;
    });

    // 2. Calcular IVA si aplica
    let iva = 0;
    if (tieneIVA) {
        iva = subtotal * (porcentajeIVA / 100);
    }

    // 3. Calcular total con descuento general (después de IVA)
    const totalConIVA = subtotal + iva;
    const total = totalConIVA * (1 - descuentoGeneral / 100);

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

/**
 * Eliminar un producto de la lista
 */
function eliminarProductoSeleccionado(index) {
    Swal.fire({
        title: '¿Está seguro?',
        text: "¿Desea eliminar este producto de la lista?",
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
            calcularTotales();
        }
    });
}

/**
 * Resetear campos del formulario de producto
 */
function resetearCamposProducto() {
    $('#producto').val('').trigger('change');
    $('#cantidad').val(1);
    $('#descuentoProducto').val(0);
}

/**
 * Resetear todo el formulario de venta
 */
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

/**
 * Registrar la venta en el servidor
 */
function registrarVenta() {
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
        success: function (response) {
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
        error: function (xhr, status, error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al registrar la venta: ' + error
            });
        }
    });
}

/**
 * Ver detalle de una venta específica
 * @param {number} ventaID - ID de la venta a consultar
 */
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
        success: function (response) {
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
                    const subtotal = detalle.Cantidad * detalle.PrecioUnitario * (1 - detalle.DescuentoProducto / 100);

                    tbody.append(`
                        <tr>
                            <td>${detalle.ProductoNombre || 'Producto no disponible'}</td>
                            <td>${detalle.Cantidad}</td>
                            <td>${detalle.PrecioUnitario.toFixed(2)}</td>
                            <td>${detalle.DescuentoProducto}%</td>
                            <td>${subtotal.toFixed(2)}</td>
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
        error: function (xhr, status, error) {
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