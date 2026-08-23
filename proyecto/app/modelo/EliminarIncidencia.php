<?php

/**
 * Elimina una incidencia junto con su tipo de incidencia y su estado.
 * La eliminación respeta el orden de las claves foráneas: primero se borra la fila
 * de REGISTROINCIDENCIA y luego los registros a los que referenciaba.
 *
 * @class EliminarIncidencia
 */
class EliminarIncidencia
{
    /** Conexión activa a la base de datos. */
    private PDO $conexion;

    /**
     * Constructor parametrizado que recibe una conexión a la base de datos.
     *
     * @param PDO $conexion Conexión a la base de datos.
     */
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
}