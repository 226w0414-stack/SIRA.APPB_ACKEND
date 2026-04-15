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

// Datos de conexión (Internal)
$host = "dpg-d7f7q13bc2fs73djih20-a";
$user = "sira_db_user";
$pass = "sMvVi1QQZpXKu0BlZszgMk0MXnUdg4y0";
$db   = "sira_db";

$conn = pg_connect("host=$host dbname=$db user=$user password=$pass");

if (!$conn) {
    die(json_encode(["error" => "No se pudo conectar a PostgreSQL en Render"]));
}

// Leer los datos de React
$data = json_decode(file_get_contents("php://input"), true);

if ($data) {
    $sql = "INSERT INTO reportes (id, descripcion, foto, latitud, longitud, direccion_manual, nombre_informante, telefono_informante) 
            VALUES ($1, $2, $3, $4, $5, $6, $7, $8)";

    // Usamos ?? para dar valores por defecto si los campos vienen vacíos o nulos
    $params = array(
        $data['id'] ?? uniqid(), 
        $data['description'] ?? '', 
        $data['image'] ?? null, 
        $data['location']['latitude'] ?? 0, 
        $data['location']['longitude'] ?? 0,
        $data['location']['manualAddress'] ?? 'No proporcionada',
        $data['informantName'] ?? 'Anónimo',
        $data['informantPhone'] ?? ''
    );

    $result = pg_query_params($conn, $sql, $params);

    if ($result) {
        echo json_encode(["message" => "Reporte guardado exitosamente"]);
    } else {
        echo json_encode(["error" => "Error al guardar: " . pg_last_error($conn)]);
    }
} else {
    echo json_encode(["error" => "No se recibieron datos"]);
}

pg_close($conn);
?>
