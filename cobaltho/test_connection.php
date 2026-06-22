<?php
// Archivo para probar la conexión a la base de datos
require_once "includes/config.php";

$config = require "includes/config.php";
$dbConfig = $config['db'];

echo "<h2>Prueba de Conexión a XAMPP</h2>";
echo "<p>Host: " . htmlspecialchars($dbConfig['host']) . "</p>";
echo "<p>Usuario: " . htmlspecialchars($dbConfig['user']) . "</p>";
echo "<p>Base de datos: " . htmlspecialchars($dbConfig['name']) . "</p>";

$conn = new mysqli($dbConfig['host'], $dbConfig['user'], $dbConfig['pass']);

if ($conn->connect_error) {
    echo "<p style='color:red;'><strong>❌ Error de conexión:</strong> " . htmlspecialchars($conn->connect_error) . "</p>";
    echo "<p>Verifica que:</p>";
    echo "<ul>";
    echo "<li>MySQL esté corriendo en XAMPP (color verde)</li>";
    echo "<li>El usuario 'root' sea correcto</li>";
    echo "<li>La contraseña sea correcta (actualmente vacía)</li>";
    echo "</ul>";
} else {
    echo "<p style='color:green;'><strong>✅ Conexión exitosa!</strong></p>";
    
    $dbName = $conn->real_escape_string($dbConfig['name']);
    $conn->query("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
    
    if ($conn->select_db($dbConfig['name'])) {
        echo "<p style='color:green;'><strong>✅ Base de datos '" . htmlspecialchars($dbConfig['name']) . "' está lista!</strong></p>";
        echo "<p><a href='setup_database.php'>Haz clic aquí para inicializar las tablas</a></p>";
    } else {
        echo "<p style='color:orange;'>Puede que necesites correr setup_database.php</p>";
    }
    
    $conn->close();
}
?>