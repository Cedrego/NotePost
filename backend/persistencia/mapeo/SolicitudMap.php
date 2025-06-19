<?php
require_once __DIR__ . '/../../dominio/SolicitudDeAmistad.php';
require_once __DIR__ . '/../../dominio/Usuario.php';
require_once __DIR__ . '/../DAO/UsuarioDAO.php';

class SolicitudMap {
    public static function mapRowToSolicitud($row): SolicitudAmistad {
        $solicitante = UsuarioDAO::obtenerPorNickname($row['solicitante']);
        $recibidor = UsuarioDAO::obtenerPorNickname($row['recibidor']);

        $solicitud = new SolicitudAmistad($solicitante, $recibidor);
        $solicitud->setAceptada((bool)$row['aceptada']);
        return $solicitud;
    }

    public static function mapSolicitudToArray(SolicitudAmistad $solicitud): array {
        return [
            'solicitante' => $solicitud->getSolicitante()->getNickname(),
            'recibidor'   => $solicitud->getRecibidor()->getNickname(),
            'aceptada'    => $solicitud->isAceptada() ? 1 : 0
        ];
    }
}
