<?php
require_once __DIR__ . '/../../dominio/Recordatorio.php';
require_once __DIR__ . '/PostMap.php'; // Para mapear el Post dentro del Recordatorio

class RecordatorioMap {
    // Mapea una fila de BD a un objeto Recordatorio
    public static function mapRowToRecordatorio(array $row): Recordatorio {
        $post = PostMap::mapRowToPost($row); // Si en la fila ya viene info del post
        $fecha = new DateTime($row['fechaRecordatorio']);

        return new Recordatorio($post, $fecha);
    }

    // Convierte un objeto Recordatorio a array para insert/update
    public static function mapRecordatorioToArray(Recordatorio $rec): array {
        return [
            'post_id' => $rec->getPost()->getId(),
            'fechaRecordatorio' => $rec->getFechaRecordatorio()->format('Y-m-d H:i:s'),
        ];
    }
}
?>
