<?php

/**
 * Recupera los equipos registrados en el sistema, para poblar las listas
 * desplegables de los formularios.
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
     * Obtiene todos los equipos registrados, sin filtros ni orden definido. La
     * consulta usa SELECT *, por lo que devuelve todas las columnas de la tabla
     * EQUIPO tal como esté definida en la base de datos.
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