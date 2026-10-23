<?php

/**
 * Clase que unifica todas las operaciones de acceso a datos
 * relacionadas con la entidad Espacio (buscar, listar, alta, baja, modificación).
 */
class EspacioDAO {
    private PDO $conexion;

    public function __construct(PDO $conexion) {
        $this->conexion = $conexion;
    }

    public function buscarEspacio(int $id): array|false {
        $sql = "SELECT id, tipo, numero FROM ESPACIO WHERE id = :id";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute(["id" => $id]);

        $espacio = $consulta->fetch(PDO::FETCH_ASSOC);

        $consulta = null;

        return $espacio;
    }

    public function listarEspacios(): array {
        $sql = "SELECT id, tipo, numero FROM ESPACIO ORDER BY tipo, numero";

        $consulta = $this->conexion->query($sql);

        $espacios = $consulta->fetchAll(PDO::FETCH_ASSOC);

        $consulta = null;

        return $espacios;
    }

    public function registrarEspacio(string $tipo, string $numero): bool {
        try {
            $sql = "INSERT INTO ESPACIO (tipo, numero) VALUES (:tipo, :numero)";

            $consulta = $this->conexion->prepare($sql);
            $consulta->execute(["tipo" => $tipo, "numero" => $numero]);

            return true;

        } catch (PDOException $error) {
            return false;
        }
    }

    public function modificarEspacio(int $id, string $tipo, string $numero): bool {
        try {
            $sql = "UPDATE ESPACIO SET tipo = :tipo, numero = :numero WHERE id = :id";

            $consulta = $this->conexion->prepare($sql);
            $consulta->execute(["tipo" => $tipo, "numero" => $numero, "id" => $id]);

            return true;

        } catch (PDOException $error) {
            return false;
        }
    }

    public function eliminarEspacio(int $id): bool {
        try {
            $sql = "DELETE FROM ESPACIO WHERE id = :id";

            $consulta = $this->conexion->prepare($sql);
            $consulta->execute(["id" => $id]);

            return true;

        } catch (PDOException $error) {
            return false;
        }
    }

        public function registrarUso(string $tipo, int $numero, string $grupo): ?int
    {
        try {
            $consulta = $this->conexion->prepare("SELECT id FROM ESPACIO WHERE tipo = :tipo AND numero = :numero");
            $consulta->execute(["tipo" => $tipo, "numero" => $numero]);
            $espacio = $consulta->fetch(PDO::FETCH_ASSOC);

            $consulta = null;

            if ($espacio === false) {
                return null;
            }

            $insertar = $this->conexion->prepare("INSERT INTO REGISTROESPACIO (idEspacio, nombreGrupo) VALUES (:idEspacio, :nombreGrupo)");
            $insertar->execute(["idEspacio" => $espacio["id"], "nombreGrupo" => $grupo]);

            return (int) $this->conexion->lastInsertId();

        } catch (PDOException $error) {
            return null;
        }
    }
}