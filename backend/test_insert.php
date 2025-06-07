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
try {
    $usuario = new Usuario("Test", "Test@dominio.com", "Test", "Perez", "123456");
    UsuarioDAO::guardar($usuario);
    echo "Usuario insertado correctamente";

    // Crear post
    $post = new Post($usuario, "Este es un post con recordatorio y tags", false);
    $post->setFechaPost(new DateTime());

    // Agregar recordatorio
    $recordatorio = new Recordatorio($post, new DateTime('+3 days'));
    $post->addRecordatorio($recordatorio);

    // Agregar tag
    $tag = new Tag($post, "estudio");
    $post->addTag($tag);

    // Guardar el post
    $postDAO = new PostDAO();
    $postDAO->guardar($post);

    echo "Post con recordatorio y tag insertado correctamente";

} catch (Exception $e) {
    echo "Error al insertar usuario: " . $e->getMessage();
}
