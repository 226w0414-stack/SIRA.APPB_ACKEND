<?php
// Configuración de la base de datos (Render Internal)
$host = "dpg-d7f7q13bc2fs73djih20-a";
$user = "sira_db_user";
$pass = "sMvVi1QQZpXKu0BlZszgMk0MXnUdg4y0";
$db   = "sira_db";

// Cadena de conexión para PostgreSQL
$conn_string = "host=$host dbname=$db user=$user password=$pass";

// Intentar la conexión
$conn = pg_connect($conn_string);

// Si la conexión falla, mandar un error en formato JSON para que React sepa qué pasó
if (!$conn) {
    die(json_encode(["error" => "Error de conexión a la base de datos"]));
}

// Nota: No cerramos la conexión aquí porque este archivo será "incluido" en otros.
?>