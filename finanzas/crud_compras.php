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
    case 'get_compra_plazos':
        getCompraPlazos();
        break;
    case 'registrar_abono':
        registrarAbonoCompra();
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

            // Insertar la compra
            $sql = "INSERT INTO compras (RUC, NumeroFactura, FechaCompra, TotalCompra, UsuarioID, es_plazo, estado_pago, saldo_pendiente) 
                    VALUES (:ruc, :numFactura, :fecha, :total, :usuarioID, :es_plazo, :estado_pago, :saldo_pendiente)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':ruc' => $data['ruc'],
                ':numFactura' => $data['numFactura'] ?? null,
                ':fecha' => $data['fecha'],
                ':total' => $data['total'],
                ':usuarioID' => $data['usuarioID'],
                ':es_plazo' => $data['es_plazo'] ? 1 : 0,
                ':estado_pago' => $data['estado_pago'] ?? 'pendiente',
                ':saldo_pendiente' => $data['saldo_pendiente'] ?? $data['total']
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
            }
            
            // Manejar plazos si es compra a plazos
            if ($data['es_plazo'] && isset($data['plazos'])) {
                $saldoPendiente = $data['total'];
                
                // Registrar abono inicial si existe
                if (isset($data['plazos']['abonoInicial']) && $data['plazos']['abonoInicial'] !== null) {
                    $abono = $data['plazos']['abonoInicial'];
                    
                    // Validar que el abono no sea mayor al total
                    if ($abono['monto'] >= $data['total']) {
                        throw new Exception("El abono inicial no puede ser mayor o igual al total de la compra");
                    }
                    
                    // Registrar abono
                    $sql = "INSERT INTO abonos_compra (compra_id, fecha_abono, monto, observaciones) 
                            VALUES (:compra_id, :fecha_abono, :monto, 'Abono inicial')";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([
                        ':compra_id' => $compraID,
                        ':fecha_abono' => $abono['fecha'],
                        ':monto' => $abono['monto']
                    ]);
                    
                    // Actualizar saldo pendiente
                    $saldoPendiente -= $abono['monto'];
                    $estadoPago = $saldoPendiente > 0 ? 'parcial' : 'completo';
                    
                    $sql = "UPDATE compras 
                            SET saldo_pendiente = :saldo_pendiente, 
                                estado_pago = :estado_pago 
                            WHERE CompraID = :compra_id";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([
                        ':saldo_pendiente' => $saldoPendiente,
                        ':estado_pago' => $estadoPago,
                        ':compra_id' => $compraID
                    ]);
                }
                
                // Registrar plazos
                $plazos = [$data['plazos']['primerPago']];
                if (!empty($data['plazos']['plazosAdicionales'])) {
                    $plazos = array_merge($plazos, $data['plazos']['plazosAdicionales']);
                }
                
                foreach ($plazos as $plazo) {
                    $sql = "INSERT INTO plazos_compra (compra_id, fecha_vencimiento, monto_esperado) 
                            VALUES (:compra_id, :fecha_vencimiento, :monto_esperado)";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([
                        ':compra_id' => $compraID,
                        ':fecha_vencimiento' => $plazo['fecha'],
                        ':monto_esperado' => $plazo['monto']
                    ]);
                    
                    // Crear notificación para el pago (1 día antes)
                    crearNotificacionPago($compraID, $plazo['fecha']);
                }
            }
            
            $conn->commit();
            echo json_encode([
                'success' => true,
                'message' => 'Compra registrada correctamente',
                'compraID' => $compraID,
                'saldo_pendiente' => $saldoPendiente ?? $data['total']
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

function registrarAbonoCompra() {
    global $conn;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        try {
            $conn->beginTransaction();
            
            // 1. Registrar el abono
            $sql = "INSERT INTO abonos_compra (compra_id, fecha_abono, monto, observaciones) 
                    VALUES (:compra_id, :fecha_abono, :monto, :observaciones)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':compra_id' => $data['compra_id'],
                ':fecha_abono' => $data['fecha_abono'],
                ':monto' => $data['monto'],
                ':observaciones' => $data['observaciones'] ?? null
            ]);
            
            // 2. Actualizar saldo pendiente en la compra
            $sql = "UPDATE compras 
                    SET saldo_pendiente = saldo_pendiente - :monto 
                    WHERE CompraID = :compra_id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':monto' => $data['monto'],
                ':compra_id' => $data['compra_id']
            ]);
            
            // 3. Si se especificó un plazo, marcarlo como pagado o parcial
            if (isset($data['plazo_id'])) {
                $sql = "SELECT monto_esperado FROM plazos_compra WHERE plazo_id = :plazo_id";
                $stmt = $conn->prepare($sql);
                $stmt->execute([':plazo_id' => $data['plazo_id']]);
                $montoEsperado = $stmt->fetchColumn();
                
                // Calcular si el pago cubre completamente el plazo
                $sql = "SELECT SUM(monto) FROM abonos_compra 
                        WHERE compra_id = :compra_id 
                        AND observaciones LIKE CONCAT('Pago plazo ', :plazo_id)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    ':compra_id' => $data['compra_id'],
                    ':plazo_id' => $data['plazo_id']
                ]);
                $totalPagado = $stmt->fetchColumn();
                
                $estadoPlazo = ($totalPagado >= $montoEsperado) ? 'pagado' : 'parcial';
                
                $sql = "UPDATE plazos_compra 
                        SET estado = :estado 
                        WHERE plazo_id = :plazo_id";
                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    ':estado' => $estadoPlazo,
                    ':plazo_id' => $data['plazo_id']
                ]);
            }
            
            // 4. Verificar si la compra está completamente pagada
            $sql = "SELECT saldo_pendiente FROM compras WHERE CompraID = :compra_id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':compra_id' => $data['compra_id']]);
            $saldoPendiente = $stmt->fetchColumn();
            
            if ($saldoPendiente <= 0) {
                $sql = "UPDATE compras SET estado_pago = 'completo' WHERE CompraID = :compra_id";
                $stmt = $conn->prepare($sql);
                $stmt->execute([':compra_id' => $data['compra_id']]);
            } elseif ($saldoPendiente < $data['total']) {
                $sql = "UPDATE compras SET estado_pago = 'parcial' WHERE CompraID = :compra_id";
                $stmt = $conn->prepare($sql);
                $stmt->execute([':compra_id' => $data['compra_id']]);
            }
            
            $conn->commit();
            echo json_encode([
                'success' => true,
                'message' => 'Abono registrado correctamente',
                'saldo_pendiente' => $saldoPendiente
            ]);
        } catch (Exception $e) {
            $conn->rollBack();
            echo json_encode([
                'error' => true,
                'message' => 'Error al registrar abono: ' . $e->getMessage()
            ]);
        }
    }
}

