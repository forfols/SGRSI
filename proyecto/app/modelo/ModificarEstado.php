<?php

/**
 * Clase encargada de modificar el estado asociado a una incidencia.
 * Es la operación que utiliza el técnico para dar seguimiento: reemplaza los valores iniciales por defecto creados por RegistroEstado.
 *
 * @class ModificarEstado
 */
class ModificarEstado
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
}