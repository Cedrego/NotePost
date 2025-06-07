<?php
session_start();

require_once '../dominio/Post.php';
require_once '../dominio/Usuario.php';
require_once '../persistencia/conexion.php'; //$conn = conexión MySQLi

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$idPost = $_POST['id']; //id del post a modificar
$nick = $_SESSION['usuario'] ?? null; //nick del usuario loggeado

if (!$nick) {
    die("No estás loggeado.");
}

//primero se obtiene el estado actual del post
$stmt = $conn->prepare("SELECT privado FROM post WHERE id = ? AND autor = ?");
$stmt->bind_param("is", $idPost, $nick);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $estadoActual = (bool) $row['privado'];
    $nuevoEstado = !$estadoActual; //lo invertimos

    //y luego se actualiza con el nuevo
    $update = $conn->prepare("UPDATE post SET privado = ? WHERE id = ? AND autor = ?");
    $update->bind_param("iis", $nuevoEstado, $idPost, $nick);

    if ($update->execute()) {
        echo "Estado cambiado correctamente";
    } else {
        echo "Error al cambiar el estado";
    }

    $update->close();
} else {
    echo "No se encontró el post";
}
?>
