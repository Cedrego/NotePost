<?php
session_start();

require_once __DIR__ . '/../dao/LikeDAO.php';
require_once __DIR__ . '/../dao/PostDAO.php';

if (!isset($_SESSION['usuario'])) {
    die("Usuario no autenticado.");
}

$nickUsu = $_SESSION['usuario'];
$idPost = isset($_POST['idPost']) ? intval($_POST['idPost']) : 0;

if ($idPost <= 0) {
    die("ID de post inválido.");
}

$likeDAO = new LikeDAO();
$postDAO = new PostDAO();

$post = $postDAO->obtenerPorId($idPost);
if ($post === null) {
    die("Post no encontrado.");
}

$accionExistente = $likeDAO->obtenerAccion($idPost, $nickUsu);

if ($accionExistente === 'like') {
    // Ya le dio like, no hacer nada
    exit;
} elseif ($accionExistente === 'dislike') {
    // Cambio de dislike a like: hacer dos downvotes y luego upvote
    $post->downvote();
    $post->downvote();
    $post->upvote();
    $postDAO->actualizar($post);
    $likeDAO->actualizarVoto($idPost, $nickUsu, 'like');
} else {
    // No había voto previo, agrego like
    $post->upvote();
    $postDAO->actualizar($post);
    $likeDAO->insertarVoto($idPost, $nickUsu, 'like');
}

echo "Like registrado correctamente.";
