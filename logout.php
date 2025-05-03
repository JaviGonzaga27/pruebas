<?php
session_start();
require_once 'conexion.php';

// Configuración
define('APP_PATH', '/mecanica2/');

// Log de logout para auditoría
$usuario_id = $_SESSION['usuario_id'] ?? null;
$timestamp = date('Y-m-d H:i:s');
$ip_address = $_SERVER['REMOTE_ADDR'] ?? '';

if ($usuario_id) {
    try {
        // Registrar logout en tabla de auditoría si existe
        if (tableExists($conn, 'auditoria_login')) {
            $stmt = $conn->prepare("INSERT INTO auditoria_login (UsuarioID, Accion, FechaHora, IP) VALUES (:usuario_id, 'logout', :fecha, :ip)");
            $stmt->execute([
                ':usuario_id' => $usuario_id,
                ':fecha' => $timestamp,
                ':ip' => $ip_address
            ]);
        }
    } catch (PDOException $e) {
        error_log("Error al registrar logout: " . $e->getMessage());
    }
}

// Limpiar todas las variables de sesión
$_SESSION = array();

// Destruir la sesión de manera segura
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destruir el token de recordar si existe
if (isset($_COOKIE['recordar_token']) && isset($_COOKIE['recordar_id'])) {
    try {
        $stmt = $conn->prepare("DELETE FROM tokens_autenticacion WHERE Token = :token AND UsuarioID = :id");
        $stmt->bindParam(':token', $_COOKIE['recordar_token']);
        $stmt->bindParam(':id', $_COOKIE['recordar_id']);
        $stmt->execute();
    } catch (PDOException $e) {
        error_log("Error al borrar token: " . $e->getMessage());
    }
    
    // Borrar cookies de manera segura
    setcookie('recordar_token', '', [
        'expires' => time() - 3600,
        'path' => APP_PATH,
        'httponly' => true,
        'secure' => isset($_SERVER['HTTPS']),
        'samesite' => 'Lax'
    ]);
    setcookie('recordar_id', '', [
        'expires' => time() - 3600,
        'path' => APP_PATH,
        'httponly' => true,
        'secure' => isset($_SERVER['HTTPS']),
        'samesite' => 'Lax'
    ]);
}

// Destruir la sesión
session_destroy();

// Headers para prevenir caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Redirigir al login
header("Location: " . APP_PATH . "login.php?logged_out=1");
exit();

/**
 * Verificar si una tabla existe en la base de datos
 */
function tableExists($pdo, $tableName)
{
    try {
        $sql = "SELECT 1 FROM `{$tableName}` LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        return false;
    }
}
?>