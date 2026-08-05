<?php

class AccesoDatosUsuario {
    private PDO $conexion;

    /**
     * Constructor parametrizado que recibe una conexión a la base de datos.
     * @param PDO $conexion La conexion a la base de datos. PRECONDICION: No debe ser NULL.
     */
    public function __construct (PDO $conexion) {
        $this->conexion = $conexion;
    }
    /**
     * Busca un usuario por su cédula y determina el rol.
     * @param string $cedula La cedula del usuario sin puntos ni guiones.
     * @return Usuario|null Los datos del usuario, retorna su objeto si existe, null en caso contrario.
     */
    public function buscarUsuario(string $ci): ?Usuario
{
    $sql = "
        SELECT
    u.ci,
    u.contra,
    u.nombre,
    u.activo,

    CASE
        WHEN s.ci IS NOT NULL THEN 1
        ELSE 0
    END AS solicitante,

    CASE
        WHEN t.ci IS NOT NULL THEN 1
        ELSE 0
    END AS tecnico,

    CASE
        WHEN a.ci IS NOT NULL THEN 1
        ELSE 0
    END AS administrador

FROM USUARIO AS u

    LEFT JOIN SOLICITANTE AS s
        ON s.ci = u.ci

    LEFT JOIN TECNICO AS t
        ON t.ci = u.ci

    LEFT JOIN ADMINISTRADOR AS a
        ON a.ci = u.ci

WHERE u.ci = :ci;
    ";

    

    $consulta = $this->conexion->prepare($sql);

    $consulta->execute(["ci" => $ci]);

    $datos = $consulta->fetch(PDO::FETCH_ASSOC);

    $consulta = null;

    if ($datos === false) {
        return null;
    }

    return new Usuario(
        $datos["ci"],
        $datos["contra"],
        $datos["nombre"],
        (bool) $datos["activo"],
        (bool) $datos["solicitante"],
        (bool) $datos["tecnico"],
        (bool) $datos["administrador"]
    );
}

public function estaActivo(string $ci, bool $activo): void
{
    $sql = "
        UPDATE USUARIO
        SET activo = :activo
        WHERE ci = :ci
    ";

    $consulta = $this->conexion->prepare($sql);

    $consulta->execute([
        "activo" => $activo ? 1 : 0,
        "ci" => $ci
    ]);

    $consulta = null;
}

}

?>