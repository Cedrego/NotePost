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

// Obtener el nick desde GET o POST
$nick = isset($_GET['usuario']) ? $_GET['usuario'] : null;
if (!$nick) {
    http_response_code(400);
    echo json_encode(['error' => 'Usuario no especificado']);
    exit;
}

$conn = Conexion::getConexion();
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión']);
    exit;
}
// Obtener amigos del usuario
$amigos = UsuarioDAO::obtenerAmigos($nick);
if ($amigos === null) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener amigos']);
    exit;
}   
// Devolver la lista de amigos como JSON
echo json_encode(['amigos' => $amigos]);
?>