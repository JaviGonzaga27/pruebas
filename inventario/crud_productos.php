<?php
include '../conexion.php';

// Manejar tanto GET como POST para acciones
$action = $_POST['action'] ?? ($_GET['action'] ?? '');

switch ($action) {
    case 'create':
        crearProducto();
        break;
    case 'read':
        leerProductos();
        break;
    case 'update':
        actualizarProducto();
        break;
    case 'delete':
        eliminarProducto();
        break;
    case 'get_categorias':
        getCategorias();
        break;
    case 'get_proveedores':
        getProveedores();
        break;
    case 'get_producto':
        getProducto();
        break;
    default:
        echo json_encode(['error' => 'Acción no válida']);
        break;
}

function crearProducto() {
    global $conn;
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            // Manejar la imagen
            $imagen = null;
            if(isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if(!in_array($_FILES['imagen']['type'], $allowedTypes)) {
                    echo json_encode(['error' => 'Tipo de imagen no permitido']);
                    exit();
                }
                
                if($_FILES['imagen']['size'] > 2097152) {
                    echo json_encode(['error' => 'La imagen no debe exceder 2MB']);
                    exit();
                }
                
                $imagen = file_get_contents($_FILES['imagen']['tmp_name']);
                if($imagen === false) {
                    echo json_encode(['error' => 'Error al leer la imagen']);
                    exit();
                }
            }
            
            // Obtener valores de campos específicos para líquidos
            // Forzar es_liquido a 0 si no está explícitamente marcado
            $esLiquido = isset($_POST['esLiquido']) && $_POST['esLiquido'] == '1' ? 1 : 0;
            $tipoAceite = $esLiquido ? ($_POST['tipoAceite'] ?? null) : null;
            $capacidadEnvase = $esLiquido ? ($_POST['capacidadEnvase'] ?? 0) : 0;
            $contenidoActual = $esLiquido ? ($_POST['contenidoActual'] ?? 0) : 0;
            
            // Determinar qué valor de stock usar
            $stock = $esLiquido ? 0 : ($_POST['stock'] ?? 0); // Para líquidos, el stock real está en contenido_actual
            
            $sql = "INSERT INTO productos (
                ProductoID, Nombre, Descripcion, PrecioCompra, PrecioVenta, 
                Stock, StockMinimo, Estado, Compatibilidad, FechaIngreso, 
                CategoriaID, RUC, Imagen, TieneGarantia, DiasGarantia,
                CodigoBarras, UbicacionAlmacen, es_liquido, marca, tipo_aceite,
                capacidad_envase, contenido_actual
            ) VALUES (
                :productoID, :nombre, :descripcion, :precioCompra, :precioVenta, 
                :stock, :stockMinimo, :estado, :compatibilidad, :fechaIngreso, 
                :categoriaID, :ruc, :imagen, :tieneGarantia, :diasGarantia,
                :codigoBarras, :ubicacionAlmacen, :es_liquido, :marca, :tipo_aceite,
                :capacidad_envase, :contenido_actual
            )";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':productoID' => $_POST['productoID'],
                ':nombre' => $_POST['nombre'],
                ':descripcion' => $_POST['descripcion'] ?? null,
                ':precioCompra' => $_POST['precioCompra'],
                ':precioVenta' => $_POST['precioVenta'],
                ':stock' => $stock,
                ':stockMinimo' => $_POST['stockMinimo'] ?? 5,
                ':estado' => $_POST['estado'] ?? 'Activo',
                ':compatibilidad' => $_POST['compatibilidad'] ?? null,
                ':fechaIngreso' => $_POST['fechaIngreso'] ?? date('Y-m-d'),
                ':categoriaID' => $_POST['categoriaID'],
                ':ruc' => $_POST['ruc'],
                ':imagen' => $imagen,
                ':tieneGarantia' => isset($_POST['tieneGarantia']) ? 1 : 0,
                ':diasGarantia' => !empty($_POST['diasGarantia']) ? $_POST['diasGarantia'] : null,
                ':codigoBarras' => $_POST['codigoBarras'] ?? null,
                ':ubicacionAlmacen' => $_POST['ubicacionAlmacen'] ?? null,
                ':es_liquido' => $esLiquido,
                ':marca' => $_POST['marca'] ?? null,
                ':tipo_aceite' => $tipoAceite,
                ':capacidad_envase' => $capacidadEnvase,
                ':contenido_actual' => $contenidoActual
            ]);
            
            echo json_encode(['success' => 'Producto agregado correctamente', 'id' => $_POST['productoID']]);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Error al agregar producto: ' . $e->getMessage()]);
        }
    }
}
function actualizarProducto() {
    global $conn;
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $conn->beginTransaction();
            
            // Obtener valores de campos específicos para líquidos
            $esLiquido = isset($_POST['esLiquido']) && $_POST['esLiquido'] == '1' ? 1 : 0;
            $tipoAceite = $esLiquido ? ($_POST['tipoAceite'] ?? null) : null;
            $capacidadEnvase = $esLiquido ? ($_POST['capacidadEnvase'] ?? 0) : 0;
            $contenidoActual = $esLiquido ? ($_POST['contenidoActual'] ?? 0) : 0;
            
            // Consulta base
            $sql = "UPDATE productos SET 
                Nombre = :nombre,
                Descripcion = :descripcion,
                PrecioCompra = :precioCompra,
                PrecioVenta = :precioVenta,
                Stock = :stock,
                StockMinimo = :stockMinimo,
                Estado = :estado,
                Compatibilidad = :compatibilidad,
                FechaIngreso = :fechaIngreso,
                CategoriaID = :categoriaID,
                RUC = :ruc,
                TieneGarantia = :tieneGarantia,
                DiasGarantia = :diasGarantia,
                CodigoBarras = :codigoBarras,
                UbicacionAlmacen = :ubicacionAlmacen,
                es_liquido = :es_liquido,
                marca = :marca,
                tipo_aceite = :tipo_aceite,
                capacidad_envase = :capacidad_envase,
                contenido_actual = :contenido_actual";
            
            // Parámetros base
            $params = [
                ':nombre' => $_POST['nombre'],
                ':descripcion' => $_POST['descripcion'] ?? null,
                ':precioCompra' => $_POST['precioCompra'],
                ':precioVenta' => $_POST['precioVenta'],
                ':stock' => $esLiquido ? 0 : ($_POST['stock'] ?? 0), // Para líquidos, el stock real está en contenido_actual
                ':stockMinimo' => $_POST['stockMinimo'] ?? 5,
                ':estado' => $_POST['estado'] ?? 'Activo',
                ':compatibilidad' => $_POST['compatibilidad'] ?? null,
                ':fechaIngreso' => $_POST['fechaIngreso'] ?? date('Y-m-d'),
                ':categoriaID' => $_POST['categoriaID'],
                ':ruc' => $_POST['ruc'],
                ':tieneGarantia' => isset($_POST['tieneGarantia']) ? 1 : 0,
                ':diasGarantia' => !empty($_POST['diasGarantia']) ? $_POST['diasGarantia'] : null,
                ':codigoBarras' => $_POST['codigoBarras'] ?? null,
                ':ubicacionAlmacen' => $_POST['ubicacionAlmacen'] ?? null,
                ':es_liquido' => $esLiquido,
                ':marca' => $_POST['marca'] ?? null,
                ':tipo_aceite' => $tipoAceite,
                ':capacidad_envase' => $capacidadEnvase,
                ':contenido_actual' => $contenidoActual,
                ':productoID' => $_POST['productoID']
            ];
            
            // Manejar la imagen si se proporciona
            if(isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if(!in_array($_FILES['imagen']['type'], $allowedTypes)) {
                    echo json_encode(['error' => 'Tipo de imagen no permitido']);
                    exit();
                }
                
                if($_FILES['imagen']['size'] > 2097152) {
                    echo json_encode(['error' => 'La imagen no debe exceder 2MB']);
                    exit();
                }
                
                $imagen = file_get_contents($_FILES['imagen']['tmp_name']);
                if($imagen === false) {
                    echo json_encode(['error' => 'Error al leer la imagen']);
                    exit();
                }
                
                $sql .= ", Imagen = :imagen";
                $params[':imagen'] = $imagen;
            }
            
            $sql .= " WHERE ProductoID = :productoID";
                
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            
            $conn->commit();
            
            echo json_encode(['success' => 'Producto actualizado correctamente']);
        } catch (PDOException $e) {
            $conn->rollBack();
            echo json_encode(['error' => 'Error al actualizar producto: ' . $e->getMessage()]);
        }
    }
}

