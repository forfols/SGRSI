<?php

/**
 * Recupera el tipo de estado asociado a una incidencia determinada.
 *
 * @class VerificarEstado
 */
class VerificarEstado
{

    /** Conexión activa a la base de datos. */
    private $conexion;

    /**
     * Constructor parametrizado.
     *
     * @param PDO $conexion Conexión a la base de datos.
     */
    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    /**
     * Consulta el estado de una incidencia a partir de su identificador. El
     * método ejecuta la consulta y guarda el resultado en la variable local
     * $incidencia, pero no lo retorna: actualmente no devuelve ningún valor al
     * invocante. Debería agregarse el return correspondiente.
     *
     * @param int $idIncidencia Identificador de la incidencia a consultar.
     * @return void
     */
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

        $incidencia = $consulta->fetch(PDO::FETCH_ASSOC);
    }
}