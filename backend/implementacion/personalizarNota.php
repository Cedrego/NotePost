<?php
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
require_once '../dominio/Usuario.php';
require_once '../dominio/Post.php';
require_once '../dominio/Recordatorio.php';
require_once '../persistencia/DAO/RecordatorioDAO.php';
require_once '../persistencia/DAO/UsuarioDAO.php';
require_once '../persistencia/DAO/PostDAO.php';
require_once '../persistencia/DAO/LikeDAO.php';

$conn = Conexion::getConexion();
if ($conn->connect_error) {
    http_response_code(500);
    ob_end_clean();
    echo json_encode(['error' => 'Error de conexión']);
    exit;
}
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    http_response_code(400);
    ob_end_clean();
    echo json_encode(['error' => 'Datos inválidos']);
    exit;
}
$id = $data['id'];
$contenido = $data['contenido'];
$fecha = $data['fecha'];
$privado = $data['privado'];

$post = PostDAO::obtenerPorId($id);
//Esto por si el post no existe 
if (!$post) {
    http_response_code(404);
    echo json_encode(['error' => 'Post no encontrado']);
    exit;
}
//Setear los datos del post
$post->setContenido($contenido);
$post->setFechaPost($fecha);
$post->setPrivado($privado);
//Seteo también los likes y dislikes a 0, ya que al editar un post no se deberían conservar los likes y dislikes previos
$post->setLikes(0);
$post->setDislikes(0);
LikeDAO::eliminarPorIdPost($id); // Eliminar likes y dislikes previos
RecordatorioDAO::actualizarFechaRecordatorioPorPostId($id, $fecha); // Actualizar recordatorio si existe
// Actualizar el post en la base de datos
PostDAO::actualizar($post);

echo json_encode(['mensaje' => 'Post actualizado con éxito']);