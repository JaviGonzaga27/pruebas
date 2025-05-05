<?php
// sidebar.php - Sidebar optimizado para taller mecánico con diseño KaiAdmin

// Verificar sesión
if (!isset($_SESSION['usuario_rol'])) {
    $usuario_rol = 'guest';
} else {
    $usuario_rol = $_SESSION['usuario_rol'];
}

// Configuración básica
$base_path = $config['base_path'] ?? '/mecanica2/';
$assets_path = $config['assets_path'] ?? '/mecanica2/assets/';

// Función para determinar si un item está activo
function isActive($currentPage, $menuItemPages) {
    return in_array($currentPage, $menuItemPages) ? 'active' : '';
}

// Obtener página actual
$pagina_actual = basename($_SERVER['PHP_SELF']);
?>

<!-- Sidebar -->
<div class="sidebar sidebar-style-2" data-background-color="dark">
    <div class="sidebar-logo">
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
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <!-- 1. DASHBOARD (Para todos los roles) -->
                <li class="nav-item <?= isActive($pagina_actual, ['index.php']) ? 'active' : '' ?>">
                    <a href="<?= $base_path ?>index.php">
                        <i class="fas fa-home"></i>
                        <p>Inicio</p>
                    </a>
                </li>

                <!-- 2. OPERACIONES DEL TALLER (admin, asesor, mecanico) -->
                <?php if (in_array($usuario_rol, ['admin', 'asesor', 'mecanico'])): ?>
                <li class="nav-item <?= isActive($pagina_actual, ['reparaciones.php', 'ordenes.php', 'mis-reparaciones.php', 'servicios.php']) ? 'active' : '' ?>">
                    <a data-bs-toggle="collapse" href="#taller" class="<?= isActive($pagina_actual, ['reparaciones.php', 'ordenes.php', 'mis-reparaciones.php', 'servicios.php']) ? '' : 'collapsed' ?>" aria-expanded="<?= isActive($pagina_actual, ['reparaciones.php', 'ordenes.php', 'mis-reparaciones.php', 'servicios.php']) ? 'true' : 'false' ?>">
                        <i class="fas fa-car"></i>
                        <p>Operaciones</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse <?= isActive($pagina_actual, ['reparaciones.php', 'ordenes.php', 'mis-reparaciones.php', 'servicios.php']) ? 'show' : '' ?>" id="taller">
                        <ul class="nav nav-collapse">
                            <?php if (in_array($usuario_rol, ['admin', 'asesor'])): ?>
                            <li class="<?= isActive($pagina_actual, ['reparaciones.php']) ? 'active' : '' ?>">
                                <a href="<?= $base_path ?>taller/reparaciones.php">
                                    <span class="sub-item">Reparaciones</span>
                                </a>
                            </li>
                            <li class="<?= isActive($pagina_actual, ['ordenes.php']) ? 'active' : '' ?>">
                                <a href="<?= $base_path ?>taller/ordenes.php">
                                    <span class="sub-item">Órdenes de Trabajo</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            
                            <?php if ($usuario_rol === 'mecanico'): ?>
                            <li class="<?= isActive($pagina_actual, ['mis-reparaciones.php']) ? 'active' : '' ?>">
                                <a href="<?= $base_path ?>taller/mis-reparaciones.php">
                                    <span class="sub-item">Mis Reparaciones</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            
                            <li class="<?= isActive($pagina_actual, ['servicios.php']) ? 'active' : '' ?>">
                                <a href="<?= $base_path ?>taller/servicios.php">
                                    <span class="sub-item">Servicios</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <?php endif; ?>

                <!-- 3. CLIENTES Y VEHÍCULOS (admin, asesor) -->
                <?php if (in_array($usuario_rol, ['admin', 'asesor'])): ?>
                <li class="nav-item <?= isActive($pagina_actual, ['clientes.php', 'vehiculos.php', 'historial.php']) ? 'active' : '' ?>">
                    <a data-bs-toggle="collapse" href="#clientes" class="<?= isActive($pagina_actual, ['clientes.php', 'vehiculos.php', 'historial.php']) ? '' : 'collapsed' ?>" aria-expanded="<?= isActive($pagina_actual, ['clientes.php', 'vehiculos.php', 'historial.php']) ? 'true' : 'false' ?>">
                        <i class="fas fa-users"></i>
                        <p>Clientes</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse <?= isActive($pagina_actual, ['clientes.php', 'vehiculos.php', 'historial.php']) ? 'show' : '' ?>" id="clientes">
                        <ul class="nav nav-collapse">
                            <li class="<?= isActive($pagina_actual, ['clientes.php']) ? 'active' : '' ?>">
                                <a href="<?= $base_path ?>clientes/clientes.php">
                                    <span class="sub-item">Gestión de Clientes</span>
                                </a>
                            </li>
                            <li class="<?= isActive($pagina_actual, ['vehiculos.php']) ? 'active' : '' ?>">
                                <a href="<?= $base_path ?>clientes/vehiculos.php">
                                    <span class="sub-item">Vehículos</span>
                                </a>
                            </li>
                            <li class="<?= isActive($pagina_actual, ['historial.php']) ? 'active' : '' ?>">
                                <a href="<?= $base_path ?>clientes/historial.php">
                                    <span class="sub-item">Historial de Servicios</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <?php endif; ?>

                <!-- 4. INVENTARIO (admin, almacen) -->
                <?php if (in_array($usuario_rol, ['admin', 'almacen'])): ?>
                <li class="nav-item <?= isActive($pagina_actual, ['productos.php', 'categorias.php', 'movimientos.php', 'proveedores.php']) ? 'active' : '' ?>">
                    <a data-bs-toggle="collapse" href="#inventario" class="<?= isActive($pagina_actual, ['productos.php', 'categorias.php', 'movimientos.php', 'proveedores.php']) ? '' : 'collapsed' ?>" aria-expanded="<?= isActive($pagina_actual, ['productos.php', 'categorias.php', 'movimientos.php', 'proveedores.php']) ? 'true' : 'false' ?>">
                        <i class="fas fa-boxes"></i>
                        <p>Inventario</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse <?= isActive($pagina_actual, ['productos.php', 'categorias.php', 'movimientos.php', 'proveedores.php']) ? 'show' : '' ?>" id="inventario">
                        <ul class="nav nav-collapse">
                            <li class="<?= isActive($pagina_actual, ['productos.php']) ? 'active' : '' ?>">
                                <a href="<?= $base_path ?>inventario/productos.php">
                                    <span class="sub-item">Productos</span>
                                </a>
                            </li>
                            <li class="<?= isActive($pagina_actual, ['categorias.php']) ? 'active' : '' ?>">
                                <a href="<?= $base_path ?>inventario/categorias.php">
                                    <span class="sub-item">Categorías</span>
                                </a>
                            </li>
                            <li class="<?= isActive($pagina_actual, ['movimientos.php']) ? 'active' : '' ?>">
                                <a href="<?= $base_path ?>inventario/movimientos.php">
                                    <span class="sub-item">Movimientos</span>
                                </a>
                            </li>
                            <?php if ($usuario_rol === 'admin'): ?>
                            <li class="<?= isActive($pagina_actual, ['proveedores.php']) ? 'active' : '' ?>">
                                <a href="<?= $base_path ?>inventario/proveedores.php">
                                    <span class="sub-item">Proveedores</span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </li>
                <?php endif; ?>

                <!-- 5. VENTAS Y COMPRAS (admin, cajero) -->
                <?php if (in_array($usuario_rol, ['admin', 'cajero'])): ?>
                <li class="nav-item <?= isActive($pagina_actual, ['ventas.php', 'compras.php', 'reportes.php']) ? 'active' : '' ?>">
                    <a data-bs-toggle="collapse" href="#finanzas" class="<?= isActive($pagina_actual, ['ventas.php', 'compras.php', 'reportes.php']) ? '' : 'collapsed' ?>" aria-expanded="<?= isActive($pagina_actual, ['ventas.php', 'compras.php', 'reportes.php']) ? 'true' : 'false' ?>">
                        <i class="fas fa-money-check-alt"></i>
                        <p>Finanzas</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse <?= isActive($pagina_actual, ['ventas.php', 'compras.php', 'reportes.php']) ? 'show' : '' ?>" id="finanzas">
                        <ul class="nav nav-collapse">
                            <li class="<?= isActive($pagina_actual, ['ventas.php']) ? 'active' : '' ?>">
                                <a href="<?= $base_path ?>finanzas/ventas.php">
                                    <span class="sub-item">Ventas</span>
                                </a>
                            </li>
                            <?php if ($usuario_rol === 'admin'): ?>
                            <li class="<?= isActive($pagina_actual, ['compras.php']) ? 'active' : '' ?>">
                                <a href="<?= $base_path ?>finanzas/compras.php">
                                    <span class="sub-item">Compras</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <li class="<?= isActive($pagina_actual, ['reportes.php']) ? 'active' : '' ?>">
                                <a href="<?= $base_path ?>finanzas/reportes.php">
                                    <span class="sub-item">Reportes</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <?php endif; ?>

                <!-- 6. ADMINISTRACIÓN (solo admin) -->
                <?php if ($usuario_rol === 'admin'): ?>
                <li class="nav-item <?= isActive($pagina_actual, ['usuarios.php', 'configuracion.php', 'backup.php']) ? 'active' : '' ?>">
                    <a data-bs-toggle="collapse" href="#admin" class="<?= isActive($pagina_actual, ['usuarios.php', 'configuracion.php', 'backup.php']) ? '' : 'collapsed' ?>" aria-expanded="<?= isActive($pagina_actual, ['usuarios.php', 'configuracion.php', 'backup.php']) ? 'true' : 'false' ?>">
                        <i class="fas fa-cog"></i>
                        <p>Administración</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse <?= isActive($pagina_actual, ['usuarios.php', 'configuracion.php', 'backup.php']) ? 'show' : '' ?>" id="admin">
                        <ul class="nav nav-collapse">
                            <li class="<?= isActive($pagina_actual, ['usuarios.php']) ? 'active' : '' ?>">
                                <a href="<?= $base_path ?>admin/usuarios.php">
                                    <span class="sub-item">Usuarios</span>
                                </a>
                            </li>
                            <li class="<?= isActive($pagina_actual, ['configuracion.php']) ? 'active' : '' ?>">
                                <a href="<?= $base_path ?>admin/configuracion.php">
                                    <span class="sub-item">Configuración</span>
                                </a>
                            </li>
                            <li class="<?= isActive($pagina_actual, ['backup.php']) ? 'active' : '' ?>">
                                <a href="<?= $base_path ?>admin/backup.php">
                                    <span class="sub-item">Respaldo</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->

<!-- Script para el comportamiento del sidebar -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Manejar el estado de los menús desplegables
    const navItems = document.querySelectorAll('.nav-item > a[data-bs-toggle="collapse"]');
    navItems.forEach(item => {
        item.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                const bsCollapse = new bootstrap.Collapse(target, {
                    toggle: true
                });
            }
        });
    });
});
</script>