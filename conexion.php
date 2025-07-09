<?php
$host = "localhost";
$usuario = "u502112016_parejas";
$contrasena = "Parejas1234*";
$base_datos = "u502112016_parejas";

// Crear conexión
$conn = new mysqli($host, $usuario, $contrasena, $base_datos);

// Verificar conexión
if ($conn->connect_error) {
    die("❌ Conexión fallida: " . $conn->connect_error);
}

// echo "✅ Conexión exitosa";
?>
