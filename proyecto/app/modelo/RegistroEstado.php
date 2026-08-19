<?php

class RegistroEstado {

    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function registrarEstado() {

        $sql = "INSERT INTO ESTADO (tipo, prioridad, diagnostico, soluciones) VALUES ('Sin asignar', 'Sin asignar', 'N/A', 'N/A')";    

        $stmt = $this->conexion->prepare($sql);


        //return $stmt->execute();
        $stmt->execute();
        return $this->conexion->lastInsertId();
    }
}