<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

include 'conexion.php';

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'] ?? null;

if ($id) {
    // Cambiamos el estado a 'en_camino'
    $sql = "UPDATE reportes SET estado = 'en_camino' WHERE id = $1";
    $result = pg_query_params($conn, $sql, array($id));
    
    if ($result) {
        echo json_encode(["success" => true, "message" => "Unidad despachada"]);
    } else {
        echo json_encode(["success" => false, "error" => pg_last_error($conn)]);
    }
} else {
    echo json_encode(["success" => false, "error" => "ID no recibido"]);
}
pg_close($conn);
?>