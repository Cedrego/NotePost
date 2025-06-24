<?php
header('Access-Control-Allow-Origin: *'); // Permite todas las fuentes (solo para pruebas)
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: POST, OPTIONS');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
require_once '../dominio/Post.php';
require_once '../dominio/Usuario.php';
require_once "../dominio/Recordatorio.php";

require_once '../persistencia/conexion.php';
require_once '../persistencia/DAO/UsuarioDAO.php';
require_once '../persistencia/DAO/RecordatorioDAO.php';
require_once '../persistencia/DAO/PostDAO.php';
require_once '../persistencia/DAO/TagDAO.php';
require_once '../persistencia/DAO/post_tagDAO.php';
//Me parece inecesario.
require_once 'procesarFormulario.php';

// Procesar el formulario enviado por Angular
$data = json_decode(file_get_contents('php://input'), true);
$nickUsu = isset($data['usuario']) ? $data['usuario'] : null;
$contenido = $data['contenido'];
$privado = isset($data['privado']) && $data['privado'] === true;
$fechaRecordatorio = !empty($data['recordatorio']) ? $data['recordatorio'] : null;
$fondoId = isset($data['fondo']) ? (int) $data['fondo'] : null;

$conn = Conexion::getConexion();
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión']);
    exit;
}

//obtener el nickname desde el POST (enviado por Angular)
if (!$nickUsu) {
    http_response_code(400);
    echo json_encode(['error' => 'usuario no especificado.']);
    exit;
}

//obtener el objeto usuario desde la base de datos
$usuario = UsuarioDAO::obtenerPorNickname($nickUsu);
if (!$usuario) {
    http_response_code(400);
    echo json_encode(['error' => 'usuario no encontrado en la base de datos.']);
    exit;
}

//crear el post con los datos obtenidos
$post = new Post($usuario, $contenido, $privado,$fondoId );
//insertar el post en la base de datos
PostDAO::guardar($post);
PostDAO::actualizarFondo($post->getId(), $fondoId); // Asignar fondo al post
//si hay recordatorio, lo agregamos al post
if ($fechaRecordatorio) {
     // Convierte "YYYY-MM-DDTHH:MM" a "YYYY-MM-DD HH:MM:SS"
    $fechaRecordatorio = str_replace('T', ' ', $fechaRecordatorio);
    if (strlen($fechaRecordatorio) === 16) {
        $fechaRecordatorio .= ':00';
    }
    try {
        $fechaRecordatorioDT = new DateTime($fechaRecordatorio);
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['error' => 'Formato de fecha inválido', 'detalle' => $e->getMessage(), 'valor' => $fechaRecordatorio]);
        exit;
    }
    $recordatorio = new Recordatorio($post ,$fechaRecordatorioDT);
    RecordatorioDAO::insert($recordatorio); // Guardar el recordatorio asociado al post

}

//se obtiene la fecha del post en formato compatible con MySQL
//$fechaActual = $post->getFechaPost()->format('Y-m-d H:i:s');  No neseario cuando creas el tipo post ya maneja la fecha


// Procesar los tags
$tags = [];
if (isset($data['tags'])) {
    if (is_array($data['tags'])) {
        $tags = $data['tags'];
    } else {
        $tags = array_map('trim', explode(',', $data['tags']));
    }
}
if (!empty($tags)) {
    foreach ($tags as $tagNombre) {
        $tag = TagDAO::searchTag($tagNombre);
        if (!$tag) {
            $tag = new Tag($tagNombre);
            TagDAO::guardar($tag);
        }
        $postTag = new post_tag($post->getId(), $tag->getTag());
        post_tagDAO::guardar($postTag);
    }
}


?>
