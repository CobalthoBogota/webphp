<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$config = require __DIR__ . "/config.php";
$dbConfig = $config['db'];

$conn = new mysqli($dbConfig['host'], $dbConfig['user'], $dbConfig['pass']);

if ($conn->connect_error) {
    die("Error de conexion: " . $conn->connect_error);
}

$dbName = $conn->real_escape_string($dbConfig['name']);
$conn->query("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
$conn->select_db($dbConfig['name']);
$conn->set_charset("utf8mb4");
?>
