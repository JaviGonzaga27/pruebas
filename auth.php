<?php
session_start();

function verificarAutenticacion() {
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: /mecanica2/login.php');
        exit();
    }
}

// Configuración base de la aplicación
define('APP_PATH', '/mecanica2/');
define('INACTIVITY_TIMEOUT', 1800); // 30 minutos

// Verificar si el usuario está logueado
if(!isset($_SESSION['usuario_id'])) {
    header("Location: " . APP_PATH . "login.php");
    exit();
}

// Verificar tiempo de inactividad
if(isset($_SESSION['ultimo_acceso'])) {
    $tiempo_sesion = time() - $_SESSION['ultimo_acceso'];
    if($tiempo_sesion > INACTIVITY_TIMEOUT) {
        // Destruir sesión y token de recordar
        require_once 'conexion.php';
        if(isset($_COOKIE['recordar_token'])) {  // CORRECCIÓN: Paréntesis cerrado aquí
            try {
                $stmt = $conn->prepare("DELETE FROM tokens_autenticacion WHERE Token = :token");
                $stmt->bindParam(':token', $_COOKIE['recordar_token']);
                $stmt->execute();
                
                setcookie('recordar_token', '', time() - 3600, APP_PATH);
                setcookie('recordar_id', '', time() - 3600, APP_PATH);
            } catch(PDOException $e) {
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
    'admin' => ['admin.php', 'usuarios.php', 'configuracion.php', 'dashboard.php'],
    'mecanico' => ['mecanico.php', 'reparaciones.php', 'ordenes.php', 'dashboard.php'],
    'asesor' => ['clientes.php', 'ventas.php', 'cotizaciones.php', 'dashboard.php'],
    'cajero' => ['ventas.php', 'pagos.php', 'dashboard.php'],
    'almacen' => ['inventario.php', 'compras.php', 'dashboard.php']
];

foreach ($permisos as $rol => $paginas) {
    if (in_array($pagina_actual, $paginas)) {
        $roles_permitidos[] = $rol;
    }
}

// El admin siempre tiene acceso
if ($_SESSION['usuario_rol'] === 'admin') {
    $roles_permitidos[] = 'admin';
}

if(!empty($roles_permitidos)) {  // CORRECCIÓN: Paréntesis cerrado aquí
    if(!in_array($_SESSION['usuario_rol'], $roles_permitidos)) {
        header("Location: " . APP_PATH . "acceso_denegado.php");
        exit();
    }
}

// Headers para prevenir caching
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies
?>