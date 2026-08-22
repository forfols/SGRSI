<?php

class VerificarEstado
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function verificarEstado($idIncidencia)
    {
        $sql = "
        SELECT E.tipo
        FROM REGISTROINCIDENCIA RI
        INNER JOIN ESTADO E
            ON RI.idEstado = E.id
        WHERE RI.id = :idIncidencia";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute(["idIncidencia" => $idIncidencia]);

        return $consulta->fetch(PDO::FETCH_ASSOC);
    }
}