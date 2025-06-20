<?php
require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../mapeo/UsuarioMap.php';

class UsuarioDAO {
    // Agregar la propiedad $posts para evitar el error de propiedad indefinida
    private array $posts = [];

    public static function existeUsuario(string $nickname, string $contrasenaIngresada): ?Usuario {
        $conn = Conexion::getConexion();
        // Usamos la función ConfirmarExistencia de Usuario, que ya verifica el hash
        $usuario = Usuario::ConfirmarExistencia($conn, $nickname, $contrasenaIngresada);
        return $usuario; // Retorna el objeto Usuario si coincide, o null si no
    }

    public static function obtenerTodos(): array {
        $conn = Conexion::getConexion();
        $stmt = $conn->query("SELECT * FROM usuarios");
        $usuarios = [];

        while ($row = $stmt->fetch_assoc()) {
            $usuarios[] = UsuarioMap::mapRowToUsuario($row);
        }

        return $usuarios;
    }
    public static function obtenerAmigos(string $nickname): array {
        $conn = Conexion::getConexion();
        $sql = "SELECT  CASE  WHEN usuario1 = ? THEN usuario2  ELSE usuario1  END AS amigo FROM amigos
                WHERE usuario1 = ? OR usuario2 = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $nickname, $nickname, $nickname);
        $stmt->execute();
        $result = $stmt->get_result();

        $amigos = [];
        while ($row = $result->fetch_assoc()) {
            $amigos[] = $row['amigo'];
        }
        $stmt->close();
        return $amigos;
    }
    public static function addAmigo(string $usuario1, string $usuario2): bool {
        $conn = Conexion::getConexion();

        // Evitar duplicados: siempre guardar el par ordenado alfabéticamente
        $u1 = $usuario1 < $usuario2 ? $usuario1 : $usuario2;
        $u2 = $usuario1 < $usuario2 ? $usuario2 : $usuario1;

        // Verificar si ya existe la amistad
        $check = $conn->prepare("SELECT 1 FROM amigos WHERE usuario1 = ? AND usuario2 = ?");
        $check->bind_param("ss", $u1, $u2);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            $check->close();
            return false; // Ya son amigos
        }
        $check->close();

        // Insertar la amistad
        $stmt = $conn->prepare("INSERT INTO amigos (usuario1, usuario2) VALUES (?, ?)");
        $stmt->bind_param("ss", $u1, $u2);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public static function obtenerPorNickname(string $nickname): ?Usuario {
        $conn = Conexion::getConexion();
        $sql = "SELECT * FROM usuarios WHERE nickname = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Error al preparar: " . $conn->error);
        }

        $stmt->bind_param("s", $nickname);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($row = $res->fetch_assoc()) {
            return UsuarioMap::mapRowToUsuario($row);
        }

        return null;
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

    public function olvidarPost(int $postId): bool {
        foreach ($this->posts as $key => $post) {
            if ($post->getId() === $postId) {
                unset($this->posts[$key]);
                // Reindexar el array para evitar "huecos"
                $this->posts = array_values($this->posts);
                return true;
            }
        }
        return false;
    }

}
