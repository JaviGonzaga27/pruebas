<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/mecanica2/auth.php';
include '../conexion.php';

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'create_venta':
        crearVenta();
        break;
    case 'get_ventas':
        getVentas();
        break;
    case 'get_detalle':
        getDetalleVenta();
        break;
    case 'get_clientes':
        getClientes();
        break;
    case 'get_productos':
        getProductos();
        break;
    case 'search_clientes':
        searchClientes();
        break;
    case 'search_productos':
        searchProductos();
        break;
        case 'get_metodos_pago':
            getMetodosPago();
            break;
    default:
        echo json_encode(['error' => 'Acción no válida']);
        break;
}

function crearVenta() {
    global $conn;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        try {
            $conn->beginTransaction();

            // 1. Calcular subtotal (suma de productos con descuentos por producto)
            $subtotal = 0;
            foreach ($data['productos'] as $producto) {
                $precioConDescuento = $producto['precio'] * (1 - ($producto['descuentoProducto'] ?? 0)/100);
                $subtotal += $producto['cantidad'] * $precioConDescuento;
            }

            // 2. Calcular IVA si aplica
            $iva = 0;
            $tieneIVA = $data['tieneIVA'] ?? false;
            $porcentajeIVA = $data['porcentajeIVA'] ?? 12.00;
            
            if ($tieneIVA) {
                $iva = $subtotal * ($porcentajeIVA / 100);
            }

            // 3. Calcular total con descuento general (después de IVA)
            $totalConIVA = $subtotal + $iva;
            $descuentoGeneral = $data['descuentoGeneral'] ?? 0;
            $total = $totalConIVA * (1 - $descuentoGeneral/100);

            // 4. Insertar la venta principal
           // Modificar la inserción para incluir método de pago
           $sql = "INSERT INTO ventas (
            ClienteID, FechaVenta, Subtotal, TotalVenta, MetodoPagoID,
            TieneIVA, PorcentajeIVA, DescuentoGeneral, UsuarioID
        ) VALUES (
            :clienteID, :fecha, :subtotal, :total, :metodoPagoID,
            :tieneIVA, :porcentajeIVA, :descuentoGeneral, :usuarioID
        )";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':clienteID' => $data['clienteID'],
            ':fecha' => $data['fecha'],
            ':subtotal' => $subtotal,
            ':total' => $total,
            ':metodoPagoID' => $data['metodoPagoID'],
            ':tieneIVA' => $data['tieneIVA'] ? 1 : 0,
            ':porcentajeIVA' => $data['porcentajeIVA'],
            ':descuentoGeneral' => $data['descuentoGeneral'],
            ':usuarioID' => $data['usuarioID'] // Usar el ID del usuario logueado
        ]);
            
            $ventaID = $conn->lastInsertId();
            
            // 5. Insertar los detalles de la venta
            foreach ($data['productos'] as $producto) {
                $sql = "INSERT INTO detalleventa (
                    VentaID, ProductoID, Cantidad, PrecioUnitario, DescuentoProducto
                ) VALUES (
                    :ventaID, :productoID, :cantidad, :precio, :descuento
                )";
                
                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    ':ventaID' => $ventaID,
                    ':productoID' => $producto['productoID'],
                    ':cantidad' => $producto['cantidad'],
                    ':precio' => $producto['precio'],
                    ':descuento' => $producto['descuentoProducto'] ?? 0
                ]);
                
                // 7. Verificar stock bajo y actualizar estado
                $sql = "SELECT Stock FROM productos WHERE ProductoID = :productoID";
                $stmt = $conn->prepare($sql);
                $stmt->execute([':productoID' => $producto['productoID']]);
                $nuevoStock = $stmt->fetchColumn();
                
                if ($nuevoStock <= 2) {
                    // Actualizar estado a 'Bajo'
                    $sql = "UPDATE productos SET Estado = 'Bajo' WHERE ProductoID = :productoID";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([':productoID' => $producto['productoID']]);
                    
                    // Crear notificación
                    $mensaje = ($nuevoStock == 0) 
                        ? "El producto {$producto['productoID']} ha agotado su stock" 
                        : "El producto {$producto['productoID']} tiene stock bajo ({$nuevoStock} unidades)";
                    
                    // Modificar la inserción de notificaciones para usar el usuario logueado
$sql = "INSERT INTO notificaciones (ProductoID, Mensaje, FechaNotificacion, Estado, UsuarioID) 
VALUES (:productoID, :mensaje, CURDATE(), 'Pendiente', :usuarioID)";
$stmt = $conn->prepare($sql);
$stmt->execute([
':productoID' => $producto['productoID'],
':mensaje' => $mensaje,
':usuarioID' => $_SESSION['usuario_id'] // Usar el ID del usuario logueado
]);
                } else {
                    // Si el stock es mayor a 2, asegurarse que el estado es 'Normal'
                    $sql = "UPDATE productos SET Estado = 'Normal' WHERE ProductoID = :productoID AND Stock > 2";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([':productoID' => $producto['productoID']]);
                }
            }
            
            $conn->commit();
            echo json_encode([
                'success' => 'Venta registrada correctamente',
                'ventaID' => $ventaID,
                'subtotal' => $subtotal,
                'iva' => $iva,
                'total' => $total
            ]);
        } catch (PDOException $e) {
            $conn->rollBack();
            echo json_encode(['error' => 'Error al registrar venta: ' . $e->getMessage()]);
        }
    }
}

