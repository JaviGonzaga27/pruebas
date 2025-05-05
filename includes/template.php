<?php
/**
 * includes/template.php - Plantilla base para todas las páginas
 * 
 * Uso:
 * 1. Definir variables de configuración:
 *    - $title: Título de la página
 *    - $page_title: Título que aparecerá en el header (opcional, por defecto usa $title)
 *    - $breadcrumbs: Array de migas de pan (opcional)
 *    - $page_css, $page_scripts, $page_plugins, etc.
 * 2. Incluir este archivo
 * 3. Definir el contenido dentro de <div class="page-inner">
 * 4. Incluir el footer con include 'includes/template_footer.php';
 */

// Validar variables requeridas
if (!isset($title)) {
    $title = 'Sistema de Taller Mecánico';
}

// Si no se definió el título de la página, usar el título general
if (!isset($page_title)) {
    $page_title = $title;
}

// Si no se definieron breadcrumbs, crear un array vacío
if (!isset($breadcrumbs) || !is_array($breadcrumbs)) {
    $breadcrumbs = [];
}

// Establecer configuración por defecto si no está definida
if (!isset($config) || !is_array($config)) {
    $config = [
        'base_path' => '/mecanica2/',
        'assets_path' => '/mecanica2/assets/',
        'site_name' => 'Sistema de Taller Mecánico',
        'version' => '1.0'
    ];
}

// Asegurar que tenemos rutas de recursos
$base_path = $config['base_path'] ?? '/mecanica2/';
$assets_path = $config['assets_path'] ?? '/mecanica2/assets/';

// Iniciar el documento
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= htmlspecialchars($title) ?> | <?= htmlspecialchars($config['site_name'] ?? 'Sistema de Taller Mecánico') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="icon" href="<?= $assets_path ?>img/kaiadmin/favicon.ico" type="image/x-icon">
    
    <!-- Webfont Loader -->
    <script src="<?= $assets_path ?>js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: { families: ["Public Sans:300,400,500,600,700"] },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons",
                ],
                urls: ["<?= $assets_path ?>css/fonts.min.css"],
            },
            active: function () {
                sessionStorage.fonts = true;
            },
        });
    </script>

    <!-- CSS Files Core -->
    <link rel="stylesheet" href="<?= $assets_path ?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= $assets_path ?>css/plugins.min.css">
    <link rel="stylesheet" href="<?= $assets_path ?>css/kaiadmin.min.css">
    <link rel="stylesheet" href="<?= $assets_path ?>css/demo.css">
    
    <!-- CSS específico de la página -->
    <?php if (isset($page_css) && is_array($page_css)): ?>
        <?php foreach ($page_css as $css): ?>
            <link rel="stylesheet" href="<?= $assets_path . $css ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- CSS desde CDN -->
    <?php if (isset($cdn_css) && is_array($cdn_css)): ?>
        <?php foreach ($cdn_css as $css): ?>
            <link rel="stylesheet" href="<?= $css ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- CSS inline de la página -->
    <?php if (isset($inline_css)): ?>
        <style>
            <?= $inline_css ?>
        </style>
    <?php endif; ?>
</head>
<body>
    <div class="wrapper">
        <?php include $_SERVER['DOCUMENT_ROOT'] . $base_path . 'sidebar.php'; ?>
        
        <div class="main-panel">
            <?php include 'header.php'; ?>
            
            <div class="container">
                <!-- El contenido específico de la página va aquí -->