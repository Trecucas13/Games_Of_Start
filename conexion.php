<?php
$host = "localhost"; // Cambia esto si el hosting tiene otro servidor
$user = "u502112016_preguntas"; // Usuario de MySQL
$pass = "Trescucas13-@"; // Si tienes contraseña en MySQL, agrégala aquí
$dbname = "u502112016_preguntas"; // Asegúrate de que coincide con la BD creada

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("❌ Error de conexión: " . $conn->connect_error);
}
?>
