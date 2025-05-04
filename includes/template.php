<?php
/**
 * includes/template.php - Plantilla base para todas las páginas
 */

// Validar variables requeridas
if (!isset($title)) {
    $title = 'Sistema de Taller Mecánico';
}

// Establecer configuración por defecto si no existe
if (!isset($config)) {
    $config = [
        'base_path' => '/mecanica2/',
        'assets_path' => '/mecanica2/assets/',
        'site_name' => 'Sistema de Taller Mecánico'
    ];
}

$base_path = $config['base_path'];
$assets_path = $config['assets_path'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= htmlspecialchars($title) ?> | <?= htmlspecialchars($config['site_name'] ?? 'Sistema de Taller Mecánico') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="icon" href="<?= $assets_path ?>img/kaiadmin/favicon.ico" type="image/x-icon">

    <!-- Fonts and icons -->
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

    <!-- CSS Files -->
    <link rel="stylesheet" href="<?= $assets_path ?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= $assets_path ?>css/plugins.min.css">
    <link rel="stylesheet" href="<?= $assets_path ?>css/kaiadmin.min.css">
    <link rel="stylesheet" href="<?= $assets_path ?>css/demo.css">
    
    <?php if (isset($page_css) && is_array($page_css)): ?>
        <?php foreach ($page_css as $css): ?>
            <link rel="stylesheet" href="<?= $assets_path . $css ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    
    <?php if (isset($cdn_css) && is_array($cdn_css)): ?>
        <?php foreach ($cdn_css as $css): ?>
            <link rel="stylesheet" href="<?= $css ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    
    <?php if (isset($inline_css)): ?>
        <style>
            <?= $inline_css ?>
        </style>
    <?php endif; ?>
</head>
<body>
    <div class="wrapper">
        <?php include '../navbar.php'; ?>

        <div class="main-panel">
            <?php include '../includes/header.php'; ?>

            <div class="container">
                <!-- Contenido específico de la página va aquí -->