<?php
// db.php - Configuración de la conexión a la base de datos

$servername = "localhost";
$username = "root";  // El nombre de usuario por defecto en XAMPP es 'root'
$password = "";  // La contraseña por defecto en XAMPP es vacía
$dbname = "api_db";  // Nombre de tu base de datos

// Crear la conexión con PDO para mejorar la seguridad y flexibilidad
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "La conexión falló: " . $e->getMessage();
}
?>
