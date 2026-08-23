<?php

/**
 * Crea la fila de ESTADO que acompaña a toda incidencia nueva. El estado se
 * inserta con valores por defecto ("Sin asignar" / "N/A") y luego lo completa
 * el técnico mediante ModificarEstado.
 *
 * @class RegistroEstado
 */
class RegistroEstado {

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
     * Registra un estado con los valores iniciales por defecto.
     *
     * @return string Identificador del estado creado, necesario para vincularlo
     *         con la incidencia.
     */
    public function registrarEstado() {

        $sql = "INSERT INTO ESTADO (tipo, prioridad, diagnostico, soluciones) VALUES ('Sin asignar', 'Sin asignar', 'N/A', 'N/A')";    

        $stmt = $this->conexion->prepare($sql);


        //return $stmt->execute();
        $stmt->execute();
        return $this->conexion->lastInsertId();
    }
}