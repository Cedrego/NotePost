<?php
class Tag {
    private Post $post;
    private string $tag;

    public function __construct(Post $post, string $tag) {
        $this->post = $post;
        $this->tag  = $tag;
    }

    // Getters y setters
    public function getPost(): Post { return $this->post; }
    public function setPost(Post $p): void { $this->post = $p; }

    public function getTag(): string { return $this->tag; }
    public function setTag(string $t): void { $this->tag = $t; }
}
?>
