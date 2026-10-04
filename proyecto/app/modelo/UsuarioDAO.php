<?php

class UsuarioDAO
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    public function buscarUsuario(string $ci): ?Usuario
    {
        $sql = "
            SELECT
                u.ci,
                u.nombre,
                u.contra,
                u.activo,

                CASE WHEN s.ci IS NOT NULL THEN 1 ELSE 0 END AS solicitante,
                CASE WHEN t.ci IS NOT NULL THEN 1 ELSE 0 END AS tecnico,
                CASE WHEN a.ci IS NOT NULL THEN 1 ELSE 0 END AS administrador

            FROM USUARIO AS u

            LEFT JOIN SOLICITANTE AS s
                ON s.ci = u.ci

            LEFT JOIN TECNICO AS t
                ON t.ci = u.ci

            LEFT JOIN ADMINISTRADOR AS a
                ON a.ci = u.ci

            WHERE u.ci = :ci
        ";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute(["ci" => $ci]);

        $fila = $consulta->fetch(PDO::FETCH_ASSOC);

        $consulta = null;

        if ($fila === false) {
            return null;
        }

        return new Usuario(
            (string) $fila["ci"],
            (string) $fila["nombre"],
            (string) $fila["contra"],
            (bool) $fila["activo"],
            (bool) $fila["solicitante"],
            (bool) $fila["tecnico"],
            (bool) $fila["administrador"]
        );
    }

    public function marcarActivo(string $ci, bool $activo): void
    {
        $consulta = $this->conexion->prepare("UPDATE USUARIO SET activo = :activo WHERE ci = :ci");
        $consulta->execute(["activo" => $activo ? 1 : 0, "ci" => $ci]);

        $consulta = null;
    }

        public function existeUsuario(string $ci): bool
    {
        $consulta = $this->conexion->prepare("SELECT 1 FROM USUARIO WHERE ci = :ci LIMIT 1");
        $consulta->execute(["ci" => $ci]);

        $existe = $consulta->fetchColumn() !== false;

        $consulta = null;

        return $existe;
    }

    public function listarUsuarios(): array
    {
        $sql = "
            SELECT
                u.ci,
                u.nombre,
                u.activo,

                CASE WHEN s.ci IS NOT NULL THEN 1 ELSE 0 END AS solicitante,
                CASE WHEN t.ci IS NOT NULL THEN 1 ELSE 0 END AS tecnico,
                CASE WHEN a.ci IS NOT NULL THEN 1 ELSE 0 END AS administrador

            FROM USUARIO AS u

            LEFT JOIN SOLICITANTE AS s
                ON s.ci = u.ci

            LEFT JOIN TECNICO AS t
                ON t.ci = u.ci

            LEFT JOIN ADMINISTRADOR AS a
                ON a.ci = u.ci
        ";

        $consulta = $this->conexion->query($sql);

        $usuarios = $consulta->fetchAll(PDO::FETCH_ASSOC);

        $consulta = null;

        return $usuarios;
    }

    //Deja al usuario con exactamente los roles indicados (inserta los que faltan y borra los que sobran)
    private function asignarRoles(string $ci, bool $solicitante, bool $tecnico, bool $administrador): void
    {
        $roles = ["SOLICITANTE" => $solicitante, "TECNICO" => $tecnico, "ADMINISTRADOR" => $administrador];

        foreach ($roles as $tabla => $tieneRol) {
            if ($tieneRol) {
                $sql = "INSERT IGNORE INTO $tabla (ci) VALUES (:ci)";
            } else {
                $sql = "DELETE FROM $tabla WHERE ci = :ci";
            }

            $consulta = $this->conexion->prepare($sql);
            $consulta->execute(["ci" => $ci]);
            $consulta = null;
        }
    }

    public function registrarUsuario(string $ci, string $nombre, string $claveHash, bool $solicitante, bool $tecnico, bool $administrador): bool
    {
        try {
            $this->conexion->beginTransaction();

            $sql = "INSERT INTO USUARIO (ci, contra, nombre) VALUES (:ci, :contra, :nombre)";
            $consulta = $this->conexion->prepare($sql);
            $consulta->execute(["ci" => $ci, "contra" => $claveHash, "nombre" => $nombre]);

            $this->asignarRoles($ci, $solicitante, $tecnico, $administrador);

            $this->conexion->commit();
            return true;

        } catch (PDOException $error) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }

            return false;
        }
    }

    public function modificarUsuario(string $ci, string $nombre, bool $solicitante, bool $tecnico, bool $administrador): bool
    {
        try {
            $this->conexion->beginTransaction();

            $sql = "UPDATE USUARIO SET nombre = :nombre WHERE ci = :ci";
            $consulta = $this->conexion->prepare($sql);
            $consulta->execute(["nombre" => $nombre, "ci" => $ci]);

            $this->asignarRoles($ci, $solicitante, $tecnico, $administrador);

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