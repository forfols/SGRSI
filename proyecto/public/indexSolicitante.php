<?php

session_start();

if (!isset($_SESSION["ci"])) {
    $mensaje = "Acceso Denegado: Sesión no iniciada";
    header("Location: inicioSesion.php?" . "error=" . $mensaje);
    exit;
}

if (!isset($_SESSION["solicitante"]) || $_SESSION["solicitante"] != true) {
    $mensaje = "Acceso Denegado: Rol incorrecto";
    header("Location: ../app/controlador/cerrarSesion.php?motivo=rol");
    exit;
}

require_once __DIR__ . "/../app/vista/indexSolicitante.php";

?>