<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $campana = $_POST["campana"];
    $emoji_final = $_POST["emoji_final"];
    $respuestas = json_decode($_POST["respuestas"], true); // Convertir respuestas a array

    // Insertar usuario
    $sql = "INSERT INTO respuestas (nombre, campana, fecha, emoji_final) VALUES (?, ?, NOW(), ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nombre, $campana, $emoji_final);
    $stmt->execute();
    $usuario_id = $stmt->insert_id; // Obtener el ID del usuario insertado
    $stmt->close();

    // Insertar respuestas del usuario
    $sql_respuesta = "INSERT INTO respuestas_usuarios (usuario_id, pregunta, respuesta, fecha) VALUES (?, ?, ?, NOW())";
    $stmt_respuesta = $conn->prepare($sql_respuesta);

    foreach ($respuestas as $respuesta) {
        $stmt_respuesta->bind_param("iss", $usuario_id, $respuesta["pregunta"], $respuesta["respuesta"]);
        $stmt_respuesta->execute();
    }

    $stmt_respuesta->close();
    $conn->close();

    echo "✅ Usuario y respuestas guardados correctamente";
} else {
    echo "❌ No se recibieron datos por POST";
}
?>
