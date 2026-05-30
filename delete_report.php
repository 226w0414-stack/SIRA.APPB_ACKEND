<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

include 'conexion.php';

// Incluye tu lógica de conexión aquí (la que ya usamos en save_report.php)
$host = "dpg-d8d6n1gjs32c73f8j0sg-a";
$user = "sira_db_v2_vsd9_user";
$pass = "68Li4gEIewWXAofNNXLyyzjMnQclR5Nx";
$db   = "sira_db_v2_vsd9";

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
