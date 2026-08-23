<?php

/**
 * Controlador encargado de procesar la modificación de una incidencia por parte del solicitante.
 * Valida la petición y los campos recibidos, verifica que la incidencia aún no haya sido
 * tomada por un técnico, y delega la actualización en ModificarIncidencia.
 */

require_once __DIR__ . "/../../config/config.php";
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/ModificarIncidencia.php";
require_once RUTA_MODELO . "/VerificarEstado.php";


//Comprueba que la solicitud haya sido enviada mediante POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    header("Location: cerrarSesion.php?motivo=peticionIncorrecta");
    exit;
}

//Recupera los datos provenientes del formulario
$idIncidencia = trim($_POST["idIncidencia"] ?? "");
$estado = trim($_POST["estadoIncidencia"] ?? "");

$tipoIncidencia = $_POST["tipoIncidencia"] ?? "";
$idEquipo = $_POST["nroPc"] ?? null;
$nombreAlumno = $_POST["nombreAlumno"] ?? "";
$descripcion = $_POST["descripcion"] ?? "";
$csrfToken = $_POST["csrfToken"];

//Valida el token CSRF para evitar peticiones falsificadas
if ($csrfToken != $_SESSION["csrfToken"]) {
    http_response_code(403);
    header("Location: cerrarSesion.php?motivo=token");
    exit;
}


//Valida que, si la incidencia es sobre una PC, se hayan indicado el equipo y el alumno
if ($tipoIncidencia === "PC" && ($nroPc === "" || $nombreAlumno === "")) {
    http_response_code(400);
    $_SESSION["error"] = "No se pudo modificar la incidencia: Se eligio una incidencia sobre PC pero no se asigno un alumno o pc";
    header("Location: " . URL_CONTROLADOR . "/cargarIncidenciasSolicitante.php");
    exit;
} else if ($tipoIncidencia === "" || $descripcion === "") {
    //Valida que los campos obligatorios hayan sido completados
    http_response_code(400);
    $_SESSION["error"] = "No se pudo modificar la incidencia: hay campos vacíos";
    header("Location: " . URL_CONTROLADOR . "/cargarIncidenciasSolicitante.php");
    exit;
}

//Establece la conexión a la base de datos utilizando las credenciales del entorno
$conectorPDO = new ConectorPDO($_ENV['DB_HOST'] . ":" . $_ENV['DB_PUERTO'], $_ENV['DB_USUARIO'], $_ENV['DB_CLAVE'], $_ENV['DB_NOMBRE']);
$conexion = $conectorPDO->establecerConexion();

//Si la conexión falló, se cierra la sesión indicando el motivo
if ($conexion === null) {
    http_response_code(500);
    header("Location: cerrarSesion.php?motivo=sinConexion");
    exit;
}


//Verifica el estado actual de la incidencia antes de intentar modificarla
$verificarEstado = new VerificarEstado($conexion);
$incidencia = $verificarEstado->verificarEstado(
    $idIncidencia
);

//Si la incidencia ya está siendo procesada por un técnico, no se permite modificarla
$estado = $incidencia["tipo"];
if ($estado != "Sin asignar") {
    http_response_code(409);
    $_SESSION["error"] = "No se pudo modificar la incidencia: La incidencia está siendo procesada por un Técnico";
    header("Location: " . URL_CONTROLADOR . "/cargarIncidenciasSolicitante.php");
    exit;
}



//Modifica los datos de la incidencia con la información recibida
$modificarIncidencia = new ModificarIncidencia($conexion);

$resultado = $modificarIncidencia->modificarIncidencia(

    $idIncidencia,
    $tipoIncidencia,
    $idEquipo,
    $nombreAlumno,
    $descripcion
);

$conectorPDO->desconectar();

//Si la modificación falló, se informa el error
if ($resultado == false) {
    http_response_code(500);
    $_SESSION["error"] = "No se pudo modificar la incidencia";
    header("Location: " . URL_CONTROLADOR . "/cargarIncidenciasSolicitante.php");
    exit;
}

//Si todo salió bien, se informa el éxito de la operación
$_SESSION["mensaje"] = "Se ha modificado la incidencia con éxito";
header("Location: " . URL_CONTROLADOR . "/cargarIncidenciasSolicitante.php");
exit;