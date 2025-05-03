<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/mecanica2/auth.php';

// Configuración de rutas
$base_path = '/mecanica2/';
$assets_path = $base_path . 'assets/';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sistema de Taller Mecánico - Clientes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="icon" href="<?= $assets_path ?>img/kaiadmin/favicon.ico" type="image/x-icon">

    <!-- Fonts and icons -->
    <script src="<?= $assets_path ?>js/plugin/webfont/webfont.min.js"></script>
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
                urls: ["<?= $assets_path ?>css/fonts.min.css"],
            },
            active: function () {
                sessionStorage.fonts = true;
            },
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="<?= $assets_path ?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= $assets_path ?>css/plugins.min.css">
    <link rel="stylesheet" href="<?= $assets_path ?>css/kaiadmin.min.css">
    <link rel="stylesheet" href="<?= $assets_path ?>css/demo.css">
    
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    <style>
        .form-button-action {
            display: flex;
            gap: 10px;
        }
        .modal-body {
            max-height: 70vh;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <?php include '../navbar.php'; ?>

        <div class="main-panel">
            <?php include '../includes/header.php'; ?>

            <div class="container">
                <div class="page-inner">
                    <div class="page-header">
                        <h3 class="fw-bold mb-3">Clientes</h3>
                        <ul class="breadcrumbs mb-3">
                            <li class="nav-home">
                                <a href="../index.php">
                                    <i class="icon-home"></i>
                                </a>
                            </li>
                            <li class="separator">
                                <i class="icon-arrow-right"></i>
                            </li>
                            <li class="nav-item">
                                <a href="#">Clientes</a>
                            </li>
                        </ul>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <h4 class="card-title">Gestión de Clientes</h4>
                                        <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#addClienteModal">
                                            <i class="fa fa-plus"></i>
                                            Agregar Cliente
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <!-- Modal para Agregar Cliente -->
                                    <div class="modal fade" id="addClienteModal" tabindex="-1" aria-labelledby="addClienteModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="addClienteModalLabel">Nuevo Cliente</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form id="addClienteForm">
                                                        <div class="form-group">
                                                            <label>Cédula</label>
                                                            <input type="text" class="form-control" id="cedula" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Nombre</label>
                                                            <input type="text" class="form-control" id="nombre" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Dirección</label>
                                                            <input type="text" class="form-control" id="direccion">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Teléfono</label>
                                                            <input type="text" class="form-control" id="telefono">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Email</label>
                                                            <input type="email" class="form-control" id="email">
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" id="addClienteBtn" class="btn btn-primary">Guardar</button>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal para Editar Cliente -->
                                    <div class="modal fade" id="editClienteModal" tabindex="-1" aria-labelledby="editClienteModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editClienteModalLabel">Editar Cliente</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form id="editClienteForm">
                                                        <input type="hidden" id="editCedula">
                                                        <div class="form-group">
                                                            <label>Cédula</label>
                                                            <input type="text" class="form-control" id="editCedulaDisplay" readonly>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Nombre</label>
                                                            <input type="text" class="form-control" id="editNombre" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Dirección</label>
                                                            <input type="text" class="form-control" id="editDireccion">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Teléfono</label>
                                                            <input type="text" class="form-control" id="editTelefono">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Email</label>
                                                            <input type="email" class="form-control" id="editEmail">
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" id="editClienteBtn" class="btn btn-primary">Guardar Cambios</button>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tabla de Clientes -->
                                    <div class="table-responsive">
                                        <table id="clientesTable" class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Cédula</th>
                                                    <th>Nombre</th>
                                                    <th>Dirección</th>
                                                    <th>Teléfono</th>
                                                    <th>Email</th>
                                                    <th>Fecha Registro</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php include '../includes/footer.php'; ?>
        </div>
    </div>

    <!-- Scripts Core -->
    <script src="<?= $assets_path ?>js/core/jquery-3.7.1.min.js"></script>
    <script src="<?= $assets_path ?>js/core/popper.min.js"></script>
    <script src="<?= $assets_path ?>js/core/bootstrap.min.js"></script>
    
    <!-- Plugins -->
    <script src="<?= $assets_path ?>js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <script src="<?= $assets_path ?>js/plugin/datatables/datatables.min.js"></script>
    <script src="<?= $assets_path ?>js/kaiadmin.min.js"></script>
    <script src="<?= $assets_path ?>js/setting-demo2.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Script para manejar la tabla dinámica -->
    <script>
        // Función para mostrar notificaciones
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

        $(document).ready(function () {
            // Inicializar DataTable
            const table = $('#clientesTable').DataTable({
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
                                    <button type="button" class="btn btn-link btn-primary btn-lg" 
                                        onclick="editarCliente('${row.Cedula}')" title="Editar">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-link btn-danger" 
                                        onclick="eliminarCliente('${row.Cedula}')" title="Eliminar">
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

            // Limpiar el formulario cuando se cierra el modal
            $('#addClienteModal').on('hidden.bs.modal', function () {
                $(this).find('form')[0].reset();
            });

            // Agregar cliente
            $('#addClienteBtn').click(function () {
                const cedula = $('#cedula').val();
                const nombre = $('#nombre').val();
                
                if (!cedula || !nombre) {
                    showNotification('error', 'Cédula y Nombre son campos requeridos');
                    return;
                }

                const $btn = $(this);
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
                        telefono: $('#telefono').val(),
                        email: $('#email').val()
                    },
                    dataType: 'json',
                    success: function (response) {
                        $btn.html(originalHtml);
                        $btn.prop('disabled', false);
                        
                        if (response.success) {
                            $('#addClienteModal').modal('hide');
                            table.ajax.reload(null, false);
                            showNotification('success', response.success);
                        } else if (response.error) {
                            showNotification('error', response.error);
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
                        showNotification('error', errorMsg);
                    }
                });
            });

            // Eliminar cliente
            window.eliminarCliente = function (cedula) {
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
                                    table.ajax.reload(null, false);
                                    showNotification('success', response.success);
                                } else if (response.error) {
                                    showNotification('error', response.error);
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
                                showNotification('error', errorMsg);
                            }
                        });
                    }
                });
            };

            // Editar cliente
            window.editarCliente = function (cedula) {
                $.ajax({
                    url: 'crud_clientes.php?action=read_one&cedula=' + encodeURIComponent(cedula),
                    method: 'GET',
                    dataType: 'json',
                    success: function (cliente) {
                        if (cliente.error) {
                            showNotification('error', cliente.error);
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
                        showNotification('error', errorMsg);
                    }
                });
            };

            // Guardar cambios al editar cliente
            $('#editClienteBtn').click(function () {
                const nombre = $('#editNombre').val();
                
                if (!nombre) {
                    showNotification('error', 'El nombre es requerido');
                    return;
                }

                const $btn = $(this);
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
                        telefono: $('#editTelefono').val(),
                        email: $('#editEmail').val()
                    },
                    dataType: 'json',
                    success: function (response) {
                        $btn.html(originalHtml);
                        $btn.prop('disabled', false);
                        
                        if (response && response.success) {
                            table.ajax.reload(null, false);
                            $('#editClienteModal').modal('hide');
                            showNotification('success', response.success || 'Cliente actualizado correctamente');
                        } else {
                            showNotification('error', response?.error || 'Error al actualizar cliente');
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
                        
                        showNotification('error', errorMsg);
                    }
                });
            });

    // Validaciones para Ecuador
    const validationsEcu = {
        validarCedula: function(cedula) {
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

        validarTelefono: function(telefono) {
            telefono = telefono.replace(/[\s-]/g, '');
            const regexMovil = /^09[0-9]{8}$/;
            const regexFijo = /^0[2-7][0-9]{7}$/;
            return regexMovil.test(telefono) || regexFijo.test(telefono);
        },

        validarEmail: function(email) {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        }
    };

    // Modificar el botón agregar cliente para incluir validaciones
    $('#addClienteBtn').off('click').click(function () {
        const cedula = $('#cedula').val();
        const nombre = $('#nombre').val();
        const telefono = $('#telefono').val();
        const email = $('#email').val();
        
        // Validaciones básicas
        if (!cedula || !nombre) {
            showNotification('error', 'Cédula y Nombre son campos requeridos');
            return;
        }
        
        // Validaciones ecuatorianas
        const errores = [];
        
        if (!validationsEcu.validarCedula(cedula)) {
            errores.push('La cédula no es válida (10 dígitos)');
        }
        
        if (telefono && !validationsEcu.validarTelefono(telefono)) {
            errores.push('El teléfono debe ser un número válido de Ecuador (09xxxxxxxx o 0[2-7]xxxxxxx)');
        }
        
        if (email && !validationsEcu.validarEmail(email)) {
            errores.push('El email no tiene un formato válido');
        }
        
        if (errores.length > 0) {
            showNotification('error', errores.join('<br>'));
            return;
        }

        const $btn = $(this);
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
                    table.ajax.reload(null, false);
                    showNotification('success', response.success);
                } else if (response.error) {
                    showNotification('error', response.error);
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
                showNotification('error', errorMsg);
            }
        });
    });

    // Modificar el botón editar cliente para incluir validaciones
    $('#editClienteBtn').off('click').click(function () {
        const nombre = $('#editNombre').val();
        const telefono = $('#editTelefono').val();
        const email = $('#editEmail').val();
        
        if (!nombre) {
            showNotification('error', 'El nombre es requerido');
            return;
        }
        
        // Validaciones ecuatorianas
        const errores = [];
        
        if (telefono && !validationsEcu.validarTelefono(telefono)) {
            errores.push('El teléfono debe ser un número válido de Ecuador (09xxxxxxxx o 0[2-7]xxxxxxx)');
        }
        
        if (email && !validationsEcu.validarEmail(email)) {
            errores.push('El email no tiene un formato válido');
        }
        
        if (errores.length > 0) {
            showNotification('error', errores.join('<br>'));
            return;
        }

        const $btn = $(this);
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
                    table.ajax.reload(null, false);
                    $('#editClienteModal').modal('hide');
                    showNotification('success', response.success || 'Cliente actualizado correctamente');
                } else {
                    showNotification('error', response?.error || 'Error al actualizar cliente');
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
                
                showNotification('error', errorMsg);
            }
        });
    });

    // Validación en tiempo real
    $('#cedula').on('input', function() {
        const cedula = $(this).val();
        $(this).removeClass('is-invalid is-valid');
        if (cedula.length === 10) {
            if (validationsEcu.validarCedula(cedula)) {
                $(this).addClass('is-valid');
            } else {
                $(this).addClass('is-invalid');
            }
        }
    });
    
    $('#telefono, #editTelefono').on('input', function() {
        const telefono = $(this).val();
        $(this).removeClass('is-invalid is-valid');
        if (telefono) {
            if (validationsEcu.validarTelefono(telefono)) {
                $(this).addClass('is-valid');
            } else {
                $(this).addClass('is-invalid');
            }
        }
    });
    
    $('#email, #editEmail').on('input', function() {
        const email = $(this).val();
        $(this).removeClass('is-invalid is-valid');
        if (email) {
            if (validationsEcu.validarEmail(email)) {
                $(this).addClass('is-valid');
            } else {
                $(this).addClass('is-invalid');
            }
        }
    });
    
    // Limitar cédula a 10 dígitos
    $('#cedula').on('input', function() {
        $(this).val($(this).val().replace(/[^0-9]/g, '').substr(0, 10));
    });
    
    $(document).ready(function() {
    
    // Limpiar validaciones al cerrar modal de agregar
    $('#addClienteModal').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
        // Remover todas las clases de validación
        $(this).find('input, select, textarea').removeClass('is-valid is-invalid');
    });
    
    // Limpiar validaciones al cerrar modal de editar
    $('#editClienteModal').on('hidden.bs.modal', function () {
        // Remover todas las clases de validación
        $(this).find('input, select, textarea').removeClass('is-valid is-invalid');
    });
});
});
    </script>
</body>
</html>