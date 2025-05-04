<?php
// includes/scripts.php - Scripts centralizados y optimizados
$assets_path = $config['assets_path'] ?? '/mecanica2/assets/';

// Definir scripts básicos que siempre se cargan
$core_scripts = [
    'js/core/jquery-3.7.1.min.js',
    'js/core/popper.min.js',
    'js/core/bootstrap.min.js',
    'js/plugin/jquery-scrollbar/jquery.scrollbar.min.js',
    'js/kaiadmin.min.js'
];

// Definir scripts que podrían cargarse condicionalmente
$conditional_scripts = [
    'chart' => 'js/plugin/chart.js/chart.min.js',
    'sparkline' => 'js/plugin/jquery.sparkline/jquery.sparkline.min.js',
    'datatables' => 'js/plugin/datatables/datatables.min.js',
    'notify' => 'js/plugin/bootstrap-notify/bootstrap-notify.min.js',
    'sweetalert' => 'js/plugin/sweetalert/sweetalert.min.js',
    'maps' => [
        'js/plugin/jsvectormap/jsvectormap.min.js',
        'js/plugin/jsvectormap/world.js'
    ]
];

// Lista de scripts específicos de la página
$page_modules = $page_modules ?? [];

// Establecer tiempo de expiración para cache (1 semana)
$cache_expiry = 60 * 60 * 24 * 7;
$version = '1.0.' . date('Ymd');

// Función de compresión condicional
function compressScriptUrl($url, $assets_path, $version) {
    return $assets_path . $url . '?v=' . $version;
}
?>

<!-- Session check -->
<script>
    // Verificar sesión cada 5 minutos
    setInterval(function() {
        fetch('check_session.php')
            .then(response => response.json())
            .then(data => {
                if (!data.valid) {
                    window.location.href = 'login.php?session_expired=1';
                }
            })
            .catch(error => {
                console.error('Error checking session:', error);
            });
    }, 300000);
</script>