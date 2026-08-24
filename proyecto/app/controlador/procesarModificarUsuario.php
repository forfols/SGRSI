<?php

/**
 * @file procesarModificarUsuario.php
 * @brief Controlador encargado de procesar la modificación de un usuario por parte del administrador.
 *
 * Valida la petición y los campos recibidos, y delega la actualización en ModificarUsuario.
 */

require_once __DIR__ . "/../../config/config.php";
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/ModificarUsuario.php";

//Comprueba que la solicitud haya sido enviada mediante POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    header("Location: cerrarSesion.php?motivo=peticionIncorrecta");
    exit;
}

//Recupera los datos enviados por el formulario
$ci = trim($_POST["ci"] ?? "");
$nombre = trim($_POST["nombre"] ?? "");
$solicitante = trim($_POST["solicitante"] ?? "");
$tecnico = $_POST["tecnico"] ?? "";
$administrador = $_POST["administrador"] ?? "";
$csrfToken = $_POST["csrfToken"];

//Valida el token CSRF para evitar peticiones falsificadas
if ($csrfToken != $_SESSION["csrfToken"]) {
    http_response_code(403);
    header("Location: cerrarSesion.php?motivo=token");
    exit;
}

//Sección que valida los datos recibidos del formulario
if ($ci === "" || $nombre === "") {
    http_response_code(400);
    $_SESSION["error"] = "No se pudo modificar el usuario: hay campos vacíos";
    header("Location: " . URL_CONTROLADOR . "/cargarUsuarios.php");
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

//Modifica los datos del usuario y sus roles con la información recibida
$modificarUsuario = new ModificarUsuario($conexion);

    $resultado = $modificarUsuario->modificarUsuario(

    $ci,
    $nombre,
    $solicitante,
    $tecnico,
    $administrador
);

$conectorPDO->desconectar();

//Si la modificación falló, se informa el error
if ($resultado == false) {
    http_response_code(500);
    $_SESSION["error"] = "No se pudo modificar el usuario";
    header("Location: " . URL_CONTROLADOR . "/cargarUsuarios.php");
    exit;
}

    //Si todo salió bien, se informa el éxito de la operación
    $_SESSION["mensaje"] = "Se ha modificado el usuario";
    header("Location: " . URL_CONTROLADOR . "/cargarUsuarios.php");
    exit;