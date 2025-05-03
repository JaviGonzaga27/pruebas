<?php
// Este archivo parece ser una plantilla genérica que duplica funcionalidad
// Recomiendo usar la estructura modular que creamos con sidebar.php, header.php e includes
// En lugar de tener este archivo, podemos crear una estructura de plantilla base

require_once $_SERVER['DOCUMENT_ROOT'] . '/mecanica2/auth.php';

// Configuración
$config = [
    'base_path' => '/mecanica2/',
    'assets_path' => '/mecanica2/assets/',
    'page_title' => 'Sistema de Taller Mecánico',
    'page_description' => 'Sistema de gestión para talleres mecánicos'
];

// Variables del usuario
$usuario = [
    'nombre' => $_SESSION['usuario_nombre'] ?? 'Usuario',
    'email' => $_SESSION['usuario_email'] ?? '',
    'rol' => $_SESSION['usuario_rol'] ?? 'guest'
];

// Páginas del dashboard (puedes mover esto a un archivo de configuración)
$dashboard_pages = [
    'inicio' => ['icon' => 'fa-home', 'title' => 'Inicio', 'file' => 'index.php'],
    'clientes' => ['icon' => 'fa-users', 'title' => 'Clientes', 'file' => 'clientes.php'],
    'vehiculos' => ['icon' => 'fa-car', 'title' => 'Vehículos', 'file' => 'vehiculos.php'],
    'inventario' => ['icon' => 'fa-boxes', 'title' => 'Inventario', 'file' => 'inventario.php'],
    'reportes' => ['icon' => 'fa-chart-bar', 'title' => 'Reportes', 'file' => 'reportes.php']
];

// En lugar de duplicar código, usemos la estructura modular:
// include 'includes/head.php';
// include 'includes/sidebar.php';
// include 'includes/header.php';
// include 'content.php'; // El contenido específico de la página
// include 'includes/footer.php';
// include 'includes/scripts.php';

// Aquí está la estructura refactorizada:
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= htmlspecialchars($config['page_title']) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="icon" href="<?= $config['assets_path'] ?>img/kaiadmin/favicon.ico" type="image/x-icon">

    <!-- Fonts and icons -->
    <?php include 'includes/head_assets.php'; ?>
</head>
<body>
    <div class="wrapper">
        <?php include 'navbar.php'; ?>

        <div class="main-panel">
            <?php include 'includes/header.php'; ?>

            <div class="container">
                <div class="page-inner">
                    <!-- Contenido específico de la página -->
                    <?php
                    // El contenido específico de cada página debería estar en archivos separados
                    // Esto permite reutilizar la estructura base para diferentes páginas
                    if (file_exists($content_file)) {
                        include $content_file;
                    } else {
                        // Contenido por defecto o dashboard
                        include 'pages/dashboard_content.php';
                    }
                    ?>
                </div>
            </div>

            <?php include 'includes/footer.php'; ?>
        </div>
    </div>

    <!-- Scripts -->
    <?php include 'includes/scripts.php'; ?>
</body>
</html>