<?php   
ob_start(); 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *'); // Permite todas las fuentes (solo para pruebas)
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Content-Type: application/json; charset=utf-8');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
require_once '../persistencia/conexion.php';
require_once '../persistencia/DAO/UsuarioDAO.php';
require_once '../persistencia/DAO/PostDAO.php';
require_once '../dominio/Usuario.php';
require_once '../dominio/Post.php';
require_once '../dominio/Recordatorio.php';
require_once '../persistencia/DAO/post_tagDAO.php';
require_once '../persistencia/DAO/RecordatorioDAO.php';

$conn = Conexion::getConexion();
if ($conn->connect_error) {
    http_response_code(500);
    ob_end_clean();
    echo json_encode(['error' => 'Error de conexión']);
    exit;
}
// Obtener el ID del post a eliminar
$postId = isset($_GET['id']) ? intval($_GET['id']) : 0; 

if ($postId <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de post inválido']);
    exit;
}   
// Verificar si el post existe  
$post = PostDAO::obtenerPorId($postId);
if (!$post) {
    http_response_code(404);
    echo json_encode(['error' => 'Post no encontrado']);    
    exit;
}

//Elimino el recordatorio asociado a ese post
 RecordatorioDAO::deleteByPostId($postId);

// Eliminar las relaciones post-tag
post_tagDAO::eliminarTagsPorPost($postId);

// Eliminar el post
if (PostDAO::eliminar($postId)) {
    ob_end_clean();
    http_response_code(200);
    echo json_encode(['message' => 'Post eliminado correctamente']);
} else {
    ob_end_clean();
    http_response_code(500);
    echo json_encode(['error' => 'Error al eliminar el post']);
}

