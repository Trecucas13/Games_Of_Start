<?php
// Incluir archivo de conexión
include("conexion.php");

// Consulta para obtener todos los resultados ordenados por movimientos y tiempo
$sql = "SELECT * FROM resultados ORDER BY movimientos ASC, tiempo ASC";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="andesBpo.png">
    <title>Resultados del Juego</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color:rgb(24, 103, 26);
            color: white;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .volver {
            display: inline-block;
            width: 150px;
            margin: 20px 10px;
            padding: 10px;
            background-color:rgba(0, 0, 0, 0.57);
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 4px;
        }
        .volver:hover {
            background-color:rgb(28, 133, 33);
        }
        .no-resultados {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }
        .botones-container {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Tabla de Resultados</h1>
        
        <?php if ($resultado->num_rows > 0): ?>
            <table id="tabla-resultados">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Campaña</th>
                        <th>Movimientos</th>
                        <th>Tiempo (segundos)</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($fila = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($fila["nombre"]); ?></td>
                            <td><?php echo htmlspecialchars($fila["campana"]); ?></td>
                            <td><?php echo $fila["movimientos"]; ?></td>
                            <td><?php echo $fila["tiempo"]; ?></td>
                            <td><?php echo isset($fila["fecha"]) ? htmlspecialchars($fila["fecha"]) : "No disponible"; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-resultados">No hay resultados disponibles todavía.</p>
        <?php endif; ?>
        
        <div class="botones-container">
            <a href="index.html" class="volver">Volver al Juego</a>
            <a href="#" class="volver" id="btn-descargar">Descargar</a>
        </div>
        
    </div>

    <script>
        document.getElementById('btn-descargar').addEventListener('click', function(e) {
            e.preventDefault();
            
            // Verificar si hay resultados para descargar
            const tabla = document.getElementById('tabla-resultados');
            if (!tabla) {
                alert('No hay resultados disponibles para descargar.');
                return;
            }
            
            // Obtener todas las filas de la tabla
            const filas = tabla.querySelectorAll('tr');
            let csvContent = '';
            
            // Recorrer cada fila y crear el contenido CSV
            filas.forEach(function(fila) {
                const celdas = fila.querySelectorAll('th, td');
                const filaDatos = [];
                
                celdas.forEach(function(celda) {
                    // Escapar comillas dobles y agregar comillas alrededor del valor
                    let valor = celda.textContent.replace(/"/g, '""');
                    filaDatos.push('"' + valor + '"');
                });
                
                // Agregar la fila al contenido CSV
                csvContent += filaDatos.join(',') + '\r\n';
            });
            
            // Crear un objeto Blob con el contenido CSV
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            
            // Crear un enlace para descargar el archivo
            const enlace = document.createElement('a');
            const url = URL.createObjectURL(blob);
            
            // Configurar el enlace de descarga
            enlace.setAttribute('href', url);
            enlace.setAttribute('download', 'resultados_juego.csv');
            enlace.style.visibility = 'hidden';
            
            // Agregar el enlace al documento, hacer clic en él y luego eliminarlo
            document.body.appendChild(enlace);
            enlace.click();
            document.body.removeChild(enlace);
        });
    </script>
</body>
</html>

<?php
// Cerrar la conexión
$conn->close();
?>