function leerProductos() {
    global $conn;

    try {
        $sql = "SELECT 
                    p.ProductoID, p.Nombre, p.Descripcion, p.PrecioCompra, p.PrecioVenta, 
                    p.Stock, p.StockMinimo, p.Estado, p.Compatibilidad, p.FechaIngreso, 
                    p.CategoriaID, p.RUC, p.TieneGarantia, p.DiasGarantia, 
                    p.CodigoBarras, p.UbicacionAlmacen, p.es_liquido, p.marca,
                    p.tipo_aceite, p.capacidad_envase, p.contenido_actual,
                    c.Nombre as CategoriaNombre, 
                    pr.Nombre as ProveedorNombre,
                    CASE WHEN p.Imagen IS NOT NULL THEN 1 ELSE 0 END as TieneImagen,
                    CASE 
                        WHEN p.es_liquido = 1 THEN CONCAT(p.contenido_actual, ' litros')
                        ELSE CONCAT(p.Stock, ' unidades')
                    END as stock_display
                FROM productos p
                JOIN categorias c ON p.CategoriaID = c.CategoriaID
                JOIN proveedores pr ON p.RUC = pr.RUC
                ORDER BY p.Nombre";
        
        $stmt = $conn->query($sql);
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($productos);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error al obtener productos: ' . $e->getMessage()]);
    }
}

