<?php

class UsuarioDAO
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    // Devuelve [Usuario, bool sesionActiva] o null si no existe
    public function buscarUsuario(string $ci): ?array
    {
        // AJUSTAR: "contra" debe ser el nombre real de la columna de la clave
        $sql = "SELECT ci, nombre, contra, solicitante, tecnico, administrador, activo
                FROM USUARIO WHERE ci = :ci";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute(["ci" => $ci]);
        $fila = $consulta->fetch(PDO::FETCH_ASSOC);
        $consulta = null;

        if ($fila === false) {
            return null;
        }

        $usuario = new Usuario(
            (string) $fila["ci"],
            (string) $fila["nombre"],
            (string) $fila["contra"],
            (bool) $fila["solicitante"],
            (bool) $fila["tecnico"],
            (bool) $fila["administrador"]
        );

        return [$usuario, (bool) $fila["activo"]];
    }

    public function marcarActivo(string $ci, bool $activo): void
    {
        $consulta = $this->conexion->prepare("UPDATE USUARIO SET activo = :activo WHERE ci = :ci");
        $consulta->execute(["activo" => $activo ? 1 : 0, "ci" => $ci]);
        $consulta = null;
    }
}