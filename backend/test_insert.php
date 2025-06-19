<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once './persistencia/conexion.php'; // Ajusta la ruta si es necesario

require_once './persistencia/mapeo/UsuarioMap.php';  
require_once './persistencia/DAO/UsuarioDAO.php'; 

require_once './persistencia/mapeo/PostMap.php';  
require_once './persistencia/DAO/PostDAO.php'; 

require_once './persistencia/mapeo/RecordatorioMap.php';  
require_once './persistencia/DAO/RecordatorioDAO.php'; 

require_once './persistencia/mapeo/TagMap.php';  
require_once './persistencia/DAO/TagDAO.php'; 

require_once './persistencia/mapeo/post_tagMap.php';  
require_once './persistencia/DAO/post_tagDAO.php';

require_once './persistencia/mapeo/SolicitudMap.php';  
require_once './persistencia/DAO/SolicitudDAO.php';

require_once './persistencia/mapeo/LikeMap.php';  
require_once './persistencia/DAO/LikeDAO.php';

// 1. Ejecutar el script SQL para recrear la base de datos
$sql = file_get_contents(__DIR__ . '/notepost_schema.sql');
$conn = Conexion::getConexion(false); // false para no seleccionar la BD aún

if ($conn->multi_query($sql)) {
    // Esperar a que termine todas las queries
    do { /* vacio */ } while ($conn->more_results() && $conn->next_result());
    echo "Base de datos recreada correctamente.<br>";
} else {
    die("Error al crear la base de datos: " . $conn->error);
}

// Ahora selecciona la base de datos para los inserts
$conn->select_db('NotePost');

try {
    //Crear usuario
    $usuario = new Usuario("Test", "Test@dominio.com", "Test", "Perez", "123456");
    UsuarioDAO::guardar($usuario);
    echo "Usuario insertado correctamente<br>";

    // Crear post
    $post = new Post($usuario, "Este es un post con recordatorio y tags", false);
    $post->setFechaPost(new DateTime());
    PostDAO::guardar($post);
    echo "Post insertado correctamente<br>";
    // Agregar recordatorio
    $recordatorio = new Recordatorio($post, new DateTime('+3 days'));
    $post->addRecordatorio($recordatorio);
    RecordatorioDAO::insert($recordatorio); // Guardar el recordatorio asociado al post
    echo "Recordatorio insertado correctamente<br>";

    // Agregar tag
    $tag = new Tag("estudio");
    TagDAO::guardar($tag); // Guardar el tag antes de asociarlo al post
    echo "Tag insertado correctamente<br>";
    // Asociar el tag al post
    $post->addTag($tag);

    // Guardar el post con el tag asociado
    $post_tag = new post_tag($post->getId(), $tag->getTag());
    post_tagDAO::guardar($post_tag);
    echo "Relación post-tag insertada correctamente<br>";

    //Guardar Solicitud
    $usuario2 = new Usuario("Test2", "Test2@dominio.com", "Test2", "Perez2", "1234562");
    UsuarioDAO::guardar($usuario2);
    $solicitud = new SolicitudAmistad($usuario, $usuario2);
    SolicitudDAO::guardar($solicitud);
    echo "Solicitud de amistad insertada correctamente<br>";

    //Gardar Like
    $like = new Like($post->getId(), $usuario, 'like');
    LikeDAO::guardar($like);
    echo "Like insertado correctamente<br>";

} catch (Exception $e) {
    echo "Error al insertar : " . $e->getMessage();
}
