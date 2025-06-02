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

if ($accionExistente === 'dislike') {
    // Ya le dio dislike, no hacer nada
    exit;
} elseif ($accionExistente === 'like') {
    // Cambio de like a dislike: hacer dos downvotes
    $post->downvote();
    $post->downvote();
    $postDAO->actualizar($post);
    $likeDAO->actualizarVoto($idPost, $nickUsu, 'dislike');
} else {
    // No había voto previo, agrego dislike
    $post->downvote();
    $postDAO->actualizar($post);
    $likeDAO->insertarVoto($idPost, $nickUsu, 'dislike');
}

echo "Dislike registrado correctamente.";
