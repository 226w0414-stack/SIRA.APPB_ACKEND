<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require 'conexion.php';

$data = json_decode(file_get_contents("php://input"), true);

$curp = $data['curp'] ?? '';
$p_nombre = $data['primer_nombre'] ?? '';
$s_nombre = $data['segundo_nombre'] ?? '';
$p_apellido = $data['primer_apellido'] ?? '';
$s_apellido = $data['segundo_apellido'] ?? '';
$telefono = $data['telefono'] ?? '';

if (!$curp || !$p_nombre || !$p_apellido || !$telefono) {
    http_response_code(400);
    echo json_encode(["error" => "Los campos obligatorios están incompletos."]);
    exit();
}

// Actualizar los datos en PostgreSQL
$query = "UPDATE usuarios 
          SET primer_nombre = $1, segundo_nombre = $2, primer_apellido = $3, segundo_apellido = $4, telefono = $5 
          WHERE curp = $6 
          RETURNING curp, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, telefono, rol";

$result = pg_query_params($conn, $query, array($p_nombre, $s_nombre, $p_apellido, $s_apellido, $telefono, $curp));

if ($result && pg_num_rows($result) > 0) {
    $usuario_actualizado = pg_fetch_assoc($result);
    echo json_encode([
        "success" => true,
        "mensaje" => "Perfil actualizado exitosamente.",
        "usuario" => $usuario_actualizado
    ]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "No se pudo actualizar el perfil en el servidor o el usuario no existe."]);
}

pg_close($conn);
?>