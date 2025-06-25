<?php
require_once '../persistencia/conexion.php';
require_once '../persistencia/DAO/PostDAO.php';
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
            'fondoId'    => $post->getFondoId()
        ];
    }

    echo json_encode($data, JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>