<?php
require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../mapeo/FondoMap.php';

class FondoDAO {
    
    public static function obtenerPorId(int $id): ?Fondo {
        $conn = Conexion::getConexion();
        $sql = "SELECT * FROM fondo WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Error al preparar: " . $conn->error);
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return FondoMap::mapRowToFondo($row);
        }
        return null;
    }

    public static function guardar(Fondo $fondo): void {
        $conn = Conexion::getConexion();
        $sql = "INSERT INTO fondo (id, rutaImagen) VALUES (?, ?)
                ON DUPLICATE KEY UPDATE rutaImagen = VALUES(rutaImagen)";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Error al preparar: " . $conn->error);
        }

        $data = FondoMap::mapFondoToArray($fondo);

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
        $sql = "DELETE FROM fondo WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Error al preparar: " . $conn->error);
        }

        $stmt->bind_param("i", $id);
        
        if (!$stmt->execute()) {
            die("Error al ejecutar: " . $stmt->error);
        }

        return true;
    }
     public static function obtenerTodos(): array {
        $conn = Conexion::getConexion();
        $sql = "SELECT * FROM fondo";
        $result = $conn->query($sql);

        $Fondos = [];
        while ($row = $result->fetch_assoc()) {
            $Fondos[] = FondoMap::mapRowToFondo($row);
        }
        return $Fondos;
    }
}