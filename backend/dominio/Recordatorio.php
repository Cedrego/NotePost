<?php
class Recordatorio {
    private DateTime $fechaRecordatorio;
    private Post $post;
    
    public function __construct(Post $post, DateTime $fecha) {
        $this->post = $post; // <-- Agregar esto
        $this->fechaRecordatorio = $fecha;
    }

    // Getters y setters
    public function getPost(): Post { return $this->post; } // <-- Agregar esto
    public function setPost(Post $p): void { $this->post = $p; } // <-- Opcional
    public function getFechaRecordatorio(): DateTime { return $this->fechaRecordatorio; }
    public function setFechaRecordatorio(DateTime $f): void { $this->fechaRecordatorio = $f; }
}
?>
