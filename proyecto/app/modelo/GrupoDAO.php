<?php

class GrupoDAO
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    public function listarGrupos(): array
    {
        $consulta = $this->conexion->query("SELECT nombre FROM GRUPO ORDER BY nombre");
        $grupos = $consulta->fetchAll(PDO::FETCH_ASSOC);

        $consulta = null;

        return $grupos;
    }
}