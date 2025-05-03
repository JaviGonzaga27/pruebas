<?php
// includes/footer.php - Footer refactorizado
$current_year = date('Y');
?>

<footer class="footer">
    <div class="container-fluid d-flex justify-content-between">
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
        <div class="copyright">
            <?= $current_year ?>, desarrollado <i class="fa text-danger"></i> por
            <a href="https://navetech.com">Ing: Esteban Loachamin</a>
        </div>
        <div>
            Distribuido Por
            <a target="_blank" href="https://navetech.com">Nave Tech</a>.
        </div>
    </div>
</footer>