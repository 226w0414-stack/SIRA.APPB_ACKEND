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

$host = "dpg-d8bj0mmq1p3s73dq0nc0-a";
$user = "sira_db_v2_user";
$pass = "ChC8u6Qoml6SKACjBJ8KDRWY95SJU54n";
$db   = "sira_db_v2";

// Conexión a PostgreSQL
$conn = pg_connect("host=$host dbname=$db user=$user password=$pass");

if (!$conn) {
    die(json_encode(["error" => "No se pudo conectar a PostgreSQL en Render"]));
}

// Consultamos (Agregué la columna 'fecha' para que no falle el ORDER BY)
$sql = "SELECT * FROM reportes WHERE estado = 'activo' ORDER BY fecha DESC";
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
