<?php
/**
 * includes/template_footer.php - Cierre de la plantilla
 */
?>
            </div> <!-- Fin de .container -->

            <?php include '../includes/footer.php'; ?>
        </div> <!-- Fin de .main-panel -->
    </div> <!-- Fin de .wrapper -->

    <!-- Scripts Core -->
    <script src="<?= $assets_path ?>js/core/jquery-3.7.1.min.js"></script>
    <script src="<?= $assets_path ?>js/core/popper.min.js"></script>
    <script src="<?= $assets_path ?>js/core/bootstrap.min.js"></script>
    
    <!-- Plugins -->
    <script src="<?= $assets_path ?>js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    
    <?php if (isset($page_plugins) && is_array($page_plugins)): ?>
        <?php foreach ($page_plugins as $plugin): ?>
            <script src="<?= $assets_path ?>js/plugin/<?= $plugin ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <script src="<?= $assets_path ?>js/kaiadmin.min.js"></script>
    <script src="<?= $assets_path ?>js/setting-demo2.js"></script>
    
    <?php if (isset($cdn_scripts) && is_array($cdn_scripts)): ?>
        <?php foreach ($cdn_scripts as $script): ?>
            <script src="<?= $script ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <?php if (isset($page_scripts) && is_array($page_scripts)): ?>
        <?php foreach ($page_scripts as $script): ?>
            <script src="<?= $assets_path . $script ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <?php if (isset($inline_script)): ?>
        <script>
            <?= $inline_script ?>
        </script>
    <?php endif; ?>
</body>
</html>