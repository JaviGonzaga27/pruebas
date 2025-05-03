<?php
// includes/head_assets.php - Assets del head centralizados
$assets_path = $config['assets_path'] ?? '/mecanica2/assets/';
?>

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

<!-- CSS Files -->
<link rel="stylesheet" href="<?= $assets_path ?>css/bootstrap.min.css">
<link rel="stylesheet" href="<?= $assets_path ?>css/plugins.min.css">
<link rel="stylesheet" href="<?= $assets_path ?>css/kaiadmin.min.css">
<link rel="stylesheet" href="<?= $assets_path ?>css/demo.css">

<!-- Page-specific CSS -->
<?php if (isset($page_css) && is_array($page_css)): ?>
    <?php foreach ($page_css as $css): ?>
        <link rel="stylesheet" href="<?= $assets_path ?>css/<?= $css ?>">
    <?php endforeach; ?>
<?php endif; ?>