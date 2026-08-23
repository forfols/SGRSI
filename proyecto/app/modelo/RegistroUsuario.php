<?php

/**
 * Registra nuevos usuarios en la base de datos junto con sus roles. Los roles
 * se modelan como tablas separadas (SOLICITANTE, TECNICO, ADMINISTRADOR) que
 * referencian la cédula del usuario.
 *
 * @class RegistroUsuario
 */
class RegistroUsuario {

    /** Conexión activa a la base de datos. */
    private $conexion;

    /**
     * Constructor parametrizado.
     *
     * @param PDO $conexion Conexión a la base de datos.
     */
    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    /**
     * Verifica si ya existe un usuario con la cédula indicada.
     *
     * @param string $ci Cédula a verificar.
     * @return bool TRUE si el usuario ya está registrado, FALSE en caso contrario.
     */
    public function existeUsuario(string $ci): bool
{
    $sql = "SELECT 1 FROM USUARIO WHERE ci = :ci LIMIT 1";

    $consulta = $this->conexion->prepare($sql);
    $consulta->execute(["ci" => $ci]);

    return $consulta->fetchColumn() !== false;
}

    /**
     * Registra un nuevo usuario y le asigna los roles indicados. Inserta primero
     * la fila en USUARIO y luego agrega la cédula a cada tabla de rol según los
     * parámetros recibidos. La contraseña debe llegar ya hasheada, porque la
     * autenticación la valida con password_verify(). Las inserciones no se
     * ejecutan dentro de una transacción, por lo que un fallo al asignar un rol
     * deja el usuario creado sin él.
     *
     * @param string $ci Cédula del nuevo usuario.
     * @param string $contra Contraseña del usuario, ya hasheada.
     * @param string $nombre Nombre completo del usuario.
     * @param bool $solicitante TRUE para asignarle el rol solicitante.
     * @param bool $tecnico TRUE para asignarle el rol técnico.
     * @param bool $administrador TRUE para asignarle el rol administrador.
     * @return bool TRUE si el registro se completó.
     */
    public function registrarUsuario($ci, $contra, $nombre, $solicitante, $tecnico, $administrador)
{
    $sql = "INSERT INTO USUARIO 
            (ci, contra, nombre)
            VALUES (:ci, :contra, :nombre)";

    $stmt = $this->conexion->prepare($sql);

    $stmt->bindParam(":ci", $ci);
    $stmt->bindParam(":contra", $contra);
    $stmt->bindParam(":nombre", $nombre);

    $stmt->execute();

    if ($solicitante) {
        $sql = "INSERT INTO SOLICITANTE (ci) VALUES (:ci)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute(["ci" => $ci]);
    }

    if ($tecnico) {
        $sql = "INSERT INTO TECNICO (ci) VALUES (:ci)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute(["ci" => $ci]);
    }

    if ($administrador) {
        $sql = "INSERT INTO ADMINISTRADOR (ci) VALUES (:ci)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute(["ci" => $ci]);
    }

    return true;
}




}