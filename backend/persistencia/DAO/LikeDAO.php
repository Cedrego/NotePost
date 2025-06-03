<?php
require_once __DIR__ . '/../Conexion.php';

class LikeDAO {
    private $conn;

    public function __construct() {
        $this->conn = Conexion::getConexion();
        $this->crearTablaSiNoExiste();
    }

    private function crearTablaSiNoExiste(): void {
        $sql = "CREATE TABLE IF NOT EXISTS likes (
            id_post INT NOT NULL,
            usuario VARCHAR(255) NOT NULL,
            accion ENUM('like', 'dislike') NOT NULL,
            PRIMARY KEY (id_post, usuario),
            FOREIGN KEY (id_post) REFERENCES posts(id) ON DELETE CASCADE,
            FOREIGN KEY (usuario) REFERENCES usuarios(nickname) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $this->conn->query($sql);
    }

    public function obtenerAccion(int $idPost, string $usuario): ?string {
        $stmt = $this->conn->prepare("SELECT accion FROM likes WHERE id_post = ? AND usuario = ?");
        $stmt->bind_param("is", $idPost, $usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return $row['accion'];
        }
        return null;
    }

    public function eliminarVoto(int $idPost, string $usuario): void {
        $stmt = $this->conn->prepare("DELETE FROM likes WHERE id_post = ? AND usuario = ?");
        $stmt->bind_param("is", $idPost, $usuario);
        $stmt->execute();
    }

    public function insertarVoto(int $idPost, string $usuario, string $accion): void {
        $stmt = $this->conn->prepare("INSERT INTO likes (id_post, usuario, accion) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $idPost, $usuario, $accion);
        $stmt->execute();
    }

    public function actualizarVoto(int $idPost, string $usuario, string $accion): void {
        $stmt = $this->conn->prepare("UPDATE likes SET accion = ? WHERE id_post = ? AND usuario = ?");
        $stmt->bind_param("sis", $accion, $idPost, $usuario);
        $stmt->execute();
    }
}
?>
