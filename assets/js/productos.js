/**
 * Archivo JavaScript para manejar la lógica de productos
 */

// Variables globales
let productosTable;

/**
 * Inicializar la página cuando se cargue el DOM
 */
$(document).ready(function () {
    // Configuración de DataTables para la tabla de productos
    productosTable = $('#productosTable').DataTable({
        ajax: {
            url: 'crud_productos.php?action=read',
            dataSrc: '',
            error: function(xhr, error, thrown) {
                console.error('Error al cargar datos:', xhr.responseText);
                mostrarErrorTabla('Error al cargar los productos. ' + obtenerMensajeError(xhr));
            }
        },
        columns: [
            { data: 'ProductoID' },
            { 
                data: 'Nombre',
                render: function(data, type, row) {
                    // Resaltar si el stock está bajo mínimo
                    const stockMinimo = row.StockMinimo || 5;
                    let nombreHtml = data;
                    
                    if (row.Stock < stockMinimo) {
                        nombreHtml = `<span class="text-danger fw-bold">${data}</span>`;
                    }
                    
                    // Agregar marca si existe
                    if (row.marca) {
                        nombreHtml += `<br><small class="text-muted">${row.marca}</small>`;
                    }
                    
                    return nombreHtml;
                }
            },
            { 
                data: 'PrecioCompra',
                render: function(data) {
                    return data ? '$' + parseFloat(data).toFixed(2) : '$0.00';
                },
                className: 'text-end'
            },
            { 
                data: 'PrecioVenta',
                render: function(data) {
                    return data ? '$' + parseFloat(data).toFixed(2) : '$0.00';
                },
                className: 'text-end'
            },
            { 
                data: null,
                render: function(data) {
                    const ganancia = parseFloat(data.PrecioVenta) - parseFloat(data.PrecioCompra);
                    return '$' + ganancia.toFixed(2);
                },
                className: 'text-end'
            },
            {
                data: 'Stock',
                render: function(data, type, row) {
                    const stockMinimo = row.StockMinimo || 5;
                    
                    if (row.es_liquido == 1) {
                        // Mostrar información de líquido
                        const badgeClass = row.contenido_actual < stockMinimo ? 'badge bg-danger' : 'badge bg-primary';
                        return `
                            <span class="${badgeClass}" title="Tipo: ${row.tipo_aceite || 'N/A'}, Capacidad: ${row.capacidad_envase || 0}L">
                                ${row.contenido_actual || 0}L / ${row.capacidad_envase || 0}L
                            </span>
                        `;
                    } else {
                        // Mostrar información normal de stock
                        const badgeClass = data < stockMinimo ? 'badge bg-danger' : 'badge bg-success';
                        return `<span class="${badgeClass}">${data} unidades</span>`;
                    }
                },
                className: 'text-center'
            },
            { 
                data: 'Estado',
                render: function(data) {
                    const badgeClass = data === 'Activo' ? 'badge bg-success' : 'badge bg-warning text-dark';
                    return `<span class="${badgeClass}">${data}</span>`;
                },
                className: 'text-center'
            },
            { 
                data: 'CategoriaNombre',
                className: 'text-center'
            },
            {
                data: null,
                render: function(data, type, row) {
                    return `
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" 
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <button class="dropdown-item" onclick="verProducto('${row.ProductoID}')">
                                        <i class="fas fa-eye me-2"></i> Ver
                                    </button>
                                </li>
                                <li>
                                    <button class="dropdown-item" onclick="editarProducto('${row.ProductoID}')">
                                        <i class="fas fa-edit me-2"></i> Editar
                                    </button>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <button class="dropdown-item text-danger" onclick="eliminarProducto('${row.ProductoID}')">
                                        <i class="fas fa-trash me-2"></i> Eliminar
                                    </button>
                                </li>
                            </ul>
                        </div>
                    `;
                },
                orderable: false,
                className: 'text-center'
            }
        ],
        order: [[1, 'asc']], // Ordenar por nombre ascendente por defecto
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        responsive: true,
        processing: true,
        serverSide: false,
        stateSave: true,
        autoWidth: false,
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        dom: '<"top"lf>rt<"bottom"ip><"clear">'
    });

    // Configurar eventos
    configurarEventos();
    
    // Cargar categorías y proveedores al iniciar
    cargarSelectCategorias('#categoriaID');
    cargarSelectProveedores('#ruc');
    
    // Establecer fecha actual por defecto
    $('#fechaIngreso').val(new Date().toISOString().split('T')[0]);
});

