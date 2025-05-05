<?php
// includes/header.php - Encabezado optimizado
$base_path = $config['base_path'] ?? '/mecanica2/';
$assets_path = $base_path . 'assets/';
$usuario_nombre = $_SESSION['usuario_nombre'] ?? 'Usuario';
?>

<div class="main-header">
    <!-- Logo y botón de toggle para móviles -->
    <div class="main-header-logo">
        <div class="logo-header" data-background-color="dark">
            <a href="<?= $base_path ?>index.php" class="logo">
                <img src="<?= $assets_path ?>img/kaiadmin/logo_light.svg" alt="navbar brand" class="navbar-brand" height="20">
            </a>
            <!-- En la sección del logo header -->
<div class="nav-toggle">
    <button class="btn btn-toggle toggle-sidebar d-lg-none">
        <i class="gg-menu-right"></i>
    </button>
    <button class="btn btn-toggle sidenav-toggler d-none d-lg-block">
        <i class="gg-menu-left"></i>
    </button>
</div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
    </div>

    <!-- Incluir el navbar -->
    <?php include 'navbar.php'; ?>
</div>

<!-- Script para el comportamiento del navbar -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar dropdowns de Bootstrap
    const dropdowns = document.querySelectorAll('.dropdown-toggle');
    dropdowns.forEach(el => {
        el.addEventListener('click', function(e) {
            e.preventDefault();
            new bootstrap.Dropdown(this).toggle();
        });
    });

    // Ajustes para móviles
    if (window.innerWidth < 992) {
        document.querySelector('.profile-username .fw-bold').textContent = '';
    }
});
</script>