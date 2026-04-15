<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

$host = "dpg-d7f7q13bc2fs73djih20-a";
$user = "sira_db_user";
$pass = "sMvVi1QQZpXKu0BlZszgMk0MXnUdg4y0";
$db   = "sira_db";

// Conexión a PostgreSQL
$conn = pg_connect("host=$host dbname=$db user=$user password=$pass");

if (!$conn) {
    die(json_encode(["error" => "No se pudo conectar a PostgreSQL en Render"]));
}

// Consultamos (Agregué la columna 'fecha' para que no falle el ORDER BY)
$sql = "SELECT id, descripcion, foto, latitud, longitud, direccion_manual, nombre_informante, telefono_informante, fecha FROM reportes ORDER BY fecha DESC";
$result = pg_query($conn, $sql);

$reportes_finales = [];

if ($result) {
    while ($row = pg_fetch_assoc($result)) {
        // Reconstruimos la estructura exacta para SIRA.APP
        $reportes_finales[] = [
            "id" => $row['id'],
            "description" => $row['descripcion'],
            "image" => $row['foto'],
            "location" => [
                "latitude" => (float)$row['latitud'],
                "longitude" => (float)$row['longitud'],
                "manualAddress" => $row['direccion_manual']
            ],
            "informantName" => $row['nombre_informante'],
            "informantPhone" => $row['telefono_informante'],
            // Convertimos la fecha de Postgres a milisegundos para JS
            "timestamp" => strtotime($row['fecha']) * 1000,
            "status" => "sent"
        ];
    }
}

echo json_encode($reportes_finales);

pg_close($conn);
?>