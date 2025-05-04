<?php
include '../conexion.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? ($_POST['action'] ?? '');

switch ($action) {
    case 'create':
        crearVehiculo();
        break;
    case 'read':
        leerVehiculos();
        break;
    case 'read_one':
        leerVehiculo();
        break;
    case 'update':
        actualizarVehiculo();
        break;
    case 'delete':
        eliminarVehiculo();
        break;
    case 'get_clientes':
        getClientes();
        break;
    default:
        echo json_encode(['error' => 'Acción no válida']);
        break;
}

function crearVehiculo() {
    global $conn;
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $required = ['clienteID', 'marca', 'modelo', 'anio', 'placa'];
            foreach ($required as $field) {
                if (empty($_POST[$field])) {
                    http_response_code(400);
                    echo json_encode(['error' => "El campo $field es obligatorio"]);
                    exit();
                }
            }

            // Convertir fechas vacías a null
            $ultimoServicio = !empty($_POST['ultimoServicio']) ? $_POST['ultimoServicio'] : null;
            $proximoServicio = !empty($_POST['proximoServicio']) ? $_POST['proximoServicio'] : null;

            $sql = "INSERT INTO vehiculos (
                ClienteID, Marca, Modelo, Anio, Placa, VIN, Kilometraje, 
                UltimoServicio, ProximoServicio, Color, Tipo, Combustible, Transmision, Observaciones
            ) VALUES (
                :clienteID, :marca, :modelo, :anio, :placa, :vin, :kilometraje, 
                :ultimoServicio, :proximoServicio, :color, :tipo, :combustible, :transmision, :observaciones
            )";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':clienteID' => $_POST['clienteID'],
                ':marca' => $_POST['marca'],
                ':modelo' => $_POST['modelo'],
                ':anio' => $_POST['anio'],
                ':placa' => $_POST['placa'],
                ':vin' => $_POST['vin'] ?? null,
                ':kilometraje' => $_POST['kilometraje'] ?? 0,
                ':ultimoServicio' => $ultimoServicio,
                ':proximoServicio' => $proximoServicio,
                ':color' => $_POST['color'] ?? null,
                ':tipo' => $_POST['tipo'] ?? 'Automovil',
                ':combustible' => $_POST['combustible'] ?? 'Gasolina',
                ':transmision' => $_POST['transmision'] ?? null,
                ':observaciones' => $_POST['observaciones'] ?? null
            ]);
            
            $vehiculoID = $conn->lastInsertId();
            echo json_encode([
                'success' => 'Vehículo agregado correctamente',
                'vehiculoID' => $vehiculoID
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $field = strpos($e->getMessage(), 'Placa') !== false ? 'Placa' : 'VIN';
                echo json_encode(['error' => "El $field ingresado ya existe en el sistema"]);
            } else {
                echo json_encode(['error' => 'Error al agregar vehículo: ' . $e->getMessage()]);
            }
        }
    }
}

function leerVehiculos() {
    global $conn;

    try {
        $sql = "SELECT v.*, c.Nombre as ClienteNombre, c.Cedula as ClienteCedula 
                FROM vehiculos v
                JOIN clientes c ON v.ClienteID = c.ClienteID
                ORDER BY v.Marca, v.Modelo";
        $stmt = $conn->query($sql);
        $vehiculos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($vehiculos);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Error al obtener vehículos: ' . $e->getMessage()]);
    }
}

function leerVehiculo() {
    global $conn;
    
    $vehiculoID = $_GET['vehiculoID'] ?? '';
    if (empty($vehiculoID)) {
        http_response_code(400);
        echo json_encode(['error' => 'ID de vehículo no proporcionado']);
        return;
    }

    try {
        $sql = "SELECT v.*, c.Nombre as ClienteNombre, c.Cedula as ClienteCedula
                FROM vehiculos v
                JOIN clientes c ON v.ClienteID = c.ClienteID
                WHERE v.VehiculoID = :vehiculoID";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':vehiculoID' => $vehiculoID]);
        $vehiculo = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($vehiculo) {
            echo json_encode($vehiculo);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Vehículo no encontrado']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Error al obtener vehículo: ' . $e->getMessage()]);
    }
}

function actualizarVehiculo() {
    global $conn;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $required = ['vehiculoID', 'clienteID', 'marca', 'modelo', 'anio', 'placa'];
            foreach ($required as $field) {
                if (empty($_POST[$field])) {
                    http_response_code(400);
                    echo json_encode(['error' => "El campo $field es obligatorio"]);
                    exit();
                }
            }

            // Convertir fechas vacías a null
            $ultimoServicio = !empty($_POST['ultimoServicio']) ? $_POST['ultimoServicio'] : null;
            $proximoServicio = !empty($_POST['proximoServicio']) ? $_POST['proximoServicio'] : null;

            $sql = "UPDATE vehiculos SET 
                    ClienteID = :clienteID,
                    Marca = :marca,
                    Modelo = :modelo,
                    Anio = :anio,
                    Placa = :placa,
                    VIN = :vin,
                    Kilometraje = :kilometraje,
                    UltimoServicio = :ultimoServicio,
                    ProximoServicio = :proximoServicio,
                    Color = :color,
                    Tipo = :tipo,
                    Combustible = :combustible,
                    Transmision = :transmision,
                    Observaciones = :observaciones
                    WHERE VehiculoID = :vehiculoID";
                    
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':clienteID' => $_POST['clienteID'],
                ':marca' => $_POST['marca'],
                ':modelo' => $_POST['modelo'],
                ':anio' => $_POST['anio'],
                ':placa' => $_POST['placa'],
                ':vin' => $_POST['vin'] ?? null,
                ':kilometraje' => $_POST['kilometraje'] ?? 0,
                ':ultimoServicio' => $ultimoServicio,
                ':proximoServicio' => $proximoServicio,
                ':color' => $_POST['color'] ?? null,
                ':tipo' => $_POST['tipo'] ?? 'Automovil',
                ':combustible' => $_POST['combustible'] ?? 'Gasolina',
                ':transmision' => $_POST['transmision'] ?? null,
                ':observaciones' => $_POST['observaciones'] ?? null,
                ':vehiculoID' => $_POST['vehiculoID']
            ]);
            
            echo json_encode(['success' => 'Vehículo actualizado correctamente']);
        } catch (PDOException $e) {
            http_response_code(500);
            
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $field = strpos($e->getMessage(), 'Placa') !== false ? 'Placa' : 'VIN';
                echo json_encode(['error' => "El $field ingresado ya existe en el sistema"]);
            } else {
                echo json_encode(['error' => 'Error al actualizar vehículo: ' . $e->getMessage()]);
            }
        }
    }
}

function eliminarVehiculo() {
    global $conn;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $vehiculoID = $_POST['vehiculoID'];
            
            // Eliminar directamente sin verificar servicios asociados
            $sql = "DELETE FROM vehiculos WHERE VehiculoID = :vehiculoID";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':vehiculoID' => $vehiculoID]);
            
            if ($stmt->rowCount() > 0) {
                echo json_encode(['success' => 'Vehículo eliminado correctamente']);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Vehículo no encontrado']);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Error al eliminar vehículo: ' . $e->getMessage()]);
        }
    }
}

function getClientes() {
    global $conn;

    try {
        $sql = "SELECT ClienteID, Nombre, Cedula FROM clientes ORDER BY Nombre";
        $stmt = $conn->query($sql);
        $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($clientes);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Error al obtener clientes: ' . $e->getMessage()]);
    }
}
?>