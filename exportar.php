<?php
include 'conexion.php';
require 'SimpleXLSXGen.php';

use Shuchkin\SimpleXLSXGen;

$data = [
    ['ID Usuario', 'Nombre', 'Campaña', 'Pregunta', 'Respuesta', 'Fecha y Hora'] // Encabezado
];

$sql = "SELECT r.id, r.nombre, r.campana, ru.pregunta, ru.respuesta, ru.fecha 
        FROM respuestas_usuarios ru
        JOIN respuestas r ON ru.usuario_id = r.id
        ORDER BY ru.fecha DESC";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = [$row['id'], $row['nombre'], $row['campana'], $row['pregunta'], $row['respuesta'], $row['fecha']];
    }
}

$conn->close();

// Generar el archivo Excel (sin estilos avanzados)
$xlsx = SimpleXLSXGen::fromArray($data);
$xlsx->downloadAs('Respuestas_Usuarios.xlsx');
exit;
?>
