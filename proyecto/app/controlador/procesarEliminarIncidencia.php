<?php
require_once __DIR__ . "/../../config/config.php";
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/EliminarIncidencia.php";
require_once RUTA_MODELO . "/VerificarEstado.php";




$idIncidencia = trim($_POST["idIncidencia"] ?? "");
$estado= trim($_POST["estadoIncidencia"] ??"");
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

$verificarEstado = new VerificarEstado($conexion);
$incidencia = $verificarEstado->verificarEstado(
    $idIncidencia
);

$estado = $incidencia["tipo"];
if ($estado != "Sin asignar") {
    $_SESSION["error"] = "No se pudo eliminar la incidencia: La incidencia está siendo procesada por un Técnico";
    header("Location: " . URL_CONTROLADOR . "/cargarIncidenciasSolicitante.php");
    exit;
}

$eliminarIncidencia = new EliminarIncidencia($conexion);

    $resultado = $eliminarIncidencia->eliminarIncidencia(

    $idIncidencia,
);

$conectorPDO->desconectar();

if ($resultado == false) {
    $_SESSION["error"] = "No se pudo eliminar la incidencia";
    header("Location: " . URL_CONTROLADOR . "/cargarIncidenciasSolicitante.php");
    exit;
}

    $_SESSION["mensaje"] = "Se ha eliminado la incidencia";
    header("Location: " . URL_CONTROLADOR . "/cargarIncidenciasSolicitante.php");
    exit;