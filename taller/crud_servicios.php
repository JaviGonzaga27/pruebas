<?php
include '../conexion.php';

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'create':
        crearServicio();
        break;
    case 'read':
        getServicios();
        break;
    case 'update':
        actualizarServicio();
        break;
    case 'delete':
        eliminarServicio();
        break;
    case 'search':
        buscarServicios();
        break;
    default:
        echo json_encode(['error' => 'Acción no válida']);
        break;
}

function crearServicio() {
    global $conn;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        try {
            $sql = "INSERT INTO servicios (Nombre, Descripcion, Precio) 
                    VALUES (:nombre, :descripcion, :precio)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':nombre' => $data['nombre'],
                ':descripcion' => $data['descripcion'],
                ':precio' => $data['precio']
            ]);
            
            echo json_encode(['success' => 'Servicio creado correctamente']);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Error al crear servicio: ' . $e->getMessage()]);
        }
    }
}

function getServicios() {
    global $conn;

    try {
        $sql = "SELECT * FROM servicios ORDER BY Nombre";
        $stmt = $conn->query($sql);
        $servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($servicios);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error al obtener servicios: ' . $e->getMessage()]);
    }
}

function actualizarServicio() {
    global $conn;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        try {
            $sql = "UPDATE servicios SET 
                    Nombre = :nombre,
                    Descripcion = :descripcion,
                    Precio = :precio
                    WHERE ServicioID = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':nombre' => $data['nombre'],
                ':descripcion' => $data['descripcion'],
                ':precio' => $data['precio'],
                ':id' => $data['id']
            ]);
            
            echo json_encode(['success' => 'Servicio actualizado correctamente']);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Error al actualizar servicio: ' . $e->getMessage()]);
        }
    }
}

function eliminarServicio() {
    global $conn;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        try {
            $sql = "DELETE FROM servicios WHERE ServicioID = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':id' => $data['id']]);
            
            echo json_encode(['success' => 'Servicio eliminado correctamente']);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Error al eliminar servicio: ' . $e->getMessage()]);
        }
    }
}

function buscarServicios() {
    global $conn;
    
    $search = $_GET['search'] ?? '';
    
    try {
        $sql = "SELECT * FROM servicios 
                WHERE Nombre LIKE :search OR Descripcion LIKE :search
                ORDER BY Nombre
                LIMIT 20";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':search' => "%$search%"]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>