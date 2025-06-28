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
            "SELECT p.*, 
                    p.fondoid AS fondoid, 
                    u.nickname AS autor_nickname, 
                    u.email AS autor_email, 
                    u.nombre AS autor_nombre, 
                    u.apellido AS autor_apellido, 
                    u.contrasena AS autor_contrasena
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

    public static function eliminar(int $id): bool {
        $conn = Conexion::getConexion();
        $post = PostDAO::obtenerPorId($id);
        if (!$post) return false;

        // $autor = $post->getAutor();
        // $autor->olvidarPost($id);

        $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->bind_param("i", $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
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

    public static function actualizarFondo(int $idpos, int $idFondo): bool {
        $conn = Conexion::getConexion();
        $sql = "UPDATE posts SET fondoid = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Error al preparar: " . $conn->error);
        }
        $stmt->bind_param("ii", $idFondo, $idpos);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public static function obtenerTodos(): array {
        $conn = Conexion::getConexion();
        $sql = "SELECT p.id AS post_id, 
                    p.contenido AS post_contenido, 
                    p.likes AS post_likes, 
                    p.dislikes AS post_dislikes, 
                    p.fechaPost AS post_fechaPost, 
                    p.privado AS post_privado, 
                    p.fondoid AS post_fondoId, 
                    u.nickname AS autor_nickname,
                    u.email AS autor_email,
                    u.nombre AS autor_nombre,
                    u.apellido AS autor_apellido,
                    u.contrasena AS autor_contrasena
                FROM posts p
                JOIN usuarios u ON p.autor_nickname = u.nickname
                WHERE p.privado = 0
                ORDER BY p.fechaPost DESC";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $posts = [];
        while ($row = $result->fetch_assoc()) {
            // Mapear cada fila a un objeto Post
            $usuario = new Usuario(
                $row['autor_nickname'],
                $row['autor_email'],
                $row['autor_nombre'],
                $row['autor_apellido'],
                $row['autor_contrasena']
            );
            $post = new Post(
                $usuario,
                $row['post_contenido'],
                (bool) $row['post_privado'],
                $row['post_fondoId'] ? (int) $row['post_fondoId'] : null
            );
            $post->setId((int) $row['post_id']);
            $post->setLikes((int) $row['post_likes']);
            $post->setDislikes((int) $row['post_dislikes']);
            $post->setFechaPost(new DateTime($row['post_fechaPost']));

            $posts[] = $post;
        }

        return $posts;
    }
    public static function obtenerPostsPorUsuario(string $nick): array {
    $conn = Conexion::getConexion();
    $sql = "SELECT p.id AS post_id, 
                   p.contenido AS post_contenido, 
                   p.likes AS post_likes, 
                   p.dislikes AS post_dislikes, 
                   p.fechaPost AS post_fechaPost, 
                   p.privado AS post_privado, 
                   p.fondoid AS post_fondoId, 
                   u.nickname AS autor_nickname,
                   u.email AS autor_email,
                   u.nombre AS autor_nombre,
                   u.apellido AS autor_apellido,
                   u.contrasena AS autor_contrasena
            FROM posts p
            JOIN usuarios u ON p.autor_nickname = u.nickname
            WHERE p.autor_nickname = ?
            ORDER BY p.fechaPost DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nick);
    $stmt->execute();
    $result = $stmt->get_result();

    $posts = [];
    while ($row = $result->fetch_assoc()) {
        $usuario = new Usuario(
            $row['autor_nickname'],
            $row['autor_email'],
            $row['autor_nombre'],
            $row['autor_apellido'],
            $row['autor_contrasena']
        );
        $post = new Post(
            $usuario,
            $row['post_contenido'],
            (bool) $row['post_privado'],
            $row['post_fondoId'] ? (int) $row['post_fondoId'] : null
        );
        $post->setId((int) $row['post_id']);
        $post->setLikes((int) $row['post_likes']);
        $post->setDislikes((int) $row['post_dislikes']);
        $post->setFechaPost(new DateTime($row['post_fechaPost']));

        $posts[] = $post;
    }

    return $posts;
}

public static function obtenerPostsPublicosPorUsuario(string $nick): array {
    $conn = Conexion::getConexion();
    $sql = "SELECT p.id AS post_id, 
                   p.contenido AS post_contenido, 
                   p.likes AS post_likes, 
                   p.dislikes AS post_dislikes, 
                   p.fechaPost AS post_fechaPost, 
                   p.privado AS post_privado, 
                   p.fondoid AS post_fondoId, 
                   u.nickname AS autor_nickname,
                   u.email AS autor_email,
                   u.nombre AS autor_nombre,
                   u.apellido AS autor_apellido,
                   u.contrasena AS autor_contrasena
            FROM posts p
            JOIN usuarios u ON p.autor_nickname = u.nickname
            WHERE p.autor_nickname = ? AND p.privado = 0
            ORDER BY p.fechaPost DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nick);
    $stmt->execute();
    $result = $stmt->get_result();

    $posts = [];
    while ($row = $result->fetch_assoc()) {
        $usuario = new Usuario(
            $row['autor_nickname'],
            $row['autor_email'],
            $row['autor_nombre'],
            $row['autor_apellido'],
            $row['autor_contrasena']
        );
        $post = new Post(
            $usuario,
            $row['post_contenido'],
            (bool) $row['post_privado'],
            $row['post_fondoId'] ? (int) $row['post_fondoId'] : null
        );
        $post->setId((int) $row['post_id']);
        $post->setLikes((int) $row['post_likes']);
        $post->setDislikes((int) $row['post_dislikes']);
        $post->setFechaPost(new DateTime($row['post_fechaPost']));

        $posts[] = $post;
    }

    return $posts;
}
}
