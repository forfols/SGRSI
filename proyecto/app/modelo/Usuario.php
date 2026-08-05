<?php

class Usuario {
    private string $ci;
    private string $contra;
    private string $nombre;
    private bool $solicitante;
    private bool $tecnico;
    private bool $administrador;
    private bool $activo;


    public function __construct(string $ci, string $contra, string $nombre, bool $activo, bool $solicitante, bool $tecnico, bool $administrador) {
        $this->ci = $ci;
        $this->contra = $contra;
        $this->nombre = $nombre;
        $this->activo = $activo;
        $this->solicitante = $solicitante;
        $this->tecnico = $tecnico;
        $this->administrador = $administrador;
    }

    public function getCi(): string {
        return $this->ci;
    }

    public function getContra(): string {
        return $this->contra;
    }

    public function getNombre(): string{
        return $this->nombre;
    }

    public function estaActivo(): bool {
        return $this->activo;
    }

    public function getRolSolicitante(): bool {
        return $this->solicitante;
    }

    public function getRolTecnico(): bool {
        return $this->tecnico;
    }

    public function getRolAdministrador(): bool {
        return $this->administrador;
    }

}

?>