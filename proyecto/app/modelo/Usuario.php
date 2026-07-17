<?php

class Usuario {
    private string $ci;
    private string $contra;
    private string $rol;
    private bool $activo;


    public function __construct(string $ci, string $contra, bool $activo, string $rol) {
        $this->ci = $ci;
        $this->contra = $contra;
        $this->activo = $activo;
        $this->rol = $rol;
    }

    public function getCi(): string {
        return $this->ci;
    }

    public function getContra(): string {
        return $this->contra;
    }

    public function estaActivo(): bool {
        return $this->activo;
    }

    public function getRol(): string {
        return $this->rol;
    }

}

?>