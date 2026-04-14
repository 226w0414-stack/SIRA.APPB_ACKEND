<?php
$host = "dpg-d7f7q13bc2fs73djih20-a";
$user = "sira_db_user";
$pass = "sMvVi1QQZpXKu0BlZszgMk0MXnUdg4y0";
$db   = "sira_db";

$conn = pg_connect("host=$host dbname=$db user=$user password=$pass");

if (!$conn) {
    die("Error de conexión");
}

$sql = "CREATE TABLE IF NOT EXISTS reportes (
    id VARCHAR(100) PRIMARY KEY,
    descripcion TEXT,
    foto TEXT,
    latitud NUMERIC,
    longitud NUMERIC,
    direccion_manual TEXT,
    nombre_informante VARCHAR(100),
    telefono_informante VARCHAR(20),
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);";

$result = pg_query($conn, $sql);

if ($result) {
    echo "¡Tabla 'reportes' creada exitosamente en Render!";
} else {
    echo "Error al crear la tabla: " . pg_last_error($conn);
}

pg_close($conn);
?>