<?php
require_once __DIR__ . '../persistencia/DAO/UsuarioDAO.php';
require_once __DIR__ . '../persistencia/DAO/SolicitudDAO.php';

function AceptarSolicitud(string $UsuarioSolicitante, string $UsuarioRecibidor): void {
    $solicitud = SolicitudDAO::obtener($UsuarioSolicitante, $UsuarioRecibidor);

    if (!$solicitud) {
        throw new Exception("Solicitud no encontrada.");
    }

    $solicitud->aceptar();

    // Guardar cambios de amistad
    UsuarioDAO::addAmigo($solicitud->getSolicitante()->getNickname(), $solicitud->getRecibidor()->getNickname());

    // Eliminar solicitud una vez aceptada
    SolicitudDAO::eliminar($solicitud);

   
}
