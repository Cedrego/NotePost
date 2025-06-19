<?php
require_once 'Tag.php';
require_once 'Post.php';

class post_tag {
    private int $postId;
    private String $tagNombre;

    public function __construct(int $postId, string $tagNombre) {
        $this->postId = $postId;
        $this->tagNombre = $tagNombre;
    }

    public function getPostId(): int {
        return $this->postId;
    }

    public function setPostId(int $postId): void {
        $this->postId = $postId;
    }

    public function getTagNombre(): string {
        return $this->tagNombre;
    }

    public function setTagNombre(string $tagNombre): void {
        $this->tagNombre = $tagNombre;
    }
}