/**
 * Sistema de Taller Mecánico - Utilidades Compartidas
 * Este archivo contiene funciones de utilidad que pueden ser reutilizadas por múltiples módulos
 */

// Namespace para utilidades
const Utils = (function() {
    
    // Función para mostrar notificaciones
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
    
    // Función para formatear fechas
    const formatearFecha = function(dateString, formato = 'es-ES') {
        if (!dateString) return '';
        const date = new Date(dateString);
        return date.toLocaleDateString(formato);
    };
    
    // Función para manejar errores AJAX
    const manejarErrorAjax = function(xhr, mensajeBase = 'Error en la solicitud: ') {
        let errorMsg = mensajeBase;
        try {
            const res = JSON.parse(xhr.responseText);
            errorMsg += res.error || xhr.statusText;
        } catch (e) {
            errorMsg += xhr.statusText;
        }
        return errorMsg;
    };
    
    // Función para validar campos de formulario
    const validarCampos = function(campos, prefijo = '') {
        let isValid = true;
        
        for (const campo of campos) {
            const selector = prefijo ? `#${prefijo}${campo}` : `#${campo}`;
            const $campo = $(selector);
            
            if (!$campo.val()) {
                $campo.addClass('is-invalid');
                isValid = false;
            } else {
                $campo.removeClass('is-invalid');
            }
        }
        
        return isValid;
    };
    
    // Función para limpiar formularios y validaciones
    const limpiarFormulario = function(formSelector) {
        const $form = $(formSelector);
        $form[0].reset();
        $form.find('input, select, textarea').removeClass('is-valid is-invalid');
    };
    
    // Función para manejar botón durante peticiones AJAX
    const manejarEstadoBoton = function($btn, estado, texto = null) {
        if (estado === 'cargando') {
            $btn.data('original-html', $btn.html());
            $btn.html('<i class="fa fa-spinner fa-spin"></i> ' + (texto || 'Cargando...'));
            $btn.prop('disabled', true);
        } else if (estado === 'normal') {
            const originalHtml = $btn.data('original-html') || texto || 'Guardar';
            $btn.html(originalHtml);
            $btn.prop('disabled', false);
        }
    };
    
    // Validaciones comunes para Ecuador
    const validacionesEcuador = {
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
        },
        
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
        }
    };
    
    // Exponer las funciones públicas
    return {
        notificar: notificar,
        formatearFecha: formatearFecha,
        manejarErrorAjax: manejarErrorAjax,
        validarCampos: validarCampos,
        limpiarFormulario: limpiarFormulario,
        manejarEstadoBoton: manejarEstadoBoton,
        validacionesEcuador: validacionesEcuador
    };
})();