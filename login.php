<?php
session_start();
require_once 'conexion.php';

// Configuración
define('APP_PATH', '/mecanica2/');
define('TOKEN_EXPIRATION', 86400 * 30); // 30 días

// Verificar si ya está logueado
if(isset($_SESSION['usuario_id'])) {
    header("Location: " . APP_PATH);
    exit();
}

$mensaje_error = '';
$email_recordado = '';

// Procesar el formulario de login
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $recordar = isset($_POST['recordar']) ? 1 : 0;
    
    try {
        // Buscar usuario activo
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE Email = :email AND Estado = 1");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        if($stmt->rowCount() == 1) {
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Verificar contraseña
            if(password_verify($password, $usuario['Password'])) {
                // Regenerar ID de sesión para prevenir fixation
                session_regenerate_id(true);
                
                // Iniciar sesión
                $_SESSION['usuario_id'] = $usuario['UsuarioID'];
                $_SESSION['usuario_nombre'] = $usuario['Nombre'] . ' ' . $usuario['Apellido'];
                $_SESSION['usuario_rol'] = $usuario['Rol'];
                $_SESSION['usuario_email'] = $usuario['Email'];
                $_SESSION['ultimo_acceso'] = time();
                
                // Actualizar último login
                $update_stmt = $conn->prepare("UPDATE usuarios SET UltimoLogin = NOW() WHERE UsuarioID = :id");
                $update_stmt->bindParam(':id', $usuario['UsuarioID']);
                $update_stmt->execute();
                
                // Recordar usuario con cookie si está marcado
                if($recordar) {
                    $token = bin2hex(random_bytes(32));
                    $expiracion = time() + TOKEN_EXPIRATION;
                    
                    // Guardar token en la base de datos
                    $token_stmt = $conn->prepare("INSERT INTO tokens_autenticacion (UsuarioID, Token, Expiracion) VALUES (:id, :token, :exp)");
                    $token_stmt->bindParam(':id', $usuario['UsuarioID']);
                    $token_stmt->bindParam(':token', $token);
                    $token_stmt->bindValue(':exp', date('Y-m-d H:i:s', $expiracion));
                    $token_stmt->execute();
                    
                    setcookie('recordar_token', $token, $expiracion, APP_PATH, "", true, true);
                    setcookie('recordar_id', $usuario['UsuarioID'], $expiracion, APP_PATH, "", true, true);
                }
                
                // Redirigir al dashboard principal
                header("Location: " . APP_PATH);
                exit();
            } else {
                $mensaje_error = "Credenciales incorrectas";
            }
        } else {
            $mensaje_error = "Usuario no encontrado o inactivo";
        }
    } catch(PDOException $e) {
        $mensaje_error = "Error en el sistema. Por favor intente más tarde.";
        error_log("Error de login: " . $e->getMessage());
    }
}

// Verificar cookie de recordar usuario
if(empty($_SESSION['usuario_id']) && isset($_COOKIE['recordar_token']) && isset($_COOKIE['recordar_id'])) {
    try {
        $token = $_COOKIE['recordar_token'];
        $usuario_id = $_COOKIE['recordar_id'];
        
        $stmt = $conn->prepare("SELECT u.* FROM usuarios u 
                               JOIN tokens_autenticacion t ON u.UsuarioID = t.UsuarioID
                               WHERE u.UsuarioID = :id AND t.Token = :token AND t.Expiracion > NOW()");
        $stmt->bindParam(':id', $usuario_id);
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        
        if($stmt->rowCount() == 1) {
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
    } catch(PDOException $e) {
        error_log("Error al verificar token: " . $e->getMessage());
    }
}

// Obtener email para recordar si existe
if(isset($_COOKIE['recordar_id'])) {
    try {
        $stmt = $conn->prepare("SELECT Email FROM usuarios WHERE UsuarioID = :id");
        $stmt->bindParam(':id', $_COOKIE['recordar_id']);
        $stmt->execute();
        
        if($stmt->rowCount() == 1) {
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            $email_recordado = $usuario['Email'];
        }
    } catch(PDOException $e) {
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
    <title>Login - Taller Mecánico</title>
    <link rel="stylesheet" href="/mecanica2/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/mecanica2/assets/css/kaiadmin.min.css">
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
            max-width: 400px;
            padding: 2rem;
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
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="logo-login">
                <img src="/mecanica2/assets/img/kaiadmin/logo_dark.svg" alt="Logo Taller Mecánico">
                <h3 class="login-title">Sistema de Taller Mecánico</h3>
            </div>
            
            <?php if(!empty($mensaje_error)): ?>
                <div class="alert alert-danger"><?php echo $mensaje_error; ?></div>
            <?php endif; ?>
            
            <form action="login.php" method="POST" autocomplete="off">
                <div class="form-group mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="<?php echo htmlspecialchars($email_recordado); ?>" 
                           required autofocus>
                </div>
                
                <div class="form-group mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="recordar" name="recordar" 
                           <?php echo isset($_COOKIE['recordar_token']) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="recordar">Recordar mi usuario</label>
                </div>
                
                <button type="submit" class="btn btn-primary btn-login">Iniciar Sesión</button>
                
                <div class="footer-login mt-3">
                    <a href="/mecanica2/recuperar.php">¿Olvidaste tu contraseña?</a>
                </div>
            </form>
        </div>
    </div>

    <script src="/mecanica2/assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="/mecanica2/assets/js/core/bootstrap.min.js"></script>
</body>
</html>