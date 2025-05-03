<?php
session_start();
require_once 'conexion.php';

// Configuración
define('APP_PATH', '/mecanica2/');
define('TOKEN_EXPIRATION', 86400 * 30); // 30 días
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOCKOUT_TIME', 900); // 15 minutos

// Prevenir acceso si ya está logueado
if (isset($_SESSION['usuario_id'])) {
    header("Location: " . APP_PATH);
    exit();
}

// Variables para el formulario
$mensaje_error = '';
$email_recordado = '';
$intentos_fallidos = 0;
$tiempo_bloqueo = 0;

// Verificar bloqueo por intentos fallidos
if (isset($_SESSION['intentos_login'])) {
    $tiempo_transcurrido = time() - $_SESSION['ultimo_intento_login'];
    
    if ($_SESSION['intentos_login'] >= MAX_LOGIN_ATTEMPTS && $tiempo_transcurrido < LOCKOUT_TIME) {
        $tiempo_bloqueo = LOCKOUT_TIME - $tiempo_transcurrido;
        $mensaje_error = "Cuenta bloqueada. Intente nuevamente en " . ceil($tiempo_bloqueo / 60) . " minutos.";
    } elseif ($tiempo_transcurrido >= LOCKOUT_TIME) {
        unset($_SESSION['intentos_login']);
        unset($_SESSION['ultimo_intento_login']);
    }
}

// Procesar el formulario de login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$tiempo_bloqueo) {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $recordar = isset($_POST['recordar']) ? 1 : 0;
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensaje_error = "Formato de email inválido";
    } else {
        try {
            // Obtener IP del usuario para logging
            $ip_address = $_SERVER['REMOTE_ADDR'];
            
            // Buscar usuario activo
            $stmt = $conn->prepare("SELECT * FROM usuarios WHERE Email = :email AND Estado = 1");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            if ($stmt->rowCount() === 1) {
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Verificar contraseña
                if (password_verify($password, $usuario['Password'])) {
                    // Login exitoso
                    session_regenerate_id(true);
                    
                    // Iniciar sesión
                    $_SESSION['usuario_id'] = $usuario['UsuarioID'];
                    $_SESSION['usuario_nombre'] = $usuario['Nombre'] . ' ' . $usuario['Apellido'];
                    $_SESSION['usuario_rol'] = $usuario['Rol'];
                    $_SESSION['usuario_email'] = $usuario['Email'];
                    $_SESSION['ultimo_acceso'] = time();
                    $_SESSION['ip_address'] = $ip_address;
                    
                    // Limpiar intentos de login
                    unset($_SESSION['intentos_login']);
                    unset($_SESSION['ultimo_intento_login']);
                    
                    // Actualizar último login en la base de datos
                    $update_stmt = $conn->prepare("UPDATE usuarios SET UltimoLogin = NOW() WHERE UsuarioID = :id");
                    $update_stmt->bindParam(':id', $usuario['UsuarioID']);
                    $update_stmt->execute();
                    
                    // Recordar usuario si está marcado
                    if ($recordar) {
                        $token = bin2hex(random_bytes(32));
                        $expiracion = time() + TOKEN_EXPIRATION;
                        
                        // Guardar token en la base de datos
                        $token_stmt = $conn->prepare("INSERT INTO tokens_autenticacion (UsuarioID, Token, Expiracion) VALUES (:id, :token, :exp)");
                        $token_stmt->bindParam(':id', $usuario['UsuarioID']);
                        $token_stmt->bindParam(':token', $token);
                        $token_stmt->bindValue(':exp', date('Y-m-d H:i:s', $expiracion));
                        $token_stmt->execute();
                        
                        setcookie('recordar_token', $token, [
                            'expires' => $expiracion,
                            'path' => APP_PATH,
                            'httponly' => true,
                            'secure' => isset($_SERVER['HTTPS']),
                            'samesite' => 'Lax'
                        ]);
                        setcookie('recordar_id', $usuario['UsuarioID'], [
                            'expires' => $expiracion,
                            'path' => APP_PATH,
                            'httponly' => true,
                            'secure' => isset($_SERVER['HTTPS']),
                            'samesite' => 'Lax'
                        ]);
                    }
                    
                    // Redirigir al dashboard
                    header("Location: " . APP_PATH);
                    exit();
                } else {
                    $mensaje_error = "Credenciales incorrectas";
                }
            } else {
                $mensaje_error = "Usuario no encontrado o inactivo";
            }
            
            // Registrar intento fallido
            $_SESSION['intentos_login'] = ($_SESSION['intentos_login'] ?? 0) + 1;
            $_SESSION['ultimo_intento_login'] = time();
            
        } catch (PDOException $e) {
            $mensaje_error = "Error en el sistema. Por favor intente más tarde.";
            error_log("Error de login: " . $e->getMessage());
        }
    }
}

