<?php

require_once '../dominio/Post.php';
require_once '../dominio/Usuario.php';
require_once "../dominio/Recordatorio.php";
require_once '../persistencia/conexion.php';
require_once '../persistencia/DAO/UsuarioDAO.php';
require_once '../persistencia/DAO/RecordatorioDAO.php';
require_once '../persistencia/DAO/PostDAO.php';
require_once '../persistencia/DAO/TagDAO.php';
require_once '../persistencia/DAO/post_tagDAO.php';
require_once 'procesarFormulario.php';

//FALTA TAG PARA SABER QUE TAGS TIENE EL POST. RELACION POST-TAG
$contenido = $data['contenido'];
$privado = isset($data['privado']) && $data['privado'] === 'true';
$fechaRecordatorio = !empty($data['recordatorio']) ? $data['recordatorio'] : null;
$fondoId = isset($data['fondo']) ? (int) $data['fondo'] : null;

$conn = Conexion::getConexion();
//verifica la conexión a la base de datos
if ($conn->connect_error) die("Conexión fallida: " . $conn->connect_error);

//obtener el nickname desde el POST (enviado por Angular)
$nickUsu = isset($_POST['usuario']) ? $_POST['usuario'] : null;
if (!$nickUsu) {
    echo "Error: usuario no especificado.";
    exit;
}

/*//obtener datos del formulario
$contenido = $_POST['contenido'];
$privado = isset($_POST['privado']) ? true : false;
$fechaRecordatorio = !empty($_POST['recordatorio']) ? $_POST['recordatorio'] : null;
$fondoId = isset($_POST['fondoId']) ? (int)$_POST['fondoId'] : null;
*/


//obtener el objeto usuario desde la base de datos
$usuario = UsuarioDAO::obtenerPorNickname($nickUsu);
if (!$usuario) {
    echo "Error: usuario no encontrado en la base de datos.";
    exit;
}

//crear el post con los datos obtenidos
$post = new Post($usuario, $contenido, $privado, $fondoId);

//si hay recordatorio, lo agregamos al post
if ($fechaRecordatorio) {
    $recordatorio = new Recordatorio($post ,$fechaRecordatorio);
    RecordatorioDAO::insert($recordatorio); // Guardar el recordatorio asociado al post
    // Agregar el recordatorio al post
    $post->addRecordatorio($recordatorio);
}

//se obtiene la fecha del post en formato compatible con MySQL
//$fechaActual = $post->getFechaPost()->format('Y-m-d H:i:s');  No neseario cuando creas el tipo post ya maneja la fecha

//insertar el post en la base de datos
PostDAO::guardar($post);

//si hay tags, los agregamos al post
if (isset($data['tags']) && is_array($data['tags'])) {
    foreach ($data['tags'] as $tagNombre) {
        // Verificar si el tag ya existe
        $tag = TagDAO::searchTag($tagNombre);
        if (!$tag) {
            // Si no existe, crearlo
            $tag = new Tag($tagNombre);
            TagDAO::guardar($tag);
        }
        // Asociar el tag al post
        $postTag = new post_tag($post->getId(), $tag->getTag());
        post_tagDAO::guardar($postTag);
    }
}

echo "Post creado correctamente.";
?>
