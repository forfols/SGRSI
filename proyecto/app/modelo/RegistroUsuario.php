<?php

class RegistroUsuario {

    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function existeUsuario(string $ci): bool
{
    $sql = "SELECT 1 FROM USUARIO WHERE ci = :ci LIMIT 1";

    $consulta = $this->conexion->prepare($sql);
    $consulta->execute(["ci" => $ci]);

    return $consulta->fetchColumn() !== false;
}


    public function registrarUsuario($ci, $contra, $nombre, $solicitante, $tecnico, $administrador)
{
    $sql = "INSERT INTO USUARIO 
            (ci, contra, nombre)
            VALUES (:ci, :contra, :nombre)";

    $stmt = $this->conexion->prepare($sql);

    $stmt->bindParam(":ci", $ci);
    $stmt->bindParam(":contra", $contra);
    $stmt->bindParam(":nombre", $nombre);

    $stmt->execute();

    if ($solicitante) {
        $sql = "INSERT INTO SOLICITANTE (ci) VALUES (:ci)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute(["ci" => $ci]);
    }

    if ($tecnico) {
        $sql = "INSERT INTO TECNICO (ci) VALUES (:ci)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute(["ci" => $ci]);
    }

    if ($administrador) {
        $sql = "INSERT INTO ADMINISTRADOR (ci) VALUES (:ci)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute(["ci" => $ci]);
    }

    return true;
}




}