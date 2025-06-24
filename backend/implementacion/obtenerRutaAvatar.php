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

require_once '../dominio/Usuario.php';
require_once '../persistencia/DAO/UsuarioDAO.php';
require_once '../persistencia/DAO/AvatarDAO.php';
// Obtener el nick desde GET o POST
$nick = isset($_GET['usuario']) ? $_GET['usuario'] : null;
if (!$nick) {
    http_response_code(400);
    echo json_encode(['error' => 'Usuario no especificado']);
    exit;
}
// Obtener el avatar del usuario
$avatar = UsuarioDAO::getAvatar($nick);
if (!$avatar) {
    http_response_code(404);
    echo json_encode(['error' => 'Avatar no encontrado']);
    exit;
}
// Obtener la ruta del avatar
$rutaAvatar = AvatarDAO::obtenerPorId($avatar->getId());
if (!$rutaAvatar) {
    http_response_code(404);
    echo json_encode(['error' => 'Ruta del avatar no encontrada']);
    exit;
}
// Devolver la ruta del avatar como JSON
echo json_encode(['ruta' => $rutaAvatar->getRutaImagen()]);
?>