/**
 * Configurar todos los eventos de la página
 */
function configurarEventos() {
    // Habilitar/deshabilitar días de garantía según checkbox
    $('#tieneGarantia').change(function() {
        $('#diasGarantia').prop('disabled', !$(this).is(':checked'));
    });

    $('#editTieneGarantia').change(function() {
        $('#editDiasGarantia').prop('disabled', !$(this).is(':checked'));
        $('#editGarantiaGroup').toggle($(this).is(':checked'));
    });
    
    // Configurar el checkbox de producto líquido
    $('#esLiquido').change(function() {
        if ($(this).is(':checked')) {
            $('#liquidFields').show();
            $('#stock').val('0').prop('disabled', true);
        } else {
            $('#liquidFields').hide();
            $('#stock').prop('disabled', false);
            // Limpiar campos de líquido
            $('#tipoAceite').val('');
            $('#capacidadEnvase').val('0');
            $('#contenidoActual').val('0');
        }
    });
    
    $('#editEsLiquido').change(function() {
        if ($(this).is(':checked')) {
            $('#editLiquidFields').show();
            $('#editStock').val('0').prop('disabled', true);
        } else {
            $('#editLiquidFields').hide();
            $('#editStock').prop('disabled', false);
            // Limpiar campos de líquido
            $('#editTipoAceite').val('');
            $('#editCapacidadEnvase').val('0');
            $('#editContenidoActual').val('0');
        }
    });

    // Modal para agregar producto
    $('#addProductoModal').on('show.bs.modal', function() {
        $('#esLiquido').prop('checked', false).trigger('change');
        $('#liquidFields').hide();
        $('#stock').prop('disabled', false).val('');
        cargarSelectCategorias('#categoriaID');
        cargarSelectProveedores('#ruc');
        $('#fechaIngreso').val(new Date().toISOString().split('T')[0]);
    });

    // Modal para editar producto
    $('#editProductoModal').on('show.bs.modal', function() {
        cargarSelectCategorias('#editCategoriaID');
        cargarSelectProveedores('#editRuc');
    });
    
    // Limpiar modal al cerrar
    $('#addProductoModal').on('hidden.bs.modal', function() {
        $(this).find('form')[0].reset();
        $(this).find('.is-invalid').removeClass('is-invalid');
    });
    
    $('#editProductoModal').on('hidden.bs.modal', function() {
        $(this).find('.is-invalid').removeClass('is-invalid');
        $('#imagenPreview').html('');
    });
    
    // Botones de acción
    $('#addProductoBtn').click(function() {
        agregarProducto();
    });
    
    $('#editProductoBtn').click(function() {
        actualizarProducto();
    });
}

/**
 * Mostrar notificación
 * @param {string} type - Tipo de notificación (success, error, warning, info)
 * @param {string} message - Mensaje a mostrar
 */
function mostrarNotificacion(type, message) {
    // Cerrar notificaciones previas si existen
    Swal.close();
    
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        },
        didClose: () => {
            // Asegurarse de que no quedan elementos bloqueantes
            $('.modal-backdrop').not('.show').remove();
            $('body').removeClass('modal-open');
        }
    });
    
    Toast.fire({
        icon: type,
        title: message
    });
}

/**
 * Obtener mensaje de error desde la respuesta AJAX
 * @param {object} xhr - Objeto de respuesta AJAX
 * @returns {string} Mensaje de error
 */
