<?php

class RegistroTipoIncidencia {

    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function registrarTipoIncidencia($tipo, $idEquipo, $nombreAlumno, $descripcion) {

        $sql = "INSERT INTO REGISTROTIPOINCIDENCIA 
                (tipo, idEquipo, alumno, descripcion)
                VALUES (:tipo, :idEquipo, :nombreAlumno, :descripcion)";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bindParam(":tipo", $tipo);
        $stmt->bindParam(":idEquipo", $idEquipo);
        $stmt->bindParam(":nombreAlumno", $nombreAlumno); 
        $stmt->bindParam(":descripcion", $descripcion); 

        $stmt->execute();
        return $this->conexion->lastInsertId();
    }
}