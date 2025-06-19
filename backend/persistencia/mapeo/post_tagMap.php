<?php
require_once __DIR__ . '/../../dominio/post_tag.php';

class post_tagMap {
// Convierte una fila de BD a objeto PostTag
    public static function mapRowToPostTag(array $row): post_tag {
        return new post_tag(
            (int)$row['post_id'],
            $row['tag_id']
        );
    }

    // Convierte un objeto PostTag a array para insert/update
    public static function mapPostTagToArray(post_tag $postTag): array {
        return [
            'post_id' => $postTag->getPostId(),
            'tag_id'  => $postTag->getTagNombre()
        ];
    }
}