// Verificar cookie de recordar usuario
if (empty($_SESSION['usuario_id']) && isset($_COOKIE['recordar_token']) && isset($_COOKIE['recordar_id'])) {
    try {
        $token = $_COOKIE['recordar_token'];
        $usuario_id = $_COOKIE['recordar_id'];
        
        $stmt = $conn->prepare("SELECT u.* FROM usuarios u 
                               JOIN tokens_autenticacion t ON u.UsuarioID = t.UsuarioID
                               WHERE u.UsuarioID = :id AND t.Token = :token AND t.Expiracion > NOW()");
        $stmt->bindParam(':id', $usuario_id);
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        
        if ($stmt->rowCount() === 1) {
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Regenerar ID de sesión
            session_regenerate_id(true);
            
            $_SESSION['usuario_id'] = $usuario['UsuarioID'];
            $_SESSION['usuario_nombre'] = $usuario['Nombre'] . ' ' . $usuario['Apellido'];
            $_SESSION['usuario_rol'] = $usuario['Rol'];
            $_SESSION['usuario_email'] = $usuario['Email'];
            $_SESSION['ultimo_acceso'] = time();
            
            // Actualizar último login
            $update_stmt = $conn->prepare("UPDATE usuarios SET UltimoLogin = NOW() WHERE UsuarioID = :id");
            $update_stmt->bindParam(':id', $usuario['UsuarioID']);
            $update_stmt->execute();
            
            header("Location: " . APP_PATH);
            exit();
        } else {
            // Token inválido, borrar cookies
            setcookie('recordar_token', '', time() - 3600, APP_PATH);
            setcookie('recordar_id', '', time() - 3600, APP_PATH);
        }
    } catch (PDOException $e) {
        error_log("Error al verificar token: " . $e->getMessage());
    }
}

// Obtener email para recordar si existe
if (isset($_COOKIE['recordar_id'])) {
    try {
        $stmt = $conn->prepare("SELECT Email FROM usuarios WHERE UsuarioID = :id");
        $stmt->bindParam(':id', $_COOKIE['recordar_id']);
        $stmt->execute();
        
        if ($stmt->rowCount() === 1) {
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            $email_recordado = $usuario['Email'];
        }
    } catch (PDOException $e) {
        error_log("Error al obtener email recordado: " . $e->getMessage());
    }
}

// Headers para prevenir caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Taller Mecánico</title>
    <link rel="stylesheet" href="<?= APP_PATH ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= APP_PATH ?>assets/css/kaiadmin.min.css">
    <style>
        .login-container {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
        }
        .login-card {
            width: 100%;
            max-width: 500px;
            padding: 1rem;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            background-color: white;
        }
        .logo-login {
            text-align: center;
            margin-bottom: 2rem;
        }
        .logo-login img {
            height: 60px;
            margin-bottom: 1rem;
        }
        .login-title {
            text-align: center;
            margin-bottom: 1.5rem;
            font-weight: 600;
            color: #343a40;
        }
        .btn-login {
            width: 100%;
            padding: 0.75rem;
            font-weight: 600;
            margin-top: 1rem;
        }
        .form-control {
            height: calc(2.5em + 0.75rem + 2px);
            border-radius: 0.25rem;
        }
        .footer-login {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.85rem;
            color: #6c757d;
        }
        .lockout-timer {
            color: #dc3545;
            font-weight: bold;
            text-align: center;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="logo-login">
                <img src="<?= APP_PATH ?>assets/img/kaiadmin/logo_dark.png" alt="Logo Taller Mecánico">
                <h3 class="login-title">Sistema de Taller Mecánico</h3>
            </div>
            
            <?php if (!empty($mensaje_error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($mensaje_error) ?></div>
            <?php endif; ?>
            
            <?php if ($tiempo_bloqueo > 0): ?>
                <div class="lockout-timer" id="lockout-timer"></div>
            <?php else: ?>
                <form action="login.php" method="POST" autocomplete="off">
                    <div class="form-group mb-3">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?= htmlspecialchars($email_recordado) ?>" 
                               required autofocus autocomplete="email">
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" 
                               required autocomplete="current-password">
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="recordar" name="recordar" 
                               <?= isset($_COOKIE['recordar_token']) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="recordar">Recordar mi usuario</label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-login">Iniciar Sesión</button>
                    
                    <div class="footer-login mt-3">
                        <a href="<?= APP_PATH ?>recuperar.php">¿Olvidaste tu contraseña?</a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <script src="<?= APP_PATH ?>assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="<?= APP_PATH ?>assets/js/core/bootstrap.min.js"></script>
    
    <?php if ($tiempo_bloqueo > 0): ?>
    <script>
        let timeLeft = <?= $tiempo_bloqueo ?>;
        const timerElement = document.getElementById('lockout-timer');
        
        function updateTimer() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            
            timerElement.textContent = `Cuenta bloqueada. Intente nuevamente en ${minutes}:${seconds.toString().padStart(2, '0')}`;
            
            if (timeLeft <= 0) {
                location.reload();
            } else {
                timeLeft--;
                setTimeout(updateTimer, 1000);
            }
        }
        
        updateTimer();
    </script>
    <?php endif; ?>
</body>
</html>