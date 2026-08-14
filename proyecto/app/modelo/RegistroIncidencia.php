<?php

class RegistroIncidencia {

    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function registrarIncidencia($idEspacio, $idTipoIncidencia, $ci, $nombre) {

        $sql = "INSERT INTO REGISTROINCIDENCIA 
                (idEspacio, idTipoIncidencia, nombreSolicitante, ci)
                VALUES (:idEspacio, :idTipoIncidencia, :nombre, :ci)";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bindParam(":idEspacio", $idEspacio);
        $stmt->bindParam(":idTipoIncidencia", $idTipoIncidencia); 
        $stmt->bindParam(":nombre", $nombre); 
        $stmt->bindParam(":ci", $ci); 

        return $stmt->execute();
    }
}