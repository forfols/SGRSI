<?php

/**
 * Crea una fila en REGISTROESPACIO que vincula un espacio físico con un grupo.
 * Antes de insertar, resuelve el identificador del espacio buscándolo por su tipo y número.
 *
 * @class RegistroEspacio
 */
class RegistroEspacio {

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
     * Registra la ocupación de un espacio por parte de un grupo.
     *
     * @param string $tipoEspacio Tipo de espacio (por ejemplo, salón o laboratorio).
     * @param int $nroEspacio Número identificatorio del espacio.
     * @param string $nombreGrupo Nombre del grupo asociado al registro.
     * @return string|false Identificador del registro creado, o FALSE si no existe un espacio con ese tipo y número.
     */
    public function registrarEspacio($tipoEspacio, $nroEspacio, $nombreGrupo) {


        $sql = "SELECT id
                FROM ESPACIO
                WHERE tipo = :tipo
                AND numero = :numero";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bindParam(":tipo", $tipoEspacio);
        $stmt->bindParam(":numero", $nroEspacio, PDO::PARAM_INT);

        $stmt->execute();

        $espacio = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($espacio === false) {
            return false;
        }

        $idEspacio = $espacio["id"];


        $sql = "INSERT INTO REGISTROESPACIO
                (idEspacio, nombreGrupo)
                VALUES (:idEspacio, :nombreGrupo)";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bindParam(":idEspacio", $idEspacio, PDO::PARAM_INT);
        $stmt->bindParam(":nombreGrupo", $nombreGrupo);

        $stmt->execute();

        return $this->conexion->lastInsertId();
    }
}