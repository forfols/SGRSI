<?php

/**
 * Clase que simula una recuperación de credenciales correspondientes a la base de datos.
 */
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
                u.activo,
                u.rol,

                CASE
                    WHEN a.cedula IS NOT NULL THEN TRUE
                    ELSE FALSE
                END AS administrador,

                CASE
                    WHEN s.cedula IS NOT NULL THEN TRUE
                    ELSE FALSE
                END AS solicitante

                CASE
                    WHEN t.cedula IS NOT NULL THEN TRUE
                    ELSE FALSE
                END AS tecnico

            FROM USUARIO AS u

            LEFT JOIN administrador AS a
                ON a.cedula = u.cedula

            LEFT JOIN solicitante AS s
                ON s.cedula = u.cedula

            LEFT JOIN tecnico AS t
                ON t.cedula = u.cedula

            WHERE u.cedula = :cedula
        ";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute(["ci" => $ci]);

        $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

        //Una vez usada la consulta, desconectar el objeto PDOStatement. https://www.php.net/manual/en/pdo.connections.php
        $consulta = null;

        if ($usuario === false) {
            return null;
        }

        return new Usuario(
            $usuario["ci"],
            $usuario["contra"],
            (bool) $usuario["activo"],
            (bool) $usuario["administrador"],
            (bool) $usuario["solicitante"],
            (bool) $usuario["tecnico"]
        );
    }

}

?>
