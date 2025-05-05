<?php
/**
 * includes/template_footer.php - Cierre de la plantilla
 */
?>
            </div> <!-- Fin de .container -->

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4">
                            <nav class="pull-left">
                                <ul class="nav">
                                    <li class="nav-item">
                                        <a class="nav-link" href="https://navetech.com">NAVETECH</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">Ayuda</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">Licencias</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="copyright">
                                &copy; <?= date('Y') ?>, desarrollado <i class="fa fa-heart text-danger"></i> por
                                <a href="https://navetech.com">Ing: Esteban Loachamin</a>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <div>
                                Distribuido Por
                                <a target="_blank" href="https://navetech.com">Nave Tech</a>.
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
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
    
    <!-- Script para inicializar componentes -->
    <script>
        // Inicializar Tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        // Inicializar Popovers
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl)
        });
        
        // Verificar sesión cada 5 minutos
        setInterval(function() {
            fetch('<?= $base_path ?>check_session.php')
                .then(response => response.json())
                .then(data => {
                    if (!data.valid) {
                        window.location.href = '<?= $base_path ?>login.php?session_expired=1';
                    }
                })
                .catch(error => {
                    console.error('Error checking session:', error);
                });
        }, 300000);
    </script>


<!-- Incluye el JavaScript de configuración -->
<script src="<?= $assets_path ?>js/setting-demo.js"></script>

</body>
</html>