function obtenerMensajeError(xhr) {
    let errorMsg = '';
    try {
        const res = JSON.parse(xhr.responseText);
        errorMsg = res.error || xhr.responseText || 'Error desconocido';
    } catch(e) {
        errorMsg = xhr.responseText || 'Error desconocido';
    }
    return errorMsg;
}

/**
 * Mostrar error en la tabla
 * @param {string} message - Mensaje de error
 */
function mostrarErrorTabla(message) {
    $('#productosTable tbody').html(`
        <tr class="odd">
            <td valign="top" colspan="9" class="dataTables_empty text-center text-danger">
                ${message}<br>
                <button onclick="location.reload()" class="btn btn-sm btn-primary mt-2">
                    <i class="fa fa-refresh"></i> Recargar
                </button>
            </td>
        </tr>
    `);
}

/**
 * Cargar categorías en un select
 * @param {string} selector - Selector del elemento select
 * @param {number|null} selectedId - ID de la categoría seleccionada (opcional)
 */
function cargarSelectCategorias(selector, selectedId = null) {
    $.ajax({
        url: 'crud_productos.php?action=get_categorias',
        method: 'GET',
        success: function(data) {
            const categorias = JSON.parse(data);
            const select = $(selector);
            select.empty();
            categorias.forEach(categoria => {
                const option = new Option(categoria.Nombre, categoria.CategoriaID);
                if (categoria.CategoriaID == selectedId) {
                    option.selected = true;
                }
                select.append(option);
            });
        },
        error: function(xhr, status, error) {
            mostrarNotificacion('error', 'Error al cargar categorías: ' + obtenerMensajeError(xhr));
        }
    });
}

/**
 * Cargar proveedores en un select
 * @param {string} selector - Selector del elemento select
 * @param {string|null} selectedRuc - RUC del proveedor seleccionado (opcional)
 */
function cargarSelectProveedores(selector, selectedRuc = null) {
    $.ajax({
        url: 'crud_productos.php?action=get_proveedores',
        method: 'GET',
        success: function(data) {
            const proveedores = JSON.parse(data);
            const select = $(selector);
            select.empty();
            proveedores.forEach(proveedor => {
                const option = new Option(`${proveedor.Nombre} (${proveedor.RUC})`, proveedor.RUC);
                if (proveedor.RUC == selectedRuc) {
                    option.selected = true;
                }
                select.append(option);
            });
        },
        error: function(xhr, status, error) {
            mostrarNotificacion('error', 'Error al cargar proveedores: ' + obtenerMensajeError(xhr));
        }
    });
}

/**
 * Agregar un nuevo producto
 */
