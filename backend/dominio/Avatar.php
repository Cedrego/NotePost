<?php
class Avatar {
    private int $id;
    private string $rutaImagen;

    public function __construct(int $id, string $rutaImagen) {
        $this->id = $id;
        $this->rutaImagen = $rutaImagen;
    }

    // Getters y setters
    public function getId(): int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getRutaImagen(): string { return $this->rutaImagen; }
    public function setRutaImagen(string $ruta): void { $this->rutaImagen = $ruta; }
}
?>
