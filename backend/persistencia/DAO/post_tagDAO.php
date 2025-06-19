<?php
require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../mapeo/post_tagMap.php';

class post_tagDAO {

    // Guardar una relación post-tag
    public static function guardar(post_tag $postTag): void {
        $conn = Conexion::getConexion();
        $sql = "INSERT INTO post_tag (post_id, tag_id) VALUES (?, ?)";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Error al preparar: " . $conn->error);
        }

        $data = post_tagMap::mapPostTagToArray($postTag);

        $stmt->bind_param(
            "is", // post_id INT, tag_id VARCHAR
            $data['post_id'],
            $data['tag_id']
        );

        if (!$stmt->execute()) {
            die("Error al ejecutar: " . $stmt->error);
        }

        $stmt->close();
    }

    // Buscar todos los posts que tengan un tag específico
    public static function obtenerPostsPorTag(string $tag_id): array {
        $conn = Conexion::getConexion();
        $sql = "SELECT post_id FROM post_tag WHERE tag_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Error al preparar: " . $conn->error);
        }
        $stmt->bind_param("s", $tag_id);
        $stmt->execute();
        $res = $stmt->get_result();

        $postIds = [];
        while ($row = $res->fetch_assoc()) {
            $postIds[] = $row['post_id'];
        }
        $stmt->close();
        return $postIds;
    }

    // Buscar todos los tags de un post específico
    public static function obtenerTagsPorPost(int $post_id): array {
        $conn = Conexion::getConexion();
        $sql = "SELECT tag_id FROM post_tag WHERE post_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Error al preparar: " . $conn->error);
        }
        $stmt->bind_param("i", $post_id);
        $stmt->execute();
        $res = $stmt->get_result();

        $tags = [];
        while ($row = $res->fetch_assoc()) {
            $tags[] = $row['tag_id'];
        }
        $stmt->close();
        return $tags;
    }
}