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
require_once '../dominio/SolicitudDeAmistad.php';
require_once '../dominio/Usuario.php';
require_once '../persistencia/DAO/UsuarioDAO.php';
require_once '../persistencia/DAO/SolicitudDAO.php';

$conn = Conexion::getConexion();
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión']);
    exit;
}

// Obtener los datos del POST (Angular envía JSON)
$input = json_decode(file_get_contents('php://input'), true);
$solicitante = isset($input['solicitante']) ? $input['solicitante'] : null;
$recibidor = isset($input['recibidor']) ? $input['recibidor'] : null;
if (!$solicitante || !$recibidor) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos incompletos.']);
    exit;
}
//Obtengo los nick de los usuarios
$solicitanteU = UsuarioDAO::obtenerPorNickname($solicitante);
$recibidorU = UsuarioDAO::obtenerPorNickname($recibidor);
if (!$solicitante || !$recibidor) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos incompletos.']);
    exit;
}
//Esto se encarga de crear la solicitud de amistad
$SolicitudAmistad = new SolicitudAmistad($solicitanteU, $recibidorU);

// Verificar si ya existe una solicitud entre estos usuarios(mas que nada para que no pueda mandar mas de una)
$solicitudExistente = SolicitudDAO::obtener($solicitanteU->getNickname(), $recibidorU->getNickname());
if ($solicitudExistente) {
    http_response_code(400);
    echo json_encode(['error' => 'Ya existe una solicitud entre estos usuarios.']);
    exit;
}
// Si no existe, creo una nueva solicitud y la guardo
SolicitudDAO::guardar($SolicitudAmistad);
echo json_encode(['success' => true, 'message' => 'Solicitud enviada']);

