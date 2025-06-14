<?php
require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../mapeo/PostMap.php';
class PostDAO {
    private $conn;

    public function __construct() {
        $this->conn = Conexion::getConexion();
    }

    public function obtenerPorId(int $id): ?Post {
        $stmt = $this->conn->prepare(
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

    public function guardar(Post $post): void {
    $data = PostMap::mapPostToArray($post);

    $sql = "INSERT INTO posts (autor_nickname, contenido, likes, dislikes, fechaPost, privado) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $this->conn->prepare($sql);
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

    $postId = $this->conn->insert_id;
    $post->setId($postId);

    // Guardar recordatorios
    foreach ($post->getRecordatorios() as $rec) {
        $stmtRec = $this->conn->prepare("INSERT INTO recordatorios (post_id, fechaRecordatorio) VALUES (?, ?)");
        $fechaStr = $rec->getFechaRecordatorio()->format('Y-m-d H:i:s');
        $stmtRec->bind_param("is", $postId, $fechaStr);
        $stmtRec->execute();
    }

    // Guardar tags
    foreach ($post->getTags() as $tag) {
        $stmtTag = $this->conn->prepare("INSERT INTO tags (post_id, tag) VALUES (?, ?)");
        $stmtTag->bind_param("is", $postId, $tag->getTag());
        $stmtTag->execute();
    }
}

    public function actualizar(Post $post): void {
        $data = PostMap::mapPostToArray($post);

        $sql = "UPDATE posts SET contenido = ?, likes = ?, dislikes = ?, fechaPost = ?, privado = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
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

    public function eliminar(int $id, RecordatorioDAO $recordatorioDAO): void {
       
        $post = $this->obtenerPorId($id);
        if (!$post) return;

        $recordatorioDAO->deleteByPostId($id);
        
        $autor = $post->getAutor();
        $autor->olvidarPost($id);

        $stmt = $this->conn->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }


    // Get Ranking (top 10)
        public function getRanking(): array {
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

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $topPosts = [];
        while ($row = $result->fetch_assoc()) {
            $topPosts[] = PostMap::mapRowToPost($row);
        }

        return $topPosts;
    }

    public function getRankingJson(): string {
        $posts = $this->getRanking();
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
