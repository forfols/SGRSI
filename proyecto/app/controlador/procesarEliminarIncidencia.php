<?php
require_once __DIR__ . "/../../config/config.php";
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/EliminarIncidencia.php";
require_once RUTA_MODELO . "/VerificarEstado.php";




$idIncidencia = trim($_POST["idIncidencia"] ?? "");
$estado= trim($_POST["estadoIncidencia"] ??"");



$conectorPDO = new ConectorPDO($_ENV['DB_HOST'] . ":" . $_ENV['DB_PUERTO'], $_ENV['DB_USUARIO'], $_ENV['DB_CLAVE'], $_ENV['DB_NOMBRE']);
$conexion = $conectorPDO->establecerConexion();

    if ($conexion === null) {
    header("Location: cerrarSesion.php?motivo=sinConexion");
    exit;
}

$verificarEstado = new VerificarEstado($conexion);
$incidencia = $verificarEstado->verificarEstado(
    $idIncidencia
);

$estado = $incidencia["tipo"];
if ($estado != "Sin asignar") {
    $_SESSION["error"] = "No se pudo modificar la incidencia: La incidencia está siendo procesada por un Técnico o Administrador";
    header("Location: " . URL_CONTROLADOR . "/cargarIncidencias.php");
    exit;
}

$eliminarIncidencia = new EliminarIncidencia($conexion);

    $resultado = $eliminarIncidencia->eliminarIncidencia(

    $idIncidencia,
);

$conectorPDO->desconectar();

if ($resultado == false) {
    $_SESSION["error"] = "No se pudo eliminar la incidencia";
    header("Location: " . URL_CONTROLADOR . "/cargarIncidencias.php");
    exit;
}

    $_SESSION["mensaje"] = "Se ha eliminado la incidencia";
    header("Location: " . URL_CONTROLADOR . "/cargarIncidencias.php");
    exit;