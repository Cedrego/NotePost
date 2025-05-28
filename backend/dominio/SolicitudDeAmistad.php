<?php
class SolicitudAmistad {
    private Usuario $solicitante;
    private Usuario $recibidor;
    private bool $aceptada = false;

    public function __construct(Usuario $solicitante, Usuario $recibidor) {
        $this->solicitante = $solicitante;
        $this->recibidor   = $recibidor;
    }

    // Getters y setters
    public function getSolicitante(): Usuario { return $this->solicitante; }
    public function setSolicitante(Usuario $u): void { $this->solicitante = $u; }

    public function getRecibidor(): Usuario { return $this->recibidor; }
    public function setRecibidor(Usuario $u): void { $this->recibidor = $u; }

    public function isAceptada(): bool { return $this->aceptada; }
    public function setAceptada(bool $aceptada): void { $this->aceptada = $aceptada; }

    public function aceptar(): void {
        $this->aceptada = true;
        $this->solicitante->addAmigo($this->recibidor);
        $this->recibidor->addAmigo($this->solicitante);
    }
}
?>
