<?php

/**
 * Clase encargada de modificar los datos
 * relacionados con incidencias del sistema.
 */
class ModificarIncidencia
{
    private PDO $conexion;

    /**
     * Constructor parametrizado que recibe una conexión
     * a la base de datos.
     *
     * @param PDO $conexion Conexión a la base de datos.
     */
    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
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
}