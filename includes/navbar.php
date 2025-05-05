<?php
// navbar.php - Barra de navegación superior

// Configuración básica
$base_path = $config['base_path'] ?? '/mecanica2/';
$assets_path = $config['assets_path'] ?? '/mecanica2/assets/';
$usuario_nombre = $_SESSION['usuario_nombre'] ?? 'Usuario';
?>

<!-- Navbar Header -->
<nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
    <div class="container-fluid">

        <!-- Menú superior derecho -->
        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
            <!-- Notificaciones -->
            <li class="nav-item topbar-icon dropdown hidden-caret">
                <a class="nav-link dropdown-toggle" href="#" id="notifDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-bell"></i>
                    <span class="notification">4</span>
                </a>
                <ul class="dropdown-menu notif-box animated fadeIn" aria-labelledby="notifDropdown">
                    <li>
                        <div class="dropdown-title">Tienes 4 notificaciones nuevas</div>
                    </li>
                    <li>
                        <div class="notif-scroll scrollbar-outer">
                            <div class="notif-center">
                                <a href="#">
                                    <div class="notif-icon notif-primary">
                                        <i class="fa fa-user-plus"></i>
                                    </div>
                                    <div class="notif-content">
                                        <span class="block">Nuevo cliente registrado</span>
                                        <span class="time">Hace 5 minutos</span>
                                    </div>
                                </a>
                                <a href="#">
                                    <div class="notif-icon notif-success">
                                        <i class="fa fa-car"></i>
                                    </div>
                                    <div class="notif-content">
                                        <span class="block">Vehículo listo para entregar</span>
                                        <span class="time">Hace 12 minutos</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </li>
                    <li>
                        <a class="see-all" href="javascript:void(0);">Ver todas las notificaciones<i class="fa fa-angle-right"></i></a>
                    </li>
                </ul>
            </li>

            <!-- Acciones rápidas -->
            <li class="nav-item topbar-icon dropdown hidden-caret">
                <a class="nav-link" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                    <i class="fas fa-layer-group"></i>
                </a>
                <div class="dropdown-menu quick-actions animated fadeIn">
                    <div class="quick-actions-header">
                        <span class="title mb-1">Acciones Rápidas</span>
                        <span class="subtitle op-7">Atajos</span>
                    </div>
                    <div class="quick-actions-scroll scrollbar-outer">
                        <div class="quick-actions-items">
                            <div class="row m-0">
                                <a class="col-6 col-md-4 p-0" href="<?= $base_path ?>clientes/clientes.php">
                                    <div class="quick-actions-item">
                                        <div class="avatar-item bg-primary rounded-circle">
                                            <i class="fas fa-user-plus"></i>
                                        </div>
                                        <span class="text">Nuevo Cliente</span>
                                    </div>
                                </a>
                                <a class="col-6 col-md-4 p-0" href="<?= $base_path ?>inventario/productos.php">
                                    <div class="quick-actions-item">
                                        <div class="avatar-item bg-success rounded-circle">
                                            <i class="fas fa-box"></i>
                                        </div>
                                        <span class="text">Nuevo Producto</span>
                                    </div>
                                </a>
                                <a class="col-6 col-md-4 p-0" href="<?= $base_path ?>finanzas/ventas.php">
                                    <div class="quick-actions-item">
                                        <div class="avatar-item bg-warning rounded-circle">
                                            <i class="fas fa-shopping-cart"></i>
                                        </div>
                                        <span class="text">Nueva Venta</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </li>

            <!-- Perfil de usuario -->
            <li class="nav-item topbar-user dropdown hidden-caret">
                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                    <div class="avatar-sm">
                        <img src="<?= $assets_path ?>img/profile.jpg" alt="..." class="avatar-img rounded-circle">
                    </div>
                    <span class="profile-username">
                        <span class="op-7">Hola,</span>
                        <span class="op-7"><?= htmlspecialchars($usuario_nombre) ?></span>
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

<script>
// Versión optimizada con detección de errores
document.addEventListener('DOMContentLoaded', function() {
    try {
        // Verificar que Bootstrap esté cargado
        if (typeof bootstrap === 'undefined' || !bootstrap.Dropdown) {
            console.error('Bootstrap Dropdown no está disponible');
            return;
        }

        // Inicializar dropdowns con manejo de errores
        document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
            try {
                new bootstrap.Dropdown(toggle, {
                    popperConfig: {
                        placement: 'bottom-end'
                    }
                });
                
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    bootstrap.Dropdown.getInstance(toggle).toggle();
                });
            } catch (error) {
                console.error('Error inicializando dropdown:', error);
            }
        });

        // Ajustes responsive
        function adjustForScreenSize() {
            const isMobile = window.innerWidth < 992;
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.style.position = isMobile ? 'fixed' : '';
                menu.style.top = isMobile ? '60px' : '';
                menu.style.right = isMobile ? '10px' : '';
                menu.style.left = isMobile ? 'auto' : '';
                menu.style.width = isMobile ? '280px' : '';
                menu.style.maxWidth = isMobile ? '90vw' : '';
            });
        }

        window.addEventListener('resize', adjustForScreenSize);
        adjustForScreenSize();

    } catch (error) {
        console.error('Error en navbar:', error);
    }
});
</script>

<!-- End Navbar -->