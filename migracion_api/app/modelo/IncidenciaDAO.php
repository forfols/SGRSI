<?php

/**
 * Clase que unifica todas las operaciones de acceso a datos
 * relacionadas con la entidad Incidencia (buscar, listar, alta, baja, modificación).
 */
class IncidenciaDAO
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    public function listarIncidencias(string $rol, string $ci): array
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
                rti.alumno,
                rti.descripcion AS descripcionIncidencia,
                eq.id AS idEquipo,
                eq.nombre AS nombreEquipo,
                es.id AS idEstado,
                es.tipo AS tipoEstado,
                es.prioridad,
                es.diagnostico,
                es.soluciones,
                ri.fecha

            FROM REGISTROINCIDENCIA AS ri

            INNER JOIN USUARIO AS solicitante
                ON ri.ciSolicitante = solicitante.ci

            LEFT JOIN USUARIO AS tecnico
                ON ri.ciTecnico = tecnico.ci

            INNER JOIN REGISTROESPACIO AS re
                ON ri.idRegistroEspacio = re.id

            INNER JOIN ESPACIO AS e
                ON re.idEspacio = e.id

            INNER JOIN GRUPO AS g
                ON re.nombreGrupo = g.nombre

            INNER JOIN REGISTROTIPOINCIDENCIA AS rti
                ON ri.idTipoIncidencia = rti.id

            LEFT JOIN EQUIPO AS eq
                ON rti.idEquipo = eq.id

            INNER JOIN ESTADO AS es
                ON ri.idEstado = es.id
        ";

        $parametros = [];

        //Cada rol ve solamente las incidencias que le corresponden
        if ($rol === "solicitante") {
            $sql .= " WHERE ri.ciSolicitante = :ci";
            $parametros = ["ci" => $ci];
        } elseif ($rol === "tecnico") {
            $sql .= " WHERE es.tipo = 'Sin asignar' OR ri.ciTecnico = :ci";
            $parametros = ["ci" => $ci];
        }

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute($parametros);

        $incidencias = $consulta->fetchAll(PDO::FETCH_ASSOC);

        $consulta = null;

        return $incidencias;
    }

    public function buscarIncidencia(int $idIncidencia): ?array
    {
        $sql = "
            SELECT
                es.tipo AS tipoEstado,
                ri.ciSolicitante,
                ri.ciTecnico

            FROM REGISTROINCIDENCIA AS ri

            INNER JOIN ESTADO AS es
                ON ri.idEstado = es.id

            WHERE ri.id = :idIncidencia
        ";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute(["idIncidencia" => $idIncidencia]);

        $incidencia = $consulta->fetch(PDO::FETCH_ASSOC);

        $consulta = null;

        if ($incidencia === false) {
            return null;
        }

        return $incidencia;
    }

    public function registrarIncidencia(int $idRegistroEspacio, string $tipo, ?int $idEquipo, ?string $alumno, string $descripcion, string $ciSolicitante): bool
    {
        try {
            $this->conexion->beginTransaction();

            $sqlTipo = "INSERT INTO REGISTROTIPOINCIDENCIA (tipo, idEquipo, alumno, descripcion) VALUES (:tipo, :idEquipo, :alumno, :descripcion)";
            $consultaTipo = $this->conexion->prepare($sqlTipo);
            $consultaTipo->execute(["tipo" => $tipo, "idEquipo" => $idEquipo, "alumno" => $alumno, "descripcion" => $descripcion]);
            $idTipoIncidencia = (int) $this->conexion->lastInsertId();

            $sqlEstado = "INSERT INTO ESTADO (tipo, prioridad, diagnostico, soluciones) VALUES ('Sin asignar', 'Sin asignar', 'N/A', 'N/A')";
            $consultaEstado = $this->conexion->prepare($sqlEstado);
            $consultaEstado->execute();
            $idEstado = (int) $this->conexion->lastInsertId();

            $sqlIncidencia = "INSERT INTO REGISTROINCIDENCIA (ciSolicitante, idRegistroEspacio, idTipoIncidencia, idEstado) VALUES (:ci, :idRegistroEspacio, :idTipoIncidencia, :idEstado)";
            $consultaIncidencia = $this->conexion->prepare($sqlIncidencia);
            $consultaIncidencia->execute(["ci" => $ciSolicitante, "idRegistroEspacio" => $idRegistroEspacio, "idTipoIncidencia" => $idTipoIncidencia, "idEstado" => $idEstado]);

            $this->conexion->commit();
            return true;

        } catch (PDOException $error) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }

            return false;
        }
    }

    public function modificarIncidencia(int $idIncidencia, string $tipo, ?int $idEquipo, ?string $alumno, string $descripcion): bool
    {
        try {
            $this->conexion->beginTransaction();

            $sqlIncidencia = "SELECT idTipoIncidencia FROM REGISTROINCIDENCIA WHERE id = :idIncidencia";
            $consultaIncidencia = $this->conexion->prepare($sqlIncidencia);
            $consultaIncidencia->execute(["idIncidencia" => $idIncidencia]);
            $incidencia = $consultaIncidencia->fetch(PDO::FETCH_ASSOC);

            if ($incidencia === false) {
                $this->conexion->rollBack();
                return false;
            }

            $sqlTipo = "UPDATE REGISTROTIPOINCIDENCIA SET tipo = :tipo, idEquipo = :idEquipo, alumno = :alumno, descripcion = :descripcion WHERE id = :idTipoIncidencia";
            $consultaTipo = $this->conexion->prepare($sqlTipo);
            $consultaTipo->execute([
                "tipo" => $tipo,
                "idEquipo" => $idEquipo,
                "alumno" => $alumno,
                "descripcion" => $descripcion,
                "idTipoIncidencia" => $incidencia["idTipoIncidencia"]
            ]);

            $this->conexion->commit();
            return true;

        } catch (PDOException $error) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }

            return false;
        }
    }

    public function modificarEstado(string $ciTecnico, int $idIncidencia, string $estado, string $prioridad, string $diagnostico, string $soluciones): bool
    {
        try {
            $this->conexion->beginTransaction();

            $sqlIncidencia = "SELECT idEstado FROM REGISTROINCIDENCIA WHERE id = :idIncidencia";
            $consultaIncidencia = $this->conexion->prepare($sqlIncidencia);
            $consultaIncidencia->execute(["idIncidencia" => $idIncidencia]);
            $incidencia = $consultaIncidencia->fetch(PDO::FETCH_ASSOC);

            if ($incidencia === false) {
                $this->conexion->rollBack();
                return false;
            }

            $sqlEstado = "UPDATE ESTADO SET tipo = :estado, prioridad = :prioridad, diagnostico = :diagnostico, soluciones = :soluciones WHERE id = :idEstado";
            $consultaEstado = $this->conexion->prepare($sqlEstado);
            $consultaEstado->execute([
                "estado" => $estado,
                "prioridad" => $prioridad,
                "diagnostico" => $diagnostico,
                "soluciones" => $soluciones,
                "idEstado" => $incidencia["idEstado"]
            ]);

            $sqlTecnico = "UPDATE REGISTROINCIDENCIA SET ciTecnico = :ciTecnico WHERE id = :idIncidencia";
            $consultaTecnico = $this->conexion->prepare($sqlTecnico);
            $consultaTecnico->execute(["ciTecnico" => $ciTecnico, "idIncidencia" => $idIncidencia]);

            $this->conexion->commit();
            return true;

        } catch (PDOException $error) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }

            return false;
        }
    }

    public function eliminarIncidencia(int $idIncidencia): bool
    {
        try {
            $this->conexion->beginTransaction();

            $sqlIncidencia = "SELECT idTipoIncidencia, idEstado FROM REGISTROINCIDENCIA WHERE id = :idIncidencia";
            $consultaIncidencia = $this->conexion->prepare($sqlIncidencia);
            $consultaIncidencia->execute(["idIncidencia" => $idIncidencia]);
            $incidencia = $consultaIncidencia->fetch(PDO::FETCH_ASSOC);

            if ($incidencia === false) {
                $this->conexion->rollBack();
                return false;
            }

            //Se respeta el orden de las claves foráneas: primero la incidencia y después lo que referenciaba
            $sqlEliminarIncidencia = "DELETE FROM REGISTROINCIDENCIA WHERE id = :idIncidencia";
            $consultaEliminarIncidencia = $this->conexion->prepare($sqlEliminarIncidencia);
            $consultaEliminarIncidencia->execute(["idIncidencia" => $idIncidencia]);

            $sqlTipo = "DELETE FROM REGISTROTIPOINCIDENCIA WHERE id = :idTipoIncidencia";
            $consultaTipo = $this->conexion->prepare($sqlTipo);
            $consultaTipo->execute(["idTipoIncidencia" => $incidencia["idTipoIncidencia"]]);

            $sqlEstado = "DELETE FROM ESTADO WHERE id = :idEstado";
            $consultaEstado = $this->conexion->prepare($sqlEstado);
            $consultaEstado->execute(["idEstado" => $incidencia["idEstado"]]);

            $this->conexion->commit();
            return true;

        } catch (PDOException $error) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }

            return false;
        }
    }
}