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

require_once './persistencia/mapeo/AvatarMap.php';  
require_once './persistencia/DAO/AvatarDAO.php';

require_once './persistencia/mapeo/FondoMap.php';  
require_once './persistencia/DAO/FondoDAO.php';
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
//Gaurdo los avatars
    AvatarDAO::guardar(new Avatar(1, 'assets/avatars/avatar1.jpg'));
    AvatarDAO::guardar(new Avatar(2, 'assets/avatars/avatar2.jpg'));
    AvatarDAO::guardar(new Avatar(3, 'assets/avatars/avatar3.jpg'));
    AvatarDAO::guardar(new Avatar(4, 'assets/avatars/avatar4.jpg'));
    AvatarDAO::guardar(new Avatar(5, 'assets/avatars/B-Rojo.png'));
    AvatarDAO::guardar(new Avatar(6, 'assets/avatars/B-Naranja.png'));
    AvatarDAO::guardar(new Avatar(7, 'assets/avatars/B-Amarillo.png'));
    AvatarDAO::guardar(new Avatar(8, 'assets/avatars/B-Lima.png'));
    AvatarDAO::guardar(new Avatar(9, 'assets/avatars/B-Verde.png'));
    AvatarDAO::guardar(new Avatar(10, 'assets/avatars/B-Turquesa.png'));
    AvatarDAO::guardar(new Avatar(11, 'assets/avatars/B-Cyan.png'));
    AvatarDAO::guardar(new Avatar(12, 'assets/avatars/B-Azul.png'));
    AvatarDAO::guardar(new Avatar(13, 'assets/avatars/B-Purpura.png'));
    AvatarDAO::guardar(new Avatar(14, 'assets/avatars/B-Rosa.png'));
    AvatarDAO::guardar(new Avatar(15, 'assets/avatars/B-Magenta.png'));
    AvatarDAO::guardar(new Avatar(16, 'assets/avatars/B-Blanco.png'));
    AvatarDAO::guardar(new Avatar(17, 'assets/avatars/W-Rojo.png'));
    AvatarDAO::guardar(new Avatar(18, 'assets/avatars/W-Naranja.png'));
    AvatarDAO::guardar(new Avatar(19, 'assets/avatars/W-Amarillo.png'));
    AvatarDAO::guardar(new Avatar(20, 'assets/avatars/W-Lima.png'));
    AvatarDAO::guardar(new Avatar(21, 'assets/avatars/W-Verde.png'));
    AvatarDAO::guardar(new Avatar(22, 'assets/avatars/W-Turquesa.png'));
    AvatarDAO::guardar(new Avatar(23, 'assets/avatars/W-Cyan.png'));
    AvatarDAO::guardar(new Avatar(24, 'assets/avatars/W-Azul.png'));
    AvatarDAO::guardar(new Avatar(25, 'assets/avatars/W-Purpura.png'));
    AvatarDAO::guardar(new Avatar(26, 'assets/avatars/W-Rosa.png'));
    AvatarDAO::guardar(new Avatar(27, 'assets/avatars/W-Magenta.png'));
    AvatarDAO::guardar(new Avatar(28, 'assets/avatars/W-Negro.png'));
    echo "Avatares insertados correctamente<br>";

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

    //Agregar amigo
    $usuario3 = new Usuario("Test3", "Test2@dominio.com", "Test2", "Perez2", "1234562");
    UsuarioDAO::guardar($usuario3);
    UsuarioDAO::addAmigo($usuario->getNickname(), $usuario3->getNickname());
    UsuarioDAO::addAmigo($usuario->getNickname(), $usuario2->getNickname());
    echo "Amigo agregado correctamente<br>";
    //Gardar Like
    $like = new Like($post->getId(), $usuario, 'like');
    LikeDAO::guardar($like);
    $post->upvote(); // Incrementar el contador de likes del post
    PostDAO::actualizar($post); // Actualizar el post con el nuevo contador de likes
    echo "Like insertado correctamente<br>";

    UsuarioDAO::actualizarAvatar($usuario->getNickname(), 1);
    UsuarioDAO::actualizarAvatar($usuario2->getNickname(), 2);
    UsuarioDAO::actualizarAvatar($usuario3->getNickname(), 3);
    echo "Avatares de usuario actualizados correctamente<br>";

    FondoDAO::guardar(new Fondo(1, 'assets/fondos/fondo1.jpg'));
    FondoDAO::guardar(new Fondo(2, 'assets/fondos/fondo2.jpg'));
    FondoDAO::guardar(new Fondo(3, 'assets/fondos/fondo3.jpg'));
    FondoDAO::guardar(new Fondo(4, 'assets/fondos/fondo4.jpg'));
    echo "Fondos insertados correctamente<br>";

    PostDAO::actualizarFondo($post->getId(), 1); // Asignar fondo al post
    echo "Fondo asignado al post correctamente<br>";

} catch (Exception $e) {
    echo "Error al insertar : " . $e->getMessage();
}
