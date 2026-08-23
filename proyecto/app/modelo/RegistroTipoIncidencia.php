<?php

/**
 * Registra los datos específicos del problema reportado:
 * tipo de falla, equipo afectado, alumno involucrado y descripción.
 * Devuelve el identificador para asociarlo a la incidencia.
 *
 * @class RegistroTipoIncidencia
 */
class RegistroTipoIncidencia {

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
     * Registra el tipo y el detalle de una incidencia.
     *
     * @param string $tipo Tipo de incidencia reportada.
     * @param string $nroPc Número de PC o equipo afectado.
     * @param string $nombreAlumno Nombre del alumno involucrado.
     * @param string $descripcion Descripción del problema.
     * @return string Identificador del registro creado.
     */
    public function registrarTipoIncidencia($tipo, $nroPc, $nombreAlumno, $descripcion) {

        $sql = "INSERT INTO REGISTROTIPOINCIDENCIA 
                (tipo, nroPc, alumno, descripcion)
                VALUES (:tipo, :nroPc, :nombreAlumno, :descripcion)";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bindParam(":tipo", $tipo);
        $stmt->bindParam(":nroPc", $nroPc);
        $stmt->bindParam(":nombreAlumno", $nombreAlumno); 
        $stmt->bindParam(":descripcion", $descripcion); 

        //return $stmt->execute();
        $stmt->execute();
        return $this->conexion->lastInsertId();
    }
}