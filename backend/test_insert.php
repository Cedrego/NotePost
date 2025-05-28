<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once './persistencia/conexion.php'; // Ajusta la ruta si es necesario
require_once './persistencia/mapeo/UsuarioMap.php';  // Ajusta la ruta si es necesario
require_once './persistencia/DAO/UsuarioDAO.php';  // Ajusta la ruta si es necesario

try {
    $usuario = new Usuario("nickprueba", "email@dominio.com", "Nombre", "Apellido", "123456");
    UsuarioDAO::guardar($usuario);
    echo "Usuario insertado correctamente";
} catch (Exception $e) {
    echo "Error al insertar usuario: " . $e->getMessage();
}
