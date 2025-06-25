<?php
require_once '../persistencia/conexion.php';
require_once '../persistencia/DAO/RecordatorioDAO.php';

header('Content-Type: application/json');

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