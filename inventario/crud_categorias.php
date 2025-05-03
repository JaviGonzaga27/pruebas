<?php
include '../conexion.php';

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'create':
        crearCategoria();
        break;
    case 'read':
        leerCategorias();
        break;
    case 'update':
        actualizarCategoria();
        break;
    case 'delete':
        eliminarCategoria();
        break;
    default:
        echo "Acción no válida";
        break;
}

function crearCategoria() {
    global $conn;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'] ?? null;

        try {
            $sql = "INSERT INTO categorias (Nombre, Descripcion) VALUES (:nombre, :descripcion)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':nombre' => $nombre,
                ':descripcion' => $descripcion
            ]);
            echo "Categoría agregada correctamente.";
        } catch (PDOException $e) {
            echo "Error al agregar categoría: " . $e->getMessage();
        }
    }
}

function leerCategorias() {
    global $conn;

    try {
        $sql = "SELECT * FROM categorias";
        $stmt = $conn->query($sql);
        $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($categorias);
    } catch (PDOException $e) {
        echo "Error al obtener categorías: " . $e->getMessage();
    }
}

function actualizarCategoria() {
    global $conn;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $categoriaID = $_POST['categoriaID'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'] ?? null;

        try {
            $sql = "UPDATE categorias SET Nombre = :nombre, Descripcion = :descripcion WHERE CategoriaID = :categoriaID";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':nombre' => $nombre,
                ':descripcion' => $descripcion,
                ':categoriaID' => $categoriaID
            ]);
            echo "Categoría actualizada correctamente.";
        } catch (PDOException $e) {
            echo "Error al actualizar categoría: " . $e->getMessage();
        }
    }
}

function eliminarCategoria() {
    global $conn;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $categoriaID = $_POST['categoriaID'];

        try {
            $sql = "DELETE FROM categorias WHERE CategoriaID = :categoriaID";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':categoriaID' => $categoriaID]);
            echo "Categoría eliminada correctamente.";
        } catch (PDOException $e) {
            echo "Error al eliminar categoría: " . $e->getMessage();
        }
    }
}
?>