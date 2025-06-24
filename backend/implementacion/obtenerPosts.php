<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
require_once '../persistencia/conexion.php';
require_once '../persistencia/DAO/PostDAO.php';
require_once '../persistencia/DAO/FondoDAO.php';
require_once '../persistencia/DAO/post_tagDAO.php';
header('Content-Type: application/json');

//obtener conexión a la base de datos
$conn = Conexion::getConexion();
if ($conn->connect_error) {
    die(json_encode(["error" => "Conexión fallida: " . $conn->connect_error]));
}

//obtener todos los posts
try {
    $posts = PostDAO::obtenerTodos();
    
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
            'fondoRuta'    => FondoDAO::obtenerRutaPorId($post->getFondoId()),
            'tags' => post_tagDAO::obtenerTagsPorPost($post->getId()),
        ];
    }

    echo json_encode($data, JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>