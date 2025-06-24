<?php
require_once __DIR__ . '/../dao/UsuarioDAO.php';
require_once __DIR__ . '/../dao/SolicitudDAO.php';

function EnviarSolicitud(string $UsuarioSolicitante, string $UsuarioRecibidor): void {
    $solicitante = UsuarioDAO::obtenerPorNickname($UsuarioSolicitante);
    $recibidor = UsuarioDAO::obtenerPorNickname($UsuarioRecibidor);

    if (!$solicitante || !$recibidor) {
        throw new Exception("Usuario no encontrado.");
    }

    $solicitud = $solicitante->enviarSolicitud($recibidor);
    SolicitudDAO::guardar($solicitud);
}
