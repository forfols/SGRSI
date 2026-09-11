<?php

/**
 * Clase que unifica todas las operaciones de acceso a datos
 * relacionadas con la entidad Usuario (buscar, listar, alta, baja, modificación).
 */
class IncidenciaDAO {
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    /**
     * Elimina una incidencia y los registros asociados a ella.
     * Recupera los identificadores del tipo de incidencia y del estado antes de borrar la incidencia, para poder eliminarlos a continuación.
     * Toda la operación se ejecuta dentro de una transacción, de modo que no queden registros huérfanos.
     * No se elimina el registro de espacio (REGISTROESPACIO) asociado a la incidencia.
     *
     * @param int $idIncidencia ID de la incidencia a eliminar.
     * @return bool TRUE si la eliminación se realiza correctamente, FALSE si la incidencia no existe o si ocurre un error.
     */
    public function eliminarIncidencia(
        int $idIncidencia,
    ): bool {

        try {

            $this->conexion->beginTransaction();

            $sqlIncidencia = "
                SELECT idTipoIncidencia, idEstado
                FROM REGISTROINCIDENCIA
                WHERE id = :idIncidencia";

            $consultaIncidencia =$this->conexion->prepare($sqlIncidencia);

            $consultaIncidencia->execute(["idIncidencia" => $idIncidencia]);

            $incidencia = $consultaIncidencia->fetch(PDO::FETCH_ASSOC);

            if (!$incidencia) {

                $this->conexion->rollBack();

                return false;
            }

            $idTipoIncidencia =$incidencia["idTipoIncidencia"];

            $idEstado = $incidencia["idEstado"];

            $sqlIncidencia = "
                DELETE FROM REGISTROINCIDENCIA
                WHERE id = :idIncidencia";

            $consultaIncidencia = $this->conexion->prepare($sqlIncidencia);

            $consultaIncidencia->execute(["idIncidencia" => $idIncidencia]);


            $sqlTipoIncidencia = "
                DELETE FROM REGISTROTIPOINCIDENCIA
                WHERE id = :idTipoIncidencia";

            $consultaTipoIncidencia = $this->conexion->prepare($sqlTipoIncidencia);

            $consultaTipoIncidencia->execute(["idTipoIncidencia" => $idTipoIncidencia]);

            $sqlEstado = "
                DELETE FROM ESTADO
                WHERE id = :idEstado";

            $consultaEstado = $this->conexion->prepare($sqlEstado);

            $consultaEstado->execute(["idEstado" => $idEstado]);


            $this->conexion->commit();

            return true;


        } catch (PDOException $error) {

            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }

            return false;
        }
    }

     /**
     * Modifica los datos de una incidencia.
     *
     * @param int $idIncidencia ID de la incidencia a modificar.
     * @param string $tipoIncidencia Nuevo tipo de incidencia ('PC' u 'Otros').
     * @param int|null $idEquipo ID del equipo de la tabla EQUIPO (o null).
     * @param string|null $nombreAlumno Nombre del alumno (o null).
     * @param string $descripcion Nueva descripción de la incidencia.
     *
     * @return bool TRUE si la modificación se realiza correctamente,
     * FALSE en caso contrario.
     */
    public function modificarIncidencia(int $idIncidencia, string $tipoIncidencia, ?int $idEquipo, ?string $nombreAlumno, string $descripcion
    ): bool {

        try {

            $this->conexion->beginTransaction();

            $sqlIncidencia = "
                SELECT idTipoIncidencia
                FROM REGISTROINCIDENCIA
                WHERE id = :idIncidencia";

            $consultaIncidencia = $this->conexion->prepare($sqlIncidencia);
            $consultaIncidencia->execute(["idIncidencia" => $idIncidencia]);
            $incidencia = $consultaIncidencia->fetch(PDO::FETCH_ASSOC);

            if (!$incidencia) {
                $this->conexion->rollBack();
                return false;
            }

            $idTipoIncidencia = $incidencia["idTipoIncidencia"];

            $sqlTipoIncidencia = "
                UPDATE REGISTROTIPOINCIDENCIA
                SET tipo = :tipo,
                    idEquipo = :idEquipo,
                    alumno = :alumno,
                    descripcion = :descripcion
                WHERE id = :idTipoIncidencia";

            $consultaTipoIncidencia = $this->conexion->prepare($sqlTipoIncidencia);

            $consultaTipoIncidencia->bindParam(":tipo", $tipoIncidencia);
            $consultaTipoIncidencia->bindParam(":idEquipo", $idEquipo);
            $consultaTipoIncidencia->bindParam(":alumno", $nombreAlumno);
            $consultaTipoIncidencia->bindParam(":descripcion", $descripcion);
            $consultaTipoIncidencia->bindParam(":idTipoIncidencia", $idTipoIncidencia, PDO::PARAM_INT);

            $consultaTipoIncidencia->execute();

            $this->conexion->commit();

            return true;

        } catch (PDOException $error) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }

            return false;
        }
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
    public function registrarIncidencia($idRegistroEspacio, $idTipoIncidencia, $ciSolicitante, $idEstado){

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
    public function listarIncidencia(): array{
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

        LEFT JOIN EQUIPO eq
            ON rti.idEquipo = eq.id

        INNER JOIN ESTADO es
            ON ri.idEstado = es.id;";

        $consulta = $this->conexion->query($sql);

        $incidencias = $consulta->fetchAll(PDO::FETCH_ASSOC);

        $consulta = null;

        return $incidencias;
    }

     /**
     * Registra el tipo y el detalle de una incidencia.
     *
     * @param string $tipo Tipo de incidencia reportada.
     * @param string $idEquipo id te la tabla EQUIPO.
     * @param string $nombreAlumno Nombre del alumno involucrado.
     * @param string $descripcion Descripción del problema.
     * @return string Identificador del registro creado.
     */
    public function registrarTipoIncidencia($tipo, $idEquipo, $nombreAlumno, $descripcion) {

        $sql = "INSERT INTO REGISTROTIPOINCIDENCIA 
                (tipo, idEquipo, alumno, descripcion)
                VALUES (:tipo, :idEquipo, :nombreAlumno, :descripcion)";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bindParam(":tipo", $tipo);
        $stmt->bindParam(":idEquipo", $idEquipo);
        $stmt->bindParam(":nombreAlumno", $nombreAlumno); 
        $stmt->bindParam(":descripcion", $descripcion); 

        //return $stmt->execute();
        $stmt->execute();
        return $this->conexion->lastInsertId();
    }

     /**
     * Modifica el estado de una incidencia. Obtiene el identificador del estado vinculado a la incidencia y actualiza la fila correspondiente de la tabla ESTADO.
     * Toda la operación se ejecuta dentro de una transacción.
     *
     * @param int $idIncidencia ID de la incidencia cuyo estado se modifica.
     * @param string $estado Nuevo tipo de estado de la incidencia.
     * @param string $prioridad Nueva prioridad asignada.
     * @param string $diagnostico Diagnóstico elaborado por el técnico.
     * @param string $soluciones Soluciones aplicadas o propuestas.
     * @return bool TRUE si la modificación se realiza correctamente, FALSE en caso contrario o si la incidencia no existe.
     */
    public function modificarEstado(string $ciTecnico, int $idIncidencia, string $estado, string $prioridad, string $diagnostico, string $soluciones): bool {

        try {
            $this->conexion->beginTransaction();

            $sqlIncidencia = "
                SELECT idEstado
                FROM REGISTROINCIDENCIA
                WHERE id = :idIncidencia";

            $consultaIncidencia = $this->conexion->prepare($sqlIncidencia);
            $consultaIncidencia->execute(["idIncidencia" => $idIncidencia]);

            $incidencia = $consultaIncidencia->fetch(PDO::FETCH_ASSOC);

            if (!$incidencia) {
                $this->conexion->rollBack();
                return false;
            }

            $idEstado = $incidencia["idEstado"];

            $sqlEstado = "
                UPDATE ESTADO
                SET tipo = :estado,
                    prioridad = :prioridad,
                    diagnostico = :diagnostico,
                    soluciones = :soluciones
                WHERE id = :idEstado";

            $consultaEstado = $this->conexion->prepare($sqlEstado);
            $consultaEstado->execute([
                "estado" => $estado,
                "prioridad" => $prioridad,
                "diagnostico" => $diagnostico,
                "soluciones" => $soluciones,
                "idEstado" => $idEstado
            ]);

            $sqlTecnico = "
                UPDATE REGISTROINCIDENCIA
                SET ciTecnico = :ciTecnico
                WHERE id = :idIncidencia";

            $consultaTecnico = $this->conexion->prepare($sqlTecnico);
            $consultaTecnico->execute([
                "ciTecnico" => $ciTecnico,
                "idIncidencia" => $idIncidencia
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

    /**
     * Obtiene todos los equipos registrados, sin filtros ni orden definido.
     *
     * @return array Arreglo asociativo con una fila por equipo.
     */
    public function recibirEquipos() {

        $sql = "SELECT * FROM EQUIPO";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

        /**
     * Obtiene todos los espacios registrados, ordenados por tipo y número.
     *
     * @return array Arreglo asociativo con las claves id, tipo y numero.
     */
    public function recibirEspacios() {

        $sql = "SELECT id, tipo, numero
                FROM ESPACIO
                ORDER BY tipo, numero";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene todos los grupos registrados, ordenados alfabéticamente.
     *
     * @return array Arreglo asociativo con la clave nombre.
     */
    public function recibirGrupos() {

        $sql = "SELECT nombre
                FROM GRUPO
                ORDER BY nombre";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    /**
     * Registra un estado con los valores iniciales por defecto.
     *
     * @return string Identificador del estado creado, necesario para vincularlo con la incidencia.
     */
    public function registrarEstado() {

        $sql = "INSERT INTO ESTADO (tipo, prioridad, diagnostico, soluciones) VALUES ('Sin asignar', 'Sin asignar', 'N/A', 'N/A')";    

        $stmt = $this->conexion->prepare($sql);


        //return $stmt->execute();
        $stmt->execute();
        return $this->conexion->lastInsertId();
    }

     /**
     * Consulta el estado de una incidencia a partir de su id.
     * El método ejecuta la consulta y guarda el resultado en $incidencia
     *
     * @param int $idIncidencia Identificador de la incidencia a consultar.
     * @return void
     */
    public function verificarEstado($idIncidencia){
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