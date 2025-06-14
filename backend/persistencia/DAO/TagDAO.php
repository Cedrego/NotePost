<?php
require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../mapeo/TagMap.php';

class TagDAO {
    private $conn;

    public function __construct() {
       $this->conn = Conexion::getConexion();
    }

    // Insertar un tag asociado a un post
    public function insert(Tag $tag): bool {
        $sql = "INSERT INTO tags (post_id, tag) VALUES (:post_id, :tag)";
        $stmt = $this->conn->prepare($sql);

        $data = TagMap::mapTagToArray($tag);

        return $stmt->execute([
            ':post_id' => $data['post_id'],
            ':tag'     => $data['tag']
        ]);
    }

    public function getAllTags(): array {
        $sql = "SELECT DISTINCT tag FROM tags ORDER BY tag";
        $stmt = $this->conn->query($sql);
        /** @var PDOStatement $stmt */
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }


    // Obtener todos los tags de un post
    public function getTagsByPostId(int $postId): array {
    $sql = "SELECT t.tag, p.id as post_id, p.contenido, p.privado, p.likes, p.dislikes, p.fechaPost, 
                   u.nickname, u.email, u.nombre, u.apellido, u.contrasena
            FROM tags t
            JOIN posts p ON t.post_id = p.id
            JOIN usuarios u ON p.autor_nick = u.nickname
            WHERE t.post_id = :post_id";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute([':post_id' => $postId]);

    $tags = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Crear Usuario
        $usuario = new Usuario(
            $row['nickname'],
            $row['email'],
            $row['nombre'],
            $row['apellido'],
            $row['contrasena']
        );

        // Crear Post
        $post = new Post($usuario, $row['contenido'], (bool)$row['privado']);
        $post->setId((int)$row['post_id']);
        $post->setLikes((int)$row['likes']);
        $post->setDislikes((int)$row['dislikes']);
        $post->setFechaPost(new DateTime($row['fechaPost']));

        // Crear Tag
        $tag = new Tag($post, $row['tag']);

        $tags[] = $tag;
        }
        return $tags;
    }
    public function getPostsByTag(string $tagName): array {
        $sql = "SELECT p.*, u.*
                FROM posts p
                JOIN tags t ON p.id = t.post_id
                JOIN usuarios u ON p.autor_nick = u.nickname
                WHERE t.tag = :tag";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':tag' => $tagName]);

        $posts = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $usuario = new Usuario(
                $row['nickname'],
                $row['email'],
                $row['nombre'],
                $row['apellido'],
                $row['contrasena']
            );

            $post = new Post($usuario, $row['contenido'], (bool)$row['privado']);
            $post->setId((int)$row['id']);
            $post->setLikes((int)$row['likes']);
            $post->setDislikes((int)$row['dislikes']);
            $post->setFechaPost(new DateTime($row['fechaPost']));

            $posts[] = $post;
        }

        return $posts;
    }

    // Eliminar un tag específico de un post
    public function delete(Tag $tag): bool {
        $sql = "DELETE FROM tags WHERE post_id = :post_id AND tag = :tag";
        $stmt = $this->conn->prepare($sql);

        $data = TagMap::mapTagToArray($tag);

        return $stmt->execute([
            ':post_id' => $data['post_id'],
            ':tag'     => $data['tag']
        ]);
    }
}
