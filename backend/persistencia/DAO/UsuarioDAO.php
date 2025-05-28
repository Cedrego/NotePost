<?php
require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../mapeo/UsuarioMap.php';

class UsuarioDAO {
    public static function obtenerTodos(): array {
        $conn = Conexion::getConexion();
        $stmt = $conn->query("SELECT * FROM usuarios");
        $usuarios = [];

        while ($row = $stmt->fetch_assoc()) {
            $usuarios[] = UsuarioMap::mapRowToUsuario($row);
        }

        return $usuarios;
    }

    public static function guardar(Usuario $usuario): void {
        $conn = Conexion::getConexion();
        $sql = "INSERT INTO usuarios (nickname, email, nombre, apellido, contrasena)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Error al preparar: " . $conn->error);
        }

        $data = UsuarioMap::mapUsuarioToArray($usuario);

        $stmt->bind_param(
            "sssss", // 5 strings
            $data['nickname'],
            $data['email'],
            $data['nombre'],
            $data['apellido'],
            $data['contrasena']
        );

        if (!$stmt->execute()) {
            die("Error al ejecutar: " . $stmt->error);
        }

        $stmt->close();
    }
}
