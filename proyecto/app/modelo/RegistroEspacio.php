<?php

class RegistroEspacio {

    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function registrarEspacio($tipoEspacio, $nroEspacio, $grupo) {

        $sql = "INSERT INTO REGISTROESPACIO 
                (nombreEspacio, nroEspacio, grupo)
                VALUES (:nombreEspacio, :nroEspacio, :grupo)";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bindParam(":nombreEspacio", $tipoEspacio);
        $stmt->bindParam(":nroEspacio", $nroEspacio, PDO::PARAM_INT);
        $stmt->bindParam(":grupo", $grupo); 

        $stmt->execute();
        return $this->conexion->lastInsertId();
    }
}