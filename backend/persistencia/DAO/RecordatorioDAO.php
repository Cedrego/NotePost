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
/*
   public static function getRecordatoriosByPostId(int $postId): array {
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

        /** @var array $row *//*
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $recordatorios[] = RecordatorioMap::mapRowToRecordatorio($row);
        }

        return $recordatorios;
    }*/

    public static function getRecordatoriosPorPostId(int $postId): array {
        $conn = Conexion::getConexion();
        $sql = "SELECT p.*, 
                    p.autor_nickname AS autor_nickname, 
                    u.email AS autor_email, 
                    u.nombre AS autor_nombre, 
                    u.apellido AS autor_apellido, 
                    u.contrasena AS autor_contrasena, 
                    r.fechaRecordatorio
                FROM recordatorios r
                JOIN posts p ON r.post_id = p.id
                JOIN usuarios u ON p.autor_nickname = u.nickname
                WHERE r.post_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $recordatorios = [];
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) {
            $recordatorios[] = RecordatorioMap::mapRowToRecordatorio($row);
        }
        $stmt->close();
        return $recordatorios;
    }


    // Eliminar un recordatorio específico
    public static function delete(Recordatorio $recordatorio): bool {
        $conn = Conexion::getConexion();
        $sql = "DELETE FROM recordatorios WHERE post_id = ? AND fechaRecordatorio = ?";
        $stmt = $conn->prepare($sql);
        $data = RecordatorioMap::mapRecordatorioToArray($recordatorio);
        $stmt->bind_param("is", $data['post_id'], $data['fechaRecordatorio']);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    // Eliminar todos los recordatorios de un post
    public static function deleteByPostId(int $postId): bool {
        $conn = Conexion::getConexion();
        $sql = "DELETE FROM recordatorios WHERE post_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $postId);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
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

    public static function actualizarFechaRecordatorioPorPostId($postId, $nuevaFecha): void {
        $conn = Conexion::getConexion();
        $sql = "UPDATE recordatorios SET fechaRecordatorio = ? WHERE post_id = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Error al preparar la consulta: " . $conn->error);
        }

        $stmt->bind_param("si", $nuevaFecha, $postId);
        $stmt->execute();
        $stmt->close();
    }

}
