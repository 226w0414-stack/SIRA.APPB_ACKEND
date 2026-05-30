<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Manejo del Pre-vuelo (CORS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require 'conexion.php';

$data = json_decode(file_get_contents("php://input"), true);
$accion = $data['accion'] ?? '';
$curp = $data['curp'] ?? '';

// Convertir CURP a mayúsculas por estándar
$curp = strtoupper($curp);

if (!$curp) {
    http_response_code(400);
    echo json_encode(["error" => "La CURP es obligatoria"]);
    exit();
}

// ----------------------------------------------------
// ACCIÓN 1: Verificar si la CURP ya existe
// ----------------------------------------------------
if ($accion === 'verificar') {
    $query = "SELECT curp, primer_nombre, primer_apellido, rol FROM usuarios WHERE curp = $1";
    $result = pg_query_params($conn, $query, array($curp));

    if (pg_num_rows($result) > 0) {
        $user = pg_fetch_assoc($result);
        echo json_encode(["existe" => true, "usuario" => $user]);
    } else {
        echo json_encode(["existe" => false]);
    }
} 
// ----------------------------------------------------
// ACCIÓN 2: Registrar o Iniciar Sesión (Validar Teléfono)
// ----------------------------------------------------
elseif ($accion === 'registrar_o_entrar') {
    $telefono = $data['telefono'] ?? '';

    // Buscamos al usuario en la BD
    $query = "SELECT * FROM usuarios WHERE curp = $1";
    $result = pg_query_params($conn, $query, array($curp));

    if (pg_num_rows($result) > 0) {
        // EL USUARIO EXISTE -> Es un Login, verificamos el teléfono (contraseña)
        $user = pg_fetch_assoc($result);
        if ($user['telefono'] === $telefono) {
            echo json_encode(["success" => true, "mensaje" => "Bienvenido de nuevo", "usuario" => $user]);
        } else {
            http_response_code(401);
            echo json_encode(["error" => "Número de teléfono incorrecto. Esa no es tu contraseña."]);
        }
    } else {
        // EL USUARIO NO EXISTE -> Lo registramos
        $p_nombre = $data['primer_nombre'] ?? '';
        $s_nombre = $data['segundo_nombre'] ?? '';
        $p_apellido = $data['primer_apellido'] ?? '';
        $s_apellido = $data['segundo_apellido'] ?? '';

        $insert = "INSERT INTO usuarios (curp, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, telefono, rol) VALUES ($1, $2, $3, $4, $5, $6, 'ciudadano') RETURNING curp, primer_nombre, primer_apellido, rol";
        $res_insert = pg_query_params($conn, $insert, array($curp, $p_nombre, $s_nombre, $p_apellido, $s_apellido, $telefono));

        if ($res_insert) {
            $nuevo_usuario = pg_fetch_assoc($res_insert);
            echo json_encode(["success" => true, "mensaje" => "Usuario registrado con éxito", "usuario" => $nuevo_usuario]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error al registrar en la base de datos."]);
        }
    }
} else {
    http_response_code(400);
    echo json_encode(["error" => "Acción no válida"]);
}

pg_close($conn);
?>