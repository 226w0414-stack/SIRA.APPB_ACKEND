<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

include 'conexion.php';

// Incluye tu lógica de conexión aquí (la que ya usamos en save_report.php)
$host = "dpg-d8bj0mmq1p3s73dq0nc0-a";
$user = "sira_db_v2_user";
$pass = "ChC8u6Qoml6SKACjBJ8KDRWY95SJU54n";
$db   = "sira_db_v2";

$conn = pg_connect("host=$host dbname=$db user=$user password=$pass");

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'] ?? null;

if ($id) {
    $sql = "UPDATE reportes SET estado = 'finalizado', fecha_finalizado = NOW() WHERE id = $1";
    $result = pg_query_params($conn, $sql, array($id));
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["error" => "ID no recibido"]);
}
pg_close($conn);
?>
