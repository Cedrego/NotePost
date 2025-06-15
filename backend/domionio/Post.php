<?php
class Post {
    private int $id;
    private Usuario $autor;
    private string $contenido;
    private int $likes;
    private int $dislikes;
    private DateTime $fechaPost;
    private bool $privado;
    private ?int $fondoId;

    /** @var Recordatorio[] */
    private array $recordatorios = [];
    /** @var Tag[] */
    private array $tags = [];

    public function __construct(Usuario $autor, string $contenido, bool $privado = false, ?int $fondoId = null) {
        $this->autor     = $autor;
        $this->contenido = $contenido;
        $this->fechaPost = new DateTime();
        $this->privado   = $privado;
        $this->likes = 0;
        $this->dislikes = 0;
        $this->fondoId = $fondoId;
    }

    // Getters y setters
    public function getId(): int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getAutor(): Usuario { return $this->autor; }
    public function setAutor(Usuario $u): void { $this->autor = $u; }

    public function getContenido(): string { return $this->contenido; }
    public function setContenido(string $c): void { $this->contenido = $c; }

    public function getLikes(): int { return $this->likes; }
    public function setLikes(int $l): void { $this->likes = $l; }

    public function getDislikes(): int { return $this->dislikes; }
    public function setDislikes(int $d): void { $this->dislikes = $d; }

    public function getFechaPost(): DateTime { return $this->fechaPost; }
    public function setFechaPost(DateTime $f): void { $this->fechaPost = $f; }

    public function isPrivado(): bool { return $this->privado; }
    public function setPrivado(bool $p): void { $this->privado = $p; }

    public function getRecordatorios(): array { return $this->recordatorios; }
    public function setRecordatorios(array $r): void { $this->recordatorios = $r; }
    public function addRecordatorio(Recordatorio $r): void { $this->recordatorios[] = $r; }

    public function getTags(): array { return $this->tags; }
    public function setTags(array $t): void { $this->tags = $t; }
    public function addTag(Tag $t): void { $this->tags[] = $t; }

    public function getFondoId(): ?int { return $this->fondoId;}
    public function setFondoId(?int $fondoId): void { $this->fondoId = $fondoId;}
    //metodos
    
    public function upvote(): void { $this->likes++; }
    public function downvote(): void { $this->dislikes++; }
}
?>
