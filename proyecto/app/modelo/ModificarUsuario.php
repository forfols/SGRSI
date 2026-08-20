<?php

/**
 * Clase encargada de modificar los datos
 * relacionados con usuarios del sistema.
 */
class ModificarUsuario
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
     * Modifica los datos de un usuario y sus roles.
     *
     * @param string $ci Cédula del usuario a modificar.
     * @param string $nombre Nuevo nombre.
     * @param bool $solicitante Indica si tendrá el rol solicitante.
     * @param bool $tecnico Indica si tendrá el rol técnico.
     * @param bool $administrador Indica si tendrá el rol administrador.
     *
     * @return bool TRUE si la modificación se realiza correctamente,
     * FALSE en caso contrario.
     */
    public function modificarUsuario(
        string $ci,
        string $nombre,
        bool $solicitante,
        bool $tecnico,
        bool $administrador
    ): bool {

        try {
            $this->conexion->beginTransaction();

            $sqlUsuario = "
                UPDATE USUARIO
                SET nombre = :nombre
                WHERE ci = :ci
            ";

            $consultaUsuario = $this->conexion->prepare($sqlUsuario);

            $consultaUsuario->execute([
                "nombre" => $nombre,
                "ci" => $ci
            ]);

            $sqlSolicitante = "
                DELETE FROM SOLICITANTE
                WHERE ci = :ci
            ";

            $consultaSolicitante =
                $this->conexion->prepare($sqlSolicitante);

            $consultaSolicitante->execute([
                "ci" => $ci
            ]);


            $sqlTecnico = "
                DELETE FROM TECNICO
                WHERE ci = :ci
            ";

            $consultaTecnico =
                $this->conexion->prepare($sqlTecnico);

            $consultaTecnico->execute([
                "ci" => $ci
            ]);


            $sqlAdministrador = "
                DELETE FROM ADMINISTRADOR
                WHERE ci = :ci
            ";

            $consultaAdministrador =
                $this->conexion->prepare($sqlAdministrador);

            $consultaAdministrador->execute([
                "ci" => $ci
            ]);

            if ($solicitante) {

                $sql = "
                    INSERT INTO SOLICITANTE (ci)
                    VALUES (:ci)
                ";

                $consulta = $this->conexion->prepare($sql);

                $consulta->execute([
                    "ci" => $ci
                ]);
            }


            if ($tecnico) {

                $sql = "
                    INSERT INTO TECNICO (ci)
                    VALUES (:ci)
                ";

                $consulta = $this->conexion->prepare($sql);

                $consulta->execute([
                    "ci" => $ci
                ]);
            }


            if ($administrador) {

                $sql = "
                    INSERT INTO ADMINISTRADOR (ci)
                    VALUES (:ci)
                ";

                $consulta = $this->conexion->prepare($sql);

                $consulta->execute([
                    "ci" => $ci
                ]);
            }

            $this->conexion->commit();

            return true;


        } catch (PDOException $error) {

            // Si ocurrió un error, deshacer todos
            // los cambios realizados durante la transacción.
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }

            return false;
        }
    }
}