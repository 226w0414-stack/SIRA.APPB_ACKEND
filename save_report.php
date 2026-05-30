<?php
include 'conexion.php'; // Usa tu archivo de conexión configurado

$sql = "CREATE TABLE IF NOT EXISTS reportes (
    id VARCHAR(255) PRIMARY KEY,
    descripcion TEXT,
    foto TEXT,
    latitud NUMERIC,
    longitud NUMERIC,
    direccion_manual TEXT,
    nombre_informante TEXT,
    telefono_informante TEXT,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado VARCHAR(20) DEFAULT 'activo',
    fecha_finalizado TIMESTAMP
);";

$result = pg_query($conn, $sql);

if ($result) {
    echo "Tabla 'reportes' creada correctamente con las variables del sistema.";
} else {
    echo "Error al crear la tabla: " . pg_last_error($conn);
}
?>