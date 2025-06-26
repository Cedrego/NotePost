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
require_once '../dominio/Like.php';

// Obtener los datos del POST (Angular envía JSON)
$input = json_decode(file_get_contents('php://input'), true);
$idPost = isset($input['postId']) ? ($input['postId']) : 0;

//obtener el nickname desde el POST (enviado por Angular)
$nickUsu = isset($input['usuario']) ? $input['usuario'] : null;
if (!$nickUsu) {
    echo json_encode(["Error: usuario no especificado."]);
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

 if($accionExistente === null){
    // No había voto previo, agrego like
    $post->upvote();
    $postDAO->actualizar($post);
    $likeDAO->insertarVoto($idPost, $nickUsu, 'like');
    exit;
} elseif ($accionExistente === 'dislike') {//previamente habia dado dislike y ahora le doy like
    //remuevo el dislike y le doy like
    $post->Quitardownvote();
    $post->upvote();
    $postDAO->actualizar($post);
    $likeDAO->actualizarVoto($idPost, $nickUsu, 'like');    
    exit;   
 }
// Si ya le dio like, no hacer nada
echo json_encode(["mensaje" => "Like registrado correctamente."]);

