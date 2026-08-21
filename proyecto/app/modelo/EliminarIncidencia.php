<?php

class EliminarIncidencia
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

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