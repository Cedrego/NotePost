<?php
require_once "persistencia/conexion.php";

$conn = Conexion::getConexion();

if ($conn instanceof mysqli) {
    echo "¡Conexión exitosa!";
}
?>
