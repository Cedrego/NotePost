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
require_once '../persistencia/conexion.php';
require_once '../persistencia/DAO/UsuarioDAO.php';
require_once '../persistencia/DAO/PostDAO.php';
require_once '../persistencia/DAO/AvatarDAO.php';
require_once '../dominio/Usuario.php';
require_once '../dominio/Post.php';
require_once '../dominio/Avatar.php';
require_once '../dominio/Recordatorio.php';
require_once '../persistencia/DAO/FondoDAO.php'; 
require_once '../persistencia/DAO/RecordatorioDAO.php'; 
require_once '../persistencia/DAO/post_tagDAO.php';
$conn = Conexion::getConexion();
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión']);
    exit;
}
// Obtener el nick desde GET o POST
$nick = isset($_GET['usuario']) ? $_GET['usuario'] : null;
if (!$nick) {
    http_response_code(400);
    echo json_encode(['error' => 'Usuario no especificado']);
    exit;
}
// Determinar si es el propietario del perfil
$esPropietario = isset($_GET['esPropietario']) && $_GET['esPropietario'] == '1';

$usuario = UsuarioDAO::obtenerPorNickname($nick);
if (!$usuario) {    
    http_response_code(404);
    echo json_encode(['error' => 'Usuario no encontrado']);
    exit;
}
// Obtener el avatar del usuario
$avatar = UsuarioDAO::getAvatar($nick);
$avatarRuta = null;
if ($avatar) {
    $avatarObj = AvatarDAO::obtenerPorId($avatar->getId());
    if ($avatarObj) {
        $avatarRuta = $avatarObj->getRutaImagen();
    }
}
// Obtener posts según si es propietario o no
if ($esPropietario) {
    // Mostrar todos los posts (incluidos privados)
    $posts = PostDAO::obtenerPostsPorUsuario($nick);
} else {
    // Solo mostrar posts públicos
    $posts = PostDAO::obtenerPostsPublicosPorUsuario($nick);
}
// Convierte los objetos Post a arrays
$data = [];

    foreach ($posts as $post) {
        $recordatorios = RecordatorioDAO::getRecordatoriosPorPostId($post->getId());
        $fechaRecordatorio = count($recordatorios) > 0 ? $recordatorios[0]->getFechaRecordatorio()->format('Y-m-d H:i:s') : null;

        $data[] = [
            'id'         => $post->getId(),
            'autor'      => $post->getAutor()->getNickname(),
            'contenido'  => $post->getContenido(),
            'likes'      => $post->getLikes(),
            'dislikes'   => $post->getDislikes(),
            'fechaPost'  => $post->getFechaPost()->format('Y-m-d H:i:s'),
            'privado'    => $post->isPrivado(),
            'fondoRuta'  => FondoDAO::obtenerRutaPorId($post->getFondoId()),
            'tags'       => post_tagDAO::obtenerTagsPorPost($post->getId()),
            'fechaRecordatorio' => $fechaRecordatorio,
        ];
    }
// Devuelve los datos del usuario y sus posts
echo json_encode([
    'usuario' => [
        'nick' => $usuario->getNickname(),
        'nombre' => $usuario->getNombre(),
        'apellido' => $usuario->getApellido(),
        'email' => $usuario->getEmail(),
        'avatarRuta' => $avatarRuta   
    ],
    'posts' => $data
], JSON_PRETTY_PRINT);
