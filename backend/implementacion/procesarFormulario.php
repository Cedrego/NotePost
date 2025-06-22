<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
// Recibir datos JSON del cuerpo de la petición
$data = json_decode(file_get_contents("php://input"), true);
// Preparar respuesta
$response = [
    "mensaje" => "Datos recibidos correctamente",
    "datos" => $data
];

// Devolver como JSON
echo json_encode($response);

?>