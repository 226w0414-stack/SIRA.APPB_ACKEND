<?php
include 'conexion.php';

$query = "CREATE TABLE IF NOT EXISTS reportes (
    id VARCHAR(50) PRIMARY KEY,
    description TEXT,
    image TEXT,
    latitude NUMERIC,
    longitude NUMERIC,
    informant_name VARCHAR(100),
    informant_phone VARCHAR(20),
    timestamp BIGINT,
    estado VARCHAR(20) DEFAULT 'activo',
    fecha_finalizado TIMESTAMP
);";

$result = pg_query($dbconn, $query);

if ($result) {
    echo "¡Tabla 'reportes' creada o actualizada exitosamente en Render!";
} else {
    echo "Error al crear la tabla: " . pg_last_error($dbconn);
}
?>