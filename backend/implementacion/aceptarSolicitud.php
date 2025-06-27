<?php

require_once __DIR__ . '/../persistencia/DAO/UsuarioDAO.php';
require_once __DIR__ . '/../persistencia/DAO/SolicitudDAO.php';
require_once '../dominio/SolicitudDeAmistad.php';
require_once '../dominio/Usuario.php';
// Permitir peticiones desde el frontend
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Obtener datos del POST (JSON)
$data = json_decode(file_get_contents('php://input'), true);
$solicitante = $data['solicitante'] ?? null;
$recibidor = $data['recibidor'] ?? null;
// El usuario recibidor es el usuario logueado (debe venir por sesión o por el frontend)
if (!$solicitante || !$recibidor) {
    http_response_code(400);
    echo json_encode(['error' => 'Faltan datos']);
    exit;
}

// Buscar la solicitud
$solicitud = SolicitudDAO::obtener($solicitante, $recibidor);

if (!$solicitud) {
    http_response_code(404);
    echo json_encode(['error' => 'Solicitud no encontrada']);
    exit;
}

// Marcar como aceptada
$solicitud->aceptar();

// Agregar como amigos
UsuarioDAO::addAmigo($solicitante, $recibidor);

// Eliminar la solicitud
SolicitudDAO::eliminar($solicitud);

echo json_encode(['success' => true]);