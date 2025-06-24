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

// Obtener datos del POST (Angular envía JSON)
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos inválidos']);
    exit;
}
// Verificar si el usuraio existe o coiciden los datos ingresados
$usuario = UsuarioDAO::existeUsuario($input['nick'], $input['pass']);

if ($usuario) {
    // Usuario y contraseña correctos
    // Puedes devolver los datos al frontend
    http_response_code(200); // OK
    echo json_encode([
        'success' => true,
        'usuario' => [
            'nick' => $usuario->getNickname(),
            'email' => $usuario->getEmail(),
            'nombre' => $usuario->getNombre(),
            'apellido' => $usuario->getApellido()
        ]
    ]);
    exit;
} else {
    // Usuario o contraseña incorrectos
    // Devuelve error al frontend
    http_response_code(401); // Mejor usar 401 para login incorrecto
    echo json_encode(['error' => 'Nick o contraseña incorrectos']);
    exit;
}

?>