<?php
require 'conexion.php';

$sql = "CREATE TABLE IF NOT EXISTS usuarios (
    curp VARCHAR(18) PRIMARY KEY,
    primer_nombre VARCHAR(50) NOT NULL,
    segundo_nombre VARCHAR(50),
    primer_apellido VARCHAR(50) NOT NULL,
    segundo_apellido VARCHAR(50),
    telefono VARCHAR(10) NOT NULL,
    rol VARCHAR(20) DEFAULT 'ciudadano',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);";

$result = pg_query($conn, $sql);

if ($result) {
    echo "¡Tabla 'usuarios' creada exitosamente en Render!";
} else {
    echo "Error al crear la tabla: " . pg_last_error($conn);
}

pg_close($conn);
?>