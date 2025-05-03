<?php
include '../conexion.php';

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'create_orden':
        crearOrden();
        break;
    case 'get_ordenes':
        getOrdenes();
        break;
    case 'get_detalle':
        getDetalleOrden();
        break;
    case 'get_clientes':
        getClientes();
        break;
    case 'get_vehiculos':
        getVehiculosCliente();
        break;
    case 'get_servicios':
        getServicios();
        break;
    case 'get_productos':
        getProductos();
        break;
    case 'search_clientes':
        searchClientes();
        break;
    case 'search_servicios':
        searchServicios();
        break;
    case 'search_productos':
        searchProductos();
        break;
    case 'update_estado':
        actualizarEstado();
        break;
    case 'delete_orden':
        eliminarOrden();
        break;
    default:
        echo json_encode(['error' => 'Acción no válida']);
        break;
}

function crearOrden() {
    global $conn;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        try {
            // Iniciar transacción
            $conn->beginTransaction();

            // Insertar la orden principal
            $sql = "INSERT INTO ordenestrabajo (Cedula, VehiculoID, FechaOrden, Estado, Total) 
                    VALUES (:cedula, :vehiculoID, :fecha, 'Pendiente', :total)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':cedula' => $data['cedula'],
                ':vehiculoID' => $data['vehiculoID'],
                ':fecha' => $data['fecha'],
                ':total' => $data['total']
            ]);
            
            // Obtener el ID de la orden recién insertada
            $ordenID = $conn->lastInsertId();
            
            // Insertar los detalles de la orden (servicios)
            foreach ($data['servicios'] as $servicio) {
                $sql = "INSERT INTO detalleorden (OrdenID, ServicioID, Cantidad, PrecioUnitario) 
                        VALUES (:ordenID, :servicioID, 1, :precio)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    ':ordenID' => $ordenID,
                    ':servicioID' => $servicio['servicioID'],
                    ':precio' => $servicio['precio']
                ]);
            }
            
            // Insertar los detalles de la orden (productos)
            foreach ($data['productos'] as $producto) {
                $sql = "INSERT INTO detalleorden (OrdenID, ProductoID, Cantidad, PrecioUnitario) 
                        VALUES (:ordenID, :productoID, :cantidad, :precio)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    ':ordenID' => $ordenID,
                    ':productoID' => $producto['productoID'],
                    ':cantidad' => $producto['cantidad'],
                    ':precio' => $producto['precio']
                ]);
                
                // Actualizar el stock del producto
                $sql = "UPDATE productos SET Stock = Stock - :cantidad WHERE ProductoID = :productoID";
                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    ':cantidad' => $producto['cantidad'],
                    ':productoID' => $producto['productoID']
                ]);
            }
            
            // Confirmar transacción
            $conn->commit();
            
            echo json_encode(['success' => 'Orden creada correctamente', 'ordenID' => $ordenID]);
        } catch (PDOException $e) {
            // Revertir transacción en caso de error
            $conn->rollBack();
            echo json_encode(['error' => 'Error al crear orden: ' . $e->getMessage()]);
        }
    }
}

function getOrdenes() {
    global $conn;

    try {
        $sql = "SELECT o.*, c.Nombre as ClienteNombre, v.Marca, v.Modelo, v.Placa 
                FROM ordenestrabajo o
                JOIN clientes c ON o.Cedula = c.Cedula
                JOIN vehiculos v ON o.VehiculoID = v.VehiculoID
                ORDER BY o.FechaOrden DESC";
        $stmt = $conn->query($sql);
        $ordenes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($ordenes);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error al obtener órdenes: ' . $e->getMessage()]);
    }
}
function getDetalleOrden() {
    global $conn;
    $ordenID = $_GET['ordenID'] ?? '';

    try {
        // Obtener servicios de la orden
        $sql = "SELECT d.*, s.Nombre as ServicioNombre 
                FROM detalleorden d
                LEFT JOIN servicios s ON d.ServicioID = s.ServicioID
                WHERE d.OrdenID = :ordenID AND d.ServicioID IS NOT NULL";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':ordenID' => $ordenID]);
        $servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Obtener productos de la orden
        $sql = "SELECT d.*, p.Nombre as ProductoNombre 
                FROM detalleorden d
                LEFT JOIN productos p ON d.ProductoID = p.ProductoID
                WHERE d.OrdenID = :ordenID AND d.ProductoID IS NOT NULL";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':ordenID' => $ordenID]);
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Convertir valores numéricos a float
        foreach ($servicios as &$servicio) {
            $servicio['PrecioUnitario'] = (float)$servicio['PrecioUnitario'];
        }
        
        foreach ($productos as &$producto) {
            $producto['PrecioUnitario'] = (float)$producto['PrecioUnitario'];
            $producto['Cantidad'] = (int)$producto['Cantidad'];
        }
        
        echo json_encode(['servicios' => $servicios, 'productos' => $productos]);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error al obtener detalle: ' . $e->getMessage()]);
    }
}

