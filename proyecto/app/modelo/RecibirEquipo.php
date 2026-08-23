<?php

/**
 * Recupera los equipos registrados en el sistema.
 *
 * @class RecibirEquipo
 */
class RecibirEquipo {

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
     * Obtiene todos los equipos registrados, sin filtros ni orden definido.
     *
     * @return array Arreglo asociativo con una fila por equipo.
     */
    public function recibirEquipos() {

        $sql = "SELECT * FROM EQUIPO";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}