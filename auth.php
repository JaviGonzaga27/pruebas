<?php
session_start();

define('APP_PATH', '/mecanica2/');
define('INACTIVITY_TIMEOUT', 1800); // 30 minutos

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: " . APP_PATH . "login.php");
    exit();
}

// Función para verificar autenticación
function verificarAutenticacion() {
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: ' . APP_PATH . 'login.php');
        exit();
    }
}

// Verificar tiempo de inactividad
if (isset($_SESSION['ultimo_acceso'])) {
    $tiempo_sesion = time() - $_SESSION['ultimo_acceso'];
    if ($tiempo_sesion > INACTIVITY_TIMEOUT) {
        // Destruir sesión y token de recordar
        require_once 'conexion.php';
        
        if (isset($_COOKIE['recordar_token'])) {
            try {
                $stmt = $conn->prepare("DELETE FROM tokens_autenticacion WHERE Token = :token");
                $stmt->bindParam(':token', $_COOKIE['recordar_token']);
                $stmt->execute();
                
                setcookie('recordar_token', '', time() - 3600, APP_PATH);
                setcookie('recordar_id', '', time() - 3600, APP_PATH);
            } catch (PDOException $e) {
                error_log("Error al borrar token: " . $e->getMessage());
            }
        }
        
        session_unset();
        session_destroy();
        header("Location: " . APP_PATH . "login.php?timeout=1");
        exit();
    }
}

$_SESSION['ultimo_acceso'] = time();

// Verificar roles y permisos
$pagina_actual = basename($_SERVER['PHP_SELF']);
$roles_permitidos = [];

// Configuración de permisos por rol
$permisos = [
    'admin' => ['*'], // Acceso completo
    'mecanico' => ['mecanico.php', 'reparaciones.php', 'ordenes.php', 'dashboard.php'],
    'asesor' => ['clientes.php', 'ventas.php', 'cotizaciones.php', 'dashboard.php'],
    'cajero' => ['ventas.php', 'pagos.php', 'dashboard.php'],
    'almacen' => ['inventario.php', 'compras.php', 'dashboard.php']
];

// Verificar si la página actual está en los permisos del rol
foreach ($permisos as $rol => $paginas) {
    if ($rol === 'admin' && in_array('*', $paginas)) {
        // Admin tiene acceso a todo
        if ($_SESSION['usuario_rol'] === 'admin') {
            break;
        }
    } elseif (in_array($pagina_actual, $paginas)) {
        $roles_permitidos[] = $rol;
    }
}

// Verificar acceso
if (!empty($roles_permitidos) && !in_array($_SESSION['usuario_rol'], $roles_permitidos)) {
    header("Location: " . APP_PATH . "acceso_denegado.php");
    exit();
}

// Headers para prevenir caching y mejorar seguridad
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
header("X-Frame-Options: SAMEORIGIN");
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");