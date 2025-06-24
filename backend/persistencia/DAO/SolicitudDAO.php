<?php
require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../mapeo/SolicitudMap.php';

class SolicitudDAO {


    public static function obtenerSolicitudesRecibidas(string $nickname): array {
        $conn = Conexion::getConexion();
        $sql = "SELECT * FROM solicitudes WHERE recibidor = ? AND aceptada = 0";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $nickname);
        $stmt->execute();
        $result = $stmt->get_result();

        $solicitudes = [];
        while ($row = $result->fetch_assoc()) {
            $solicitudes[] = SolicitudMap::mapRowToSolicitud($row);
        }
        return $solicitudes;
    }

    public static function guardar(SolicitudAmistad $s) {

        $conn = Conexion::getConexion();
        $sql = "INSERT INTO solicitudes (solicitante, recibidor, aceptada) VALUES (?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $data = SolicitudMap::mapSolicitudToArray($s);

        $stmt->bind_param("ssi", $data['solicitante'], $data['recibidor'], $data['aceptada']);
        $stmt->execute();
        $stmt->close();
    }

    public static function eliminar(SolicitudAmistad $s) {
        $conn = Conexion::getConexion();
        $sql = "DELETE FROM solicitudes WHERE solicitante = ? AND recibidor = ?";
        $stmt = $conn->prepare($sql);

        $stmt->bind_param("ss", $s->getSolicitante()->getNickname(), $s->getRecibidor()->getNickname());
        $stmt->execute();
        $stmt->close();
    }

    public static function obtener(String $nickSolicitante, String $nickRecibidor): ?SolicitudAmistad {
        $conn = Conexion::getConexion();
        $sql = "SELECT * FROM solicitudes WHERE solicitante = ? AND recibidor = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $nickSolicitante, $nickRecibidor);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return SolicitudMap::mapRowToSolicitud($row);
        }
        return null;
    }
}
