<?php
header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
// header("Vary: Origin");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

$host = "sql201.infinityfree.com";
$user = "if0_41460136";
$pass = "lb6odmfhY7Hc1";
$db   = "if0_41460136_sira_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die(json_encode(["error" => "Conexión fallida"]));
}

// Leer los datos que vienen de React
$data = json_decode(file_get_contents("php://input"), true);

if ($data) {
$stmt = $conn->prepare("INSERT INTO reportes (id, descripcion, foto, latitud, longitud, direccion_manual, nombre_informante, telefono_informante) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

// Mapeamos los campos del JSON de React a la base de datos
    $stmt->bind_param("ssssssss", 
        $data['id'], 
        $data['description'], 
        $data['image'], 
        $data['location']['latitude'], 
        $data['location']['longitude'],
        $data['location']['manualAddress'],
        $data['informantName'],
        $data['informantPhone']
    );

    if ($stmt->execute()) {
        echo json_encode(["message" => "Reporte guardado exitosamente"]);
    } else {
        echo json_encode(["error" => "Error al guardar"]);
    }
    $stmt->close();
}

$conn->close();
?>