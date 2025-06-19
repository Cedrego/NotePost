<?php
require_once __DIR__ . '/../dao/UsuarioDAO.php';
require_once __DIR__ . '/../dao/SolicitudDAO.php';

function AceptarSolicitud(string $UsuarioSolicitante, string $UsuarioRecibidor): void {
    $solicitud = SolicitudDAO::obtener($UsuarioSolicitante, $UsuarioRecibidor);

    if (!$solicitud) {
        throw new Exception("Solicitud no encontrada.");
    }

    $solicitud->aceptar();

    // Guardar cambios de amistad
    UsuarioDAO::guardar($solicitud->getSolicitante());
    UsuarioDAO::guardar($solicitud->getRecibidor());

    // Eliminar solicitud una vez aceptada
    SolicitudDAO::eliminar($solicitud);
}
