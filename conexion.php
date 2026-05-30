<?php
// Configuración de la base de datos (Render Internal)
$host = "dpg-d8d6n1gjs32c73f8j0sg-a";
$user = "sira_db_v2_vsd9_user";
$pass = "68Li4gEIewWXAofNNXLyyzjMnQclR5Nx";
$db   = "sira_db_v2_vsd9";

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
