<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/mecanica2/auth.php';

// Configuración de rutas
$base_path = '/mecanica2/';
$assets_path = $base_path . 'assets/';

// Obtener información del usuario
$usuario_nombre = $_SESSION['usuario_nombre'] ?? 'Usuario';
$usuario_rol = $_SESSION['usuario_rol'] ?? 'guest';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sistema de Taller Mecánico - Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="icon" href="<?= $assets_path ?>img/kaiadmin/favicon.ico" type="image/x-icon">

    <!-- Fonts and icons -->
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
</head>
<body>
    <div class="wrapper">
        <?php include 'navbar.php'; ?>

        <div class="main-panel">
            <?php include 'includes/header.php'; ?>

            <div class="container">
                <div class="page-inner">
                    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                        <div>
                            <h3 class="fw-bold mb-3">Dashboard</h3>
                            <h6 class="op-7 mb-2">Sistema de Gestión de Taller Mecánico</h6>
                        </div>
                        <div class="ms-md-auto py-2 py-md-0">
                            <a href="#" class="btn btn-label-info btn-round me-2">Gestionar</a>
                            <a href="#" class="btn btn-primary btn-round">Agregar Cliente</a>
                        </div>
                    </div>

                    <!-- Cards de resumen -->
                    <div class="row">
                        <div class="col-sm-6 col-md-3">
                            <div class="card card-stats card-round">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-icon">
                                            <div class="icon-big text-center icon-primary bubble-shadow-small">
                                                <i class="fas fa-users"></i>
                                            </div>
                                        </div>
                                        <div class="col col-stats ms-3 ms-sm-0">
                                            <div class="numbers">
                                                <p class="card-category">Visitantes</p>
                                                <h4 class="card-title">1,294</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Repetir para otras tarjetas de estadísticas -->
                    </div>

                    <!-- Gráficos y tablas -->
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card card-round">
                                <div class="card-header">
                                    <div class="card-head-row">
                                        <div class="card-title">Estadísticas de Usuario</div>
                                        <div class="card-tools">
                                            <a href="#" class="btn btn-label-success btn-round btn-sm me-2">
                                                <span class="btn-label"><i class="fa fa-pencil"></i></span>
                                                Exportar
                                            </a>
                                            <a href="#" class="btn btn-label-info btn-round btn-sm">
                                                <span class="btn-label"><i class="fa fa-print"></i></span>
                                                Imprimir
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container" style="min-height: 375px">
                                        <canvas id="statisticsChart"></canvas>
                                    </div>
                                    <div id="myChartLegend"></div>
                                </div>
                            </div>
                        </div>
                        <!-- Más contenido del dashboard -->
                    </div>
                </div>
            </div>

            <?php include 'includes/footer.php'; ?>
        </div>
    </div>

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

    <script>
        // Sparkline charts
        $("#lineChart").sparkline([102, 109, 120, 99, 110, 105, 115], {
            type: "line",
            height: "70",
            width: "100%",
            lineWidth: "2",
            lineColor: "#177dff",
            fillColor: "rgba(23, 125, 255, 0.14)",
        });

        // Session timeout check
        setInterval(function() {
            fetch('check_session.php')
                .then(response => response.json())
                .then(data => {
                    if (!data.valid) {
                        window.location.href = 'login.php?session_expired=1';
                    }
                });
        }, 300000); // Check every 5 minutes
    </script>
</body>
</html>