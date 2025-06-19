<?php
require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../mapeo/PostMap.php';

class PostDAO {
    public function __construct() {
        // Constructor vacío, no es necesario inicializar nada
    }
    public static function obtenerPorId(int $id): ?Post {
        $conn = Conexion::getConexion();
        $stmt = $conn->prepare(
            "SELECT p.*, u.nickname AS autor_nickname, u.email AS autor_email, u.nombre AS autor_nombre, u.apellido AS autor_apellido, u.contrasena AS autor_contrasena
             FROM posts p
             JOIN usuarios u ON p.autor_nickname = u.nickname
             WHERE p.id = ?"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return PostMap::mapRowToPost($row);
        }
        return null;
    }


    public static function guardar(Post $post): void {
        $data = PostMap::mapPostToArray($post);
        $conn = Conexion::getConexion();

        $sql = "INSERT INTO posts (autor_nickname, contenido, likes, dislikes, fechaPost, privado) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssiisi",
            $data['autor_nickname'],
            $data['contenido'],
            $data['likes'],
            $data['dislikes'],
            $data['fechaPost'],
            $data['privado']
        );
        $stmt->execute();

        $postId = $conn->insert_id;
        $post->setId($postId);
        /*
        // Guardar recordatorios
        foreach ($post->getRecordatorios() as $rec) {
            $fechaStr = $rec->getFechaRecordatorio()->format('Y-m-d H:i:s');
            $stmtRec = $conn->prepare("INSERT INTO recordatorios (post_id, fechaRecordatorio) VALUES (?, ?)");
            $stmtRec->bind_param("is", $postId, $fechaStr);
            $stmtRec->execute();
        }

        // Guardar tags y relaciones post_tag
        foreach ($post->getTags() as $tag) {
            // 1. Insertar el tag si no existe
            $stmtTag = $conn->prepare("INSERT IGNORE INTO tags (nombre) VALUES (?)");
            $nombreTag = $tag->getTag();
            $stmtTag->bind_param("s", $nombreTag);
            $stmtTag->execute();
            $stmtTag->close();

            // 2. Insertar la relación en post_tag
            $stmtPostTag = $conn->prepare("INSERT INTO post_tag (post_id, tag_id) VALUES (?, ?)");
            $stmtPostTag->bind_param("is", $postId, $nombreTag);
            $stmtPostTag->execute();
            $stmtPostTag->close();
        }

        */
    }

    public static function actualizar(Post $post): void {
        $data = PostMap::mapPostToArray($post);
        $conn = Conexion::getConexion();
        $sql = "UPDATE posts SET contenido = ?, likes = ?, dislikes = ?, fechaPost = ?, privado = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "siisii",
            $data['contenido'],
            $data['likes'],
            $data['dislikes'],
            $data['fechaPost'],
            $data['privado'],
            $data['id']
        );
        $stmt->execute();
    }

    public static function eliminar(int $id, RecordatorioDAO $recordatorioDAO): void {
        $conn = Conexion::getConexion();
        $post = PostDAO::obtenerPorId($id);
        if (!$post) return;

        $recordatorioDAO->deleteByPostId($id);
        
        $autor = $post->getAutor();
        $autor->olvidarPost($id);

        $stmt =$conn->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }


    // Get Ranking (top 10)
        public static function getRanking(): array {
            $conn = Conexion::getConexion();
        $sql = "SELECT p.*, 
                    u.nickname AS autor_nickname, 
                    u.email AS autor_email, 
                    u.nombre AS autor_nombre, 
                    u.apellido AS autor_apellido, 
                    u.contrasena AS autor_contrasena
                FROM posts p
                JOIN usuarios u ON p.autor_nickname = u.nickname
                ORDER BY p.likes DESC
                LIMIT 10";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $topPosts = [];
        while ($row = $result->fetch_assoc()) {
            $topPosts[] = PostMap::mapRowToPost($row);
        }

        return $topPosts;
    }

    public static function getRankingJson(): string {
        $posts = PostDAO::getRanking();
        $data = [];

        foreach ($posts as $post) {
            $data[] = [
                'id'         => $post->getId(),
                'autor'      => $post->getAutor()->getNickname(),
                'contenido'  => $post->getContenido(),
                'likes'      => $post->getLikes(),
                'dislikes'   => $post->getDislikes(),
                'fechaPost'  => $post->getFechaPost()->format('Y-m-d H:i:s'),
                'privado'    => $post->isPrivado()
            ];
        }
        return json_encode($data, JSON_PRETTY_PRINT);
    }

    
}
