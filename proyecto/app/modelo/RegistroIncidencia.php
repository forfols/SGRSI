<?php

class RegistroIncidencia
{

    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

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