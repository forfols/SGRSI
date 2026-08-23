<?php
require_once __DIR__ . "/../../config/config.php";
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/ModificarEstado.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    header("Location: cerrarSesion.php?motivo=peticionIncorrecta");
    exit;
}

$ciTecnico = $_SESSION["ci"] ?? "";
$idIncidencia = trim($_POST["idIncidencia"] ?? "");
$estado = $_POST["estado"] ?? "";
$prioridad = $_POST["prioridad"] ?? "";
$diagnostico = $_POST["diagnostico"] ?? "";
$soluciones = $_POST["soluciones"] ?? "";
$csrfToken = $_POST["csrfToken"];

if ($csrfToken != $_SESSION["csrfToken"]) {
    http_response_code(403);
    header("Location: cerrarSesion.php?motivo=token");
    exit;
}

if ($estado === "" || $prioridad === "" || $diagnostico === "") {
    http_response_code(400);
    $_SESSION["error"] = "No se pudo modificar el estado: hay campos vacíos";
    header("Location: " . URL_CONTROLADOR . "/cargarIncidenciasTecnico.php");
    exit;
}


$conectorPDO = new ConectorPDO($_ENV['DB_HOST'] . ":" . $_ENV['DB_PUERTO'], $_ENV['DB_USUARIO'], $_ENV['DB_CLAVE'], $_ENV['DB_NOMBRE']);
$conexion = $conectorPDO->establecerConexion();

if ($conexion === null) {
    http_response_code(500);
    header("Location: cerrarSesion.php?motivo=sinConexion");
    exit;
}

$modificarEstado = new ModificarEstado($conexion);

$resultado = $modificarEstado->modificarEstado(

    $ciTecnico, $idIncidencia, $estado, $prioridad, $diagnostico, $soluciones
);

$conectorPDO->desconectar();

if ($resultado == false) {
    http_response_code(500);
    $_SESSION["error"] = "No se pudo modificar el estado de la incidencia";
    header("Location: " . URL_CONTROLADOR . "/cargarIncidenciasTecnico.php");
    exit;
}

$_SESSION["mensaje"] = "El estado de la incidencia se ha modificado";
header("Location: " . URL_CONTROLADOR . "/cargarIncidenciasTecnico.php");
exit;