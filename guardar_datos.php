<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include("conexion.php");

    // Sanitizar y obtener valores
    $nombre = $conn->real_escape_string($_POST["nombre"]);
    $campana = $conn->real_escape_string($_POST["campana"]);
    $movimientos = (int)$_POST["movimientos"];
    $tiempo = (int)$_POST["tiempo"];

    $sql = "INSERT INTO resultados (nombre, campana, movimientos, tiempo) 
            VALUES ('$nombre', '$campana', $movimientos, $tiempo)";

    if ($conn->query($sql) === TRUE) {
        echo "✅ Datos guardados correctamente";
    } else {
        echo "❌ Error al guardar: " . $conn->error;
    }

    $conn->close();
} else {
    echo "❌ Método no permitido";
}
?>
