<?php
/**
 * includes/template.php - Plantilla base para todas las páginas
 * 
 * Uso:
 * 1. Definir variables de configuración: $title, $page_css, $page_scripts, $page_modules
 * 2. Incluir este archivo
 * 3. Definir el contenido dentro de <div class="page-inner">
 * 4. Incluir el footer con include 'includes/template_footer.php';
 */

// Validar variables requeridas
if (!isset($title)) {
    $title = 'Sistema de Taller Mecánico';
}

// Establecer configuración por defecto
$config = [
    'base_path' => '/mecanica2/',
    'assets_path' => '/mecanica2/assets/',
    'site_name' => 'Sistema de Taller Mecánico',
    'version' => '1.0'
];

// Iniciar el documento
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= htmlspecialchars($title) ?> | <?= htmlspecialchars($config['site_name']) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="icon" href="<?= $config['assets_path'] ?>img/kaiadmin/favicon.ico" type="image/x-icon">
    
    <?php include 'head_assets.php'; ?>
    
    <!-- CSS específico de la página -->
    <?php if (isset($page_css) && is_array($page_css)): ?>
        <?php foreach ($page_css as $css): ?>
            <link rel="stylesheet" href="<?= $config['assets_path'] . $css ?>">
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
        <?php include $_SERVER['DOCUMENT_ROOT'] . $config['base_path'] . 'navbar.php'; ?>
        
        <div class="main-panel">
            <?php include 'header.php'; ?>
            
            <div class="container">
                <!-- El contenido específico de la página va aquí -->