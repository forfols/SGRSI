<?php
require_once __DIR__ . "/../../config/config.php";
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/ModificarIncidencia.php";
require_once RUTA_MODELO . "/VerificarEstado.php";




$idIncidencia = trim($_POST["idIncidencia"] ?? "");
$estado = $_POST["estado"] ?? "";
$prioridad = $_POST["prioridad"] ?? "";
$diagnostico = $_POST["diagnostico"] ?? "";
$soluciones = $_POST["soluciones"] ?? "";


$conectorPDO = new ConectorPDO($_ENV['DB_HOST'] . ":" . $_ENV['DB_PUERTO'], $_ENV['DB_USUARIO'], $_ENV['DB_CLAVE'], $_ENV['DB_NOMBRE']);
$conexion = $conectorPDO->establecerConexion();

if ($conexion === null) {
    header("Location: cerrarSesion.php?motivo=sinConexion");
    exit;
}

$modificarIncidencia = new ModificarIncidencia($conexion);

$resultado = $modificarIncidencia->modificarIncidencia(

    $idIncidencia, $estado, $prioridad, $diagnostico, $soluciones
);

$conectorPDO->desconectar();

if ($resultado == false) {
    $_SESSION["error"] = "No se pudo modificar el estado de la incidencia";
    header("Location: " . URL_CONTROLADOR . "/cargarIncidenciasTecnico.php");
    exit;
}

$_SESSION["mensaje"] = "El estado de la incidencia se ha modificado";
header("Location: " . URL_CONTROLADOR . "/cargarIncidenciasTecnico.php");
exit;