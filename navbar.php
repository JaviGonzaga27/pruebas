<?php
// sidebar.php - Sidebar optimizado para taller mecánico

// Verificar sesión
if (!isset($_SESSION['usuario_rol'])) {
    $usuario_rol = 'guest';
} else {
    $usuario_rol = $_SESSION['usuario_rol'];
}

// Función para determinar si un item está activo
function isActive($currentPage, $menuItemPages) {
    return in_array($currentPage, $menuItemPages) ? 'active' : '';
}

// Obtener página actual
$pagina_actual = basename($_SERVER['PHP_SELF']);
$base_path = '/mecanica2/';

// Configuración del menú por roles
$menu_items = [];

// 1. DASHBOARD (Para todos los roles)
$menu_items[] = [
    'id' => 'dashboard',
    'icon' => 'fas fa-home',
    'text' => 'Inicio',
    'items' => [
        ['link' => $base_path . 'index.php', 'text' => 'Panel Principal', 'page' => 'index.php']
    ]
];

// 2. OPERACIONES DEL TALLER (admin, asesor, mecanico)
if (in_array($usuario_rol, ['admin', 'asesor', 'mecanico'])) {
    $menu_items[] = [
        'id' => 'taller',
        'icon' => 'fas fa-car',
        'text' => 'Operaciones',
        'items' => array_filter([
            in_array($usuario_rol, ['admin', 'asesor']) ? 
                ['link' => $base_path . 'taller/reparaciones.php', 'text' => 'Reparaciones', 'page' => 'reparaciones.php'] : null,
            in_array($usuario_rol, ['admin', 'asesor']) ? 
                ['link' => $base_path . 'taller/ordenes.php', 'text' => 'Órdenes de Trabajo', 'page' => 'ordenes.php'] : null,
            ($usuario_rol === 'mecanico') ? 
                ['link' => $base_path . 'taller/mis-reparaciones.php', 'text' => 'Mis Reparaciones', 'page' => 'mis-reparaciones.php'] : null,
            ['link' => $base_path . 'taller/servicios.php', 'text' => 'Servicios', 'page' => 'servicios.php']
        ])
    ];
}

// 3. CLIENTES Y VEHÍCULOS (admin, asesor)
if (in_array($usuario_rol, ['admin', 'asesor'])) {
    $menu_items[] = [
        'id' => 'clientes',
        'icon' => 'fas fa-users',
        'text' => 'Clientes',
        'items' => [
            ['link' => $base_path . 'clientes/clientes.php', 'text' => 'Gestión de Clientes', 'page' => 'lista.php'],
            ['link' => $base_path . 'clientes/vehiculos.php', 'text' => 'Vehículos', 'page' => 'vehiculos.php'],
            ['link' => $base_path . 'clientes/historial.php', 'text' => 'Historial de Servicios', 'page' => 'historial.php']
        ]
    ];
}

// 4. INVENTARIO (admin, almacen)
if (in_array($usuario_rol, ['admin', 'almacen'])) {
    $menu_items[] = [
        'id' => 'inventario',
        'icon' => 'fas fa-boxes',
        'text' => 'Inventario',
        'items' => array_filter([
            ['link' => $base_path . 'inventario/productos.php', 'text' => 'Productos', 'page' => 'productos.php'],
            ['link' => $base_path . 'inventario/categorias.php', 'text' => 'Categorías', 'page' => 'categorias.php'],
            ['link' => $base_path . 'inventario/movimientos.php', 'text' => 'Movimientos', 'page' => 'movimientos.php'],
            ($usuario_rol === 'admin') ? 
                ['link' => $base_path . 'inventario/proveedores.php', 'text' => 'Proveedores', 'page' => 'proveedores.php'] : null
        ])
    ];
}

// 5. VENTAS Y COMPRAS (admin, cajero)
if (in_array($usuario_rol, ['admin', 'cajero'])) {
    $menu_items[] = [
        'id' => 'finanzas',
        'icon' => 'fas fa-money-check-alt',
        'text' => 'Finanzas',
        'items' => array_filter([
            ['link' => $base_path . 'finanzas/ventas.php', 'text' => 'Ventas', 'page' => 'ventas.php'],
            ($usuario_rol === 'admin') ? 
                ['link' => $base_path . 'finanzas/compras.php', 'text' => 'Compras', 'page' => 'compras.php'] : null,
            ['link' => $base_path . 'finanzas/reportes.php', 'text' => 'Reportes', 'page' => 'reportes.php']
        ])
    ];
}

// 6. ADMINISTRACIÓN (solo admin)
if ($usuario_rol === 'admin') {
    $menu_items[] = [
        'id' => 'admin',
        'icon' => 'fas fa-cog',
        'text' => 'Administración',
        'items' => [
            ['link' => $base_path . 'admin/usuarios.php', 'text' => 'Usuarios', 'page' => 'usuarios.php'],
            ['link' => $base_path . 'admin/configuracion.php', 'text' => 'Configuración', 'page' => 'configuracion.php'],
            ['link' => $base_path . 'admin/backup.php', 'text' => 'Respaldo', 'page' => 'backup.php']
        ]
    ];
}

// Función para verificar si un grupo está activo
function isGroupActive($items, $currentPage) {
    foreach ($items as $item) {
        if ($item && $item['page'] === $currentPage) {
            return true;
        }
    }
    return false;
}
?>

<!-- Sidebar -->
<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <div class="logo-header" data-background-color="dark">
            <a href="<?= $base_path ?>index.php" class="logo">
                <img src="<?= $base_path ?>assets/img/logo_taller.png" alt="Logo Taller" class="navbar-brand" height="30">
                <span class="logo-text">Mecánica Pro</span>
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </div>
    
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <?php foreach ($menu_items as $menu): ?>
                    <?php if (!empty($menu['items'])): ?>
                        <li class="nav-item <?= isGroupActive($menu['items'], $pagina_actual) ? 'active' : '' ?>">
                            <a data-bs-toggle="collapse" href="#<?= $menu['id'] ?>" 
                               class="<?= isGroupActive($menu['items'], $pagina_actual) ? '' : 'collapsed' ?>" 
                               aria-expanded="<?= isGroupActive($menu['items'], $pagina_actual) ? 'true' : 'false' ?>">
                                <i class="<?= $menu['icon'] ?>"></i>
                                <p><?= $menu['text'] ?></p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse <?= isGroupActive($menu['items'], $pagina_actual) ? 'show' : '' ?>" id="<?= $menu['id'] ?>">
                                <ul class="nav nav-collapse">
                                    <?php foreach ($menu['items'] as $item): ?>
                                        <?php if ($item): ?>
                                            <li class="<?= isActive($pagina_actual, [$item['page']]) ?>">
                                                <a href="<?= $item['link'] ?>">
                                                    <span class="sub-item"><?= $item['text'] ?></span>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->