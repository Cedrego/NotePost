<?php
require_once '../dominio/Usuario.php';
require_once '../dominio/Avatar.php';
require_once '../persistencia/conexion.php';
require_once '../persistencia/DAO/UsuarioDAO.php';

$conn = Conexion::getConexion();
//verifica la conexión a la base de datos
if ($conn->connect_error) die("Conexión fallida: " . $conn->connect_error);

//obtener el nickname desde el POST (enviado por Angular)
$nickUsu = isset($_POST['usuario']) ? $_POST['usuario'] : null;
if (!$nickUsu) {
    echo "Error: usuario no especificado.";
    exit;
}
//obtener el id del avatar seleccionado desde el formulario
$idAvatar = isset($_POST['idAvatar']) ? (int)$_POST['idAvatar'] : null;
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
$usuario = UsuarioDAO::obtenerPorNickname($nickUsu);
if (!$usuario) {
    echo "Error: usuario no encontrado.";
    exit;
}

//cambiar el avatar en el objeto usuario
$avatar = new Avatar($avatarId, $rutaImagen);
$usuario->setAvatar($avatar);

//actualizar el avatar en la base de datos
$stmt2 = $conn->prepare("UPDATE usuarios SET avatar = ? WHERE nickname = ?");
$stmt2->bind_param("is", $idAvatar, $nickUsu);
$stmt2->execute();
$stmt2->close();
if ($stmt2->affected_rows > 0) {
    echo "Avatar actualizado correctamente.";
} else {
    echo "No se pudo actualizar el avatar.";
}
?>
