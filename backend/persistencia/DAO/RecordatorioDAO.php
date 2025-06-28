<?php
require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../mapeo/RecordatorioMap.php';

class RecordatorioDAO {


    // Insertar un recordatorio asociado a un post
    public static function insert(Recordatorio $recordatorio): bool {
        $conn = Conexion::getConexion();
        $sql = "INSERT INTO recordatorios (post_id, fechaRecordatorio) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);

        $data = RecordatorioMap::mapRecordatorioToArray($recordatorio);

        $stmt->bind_param(
            "is", // post_id INT, fechaRecordatorio STRING
            $data['post_id'],
            $data['fechaRecordatorio']
        );

        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

   public function getRecordatoriosByPostId(int $postId): array {
        $sql = "SELECT r.fechaRecordatorio, p.id as post_id, p.contenido, p.privado, p.likes, p.dislikes, p.fechaPost,
                    u.nickname, u.email, u.nombre, u.apellido, u.contrasena
                FROM recordatorios r
                JOIN posts p ON r.post_id = p.id
                JOIN usuarios u ON p.autor_nick = u.nickname
                WHERE r.post_id = :post_id";
        $conn = Conexion::getConexion();
        $stmt = $conn->prepare($sql);
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
        $conn = Conexion::getConexion();
        $sql = "DELETE FROM recordatorios WHERE post_id = :post_id AND fechaRecordatorio = :fecha";
        $stmt = $conn->prepare($sql);

        $data = RecordatorioMap::mapRecordatorioToArray($recordatorio);

        return $stmt->execute([
            ':post_id' => $data['post_id'],
            ':fecha'   => $data['fechaRecordatorio'],
        ]);
    }

    // Eliminar todos los recordatorios de un post
    public function deleteByPostId(int $postId): bool {
        $conn = Conexion::getConexion();
        $sql = "DELETE FROM recordatorios WHERE post_id = :post_id";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([':post_id' => $postId]);
    }

    public static function obtenerRecordatoriosPorUsuario(string $nickname): array {
        $conn = Conexion::getConexion();
        $sql = "SELECT r.fechaRecordatorio, p.id AS post_id, p.contenido, p.privado, p.likes, p.dislikes, p.fechaPost
                FROM recordatorios r
                JOIN posts p ON r.post_id = p.id
                WHERE p.autor_nickname = ?
                ORDER BY r.fechaRecordatorio ASC";
    
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $nickname);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $recordatorios = [];
        while ($row = $result->fetch_assoc()) {
            $recordatorios[] = [
                'postId' => $row['post_id'],
                'contenido' => $row['contenido'],
                'fechaRecordatorio' => $row['fechaRecordatorio'],
                'privado' => (bool) $row['privado'],
                'likes' => $row['likes'],
                'dislikes' => $row['dislikes'],
                'fechaPost' => $row['fechaPost']
            ];
        }
    
        return $recordatorios;
    }
}