function agregarProducto() {
    const esLiquido = $('#esLiquido').is(':checked') ? 1 : 0;
    
    // Validar campos requeridos
    if (!$('#productoID').val() || !$('#nombre').val() || !$('#precioCompra').val() || 
        !$('#precioVenta').val() || !$('#stock').val() || !$('#categoriaID').val() || 
        !$('#ruc').val()) {
        mostrarNotificacion('error', 'Por favor complete todos los campos requeridos');
        return;
    }

    // Crear FormData
    const formData = new FormData();
    formData.append('action', 'create');
    formData.append('productoID', $('#productoID').val());
    formData.append('nombre', $('#nombre').val());
    formData.append('descripcion', $('#descripcion').val());
    formData.append('precioCompra', $('#precioCompra').val());
    formData.append('precioVenta', $('#precioVenta').val());
    formData.append('stock', $('#stock').val());
    formData.append('stockMinimo', $('#stockMinimo').val() || 5);
    formData.append('estado', $('#estado').val() || 'Activo');
    formData.append('compatibilidad', $('#compatibilidad').val() || '');
    formData.append('fechaIngreso', $('#fechaIngreso').val() || new Date().toISOString().split('T')[0]);
    formData.append('categoriaID', $('#categoriaID').val());
    formData.append('ruc', $('#ruc').val());
    formData.append('tieneGarantia', $('#tieneGarantia').is(':checked') ? 1 : 0);
    formData.append('diasGarantia', $('#diasGarantia').val() || null);
    formData.append('codigoBarras', $('#codigoBarras').val() || '');
    formData.append('ubicacionAlmacen', $('#ubicacionAlmacen').val() || '');
    formData.append('marca', $('#marca').val() || '');
    formData.append('esLiquido', esLiquido);
    
    // Agregar imagen si existe
    const imagenInput = $('#imagen')[0];
    if (imagenInput.files.length > 0) {
        formData.append('imagen', imagenInput.files[0]);
    }
    
    // Solo agregar campos de líquido si está marcado
    if (esLiquido) {
        formData.append('tipoAceite', $('#tipoAceite').val() || '');
        formData.append('capacidadEnvase', $('#capacidadEnvase').val() || '0');
        formData.append('contenidoActual', $('#contenidoActual').val() || '0');
    } else {
        // Forzar valores vacíos/cero si no es líquido
        formData.append('tipoAceite', '');
        formData.append('capacidadEnvase', '0');
        formData.append('contenidoActual', '0');
    }
    
    // Mostrar loading
    const $btn = $('#addProductoBtn');
    const originalHtml = $btn.html();
    $btn.html('<i class="fa fa-spinner fa-spin"></i> Guardando...');
    $btn.prop('disabled', true);

    $.ajax({
        url: 'crud_productos.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            // Restaurar botón
            $btn.html(originalHtml);
            $btn.prop('disabled', false);
            
            if (response.success) {
                // Cerrar el modal correctamente
                $('#addProductoModal').modal('hide');
                
                // Limpiar el formulario
                $('#addProductoForm')[0].reset();
                
                // Recargar la tabla
                productosTable.ajax.reload(null, false);
                
                // Mostrar notificación
                mostrarNotificacion('success', response.success);
            } else {
                mostrarNotificacion('error', response.error || 'Error desconocido al guardar');
            }
        },
        error: function(xhr, status, error) {
            // Restaurar botón
            $btn.html(originalHtml);
            $btn.prop('disabled', false);
            
            // Mostrar error
            let errorMsg = 'Error al guardar el producto: ' + obtenerMensajeError(xhr);
            mostrarNotificacion('error', errorMsg);
        }
    });
}

/**
 * Ver detalles de un producto
 * @param {string} productoID - ID del producto
 */
