<?php
header("Access-Control-Allow-Origin: *"); // Permite peticiones desde cualquier origen
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$data = [
    [ "ide" => "NetBeans", "image" => "http://localhost/backend/imagenes/foto1.png" ],
    [ "ide" => "IntelliJ IDEA", "image" => "http://localhost/backend/imagenes/foto2.png" ]
];
echo json_encode($data);
?>

