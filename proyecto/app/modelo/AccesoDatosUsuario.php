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
            ci,
            contra,
            nombre,
            rol,
            activo
        FROM USUARIO
        WHERE ci = :ci
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
        $datos["rol"]
    );
}
}

?>