function verProducto(productoID) {
    // Limpiar el modal y mostrar spinner
    $('#viewProductoModal .modal-body').html(`
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2">Cargando información del producto...</p>
        </div>
    `);
    
    $('#viewProductoModal').modal('show');

    $.ajax({
        url: 'crud_productos.php?action=get_producto&id=' + encodeURIComponent(productoID),
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            try {
                // Verificar si la respuesta es válida
                if (!response || typeof response !== 'object') {
                    throw new Error('Respuesta del servidor no válida');
                }
                
                // Verificar si hay error
                if (response.error) {
                    throw new Error(response.error);
                }

                // Plantilla base para el contenido del modal
                let contenido = `
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>ID del Producto:</strong></label>
                                <p>${response.ProductoID || 'N/A'}</p>
                            </div>
                            <div class="form-group">
                                <label><strong>Nombre:</strong></label>
                                <p>${response.Nombre || 'N/A'}</p>
                            </div>
                            <div class="form-group">
                                <label><strong>Marca:</strong></label>
                                <p>${response.marca || 'N/A'}</p>
                            </div>
                            <div class="form-group">
                                <label><strong>Descripción:</strong></label>
                                <p>${response.Descripcion || 'N/A'}</p>
                            </div>`;

                // Agregar información específica para líquidos
                if (response.es_liquido == 1) {
                    contenido += `
                            <div class="form-group">
                                <label><strong>Tipo de Aceite:</strong></label>
                                <p>${response.tipo_aceite || 'N/A'}</p>
                            </div>
                            <div class="form-group">
                                <label><strong>Capacidad del Envase:</strong></label>
                                <p>${response.capacidad_envase || '0'} litros</p>
                            </div>
                            <div class="form-group">
                                <label><strong>Contenido Actual:</strong></label>
                                <p>${response.contenido_actual || '0'} litros</p>
                            </div>`;
                }

                // Continuar con el resto de campos comunes
                contenido += `
                            <div class="form-group">
                                <label><strong>Precio de Compra:</strong></label>
                                <p>$${response.PrecioCompra ? parseFloat(response.PrecioCompra).toFixed(2) : '0.00'}</p>
                            </div>
                            <div class="form-group">
                                <label><strong>Precio de Venta:</strong></label>
                                <p>$${response.PrecioVenta ? parseFloat(response.PrecioVenta).toFixed(2) : '0.00'}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Stock:</strong></label>
                                <p>${response.es_liquido == 1 ? (response.contenido_actual || '0') + ' litros' : (response.Stock || '0') + ' unidades'}</p>
                            </div>
                            <div class="form-group">
                                <label><strong>Categoría:</strong></label>
                                <p>${response.CategoriaNombre || 'N/A'}</p>
                            </div>
                            <div class="form-group">
                                <label><strong>Proveedor:</strong></label>
                                <p>${response.ProveedorNombre || 'N/A'}</p>
                            </div>
                            <div class="form-group">
                                <label><strong>Código de Barras:</strong></label>
                                <p>${response.CodigoBarras || 'N/A'}</p>
                            </div>
                            <div class="form-group">
                                <label><strong>Ubicación en Almacén:</strong></label>
                                <p>${response.UbicacionAlmacen || 'N/A'}</p>
                            </div>
                            <div class="form-group">
                                <label><strong>Garantía:</strong></label>
                                <p>${response.TieneGarantia ? 'Sí (' + (response.DiasGarantia || '0') + ' días)' : 'No'}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label><strong>Imagen del Producto:</strong></label>
                            <div id="viewImagen" class="text-center">
                                ${response.TieneImagen && response.ImagenBase64 ? 
                                    `<img src="data:image/jpeg;base64,${response.ImagenBase64}" class="img-fluid" style="max-height: 300px;">` : 
                                    '<div class="alert alert-info">No hay imagen disponible</div>'}
                            </div>
                        </div>
                    </div>`;

                // Insertar todo el contenido en el modal de una vez
                $('#viewProductoModal .modal-body').html(contenido);
                
            } catch (e) {
                console.error('Error al procesar respuesta:', e);
                $('#viewProductoModal .modal-body').html(`
                    <div class="alert alert-danger">
                        Error al mostrar el producto: ${e.message}
                        <button onclick="verProducto('${productoID}')" class="btn btn-sm btn-primary mt-2">
                            <i class="fa fa-refresh"></i> Intentar nuevamente
                        </button>
                    </div>
                `);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error en la solicitud:', error, 'Respuesta:', xhr.responseText);
            let errorMsg = 'Error al cargar el producto: ' + obtenerMensajeError(xhr);
            
            $('#viewProductoModal .modal-body').html(`
                <div class="alert alert-danger">
                    ${errorMsg}
                    <button onclick="verProducto('${productoID}')" class="btn btn-sm btn-primary mt-2">
                        <i class="fa fa-refresh"></i> Intentar nuevamente
                    </button>
                </div>
            `);
        }
    });
}

/**
 * Editar un producto
 * @param {string} productoID - ID del producto
 */