function getCompras() {
    global $conn;

    try {
        $sql = "SELECT c.CompraID, c.NumeroFactura, c.FechaCompra, c.TotalCompra, 
                       c.estado_pago, c.saldo_pendiente, c.es_plazo,
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

function getCompraPlazos() {
    global $conn;
    $compraID = $_GET['compra_id'] ?? null;

    try {
        // Obtener información de la compra
        $sql = "SELECT c.CompraID, c.TotalCompra, c.saldo_pendiente, c.estado_pago,
                       p.Nombre as proveedor_nombre
                FROM compras c
                JOIN proveedores p ON c.RUC = p.RUC
                WHERE c.CompraID = :compra_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':compra_id' => $compraID]);
        $compra = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$compra) {
            throw new Exception("Compra no encontrada");
        }
        
        // Obtener plazos
        $sql = "SELECT pc.*, 
                       CASE 
                           WHEN pc.estado = 'pagado' THEN 'Pagado'
                           WHEN pc.fecha_vencimiento < CURDATE() THEN 'Vencido'
                           ELSE 'Pendiente'
                       END as estado_actual
                FROM plazos_compra pc
                WHERE pc.compra_id = :compra_id
                ORDER BY pc.fecha_vencimiento";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':compra_id' => $compraID]);
        $plazos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Obtener abonos realizados
        $sql = "SELECT * FROM abonos_compra 
                WHERE compra_id = :compra_id
                ORDER BY fecha_abono";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':compra_id' => $compraID]);
        $abonos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true,
            'compra' => $compra,
            'plazos' => $plazos,
            'abonos' => $abonos
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'error' => true,
            'message' => 'Error al obtener plazos: ' . $e->getMessage()
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
?>