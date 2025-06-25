<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *'); // Permite todas las fuentes (solo para pruebas)
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Content-Type: application/json; charset=utf-8');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
require_once __DIR__ . '../persistencia/DAO/LikeDAO.php';
require_once __DIR__ . '../persistencia/DAO/PostDAO.php';

$idPost = isset($_POST['idPost']) ? intval($_POST['idPost']) : 0;

//obtener el nickname desde el POST (enviado por Angular)
$nickUsu = isset($_POST['usuario']) ? $_POST['usuario'] : null;
if (!$nickUsu) {
    echo "Error: usuario no especificado.";
    exit;
}
if ($idPost <= 0) {
    die("ID de post inválido.");
}
$conn = Conexion::getConexion();
$likeDAO = new LikeDAO();
$postDAO = new PostDAO();

$post = $postDAO->obtenerPorId($idPost);
if ($post === null) {
    die("Post no encontrado.");
}

$accionExistente = $likeDAO->obtenerAccion($idPost, $nickUsu);

if ($accionExistente === 'like') {
    // Ya le dio like, no hacer nada
    exit;
} elseif ($accionExistente === 'dislike') {
    // Cambio de dislike a like: hacer dos downvotes y luego upvote
    $post->downvote();
    $post->downvote();
    $post->upvote();
    $postDAO->actualizar($post);
    $likeDAO->actualizarVoto($idPost, $nickUsu, 'like');
} else {
    // No había voto previo, agrego like
    $post->upvote();
    $postDAO->actualizar($post);
    $likeDAO->insertarVoto($idPost, $nickUsu, 'like');
}

echo "Like registrado correctamente.";
