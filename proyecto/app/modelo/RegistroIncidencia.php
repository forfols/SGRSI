<?php

/**
 * Registra incidencias y recupera el listado completo con sus datos asociados.
 * La incidencia actúa como entidad central: referencia al solicitante,
 * al registro de espacio, al tipo de incidencia y al estado, creados previamente.
 *
 * @class RegistroIncidencia
 */
class RegistroIncidencia
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
     * Registra una nueva incidencia vinculando sus entidades asociadas.
     * Los identificadores recibidos deben existir previamente en sus respectivas tablas.
     *
     * @param int $idRegistroEspacio Identificador del registro de espacio.
     * @param int $idTipoIncidencia Identificador del tipo de incidencia.
     * @param string $ciSolicitante Cédula del usuario que reporta la incidencia.
     * @param int $idEstado Identificador del estado inicial.
     * @return bool TRUE si la inserción se ejecutó correctamente, FALSE en caso contrario.
     */
    public function registrarIncidencia($idRegistroEspacio, $idTipoIncidencia, $ciSolicitante, $idEstado)
    {

        $sql = "INSERT INTO REGISTROINCIDENCIA 
                (ciSolicitante, idRegistroEspacio, idTipoIncidencia, idEstado)
                VALUES (:ciSolicitante, :idRegistroEspacio, :idTipoIncidencia, :idEstado)";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bindParam(":ciSolicitante", $ciSolicitante);
        $stmt->bindParam(":idRegistroEspacio", $idRegistroEspacio, PDO::PARAM_INT);
        $stmt->bindParam(":idTipoIncidencia", $idTipoIncidencia, PDO::PARAM_INT);
        $stmt->bindParam(":idEstado", $idEstado, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Obtiene el listado completo de incidencias con todos sus datos
     * relacionados: solicitante, técnico asignado, espacio y grupo, tipo de
     * incidencia y estado. El JOIN con el técnico es LEFT, porque una incidencia
     * puede no tener técnico asignado; los demás son INNER JOIN.
     *
     * @return array Arreglo asociativo con una fila por incidencia.
     */
    public function listarIncidencias(): array
    {
        $sql = "
            SELECT
                ri.id,
                
                ri.ciSolicitante,
                solicitante.nombre AS nombreSolicitante,
                
                ri.ciTecnico,
                tecnico.nombre AS nombreTecnico,

                re.id AS idRegistroEspacio,
                e.id AS idEspacio,
                e.tipo AS tipoEspacio,
                e.numero AS numeroEspacio,
                g.nombre AS nombreGrupo,
                
                rti.id AS idTipoIncidencia,
                rti.tipo AS tipoIncidencia,
                rti.nroPc,
                rti.alumno,
                rti.descripcion AS descripcionIncidencia,
                
                es.id AS idEstado,
                es.tipo AS tipoEstado,
                es.prioridad,
                es.diagnostico,
                es.soluciones,

                ri.fecha

        FROM REGISTROINCIDENCIA ri

        INNER JOIN USUARIO solicitante
            ON ri.ciSolicitante = solicitante.ci

        LEFT JOIN USUARIO tecnico
            ON ri.ciTecnico = tecnico.ci

        INNER JOIN REGISTROESPACIO re
            ON ri.idRegistroEspacio = re.id

        INNER JOIN ESPACIO e
            ON re.idEspacio = e.id

        INNER JOIN GRUPO g
            ON re.nombreGrupo = g.nombre

        INNER JOIN REGISTROTIPOINCIDENCIA rti
            ON ri.idTipoIncidencia = rti.id

        INNER JOIN ESTADO es
            ON ri.idEstado = es.id;";

        $consulta = $this->conexion->query($sql);

        $incidencias = $consulta->fetchAll(PDO::FETCH_ASSOC);

        $consulta = null;

        return $incidencias;
    }

}