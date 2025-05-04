// compras.js - Lógica de negocio para el módulo de compras

// Variables globales
let productosSeleccionados = [];
let productos = [];
let proveedores = [];
let plazosAdicionales = [];
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
    });

    // Inicializar el resto de eventos después del DOM ready
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
    
    $('#registrarCompraBtn').click(function() {
        const esPlazo = $('#esPlazo').is(':checked');
        const total = calcularTotal();
        
        if (!validarFormularioCompra()) return;

        const compraData = {
            ruc: $('#proveedor').val(),
            numFactura: $('#numeroFactura').val(),
            fecha: $('#fechaCompra').val(),
            total: total,
            usuarioID: window.usuarioID || 1,
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

// Validar formulario de compra
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

// Validar plazos
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

// Procesar plazos adicionales
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

// Eliminar plazo
function eliminarPlazo(plazoId) {
    $(`#plazo-${plazoId}`).remove();
    plazosAdicionales = plazosAdicionales.filter(p => p.id !== plazoId);
    actualizarResumenPlazos();
}

// Actualizar resumen de plazos
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
function verPlazosCompra(compraID) {
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
    
    // Inicializar eventos de la interfaz
    initEventListeners();
}

function initEventListeners() {
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
}

// Función auxiliar para calcular el total (reemplazada por la función en compras.js)
function calcularTotal() {
    // Esta función es un placeholder - la implementación real está en compras.js
    return parseFloat($('#totalCompra').text().replace('$', '')) || 0;
}

// Imprimir compra
function imprimirCompra() {
    const compraID = $('#detalleCompraID').text();
    window.open(`reporte_compra.php?compraID=${compraID}`, '_blank');
}
