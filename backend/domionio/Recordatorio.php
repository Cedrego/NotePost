<?php
class Recordatorio {
    private Post $post;
    private DateTime $fechaRecordatorio;

    public function __construct(Post $post, DateTime $fecha) {
        $this->post = $post;
        $this->fechaRecordatorio = $fecha;
    }

    // Getters y setters
    public function getPost(): Post { return $this->post; }
    public function setPost(Post $p): void { $this->post = $p; }

    public function getFechaRecordatorio(): DateTime { return $this->fechaRecordatorio; }
    public function setFechaRecordatorio(DateTime $f): void { $this->fechaRecordatorio = $f; }
}
?>
