<?php
// includes/scripts.php - Scripts centralizados
$assets_path = $config['assets_path'] ?? '/mecanica2/assets/';
?>

<!-- Core JS Files -->
<script src="<?= $assets_path ?>js/core/jquery-3.7.1.min.js"></script>
<script src="<?= $assets_path ?>js/core/popper.min.js"></script>
<script src="<?= $assets_path ?>js/core/bootstrap.min.js"></script>

<!-- jQuery Scrollbar -->
<script src="<?= $assets_path ?>js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

<!-- Chart JS -->
<script src="<?= $assets_path ?>js/plugin/chart.js/chart.min.js"></script>

<!-- jQuery Sparkline -->
<script src="<?= $assets_path ?>js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

<!-- Chart Circle -->
<script src="<?= $assets_path ?>js/plugin/chart-circle/circles.min.js"></script>

<!-- Datatables -->
<script src="<?= $assets_path ?>js/plugin/datatables/datatables.min.js"></script>

<!-- Bootstrap Notify -->
<script src="<?= $assets_path ?>js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

<!-- jQuery Vector Maps -->
<script src="<?= $assets_path ?>js/plugin/jsvectormap/jsvectormap.min.js"></script>
<script src="<?= $assets_path ?>js/plugin/jsvectormap/world.js"></script>

<!-- Sweet Alert -->
<script src="<?= $assets_path ?>js/plugin/sweetalert/sweetalert.min.js"></script>

<!-- Kaiadmin JS -->
<script src="<?= $assets_path ?>js/kaiadmin.min.js"></script>

<!-- Kaiadmin DEMO methods, don't include it in your project! -->
<script src="<?= $assets_path ?>js/setting-demo.js"></script>
<script src="<?= $assets_path ?>js/demo.js"></script>

<!-- Page-specific scripts -->
<?php if (isset($page_scripts) && is_array($page_scripts)): ?>
    <?php foreach ($page_scripts as $script): ?>
        <script src="<?= $assets_path ?>js/<?= $script ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>

<!-- Session check -->
<script>
    // Verificar sesión cada 5 minutos
    setInterval(function() {
        fetch('check_session.php')
            .then(response => response.json())
            .then(data => {
                if (!data.valid) {
                    window.location.href = 'login.php?session_expired=1';
                }
            })
            .catch(error => {
                console.error('Error checking session:', error);
            });
    }, 300000);
</script>