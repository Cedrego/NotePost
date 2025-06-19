<?php
class Tag {
    private string $tag;

    public function __construct( string $tag) {
        $this->tag  = $tag;
    }

    // Getters y setters
    public function getTag(): string { return $this->tag; }
    public function setTag(string $t): void { $this->tag = $t; }
}
?>
