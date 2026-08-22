<?php

class RecibirEquipo {

    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function recibirEquipos() {

        $sql = "SELECT * FROM EQUIPO";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}