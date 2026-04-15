<?php
include 'conexion.php'; // Asegúrate de tener un archivo con los datos de conexión

$sql = "ALTER TABLE reportes ADD COLUMN estado VARCHAR(20) DEFAULT 'activo';
        ALTER TABLE reportes ADD COLUMN fecha_finalizado TIMESTAMP;";

$result = pg_query($conn, $sql);

if ($result) {
    echo "¡Base de datos actualizada con éxito!";
} else {
    echo "Error al actualizar: " . pg_last_error($conn);
}
?>