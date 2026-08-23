<?php

/**
 * Controlador encargado de procesar el registro de una nueva incidencia.
 * Valida la petición y los campos recibidos, y crea en orden el tipo de
 * incidencia, el estado inicial y la incidencia que los vincula.
 */

require_once __DIR__ . "/../../config/config.php";
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/RegistroTipoIncidencia.php";
require_once RUTA_MODELO . "/RegistroIncidencia.php";
require_once RUTA_MODELO . "/RegistroEstado.php";

//Comprueba que el formulario haya sido enviado mediante POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    header("Location: cerrarSesion.php?motivo=peticionIncorrecta");
    exit;
}

//Recupera las credenciales provenientes del formulario
$tipoEspacio = $_POST["tipoEspacio"];

$tipo = trim($_POST["tipo"] ?? "");
$idEquipo = $_POST["nroPc"] ?? null;
$nombreAlumno = $_POST["nombreAlumno"] ?? null;
$descripcion = $_POST["descripcion"] ?? "";
$idRegistroEspacio = $_POST["idRegistroEspacio"] ?? null;
$csrfToken = $_POST["csrfToken"];

//Valida el token CSRF para evitar peticiones falsificadas
if ($csrfToken != $_SESSION["csrfToken"]) {
    http_response_code(403);
    header("Location: cerrarSesion.php?motivo=token");
    exit;
}

//Valida que, si la incidencia es sobre una PC, se hayan indicado el equipo y el alumno
if ($tipo === "PC" && ($idEquipo === "" || $nombreAlumno === "")) {
    http_response_code(400);
    $_SESSION["error"] = "No se pudo registrar la incidencia: Se eligio una incidencia sobre PC pero no se asigno un alumno o pc";
    header("Location: solicitanteRegistroIncidencias.php?idRegistroEspacio=" . $idRegistroEspacio . "&tipoEspacio=". $tipoEspacio);
    exit;
} else if ($tipo === "" || $descripcion === "") {
    //Valida que los campos obligatorios hayan sido completados
    http_response_code(400);
    $_SESSION["error"] = "No se pudo registrar la incidencia: hay campos vacíos";
    header("Location: solicitanteRegistroIncidencias.php?idRegistroEspacio=" . $idRegistroEspacio . "&tipoEspacio=". $tipoEspacio);
    exit;
}

//Si la incidencia no es sobre una PC, se descartan el equipo y el alumno
if ($tipo === "Otros") {
    $idEquipo=null;
    $nombreAlumno=null;
} 

//Establece la conexión a la base de datos utilizando las credenciales del entorno
$conectorPDO = new ConectorPDO ($_ENV['DB_HOST'] . ":" . $_ENV['DB_PUERTO'], $_ENV['DB_USUARIO'], $_ENV['DB_CLAVE'], $_ENV['DB_NOMBRE']);
$conexion = $conectorPDO->establecerConexion();

//Si la conexión falló, se cierra la sesión indicando el motivo
if ($conexion === null) {
    http_response_code(500);
    header("Location: cerrarSesion.php?motivo=sinConexion");
    exit;
}

//Registra el tipo y detalle de la incidencia reportada
$registroTipoIncidencia = new RegistroTipoIncidencia($conexion);

$idTipoIncidencia = $registroTipoIncidencia->registrarTipoIncidencia(
    $tipo, $idEquipo, $nombreAlumno, $descripcion
);

//Registra el estado inicial de la incidencia, con valores por defecto
$registroEstado = new RegistroEstado($conexion);
$idEstado = $registroEstado->registrarEstado();

//Registra la incidencia vinculando el espacio, el tipo, el solicitante y el estado creados
$registroIncidencia = new RegistroIncidencia($conexion);
$registroIncidencia->registrarIncidencia(
    $idRegistroEspacio,
    $idTipoIncidencia,
    $_SESSION["ci"],
    $idEstado
);


$conectorPDO->desconectar();
$_SESSION["mensaje"] = "Se registró la incidencia";
//Redirige al registro de incidencias del mismo espacio
header("Location: solicitanteRegistroIncidencias.php?idRegistroEspacio=" . $idRegistroEspacio . "&tipoEspacio=". $tipoEspacio);
exit;

?>