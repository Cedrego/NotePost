<?php
class Usuario {
    private string $nickname;
    private string $email;
    private string $nombre;
    private string $apellido;
    private string $contrasena;

    private ?Avatar $avatar = null;
    /** @var Usuario[] */
    private array $amigos = [];
    /** @var Post[] */
    private array $posts = [];

    public function __construct(string $nickname, string $email, string $nombre, string $apellido, string $contrasena) {
        $this->nickname   = $nickname;
        $this->email      = $email;
        $this->nombre     = $nombre;
        $this->apellido   = $apellido;
        $this->contrasena = password_hash($contrasena, PASSWORD_DEFAULT);
    }

    //getters y setters
    public function getNickname(): string { return $this->nickname; }
    public function setNickname(string $nickname): void { $this->nickname = $nickname; }

    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): void { $this->email = $email; }

    public function getNombre(): string { return $this->nombre; }
    public function setNombre(string $nombre): void { $this->nombre = $nombre; }

    public function getApellido(): string { return $this->apellido; }
    public function setApellido(string $apellido): void { $this->apellido = $apellido; }

    public function getContrasena(): string { return $this->contrasena; }
    public function setContrasena(string $contrasena): void { 
        $this->contrasena = password_hash($contrasena, PASSWORD_DEFAULT);
    }

    public function getAvatar(): ?Avatar { return $this->avatar; }
    public function setAvatar(Avatar $avatar): void { $this->avatar = $avatar; }

    public function getAmigos(): array { return array_values($this->amigos); }
    public function setAmigos(array $amigos): void { $this->amigos = $amigos; }
    public function addAmigo(Usuario $u): void { $this->amigos[$u->getNickname()] = $u; }

    public function getPosts(): array { return $this->posts; }
    public function setPosts(array $posts): void { $this->posts = $posts; }
    public function addPost(Post $post): void { $this->posts[] = $post; }

    //metodos
    public function enviarSolicitud(Usuario $dest) {
        return new SolicitudAmistad($this, $dest);
    }

    public function olvidarPost(int $postId): bool {
        foreach ($this->posts as $key => $post) {
            if ($post->getId() === $postId) {
                unset($this->posts[$key]);
                // Reindexar el array para evitar "huecos"
                $this->posts = array_values($this->posts);
                return true;
            }
        }
        return false;
    }

}
?>
