<?php
$servername = "localhost"; // Cambia esto si tu servidor de base de datos es diferente
$username = "root"; // Tu nombre de usuario de MySQL
$password = ""; // Tu contraseña de MySQL
$dbname = "ticket_system"; // El nombre de tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>