<?php
require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../mapeo/TagMap.php';

class TagDAO {

    public static function guardar(Tag $tag): void {
        $conn = Conexion::getConexion();

        // Verifica si ya existe el tag
        $sqlCheck = "SELECT nombre FROM tags WHERE nombre = ?";
        $stmtCheck = $conn->prepare($sqlCheck);
        if (!$stmtCheck) {
            throw new Exception("Error al preparar la consulta: " . $conn->error);
        }
        $nombre = $tag->getTag();
        $stmtCheck->bind_param("s", $nombre);
        $stmtCheck->execute();
        $result = $stmtCheck->get_result();
        if ($result->fetch_assoc()) {
            $stmtCheck->close();
            throw new Exception("El tag '$nombre' ya existe.");
        }
        $stmtCheck->close();

        // Inserta el tag
        $sql = "INSERT INTO tags (nombre) VALUES (?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error al preparar la inserción: " . $conn->error);
        }
        $stmt->bind_param("s", $nombre);
        if (!$stmt->execute()) {
            $stmt->close();
            throw new Exception("Error al insertar el tag: " . $stmt->error);
        }
        $stmt->close();
    }

    // Obtener todos los tags
    public static function getAllTags(): array {
        $conn = Conexion::getConexion();
        $sql = "SELECT nombre FROM tags";
        $result = $conn->query($sql);
        $tags = [];
        while ($row = $result->fetch_assoc()) {
            $tags[] = TagMap::mapRowToTag($row);
        }
        return $tags;
    }

    // Buscar tags por nombre (búsqueda parcial)
    public static function searchTag(string $busqueda): ?Tag {
        $conn = Conexion::getConexion();
        $sql = "SELECT nombre FROM tags WHERE nombre LIKE ? LIMIT 1";
        $like = "%$busqueda%";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $like);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return TagMap::mapRowToTag($row);
        }
        return null;
    }
}

   
    