function getVentas() {
    global $conn;

    try {
        $sql = "SELECT v.*, c.Nombre as ClienteNombre 
                FROM ventas v
                JOIN clientes c ON v.ClienteID = c.ClienteID
                ORDER BY v.FechaVenta DESC";
        $stmt = $conn->query($sql);
        $ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Formatear los datos para incluir información de IVA y descuentos
        $ventasFormateadas = array_map(function($venta) {
            return [
                'VentaID' => $venta['VentaID'],
                'FechaVenta' => $venta['FechaVenta'],
                'ClienteNombre' => $venta['ClienteNombre'],
                'Subtotal' => $venta['Subtotal'],
                'TotalVenta' => $venta['TotalVenta'],
                'TieneIVA' => (bool)$venta['TieneIVA'],
                'PorcentajeIVA' => $venta['PorcentajeIVA'],
                'DescuentoGeneral' => $venta['DescuentoGeneral'],
                'Estado' => $venta['Estado']
            ];
        }, $ventas);
        
        echo json_encode($ventasFormateadas);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error al obtener ventas: ' . $e->getMessage()]);
    }
}
function getDetalleVenta($ventaID = null) {
    global $conn;

    header('Content-Type: application/json');
    
    try {
        $ventaID = $ventaID ?? $_GET['ventaID'];
        
        // Consulta mejorada para obtener la venta
        $sqlVenta = "SELECT 
                        v.VentaID, 
                        v.FechaVenta, 
                        v.Subtotal, 
                        v.TotalVenta,
                        v.TieneIVA,
                        v.PorcentajeIVA,
                        v.DescuentoGeneral,
                        v.Estado,
                        c.Nombre as ClienteNombre
                    FROM ventas v
                    JOIN clientes c ON v.ClienteID = c.ClienteID
                    WHERE v.VentaID = ?";
        
        $stmtVenta = $conn->prepare($sqlVenta);
        $stmtVenta->execute([$ventaID]);
        $venta = $stmtVenta->fetch(PDO::FETCH_ASSOC);

        if (!$venta) {
            throw new Exception("No se encontró la venta con ID: $ventaID");
        }

        // Consulta mejorada para obtener los detalles
        $sqlDetalles = "SELECT 
                            d.ProductoID,
                            p.Nombre as ProductoNombre,
                            d.Cantidad,
                            d.PrecioUnitario,
                            IFNULL(d.DescuentoProducto, 0) as DescuentoProducto,
                            (d.Cantidad * d.PrecioUnitario * (1 - IFNULL(d.DescuentoProducto, 0)/100)) as SubtotalProducto
                        FROM detalleventa d
                        JOIN productos p ON d.ProductoID = p.ProductoID
                        WHERE d.VentaID = ?";
        
        $stmtDetalles = $conn->prepare($sqlDetalles);
        $stmtDetalles->execute([$ventaID]);
        $detalles = $stmtDetalles->fetchAll(PDO::FETCH_ASSOC);

        // Preparar respuesta
        $response = [
            'success' => true,
            'venta' => $venta,
            'detalles' => $detalles
        ];

        echo json_encode($response, JSON_NUMERIC_CHECK);
        
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Error de base de datos']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
// Las demás funciones permanecen igual
function getClientes() {
    global $conn;

    try {
        $sql = "SELECT ClienteID, Nombre, Cedula FROM clientes ORDER BY Nombre";
        $stmt = $conn->query($sql);
        $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($clientes);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error al obtener clientes: ' . $e->getMessage()]);
    }
}

function getProductos() {
    global $conn;

    try {
        $sql = "SELECT ProductoID, Nombre, PrecioVenta, Stock, Estado FROM productos ORDER BY Nombre";
        $stmt = $conn->query($sql);
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($productos);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error al obtener productos: ' . $e->getMessage()]);
    }
}

function searchClientes() {
    global $conn;
    
    $search = $_GET['search'] ?? '';
    
    try {
        $sql = "SELECT ClienteID, Nombre, Cedula FROM clientes 
                WHERE Nombre LIKE :search OR Cedula LIKE :search
                LIMIT 20";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':search' => "%$search%"]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function searchProductos() {
    global $conn;
    
    $search = $_GET['search'] ?? '';
    
    try {
        $sql = "SELECT ProductoID, Nombre, PrecioVenta, Stock, Estado 
                FROM productos 
                WHERE (Nombre LIKE :search OR ProductoID LIKE :search)
                LIMIT 20";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':search' => "%$search%"]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// Agregar nueva función
function getMetodosPago() {
    global $conn;

    try {
        $sql = "SELECT MetodoPagoID, Nombre FROM metodos_pago WHERE Estado = 1 ORDER BY Nombre";
        $stmt = $conn->query($sql);
        $metodos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($metodos);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error al obtener métodos de pago: ' . $e->getMessage()]);
    }
}


?>

