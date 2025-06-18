<?php
class Usuario {
    private string $nickname;
    private string $email;
    private string $nombre;
    private string $apellido;
    private string $contrasena;
    private ?SolicitudAmistad $solicitudEnviada = null;
    private ?SolicitudAmistad $solicitudRecibida = null;
    private ?int $avatar;
    /** @var Usuario[] */
    private array $amigos = [];
    /** @var Post[] */
    private array $posts = [];
    //Parametros para el tema del hash de la contraseña
    private bool $yaHasheada;
    
    public function __construct(string $nickname, string $email, string $nombre, string $apellido, string $contrasena, ?int $avatar = null, bool $yaHasheada = false) {
        $this->nickname   = $nickname;
        $this->email      = $email;
        $this->nombre     = $nombre;
        $this->apellido   = $apellido;
        $this->avatar     = $avatar;
        $this->contrasena = $yaHasheada ? $contrasena : password_hash($contrasena, PASSWORD_DEFAULT);
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
    public function getSolicitudEnviada(): ?SolicitudAmistad { return $this->solicitudEnviada; }
    public function setSolicitudEnviada(SolicitudAmistad $sol): void { $this->solicitudEnviada = $sol; }

    public function getSolicitudRecibida(): ?SolicitudAmistad { return $this->solicitudRecibida; }
    public function setSolicitudRecibida(SolicitudAmistad $sol): void { $this->solicitudRecibida = $sol; }

    public function getAvatar(): ?int { return $this->avatar; }
    public function setAvatar(?int $avatar): void { $this->avatar = $avatar; }

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
                return true; // Post encontrado y eliminado
            }
        }
        return false; // Post no encontrado
    }

    //Retorna un usuario por su nickname y verifica si la contraseña es correcta
    public static function ConfirmarExistencia($conn, $nickname, $contrasenaIngresada): ?Usuario {
        $stmt = $conn->prepare("SELECT email, nombre, apellido, contrasena, avatar FROM usuarios WHERE nickname = ?");
        $stmt->bind_param("s", $nickname);
        $stmt->execute();
        $email = $nombre = $apellido = $contrasenaHash = null;
        $avatarId = null;
        $stmt->bind_result($email, $nombre, $apellido, $contrasenaHash, $avatarId);

        if ($stmt->fetch()) {
            if (
                $contrasenaHash !== null && 
                $email !== null && 
                $nombre !== null && 
                $apellido !== null &&
                password_verify($contrasenaIngresada, $contrasenaHash)
            ) {
                // Indica que la contraseña ya está hasheada
                return new Usuario($nickname, (string)$email, (string)$nombre, (string)$apellido, (string)$contrasenaHash, $avatarId, true);
            }
        }
        return null; // Usuario no encontrado o contraseña incorrecta
    }

    //busca al usuario que tenga ese nickname en la base de datos
    public static function obtenerPorNickname($conn, $nickname): ?Usuario {
        $stmt = $conn->prepare("SELECT email, nombre, apellido, contrasena, avatar FROM usuario WHERE nickname = ?");
        $stmt->bind_param("s", $nickname);
        $stmt->execute();
        $email = $nombre = $apellido = $contrasena = null;
        $avatarId = null;
        $stmt->bind_result($email, $nombre, $apellido, $contrasena, $avatarId);

        if ($stmt->fetch()) {
            return new Usuario($nickname, (string)$email, (string)$nombre, (string)$apellido, (string)$contrasena, $avatarId);
        } else {
            return null; // No encontrado
        }
    }

    /*public function enviarSolicitud(Usuario $dest): SolicitudAmistad {
        $sol = new SolicitudAmistad($this, $dest);
        $this->solicitudEnviada = $sol;
        $dest->setSolicitudRecibida($sol);
        return $sol;
    }*/

    public function agregarPostUsuario(Post $post, $conn): void {
        //por ahora es manual, esto en teoria inserta el post_id con el nick del usuario
        $stmt = $conn->prepare("INSERT INTO usuario_post (nickname, idPost) VALUES (?, ?)");
        $postId = $post->getId();
        $stmt->bind_param("si", $this->nickname, $postId);
        $stmt->execute();
    }

    public function cambiarFondoPost(int $idPost, int $nuevoFondoId): bool {
        foreach ($this->posts as $post) {
            if ($post->getId() === $idPost) {
                $post->setFondoId($nuevoFondoId);
                return true;
            }
        }
        return false;
    }
}
?>
