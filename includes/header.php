<?php
// includes/header.php - Header refactorizado
$base_path = '/mecanica2/';
$assets_path = $base_path . 'assets/';
$usuario_nombre = $_SESSION['usuario_nombre'] ?? 'Usuario';
?>

<div class="main-header">
    <div class="main-header-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
            <a href="<?= $base_path ?>index.php" class="logo">
                <img src="<?= $assets_path ?>img/kaiadmin/logo_light.svg" alt="navbar brand" class="navbar-brand" height="20">
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>
    
    <!-- Navbar Header -->
    <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
        <div class="container-fluid">
            <nav class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <button type="submit" class="btn btn-search pe-1">
                            <i class="fa fa-search search-icon"></i>
                        </button>
                    </div>
                    <input type="text" placeholder="Buscar ..." class="form-control">
                </div>
            </nav>

            <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                <li class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false" aria-haspopup="true">
                        <i class="fa fa-search"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-search animated fadeIn">
                        <form class="navbar-left navbar-form nav-search">
                            <div class="input-group">
                                <input type="text" placeholder="Buscar ..." class="form-control">
                            </div>
                        </form>
                    </ul>
                </li>
                
                <li class="nav-item topbar-icon dropdown hidden-caret">
                    <a class="nav-link dropdown-toggle" href="#" id="notifDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-bell"></i>
                        <span class="notification">4</span>
                    </a>
                    <ul class="dropdown-menu notif-box animated fadeIn" aria-labelledby="notifDropdown">
                        <li>
                            <div class="dropdown-title">Tienes 4 notificaciones nuevas</div>
                        </li>
                        <!-- Notificaciones aquí -->
                    </ul>
                </li>
                
                <li class="nav-item topbar-icon dropdown hidden-caret">
                    <a class="nav-link" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                        <i class="fas fa-layer-group"></i>
                    </a>
                    <div class="dropdown-menu quick-actions animated fadeIn">
                        <div class="quick-actions-header">
                            <span class="title mb-1">Acciones Rápidas</span>
                            <span class="subtitle op-7">Atajos</span>
                        </div>
                        <!-- Quick actions aquí -->
                    </div>
                </li>

                <li class="nav-item topbar-user dropdown hidden-caret">
                    <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                        <div class="avatar-sm">
                            <img src="<?= $assets_path ?>img/profile.jpg" alt="..." class="avatar-img rounded-circle">
                        </div>
                        <span class="profile-username">
                            <span class="op-7">Hola,</span>
                            <span class="fw-bold"><?= htmlspecialchars($usuario_nombre) ?></span>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-user animated fadeIn">
                        <div class="dropdown-user-scroll scrollbar-outer">
                            <li>
                                <div class="user-box">
                                    <div class="avatar-lg">
                                        <img src="<?= $assets_path ?>img/profile.jpg" alt="image profile" class="avatar-img rounded">
                                    </div>
                                    <div class="u-text">
                                        <h4><?= htmlspecialchars($usuario_nombre) ?></h4>
                                        <p class="text-muted"><?= htmlspecialchars($_SESSION['usuario_email'] ?? '') ?></p>
                                        <a href="profile.php" class="btn btn-xs btn-secondary btn-sm">Ver Perfil</a>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">Mi Perfil</a>
                                <a class="dropdown-item" href="#">Mi Saldo</a>
                                <a class="dropdown-item" href="#">Bandeja de entrada</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">Configuración de Cuenta</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?= $base_path ?>logout.php">Cerrar Sesión</a>
                            </li>
                        </div>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    <!-- End Navbar -->
</div>