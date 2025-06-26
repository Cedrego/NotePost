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
require_once '../persistencia/DAO/AvatarDAO.php';
require_once '../persistencia/mapeo/AvatarMap.php';


$conn = Conexion::getConexion();
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión']);
    exit;
}
// Obtener todos los avatares
$avataresObj = AvatarDAO::obtenerTodos();

// Convertir cada objeto a array asociativo
$avatares = [];
foreach ($avataresObj as $avatar) {
    $avatares[] = AvatarMap::mapAvatarToArray($avatar);
}

// Devolver la lista de solicitudes como JSON
echo json_encode(['Solicitudes' => $avatares]);
?>