function getProducto() {
    global $conn;
    
    ob_clean();
    header('Content-Type: application/json');
    
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'ID de producto no proporcionado']);
        exit();
    }

    try {
        $sql = "SELECT 
                    p.ProductoID, p.Nombre, p.Descripcion, p.PrecioCompra, 
                    p.PrecioVenta, p.Stock, p.StockMinimo, p.Estado, 
                    p.Compatibilidad, p.FechaIngreso, p.CategoriaID, p.RUC,
                    p.TieneGarantia, p.DiasGarantia, p.CodigoBarras, p.UbicacionAlmacen,
                    p.es_liquido, p.marca, p.tipo_aceite, p.capacidad_envase, p.contenido_actual,
                    c.Nombre as CategoriaNombre, 
                    pr.Nombre as ProveedorNombre,
                    CASE WHEN p.Imagen IS NOT NULL THEN 1 ELSE 0 END as TieneImagen
                FROM productos p
                JOIN categorias c ON p.CategoriaID = c.CategoriaID
                JOIN proveedores pr ON p.RUC = pr.RUC
                WHERE p.ProductoID = :id";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $_GET['id']]);
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$producto) {
            http_response_code(404);
            echo json_encode(['error' => 'Producto no encontrado']);
            exit();
        }

        if ($producto['TieneImagen']) {
            $sqlImagen = "SELECT Imagen FROM productos WHERE ProductoID = :id";
            $stmtImagen = $conn->prepare($sqlImagen);
            $stmtImagen->execute([':id' => $_GET['id']]);
            $imagen = $stmtImagen->fetchColumn();
            
            if ($imagen !== false) {
                $producto['ImagenBase64'] = base64_encode($imagen);
            } else {
                $producto['TieneImagen'] = 0;
            }
        }

        // Asegurar que todos los campos requeridos existan
        $camposRequeridos = [
            'Descripcion', 'Compatibilidad', 'CodigoBarras', 'UbicacionAlmacen',
            'marca', 'tipo_aceite', 'capacidad_envase', 'contenido_actual'
        ];
        
        foreach ($camposRequeridos as $campo) {
            if (!isset($producto[$campo])) {
                $producto[$campo] = null;
            }
        }

        echo json_encode($producto);
        exit();
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Error al obtener producto: ' . $e->getMessage()]);
        exit();
    }
}

function eliminarProducto() {
    global $conn;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $productoID = $_POST['productoID'];
        
        try {
            $conn->beginTransaction();
            
            $sqlDeleteHistorial = "DELETE FROM historial_precios WHERE ProductoID = :productoID";
            $stmtHistorial = $conn->prepare($sqlDeleteHistorial);
            $stmtHistorial->execute([':productoID' => $productoID]);
            
            $sqlCheckMovimientos = "SELECT COUNT(*) FROM movimientosinventario WHERE ProductoID = :productoID";
            $stmtCheck = $conn->prepare($sqlCheckMovimientos);
            $stmtCheck->execute([':productoID' => $productoID]);
            $count = $stmtCheck->fetchColumn();
            
            if ($count > 0) {
                throw new Exception('No se puede eliminar el producto porque tiene movimientos de inventario asociados');
            }
            
            $sqlCheckVentas = "SELECT COUNT(*) FROM detalleventa WHERE ProductoID = :productoID";
            $stmtCheck = $conn->prepare($sqlCheckVentas);
            $stmtCheck->execute([':productoID' => $productoID]);
            $count = $stmtCheck->fetchColumn();
            
            if ($count > 0) {
                throw new Exception('No se puede eliminar el producto porque tiene ventas asociadas');
            }
            
            $sql = "DELETE FROM productos WHERE ProductoID = :productoID";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':productoID' => $productoID]);
            
            $conn->commit();
            
            echo json_encode(['success' => 'Producto eliminado correctamente']);
        } catch (Exception $e) {
            $conn->rollBack();
            echo json_encode(['error' => 'Error al eliminar producto: ' . $e->getMessage()]);
        }
    }
}

function getCategorias() {
    global $conn;

    try {
        $sql = "SELECT CategoriaID, Nombre FROM categorias ORDER BY Nombre";
        $stmt = $conn->query($sql);
        $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($categorias);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error al obtener categorías: ' . $e->getMessage()]);
    }
}

function getProveedores() {
    global $conn;

    try {
        $sql = "SELECT RUC, Nombre FROM proveedores ORDER BY Nombre";
        $stmt = $conn->query($sql);
        $proveedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($proveedores);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error al obtener proveedores: ' . $e->getMessage()]);
    }
}