function getClientes() {
    global $conn;

    try {
        $sql = "SELECT Cedula, Nombre FROM clientes ORDER BY Nombre";
        $stmt = $conn->query($sql);
        $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($clientes);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error al obtener clientes: ' . $e->getMessage()]);
    }
}

function getVehiculosCliente() {
    global $conn;
    $cedula = $_GET['cedula'] ?? '';

    try {
        $sql = "SELECT VehiculoID, CONCAT(Marca, ' ', Modelo, ' (', Placa, ')') as InfoVehiculo 
                FROM vehiculos 
                WHERE Cedula = :cedula";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':cedula' => $cedula]);
        $vehiculos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($vehiculos);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error al obtener vehículos: ' . $e->getMessage()]);
    }
}

function getServicios() {
    global $conn;

    try {
        $sql = "SELECT ServicioID, Nombre, CAST(Precio AS DECIMAL(10,2)) as Precio FROM servicios ORDER BY Nombre";
        $stmt = $conn->query($sql);
        $servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Convertir explícitamente a float
        foreach ($servicios as &$servicio) {
            $servicio['Precio'] = (float)$servicio['Precio'];
        }
        
        echo json_encode($servicios);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error al obtener servicios: ' . $e->getMessage()]);
    }
}


function getProductos() {
    global $conn;

    try {
        $sql = "SELECT ProductoID, Nombre, CAST(PrecioVenta AS DECIMAL(10,2)) as Precio, Stock 
                FROM productos 
                WHERE Stock > 0 
                ORDER BY Nombre";
        $stmt = $conn->query($sql);
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Convertir explícitamente a float
        foreach ($productos as &$producto) {
            $producto['Precio'] = (float)$producto['Precio'];
        }
        
        echo json_encode($productos);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error al obtener productos: ' . $e->getMessage()]);
    }
}

function searchClientes() {
    global $conn;
    $search = $_GET['search'] ?? '';
    
    try {
        $sql = "SELECT Cedula, Nombre FROM clientes 
                WHERE Nombre LIKE :search OR Cedula LIKE :search
                LIMIT 20";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':search' => "%$search%"]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function searchServicios() {
    global $conn;
    $search = $_GET['search'] ?? '';
    
    try {
        $sql = "SELECT ServicioID, Nombre, Precio 
                FROM servicios 
                WHERE Nombre LIKE :search
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
        $sql = "SELECT ProductoID, Nombre, PrecioVenta as Precio, Stock 
                FROM productos 
                WHERE (Nombre LIKE :search OR ProductoID LIKE :search) 
                AND Stock > 0
                LIMIT 20";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':search' => "%$search%"]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function actualizarEstado() {
    global $conn;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        try {
            $sql = "UPDATE ordenestrabajo SET Estado = :estado WHERE OrdenID = :ordenID";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':estado' => $data['estado'],
                ':ordenID' => $data['ordenID']
            ]);
            
            echo json_encode(['success' => 'Estado actualizado correctamente']);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Error al actualizar estado: ' . $e->getMessage()]);
        }
    }
}

function eliminarOrden() {
    global $conn;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        try {
            // Iniciar transacción
            $conn->beginTransaction();

            // Primero obtener los productos para devolver el stock
            $sql = "SELECT ProductoID, Cantidad FROM detalleorden 
                    WHERE OrdenID = :ordenID AND ProductoID IS NOT NULL";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':ordenID' => $data['ordenID']]);
            $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Devolver el stock
            foreach ($productos as $producto) {
                $sql = "UPDATE productos SET Stock = Stock + :cantidad WHERE ProductoID = :productoID";
                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    ':cantidad' => $producto['Cantidad'],
                    ':productoID' => $producto['ProductoID']
                ]);
            }
            
            // Eliminar los detalles de la orden
            $sql = "DELETE FROM detalleorden WHERE OrdenID = :ordenID";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':ordenID' => $data['ordenID']]);
            
            // Eliminar la orden principal
            $sql = "DELETE FROM ordenestrabajo WHERE OrdenID = :ordenID";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':ordenID' => $data['ordenID']]);
            
            // Confirmar transacción
            $conn->commit();
            
            echo json_encode(['success' => 'Orden eliminada correctamente']);
        } catch (PDOException $e) {
            // Revertir transacción en caso de error
            $conn->rollBack();
            echo json_encode(['error' => 'Error al eliminar orden: ' . $e->getMessage()]);
        }
    }
}
?>