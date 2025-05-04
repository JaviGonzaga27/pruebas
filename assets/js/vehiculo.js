/**
 * Sistema de Taller Mecánico - Módulo de Vehículos
 * Este archivo contiene todas las funcionalidades JavaScript para la gestión de vehículos
 */

// Namespace para evitar conflictos globales
const VehiculosModule = (function() {
    // Variables privadas del módulo
    let datatable = null;
    
    // Configuración de validaciones específicas para Ecuador
    const validaciones = {
        placa: function(placa) {
            const regexPlacaActual = /^[A-Z]{3}-[0-9]{4}$/;
            const regexPlacaAntigua = /^[A-Z]{2}-[0-9]{4}$/;
            return regexPlacaActual.test(placa) || regexPlacaAntigua.test(placa);
        },
        
        formatearPlaca: function(placa) {
            let placaFormatted = placa.toUpperCase().replace(/[^A-Z0-9]/g, '');
            
            // Formatear automáticamente la placa
            if (placaFormatted.length > 2 && placaFormatted.length <= 6) {
                placaFormatted = placaFormatted.slice(0, 2) + '-' + placaFormatted.slice(2);
            } else if (placaFormatted.length > 3) {
                placaFormatted = placaFormatted.slice(0, 3) + '-' + placaFormatted.slice(3);
            }
            
            if (placaFormatted.length > 8) {
                placaFormatted = placaFormatted.slice(0, 8);
            }
            
            return placaFormatted;
        },
        
        validarAnio: function(anio) {
            const anioActual = new Date().getFullYear();
            return anio >= 1900 && anio <= anioActual + 1;
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
    
    // Inicialización de la tabla de vehículos
    const initDataTable = function() {
        datatable = $('#vehiculosTable').DataTable({
            ajax: {
                url: 'crud_vehiculos.php?action=read',
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
                    
                    $('#vehiculosTable tbody').html(`
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
                { data: 'VehiculoID' },
                { 
                    data: 'ClienteNombre',
                    render: function(data, type, row) {
                        return `${data} (${row.ClienteCedula})`;
                    }
                },
                { data: 'Marca' },
                { data: 'Modelo' },
                { data: 'Anio' },
                { data: 'Placa' },
                {
                    data: null,
                    render: function(data, type, row) {
                        return `
                            <div class="form-button-action">
                                <button type="button" class="btn btn-link btn-info btn-lg view-vehiculo" 
                                    data-id="${row.VehiculoID}" title="Ver detalles">
                                    <i class="fa fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-link btn-primary btn-lg edit-vehiculo" 
                                    data-id="${row.VehiculoID}" title="Editar">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-link btn-danger delete-vehiculo" 
                                    data-id="${row.VehiculoID}" title="Eliminar">
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
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50, 100]
        });
        
        return datatable;
    };
    
    // Cargar lista de clientes para los selects
    const cargarClientes = function(selector, selectedClienteID = null) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: 'crud_vehiculos.php?action=get_clientes',
                method: 'GET',
                success: function(response) {
                    try {
                        const clientes = typeof response === 'string' ? JSON.parse(response) : response;
                        const select = $(selector);
                        select.empty();
                        select.append('<option value="">Seleccione un cliente</option>');
                        
                        clientes.forEach(cliente => {
                            const optionText = `${cliente.Nombre} (${cliente.Cedula})`;
                            const option = new Option(optionText, cliente.ClienteID);
                            if (cliente.ClienteID == selectedClienteID) {
                                option.selected = true;
                            }
                            select.append(option);
                        });
                        resolve(clientes);
                    } catch (e) {
                        console.error('Error al parsear clientes:', e);
                        notificar('error', 'Error al cargar la lista de clientes');
                        reject(e);
                    }
                },
                error: function(xhr) {
                    console.error('Error en AJAX:', xhr.responseText);
                    notificar('error', 'Error al obtener clientes del servidor');
                    reject(xhr);
                }
            });
        });
    };
    
    // Configurar validaciones en tiempo real
    const setupValidaciones = function() {
        // Validación de placa en tiempo real
        $('#placa, #editPlaca').on('input', function() {
            const placa = validaciones.formatearPlaca($(this).val());
            $(this).val(placa);
            $(this).removeClass('is-invalid is-valid');
            
            if (placa.length >= 7) {
                if (validaciones.placa(placa)) {
                    $(this).addClass('is-valid');
                } else {
                    $(this).addClass('is-invalid');
                }
            }
        });
        
        // Validación de año en tiempo real
        $('#anio, #editAnio').on('input', function() {
            const anio = $(this).val();
            const anioActual = new Date().getFullYear();
            
            if (anio) {
                if (validaciones.validarAnio(anio)) {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                } else {
                    $(this).removeClass('is-valid').addClass('is-invalid');
                }
            }
        });
        
        // Validación de kilometraje
        $('#kilometraje, #editKilometraje').on('input', function() {
            const km = $(this).val();
            $(this).val($(this).val().replace(/[^0-9]/g, ''));
            
            if (km >= 0) {
                $(this).removeClass('is-invalid').addClass('is-valid');
            } else {
                $(this).removeClass('is-valid').addClass('is-invalid');
            }
        });
        
        // Limitar VIN a 17 caracteres
        $('#vin, #editVin').on('input', function() {
            $(this).val($(this).val().toUpperCase().replace(/[^A-Z0-9]/g, '').substr(0, 17));
        });
    };

    // Validar formulario
    const validarFormulario = function(form) {
        const required = {
            'clienteID': 'Cliente',
            'marca': 'Marca',
            'modelo': 'Modelo',
            'anio': 'Año',
            'placa': 'Placa'
        };
        
        let isValid = true;
        const errores = [];
        
        // Validar campos requeridos
        for (const [campo, label] of Object.entries(required)) {
            const $campo = form === 'add' ? $(`#${campo}`) : $(`#edit${campo.charAt(0).toUpperCase() + campo.slice(1)}`);
            
            if (!$campo.val()) {
                $campo.addClass('is-invalid');
                isValid = false;
            } else {
                $campo.removeClass('is-invalid');
            }
        }
        
        if (!isValid) {
            errores.push('Por favor complete todos los campos requeridos');
        }
        
        // Validar placa
        const placa = form === 'add' ? $('#placa').val() : $('#editPlaca').val();
        if (!validaciones.placa(placa)) {
            errores.push('La placa debe tener formato ecuatoriano (AAA-1234 o AA-1234)');
            isValid = false;
        }
        
        // Validar año
        const anio = form === 'add' ? $('#anio').val() : $('#editAnio').val();
        const anioActual = new Date().getFullYear();
        if (!validaciones.validarAnio(anio)) {
            errores.push(`El año debe estar entre 1900 y ${anioActual + 1}`);
            isValid = false;
        }
        
        if (errores.length > 0) {
            notificar('error', errores.join('<br>'));
        }
        
        return isValid;
    };
    
    // Handler para agregar vehículo
    const handleAddVehiculo = function() {
        if (!validarFormulario('add')) {
            return false;
        }

        const $btn = $('#addVehiculoBtn');
        const originalHtml = $btn.html();
        $btn.html('<i class="fa fa-spinner fa-spin"></i> Guardando...');
        $btn.prop('disabled', true);

        const formData = {
            clienteID: $('#clienteID').val(),
            marca: $('#marca').val(),
            modelo: $('#modelo').val(),
            anio: $('#anio').val(),
            placa: $('#placa').val(),
            vin: $('#vin').val() || null,
            kilometraje: $('#kilometraje').val() || 0,
            ultimoServicio: $('#ultimoServicio').val() || null,
            proximoServicio: $('#proximoServicio').val() || null,
            color: $('#color').val() || null,
            tipo: $('#tipo').val() || 'Automovil',
            combustible: $('#combustible').val() || 'Gasolina',
            transmision: $('#transmision').val() || null,
            observaciones: $('#observaciones').val() || null
        };

        $.ajax({
            url: 'crud_vehiculos.php?action=create',
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {
                $btn.html(originalHtml);
                $btn.prop('disabled', false);
                
                if (response.success) {
                    $('#addVehiculoModal').modal('hide');
                    $('#addVehiculoForm')[0].reset();
                    datatable.ajax.reload(null, false);
                    notificar('success', response.success);
                } else if (response.error) {
                    notificar('error', response.error);
                }
            },
            error: function(xhr) {
                $btn.html(originalHtml);
                $btn.prop('disabled', false);
                
                let errorMsg = 'Error al guardar vehículo: ';
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
    
    // Handler para ver detalles del vehículo
    const handleViewVehiculo = function(vehiculoID) {
        $.ajax({
            url: 'crud_vehiculos.php?action=read_one&vehiculoID=' + vehiculoID,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.error) {
                    notificar('error', response.error);
                    return;
                }

                // Función para formatear fechas
                function formatDate(dateString) {
                    if (!dateString) return 'N/A';
                    const date = new Date(dateString);
                    return date.toLocaleDateString('es-ES');
                }

                // Llenar el modal con los datos
                $('#viewVehiculoID').text(response.VehiculoID || 'N/A');
                $('#viewCliente').text(response.ClienteNombre ? `${response.ClienteNombre} (${response.ClienteCedula || 'Sin cédula'})` : 'N/A');
                $('#viewMarca').text(response.Marca || 'N/A');
                $('#viewModelo').text(response.Modelo || 'N/A');
                $('#viewAnio').text(response.Anio || 'N/A');
                $('#viewPlaca').text(response.Placa || 'N/A');
                $('#viewVin').text(response.VIN || 'N/A');
                $('#viewKilometraje').text(response.Kilometraje ? response.Kilometraje + ' km' : 'N/A');
                $('#viewColor').text(response.Color || 'N/A');
                $('#viewTipo').text(response.Tipo || 'N/A');
                $('#viewCombustible').text(response.Combustible || 'N/A');
                $('#viewTransmision').text(response.Transmision || 'N/A');
                $('#viewUltimoServicio').text(formatDate(response.UltimoServicio));
                $('#viewProximoServicio').text(formatDate(response.ProximoServicio));
                $('#viewObservaciones').text(response.Observaciones || 'Ninguna');

                $('#viewVehiculoModal').modal('show');
            },
            error: function(xhr) {
                let errorMsg = 'Error al cargar los detalles del vehículo: ';
                try {
                    const res = JSON.parse(xhr.responseText);
                    errorMsg += res.error || xhr.statusText;
                } catch(e) {
                    errorMsg += xhr.statusText;
                }
                notificar('error', errorMsg);
            }
        });
    };
    
    // Handler para editar vehículo
    const handleEditVehiculo = function(vehiculoID) {
        $.ajax({
            url: 'crud_vehiculos.php?action=read_one&vehiculoID=' + vehiculoID,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.error) {
                    notificar('error', response.error);
                    return;
                }

                // Llenar el formulario de edición
                $('#editVehiculoID').val(response.VehiculoID);
                $('#editMarca').val(response.Marca);
                $('#editModelo').val(response.Modelo);
                $('#editAnio').val(response.Anio);
                $('#editPlaca').val(response.Placa);
                $('#editVin').val(response.VIN || '');
                $('#editKilometraje').val(response.Kilometraje || 0);
                $('#editUltimoServicio').val(response.UltimoServicio || '');
                $('#editProximoServicio').val(response.ProximoServicio || '');
                $('#editColor').val(response.Color || '');
                $('#editTipo').val(response.Tipo || 'Automovil');
                $('#editCombustible').val(response.Combustible || 'Gasolina');
                $('#editTransmision').val(response.Transmision || '');
                $('#editObservaciones').val(response.Observaciones || '');

                // Cargar clientes y seleccionar el correcto
                cargarClientes('#editClienteID', response.ClienteID);
                
                $('#editVehiculoModal').modal('show');
            },
            error: function(xhr) {
                let errorMsg = 'Error al cargar vehículo: ';
                try {
                    const res = JSON.parse(xhr.responseText);
                    errorMsg += res.error || xhr.statusText;
                } catch (e) {
                    errorMsg += xhr.statusText;
                }
                notificar('error', errorMsg);
            }
        });
    };
    
    // Handler para guardar cambios al editar vehículo
    const handleSaveEdit = function() {
        if (!validarFormulario('edit')) {
            return false;
        }

        const $btn = $('#editVehiculoBtn');
        const originalHtml = $btn.html();
        $btn.html('<i class="fa fa-spinner fa-spin"></i> Guardando...');
        $btn.prop('disabled', true);

        const formData = {
            vehiculoID: $('#editVehiculoID').val(),
            clienteID: $('#editClienteID').val(),
            marca: $('#editMarca').val(),
            modelo: $('#editModelo').val(),
            anio: $('#editAnio').val(),
            placa: $('#editPlaca').val(),
            vin: $('#editVin').val() || null,
            kilometraje: $('#editKilometraje').val() || 0,
            ultimoServicio: $('#editUltimoServicio').val() || null,
            proximoServicio: $('#editProximoServicio').val() || null,
            color: $('#editColor').val() || null,
            tipo: $('#editTipo').val() || 'Automovil',
            combustible: $('#editCombustible').val() || 'Gasolina',
            transmision: $('#editTransmision').val() || null,
            observaciones: $('#editObservaciones').val() || null
        };

        $.ajax({
            url: 'crud_vehiculos.php?action=update',
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                $btn.html(originalHtml);
                $btn.prop('disabled', false);
                
                if (response.success) {
                    $('#editVehiculoModal').modal('hide');
                    datatable.ajax.reload(null, false);
                    notificar('success', response.success);
                } else if (response.error) {
                    notificar('error', response.error);
                }
            },
            error: function(xhr) {
                $btn.html(originalHtml);
                $btn.prop('disabled', false);
                
                let errorMsg = 'Error al actualizar vehículo: ';
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
    
    // Handler para eliminar vehículo
    const handleDeleteVehiculo = function(vehiculoID) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡Esta acción no se puede deshacer!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            allowOutsideClick: false,
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        url: 'crud_vehiculos.php?action=delete',
                        method: 'POST',
                        data: { vehiculoID: vehiculoID },
                        dataType: 'json',
                        success: function(response) {
                            resolve(response);
                        },
                        error: function(xhr) {
                            let errorMsg = 'Error en la conexión: ';
                            try {
                                const res = JSON.parse(xhr.responseText);
                                errorMsg += res.error || xhr.statusText;
                            } catch (e) {
                                errorMsg += xhr.statusText;
                            }
                            reject(errorMsg);
                        }
                    });
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                if (result.value && result.value.success) {
                    datatable.ajax.reload(null, false);
                    notificar('success', result.value.success);
                } else if (result.value && result.value.error) {
                    notificar('error', result.value.error);
                } else {
                    notificar('error', 'Respuesta inesperada del servidor');
                }
            }
        }).catch((error) => {
            notificar('error', error);
        });
    };
    
    // Configurar los event listeners
    const setupEventListeners = function() {
        // Limpiar el formulario cuando se cierra el modal
        $('#addVehiculoModal, #editVehiculoModal, #viewVehiculoModal').on('hidden.bs.modal', function () {
            $(this).find('form').trigger('reset');
            $(this).find('input, select, textarea').removeClass('is-valid is-invalid');
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open');
        });
        
        // Cargar clientes al abrir modal de agregar
        $('#addVehiculoModal').on('show.bs.modal', function () {
            cargarClientes('#clienteID');
        });
        
        // Botón de agregar vehículo
        $('#addVehiculoBtn').on('click', handleAddVehiculo);
        
        // Botón de guardar cambios al editar
        $('#editVehiculoBtn').on('click', handleSaveEdit);
        
        // Botones de ver, editar y eliminar en la tabla (delegación de eventos)
        $('#vehiculosTable').on('click', '.view-vehiculo', function() {
            const vehiculoID = $(this).data('id');
            handleViewVehiculo(vehiculoID);
        });
        
        $('#vehiculosTable').on('click', '.edit-vehiculo', function() {
            const vehiculoID = $(this).data('id');
            handleEditVehiculo(vehiculoID);
        });
        
        $('#vehiculosTable').on('click', '.delete-vehiculo', function() {
            const vehiculoID = $(this).data('id');
            handleDeleteVehiculo(vehiculoID);
        });
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
    VehiculosModule.init();
});