function editarProducto(productoID) {
    console.log('Iniciando edición para producto ID:', productoID);
    
    // Mostrar spinner
    $('#editProductoModal .modal-body').prepend(
        '<div class="overlay d-flex justify-content-center align-items-center" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(255,255,255,0.7); z-index: 1000;">' +
        '<div class="spinner-border text-primary" role="status">' +
        '<span class="visually-hidden">Cargando...</span>' +
        '</div></div>'
    );

    $.ajax({
        url: 'crud_productos.php?action=get_producto&id=' + productoID,
        method: 'GET',
        dataType: 'json',
        success: function(producto) {
            console.log('Producto recibido:', producto);
            
            if (producto.error) {
                $('#editProductoModal .overlay').remove();
                mostrarNotificacion('error', producto.error);
                return;
            }

            // Llenar campos del formulario
            $('#editProductoID').val(producto.ProductoID);
            $('#editNombre').val(producto.Nombre);
            $('#editDescripcion').val(producto.Descripcion || '');
            $('#editPrecioCompra').val(parseFloat(producto.PrecioCompra).toFixed(2));
            $('#editPrecioVenta').val(parseFloat(producto.PrecioVenta).toFixed(2));
            $('#editStock').val(producto.Stock);
            $('#editStockMinimo').val(producto.StockMinimo || 5);
            $('#editEstado').val(producto.Estado);
            $('#editCompatibilidad').val(producto.Compatibilidad || '');
            $('#editFechaIngreso').val(producto.FechaIngreso);
            $('#editCodigoBarras').val(producto.CodigoBarras || '');
            $('#editUbicacionAlmacen').val(producto.UbicacionAlmacen || '');
            $('#editMarca').val(producto.marca || '');
            
            // Manejar garantía
            if (producto.TieneGarantia) {
                $('#editTieneGarantia').prop('checked', true).trigger('change');
                $('#editDiasGarantia').val(producto.DiasGarantia || '');
            } else {
                $('#editTieneGarantia').prop('checked', false).trigger('change');
            }

            // Mostrar imagen actual
            if (producto.ImagenBase64) {
                $('#imagenPreview').html(
                    '<img src="data:image/jpeg;base64,' + producto.ImagenBase64 + 
                    '" class="img-thumbnail" style="max-height: 200px;">' +
                    '<p class="text-muted mt-2">Imagen actual. Suba una nueva para reemplazar.</p>'
                );
            } else {
                $('#imagenPreview').html(
                    '<div class="alert alert-info">No hay imagen actual para este producto</div>'
                );
            }

            // Manejar estado de líquido
            if (producto.es_liquido == 1) {
                $('#editEsLiquido').prop('checked', true).trigger('change');
                $('#editTipoAceite').val(producto.tipo_aceite || '');
                $('#editCapacidadEnvase').val(producto.capacidad_envase || '0');
                $('#editContenidoActual').val(producto.contenido_actual || '0');
                $('#editStock').val('0').prop('disabled', true);
            } else {
                $('#editEsLiquido').prop('checked', false).trigger('change');
                $('#editStock').val(producto.Stock).prop('disabled', false);
                $('#editTipoAceite').val('');
                $('#editCapacidadEnvase').val('0');
                $('#editContenidoActual').val('0');
            }

            // Cargar selects con valores actuales
            cargarSelectCategorias('#editCategoriaID', producto.CategoriaID);
            cargarSelectProveedores('#editRuc', producto.RUC);
            
            // Ocultar spinner y mostrar modal
            $('#editProductoModal .overlay').remove();
            $('#editProductoModal').modal('show');
        },
        error: function(xhr, status, error) {
            console.error('Error en la solicitud:', error, 'Respuesta:', xhr.responseText);
            $('#editProductoModal .overlay').remove();
            
            let errorMsg = 'Error al obtener producto: ' + obtenerMensajeError(xhr);
            mostrarNotificacion('error', errorMsg);
        }
    });
}

/**
 * Actualizar un producto existente
 */
/**
 * Eliminar un producto
 * @param {string} productoID - ID del producto a eliminar
 */
function eliminarProducto(productoID) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡No podrás revertir esta acción!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'crud_productos.php?action=delete',
                method: 'POST',
                data: { productoID: productoID },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        productosTable.ajax.reload(null, false);
                        mostrarNotificacion('success', response.success);
                    } else {
                        mostrarNotificacion('error', response.error || 'Error al eliminar el producto');
                    }
                },
                error: function(xhr, status, error) {
                    let errorMsg = 'Error al eliminar producto: ' + obtenerMensajeError(xhr);
                    mostrarNotificacion('error', errorMsg);
                }
            });
        }
    });
}

