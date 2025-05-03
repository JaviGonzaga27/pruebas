<?php
include '../conexion.php';

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'generar_cierre':
        generarCierreMensual();
        break;
    case 'get_cierres':
        getCierresMensuales();
        break;
    case 'get_cierre_detalle':
        getDetalleCierre();
        break;
    default:
        echo json_encode(['error' => 'Acción no válida']);
        break;
}

function generarCierreMensual() {
    global $conn;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        $mes = $data['mes']; // Formato YYYYMM (ej. 202306)

        try {
            // Verificar si ya existe un cierre para este mes
            $sql = "SELECT COUNT(*) FROM CierresMensuales WHERE Mes = :mes";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':mes' => $mes]);
            
            if ($stmt->fetchColumn() > 0) {
                echo json_encode(['error' => 'Ya existe un cierre para este mes']);
                return;
            }

            // Ejecutar el procedimiento almacenado
            $sql = "CALL GenerarCierreMensual(:mes)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':mes' => $mes]);

            // Obtener los datos del cierre generado
            $sql = "SELECT * FROM CierresMensuales WHERE Mes = :mes";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':mes' => $mes]);
            $cierre = $stmt->fetch(PDO::FETCH_ASSOC);

            echo json_encode(['success' => 'Cierre mensual generado correctamente', 'cierre' => $cierre]);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Error al generar cierre mensual: ' . $e->getMessage()]);
        }
    }
}

function getCierresMensuales() {
    global $conn;

    try {
        $sql = "SELECT * FROM CierresMensuales ORDER BY Mes DESC";
        $stmt = $conn->query($sql);
        $cierres = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Formatear el mes para mostrarlo como YYYY-MM
        $cierres = array_map(function($cierre) {
            $cierre['MesFormateado'] = substr($cierre['Mes'], 0, 4) . '-' . substr($cierre['Mes'], 4, 2);
            return $cierre;
        }, $cierres);
        
        echo json_encode($cierres);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error al obtener cierres mensuales: ' . $e->getMessage()]);
    }
}

function getDetalleCierre() {
    global $conn;
    $mes = $_GET['mes'] ?? '';

    try {
        $sql = "SELECT 
                    p.ProductoID, 
                    p.Nombre, 
                    SUM(dv.Cantidad) as CantidadVendida,
                    SUM(dv.Cantidad * p.PrecioVenta) as TotalVentas,
                    SUM(dv.Cantidad * p.PrecioCompra) as TotalCostos,
                    SUM(dv.Cantidad * (p.PrecioVenta - p.PrecioCompra)) as Ganancia
                FROM DetalleVenta dv
                JOIN Productos p ON dv.ProductoID = p.ProductoID
                JOIN Ventas v ON dv.VentaID = v.VentaID
                WHERE CONCAT(YEAR(v.FechaVenta), LPAD(MONTH(v.FechaVenta), 2, '0')) = :mes
                GROUP BY p.ProductoID, p.Nombre
                ORDER BY Ganancia DESC";
                
        $stmt = $conn->prepare($sql);
        $stmt->execute([':mes' => $mes]);
        $detalles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($detalles);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error al obtener detalle del cierre: ' . $e->getMessage()]);
    }
}
?>