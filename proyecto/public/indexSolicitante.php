<?php

session_start();

if (!isset($_SESSION["ci"])) {
    $mensaje = "Acceso Denegado: Sesión no iniciada";
    header("Location: inicioSesion.php?" . "error=" . $mensaje);
    exit;
}

if ( !isset($_SESSION["rol"]) || $_SESSION["rol"] !== "Solicitante" ) {
    $mensaje = "Acceso Denegado: Rol incorrecto";
    header("Location: inicioSesion.php?" . "error=" . $mensaje);
    exit;
}

require_once __DIR__ . "/../app/vista/indexSolicitante.php";

?>