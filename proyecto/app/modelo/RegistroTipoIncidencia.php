<?php

class RegistroTipoIncidencia {

    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

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