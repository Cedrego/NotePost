<?php
session_start();
require_once '../dominio/Post.php';
require_once '../dominio/Usuario.php';
require_once "../dominio/Recordatorio.php";
require_once '../persistencia/conexion.php';
require_once 'procesarFormulario.php';

$contenido = $data['contenido'];
$privado = isset($data['privado']) && $data['privado'] === 'true';
$fechaRecordatorio = !empty($data['recordatorio']) ? $data['recordatorio'] : null;
$fondoId = isset($data['fondo']) ? (int) $data['fondo'] : null;

//verifica la conexión a la base de datos
if ($conn->connect_error) die("Conexión fallida: " . $conn->connect_error);

//el usuario ya debe estar loggeado si está usando esta función
$nickUsu = $_SESSION['usuario']; // nickname del usuario

/*//obtener datos del formulario
$contenido = $_POST['contenido'];
$privado = isset($_POST['privado']) ? true : false;
$fechaRecordatorio = !empty($_POST['recordatorio']) ? $_POST['recordatorio'] : null;
$fondoId = isset($_POST['fondoId']) ? (int)$_POST['fondoId'] : null;
*/


//obtener el objeto usuario desde la base de datos
$usuario = Usuario::obtenerPorNickname($conn, $nickUsu);
if (!$usuario) {
    echo "Error: usuario no encontrado en la base de datos.";
    exit;
}

//crear el post con los datos obtenidos
$post = new Post($usuario, $contenido, $privado, $fondoId);

//si hay recordatorio, lo agregamos al post
if ($fechaRecordatorio) {
    $recordatorio = new Recordatorio($fechaRecordatorio);
    $post->addRecordatorio($recordatorio);
}

//se obtiene la fecha del post en formato compatible con MySQL
$fechaActual = $post->getFechaPost()->format('Y-m-d H:i:s');

//prepara la consulta para insertar el post en la base de datos
$stmt = $conn->prepare("INSERT INTO post (contenido, likes, dislikes, fechaPost, privado, fondoId, fechaRecordatorio) VALUES (?, 0, 0, ?, ?, ?, ?)");
$privadoInt = $privado ? 1 : 0;
$fondoIdDb = $fondoId !== null ? $fondoId : null;
$fechaRecordatorioDb = $fechaRecordatorio !== null ? $fechaRecordatorio : null;

//vincula los valores reales a los signos de pregunta del prepare de arriba
$stmt->bind_param("ssiiis", $contenido, $fechaActual, $privadoInt, $fondoIdDb, $fechaRecordatorioDb);

//ejecuta la sentencia con los valores que se le pasaron
$stmt->execute();

//guarda el id que se le asignó automáticamente al post insertado
$post->setId($conn->insert_id);

//linkea el post al usuario en la tabla usuario_post
$usuario->agregarPostUsuario($post, $conn);

//echo "Post creado correctamente.";
?>
