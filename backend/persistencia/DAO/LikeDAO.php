<?php
require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../mapeo/LikeMap.php';

class LikeDAO {

    // Insertar un like/dislike
    public static function guardar(Like $like): void {
        $conn = Conexion::getConexion();
        $sql = "INSERT INTO likes (id_post, usuario, accion) VALUES (?, ?, ?)";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Error al preparar: " . $conn->error);
        }

        $data = LikeMap::mapLikeToArray($like);

        $stmt->bind_param(
            "iss",
            $data['id_post'],
            $data['usuario'],
            $data['accion']
        );

        if (!$stmt->execute()) {
            die("Error al ejecutar: " . $stmt->error);
        }

        $stmt->close();
    }

    // Obtener todos los likes de un post
    public static function obtenerPorPost(int $idPost): array {
        $conn = Conexion::getConexion();
        $sql = "SELECT * FROM likes WHERE id_post = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idPost);
        $stmt->execute();
        $res = $stmt->get_result();

        $likes = [];
        while ($row = $res->fetch_assoc()) {
            $likes[] = LikeMap::mapRowToLike($row);
        }
        $stmt->close();
        return $likes;
    }

    // Obtener el like de un usuario en un post (si existe)
    public static function obtenerPorUsuarioYPost(int $idPost, string $usuario): ?Like {
        $conn = Conexion::getConexion();
        $sql = "SELECT * FROM likes WHERE id_post = ? AND usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $idPost, $usuario);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($row = $res->fetch_assoc()) {
            $stmt->close();
            return LikeMap::mapRowToLike($row);
        }
        $stmt->close();
        return null;
    }

    // Eliminar un like/dislike
    public static function eliminar(Like $like): void {
        $conn = Conexion::getConexion();
        $sql = "DELETE FROM likes WHERE id_post = ? AND usuario = ?";
        $stmt = $conn->prepare($sql);
        $data = LikeMap::mapLikeToArray($like);
        $stmt->bind_param("is", $data['id_post'], $data['usuario']);
        $stmt->execute();
        $stmt->close();
    }

     public function obtenerAccion(int $idPost, string $usuario): ?string {
        $conn = Conexion::getConexion();
        $stmt = $conn->prepare("SELECT accion FROM likes WHERE id_post = ? AND usuario = ?");
        $stmt->bind_param("is", $idPost, $usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return $row['accion'];
        }
        return null;
    }

    public function eliminarVoto(int $idPost, string $usuario): void {
        $conn = Conexion::getConexion();
        $stmt = $conn->prepare("DELETE FROM likes WHERE id_post = ? AND usuario = ?");
        $stmt->bind_param("is", $idPost, $usuario);
        $stmt->execute();
    }

    public function insertarVoto(int $idPost, string $usuario, string $accion): void {
        $conn = Conexion::getConexion();
        $stmt = $conn->prepare("INSERT INTO likes (id_post, usuario, accion) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $idPost, $usuario, $accion);
        $stmt->execute();
    }

    public function actualizarVoto(int $idPost, string $usuario, string $accion): void {
        $conn = Conexion::getConexion();
        $stmt = $conn->prepare("UPDATE likes SET accion = ? WHERE id_post = ? AND usuario = ?");
        $stmt->bind_param("sis", $accion, $idPost, $usuario);
        $stmt->execute();
    }

}
