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
     * @param string $tipoIncidencia Nuevo tipo de incidencia.
     * @param string $nroPc Nuevo número de PC.
     * @param string $nombreAlumno Nuevo nombre del alumno.
     * @param string $descripcion Nueva descripción de la incidencia.
     *
     * @return bool TRUE si la modificación se realiza correctamente,
     * FALSE en caso contrario.
     */
    public function modificarIncidencia(
        int $idIncidencia,
        string $tipoIncidencia,
        string $nroPc,
        string $nombreAlumno,
        string $descripcion
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
                    nroPc = :nroPc,
                    alumno = :alumno,
                    descripcion = :descripcion
                WHERE id = :idTipoIncidencia";

            $consultaTipoIncidencia = $this->conexion->prepare($sqlTipoIncidencia);

            $consultaTipoIncidencia->execute([
                "tipo" => $tipoIncidencia,
                "nroPc" => $nroPc,
                "alumno" => $nombreAlumno,
                "descripcion" => $descripcion,
                "idTipoIncidencia" => $idTipoIncidencia
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