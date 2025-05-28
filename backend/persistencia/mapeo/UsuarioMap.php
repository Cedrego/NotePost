<?php
require_once __DIR__ . '/../../dominio/Usuario.php';

class UsuarioMap {
    // Convierte una fila de BD (array asociativo) a objeto Usuario
    public static function mapRowToUsuario($row) {
        return new Usuario(
            $row['nickname'] ,
            $row['email'] ,
            $row['nombre'] ,
            $row['apellido'] ,
            $row['contrasena'] 
        );
    }
    public static function mapUsuarioToArray(Usuario $usuario): array {
        return [
            'nickname'   => $usuario->getNickname(),
            'email'      => $usuario->getEmail(),
            'nombre'     => $usuario->getNombre(),
            'apellido'   => $usuario->getApellido(),
            'contrasena' => $usuario->getContrasena(),
        ];
    }
}