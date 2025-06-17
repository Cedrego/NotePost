para mandar datos del backend al frontend solo hay que hacer esto

<?php
include 'dephpAAngular.php';

// Asignar datos a la respuesta
$response['mensaje'] = "Datos enviados correctamente";
$response['datos'] = [
    ["id" => 1, "nombre" => "Producto A", "precio" => 100],
    ["id" => 2, "nombre" => "Producto B", "precio" => 200],
];

// Enviar respuesta
echo json_encode($response);
?>
