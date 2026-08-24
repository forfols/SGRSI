<?php

/**
 * @file procesarEliminarIncidencia.php
 * @brief Controlador encargado de procesar la eliminación de una incidencia.
 *
 * Verifica que la petición sea válida, que la incidencia no esté siendo
 * procesada por un técnico, y delega la eliminación en EliminarIncidencia.
 */

require_once __DIR__ . "/../../config/config.php";
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/EliminarIncidencia.php";
require_once RUTA_MODELO . "/VerificarEstado.php";

//Comprueba que la solicitud haya sido enviada mediante POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    header("Location: cerrarSesion.php?motivo=peticionIncorrecta");
    exit;
}


//Recupera los datos provenientes del formulario
$idIncidencia = trim($_POST["idIncidencia"] ?? "");
$estado= trim($_POST["estadoIncidencia"] ??"");
$csrfToken = $_POST["csrfToken"];

//Valida el token CSRF para evitar peticiones falsificadas
if ($csrfToken != $_SESSION["csrfToken"]) {
    http_response_code(403);
    header("Location: cerrarSesion.php?motivo=token");
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

//Verifica el estado actual de la incidencia antes de intentar eliminarla
$verificarEstado = new VerificarEstado($conexion);
$incidencia = $verificarEstado->verificarEstado(
    $idIncidencia
);

//Si la incidencia ya está siendo procesada por un técnico, no se permite eliminarla
$estado = $incidencia["tipo"];
if ($estado != "Sin asignar") {
    $_SESSION["error"] = "No se pudo eliminar la incidencia: La incidencia está siendo procesada por un Técnico";
    header("Location: " . URL_CONTROLADOR . "/cargarIncidenciasSolicitante.php");
    exit;
}

//Elimina la incidencia junto con sus registros asociados
$eliminarIncidencia = new EliminarIncidencia($conexion);

    $resultado = $eliminarIncidencia->eliminarIncidencia(

    $idIncidencia,
);

$conectorPDO->desconectar();

//Si la eliminación falló, se informa el error
if ($resultado == false) {
    http_response_code(500);
    $_SESSION["error"] = "No se pudo eliminar la incidencia";
    header("Location: " . URL_CONTROLADOR . "/cargarIncidenciasSolicitante.php");
    exit;
}

    //Si todo salió bien, se informa el éxito de la operación
    $_SESSION["mensaje"] = "Se ha eliminado la incidencia";
    header("Location: " . URL_CONTROLADOR . "/cargarIncidenciasSolicitante.php");
    exit;