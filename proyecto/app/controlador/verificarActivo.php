<?php

/**
 * Controlador encargado de mostrar el motivo por el cual se rechazó un intento
 * de inicio de sesión cuando el usuario ya tiene una sesión activa en otro dispositivo.
 */

require_once __DIR__ . "/../../config/config.php";

$motivo = $_GET["motivo"] ?? "";

//Si el motivo indica que la sesión ya está activa, define el mensaje de error correspondiente
if($motivo == "sesionActiva"){
    http_response_code(409);
    $_SESSION["error"] = "Acceso Denegado: La sesión está activa en otro dispositivo";
}

//Redirige siempre a la pantalla de inicio de sesión
header("Location:" . URL_PUBLIC . "/inicioSesion.php");
exit;