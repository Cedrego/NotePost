<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *'); // Permite todas las fuentes (solo para pruebas)
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Content-Type: application/json; charset=utf-8');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
require_once '../persistencia/conexion.php';
require_once '../dominio/Usuario.php';
require_once '../persistencia/DAO/UsuarioDAO.php';

$conn = Conexion::getConexion();
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión a la base de datos']);
    exit;
}

// Obtener parámetro de búsqueda desde GET
$termino = $_GET['termino'] ?? '';
$usuarioActual = $_GET['usuarioActual'] ?? '';
if ($termino === '') {
    echo json_encode(['error' => 'Usuario no especificado.']); // Nada para buscar
    exit;
}

// Buscar usuarios usando el DAO
$usuarios = UsuarioDAO::BuscadorNick($termino, $usuarioActual);

echo json_encode($usuarios);
exit;
