<?php

/**
 * Recupera los espacios y grupos disponibles.
 *
 * @class RecibirEspacio
 */
class RecibirEspacio {

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
     * Obtiene todos los espacios registrados, ordenados por tipo y número.
     *
     * @return array Arreglo asociativo con las claves id, tipo y numero.
     */
    public function recibirEspacios() {

        $sql = "SELECT id, tipo, numero
                FROM ESPACIO
                ORDER BY tipo, numero";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene todos los grupos registrados, ordenados alfabéticamente.
     *
     * @return array Arreglo asociativo con la clave nombre.
     */
    public function recibirGrupos() {

        $sql = "SELECT nombre
                FROM GRUPO
                ORDER BY nombre";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}