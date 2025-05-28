<?php
require_once __DIR__ . '/../../dominio/Tag.php';
require_once __DIR__ . '/PostMap.php'; // Para mapear el Post dentro del Tag

class TagMap {
    // Mapea una fila de BD a un objeto Tag
    public static function mapRowToTag(array $row): Tag {
        // Mapeamos el post usando PostMap (asumiendo que la fila tiene datos de post)
        $post = PostMap::mapRowToPost($row);
        $tag  = $row['tag'];

        return new Tag($post, $tag);
    }

    // Convierte un objeto Tag a array para insert/update
    public static function mapTagToArray(Tag $tag): array {
        return [
            'post_id' => $tag->getPost()->getId(),
            'tag'     => $tag->getTag(),
        ];
    }
}
