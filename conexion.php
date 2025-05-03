<?php
// Configuración de la base de datos
$config = [
    'host' => $_ENV['DB_HOST'] ?? 'localhost',
    'dbname' => $_ENV['DB_NAME'] ?? 'mecanica4',
    'username' => $_ENV['DB_USER'] ?? 'root',
    'password' => $_ENV['DB_PASS'] ?? '',
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
    ]
];

try {
    $conn = new PDO(
        "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}", 
        $config['username'], 
        $config['password'],
        $config['options']
    );
} catch (PDOException $e) {
    // En producción, no mostrar detalles del error
    if ($_ENV['APP_ENV'] ?? 'production' !== 'development') {
        error_log("Database connection error: " . $e->getMessage());
        die("Error de conexión. Por favor, intente más tarde.");
    } else {
        die("Error de conexión: " . $e->getMessage());
    }
}