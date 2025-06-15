<?php
require_once '../dominio/Post.php';
require_once '../dominio/Tag.php';
require_once '../persistencia/conexion.php';

//obtener el tag a buscar desde el formulario o la URL
$tagBuscar = isset($_GET['tag']) ? trim($_GET['tag']) : (isset($_POST['tag']) ? trim($_POST['tag']) : null);

if (!$tagBuscar) {
    echo "Error: no se especificó ningún tag para buscar.";
    exit;
}

//buscar los posts que tengan el tag especificado
$postsFiltrados = [];

//consulta para obtener los posts y sus ids
$stmt = $conn->prepare("SELECT p.id, p.contenido, p.likes, p.dislikes, p.fechaPost, p.privado, p.fondoId, p.autor 
                        FROM post p 
                        JOIN tag t ON p.id = t.post_id 
                        WHERE t.tag = ?");
$stmt->bind_param("s", $tagBuscar);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    // Crear objeto Post (puedes ajustar según tu constructor)
    // Aquí se asume que tienes una función para obtener el Usuario por nickname
    $autor = Usuario::obtenerPorNickname($conn, $row['autor']);
    $post = new Post($autor, $row['contenido'], (bool)$row['privado'], $row['fondoId']);
    $post->setId($row['id']);
    $post->setLikes($row['likes']);
    $post->setDislikes($row['dislikes']);
    $post->setFechaPost(new DateTime($row['fechaPost']));
    // Opcional: puedes cargar los tags y recordatorios si lo necesitas
    $postsFiltrados[] = $post;
}

// Mostrar los posts encontrados
if (count($postsFiltrados) === 0) {
    echo "No se encontraron posts con el tag '$tagBuscar'.";
} else {
    foreach ($postsFiltrados as $post) {
        echo "<div class='post'>";
        echo "<h4>Autor: " . htmlspecialchars($post->getAutor()->getNickname()) . "</h4>";
        echo "<p>" . htmlspecialchars($post->getContenido()) . "</p>";
        echo "<small>Likes: " . $post->getLikes() . " | Dislikes: " . $post->getDislikes() . "</small>";
        echo "</div><hr>";
    }
}
?>
