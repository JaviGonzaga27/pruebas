<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/mecanica2/auth.php';
include '../conexion.php';

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'create_compra':
        crearCompra();
        break;
    case 'get_compras':
        getCompras();
        break;
    case 'get_detalle':
        getDetalleCompra();
        break;
    case 'get_proveedores':
        getProveedores();
        break;
    case 'get_productos':
        getProductos();
        break;
    default:
        echo json_encode(['error' => 'Acción no válida']);
        break;
}

function crearCompra() {
    global $conn;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        try {
            $conn->beginTransaction();

            // Validar datos básicos
            if (empty($data['ruc'])) {
                throw new Exception("Debe seleccionar un proveedor");
            }
            
            if (empty($data['fecha'])) {
                throw new Exception("La fecha de compra es requerida");
            }
            
            if (empty($data['productos'])) {
                throw new Exception("Debe agregar al menos un producto");
            }

            // Insertar la compra
            $sql = "INSERT INTO compras (RUC, NumeroFactura, FechaCompra, TotalCompra, UsuarioID) 
                    VALUES (:ruc, :numFactura, :fecha, :total, :usuarioID)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':ruc' => $data['ruc'],
                ':numFactura' => $data['numFactura'] ?? null,
                ':fecha' => $data['fecha'],
                ':total' => $data['total'],
                ':usuarioID' => $data['usuarioID']
            ]);
            
            $compraID = $conn->lastInsertId();
            
            // Insertar detalles de compra
            foreach ($data['productos'] as $producto) {
                $sql = "INSERT INTO detallecompra (CompraID, ProductoID, Cantidad, PrecioUnitario) 
                        VALUES (:compraID, :productoID, :cantidad, :precio)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    ':compraID' => $compraID,
                    ':productoID' => $producto['productoID'],
                    ':cantidad' => $producto['cantidad'],
                    ':precio' => $producto['precio']
                ]);
                
                // Actualizar precio de compra del producto si es diferente
                $sql = "UPDATE productos SET PrecioCompra = :precio WHERE ProductoID = :productoID";
                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    ':precio' => $producto['precio'],
                    ':productoID' => $producto['productoID']
                ]);
            }
            
            $conn->commit();
            echo json_encode([
                'success' => true,
                'message' => 'Compra registrada correctamente',
                'compraID' => $compraID
            ]);
        } catch (Exception $e) {
            $conn->rollBack();
            echo json_encode([
                'error' => true,
                'message' => 'Error al registrar compra: ' . $e->getMessage()
            ]);
        }
    }
}
function getCompras() {
    global $conn;

    try {
        $sql = "SELECT c.CompraID, c.NumeroFactura, c.FechaCompra, c.TotalCompra,
                       p.Nombre as ProveedorNombre, p.RUC,
                       u.Nombre as UsuarioNombre
                FROM compras c
                JOIN proveedores p ON c.RUC = p.RUC
                JOIN usuarios u ON c.UsuarioID = u.UsuarioID
                ORDER BY c.FechaCompra DESC";
        $stmt = $conn->query($sql);
        $compras = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($compras);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error al obtener compras: ' . $e->getMessage()]);
    }
}

function getDetalleCompra() {
    global $conn;
    $compraID = $_GET['compraID'] ?? null;

    try {
        // Obtener información básica de la compra
        $sql = "SELECT c.*, p.Nombre as ProveedorNombre, u.Nombre as UsuarioNombre
                FROM compras c
                JOIN proveedores p ON c.RUC = p.RUC
                JOIN usuarios u ON c.UsuarioID = u.UsuarioID
                WHERE c.CompraID = :compraID";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':compraID' => $compraID]);
        $compra = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$compra) {
            throw new Exception("Compra no encontrada");
        }
        
        // Obtener detalles de los productos
        $sql = "SELECT d.*, p.Nombre as ProductoNombre, p.CodigoBarras, p.marca
                FROM detallecompra d
                JOIN productos p ON d.ProductoID = p.ProductoID
                WHERE d.CompraID = :compraID";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':compraID' => $compraID]);
        $detalles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true,
            'compra' => $compra,
            'detalles' => $detalles
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'error' => true,
            'message' => 'Error al obtener detalle: ' . $e->getMessage()
        ]);
    }
}

function getProveedores() {
    global $conn;

    try {
        $sql = "SELECT RUC, Nombre, Telefono, Email FROM proveedores ORDER BY Nombre";
        $stmt = $conn->query($sql);
        $proveedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($proveedores);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error al obtener proveedores: ' . $e->getMessage()]);
    }
}

function getProductos() {
    global $conn;

    try {
        $sql = "SELECT p.ProductoID, p.Nombre, p.PrecioCompra, p.PrecioVenta, p.Stock, p.StockMinimo, 
                       p.marca, p.tipo_aceite, p.es_liquido, p.UbicacionAlmacen, p.capacidad_envase,
                       c.Nombre as Categoria
                FROM productos p
                LEFT JOIN categorias c ON p.CategoriaID = c.CategoriaID
                ORDER BY p.Nombre";
        $stmt = $conn->query($sql);
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($productos);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error al obtener productos: ' . $e->getMessage()]);
    }
}

function obtenerUsuarioID() {
    return $_SESSION['usuario_id'] ?? 1;
}