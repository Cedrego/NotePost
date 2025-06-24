<?php   
header('Access-Control-Allow-Origin: *'); // Permite todas las fuentes (solo para pruebas)
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: POST, OPTIONS');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once '../dominio/Usuario.php';
require_once '../persistencia/DAO/UsuarioDAO.php';

// Obtener datos del POST (Angular envía JSON)
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos inválidos']);
    exit;
}
// Verificar si el nick ya existe
$usuarioExistente = UsuarioDAO::obtenerPorNickname($input['nick']);
if ($usuarioExistente) {
    http_response_code(409); // Conflicto
    echo json_encode(['error' => 'El nick ya existe']);
    exit;
}
$usuario = new Usuario(
    $input['nick'],
    $input['email'],
    $input['nombre'],
    $input['apellido'],
    $input['pass']
);

UsuarioDAO::guardar($usuario);
$ok = UsuarioDAO::actualizarAvatar($input['nick'], 1);
echo json_encode(['success' => true]);
?>