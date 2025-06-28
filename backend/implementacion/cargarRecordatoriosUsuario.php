<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
require_once '../persistencia/conexion.php';
require_once '../persistencia/DAO/RecordatorioDAO.php';

$usuario = isset($_GET['usuario']) ? trim($_GET['usuario']) : null;

if (!$usuario) {
    echo json_encode(["error" => "No se especificó el usuario."]);
    exit;
}

try {
    $recordatorios = RecordatorioDAO::obtenerRecordatoriosPorUsuario($usuario);
    echo json_encode($recordatorios, JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>