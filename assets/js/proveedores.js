/**
 * Archivo JavaScript para manejar la lógica de proveedores
 */

// Variables globales
let proveedoresTable;

/**
 * Inicializar la página cuando se cargue el DOM
 */
$(document).ready(function () {
    // Configuración de DataTables para la tabla de proveedores
    proveedoresTable = $('#proveedoresTable').DataTable({
        ajax: {
            url: 'crud_proveedores.php?action=read',
            dataSrc: '',
            error: function(xhr, error, thrown) {
                console.error('Error al cargar datos:', xhr.responseText);
                mostrarErrorTabla('Error al cargar los proveedores. ' + obtenerMensajeError(xhr));
            }
        },
        columns: [
            { data: 'RUC' },
            { data: 'Nombre' },
            { data: 'Direccion' },
            { data: 'Telefono' },
            { data: 'Celular' },
            { data: 'Email' },
            {
                data: null,
                render: function (data, type, row) {
                    return `
                        <div class="form-button-action">
                            <button type="button" class="btn btn-link btn-primary btn-lg" 
                                onclick="editarProveedor('${row.RUC}')" title="Editar">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-link btn-danger" 
                                onclick="eliminarProveedor('${row.RUC}')" title="Eliminar">
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
        autoWidth: false,
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        dom: '<"top"lf>rt<"bottom"ip><"clear">'
    });

    // Configurar eventos
    configurarEventos();
});

/**
 * Configurar todos los eventos de la página
 */
function configurarEventos() {
    // Agregar proveedor
    $('#addProveedorButton').click(function () {
        agregarProveedor();
    });

    // Editar proveedor
    $('#editProveedorButton').click(function () {
        actualizarProveedor();
    });

    // Limpiar formulario cuando se cierra el modal
    $('#addRowModal').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
        $(this).find('.is-invalid').removeClass('is-invalid');
    });

    $('#editRowModal').on('hidden.bs.modal', function () {
        $(this).find('.is-invalid').removeClass('is-invalid');
    });
}

/**
 * Mostrar notificación
 * @param {string} type - Tipo de notificación (success, error, warning, info)
 * @param {string} message - Mensaje a mostrar
 */
function mostrarNotificacion(type, message) {
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
    $('#proveedoresTable tbody').html(`
        <tr class="odd">
            <td valign="top" colspan="7" class="dataTables_empty text-center text-danger">
                ${message}<br>
                <button onclick="location.reload()" class="btn btn-sm btn-primary mt-2">
                    <i class="fa fa-refresh"></i> Recargar
                </button>
            </td>
        </tr>
    `);
}

/**
 * Agregar un nuevo proveedor
 */
function agregarProveedor() {
    const ruc = $('#ruc').val().trim();
    const nombre = $('#nombre').val().trim();
    
    // Validar campos requeridos
    if (!ruc) {
        mostrarNotificacion('error', 'El RUC del proveedor es requerido');
        $('#ruc').addClass('is-invalid');
        return;
    }
    
    if (!nombre) {
        mostrarNotificacion('error', 'El nombre del proveedor es requerido');
        $('#nombre').addClass('is-invalid');
        return;
    }
    
    const $btn = $('#addProveedorButton');
    const originalHtml = $btn.html();
    $btn.html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
    $btn.prop('disabled', true);

    $.ajax({
        url: 'crud_proveedores.php?action=create',
        method: 'POST',
        data: {
            ruc: ruc,
            nombre: nombre,
            direccion: $('#direccion').val().trim(),
            telefono: $('#telefono').val().trim(),
            celular: $('#celular').val().trim(),
            email: $('#email').val().trim()
        },
        dataType: 'json',
        success: function (response) {
            $btn.html(originalHtml);
            $btn.prop('disabled', false);
            
            if (response.success) {
                proveedoresTable.ajax.reload(null, false);
                $('#addRowModal').modal('hide');
                $('#addProveedorForm')[0].reset();
                mostrarNotificacion('success', response.success);
            } else if (response.error) {
                mostrarNotificacion('error', response.error);
            }
        },
        error: function(xhr, status, error) {
            $btn.html(originalHtml);
            $btn.prop('disabled', false);
            
            let errorMsg = 'Error al agregar proveedor: ' + obtenerMensajeError(xhr);
            mostrarNotificacion('error', errorMsg);
        }
    });
}

/**
 * Editar proveedor existente
 * @param {string} ruc - RUC del proveedor a editar
 */
function editarProveedor(ruc) {
    // Mostrar spinner en el modal
    $('#editRowModal .modal-body').prepend(`
        <div class="overlay d-flex justify-content-center align-items-center" 
            style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(255,255,255,0.7); z-index: 1000;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
        </div>
    `);
    
    $('#editRowModal').modal('show');

    $.ajax({
        url: 'crud_proveedores.php?action=read_one&ruc=' + encodeURIComponent(ruc),
        method: 'GET',
        dataType: 'json',
        success: function (proveedor) {
            // Verificar si hay error en la respuesta
            if (proveedor.error) {
                $('#editRowModal .overlay').remove();
                mostrarNotificacion('error', proveedor.error);
                $('#editRowModal').modal('hide');
                return;
            }

            // Llenar el modal de edición
            $('#editRuc').val(proveedor.RUC);
            $('#editNombre').val(proveedor.Nombre);
            $('#editDireccion').val(proveedor.Direccion || '');
            $('#editTelefono').val(proveedor.Telefono || '');
            $('#editCelular').val(proveedor.Celular || '');
            $('#editEmail').val(proveedor.Email || '');

            // Ocultar spinner
            $('#editRowModal .overlay').remove();
        },
        error: function(xhr, status, error) {
            $('#editRowModal .overlay').remove();
            $('#editRowModal').modal('hide');
            
            let errorMsg = 'Error al obtener proveedor: ' + obtenerMensajeError(xhr);
            mostrarNotificacion('error', errorMsg);
        }
    });
}

/**
 * Actualizar proveedor existente
 */
function actualizarProveedor() {
    const ruc = $('#editRuc').val().trim();
    const nombre = $('#editNombre').val().trim();
    
    // Validar campos requeridos
    if (!nombre) {
        mostrarNotificacion('error', 'El nombre del proveedor es requerido');
        $('#editNombre').addClass('is-invalid');
        return;
    }
    
    const $btn = $('#editProveedorButton');
    const originalHtml = $btn.html();
    $btn.html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
    $btn.prop('disabled', true);

    $.ajax({
        url: 'crud_proveedores.php?action=update',
        method: 'POST',
        data: {
            ruc: ruc,
            nombre: nombre,
            direccion: $('#editDireccion').val().trim(),
            telefono: $('#editTelefono').val().trim(),
            celular: $('#editCelular').val().trim(),
            email: $('#editEmail').val().trim()
        },
        dataType: 'json',
        success: function (response) {
            $btn.html(originalHtml);
            $btn.prop('disabled', false);
            
            if (response && response.success) {
                proveedoresTable.ajax.reload(null, false);
                $('#editRowModal').modal('hide');
                mostrarNotificacion('success', response.success || 'Proveedor actualizado correctamente');
            } else {
                mostrarNotificacion('error', response?.error || 'Error al actualizar proveedor');
            }
        },
        error: function(xhr, status, error) {
            $btn.html(originalHtml);
            $btn.prop('disabled', false);
            
            let errorMsg = 'Error al actualizar proveedor: ' + obtenerMensajeError(xhr);
            mostrarNotificacion('error', errorMsg);
        }
    });
}

/**
 * Eliminar un proveedor
 * @param {string} ruc - RUC del proveedor a eliminar
 */
function eliminarProveedor(ruc) {
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
                url: 'crud_proveedores.php?action=delete',
                method: 'POST',
                data: { ruc: ruc },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        proveedoresTable.ajax.reload(null, false);
                        mostrarNotificacion('success', response.success);
                    } else if (response.error) {
                        mostrarNotificacion('error', response.error);
                    }
                },
                error: function(xhr, status, error) {
                    let errorMsg = 'Error al eliminar proveedor: ' + obtenerMensajeError(xhr);
                    mostrarNotificacion('error', errorMsg);
                }
            });
        }
    });
}

// Solución adicional para problemas con el backdrop del modal
$(document).on('click', function() {
    if($('body').hasClass('modal-open') && $('.modal.show').length === 0) {
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
    }
});