<?php
session_start();
require_once 'conexion.php';

// Configuración
define('APP_PATH', '/mecanica2/');

// Destruir todas las variables de sesión
$_SESSION = array();

// Borrar token de autenticación si existe
if(isset($_COOKIE['recordar_token']) && isset($_COOKIE['recordar_id'])) {
    try {
        $stmt = $conn->prepare("DELETE FROM tokens_autenticacion WHERE Token = :token AND UsuarioID = :id");
        $stmt->bindParam(':token', $_COOKIE['recordar_token']);
        $stmt->bindParam(':id', $_COOKIE['recordar_id']);
        $stmt->execute();
    } catch(PDOException $e) {
        error_log("Error al borrar token: " . $e->getMessage());
    }
    
    setcookie('recordar_token', '', time() - 3600, APP_PATH);
    setcookie('recordar_id', '', time() - 3600, APP_PATH);
}

// Destruir la sesión
session_destroy();

// Headers para prevenir caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Redirigir al login
header("Location: " . APP_PATH . "login.php");
exit();
?>