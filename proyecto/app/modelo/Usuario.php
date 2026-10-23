<?php

class Usuario
{
    private string $ci;
    private string $nombre;
    private string $claveHash;
    private bool $rolSolicitante;
    private bool $rolTecnico;
    private bool $rolAdministrador;

    public function __construct(
        string $ci,
        string $nombre,
        string $claveHash,
        bool $rolSolicitante,
        bool $rolTecnico,
        bool $rolAdministrador
    ) {
        $this->ci = $ci;
        $this->nombre = $nombre;
        $this->claveHash = $claveHash;
        $this->rolSolicitante = $rolSolicitante;
        $this->rolTecnico = $rolTecnico;
        $this->rolAdministrador = $rolAdministrador;
    }

    public function getCi(): string { return $this->ci; }
    public function getNombre(): string { return $this->nombre; }
    public function getClaveHash(): string { return $this->claveHash; }
    public function getRolSolicitante(): bool { return $this->rolSolicitante; }
    public function getRolTecnico(): bool { return $this->rolTecnico; }
    public function getRolAdministrador(): bool { return $this->rolAdministrador; }
}