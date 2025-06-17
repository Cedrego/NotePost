<?php
class Tag {
    private string $tag;
    private Post $post;

    public function __construct(Post $post, string $tag) {
        $this->post = $post;
        $this->tag  = $tag;
    }

    // Getters y setters
    public function getTag(): string { return $this->tag; }
    public function setTag(string $t): void { $this->tag = $t; }
}
?>
