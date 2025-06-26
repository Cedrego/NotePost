<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=utf-8');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once '../persistencia/conexion.php';
require_once '../persistencia/DAO/LikeDAO.php';
require_once '../persistencia/DAO/PostDAO.php';

// Obtener los datos del POST (Angular envía JSON)
$input = json_decode(file_get_contents('php://input'), true);
$idPost = isset($input['postId']) ? ($input['postId']) : 0;

//obtener el nickname desde el POST (enviado por Angular)
$nickUsu = isset($input['usuario']) ? $input['usuario'] : null;
if (!$nickUsu) {
    echo json_encode(['error' => ' usuario no especificado.']);
    exit;
}
if (!$idPost || $idPost <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'IdPost no valido.']);
    exit;
}
$conn = Conexion::getConexion();
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión']);
    exit;
}
$likeDAO = new LikeDAO();
$postDAO = new PostDAO();

$post = $postDAO->obtenerPorId($idPost);
if ($post === null) {
    echo json_encode(['error' => 'Post no encontrado.']);
    exit;
}

$accionExistente = $likeDAO->obtenerAccion($idPost, $nickUsu);

if ($accionExistente === null) {
    // No había voto previo, agrego dislike
    $post->downvote();
    $postDAO->actualizar($post);
    $likeDAO->insertarVoto($idPost, $nickUsu, 'dislike');
    exit;
} elseif ($accionExistente === 'like') {
    // Cambio de like a dislike: hacer dos downvotes
    $post->Quitarupvote();
    $post->downvote();
    $postDAO->actualizar($post);
    $likeDAO->actualizarVoto($idPost, $nickUsu, 'dislike');
    exit;
}
// Ya le dio dislike, no hacer nada
echo json_encode(["mensaje" => "DisLike registrado correctamente."]);
