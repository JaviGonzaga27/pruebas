/**
 * Archivo JavaScript para manejar la lógica de categorías
 */

// Variables globales
let categoriasTable;

/**
 * Inicializar la página cuando se cargue el DOM
 */
$(document).ready(function () {
    // Configuración de DataTables para la tabla de categorías
    categoriasTable = $('#categoriasTable').DataTable({
        ajax: {
            url: 'crud_categorias.php?action=read',
            dataSrc: '',
            error: function(xhr, error, thrown) {
                console.error('Error al cargar datos:', xhr.responseText);
                mostrarErrorTabla('Error al cargar las categorías. ' + obtenerMensajeError(xhr));
            }
        },
        columns: [
            { data: 'CategoriaID' },
            { 
                data: 'Nombre',
                className: 'fw-bold'
            },
            { 
                data: 'Descripcion',
                render: function(data) {
                    return data || '<span class="text-muted">Sin descripción</span>';
                }
            },
            {
                data: null,
                render: function (data, type, row) {
                    return `
                        <div class="action-buttons text-center">
                            <button type="button" class="btn btn-link btn-primary btn-sm" 
                                onclick="editarCategoria(${row.CategoriaID})" title="Editar">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-link btn-danger btn-sm" 
                                onclick="eliminarCategoria(${row.CategoriaID})" title="Eliminar">
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
    // Agregar categoría
    $('#addCategoriaButton').click(function () {
        agregarCategoria();
    });

    // Editar categoría
    $('#editCategoriaButton').click(function () {
        actualizarCategoria();
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
    $('#categoriasTable tbody').html(`
        <tr class="odd">
            <td valign="top" colspan="4" class="dataTables_empty text-center text-danger">
                ${message}<br>
                <button onclick="location.reload()" class="btn btn-sm btn-primary mt-2">
                    <i class="fa fa-refresh"></i> Recargar
                </button>
            </td>
        </tr>
    `);
}

/**
 * Agregar una nueva categoría
 */
function agregarCategoria() {
    const nombre = $('#nombre').val().trim();
    const descripcion = $('#descripcion').val().trim();
    
    if (!nombre) {
        mostrarNotificacion('error', 'El nombre de la categoría es requerido');
        $('#nombre').addClass('is-invalid');
        return;
    }
    
    const $btn = $('#addCategoriaButton');
    const originalHtml = $btn.html();
    $btn.html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
    $btn.prop('disabled', true);

    $.ajax({
        url: 'crud_categorias.php?action=create',
        method: 'POST',
        data: {
            nombre: nombre,
            descripcion: descripcion
        },
        success: function (response) {
            $btn.html(originalHtml);
            $btn.prop('disabled', false);
            
            if (response.includes('correctamente')) {
                categoriasTable.ajax.reload(null, false);
                $('#addRowModal').modal('hide');
                $('#addCategoriaForm')[0].reset();
                mostrarNotificacion('success', 'Categoría agregada correctamente');
            } else {
                mostrarNotificacion('error', response || 'Error al agregar categoría');
            }
        },
        error: function(xhr, status, error) {
            $btn.html(originalHtml);
            $btn.prop('disabled', false);
            mostrarNotificacion('error', 'Error al agregar categoría: ' + obtenerMensajeError(xhr));
        }
    });
}

/**
 * Eliminar una categoría
 * @param {number} categoriaID - ID de la categoría a eliminar
 */
function eliminarCategoria(categoriaID) {
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
                url: 'crud_categorias.php?action=delete',
                method: 'POST',
                data: { categoriaID: categoriaID },
                success: function (response) {
                    if (response.includes('correctamente')) {
                        categoriasTable.ajax.reload(null, false);
                        mostrarNotificacion('success', 'Categoría eliminada correctamente');
                    } else {
                        mostrarNotificacion('error', response || 'Error al eliminar categoría');
                    }
                },
                error: function(xhr, status, error) {
                    mostrarNotificacion('error', 'Error al eliminar categoría: ' + obtenerMensajeError(xhr));
                }
            });
        }
    });
}

/**
 * Cargar datos de categoría para editar
 * @param {number} categoriaID - ID de la categoría a editar
 */
function editarCategoria(categoriaID) {
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
        url: 'crud_categorias.php?action=read',
        method: 'GET',
        success: function (data) {
            try {
                const categorias = JSON.parse(data);
                const categoria = categorias.find(c => c.CategoriaID == categoriaID);

                if (categoria) {
                    $('#editCategoriaID').val(categoria.CategoriaID);
                    $('#editNombre').val(categoria.Nombre);
                    $('#editDescripcion').val(categoria.Descripcion || '');
                    
                    // Ocultar spinner
                    $('#editRowModal .overlay').remove();
                } else {
                    $('#editRowModal').modal('hide');
                    mostrarNotificacion('error', 'Categoría no encontrada');
                }
            } catch(e) {
                $('#editRowModal').modal('hide');
                mostrarNotificacion('error', 'Error al procesar los datos de la categoría');
            }
        },
        error: function(xhr, status, error) {
            $('#editRowModal').modal('hide');
            mostrarNotificacion('error', 'Error al obtener categoría: ' + obtenerMensajeError(xhr));
        }
    });
}

/**
 * Actualizar categoría existente
 */
function actualizarCategoria() {
    const categoriaID = $('#editCategoriaID').val();
    const nombre = $('#editNombre').val().trim();
    const descripcion = $('#editDescripcion').val().trim();
    
    if (!nombre) {
        mostrarNotificacion('error', 'El nombre de la categoría es requerido');
        $('#editNombre').addClass('is-invalid');
        return;
    }
    
    const $btn = $('#editCategoriaButton');
    const originalHtml = $btn.html();
    $btn.html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
    $btn.prop('disabled', true);

    $.ajax({
        url: 'crud_categorias.php?action=update',
        method: 'POST',
        data: {
            categoriaID: categoriaID,
            nombre: nombre,
            descripcion: descripcion
        },
        success: function (response) {
            $btn.html(originalHtml);
            $btn.prop('disabled', false);
            
            if (response.includes('correctamente')) {
                categoriasTable.ajax.reload(null, false);
                $('#editRowModal').modal('hide');
                mostrarNotificacion('success', 'Categoría actualizada correctamente');
            } else {
                mostrarNotificacion('error', response || 'Error al actualizar categoría');
            }
        },
        error: function(xhr, status, error) {
            $btn.html(originalHtml);
            $btn.prop('disabled', false);
            mostrarNotificacion('error', 'Error al actualizar categoría: ' + obtenerMensajeError(xhr));
        }
    });
}