<?php
require_once __DIR__ . '/../../dominio/Tag.php';

class TagMap {
    // Convierte una fila de BD a objeto Tag
    public static function mapRowToTag(array $row): Tag {
        return new Tag($row['nombre']);
    }

    // Convierte un objeto Tag a array para insert/update
    public static function mapTagToArray(Tag $tag): array {
        return [
            'nombre' => $tag->getTag(),
        ];
    }
}
