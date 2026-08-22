<?php

class ModificarEstado
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

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