<?php

class RegistroIncidencia {

    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function registrarIncidencia($idRegistroEspacio, $idTipoIncidencia, $ciSolicitante, $idEstado) {

        $sql = "INSERT INTO REGISTROINCIDENCIA 
                (ciSolicitante, idRegistroEspacio, idTipoIncidencia, idEstado)
                VALUES (:ciSolicitante, :idRegistroEspacio, :idTipoIncidencia, :idEstado)";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bindParam(":ciSolicitante", $ciSolicitante);
        $stmt->bindParam(":idRegistroEspacio", $idRegistroEspacio, PDO::PARAM_INT); 
        $stmt->bindParam(":idTipoIncidencia", $idTipoIncidencia, PDO::PARAM_INT); 
        $stmt->bindParam(":idEstado", $idEstado, PDO::PARAM_INT); 

        return $stmt->execute();
    }
}