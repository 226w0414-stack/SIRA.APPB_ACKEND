<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Incluye tu lógica de conexión aquí (la que ya usamos en save_report.php)
$host = "dpg-d7f7q13bc2fs73djih20-a";
$user = "sira_db_user";
$pass = "sMvVi1QQZpXKu0BlZszgMk0MXnUdg4y0";
$db   = "sira_db";

$conn = pg_connect("host=$host dbname=$db user=$user password=$pass");

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'] ?? null;

if ($id) {
    $result = pg_query_params($conn, "DELETE FROM reportes WHERE id = $1", array($id));
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["error" => "ID no recibido"]);
}
pg_close($conn);
?>