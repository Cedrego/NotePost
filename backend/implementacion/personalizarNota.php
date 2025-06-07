<?php
session_start();
require_once '../dominio/Usuario.php';
require_once '../dominio/Post.php';
require_once '../persistencia/conexion.php';

// Verifica la conexión a la base de datos
if ($conn->connect_error) die("Conexión fallida: " . $conn->connect_error);

// El usuario debe estar loggeado
$nickUsu = $_SESSION['usuario'];

// Obtener datos del formulario
$idPost = isset($_POST['idPost']) ? (int)$_POST['idPost'] : null;
$nuevoFondoId = isset($_POST['nuevoFondoId']) ? (int)$_POST['nuevoFondoId'] : null;

if (!$idPost || !$nuevoFondoId) {
    echo "Error: datos incompletos.";
    exit;
}

// Obtener el usuario desde la base de datos
$usuario = Usuario::obtenerPorNickname($conn, $nickUsu);
if (!$usuario) {
    echo "Error: usuario no encontrado.";
    exit;
}

// Cambiar el fondo del post en la colección del usuario
$cambio = $usuario->cambiarFondoPost($idPost, $nuevoFondoId);

if ($cambio) {
    // Reflejar el cambio en la base de datos
    $stmt = $conn->prepare("UPDATE post SET fondoId = ? WHERE id = ? AND autor = ?");
    $stmt->bind_param("iis", $nuevoFondoId, $idPost, $nickUsu);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Fondo actualizado correctamente.";
    } else {
        echo "No se pudo actualizar el fondo en la base de datos.";
    }
} else {
    echo "No tienes permisos para modificar este post o el post no existe.";
}
?>
