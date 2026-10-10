<?php

class EquipoDAO
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    public function listarEquipos(): array
    {
        $consulta = $this->conexion->query("SELECT * FROM EQUIPO");
        $equipos = $consulta->fetchAll(PDO::FETCH_ASSOC);

        $consulta = null;

        return $equipos;
    }
}