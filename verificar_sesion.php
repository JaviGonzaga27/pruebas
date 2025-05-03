<?php
session_start();


if(!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Verificar permisos según el rol si es necesario
$pagina_actual = basename($_SERVER['PHP_SELF']);
$roles_permitidos = [];

// Configurar qué roles pueden acceder a qué páginas
switch($pagina_actual) {
    case 'admin.php':
        $roles_permitidos = ['admin'];
        break;
    case 'mecanico.php':
        $roles_permitidos = ['mecanico', 'admin'];
        break;
    // Agregar más casos según sea necesario
}

if(!empty($roles_permitidos) && !in_array($_SESSION['usuario_rol'], $roles_permitidos)) {
    header("Location: acceso_denegado.php");
    exit();
}


