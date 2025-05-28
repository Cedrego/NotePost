<?php
require_once __DIR__ . '/../../dominio/Post.php';
require_once __DIR__ . '/../../dominio/Usuario.php';

class PostMap {
    // Convierte un row (array asociativo) de BD a objeto Post
    public static function mapRowToPost(array $row): Post {
        $autor = new Usuario(
            $row['autor_nickname'],   // Asumiendo que traes este dato del join o consulta
            $row['autor_email'] ?? '',
            $row['autor_nombre'] ?? '',
            $row['autor_apellido'] ?? '',
            $row['autor_contrasena'] ?? ''
        );

        $post = new Post($autor, $row['contenido'], (bool)$row['privado']);
        $post->setId((int)$row['id']);
        $post->setLikes((int)$row['likes']);
        $post->setDislikes((int)$row['dislikes']);
        $post->setFechaPost(new DateTime($row['fechaPost']));

        return $post;
    }

    // Convierte un objeto Post a un array para insert/update
    public static function mapPostToArray(Post $post): array {
        return [
            'id' => $post->getId(),
            'autor_nickname' => $post->getAutor()->getNickname(),
            'contenido' => $post->getContenido(),
            'likes' => $post->getLikes(),
            'dislikes' => $post->getDislikes(),
            'fechaPost' => $post->getFechaPost()->format('Y-m-d H:i:s'),
            'privado' => $post->isPrivado() ? 1 : 0,
        ];
    }
}