/**
 * Actualizar un producto existente
 */
function actualizarProducto() {
    // Validar campos requeridos
    const requiredFields = [
        '#editNombre', 
        '#editPrecioCompra', 
        '#editPrecioVenta', 
        '#editStock', 
        '#editCategoriaID', 
        '#editRuc'
    ];
    
    let isValid = true;
    requiredFields.forEach(field => {
        if (!$(field).val()) {
            $(field).addClass('is-invalid');
            isValid = false;
        } else {
            $(field).removeClass('is-invalid');
        }
    });

    if (!isValid) {
        mostrarNotificacion('error', 'Por favor complete todos los campos requeridos');
        return;
    }

    // Preparar FormData
    const formData = new FormData();
    formData.append('action', 'update');
    formData.append('productoID', $('#editProductoID').val());
    formData.append('nombre', $('#editNombre').val());
    formData.append('descripcion', $('#editDescripcion').val());
    formData.append('precioCompra', $('#editPrecioCompra').val());
    formData.append('precioVenta', $('#editPrecioVenta').val());
    formData.append('stock', $('#editStock').val());
    formData.append('stockMinimo', $('#editStockMinimo').val() || 5);
    formData.append('estado', $('#editEstado').val());
    formData.append('compatibilidad', $('#editCompatibilidad').val() || '');
    formData.append('fechaIngreso', $('#editFechaIngreso').val());
    formData.append('categoriaID', $('#editCategoriaID').val());
    formData.append('ruc', $('#editRuc').val());
    formData.append('tieneGarantia', $('#editTieneGarantia').is(':checked') ? 1 : 0);
    formData.append('diasGarantia', $('#editDiasGarantia').val() || null);
    formData.append('codigoBarras', $('#editCodigoBarras').val() || '');
    formData.append('ubicacionAlmacen', $('#editUbicacionAlmacen').val() || '');
    formData.append('marca', $('#editMarca').val() || '');
    
    // Asegurarse de capturar correctamente el estado del checkbox
    formData.append('esLiquido', $('#editEsLiquido').is(':checked') ? '1' : '0');
    
    // Solo agregar campos de líquido si está marcado
    if ($('#editEsLiquido').is(':checked')) {
        formData.append('tipoAceite', $('#editTipoAceite').val());
        formData.append('capacidadEnvase', $('#editCapacidadEnvase').val());
        formData.append('contenidoActual', $('#editContenidoActual').val());
    } else {
        // Forzar valores vacíos/cero si no es líquido
        formData.append('tipoAceite', '');
        formData.append('capacidadEnvase', '0');
        formData.append('contenidoActual', '0');
    }

    // Agregar imagen si se seleccionó
    const imagenInput = $('#editImagen')[0];
    if (imagenInput.files.length > 0) {
        formData.append('imagen', imagenInput.files[0]);
    }

    // Deshabilitar botón y mostrar spinner
    const $btn = $('#editProductoBtn');
    const originalHtml = $btn.html();
    $btn.html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
    $btn.prop('disabled', true);

    // Enviar datos al servidor
    $.ajax({
        url: 'crud_productos.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            console.log('Respuesta del servidor:', response);
            
            // Restaurar botón
            $btn.html(originalHtml);
            $btn.prop('disabled', false);

            if (response && response.success) {
                productosTable.ajax.reload(null, false);
                $('#editProductoModal').modal('hide');
                mostrarNotificacion('success', response.success);
            } else {
                const errorMsg = response?.error || 'Error desconocido al actualizar el producto';
                mostrarNotificacion('error', errorMsg);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error en la solicitud:', error, 'Respuesta:', xhr.responseText);
            
            // Restaurar botón
            $btn.html(originalHtml);
            $btn.prop('disabled', false);
            
            let errorMsg = 'Error al actualizar el producto: ' + obtenerMensajeError(xhr);
            mostrarNotificacion('error', errorMsg);
        }
    });
}