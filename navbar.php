<?php
// sidebar.php - Sidebar refactorizado y limpio

// Verificar que la sesión esté iniciada
if (!isset($_SESSION['usuario_rol'])) {
    $usuario_rol = 'guest';
} else {
    $usuario_rol = $_SESSION['usuario_rol'];
}

// Función para determinar si un item del menú está activo
function isActive($currentPage, $menuItemPages) {
    return in_array($currentPage, $menuItemPages) ? 'active' : '';
}

// Obtener la página actual
$pagina_actual = basename($_SERVER['PHP_SELF']);

// Configurar rutas base
$base_path = '/mecanica2/';

// Configurar los items del menú según el rol del usuario
$menu_items = [];

// Dashboard - disponible para todos
$menu_items[] = [
    'id' => 'dashboard',
    'icon' => 'fas fa-home',
    'text' => 'Inicio',
    'items' => [
        ['link' => $base_path . 'index.php', 'text' => 'Gráficas', 'page' => 'index.php']
    ]
];

// Inventario - disponible para admin, almacen
if (in_array($usuario_rol, ['admin', 'almacen'])) {
    $menu_items[] = [
        'id' => 'inventario',
        'icon' => 'fas fa-dolly',
        'text' => 'Inventario',
        'items' => [
            ['link' => $base_path . 'inventario/proveedor.php', 'text' => 'Proveedor', 'page' => 'proveedor.php'],
            ['link' => $base_path . 'inventario/categoria.php', 'text' => 'Categorías', 'page' => 'categoria.php'],
            ['link' => $base_path . 'inventario/productos.php', 'text' => 'Productos', 'page' => 'productos.php']
        ]
    ];
}

// Admin - solo para admin
if ($usuario_rol === 'admin') {
    $menu_items[] = [
        'id' => 'admin',
        'icon' => 'fas fa-dollar-sign',
        'text' => 'Admin',
        'items' => [
            ['link' => $base_path . 'admin/compras.php', 'text' => 'Compras', 'page' => 'compras.php'],
            ['link' => $base_path . 'admin/ventas.php', 'text' => 'Ventas', 'page' => 'ventas.php']
        ]
    ];
}

// Clientes - disponible para admin, asesor
if (in_array($usuario_rol, ['admin', 'asesor'])) {
    $menu_items[] = [
        'id' => 'clientes',
        'icon' => 'fas fa-user-plus',
        'text' => 'Clientes',
        'items' => [
            ['link' => $base_path . 'users/clientes.php', 'text' => 'Clientes', 'page' => 'clientes.php'],
            ['link' => $base_path . 'users/vehiculo.php', 'text' => 'Vehículos', 'page' => 'vehiculo.php']
        ]
    ];
}

// Función para determinar si un grupo está activo
function isGroupActive($items, $currentPage) {
    foreach ($items as $item) {
        if ($item['page'] === $currentPage) {
            return true;
        }
    }
    return false;
}
?>

<!-- Sidebar -->
<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
            <a href="<?= $base_path ?>index.php" class="logo">
                <img src="<?= $base_path ?>assets/img/kaiadmin/logo_light.svg" alt="navbar brand" class="navbar-brand" height="20">
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
                <?php foreach ($menu_items as $menu): ?>
                    <li class="nav-item <?= isGroupActive($menu['items'], $pagina_actual) ? 'active' : '' ?>">
                        <a data-bs-toggle="collapse" href="#<?= $menu['id'] ?>" class="<?= isGroupActive($menu['items'], $pagina_actual) ? '' : 'collapsed' ?>" aria-expanded="<?= isGroupActive($menu['items'], $pagina_actual) ? 'true' : 'false' ?>">
                            <i class="<?= $menu['icon'] ?>"></i>
                            <p><?= $menu['text'] ?></p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse <?= isGroupActive($menu['items'], $pagina_actual) ? 'show' : '' ?>" id="<?= $menu['id'] ?>">
                            <ul class="nav nav-collapse">
                                <?php foreach ($menu['items'] as $item): ?>
                                    <li class="<?= isActive($pagina_actual, [$item['page']]) ?>">
                                        <a href="<?= $item['link'] ?>">
                                            <span class="sub-item"><?= $item['text'] ?></span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->