<?php
require_once __DIR__ . '/../../dominio/Like.php';
require_once __DIR__ . '/../DAO/UsuarioDAO.php';

class LikeMap {
    // Convierte una fila de BD a objeto Like
    public static function mapRowToLike(array $row): Like {
        $usuario = UsuarioDAO::obtenerPorNickname($row['usuario']);
        return new Like(
            (int)$row['id_post'],
            $usuario,
            $row['accion']
        );
    }

    // Convierte un objeto Like a array para insert/update
    public static function mapLikeToArray(Like $like): array {
        return [
            'id_post' => $like->getIdPost(),
            'usuario' => $like->getUsuario()->getNickname(),
            'accion'  => $like->getAccion()
        ];
    }
}