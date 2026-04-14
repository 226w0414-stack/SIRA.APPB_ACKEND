<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
// header("Access-Control-Allow-Credentials: true");
// header("Content-Type: application/json");
// header("Vary: Origin");

// Manejar la petición OPTIONS (Preflight) que hace el navegador
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

// Consultamos los reportes ordenados del más reciente al más antiguo
$sql = "SELECT * FROM reportes ORDER BY fecha DESC";
$result = $conn->query($sql);

$reportes = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Reconstruimos la estructura que espera React
        $reportes[] = [
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
            "timestamp" => strtotime($row['fecha']) * 1000,
            "status" => "sent"
        ];
    }
}

echo json_encode($reportes);
$conn->close();
?>