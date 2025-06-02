<?php
session_start();
require_once '../dominio/Post.php';
require_once '../dominio/Usuario.php';
require_once "../dominio/Recordatorio.php";
require_once '../persistencia/conexion.php'; //$conn = conexión MySQLi

if ($conn->connect_error) die("Conexión fallida: " . $conn->connect_error);

//en teoria el usuario ya esta loggeado si esta usando esta funcion
$nickUsu = $_SESSION['usuario']; //nickname del usuario

//obtener datos del formulario
$contenido = $_POST['contenido'];
$privado = isset($_POST['privado']) ? 1 : 0;
$fechaRecordatorio = !empty($_POST['recordatorio']) ? $_POST['recordatorio'] : null;

//crear recordatorio y post  
$recordatorio = $fechaRecordatorio ? new Recordatorio($fechaRecordatorio) : null;
$post = new Post($contenido, $privado, $recordatorio);

//por ahora se hace manual, en teoría esto inserta el post en la tabla
$stmt = $conn->prepare("INSERT INTO post (contenido, likes, dislikes, fechaPost, privado, fechaRecordatorio) VALUES (?, 0, 0, ?, ?, ?)");
//se saca la fecha del post desde el objeto post que ya se había creado antes
$fechaActual = $post->fechaPost;
//se vinculan los valores reales a los signos de pregunta del prepare de arriba, el "ssis" indica los tipos (string, string, int, string)
$stmt->bind_param("ssis", $post->contenido, $fechaActual, $post->privado, $fechaRecordatorio);
//se ejecuta la sentencia con los valores que se le pasaron
$stmt->execute();
//se guarda el id que se le asignó automáticamente al post insertado, esto lo da la base de datos
$post->idPost = $conn->insert_id;


//linkeamos el post al usuario
$usuario = Usuario::obtenerPorNickname($conn, $nickUsu);
if ($usuario) {
    $usuario->agregarPostUsuario($post, $conn);
} else {
    echo "Error: usuario no encontrado en la base de datos.";
    exit;
}


echo "Post creado correctamente.";
?>
