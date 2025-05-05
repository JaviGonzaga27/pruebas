/**
 * Sistema de Taller Mecánico - Módulo de Clientes
 * Este archivo contiene todas las funcionalidades JavaScript para la gestión de clientes
 */

// Namespace para evitar conflictos globales
const ClientesModule = (function() {
    // Variables privadas del módulo
    let datatable = null;
    
    // Configuración de validaciones específicas para Ecuador
    const validaciones = {
        cedula: function(cedula) {
            if (cedula.length !== 10) return false;
            
            let coeficiente = [2, 1, 2, 1, 2, 1, 2, 1, 2];
            let provincia = parseInt(cedula.substr(0, 2));
            
            if (provincia < 1 || provincia > 24) return false;
            if (parseInt(cedula[2]) > 6) return false;
            
            let total = 0;
            for (let i = 0; i < 9; i++) {
                let valor = coeficiente[i] * parseInt(cedula[i]);
                if (valor >= 10) valor -= 9;
                total += valor;
            }
            
            let final = 10 - (total % 10);
            if (final === 10) final = 0;
            
            return final === parseInt(cedula[9]);
        },

        telefono: function(telefono) {
            telefono = telefono.replace(/[\s-]/g, '');
            const regexMovil = /^09[0-9]{8}$/;
            const regexFijo = /^0[2-7][0-9]{7}$/;
            return regexMovil.test(telefono) || regexFijo.test(telefono);
        },

        email: function(email) {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        }
    };
    
    // Función para mostrar notificaciones reutilizable
    const notificar = function(type, message) {
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
    };
    
    // Inicialización de la tabla de clientes
    const initDataTable = function() {
        datatable = $('#clientesTable').DataTable({
            ajax: {
                url: 'crud_clientes.php?action=read',
                dataSrc: '',
                error: function(xhr, error, thrown) {
                    console.error('Error al cargar datos:', xhr.responseText);
                    let mensaje = 'Error al cargar los datos. ';
                    try {
                        const response = JSON.parse(xhr.responseText);
                        mensaje += response.error || '';
                    } catch(e) {
                        mensaje += xhr.responseText || error;
                    }
                    
                    $('#clientesTable tbody').html(`
                        <tr class="odd">
                            <td valign="top" colspan="7" class="dataTables_empty text-center text-danger">
                                ${mensaje}<br>
                                <button onclick="location.reload()" class="btn btn-sm btn-primary mt-2">
                                    <i class="fa fa-refresh"></i> Recargar
                                </button>
                            </td>
                        </tr>
                    `);
                }
            },
            columns: [
                { data: 'Cedula' },
                { data: 'Nombre' },
                { data: 'Direccion' },
                { data: 'Telefono' },
                { data: 'Email' },
                { 
                    data: 'FechaRegistro',
                    render: function(data) {
                        return data ? new Date(data).toLocaleDateString() : '';
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `
                            <div class="form-button-action">
                                <button type="button" class="btn btn-link btn-primary btn-lg edit-cliente" 
                                    data-cedula="${row.Cedula}" title="Editar">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-link btn-danger delete-cliente" 
                                    data-cedula="${row.Cedula}" title="Eliminar">
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
            responsive: true
        });
        
        return datatable;
    };
    
    // Configurar validaciones en tiempo real
    const setupValidaciones = function() {
        // Validación de cédula en tiempo real
        $('#cedula').on('input', function() {
            $(this).val($(this).val().replace(/[^0-9]/g, '').substr(0, 10));
            $(this).removeClass('is-invalid is-valid');
            
            if ($(this).val().length === 10) {
                if (validaciones.cedula($(this).val())) {
                    $(this).addClass('is-valid');
                } else {
                    $(this).addClass('is-invalid');
                }
            }
        });
        
        // Validación de teléfono en tiempo real
        $('#telefono, #editTelefono').on('input', function() {
            const telefono = $(this).val();
            $(this).removeClass('is-invalid is-valid');
            
            if (telefono) {
                if (validaciones.telefono(telefono)) {
                    $(this).addClass('is-valid');
                } else {
                    $(this).addClass('is-invalid');
                }
            }
        });
        
        // Validación de email en tiempo real
        $('#email, #editEmail').on('input', function() {
            const email = $(this).val();
            $(this).removeClass('is-invalid is-valid');
            
            if (email) {
                if (validaciones.email(email)) {
                    $(this).addClass('is-valid');
                } else {
                    $(this).addClass('is-invalid');
                }
            }
        });
    };

    // Handler para agregar cliente
    const handleAddCliente = function() {
        const cedula = $('#cedula').val();
        const nombre = $('#nombre').val();
        const telefono = $('#telefono').val();
        const email = $('#email').val();
        
        // Validaciones básicas
        if (!cedula || !nombre) {
            notificar('error', 'Cédula y Nombre son campos requeridos');
            return false;
        }
        
        // Validaciones ecuatorianas
        const errores = [];
        
        if (!validaciones.cedula(cedula)) {
            errores.push('La cédula no es válida (10 dígitos)');
        }
        
        if (telefono && !validaciones.telefono(telefono)) {
            errores.push('El teléfono debe ser un número válido de Ecuador (09xxxxxxxx o 0[2-7]xxxxxxx)');
        }
        
        if (email && !validaciones.email(email)) {
            errores.push('El email no tiene un formato válido');
        }
        
        if (errores.length > 0) {
            notificar('error', errores.join('<br>'));
            return false;
        }

        const $btn = $('#addClienteBtn');
        const originalHtml = $btn.html();
        $btn.html('<i class="fa fa-spinner fa-spin"></i> Guardando...');
        $btn.prop('disabled', true);

        $.ajax({
            url: 'crud_clientes.php?action=create',
            method: 'POST',
            data: {
                cedula: cedula,
                nombre: nombre,
                direccion: $('#direccion').val(),
                telefono: telefono,
                email: email
            },
            dataType: 'json',
            success: function (response) {
                $btn.html(originalHtml);
                $btn.prop('disabled', false);
                
                if (response.success) {
                    $('#addClienteModal').modal('hide');
                    datatable.ajax.reload(null, false);
                    notificar('success', response.success);
                } else if (response.error) {
                    notificar('error', response.error);
                }
            },
            error: function(xhr) {
                $btn.html(originalHtml);
                $btn.prop('disabled', false);
                
                let errorMsg = 'Error en la solicitud: ';
                try {
                    const res = JSON.parse(xhr.responseText);
                    errorMsg += res.error || xhr.statusText;
                } catch (e) {
                    errorMsg += xhr.statusText;
                }
                notificar('error', errorMsg);
            }
        });
        
        return true;
    };
    
    // Handler para editar cliente
    const handleEditCliente = function(cedula) {
        $.ajax({
            url: 'crud_clientes.php?action=read_one&cedula=' + encodeURIComponent(cedula),
            method: 'GET',
            dataType: 'json',
            success: function (cliente) {
                if (cliente.error) {
                    notificar('error', cliente.error);
                    return;
                }

                // Llenar el modal de edición
                $('#editCedula').val(cliente.Cedula);
                $('#editCedulaDisplay').val(cliente.Cedula);
                $('#editNombre').val(cliente.Nombre);
                $('#editDireccion').val(cliente.Direccion || '');
                $('#editTelefono').val(cliente.Telefono || '');
                $('#editEmail').val(cliente.Email || '');

                $('#editClienteModal').modal('show');
            },
            error: function(xhr, status, error) {
                let errorMsg = 'Error al cargar cliente: ';
                try {
                    const res = JSON.parse(xhr.responseText);
                    errorMsg += res.error || error;
                } catch (e) {
                    errorMsg += xhr.statusText || error;
                }
                notificar('error', errorMsg);
            }
        });
    };
    
    // Handler para guardar cambios al editar cliente
    const handleSaveEdit = function() {
        const nombre = $('#editNombre').val();
        const telefono = $('#editTelefono').val();
        const email = $('#editEmail').val();
        
        if (!nombre) {
            notificar('error', 'El nombre es requerido');
            return false;
        }
        
        // Validaciones ecuatorianas
        const errores = [];
        
        if (telefono && !validaciones.telefono(telefono)) {
            errores.push('El teléfono debe ser un número válido de Ecuador (09xxxxxxxx o 0[2-7]xxxxxxx)');
        }
        
        if (email && !validaciones.email(email)) {
            errores.push('El email no tiene un formato válido');
        }
        
        if (errores.length > 0) {
            notificar('error', errores.join('<br>'));
            return false;
        }

        const $btn = $('#editClienteBtn');
        const originalHtml = $btn.html();
        $btn.html('<i class="fa fa-spinner fa-spin"></i> Guardando...');
        $btn.prop('disabled', true);

        $.ajax({
            url: 'crud_clientes.php?action=update',
            method: 'POST',
            data: {
                cedula: $('#editCedula').val(),
                nombre: nombre,
                direccion: $('#editDireccion').val(),
                telefono: telefono,
                email: email
            },
            dataType: 'json',
            success: function (response) {
                $btn.html(originalHtml);
                $btn.prop('disabled', false);
                
                if (response && response.success) {
                    datatable.ajax.reload(null, false);
                    $('#editClienteModal').modal('hide');
                    notificar('success', response.success || 'Cliente actualizado correctamente');
                } else {
                    notificar('error', response?.error || 'Error al actualizar cliente');
                }
            },
            error: function(xhr, status, error) {
                $btn.html(originalHtml);
                $btn.prop('disabled', false);
                
                let errorMsg = 'Error al actualizar cliente: ';
                try {
                    const res = JSON.parse(xhr.responseText);
                    errorMsg += res.error || error;
                } catch (e) {
                    errorMsg += xhr.responseText || error;
                }
                
                notificar('error', errorMsg);
            }
        });
        
        return true;
    };
    
    // Handler para eliminar cliente
    const handleDeleteCliente = function(cedula) {
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
                    url: 'crud_clientes.php?action=delete',
                    method: 'POST',
                    data: { cedula: cedula },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            datatable.ajax.reload(null, false);
                            notificar('success', response.success);
                        } else if (response.error) {
                            notificar('error', response.error);
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error en la solicitud: ';
                        try {
                            const res = JSON.parse(xhr.responseText);
                            errorMsg += res.error || xhr.statusText;
                        } catch (e) {
                            errorMsg += xhr.statusText;
                        }
                        notificar('error', errorMsg);
                    }
                });
            }
        });
    };
    
    // Configurar los event listeners
    const setupEventListeners = function() {
        // Limpiar el formulario cuando se cierra el modal
        $('#addClienteModal, #editClienteModal').on('hidden.bs.modal', function () {
            $(this).find('form')[0].reset();
            $(this).find('input, select, textarea').removeClass('is-valid is-invalid');
        });
        
        // Botón de agregar cliente
        $('#addClienteBtn').on('click', handleAddCliente);
        
        // Botones de editar y eliminar en la tabla (delegación de eventos)
        $('#clientesTable').on('click', '.edit-cliente', function() {
            const cedula = $(this).data('cedula');
            handleEditCliente(cedula);
        });
        
        $('#clientesTable').on('click', '.delete-cliente', function() {
            const cedula = $(this).data('cedula');
            handleDeleteCliente(cedula);
        });
        
        // Botón de guardar cambios al editar
        $('#editClienteBtn').on('click', handleSaveEdit);
    };
    
    // Función de inicialización principal
    const init = function() {
        // Inicializar la tabla
        datatable = initDataTable();
        
        // Configurar validaciones
        setupValidaciones();
        
        // Configurar event listeners
        setupEventListeners();
    };
    
    // Revelar las funciones públicas (patrón revelador)
    return {
        init: init,
        recargarTabla: function() {
            if (datatable) {
                datatable.ajax.reload(null, false);
            }
        }
    };
})();

// Iniciar el módulo cuando el documento esté listo
$(document).ready(function() {
    ClientesModule.init();
});