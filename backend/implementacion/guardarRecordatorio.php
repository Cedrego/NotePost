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
require_once '../persistencia/DAO/RecordatorioDAO.php';
require_once '../persistencia/DAO/PostDAO.php';
require_once '../dominio/Recordatorio.php';
require_once '../dominio/Post.php';

// Obtener datos del POST
$data = json_decode(file_get_contents('php://input'), true);
$nickUsu = $data['usuario'] ?? null; // Nickname del usuario
$tituloEvento = $data['tituloEvento'] ?? 'Evento sin título'; // Título del evento
$fechaRecordatorio = $data['fechaRecordatorio'] ?? null;

if (!$nickUsu || !$fechaRecordatorio) {
    echo json_encode(['error' => 'Datos incompletos']);
    exit;
}

try {

    //verifica conexión a la base de datos
    $conn = Conexion::getConexion();
    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode(['error' => 'Error de conexión']);
        exit;
    }

    //verifica si el usuario existe
    $usuario = UsuarioDAO::obtenerPorNickname($nickUsu); // Obtener el usuario
    if (!$usuario) {
        echo json_encode(['error' => 'Usuario no encontrado']);
        exit;
    }
   
    //crear un nuevo post
    $post = new Post($usuario, $tituloEvento, false, 1); //por default, el post esta en privado
    PostDAO::guardar($post); //guardar el post en la base de datos
    

    //crear el recordatorio asociado al post
    $fecha = new DateTime($fechaRecordatorio);
    $recordatorio = new Recordatorio($post, $fecha);
    $resultado = RecordatorioDAO::insert($recordatorio);
    $post->addRecordatorio($recordatorio); //agregar el recordatorio al post

    if ($resultado) {
        echo json_encode(['success' => 'Recordatorio creado exitosamente']);
    } else {
        echo json_encode(['error' => 'Error al crear el recordatorio']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>