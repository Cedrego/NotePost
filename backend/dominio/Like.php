<?php
require_once __DIR__ . '/Usuario.php';
// Clase Like que representa un "me gusta" o "no me gusta" en un post
class Like {
    private int $idPost;
    private Usuario $usuario;
    private string $accion; // 'like' o 'dislike'

    public function __construct(int $idPost, Usuario $usuario, string $accion) {
        $this->idPost = $idPost;
        $this->usuario = $usuario;
        $this->accion = $accion;
    }

    public function getIdPost(): int {
        return $this->idPost;
    }

    public function setIdPost(int $idPost): void {
        $this->idPost = $idPost;
    }

    public function getUsuario(): Usuario {
        return $this->usuario;
    }

    public function setUsuario(Usuario $usuario): void {
        $this->usuario = $usuario;
    }

    public function getAccion(): string {
        return $this->accion;
    }

    public function setAccion(string $accion): void {
        $this->accion = $accion;
    }
}