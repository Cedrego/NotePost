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
require_once '../persistencia/DAO/RecordatorioDAO.php';
require_once '../dominio/Recordatorio.php';

// Obtener el nickname del usuario desde el POST
$data = json_decode(file_get_contents('php://input'), true);
$nickUsu = $data['usuario'] ?? null;

if (!$nickUsu) {
    echo json_encode(['error' => 'Usuario no especificado']);
    exit;
}

try {
    $recordatorios = RecordatorioDAO::obtenerRecordatoriosPorUsuario($nickUsu);
    echo json_encode($recordatorios, JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>