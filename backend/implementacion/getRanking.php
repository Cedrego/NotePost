<?php
ob_start(); // Iniciar el buffer de salida
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    ob_end_clean(); // Limpiar cualquier salida previa
    exit;
}

require_once '../persistencia/conexion.php';
require_once '../persistencia/DAO/PostDAO.php';
require_once '../persistencia/DAO/post_tagDAO.php';
require_once '../persistencia/DAO/FondoDAO.php';
require_once '../dominio/Post.php';
require_once '../dominio/Fondo.php';

// Obtener conexión a la base de datos
$conn = Conexion::getConexion();
if ($conn->connect_error) {
    ob_end_clean(); // Limpiar cualquier salida previa
    die(json_encode(["error" => "Conexión fallida: " . $conn->connect_error]));
}

try {
    $posts = PostDAO::getRanking(); // Obtener los 10 posts con más likes
    $data = [];

    foreach ($posts as $post) {

        $data[] = [
            'id'         => $post->getId(),
            'autor'      => $post->getAutor()->getNickname(),
            'contenido'  => $post->getContenido(),
            'likes'      => $post->getLikes(),
            'dislikes'   => $post->getDislikes(),
            'fechaPost'  => $post->getFechaPost()->format('Y-m-d H:i:s'),
            'privado'    => $post->isPrivado(),
            'fondoId'    => $post->getFondoId(),
            'fondoRuta'  => FondoDAO::obtenerRutaPorId($post->getFondoId()),
            'tags'       => post_tagDAO::obtenerTagsPorPost($post->getId()),
        ];
    }

    ob_end_clean(); // Limpiar cualquier salida previa
    echo json_encode($data, JSON_PRETTY_PRINT);
} catch (Exception $e) {
    ob_end_clean(); // Limpiar cualquier salida previa
    echo json_encode(["error" => $e->getMessage()]);
}
?>