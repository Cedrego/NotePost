<?php
require_once __DIR__ . '/../../dominio/Fondo.php';

class FondoMap {
    
    public static function mapRowToFondo(array $row): Fondo {
        return new Fondo(
            (int)$row['id'],
            $row['rutaImagen']
        );
    }

    public static function mapFondoToArray(Fondo $fondo): array {
        return [
            'id' => $fondo->getId(),
            'rutaImagen' => $fondo->getRutaImagen()
        ];
    }
}