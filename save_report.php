<?php
// 1. Cabeceras CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

include 'conexion.php';

// 2. Leer los datos de React
$data = json_decode(file_get_contents("php://input"), true);

if ($data) {
    // La estructura de la tabla coincide con las columnas que mapeas en get_reports
    $sql = "INSERT INTO reportes (
                id, descripcion, foto, latitud, longitud, 
                direccion_manual, nombre_informante, telefono_informante, fecha
            ) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, TO_TIMESTAMP($9/1000.0))";

    // 3. Mapeo de datos (Coincidente con la estructura de get_reports.php)
    $params = array(
        $data['id'] ?? uniqid(), 
        $data['description'] ?? '', 
        $data['image'] ?? null, 
        $data['location']['latitude'] ?? 0, 
        $data['location']['longitude'] ?? 0,
        $data['location']['manualAddress'] ?? 'No proporcionada',
        $data['informantName'] ?? 'Anónimo',
        $data['informantPhone'] ?? '',
        $data['timestamp'] ?? (time() * 1000) // Aseguramos que la fecha coincida
    );

    $result = pg_query_params($conn, $sql, $params);

    if ($result) {
        echo json_encode(["message" => "Reporte guardado exitosamente"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Error al guardar: " . pg_last_error($conn)]);
    }
} else {
    http_response_code(400);
    echo json_encode(["error" => "No se recibieron datos"]);
}

pg_close($conn);
?>