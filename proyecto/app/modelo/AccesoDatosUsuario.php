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
    public function buscarUsuario(string $ci): ?Usuario {
        $sql = "
        SELECT
        u.cedula,
                u.claveHash,
                u.sesionActiva,

                CASE
                    WHEN a.cedula IS NOT NULL THEN 1
                    ELSE 0
                END AS administrador,

                CASE
                    WHEN l.cedula IS NOT NULL THEN 1
                    ELSE 0
                END AS logistica

            FROM USUARIO AS u

            LEFT JOIN ADMINISTRADOR AS a
                ON a.cedula = u.cedula

            LEFT JOIN LOGISTICA AS l
                ON l.cedula = u.cedula

            WHERE u.cedula = :cedula
            ";
        foreach ($usuarios as $datos) {
            if ($datos["ci"] === $ci) {
                return new Usuario(
                    $datos["ci"],
                    $datos["contra"],
                    $datos["activo"],
                    $datos["rol"]
                );
            }
        }return null;

    }
}

?>