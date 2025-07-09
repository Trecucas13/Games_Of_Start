<?php
include 'conexion.php';

// Obtener valores del filtro
$filtro_usuario = isset($_GET["usuario"]) ? $_GET["usuario"] : "";
$filtro_campana = isset($_GET["campana"]) ? $_GET["campana"] : "";

// Construir la consulta SQL con filtros
$sql = "SELECT r.id, r.nombre, r.campana, ru.pregunta, ru.respuesta, ru.fecha 
        FROM respuestas_usuarios ru
        JOIN respuestas r ON ru.usuario_id = r.id
        WHERE 1=1";

if (!empty($filtro_usuario)) {
    $sql .= " AND r.nombre LIKE '%$filtro_usuario%'";
}
if (!empty($filtro_campana)) {
    $sql .= " AND r.campana LIKE '%$filtro_campana%'";
}

$sql .= " ORDER BY r.id, ru.fecha DESC";
$result = $conn->query($sql);

// Almacenar los datos en un array para agruparlos por usuario
$usuarios = [];

while ($row = $result->fetch_assoc()) {
    $usuarios[$row["id"]]["nombre"] = $row["nombre"];
    $usuarios[$row["id"]]["campana"] = $row["campana"];
    $usuarios[$row["id"]]["respuestas"][] = [
        "pregunta" => $row["pregunta"],
        "respuesta" => $row["respuesta"],
        "fecha" => $row["fecha"]
    ];
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="imagenes/logo.png" type="image/x-icon">
    <title>Respuestas de Usuarios</title>
    <style>
        /* 🎨 Estilos generales */
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f9f9f9, #e3e3e3);
            text-align: center;
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        /* 🏷️ Título */
        h2 {
            color: #333;
            background: rgba(255, 255, 255, 0.8);
            padding: 15px 30px;
            border-radius: 10px;
            display: inline-block;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* 🎯 Contenedor de filtros */
        .filtros {
            margin-bottom: 20px;
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            width: 90%;
            max-width: 600px;
        }

        /* 📝 Campos de entrada */
        input {
            padding: 10px;
            border: 2px solid #ccc;
            border-radius: 5px;
            width: 200px;
            font-size: 15px;
            transition: 0.3s;
            text-align: center;
        }

        input:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.4);
        }

        /* 🔘 Botones */
        button {
            padding: 10px 15px;
            border: none;
            background: #007bff;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            font-size: 14px;
        }

        button:hover {
            background: #0056b3;
            transform: scale(1.03);
        }

        button[type="button"] {
            background: #6c757d;
        }

        button[type="button"]:hover {
            background: #545b62;
        }

        /* 📋 Tabla */
        table {
            width: 90%;
            max-width: 1000px;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        th,
        td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
            font-size: 14px;
        }

        /* 🎨 Encabezados de la tabla */
        th {
            background: #007bff;
            color: white;
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 1px;
        }

        /* 🖌️ Filas de la tabla */
        tr:nth-child(even) {
            background: #f8f9fa;
        }

        tr:hover {
            background: #e2e6ea;
            transition: 0.3s;
        }

        /* 📱 Responsividad */
        @media (max-width: 768px) {
            table {
                width: 100%;
            }

            .filtros {
                flex-direction: column;
                align-items: stretch;
            }

            input {
                width: 100%;
            }

            .exportar {
                text-align: right;
                margin: 10px;
            }

            .exportar button {
                padding: 10px 15px;
                background: #28a745;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                transition: 0.3s ease-in-out;
                font-size: 14px;
            }

            .exportar button:hover {
                background: #218838;
                transform: scale(1.05);
            }

        }
    </style>
</head>

<body>

    <h2>Respuestas de Usuarios</h2>

    <div class="filtros">
        <form method="GET">
            <input type="text" name="usuario" placeholder="Buscar por usuario" value="<?= $filtro_usuario ?>">
            <input type="text" name="campana" placeholder="Buscar por campaña" value="<?= $filtro_campana ?>">
            <button type="submit">Filtrar</button>
            <a href="admin.php"><button type="button">Restablecer</button></a>
        </form>
        <div class="exportar">
            <a href="exportar.php">
                <button>📥 Descargar Excel</button>
            </a>
        </div>

    </div>

    <table>
        <tr>
            <th>ID Usuario</th>
            <th>Nombre</th>
            <th>Campaña</th>
            <th>Pregunta</th>
            <th>Respuesta</th>
            <th>Fecha y Hora</th>
        </tr>

        <?php foreach ($usuarios as $id => $data): ?>
            <tr>
                <td rowspan="<?= count($data["respuestas"]) ?>"><?= $id ?></td>
                <td rowspan="<?= count($data["respuestas"]) ?>"><?= $data["nombre"] ?></td>
                <td rowspan="<?= count($data["respuestas"]) ?>"><?= $data["campana"] ?></td>

                <?php $first = true;
                foreach ($data["respuestas"] as $respuesta): ?>
                    <?php if (!$first) echo "<tr>"; ?>
                    <td><?= $respuesta["pregunta"] ?></td>
                    <td><?= $respuesta["respuesta"] ?></td>
                    <td><?= $respuesta["fecha"] ?></td>
            </tr>
        <?php $first = false;
                endforeach; ?>
    <?php endforeach; ?>
    </table>

</body>

</html>

<?php
$conn->close();
?>