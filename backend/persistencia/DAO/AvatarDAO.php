<?php
require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../mapeo/AvatarMap.php';

class AvatarDAO{

    public static function obtenerPorId(int $id): ?Avatar {
        $conn = Conexion::getConexion();
        $sql = "SELECT * FROM avatar WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Error al preparar: " . $conn->error);
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return AvatarMap::mapRowToAvatar($row);
        }
        return null;
    }

    public static function guardar(Avatar $avatar): void {
        $conn = Conexion::getConexion();
        $sql = "INSERT INTO avatar (id, rutaImagen) VALUES (?, ?)
                ON DUPLICATE KEY UPDATE rutaImagen = VALUES(rutaImagen)";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Error al preparar: " . $conn->error);
        }

        $data = AvatarMap::mapAvatarToArray($avatar);

        $stmt->bind_param(
            "is", // id: int, rutaImagen: string
            $data['id'],
            $data['rutaImagen']
        );

        if (!$stmt->execute()) {
            die("Error al ejecutar: " . $stmt->error);
        }

        $stmt->close();
    }

    public static function eliminar(int $id): bool {
        $conn = Conexion::getConexion();
        $sql = "DELETE FROM avatar WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Error al preparar: " . $conn->error);
        }

        $stmt->bind_param("i", $id);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public static function obtenerTodos(): array {
        $conn = Conexion::getConexion();
        $sql = "SELECT * FROM avatar";
        $result = $conn->query($sql);

        $avatares = [];
        while ($row = $result->fetch_assoc()) {
            $avatares[] = AvatarMap::mapRowToAvatar($row);
        }
        return $avatares;
    }
}