<?php
session_start();
require_once '../dominio/Usuario.php';
require_once '../dominio/Avatar.php';
require_once '../persistencia/conexion.php';
require_once 'procesarFormulario.php';

//verifica la conexión a la base de datos
if ($conn->connect_error) die("Conexión fallida: " . $conn->connect_error);

//el usuario debe estar loggeado
$nickUsu = $_SESSION['usuario'];

//obtener el id del avatar seleccionado desde el formulario
$idAvatar = isset($data['idAvatar']) ? (int)$data['idAvatar'] : null;
echo $idAvatar;
if (!$idAvatar) {
    echo "Error: avatar no especificado.";
    exit;
}

//buscar el avatar en la base de datos
$stmt = $conn->prepare("SELECT id, rutaImagen FROM avatar WHERE id = ?");
$stmt->bind_param("i", $idAvatar);
$stmt->execute();
$stmt->bind_result($avatarId, $rutaImagen);

if (!$stmt->fetch()) {
    echo "Error: avatar no encontrado.";
    exit;
}
$stmt->close();

//obtener el usuario desde la base de datos
$usuario = Usuario::obtenerPorNickname($conn, $nickUsu);
if (!$usuario) {
    echo "Error: usuario no encontrado.";
    exit;
}

//cambiar el avatar en el objeto usuario
$usuario->setAvatar($idAvatar);

//actualizar el avatar en la base de datos
$stmt2 = $conn->prepare("UPDATE usuario SET avatar = ? WHERE nickname = ?");
$stmt2->bind_param("is", $idAvatar, $nickUsu);
$stmt2->execute();

if ($stmt2->affected_rows > 0) {
    echo "Avatar actualizado correctamente.";
} else {
    echo "No se pudo actualizar el avatar.";
}
?>
