<?php

/**
 * Controlador encargado de procesar la modificación del estado de una incidencia.
 * Valida la petición y los campos recibidos, y delega la actualización en ModificarEstado.
 */

require_once __DIR__ . "/../../config/config.php";
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/ModificarEstado.php";

//Comprueba que la solicitud haya sido enviada mediante POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    header("Location: cerrarSesion.php?motivo=peticionIncorrecta");
    exit;
}

//Recupera los datos provenientes del formulario y de la sesión del técnico
$ciTecnico = $_SESSION["ci"] ?? "";
$idIncidencia = trim($_POST["idIncidencia"] ?? "");
$estado = $_POST["estado"] ?? "";
$prioridad = $_POST["prioridad"] ?? "";
$diagnostico = $_POST["diagnostico"] ?? "";
$soluciones = $_POST["soluciones"] ?? "";
$csrfToken = $_POST["csrfToken"];

//Valida el token CSRF para evitar peticiones falsificadas
if ($csrfToken != $_SESSION["csrfToken"]) {
    http_response_code(403);
    header("Location: cerrarSesion.php?motivo=token");
    exit;
}

//Valida que los campos obligatorios hayan sido completados
if ($estado === "" || $prioridad === "" || $diagnostico === "") {
    http_response_code(400);
    $_SESSION["error"] = "No se pudo modificar el estado: hay campos vacíos";
    header("Location: " . URL_CONTROLADOR . "/cargarIncidenciasTecnico.php");
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

//Modifica el estado de la incidencia con los datos recibidos
$modificarEstado = new ModificarEstado($conexion);

$resultado = $modificarEstado->modificarEstado(

    $ciTecnico, $idIncidencia, $estado, $prioridad, $diagnostico, $soluciones
);

$conectorPDO->desconectar();

//Si la modificación falló, se informa el error
if ($resultado == false) {
    http_response_code(500);
    $_SESSION["error"] = "No se pudo modificar el estado de la incidencia";
    header("Location: " . URL_CONTROLADOR . "/cargarIncidenciasTecnico.php");
    exit;
}

//Si todo salió bien, se informa el éxito de la operación
$_SESSION["mensaje"] = "El estado de la incidencia se ha modificado";
header("Location: " . URL_CONTROLADOR . "/cargarIncidenciasTecnico.php");
exit;