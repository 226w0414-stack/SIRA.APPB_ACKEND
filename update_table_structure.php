<?php
include 'conexion.php';

// Comandos para agregar las nuevas columnas
$sql = "ALTER TABLE reportes ADD COLUMN IF NOT EXISTS estado VARCHAR(20) DEFAULT 'activo';
        ALTER TABLE reportes ADD COLUMN IF NOT EXISTS fecha_finalizado TIMESTAMP;";

$result = pg_query($conn, $sql);

if ($result) {
    echo "<h3>¡Estructura actualizada!</h3>";
    echo "<p>Las columnas 'estado' y 'fecha_finalizado' han sido agregadas.</p>";
} else {
    echo "<h3>Error al actualizar</h3>";
    echo pg_last_error($conn);
}

pg_close($conn);
?>