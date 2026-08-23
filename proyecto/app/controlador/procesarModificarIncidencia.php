<?php
require_once __DIR__ . "/../../config/config.php";
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/ModificarIncidencia.php";
require_once RUTA_MODELO . "/VerificarEstado.php";


if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    header("Location: cerrarSesion.php?motivo=peticionIncorrecta");
    exit;
}

$idIncidencia = trim($_POST["idIncidencia"] ?? "");
$estado = trim($_POST["estadoIncidencia"] ?? "");

$tipoIncidencia = $_POST["tipoIncidencia"] ?? "";
$idEquipo = $_POST["nroPc"] ?? null;
$nombreAlumno = $_POST["nombreAlumno"] ?? "";
$descripcion = $_POST["descripcion"] ?? "";
$csrfToken = $_POST["csrfToken"];

if ($csrfToken != $_SESSION["csrfToken"]) {
    http_response_code(403);
    header("Location: cerrarSesion.php?motivo=token");
    exit;
}


if ($tipoIncidencia === "PC" && ($nroPc === "" || $nombreAlumno === "")) {
    http_response_code(400);
    $_SESSION["error"] = "No se pudo modificar la incidencia: Se eligio una incidencia sobre PC pero no se asigno un alumno o pc";
    header("Location: " . URL_CONTROLADOR . "/cargarIncidenciasSolicitante.php");
    exit;
} else if ($tipoIncidencia === "" || $descripcion === "") {
    http_response_code(400);
    $_SESSION["error"] = "No se pudo modificar la incidencia: hay campos vacíos";
    header("Location: " . URL_CONTROLADOR . "/cargarIncidenciasSolicitante.php");
    exit;
}

$conectorPDO = new ConectorPDO($_ENV['DB_HOST'] . ":" . $_ENV['DB_PUERTO'], $_ENV['DB_USUARIO'], $_ENV['DB_CLAVE'], $_ENV['DB_NOMBRE']);
$conexion = $conectorPDO->establecerConexion();

if ($conexion === null) {
    http_response_code(500);
    header("Location: cerrarSesion.php?motivo=sinConexion");
    exit;
}


$verificarEstado = new VerificarEstado($conexion);
$incidencia = $verificarEstado->verificarEstado(
    $idIncidencia
);

$estado = $incidencia["tipo"];
if ($estado != "Sin asignar") {
    http_response_code(409);
    $_SESSION["error"] = "No se pudo modificar la incidencia: La incidencia está siendo procesada por un Técnico";
    header("Location: " . URL_CONTROLADOR . "/cargarIncidenciasSolicitante.php");
    exit;
}



$modificarIncidencia = new ModificarIncidencia($conexion);

$resultado = $modificarIncidencia->modificarIncidencia(

    $idIncidencia,
    $tipoIncidencia,
    $idEquipo,
    $nombreAlumno,
    $descripcion
);

$conectorPDO->desconectar();

if ($resultado == false) {
    http_response_code(500);
    $_SESSION["error"] = "No se pudo modificar la incidencia";
    header("Location: " . URL_CONTROLADOR . "/cargarIncidenciasSolicitante.php");
    exit;
}

$_SESSION["mensaje"] = "Se ha modificado la incidencia con éxito";
header("Location: " . URL_CONTROLADOR . "/cargarIncidenciasSolicitante.php");
exit;