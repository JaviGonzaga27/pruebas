<?php
$host = "localhost"; // Servidor de la base de datos
$dbname = "mecanica4"; // Nombre de la base de datos
$username = "root"; // Usuario de la base de datos
$password = ""; // Contraseña de la base de datos

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Conexión exitosa"; // Opcional: Para verificar la conexión
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>