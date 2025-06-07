<?php
class Recordatorio {
    private DateTime $fechaRecordatorio;

    public function __construct(Post $post, DateTime $fecha) {
        $this->fechaRecordatorio = $fecha;
    }

    // Getters y setters
    public function getFechaRecordatorio(): DateTime { return $this->fechaRecordatorio; }
    public function setFechaRecordatorio(DateTime $f): void { $this->fechaRecordatorio = $f; }
}
?>
