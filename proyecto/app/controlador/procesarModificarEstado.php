<?php
require_once __DIR__ . "/../../config/config.php";
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/ModificarEstado.php";



$ciTecnico = $_SESSION["ci"] ?? "";
$idIncidencia = trim($_POST["idIncidencia"] ?? "");
$estado = $_POST["estado"] ?? "";
$prioridad = $_POST["prioridad"] ?? "";
$diagnostico = $_POST["diagnostico"] ?? "";
$soluciones = $_POST["soluciones"] ?? "";
$csrfToken = $_POST["csrfToken"];
if ($csrfToken != $_SESSION["csrfToken"]) {
    header("Location: cerrarSesion.php?motivo=token");
    exit;
}

$conectorPDO = new ConectorPDO($_ENV['DB_HOST'] . ":" . $_ENV['DB_PUERTO'], $_ENV['DB_USUARIO'], $_ENV['DB_CLAVE'], $_ENV['DB_NOMBRE']);
$conexion = $conectorPDO->establecerConexion();

if ($conexion === null) {
    header("Location: cerrarSesion.php?motivo=sinConexion");
    exit;
}

$modificarEstado = new ModificarEstado($conexion);

$resultado = $modificarEstado->modificarEstado(

    $ciTecnico, $idIncidencia, $estado, $prioridad, $diagnostico, $soluciones
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