<?php
require_once __DIR__ . '/../../dominio/Avatar.php';

class AvatarMap {
    // Convierte una fila de BD (array asociativo) a objeto Avatar
    public static function mapRowToAvatar($row) {
        return new Avatar(
            $row['id'],           // Asegúrate que la columna en la BD es 'id'
            $row['rutaImagen']    // Asegúrate que la columna en la BD es 'rutaImagen'
        );
    }

    // Convierte un objeto Avatar a array asociativo para insertar en BD
    public static function mapAvatarToArray(Avatar $avatar): array {
        return [
            'id'         => $avatar->getId(),
            'rutaImagen' => $avatar->getRutaImagen(),
        ];
    }
}