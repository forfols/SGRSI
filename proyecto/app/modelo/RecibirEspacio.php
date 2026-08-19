<?php

class RecibirEspacio {

    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function recibirEspacios() {

        $sql = "SELECT id, tipo, numero
                FROM ESPACIO
                ORDER BY tipo, numero";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function recibirGrupos() {

        $sql = "SELECT nombre
                FROM GRUPO
                ORDER BY nombre";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}