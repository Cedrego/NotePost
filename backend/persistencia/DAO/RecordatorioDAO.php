<?php
require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../mapeo/RecordatorioMap.php';

class RecordatorioDAO {
    private $conn;

    public function __construct() {
       $this->conn = Conexion::getConexion();
    }

    // Insertar un recordatorio asociado a un post
    public function insert(Recordatorio $recordatorio): bool {
        $sql = "INSERT INTO recordatorios (post_id, fechaRecordatorio) VALUES (:post_id, :fecha)";
        $stmt = $this->conn->prepare($sql);

        $data = RecordatorioMap::mapRecordatorioToArray($recordatorio);

        return $stmt->execute([
            ':post_id' => $data['post_id'],
            ':fecha'   => $data['fechaRecordatorio'],
        ]);
    }

   public function getRecordatoriosByPostId(int $postId): array {
        $sql = "SELECT r.fechaRecordatorio, p.id as post_id, p.contenido, p.privado, p.likes, p.dislikes, p.fechaPost,
                    u.nickname, u.email, u.nombre, u.apellido, u.contrasena
                FROM recordatorios r
                JOIN posts p ON r.post_id = p.id
                JOIN usuarios u ON p.autor_nick = u.nickname
                WHERE r.post_id = :post_id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':post_id' => $postId]);

        $recordatorios = [];

        /** @var array $row */
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $recordatorios[] = RecordatorioMap::mapRowToRecordatorio($row);
        }

        return $recordatorios;
    }


    // Eliminar un recordatorio específico
    public function delete(Recordatorio $recordatorio): bool {
        $sql = "DELETE FROM recordatorios WHERE post_id = :post_id AND fechaRecordatorio = :fecha";
        $stmt = $this->conn->prepare($sql);

        $data = RecordatorioMap::mapRecordatorioToArray($recordatorio);

        return $stmt->execute([
            ':post_id' => $data['post_id'],
            ':fecha'   => $data['fechaRecordatorio'],
        ]);
    }

    // Eliminar todos los recordatorios de un post
    public function deleteByPostId(int $postId): bool {
        $sql = "DELETE FROM recordatorios WHERE post_id = :post_id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':post_id' => $postId]);
    }
}
