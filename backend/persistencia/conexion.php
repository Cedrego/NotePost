<?php
$host = "localhost";
$usuario = "root";
$contrasena = "";  // Vacío por defecto en XAMPP
$baseDeDatos = "NotePost"; // Nombre de la base de datos
// Creamos la conexión

$conn = new mysqli($host, $usuario, $contrasena, $baseDeDatos);

// Verificamos si hay error
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$conn->set_charset("utf8");

// Mostrar mensaje de éxito
echo "Conexión a la base de datos exitosa.";

?>
