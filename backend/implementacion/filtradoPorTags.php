<?php
ob_start(); 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once '../persistencia/conexion.php';
require_once '../persistencia/DAO/PostDAO.php';
require_once '../persistencia/DAO/post_tagDAO.php';
require_once '../persistencia/DAO/FondoDAO.php';
require_once '../dominio/Post.php';
require_once '../dominio/Fondo.php';
$conn = Conexion::getConexion();
if ($conn->connect_error) {
    ob_end_clean(); // Limpiar cualquier salida previa
    die(json_encode(["error" => "Conexión fallida: " . $conn->connect_error]));
}

$tagBuscar = isset($_GET['tag']) ? trim($_GET['tag']) : null;

if (!$tagBuscar) {
    ob_end_clean(); // Limpiar cualquier salida previa
    echo json_encode(["error" => "No se especificó ningún tag para buscar."]);
    exit;
}

try {
    $postIds = post_tagDAO::obtenerPostsPorTag($tagBuscar);
    if (empty($postIds)) {
        ob_end_clean(); // Limpiar cualquier salida previa
        echo json_encode(["error" => "No se encontraron posts para el tag especificado."]);
        exit;
    }
    $postsFiltrados = [];

    foreach ($postIds as $postId) {
        $post = PostDAO::obtenerPorId($postId);
        $fondoId = $post->getFondoId();
        $fondoRuta = $fondoId !== null ? FondoDAO::obtenerRutaPorId($fondoId) : null;

        $postsFiltrados[] = [
            'id'         => $post->getId(),
            'autor'      => $post->getAutor()->getNickname(),
            'contenido'  => $post->getContenido(),
            'likes'      => $post->getLikes(),
            'dislikes'   => $post->getDislikes(),
            'fechaPost'  => $post->getFechaPost()->format('Y-m-d H:i:s'),
            'privado'    => $post->isPrivado(),
            'fondoId'    => $fondoId,
            'fondoRuta'  => $fondoRuta,
            'tags'       => post_tagDAO::obtenerTagsPorPost($post->getId()),
        ];
    }

    ob_end_clean(); // Limpiar cualquier salida previa
    echo json_encode($postsFiltrados, JSON_PRETTY_PRINT);
} catch (Exception $e) {
    ob_end_clean(); // Limpiar cualquier salida previa
    echo json_encode(["error" => $e->getMessage()]);
}
?>