<?php

class RegistroEspacio {

    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function registrarEspacio($tipoEspacio, $nroEspacio, $nombreGrupo) {


        $sql = "SELECT id
                FROM ESPACIO
                WHERE tipo = :tipo
                AND numero = :numero";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bindParam(":tipo", $tipoEspacio);
        $stmt->bindParam(":numero", $nroEspacio, PDO::PARAM_INT);

        $stmt->execute();

        $espacio = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($espacio === false) {
            return false;
        }

        $idEspacio = $espacio["id"];


        $sql = "INSERT INTO REGISTROESPACIO
                (idEspacio, nombreGrupo)
                VALUES (:idEspacio, :nombreGrupo)";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bindParam(":idEspacio", $idEspacio, PDO::PARAM_INT);
        $stmt->bindParam(":nombreGrupo", $nombreGrupo);

        $stmt->execute();

        return $this->conexion->lastInsertId();
    }
}