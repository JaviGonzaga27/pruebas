<?php
include '../conexion.php';

// Establecer el tipo de contenido como JSON
header('Content-Type: application/json');

// Determinar la acción a realizar
$action = $_GET['action'] ?? ($_POST['action'] ?? '');

switch ($action) {
    case 'create':
        crearProveedor();
        break;
    case 'read':
        leerProveedores();
        break;
    case 'update':
        actualizarProveedor();
        break;
    
        case 'read_one':
            leerProveedor();
            break;    
    case 'delete':
        eliminarProveedor();
        break;
    
    default:
        echo json_encode(['error' => 'Acción no válida']);
        break;
}

function leerProveedor() {
    global $conn;
    
    $ruc = $_GET['ruc'] ?? '';
    if (empty($ruc)) {
        echo json_encode(['error' => 'RUC no proporcionado']);
        return;
    }

    try {
        $sql = "SELECT * FROM proveedores WHERE RUC = :ruc";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':ruc' => $ruc]);
        $proveedor = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($proveedor) {
            echo json_encode($proveedor);
        } else {
            echo json_encode(['error' => 'Proveedor no encontrado']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error al obtener proveedor: ' . $e->getMessage()]);
    }
}

function crearProveedor() {
    global $conn;
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $sql = "INSERT INTO Proveedores (RUC, Nombre, Direccion, Telefono, Celular, Email) 
                    VALUES (:ruc, :nombre, :direccion, :telefono, :celular, :email)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':ruc' => $_POST['ruc'],
                ':nombre' => $_POST['nombre'],
                ':direccion' => $_POST['direccion'] ?? null,
                ':telefono' => $_POST['telefono'] ?? null,
                ':celular' => $_POST['celular'] ?? null,
                ':email' => $_POST['email'] ?? null
            ]);
            
            echo json_encode(['success' => 'Proveedor agregado correctamente']);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Error al agregar proveedor: ' . $e->getMessage()]);
        }
    }
}

function leerProveedores() {
    global $conn;

    try {
        $sql = "SELECT * FROM proveedores";
        $stmt = $conn->query($sql);
        $proveedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($proveedores);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error al obtener proveedores: ' . $e->getMessage()]);
    }
}

function actualizarProveedor() {
    global $conn;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $sql = "UPDATE Proveedores SET 
                    Nombre = :nombre, 
                    Direccion = :direccion, 
                    Telefono = :telefono, 
                    Celular = :celular, 
                    Email = :email 
                    WHERE RUC = :ruc";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':nombre' => $_POST['nombre'],
                ':direccion' => $_POST['direccion'] ?? null,
                ':telefono' => $_POST['telefono'] ?? null,
                ':celular' => $_POST['celular'] ?? null,
                ':email' => $_POST['email'] ?? null,
                ':ruc' => $_POST['ruc']
            ]);
            
            echo json_encode(['success' => 'Proveedor actualizado correctamente']);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Error al actualizar proveedor: ' . $e->getMessage()]);
        }
    }
}

function eliminarProveedor() {
    global $conn;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            // Verificar si el proveedor tiene productos asociados
            $sqlCheck = "SELECT COUNT(*) FROM productos WHERE RUC = :ruc";
            $stmtCheck = $conn->prepare($sqlCheck);
            $stmtCheck->execute([':ruc' => $_POST['ruc']]);
            $count = $stmtCheck->fetchColumn();
            
            if ($count > 0) {
                echo json_encode(['error' => 'No se puede eliminar el proveedor porque tiene productos asociados']);
                return;
            }

            $sql = "DELETE FROM Proveedores WHERE RUC = :ruc";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':ruc' => $_POST['ruc']]);
            
            echo json_encode(['success' => 'Proveedor eliminado correctamente']);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Error al eliminar proveedor: ' . $e->getMessage()]);
        }
    }
}
?>