<?php
include '../conexion.php';

// Establecer el tipo de contenido como JSON
header('Content-Type: application/json');

// Determinar la acción a realizar
$action = $_GET['action'] ?? ($_POST['action'] ?? '');

switch ($action) {
    case 'create':
        crearCliente();
        break;
    case 'read':
        leerClientes();
        break;
    case 'read_one':
        leerCliente();
        break;
    case 'update':
        actualizarCliente();
        break;
    case 'delete':
        eliminarCliente();
        break;
    default:
        echo json_encode(['error' => 'Acción no válida']);
        break;
}

function leerCliente() {
    global $conn;
    
    $cedula = $_GET['cedula'] ?? '';
    if (empty($cedula)) {
        echo json_encode(['error' => 'Cédula no proporcionada']);
        return;
    }

    try {
        $sql = "SELECT * FROM clientes WHERE Cedula = :cedula";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':cedula' => $cedula]);
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($cliente) {
            echo json_encode($cliente);
        } else {
            echo json_encode(['error' => 'Cliente no encontrado']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error al obtener cliente: ' . $e->getMessage()]);
    }
}

function crearCliente() {
    global $conn;
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $sql = "INSERT INTO clientes (Nombre, Cedula, Direccion, Telefono, Email, FechaRegistro) 
                    VALUES (:nombre, :cedula, :direccion, :telefono, :email, NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':nombre' => $_POST['nombre'],
                ':cedula' => $_POST['cedula'],
                ':direccion' => $_POST['direccion'] ?? null,
                ':telefono' => $_POST['telefono'] ?? null,
                ':email' => $_POST['email'] ?? null
            ]);
            
            echo json_encode(['success' => 'Cliente agregado correctamente']);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Error al agregar cliente: ' . $e->getMessage()]);
        }
    }
}

function leerClientes() {
    global $conn;

    try {
        $sql = "SELECT * FROM clientes ORDER BY Nombre";
        $stmt = $conn->query($sql);
        $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($clientes);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error al obtener clientes: ' . $e->getMessage()]);
    }
}

function actualizarCliente() {
    global $conn;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $sql = "UPDATE clientes SET 
                    Nombre = :nombre, 
                    Direccion = :direccion, 
                    Telefono = :telefono, 
                    Email = :email 
                    WHERE Cedula = :cedula";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':nombre' => $_POST['nombre'],
                ':direccion' => $_POST['direccion'] ?? null,
                ':telefono' => $_POST['telefono'] ?? null,
                ':email' => $_POST['email'] ?? null,
                ':cedula' => $_POST['cedula']
            ]);
            
            echo json_encode(['success' => 'Cliente actualizado correctamente']);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Error al actualizar cliente: ' . $e->getMessage()]);
        }
    }
}

function eliminarCliente() {
    global $conn;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            // Verificar si el cliente existe
            $sqlCheckExist = "SELECT ClienteID FROM clientes WHERE Cedula = :cedula";
            $stmtCheckExist = $conn->prepare($sqlCheckExist);
            $stmtCheckExist->execute([':cedula' => $_POST['cedula']]);
            $clienteID = $stmtCheckExist->fetchColumn();
            
            if (!$clienteID) {
                echo json_encode(['error' => 'Cliente no encontrado']);
                return;
            }

            // Verificar si el cliente tiene vehículos asociados
            $sqlCheckVehiculos = "SELECT COUNT(*) FROM vehiculos WHERE ClienteID = :clienteID";
            $stmtCheckVehiculos = $conn->prepare($sqlCheckVehiculos);
            $stmtCheckVehiculos->execute([':clienteID' => $clienteID]);
            $count = $stmtCheckVehiculos->fetchColumn();
            
            if ($count > 0) {
                echo json_encode(['error' => 'No se puede eliminar el cliente porque tiene vehículos asociados']);
                return;
            }

            // Si no hay vehículos asociados, proceder con la eliminación
            $sql = "DELETE FROM clientes WHERE Cedula = :cedula";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':cedula' => $_POST['cedula']]);
            
            if ($stmt->rowCount() > 0) {
                echo json_encode(['success' => 'Cliente eliminado correctamente']);
            } else {
                echo json_encode(['error' => 'No se pudo eliminar el cliente']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Error al eliminar cliente: ' . $e->getMessage()]);
        }
    }
}