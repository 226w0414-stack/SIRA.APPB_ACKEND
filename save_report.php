<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

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
    // En Postgres usamos $1, $2, etc., para los parámetros preparados
    $sql = "INSERT INTO reportes (id, descripcion, foto, latitud, longitud, direccion_manual, nombre_informante, telefono_informante) 
            VALUES ($1, $2, $3, $4, $5, $6, $7, $8)";

    // Preparamos los valores en un array ordenado
    $params = array(
        $data['id'], 
        $data['description'], 
        $data['image'], 
        $data['location']['latitude'], 
        $data['location']['longitude'],
        $data['location']['manualAddress'],
        $data['informantName'],
        $data['informantPhone']
    );

    // Ejecutamos la consulta
    $result = pg_query_params($conn, $sql, $params);

    if ($result) {
        echo json_encode(["message" => "Reporte guardado exitosamente"]);
    } else {
        // Obtenemos el último error de Postgres para saber qué pasó
        echo json_encode(["error" => "Error al guardar: " . pg_last_error($conn)]);
    }
} else {
    echo json_encode(["error" => "No se recibieron datos"]);
}

// Cerramos la conexión
pg_close($conn);
?>