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
require_once '../dominio/Usuario.php';
require_once '../dominio/Avatar.php';
require_once '../persistencia/DAO/UsuarioDAO.php';

$conn = Conexion::getConexion();
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión']);
    exit;
}
// Obtener los datos del POST (Angular envía JSON)
$input = json_decode(file_get_contents('php://input'), true);

//obtener el nickname desde el POST (enviado por Angular)
$nickUsu = isset($input['usuario']) ? $input['usuario'] : null;
$idAvatar = isset($input['idAvatar']) ? (int)$input['idAvatar'] : null;

if (!$nickUsu) {
    http_response_code(400);
    echo json_encode(['error' => 'Usuario no especificado.']);
    exit;
}
if (!$idAvatar) {
    http_response_code(400);
    echo json_encode(['error' => 'Avatar no especificado.']);
    exit;
}

$ok = UsuarioDAO::actualizarAvatar($nickUsu, $idAvatar);

if ($ok) {
    echo json_encode(['success' => true, 'message' => 'Avatar actualizado correctamente.']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'No se pudo actualizar el avatar.']);
}
?>
